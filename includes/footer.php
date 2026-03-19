<!-- FOOTER -->
<footer>
  <div class="footer-container">
    
    <!-- Footer Top Section -->
    <div class="footer-top">
      
      <!-- Company Info -->
      <div class="footer-section">
        <div class="footer-brand">
          <h3>🌍 Wanderly</h3>
          <p class="brand-tagline">Explore Nepal's Magic</p>
        </div>
        <p class="footer-description">
          Your ultimate travel companion for discovering Nepal's hidden gems, breathtaking destinations, and unforgettable adventures.
        </p>
        <div class="social-links">
          <a href="#" title="Facebook" class="social-icon">f</a>
          <a href="#" title="Twitter" class="social-icon">𝕏</a>
          <a href="#" title="Instagram" class="social-icon">📷</a>
          <a href="#" title="YouTube" class="social-icon">▶</a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer-section">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="places.php">Destinations</a></li>
          <li><a href="info.php">Travel Guide</a></li>
          <li><a href="search.php">Search</a></li>
        </ul>
      </div>

      <!-- Explore -->
      <div class="footer-section">
        <h4>Explore</h4>
        <ul class="footer-links">
          <li><a href="#">Popular Tours</a></li>
          <li><a href="#">Adventure Packages</a></li>
          <li><a href="#">Cultural Tours</a></li>
          <li><a href="#">Wildlife Safari</a></li>
        </ul>
      </div>

      <!-- Support -->
      <div class="footer-section">
        <h4>Support</h4>
        <ul class="footer-links">
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms & Conditions</a></li>
        </ul>
      </div>

    </div>

    <!-- Footer Bottom Section -->
    <div class="footer-bottom">
      <div class="footer-credits">
        <p>&copy; 2026 Wanderly. All rights reserved. ❤️ Made with passion for travel lovers.</p>
      </div>
      <div class="footer-stats">
        <span class="stat-item">✈️ 50+ Destinations</span>
        <span class="stat-item">👥 10K+ Users</span>
        <span class="stat-item">⭐ 4.8/5 Rating</span>
      </div>
    </div>

  </div>
</footer>

<style>
/* ===== FOOTER ===== */
footer {
  background: var(--card);
  border-top: 3px solid rgba(255, 45, 122, 0.15);
  margin-top: 60px;
  color: var(--text);
  transition: all 0.3s ease;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 50px 25px;
}

/* Footer Top Section */
.footer-top {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 40px;
  margin-bottom: 40px;
}

/* Footer Section */
.footer-section {
  animation: fadeInUp 0.6s ease;
}

.footer-section h4 {
  font-size: 16px;
  font-weight: 700;
  margin: 0 0 18px 0;
  color: var(--text);
  position: relative;
  display: inline-block;
  padding-bottom: 8px;
}

.footer-section h4::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 30px;
  height: 3px;
  background: var(--accent);
  border-radius: 2px;
}

/* Brand Section */
.footer-brand {
  margin-bottom: 15px;
}

.footer-brand h3 {
  font-size: 22px;
  margin: 0 0 5px 0;
  color: var(--accent);
  font-weight: 700;
  letter-spacing: 0.5px;
}

.brand-tagline {
  font-size: 12px;
  color: var(--muted);
  margin: 0;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.footer-description {
  font-size: 14px;
  color: var(--muted);
  line-height: 1.6;
  margin: 12px 0;
}

/* Social Links */
.social-links {
  display: flex;
  gap: 12px;
  margin-top: 15px;
}

.social-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 45, 122, 0.1);
  color: var(--accent);
  text-decoration: none;
  transition: all 0.3s ease;
  font-weight: 700;
  font-size: 16px;
}

.social-icon:hover {
  background: var(--accent);
  color: #fff;
  transform: translateY(-4px) scale(1.1);
  box-shadow: 0 6px 16px rgba(255, 45, 122, 0.3);
}

/* Footer Links */
.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.footer-links li a {
  color: var(--muted);
  text-decoration: none;
  font-size: 14px;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  position: relative;
}

.footer-links li a::before {
  content: '→';
  opacity: 0;
  transform: translateX(-8px);
  transition: all 0.3s ease;
}

.footer-links li a:hover {
  color: var(--accent);
  padding-left: 8px;
}

.footer-links li a:hover::before {
  opacity: 1;
  transform: translateX(0);
}

/* Newsletter Form */
.newsletter-text {
  font-size: 13px;
  color: var(--muted);
  margin: 0 0 12px 0;
}

.newsletter-form {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 12px;
}

.newsletter-input {
  padding: 10px 14px;
  border: 2px solid rgba(255, 45, 122, 0.2);
  border-radius: 8px;
  background: rgba(255, 45, 122, 0.03);
  color: var(--text);
  font-size: 14px;
  transition: all 0.3s ease;
  font-family: inherit;
}

.newsletter-input::placeholder {
  color: var(--muted);
}

.newsletter-input:focus {
  outline: none;
  border-color: var(--accent);
  background: rgba(255, 45, 122, 0.08);
  box-shadow: 0 0 0 3px rgba(255, 45, 122, 0.1);
}

.newsletter-btn {
  padding: 10px 16px;
  background: var(--accent);
  color: #fff;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: inherit;
}

.newsletter-btn:hover {
  background: var(--accent-2);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 45, 122, 0.3);
}

.newsletter-btn:active {
  transform: translateY(0);
}

.newsletter-note {
  font-size: 11px;
  color: var(--muted);
  margin: 0;
  font-weight: 500;
}

/* Footer Bottom Section */
.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 30px;
  border-top: 2px solid rgba(255, 45, 122, 0.08);
  flex-wrap: wrap;
  gap: 20px;
}

.footer-credits {
  flex: 1;
  min-width: 250px;
}

.footer-credits p {
  margin: 0;
  font-size: 13px;
  color: var(--muted);
}

/* Footer Stats */
.footer-stats {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.stat-item {
  font-size: 13px;
  color: var(--muted);
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: rgba(255, 45, 122, 0.05);
  border-radius: 20px;
  transition: all 0.3s ease;
}

.stat-item:hover {
  background: rgba(255, 45, 122, 0.1);
  color: var(--accent);
}

/* Dark Mode Footer */
html.dark-mode footer {
  border-top-color: rgba(255, 45, 122, 0.2);
}

html.dark-mode .footer-bottom {
  border-top-color: rgba(255, 45, 122, 0.1);
}

/* Animations */
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

/* Responsive */
@media (max-width: 768px) {
  .footer-container {
    padding: 35px 20px;
  }

  .footer-top {
    gap: 30px;
  }

  .footer-bottom {
    flex-direction: column;
    text-align: center;
  }

  .footer-stats {
    justify-content: center;
  }

  .footer-credits p {
    font-size: 12px;
  }
}

@media (max-width: 480px) {
  .footer-container {
    padding: 25px 15px;
  }

  .footer-top {
    grid-template-columns: 1fr;
    gap: 25px;
  }

  .social-links {
    gap: 10px;
  }

  .social-icon {
    width: 36px;
    height: 36px;
    font-size: 14px;
  }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
