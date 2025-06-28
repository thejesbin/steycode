<?php
// Include database connection
require_once '../config/conn.php';

// Handle POST request
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get the JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate required fields
if (!isset($data['templateId']) || !isset($data['amount']) || !isset($data['email']) || !isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// PhonePe API credentials (store these in a secure way in production)
$clientId = 'SU2506271525121002988041';
$clientSecret = '08c5c09a-c4f0-4d05-87c9-3bc6a9e0ea76';
$clientVersion = '1';
$environment = 'production'; // 'sandbox' or 'production'

// API endpoints
if ($environment === 'sandbox') {
    $oauthEndpoint = 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token';
    $paymentEndpoint = 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay';
} else {
    $oauthEndpoint = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';
    $paymentEndpoint = 'https://api.phonepe.com/apis/pg/checkout/v2/pay';
}

// Create logs directory if it doesn't exist
$logsDir = '../logs';
if (!file_exists($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Prepare transaction data
$merchantOrderId = 'STEYCOD' . time(); // Unique transaction ID
$amount = $data['amount'] * 100; // Amount in paise (smallest currency unit)
$templateId = $data['templateId'];
$userEmail = $data['email'];
$userName = $data['name'];

// Base URL for redirects
// Use the server's hostname and protocol from the current request for more reliability
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '';
$redirectUrl = $baseUrl . '/api/phonepe-callback.php?txnId=' . $merchantOrderId;

// Insert transaction record in database
$insertQuery = "INSERT INTO transactions (merchant_transaction_id, template_id, amount, customer_email, customer_name, status, created_at) 
                VALUES ('$merchantOrderId', '$templateId', '$amount', '$userEmail', '$userName', 'INITIATED', NOW())";

if (!mysqli_query($conn, $insertQuery)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to create transaction']);
    exit;
}

// Step 1: Get OAuth token
$oauthParams = [
    'client_id' => $clientId,
    'client_version' => $clientVersion,
    'client_secret' => $clientSecret,
    'grant_type' => 'client_credentials'
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $oauthEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($oauthParams),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded'
    ]
]);

$oauthResponse = curl_exec($curl);
$oauthErr = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// Log OAuth API call
$logFile = fopen($logsDir . '/phonepe_oauth_debug.log', 'a');
fwrite($logFile, date('Y-m-d H:i:s') . " - OAuth Request: " . json_encode($oauthParams) . "\n");
fwrite($logFile, date('Y-m-d H:i:s') . " - OAuth Response: " . $oauthResponse . "\n");
fwrite($logFile, date('Y-m-d H:i:s') . " - HTTP Code: " . $httpCode . "\n");
fwrite($logFile, "------------------------------\n");
fclose($logFile);

// Handle OAuth errors
if ($oauthErr || $httpCode >= 400) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Authentication failed: ' . ($oauthErr ? $oauthErr : $oauthResponse)
    ]);
    exit;
}

$tokenData = json_decode($oauthResponse, true);
if (!isset($tokenData['access_token'])) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No access token received']);
    exit;
}

$accessToken = $tokenData['access_token'];

// Prepare payment request payload EXACTLY as in the sample cURL
$paymentPayload = [
    "merchantOrderId" => $merchantOrderId,
    "amount" => $amount,
    "paymentFlow" => [
        "type" => "PG_CHECKOUT",
        "message" => "Payment for template purchase",
        "merchantUrls" => [
            "redirectUrl" => $redirectUrl
        ]
    ]
];

// Convert payload to JSON
$paymentJson = json_encode($paymentPayload);

// Make payment request with O-Bearer token prefix as in sample cURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $paymentEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $paymentJson,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: O-Bearer ' . $accessToken
    ]
]);

$paymentResponse = curl_exec($curl);
$paymentErr = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// Log payment API call
$logFile = fopen($logsDir . '/phonepe_payment_debug.log', 'a');
fwrite($logFile, date('Y-m-d H:i:s') . " - Payment Request: " . $paymentJson . "\n");
fwrite($logFile, date('Y-m-d H:i:s') . " - Payment Response: " . $paymentResponse . "\n");
fwrite($logFile, date('Y-m-d H:i:s') . " - HTTP Code: " . $httpCode . "\n");
if ($paymentErr) {
    fwrite($logFile, date('Y-m-d H:i:s') . " - Error: " . $paymentErr . "\n");
}
fwrite($logFile, "------------------------------\n");
fclose($logFile);

// Handle payment request errors
if ($paymentErr || $httpCode >= 400) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Payment request failed: ' . ($paymentErr ? $paymentErr : $paymentResponse)
    ]);
    exit;
}

$paymentData = json_decode($paymentResponse, true);

// Handle the actual PhonePe response format which directly contains the redirectUrl
if (isset($paymentData['redirectUrl'])) {
    // Update transaction with payment URL and order ID
    $paymentUrl = $paymentData['redirectUrl'];
    $orderId = $paymentData['orderId'] ?? '';

    mysqli_query($conn, "UPDATE transactions SET payment_url = '$paymentUrl', payment_id = '$orderId', status = 'PENDING' WHERE merchant_transaction_id = '$merchantOrderId'");

    // Return success response
    echo json_encode([
        'success' => true,
        'paymentUrl' => $paymentUrl,
        'transactionId' => $merchantOrderId
    ]);
    exit;
}
// Fallback to original expected format just in case
else if (isset($paymentData['success']) && $paymentData['success'] === true && isset($paymentData['data']['instrumentResponse']['redirectInfo']['url'])) {
    // Update transaction with payment URL
    $paymentUrl = $paymentData['data']['instrumentResponse']['redirectInfo']['url'];
    mysqli_query($conn, "UPDATE transactions SET payment_url = '$paymentUrl' WHERE merchant_transaction_id = '$merchantOrderId'");

    // Return success response
    echo json_encode([
        'success' => true,
        'paymentUrl' => $paymentUrl,
        'transactionId' => $merchantOrderId
    ]);
    exit;
} else {
    // Return error response with detailed message
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Payment creation failed: ' . json_encode($paymentData)
    ]);
    exit;
}
