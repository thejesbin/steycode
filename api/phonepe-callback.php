<?php
// Include database connection
require_once '../config/conn.php';
session_start();

// Create logs directory if it doesn't exist
$logsDir = '../logs';
if (!file_exists($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Log all incoming data
$logFile = fopen($logsDir . '/phonepe_callback.log', 'a');
fwrite($logFile, date('Y-m-d H:i:s') . " - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n");
fwrite($logFile, date('Y-m-d H:i:s') . " - GET params: " . json_encode($_GET) . "\n");
fwrite($logFile, date('Y-m-d H:i:s') . " - POST params: " . json_encode($_POST) . "\n");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // PhonePe server callback - webhook 
    $callbackData = file_get_contents('php://input');
    fwrite($logFile, date('Y-m-d H:i:s') . " - Webhook data: " . $callbackData . "\n");
    fwrite($logFile, date('Y-m-d H:i:s') . " - HTTP Code: " . $_SERVER['HTTP_CODE'] . "\n");
    fwrite($logFile, "------------------------------\n");
    fclose($logFile);
    
    $callbackJson = json_decode($callbackData, true);
    
    if ($callbackJson && isset($callbackJson['merchantOrderId'])) {
        $merchantOrderId = $callbackJson['merchantOrderId'];
        $status = ($callbackJson['state'] === 'COMPLETED') ? 'SUCCESS' : 'FAILED';
        
        // Update transaction status in database
        mysqli_query($conn, "UPDATE transactions SET 
                            status = '$status', 
                            payment_id = '{$callbackJson['transactionId']}',
                            updated_at = NOW() 
                            WHERE merchant_transaction_id = '$merchantOrderId'");
        
        echo json_encode(['success' => true]); // Acknowledge receipt to PhonePe server
        exit;
    }
    
    // Invalid webhook data
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid webhook data']);
    exit;
} else {
    // Browser redirect handling
    fwrite($logFile, "------------------------------\n");
    fclose($logFile);
    
    // Get transaction ID from query string
    $txnId = isset($_GET['txnId']) ? $_GET['txnId'] : '';
    
    if (empty($txnId)) {
        header('Location: ../purchase.php?status=error');
        exit;
    }
    
    // Check transaction status
    $txnQuery = mysqli_query($conn, "SELECT t.*, tm.title, tm.amount as template_amount, tm.id as template_id 
                                    FROM transactions t 
                                    JOIN templates tm ON t.template_id = tm.id 
                                    WHERE t.merchant_transaction_id = '$txnId'");
    
    if (mysqli_num_rows($txnQuery) > 0) {
        $txn = mysqli_fetch_assoc($txnQuery);
        
        // Verify transaction status from PhonePe API
        // PhonePe API credentials
        $clientId = 'SU2506271525121002988041';
        $clientSecret = '08c5c09a-c4f0-4d05-87c9-3bc6a9e0ea76';
        $clientVersion = '1';
        $environment = 'production'; // 'sandbox' or 'production'
        
        // API endpoints
        if ($environment === 'sandbox') {
            $oauthEndpoint = 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token';
            $statusBaseEndpoint = 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/order';
        } else {
            $oauthEndpoint = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';
            $statusBaseEndpoint = 'https://api.phonepe.com/apis/pg/checkout/v2/order';
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
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        // Log OAuth API call for debugging
        $logFile = fopen($logsDir . '/phonepe_callback_oauth.log', 'a');
        fwrite($logFile, date('Y-m-d H:i:s') . " - OAuth Response: " . $oauthResponse . "\n");
        fwrite($logFile, date('Y-m-d H:i:s') . " - HTTP Code: " . $httpCode . "\n");
        fwrite($logFile, "------------------------------\n");
        fclose($logFile);
        
        $tokenData = json_decode($oauthResponse, true);
        if (!isset($tokenData['access_token'])) {
            header('Location: ../purchase.php?status=error&message=auth_failed');
            exit;
        }
        
        $accessToken = $tokenData['access_token'];
        
        // Step 2: Check order status using GET method
        // Format: /checkout/v2/order/{merchantOrderId}/status
        $statusEndpoint = $statusBaseEndpoint . '/' . $txnId . '/status?details=false';
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $statusEndpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,  // Use GET method instead of POST
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: O-Bearer ' . $accessToken  // Use O-Bearer prefix
            ]
        ]);
        
        $statusResponse = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        // Log status API call for debugging
        $logFile = fopen($logsDir . '/phonepe_status_check.log', 'a');
        fwrite($logFile, date('Y-m-d H:i:s') . " - Status Request: " . $statusEndpoint . "\n");
        fwrite($logFile, date('Y-m-d H:i:s') . " - Status Response: " . $statusResponse . "\n");
        fwrite($logFile, date('Y-m-d H:i:s') . " - HTTP Code: " . $httpCode . "\n");
        fwrite($logFile, "------------------------------\n");
        fclose($logFile);
        
        $statusData = json_decode($statusResponse, true);
        
        // Extract the status from the correct location in the response
        // PhonePe API returns state at the root level, not under 'data'
        $phonepeStatus = isset($statusData['state']) ? $statusData['state'] : 'UNKNOWN';
        
        // Log the actual response structure for debugging
        $logFile = fopen($logsDir . '/phonepe_response_debug.log', 'a');
        fwrite($logFile, date('Y-m-d H:i:s') . " - Full Status Response: " . print_r($statusData, true) . "\n");
        fwrite($logFile, date('Y-m-d H:i:s') . " - Extracted Status: " . $phonepeStatus . "\n");
        fwrite($logFile, "------------------------------\n");
        fclose($logFile);
        
        // Map PhonePe status to our database status values
        $dbStatus = 'FAILED'; // Default to FAILED
        
        if ($phonepeStatus == 'COMPLETED') {
            $dbStatus = 'SUCCESS';
            
            // Payment successful - update transaction record
            mysqli_query($conn, "UPDATE transactions SET 
                            status = '$dbStatus', 
                            payment_id = '" . (isset($statusData['paymentDetails'][0]['transactionId']) ? $statusData['paymentDetails'][0]['transactionId'] : '') . "', 
                            updated_at = NOW() 
                            WHERE merchant_transaction_id = '$txnId'");
            
            // Set session variables for success page
            $_SESSION['purchase_success'] = true;
            $_SESSION['transaction'] = [
                'id' => $txnId,
                'template_name' => $txn['title'],
                'amount' => $txn['template_amount'],
                'customer_name' => $txn['customer_name'],
                'customer_email' => $txn['customer_email'],
                'purchase_date' => date('Y-m-d H:i:s')
            ];
            
            header('Location: ../purchase-success.php');
            exit;
        } else if ($phonepeStatus == 'PENDING') {
            $dbStatus = 'PENDING';
            
            // Update with compatible status value
            mysqli_query($conn, "UPDATE transactions SET 
                            status = '$dbStatus', 
                            updated_at = NOW() 
                            WHERE merchant_transaction_id = '$txnId'");
            
            header('Location: ../purchase.php?status=pending&message=Your+payment+is+being+processed');
            exit;
        } else {
            // Payment failed or canceled
            
            // Update with compatible status value
            mysqli_query($conn, "UPDATE transactions SET 
                            status = '$dbStatus', 
                            updated_at = NOW() 
                            WHERE merchant_transaction_id = '$txnId'");
            
            header('Location: ../purchase.php?status=failed');
            exit;
        }
    } else {
        // Transaction not found
        header('Location: ../purchase.php?status=error&message=txn_not_found');
        exit;
    }
}
