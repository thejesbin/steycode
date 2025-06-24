<?php
require_once 'config/conn.php';

// Set headers for JSON response
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'No form submission detected'
];

// Process only POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = !empty($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : "";
    $email = !empty($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : "";
    $service = !empty($_POST['service']) ? mysqli_real_escape_string($conn, $_POST['service']) : "";
    $message = !empty($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : "";
    
    // Basic validation
    if (empty($name) || empty($email) || empty($service) || empty($message)) {
        $response['message'] = "Please fill in all required fields.";
    } else {
        // Insert into database
        $sql = "INSERT INTO contacts (name, email, service, message) VALUES (?, ?, ?, ?)";
        
        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $service, $message);
            
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Your message has been received. We will contact you soon!";
            } else {
                $response['message'] = "Database error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $response['message'] = "Failed to prepare statement: " . mysqli_error($conn);
        }
    }
}

// Return the JSON response
echo json_encode($response);
?>
