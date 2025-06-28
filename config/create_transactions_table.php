<?php
require_once 'conn.php';

// SQL to create transactions table
$createTableSQL = "CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_transaction_id` varchar(255) NOT NULL,
  `template_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_url` text DEFAULT NULL,
  `status` enum('INITIATED', 'SUCCESS', 'FAILED', 'PENDING', 'EXPIRED') NOT NULL DEFAULT 'INITIATED',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `merchant_transaction_id` (`merchant_transaction_id`),
  KEY `template_id` (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute the SQL
if ($conn->query($createTableSQL) === TRUE) {
    echo "Transactions table created successfully or already exists";
} else {
    echo "Error creating transactions table: " . $conn->error;
}

$conn->close();
?>
