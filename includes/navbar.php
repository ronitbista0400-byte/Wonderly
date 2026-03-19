<?php
session_start(); // Start session if not already
$userLoggedIn = isset($_SESSION['user_email']); // Check if user is logged in
$currentPage = basename($_SERVER['PHP_SELF']); // Get current page
$darkMode = isset($_SESSION['dark_mode']) ? $_SESSION['dark_mode'] : false; // Check dark mode
?>

<header>
  <!-- LEFT SECTION: BRAND + THEME TOGGLE -->
  <div class="header-left">
    <div class="brand">
      <div class="logo-container">
        <a href="index.php">
          <img src="assets/images/logo.png" class="logo-img" alt="Wanderly Logo">
        </a>
      </div>
      <div class="brand-text">
        <h1>Wanderly</h1>
        <p class="muted">Special Tourism Experience</p>
      </div>
    </div>

    <!-- THEME TOGGLE -->
    <button id="themeToggle" class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
      <span id="themeIcon" class="theme-icon">🌙</span>
    </button>
  </div>

  <!-- MIDDLE SECTION: NAVIGATION -->
  <nav id="mainNav">
    <a href="index.php" class="nav-link <?php echo ($currentPage=='index.php') ? 'active' : ''; ?>">
      <span>🏠 Home</span>
    </a>
    <a href="places.php" class="nav-link <?php echo ($currentPage=='places.php') ? 'active' : ''; ?>">
      <span>🌍 Destinations</span>
    </a>

    <?php if($userLoggedIn): ?>
      <a href="booking.php" class="nav-link <?php echo ($currentPage=='booking.php') ? 'active' : ''; ?>">
        <span>🎫 Bookings</span>
      </a>
      <a href="saved.php" class="nav-link <?php echo ($currentPage=='saved.php') ? 'active' : ''; ?>">
        <span>❤️ Saved</span>
      </a>
    <?php endif; ?>

    <a href="info.php" class="nav-link <?php echo ($currentPage=='info.php') ? 'active' : ''; ?>">
      <span>ℹ️ Info</span>
    </a>
  </nav>

  <!-- RIGHT SECTION: ACTIONS -->
  <div class="header-right">
    <?php if($userLoggedIn): ?>
      <div class="user-menu">
        <button class="btn user-btn" onclick="toggleLoginMenu()">
          <span class="user-icon">👤</span>
          <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
          <span class="dropdown-arrow">⬇</span>
        </button>
        <div id="loginMenu" class="login-dropdown" style="display:none;">
          <a href="profile.php" class="logout-link">👤 Profile</a>
          <a href="logout.php" class="logout-link">🚪 Logout</a>
        </div>
      </div>
    <?php else: ?>
      <a href="login.php" class="btn login-btn">
        <span>🔐 Login</span>
      </a>
    <?php endif; ?>
  </div>
</header>

<style>
/* ===== ENHANCED HEADER ===== */
header {
  position: sticky;
  top: 0;
  z-index: 1001;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 25px;
  background: var(--card);
  border-bottom: 2px solid rgba(255, 45, 122, 0.1);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  gap: 20px;
}

/* Header sections */
.header-left {
  display: flex;
  align-items: center;
  gap: 20px;
  min-width: fit-content;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 15px;
  min-width: fit-content;
}

/* ===== BRAND ===== */
.brand {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  cursor: pointer;
}

.logo-container {
  width: 45px;
  height: 45px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border-radius: 50%;
  background: rgba(255, 45, 122, 0.1);
  transition: all 0.3s ease;
}

.logo-container:hover {
  background: rgba(255, 45, 122, 0.2);
  transform: scale(1.05);
}

.logo-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}

.brand-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.brand-text h1 {
  font-size: 18px;
  margin: 0;
  font-weight: 700;
  color: var(--text);
  letter-spacing: 0.5px;
}

.brand-text p {
  font-size: 11px;
  margin: 0;
  color: var(--muted);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.8px;
}

/* ===== THEME TOGGLE ===== */
.theme-toggle {
  background: none;
  border: 2.5px solid var(--accent);
  border-radius: 50%;
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 22px;
  transition: all 0.3s ease;
  color: var(--accent);
  padding: 0;
  min-width: 42px;
  min-height: 42px;
}

.theme-toggle:hover {
  background: rgba(255, 45, 122, 0.1);
  transform: scale(1.12) rotate(25deg);
  box-shadow: 0 6px 16px rgba(255, 45, 122, 0.25);
  border-color: var(--accent-2);
  color: var(--accent-2);
}

.theme-toggle:active {
  transform: scale(0.95) rotate(0deg);
}

.theme-icon {
  display: inline-block;
  transition: transform 0.4s ease;
}

/* ===== NAVIGATION ===== */
nav#mainNav {
  display: flex;
  align-items: center;
  gap: 5px;
  flex: 1;
  justify-content: center;
  padding: 0 10px;
}

.nav-link {
  text-decoration: none;
  color: var(--muted);
  padding: 8px 16px;
  font-weight: 600;
  border-radius: 25px;
  font-size: 13px;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  position: relative;
  white-space: nowrap;
  overflow: hidden;
}

.nav-link span {
  display: flex;
  align-items: center;
  gap: 6px;
}

.nav-link:hover {
  color: var(--accent);
  background: rgba(255, 45, 122, 0.08);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 45, 122, 0.1);
}

.nav-link.active {
  color: #fff;
  background: var(--accent);
  box-shadow: 0 4px 15px rgba(255, 45, 122, 0.3);
}

.nav-link.active:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(255, 45, 122, 0.4);
}

/* ===== ACTION BUTTONS ===== */
.user-btn,
.login-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 16px !important;
  font-size: 14px !important;
  border-radius: 25px !important;
  cursor: pointer !important;
  transition: all 0.3s ease !important;
  font-weight: 600 !important;
}

.user-btn {
  border: 2px solid var(--accent) !important;
  color: var(--accent) !important;
  background: rgba(255, 45, 122, 0.05) !important;
}

.user-btn:hover {
  background: rgba(255, 45, 122, 0.12) !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 45, 122, 0.2) !important;
  border-color: var(--accent-2) !important;
  color: var(--accent-2) !important;
}

.login-btn {
  background: var(--accent) !important;
  color: #fff !important;
  border: none !important;
}

.login-btn:hover {
  background: var(--accent-2) !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(255, 45, 122, 0.3) !important;
}

.user-icon {
  font-size: 16px;
}

.dropdown-arrow {
  font-size: 12px;
  transition: transform 0.3s ease;
}

/* ===== LOGIN DROPDOWN ===== */
.user-menu {
  position: relative;
}

.login-dropdown {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  width: 200px;
  padding: 12px 0;
  display: none;
  background: var(--card);
  border-radius: 15px;
  border: 2px solid rgba(255, 45, 122, 0.15);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  z-index: 2001;
  animation: dropdownSlideIn 0.2s ease;
}

@keyframes dropdownSlideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.logout-link {
  display: block;
  padding: 12px 20px;
  text-decoration: none;
  color: var(--text);
  transition: all 0.2s ease;
  font-weight: 500;
  font-size: 14px;
}

.logout-link:hover {
  background: rgba(255, 45, 122, 0.1);
  color: var(--accent);
  padding-left: 26px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  header {
    padding: 10px 15px;
    gap: 15px;
    flex-wrap: wrap;
  }

  nav#mainNav {
    gap: 2px;
    flex-basis: 100%;
    order: 3;
  }

  .nav-link {
    padding: 6px 12px;
    font-size: 12px;
  }

  .nav-link span {
    gap: 4px;
  }

  .brand-text h1 {
    font-size: 16px;
  }

  .brand-text p {
    font-size: 9px;
  }
}
</style>

<script>
// Initialize theme on page load
function initializeTheme() {
  const isDarkMode = localStorage.getItem('darkMode') === 'true';
  const themeIcon = document.getElementById('themeIcon');
  
  if(isDarkMode) {
    document.documentElement.classList.add('dark-mode');
    if(themeIcon) themeIcon.textContent = '☀️';
  } else {
    document.documentElement.classList.remove('dark-mode');
    if(themeIcon) themeIcon.textContent = '🌙';
  }
}

// Toggle theme function
function toggleTheme() {
  const htmlElement = document.documentElement;
  const isDarkMode = htmlElement.classList.toggle('dark-mode');
  const themeIcon = document.getElementById('themeIcon');
  
  if(themeIcon) {
    themeIcon.textContent = isDarkMode ? '☀️' : '🌙';
  }
  
  // Save to localStorage
  localStorage.setItem('darkMode', isDarkMode);
  
  // Also save to server via AJAX
  fetch('includes/theme-handler.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'darkMode=' + (isDarkMode ? 1 : 0)
  });
}

// Initialize theme on page load
window.addEventListener('DOMContentLoaded', initializeTheme);
if(document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeTheme);
} else {
  initializeTheme();
}

// Toggle login dropdown menu
function toggleLoginMenu(){
  const menu = document.getElementById('loginMenu');
  menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

// Optional: close dropdown when clicking outside
document.addEventListener('click', function(e){
  const menu = document.getElementById('loginMenu');
  if(menu && !menu.contains(e.target) && !e.target.closest('.user-menu')){
    menu.style.display='none';
  }
});
</script>
