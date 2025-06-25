<?php
require_once 'config/conn.php';
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Steycode - Premium Software & Web Templates</title>
  <meta name="description" content="Professional software development company creating innovative products and premium web templates. Quality source code and custom solutions.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        <li><a href="#home" class="nav-link">Home</a></li>
        <li><a href="#about" class="nav-link">About</a></li>
        <!-- <li><a href="#products" class="nav-link">Products</a></li> -->
        <li><a href="#templates" class="nav-link">Templates</a></li>
        <li><a href="#contact" class="nav-link">Contact</a></li>
      </ul>
      <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="hero-container">
      <div class="hero-content">
        <h1 class="hero-title">Crafting Digital Excellence</h1>
        <p class="hero-subtitle">Purchase ready-to-deploy premium templates for your business or explore our innovative SaaS solutions designed for modern enterprises.</p>
        <div class="hero-buttons">
          <a href="#templates" class="btn btn-primary">Browse Templates</a>
          <a href="#services" class="btn btn-secondary">Our Services</a>
        </div>
      </div>
      <div class="hero-image">
        <div class="code-window">
          <div class="window-header">
            <div class="window-buttons">
              <span class="btn-close"></span>
              <span class="btn-minimize"></span>
              <span class="btn-maximize"></span>
            </div>
          </div>
          <div class="code-content">
            <div class="code-line"><span class="keyword">function</span> <span class="function">createAwesome</span>() {</div>
            <div class="code-line"> <span class="keyword">return</span> <span class="string">'innovative software'</span>;</div>
            <div class="code-line">}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="about">
    <div class="container">
      <div class="section-header">
        <h2>About Steycode</h2>
        <p>Transforming ideas into powerful digital solutions</p>
      </div>
      <div class="about-grid">
        <div class="about-content">
          <h3>Our Mission</h3>
          <p>Founded in 2021, Steycode is a passionate team of developers and designers dedicated to creating high-quality software products and web templates. Our mission is to empower businesses and developers with tools that make their work more efficient and impactful.</p>
          <p>We believe in delivering exceptional quality, innovative solutions, and outstanding customer service to every client we serve. Our team's expertise spans across various technologies to ensure we can meet the diverse needs of our customers.</p>
          <div class="stats">
            <div class="stat">
              <h4>20+</h4>
              <p>Projects Delivered</p>
            </div>
            <div class="stat">
              <h4>18+</h4>
              <p>Happy Clients</p>
            </div>
            <!-- <div class="stat">
              <h4>5+</h4>
              <p>Years Experience</p>
            </div> -->
          </div>
        </div>
        <div class="about-features">
          <div class="feature">
            <div class="feature-icon">💻</div>
            <h4>Custom Software</h4>
            <p>Tailored solutions built with cutting-edge technologies</p>
          </div>
          <div class="feature">
            <div class="feature-icon">🎨</div>
            <h4>Premium Templates</h4>
            <p>Beautiful, responsive templates for every business need</p>
          </div>
          <div class="feature">
            <div class="feature-icon">⚡</div>
            <h4>Source Code</h4>
            <p>Clean, well-documented code ready for customization</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Templates Section -->
  <section id="templates" class="templates">
    <div class="container">
      <div class="section-header">
        <h2>Premium Web Templates</h2>
        <p>Professional templates with clean code and modern designs</p>
      </div>
      <div class="templates-grid">
        <?php
        $fetchTemplates = mysqli_query($conn, "SELECT * FROM templates");
        while ($row = mysqli_fetch_assoc($fetchTemplates)) {
        ?>
          <div class="template-card">
            <div class="template-image">
              <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>">
            </div>
            <div class="template-content">
              <h3><?= $row['title'] ?></h3>
              <p><?= $row['description'] ?></p>
              <div class="template-tech">
                <?php
                $features = json_decode($row['technologies'], true);
                foreach ($features as $feature) {
                ?>
                  <span class="tech-tag"><?= $feature ?></span>
                <?php
                }
                ?>
              </div>
              <div class="template-price">₹<?= $row['amount'] ?></div>
              <a href="purchase.php?id=<?= $row['id'] ?>" class="btn btn-primary">Purchase Template</a>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="services">
    <div class="container">
      <div class="section-header">
        <h2>Our Services</h2>
        <p>Comprehensive solutions for your digital needs</p>
      </div>
      <div class="services-grid">
        <div class="service-card">
          <div class="service-icon">⚙️</div>
          <h3>Custom Development</h3>
          <p>Tailored software solutions built to meet your specific business requirements and goals.</p>
        </div>
        <div class="service-card">
          <div class="service-icon">🎨</div>
          <h3>Template Customization</h3>
          <p>Professional customization of our templates to match your brand and functionality needs.</p>
        </div>
        <div class="service-card">
          <div class="service-icon">📱</div>
          <h3>Mobile App Development</h3>
          <p>Native and cross-platform mobile applications with modern UI/UX design principles.</p>
        </div>
        <div class="service-card">
          <div class="service-icon">☁️</div>
          <h3>Cloud Solutions</h3>
          <p>Scalable cloud infrastructure setup and migration services for optimal performance.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="contact-section">
    <div class="container">
      <div class="section-header">
        <h2>Get in Touch</h2>
        <p>Have questions or need assistance? Reach out to us today.</p>
      </div>
      <div class="contact-grid">
        <div class="contact-info">
          <div class="contact-item">
            <div class="contact-icon">📧</div>
            <div>
              <h4>Email</h4>
              <p>info@steycode.com</p>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon">📞</div>
            <div>
              <h4>Phone</h4>
              <p>+91 8301020496</p>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon">📍</div>
            <div>
              <h4>Registered Address</h4>
              <p>Thudiyanparambill, Vemom PO</p>
              <p>Mudramoola, Mananthavady</p>
              <p>Wayanad, Kerala, 670645</p>
              <p>India</p>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon">⏰</div>
            <div>
              <h4>Business Hours</h4>
              <p>Monday - Friday: 9:00 AM - 6:00 PM IST</p>
            </div>
          </div>
        </div>
        <div class="contact-form">
          <form method="POST" id="contactForm" novalidate>
            <div class="form-group">
              <input type="text" name="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
              <input type="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="form-group">
              <select name="service" required>
                <option value="">Select Service</option>
                <option value="custom">Custom Development</option>
                <option value="template">Template Purchase</option>
                <option value="consultation">Consultation</option>
              </select>
            </div>
            <div class="form-group">
              <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-full" id="submit-contact">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Custom Alert Popup -->
  <div id="custom-alert" class="custom-alert">
    <div class="alert-content">
      <div class="alert-header">
        <span class="close-alert">&times;</span>
      </div>
      <div class="alert-body">
        <div class="alert-icon success">
          <svg viewBox="0 0 24 24" width="40" height="40">
            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
          </svg>
        </div>
        <div class="alert-icon error" style="display: none;">
          <svg viewBox="0 0 24 24" width="40" height="40">
            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
          </svg>
        </div>
        <h3 id="alert-title">Success</h3>
        <p id="alert-message"></p>
        <button class="btn btn-primary close-btn">Close</button>
      </div>
    </div>
  </div>

  <style>
    .custom-alert {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    
    .alert-content {
      background-color: white;
      border-radius: 8px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      animation: fadeInUp 0.3s ease-out;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .alert-header {
      padding: 10px 15px;
      text-align: right;
    }
    
    .close-alert {
      font-size: 24px;
      cursor: pointer;
      color: #777;
    }
    
    .alert-body {
      padding: 0 20px 20px;
      text-align: center;
    }
    
    .alert-icon {
      margin: 0 auto 15px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .alert-icon.success {
      background-color: #e7f4e4;
      color: #2e7d32;
    }
    
    .alert-icon.error {
      background-color: #fdeded;
      color: #d32f2f;
    }
    
    #alert-title {
      margin: 0 0 10px;
      color: #333;
    }
    
    #alert-message {
      margin-bottom: 20px;
      color: #555;
    }
    
    .close-btn {
      margin-top: 10px;
    }
  </style>

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
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="mailto:info@steycode.com">Support</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 Steycode. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const contactForm = document.getElementById('contactForm');
      const customAlert = document.getElementById('custom-alert');
      const alertTitle = document.getElementById('alert-title');
      const alertMessage = document.getElementById('alert-message');
      const successIcon = document.querySelector('.alert-icon.success');
      const errorIcon = document.querySelector('.alert-icon.error');
      const closeButtons = document.querySelectorAll('.close-alert, .close-btn');
      
      // Handle form submission with AJAX
      contactForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Manual form validation
        const nameInput = contactForm.querySelector('[name="name"]');
        const emailInput = contactForm.querySelector('[name="email"]');
        const serviceInput = contactForm.querySelector('[name="service"]');
        const messageInput = contactForm.querySelector('[name="message"]');
        
        // Basic validation
        if (!nameInput.value.trim()) {
          showAlert('Validation Error', 'Please enter your name.', false);
          nameInput.focus();
          return;
        }
        
        if (!emailInput.value.trim()) {
          showAlert('Validation Error', 'Please enter your email address.', false);
          emailInput.focus();
          return;
        }
        
        // Basic email format validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value.trim())) {
          showAlert('Validation Error', 'Please enter a valid email address.', false);
          emailInput.focus();
          return;
        }
        
        if (!serviceInput.value) {
          showAlert('Validation Error', 'Please select a service.', false);
          serviceInput.focus();
          return;
        }
        
        if (!messageInput.value.trim()) {
          showAlert('Validation Error', 'Please enter your message.', false);
          messageInput.focus();
          return;
        }
        
        // Show loading state on button
        const submitButton = document.getElementById('submit-contact');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = 'Sending...';
        submitButton.disabled = true;
        
        // Collect form data
        const formData = new FormData(contactForm);
        
        // Send AJAX request
        fetch('contact-form.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          // Display response using our custom alert function
          showAlert(
            data.success ? 'Thank You!' : 'Message Status',
            data.message,
            data.success
          );
          
          // Reset form if successful
          if (data.success) {
            contactForm.reset();
          }
          
          // Reset button state
          submitButton.innerHTML = originalText;
          submitButton.disabled = false;
        })
        .catch(error => {
          // Handle errors
          showAlert('Error', 'Something went wrong. Please try again later.', false);
          
          // Reset button state
          submitButton.innerHTML = originalText;
          submitButton.disabled = false;
        });
      });
      
      // Function to display custom alert
      function showAlert(title, message, isSuccess) {
        alertTitle.textContent = title;
        alertMessage.textContent = message;
        
        if (isSuccess) {
          successIcon.style.display = 'flex';
          errorIcon.style.display = 'none';
        } else {
          successIcon.style.display = 'none';
          errorIcon.style.display = 'flex';
        }
        
        customAlert.style.display = 'flex';
      }
      
      // Close alert when close button or X is clicked
      closeButtons.forEach(button => {
        button.addEventListener('click', function() {
          customAlert.style.display = 'none';
        });
      });
      
      // Close alert when clicking outside the content
      customAlert.addEventListener('click', function(event) {
        if (event.target === customAlert) {
          customAlert.style.display = 'none';
        }
      });
    });
  </script>
  <script src="main.js"></script>
</body>

</html>