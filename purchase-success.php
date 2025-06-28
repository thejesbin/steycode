<?php
session_start();

// Check if payment was successful
if (!isset($_SESSION['purchase_success']) || $_SESSION['purchase_success'] !== true) {
    header('Location: index.php');
    exit;
}

// Get transaction details from session
$transaction = $_SESSION['transaction'] ?? [];
$templateTitle = $transaction['template_name'] ?? 'Template';
$orderId = $transaction['id'] ?? '';
$amount = $transaction['amount'] ?? '0';
$customerName = $transaction['customer_name'] ?? '';
$customerEmail = $transaction['customer_email'] ?? '';
$purchaseDate = $transaction['purchase_date'] ?? date('F j, Y');

// Clear the session variables after using them
$_SESSION['purchase_success'] = false;
$_SESSION['transaction'] = null;
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thank You for Your Purchase - Steycode</title>
  <meta name="description" content="Thank you for purchasing a premium web template from Steycode">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .success-page {
      padding: 80px 0;
      min-height: 80vh;
      background-color: #f8f9fa;
    }
    .success-icon {
      font-size: 4rem;
      color: #198754;
      margin-bottom: 1.5rem;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      background-color: #ffffff;
      border-bottom: 1px solid #f0f0f0;
      border-radius: 15px 15px 0 0 !important;
    }
    .order-details {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 20px;
    }
    .template-name {
      color: #0d6efd;
      font-weight: 600;
    }
    .btn-download {
      background-color: #0d6efd;
      color: white;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
    }
    .btn-download:hover {
      background-color: #0b5ed7;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
    }
    .steps-list {
      margin-top: 20px;
    }
    .steps-list li {
      margin-bottom: 12px;
      position: relative;
      padding-left: 28px;
    }
    .steps-list li:before {
      content: "\f058";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      position: absolute;
      left: 0;
      color: #198754;
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold fs-4" href="index.php">Steycode</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#templates">Templates</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#contact">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Success Section -->
  <section class="success-page">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header text-center p-4">
              <div class="success-icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <h1 class="card-title display-5 fw-bold">Thank You for Your Purchase!</h1>
              <p class="text-muted fs-5">Your payment was successful and your purchase is complete.</p>
            </div>
            
            <div class="card-body p-4">
              <!-- Order Information Card -->
              <div class="card mb-4 order-details">
                <div class="card-body">
                  <h3 class="card-title fs-4 mb-3">Order Details</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p><strong>Template:</strong> <span class="template-name"><?php echo htmlspecialchars($templateTitle); ?></span></p>
                      <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($orderId); ?></p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Amount:</strong> <span class="badge bg-success fs-6">₹<?php echo htmlspecialchars($amount); ?></span></p>
                      <p><strong>Date:</strong> <?php echo htmlspecialchars($purchaseDate); ?></p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Customer Information Card -->
              <div class="card mb-4 order-details">
                <div class="card-body">
                  <h3 class="card-title fs-4 mb-3">Customer Information</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p><strong>Name:</strong> <?php echo htmlspecialchars($customerName); ?></p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Email:</strong> <?php echo htmlspecialchars($customerEmail); ?></p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Next Steps Card -->
              <div class="card mb-4">
                <div class="card-body">
                  <h3 class="fs-4 mb-3"><i class="fas fa-map-signs me-2 text-primary"></i>What's Next?</h3>
                  <ul class="steps-list ps-0 list-unstyled">
                    <li>You will receive an email with your purchase details and download instructions</li>
                    <li>Check your spam folder if you don't see the email in your inbox</li>
                    <li>You can download your template using the button below</li>
                    <li>For any assistance, please contact our support team</li>
                  </ul>
                </div>
              </div>
              
              <!-- Download Button -->
              <div class="text-center mt-4">
                <p class="text-muted mt-3">Having issues? <a href="mailto:info@steycode.com">Contact Support</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h4>Steycode</h4>
          <p>Premium web templates for modern businesses.</p>
          <p class="mb-1"><i class="fas fa-envelope me-2"></i> <a href="mailto:info@steycode.com" class="text-decoration-none text-white">info@steycode.com</a></p>
          <p><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</p>
        </div>
        <div class="col-md-4 mb-4">
          <h4>Quick Links</h4>
          <ul class="list-unstyled">
            <li><a href="index.php#home" class="text-decoration-none text-white">Home</a></li>
            <li><a href="index.php#about" class="text-decoration-none text-white">About Us</a></li>
            <li><a href="index.php#templates" class="text-decoration-none text-white">Templates</a></li>
            <li><a href="index.php#contact" class="text-decoration-none text-white">Contact</a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-4">
          <h4>Legal</h4>
          <ul class="list-unstyled">
            <li><a href="terms-conditions.html" class="text-decoration-none text-white">Terms & Conditions</a></li>
            <li><a href="privacy-policy.html" class="text-decoration-none text-white">Privacy Policy</a></li>
            <li><a href="refund-policy.html" class="text-decoration-none text-white">Refund Policy</a></li>
          </ul>
        </div>
      </div>
      <hr class="mt-4">
      <div class="row">
        <div class="col-12 text-center">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> Steycode. All Rights Reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap & Popper JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
