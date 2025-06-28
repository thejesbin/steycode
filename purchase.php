<?php
require_once 'config/conn.php';

// Initialize template variable
$template = null;
$showTemplateDetails = false;
$paymentStatus = '';
$paymentMessage = '';

// Check if template ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $templateId = mysqli_real_escape_string($conn, $_GET['id']);
  $getTemplate = mysqli_query($conn, "SELECT * FROM templates WHERE id=$templateId");
  
  if (mysqli_num_rows($getTemplate) > 0) {
    $template = mysqli_fetch_assoc($getTemplate);
    $showTemplateDetails = true;
  }
} 

// Check if this is a payment return
if (isset($_GET['status'])) {
  $paymentStatus = $_GET['status'];
  if ($paymentStatus === 'failed') {
    $paymentMessage = 'Your payment could not be processed. Please try again.';
  } elseif ($paymentStatus === 'success') {
    $paymentMessage = 'Thank you for your purchase! Your template is ready to download.';
  } elseif (isset($_GET['message'])) {
    $paymentMessage = htmlspecialchars($_GET['message']);
  }
}
?>



<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Purchase Template - Steycode</title>
  <meta name="description" content="Purchase premium web templates from Steycode">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar">
    <div class="nav-container">
      <div class="nav-logo">
        <a href="index.php">
          <h2>Steycode</h2>
        </a>
      </div>
      <ul class="nav-menu">
        <li><a href="index.php#home" class="nav-link">Home</a></li>
        <li><a href="index.php#about" class="nav-link">About</a></li>
        <li><a href="index.php#templates" class="nav-link">Templates</a></li>
        <li><a href="index.php#contact" class="nav-link">Contact</a></li>
      </ul>
      <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </nav>

  <!-- Purchase Section -->
  <section class="purchase-page">
    <div class="container">
      <div class="breadcrumb">
        <a href="index.php">Home</a> > <a href="index.php#templates">Templates</a> > <span>Purchase</span>
      </div>

      <?php if ($paymentStatus) { ?>
        <div class="payment-status">
          <h2><?php echo $paymentMessage; ?></h2>
        </div>
      <?php } else { ?>
        <div class="purchase-container">
          <div class="purchase-header">
            <h1>Complete Your Purchase</h1>
            <p>Secure checkout for premium web templates</p>
          </div>

          <div class="purchase-grid">
            <div class="purchase-details">
              <h2>Order Summary</h2>
              <div id="order-summary" class="order-summary">
                <div class="order-item">
                  <div class="item-details">
                    <?php if ($template) { ?>
                      <h3><?php echo $template['title']; ?></h3>
                      <p><?php echo $template['description']; ?></p>
                      <div class="item-features">
                        <h4>Included Features:</h4>
                        <ul>
                          <?php 
                          $features = json_decode($template['features']);
                          if ($features) {
                            foreach ($features as $feature) { ?>
                              <li><?php echo $feature; ?></li>
                            <?php } 
                          } ?>
                        </ul>
                      </div>
                      <div class="item-tech">
                        <h4>Technologies:</h4>
                        <div class="tech-tags">
                          <?php 
                          $technologies = json_decode($template['technologies']);
                          if ($technologies) {
                            foreach ($technologies as $tech) { ?>
                              <span class="tech-tag"><?php echo $tech; ?></span>
                            <?php } 
                          } ?>
                        </div>
                      </div>
                    <?php } else { ?>
                      <h3>Payment Processing</h3>
                      <p>Your payment is being processed. Please wait...</p>
                    <?php } ?>
                  </div>
                  <?php if ($template) { ?>
                    <div class="item-price">₹<?php echo $template['amount']; ?></div>
                  <?php } ?>
                </div>
                <div class="order-notes">
                  <h4>What you'll receive:</h4>
                  <ul>
                    <li>Complete source code</li>
                    <li>Documentation and setup guide</li>
                    <li>Free updates for 1 year</li>
                    <li>Email support</li>
                    <li>Commercial license</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="purchase-form-container">
              <h2>Billing Information</h2>
              <form id="purchase-form" class="purchase-form">
                <div class="form-row">
                  <div class="form-group">
                    <label for="firstName">First Name *</label>
                    <input type="text" id="firstName" name="firstName" required>
                  </div>
                  <div class="form-group">
                    <label for="lastName">Last Name *</label>
                    <input type="text" id="lastName" name="lastName" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="email">Email Address *</label>
                  <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                  <label for="company">Company (Optional)</label>
                  <input type="text" id="company" name="company">
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="country">Country *</label>
                    <select id="country" name="country" required>
                      <option value="">Select Country</option>
                      <option value="US">United States</option>
                      <option value="CA">Canada</option>
                      <option value="UK">United Kingdom</option>
                      <option value="AU">Australia</option>
                      <option value="DE">Germany</option>
                      <option value="FR">France</option>
                      <option value="JP">Japan</option>
                      <option value="IN">India</option>
                      <option value="BR">Brazil</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="state">State/Province</label>
                    <input type="text" id="state" name="state">
                  </div>
                </div>

                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" id="address" name="address">
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city">
                  </div>
                  <div class="form-group">
                    <label for="zipCode">ZIP/Postal Code</label>
                    <input type="text" id="zipCode" name="zipCode">
                  </div>
                </div>

                <div class="form-group checkbox-group">
                  <label class="checkbox-label">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span class="checkmark"></span>
                    I agree to the <a href="terms-conditions.html" target="_blank">Terms & Conditions</a> and <a href="privacy-policy.html" target="_blank">Privacy Policy</a>
                  </label>
                </div>

                <div class="purchase-summary">
                  <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="subtotal"><?php echo $template ? '₹'.$template['amount'] : 'Processing...'; ?></span>
                  </div>

                  <div class="summary-row total">
                    <span>Total:</span>
                    <span id="total"><?php echo $template ? '₹'.$template['amount'] : 'Processing...'; ?></span>
                  </div>
                </div>

                <button type="button" id="initiatePaymentBtn" class="btn btn-primary btn-full purchase-btn" <?php echo !$template ? 'disabled' : ''; ?>>
                  Proceed to Payment
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>

  <!-- Loading Modal -->
  <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center p-5">
          <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <h5>Processing your payment...</h5>
          <p>Please wait, do not close this window.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Payment Error</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="errorMessage">Sorry, there was a problem processing your payment. Please try again.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- PhonePe JS Checkout Script -->
  <script src="https://mercury.phonepe.com/web/bundle/checkout.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Handle payment form submission
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('initiatePaymentBtn').addEventListener('click', function() {
        console.log('Payment button clicked'); // Debug log
        // Get form data
        const templateId = <?php echo $template['id']; ?>;
        const name = document.getElementById('firstName').value + ' ' + document.getElementById('lastName').value;
        const email = document.getElementById('email').value;
        const amount = parseFloat(<?php echo $template['amount']; ?>);
        
        // Validate form
        if (!name || !email || isNaN(amount)) {
          alert('Please fill in all required fields');
          return;
        }
        
        // Show loading modal
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        
        // Initialize payment
        fetch('/api/phonepe-init.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            templateId: templateId,
            name: name,
            email: email,
            amount: amount
          })
        })
        .then(response => response.json())
        .then(data => {
          loadingModal.hide();
          
          if (data.success) {
            // Open PhonePe checkout using JS SDK
            window.location.href = data.paymentUrl;
          } else {
            // Show error
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            document.getElementById('errorMessage').textContent = data.message || 'Payment initialization failed. Please try again.';
            errorModal.show();
          }
        })
        .catch(error => {
          loadingModal.hide();
          const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
          document.getElementById('errorMessage').textContent = 'An unexpected error occurred. Please try again.';
          errorModal.show();
          console.error('Payment error:', error);
        });
      });
      
      // Show appropriate modal based on URL parameters
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');
      
      if (status === 'failed') {
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        document.getElementById('errorMessage').textContent = 'Your payment was not successful. Please try again.';
        errorModal.show();
      } else if (status === 'error') {
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        const errorMsg = urlParams.get('message');
        document.getElementById('errorMessage').textContent = errorMsg ? 
          'Payment error: ' + errorMsg.replace(/_/g, ' ') : 
          'There was a problem with your payment. Please try again.';
        errorModal.show();
      }
    });
  </script>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>Steycode</h3>
          <p>Creating innovative software and premium web templates for modern businesses.</p>
        </div>
        <!-- <div class="footer-section">
          <h4>Products</h4>
          <ul>
            <li><a href="product-details.html?product=dataflow">DataFlow Pro</a></li>
            <li><a href="product-details.html?product=secureauth">SecureAuth Suite</a></li>
            <li><a href="product-details.html?product=clouddeploy">CloudDeploy</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Templates</h4>
          <ul>
            <li><a href="purchase.html?template=ecommerce&price=89">E-commerce</a></li>
            <li><a href="purchase.html?template=saas&price=129">SaaS Dashboard</a></li>
            <li><a href="purchase.html?template=portfolio&price=59">Portfolio</a></li>
          </ul>
        </div> -->
        <div class="footer-section">
          <h4>Legal</h4>
          <ul>
            <li><a href="privacy-policy.html">Privacy Policy</a></li>
            <li><a href="terms-conditions.html">Terms & Conditions</a></li>
            <li><a href="refund-policy.html">Refund Policy</a></li>
            <li><a href="shipping-policy.html">Shipping Policy</a></li>
            <li><a href="cancellation-policy.html">Cancellation Policy</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Company</h4>
          <ul>
            <li><a href="index.php#about">About Us</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="mailto:info@steycode.com">Support</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 Steycode. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>

</html>