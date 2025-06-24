// Product Details Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
  // Get product parameter from URL
  const urlParams = new URLSearchParams(window.location.search);
  const productKey = urlParams.get('product');

  // Product data
  const productData = {
    dataflow: {
      name: 'DataFlow Pro',
      icon: '📊',
      price: '$299/month',
      description: 'Advanced data analytics platform with real-time visualization and automated reporting capabilities.',
      longDescription: 'DataFlow Pro is a comprehensive data analytics solution designed for modern businesses that need to make data-driven decisions quickly and efficiently. Our platform combines powerful data processing capabilities with intuitive visualization tools to help you understand your data like never before.',
      features: [
        'Real-time data processing and visualization',
        'Automated report generation and scheduling',
        'Advanced analytics and machine learning integration',
        'Custom dashboard creation with drag-and-drop interface',
        'Multi-source data integration (SQL, NoSQL, APIs)',
        'Role-based access control and user management',
        'Export capabilities (PDF, Excel, CSV)',
        'Mobile-responsive dashboards',
        'API access for custom integrations',
        '24/7 technical support and training'
      ],
      specs: {
        'Data Sources': '50+ integrations',
        'Users': 'Unlimited',
        'Storage': '1TB included',
        'API Calls': '100,000/month',
        'Support': '24/7 Priority',
        'Deployment': 'Cloud & On-premise'
      },
      benefits: [
        'Reduce reporting time by 80%',
        'Make faster, data-driven decisions',
        'Improve team collaboration',
        'Scale with your business growth'
      ]
    },
    secureauth: {
      name: 'SecureAuth Suite',
      icon: '🛡️',
      price: '$199/month',
      description: 'Comprehensive authentication and security management system for modern applications.',
      longDescription: 'SecureAuth Suite provides enterprise-grade authentication and security management for applications of all sizes. Built with security-first principles, our platform ensures your users and data remain protected while providing a seamless user experience.',
      features: [
        'Multi-factor authentication (MFA) support',
        'Single Sign-On (SSO) integration',
        'OAuth 2.0 and OpenID Connect compliance',
        'Advanced threat detection and prevention',
        'User behavior analytics',
        'Passwordless authentication options',
        'Compliance reporting (SOC 2, GDPR, HIPAA)',
        'Custom branding and white-labeling',
        'SDK for popular programming languages',
        'Audit logs and security monitoring'
      ],
      specs: {
        'Authentication Methods': '10+ options',
        'Users': 'Up to 10,000',
        'Integrations': '100+ apps',
        'Uptime SLA': '99.9%',
        'Support': 'Business hours',
        'Compliance': 'SOC 2, GDPR, HIPAA'
      },
      benefits: [
        'Reduce security breaches by 95%',
        'Improve user experience',
        'Ensure regulatory compliance',
        'Streamline user management'
      ]
    },
    clouddeploy: {
      name: 'CloudDeploy',
      icon: '🚀',
      price: '$149/month',
      description: 'Automated deployment and CI/CD pipeline management with multi-cloud support.',
      longDescription: 'CloudDeploy simplifies the deployment process with automated CI/CD pipelines that work across multiple cloud providers. Whether you\'re deploying a simple web application or a complex microservices architecture, CloudDeploy has you covered.',
      features: [
        'Automated CI/CD pipeline creation',
        'Multi-cloud deployment (AWS, Azure, GCP)',
        'Container orchestration with Kubernetes',
        'Blue-green and canary deployment strategies',
        'Automated testing and quality gates',
        'Infrastructure as Code (IaC) support',
        'Rollback and disaster recovery',
        'Performance monitoring and alerts',
        'Team collaboration and approval workflows',
        'Cost optimization recommendations'
      ],
      specs: {
        'Cloud Providers': 'AWS, Azure, GCP',
        'Deployments': 'Unlimited',
        'Build Minutes': '5,000/month',
        'Team Members': 'Up to 25',
        'Support': 'Email & Chat',
        'Monitoring': 'Real-time'
      },
      benefits: [
        'Deploy 10x faster',
        'Reduce deployment errors by 90%',
        'Improve team productivity',
        'Lower infrastructure costs'
      ]
    }
  };

  // Load product details
  if (productKey && productData[productKey]) {
    loadProductDetails(productData[productKey]);
  } else {
    // Redirect to home if product not found
    window.location.href = 'index.html';
  }

  function loadProductDetails(product) {
    // Update page title and breadcrumb
    document.title = `${product.name} - Steycode`;
    document.getElementById('product-name').textContent = product.name;

    // Create product details HTML
    const productContent = document.getElementById('product-content');
    productContent.innerHTML = `
      <div class="product-hero">
        <div class="product-hero-content">
          <div class="product-icon-large">${product.icon}</div>
          <div class="product-info">
            <h1>${product.name}</h1>
            <p class="product-tagline">${product.description}</p>
            <div class="product-price-large">${product.price}</div>
            <div class="product-actions">
              <button class="btn btn-primary btn-large" onclick="requestDemo()">Request Demo</button>
              <button class="btn btn-secondary btn-large" onclick="startTrial()">Start Free Trial</button>
            </div>
          </div>
        </div>
      </div>

      <div class="product-details-grid">
        <div class="product-main">
          <section class="product-section">
            <h2>Overview</h2>
            <p>${product.longDescription}</p>
          </section>

          <section class="product-section">
            <h2>Key Features</h2>
            <div class="features-grid">
              ${product.features.map(feature => `
                <div class="feature-item">
                  <div class="feature-check">✓</div>
                  <span>${feature}</span>
                </div>
              `).join('')}
            </div>
          </section>

          <section class="product-section">
            <h2>Benefits</h2>
            <div class="benefits-grid">
              ${product.benefits.map(benefit => `
                <div class="benefit-item">
                  <div class="benefit-icon">🎯</div>
                  <span>${benefit}</span>
                </div>
              `).join('')}
            </div>
          </section>
        </div>

        <div class="product-sidebar">
          <div class="specs-card">
            <h3>Technical Specifications</h3>
            <div class="specs-list">
              ${Object.entries(product.specs).map(([key, value]) => `
                <div class="spec-row">
                  <span class="spec-label">${key}</span>
                  <span class="spec-value">${value}</span>
                </div>
              `).join('')}
            </div>
          </div>

          <div class="cta-card">
            <h3>Ready to get started?</h3>
            <p>Join thousands of companies already using ${product.name}</p>
            <button class="btn btn-primary btn-full" onclick="requestDemo()">Request Demo</button>
            <button class="btn btn-secondary btn-full" onclick="startTrial()">Start Free Trial</button>
          </div>
        </div>
      </div>
    `;
  }

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

// Global functions for demo and trial requests
function requestDemo() {
  alert('Thank you for your interest! Our sales team will contact you within 24 hours to schedule a personalized demo. Please email us at info@steycode.com with your preferred time.');
}

function startTrial() {
  alert('Free trial coming soon! Sign up for our newsletter to be notified when trials become available. Contact info@steycode.com for early access.');
}