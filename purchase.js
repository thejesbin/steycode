// Purchase Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
  // Get template and price parameters from URL
  const urlParams = new URLSearchParams(window.location.search);
  const templateKey = urlParams.get('template');
  const price = parseFloat(urlParams.get('price')) || 0;

  // Template data
  const templateData = {
    ecommerce: {
      name: 'E-commerce Pro',
      description: 'Modern e-commerce template with shopping cart, payment integration, and admin panel.',
      features: ['Responsive Design', 'Shopping Cart', 'Payment Integration', 'Admin Panel', 'Product Management'],
      tech: ['HTML5', 'CSS3', 'JavaScript', 'Bootstrap'],
      price: 89
    },
    saas: {
      name: 'SaaS Dashboard',
      description: 'Complete dashboard template for SaaS applications with analytics and user management.',
      features: ['Dashboard Analytics', 'User Management', 'Billing System', 'API Integration', 'Mobile Responsive'],
      tech: ['React', 'TypeScript', 'Tailwind CSS', 'Chart.js'],
      price: 129
    },
    portfolio: {
      name: 'Creative Portfolio',
      description: 'Stunning portfolio template for designers and creative professionals.',
      features: ['Portfolio Gallery', 'Contact Forms', 'Blog Section', 'SEO Optimized', 'Animation Effects'],
      tech: ['HTML5', 'SCSS', 'jQuery', 'CSS Grid'],
      price: 59
    },
    corporate: {
      name: 'Corporate Elite',
      description: 'Professional corporate website template with CMS integration and SEO optimization.',
      features: ['CMS Integration', 'SEO Optimized', 'Contact Forms', 'Team Pages', 'Service Pages'],
      tech: ['WordPress', 'PHP', 'MySQL', 'Bootstrap'],
      price: 99
    }
  };

  // Load purchase details
  if (templateKey && templateData[templateKey]) {
    loadPurchaseDetails(templateData[templateKey]);
  } else {
    // Redirect to home if template not found
    window.location.href = 'index.html';
  }

  function loadPurchaseDetails(template) {
    // Update page title
    document.title = `Purchase ${template.name} - Steycode`;

    // Create order summary HTML
    const orderSummary = document.getElementById('order-summary');
    orderSummary.innerHTML = `
      <div class="order-item">
        <div class="item-details">
          <h3>${template.name}</h3>
          <p>${template.description}</p>
          <div class="item-features">
            <h4>Included Features:</h4>
            <ul>
              ${template.features.map(feature => `<li>${feature}</li>`).join('')}
            </ul>
          </div>
          <div class="item-tech">
            <h4>Technologies:</h4>
            <div class="tech-tags">
              ${template.tech.map(tech => `<span class="tech-tag">${tech}</span>`).join('')}
            </div>
          </div>
        </div>
        <div class="item-price">$${template.price}</div>
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
    `;

    // Update pricing
    updatePurchaseSummary(template.price);
  }

  function updatePurchaseSummary(price) {
    const subtotal = price;
    const tax = Math.round(subtotal * 0.08 * 100) / 100; // 8% tax
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = `$${subtotal}`;
    document.getElementById('tax').textContent = `$${tax}`;
    document.getElementById('total').textContent = `$${total}`;
  }

  // Purchase form submission
  const purchaseForm = document.getElementById('purchase-form');
  purchaseForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const customerData = Object.fromEntries(formData);
    
    // Validate required fields
    if (!customerData.firstName || !customerData.lastName || !customerData.email || !customerData.country) {
      alert('Please fill in all required fields.');
      return;
    }

    // Validate terms acceptance
    if (!customerData.terms) {
      alert('Please accept the Terms & Conditions to proceed.');
      return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline';
    submitBtn.disabled = true;
    
    // Simulate payment processing
    setTimeout(() => {
      const template = templateData[templateKey];
      const customerName = `${customerData.firstName} ${customerData.lastName}`;
      
      alert(`Thank you ${customerName}! Your purchase of ${template.name} has been processed successfully. You will receive download instructions at ${customerData.email} within the next few minutes.`);
      
      // Reset form and button
      this.reset();
      btnText.style.display = 'inline';
      btnLoading.style.display = 'none';
      submitBtn.disabled = false;
      
      // Redirect to thank you page or home
      setTimeout(() => {
        window.location.href = 'index.html';
      }, 2000);
    }, 3000);
  });

  // Mobile navigation toggle
  const hamburger = document.querySelector('.hamburger');
  const navMenu = document.querySelector('.nav-menu');

  if (hamburger && navMenu) {
    hamburger.addEventListener('click', function() {
      hamburger.classList.toggle('active');
      navMenu.classList.toggle('active');
    });

    // Close menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
      hamburger.classList.remove('active');
      navMenu.classList.remove('active');
    }));
  }
});