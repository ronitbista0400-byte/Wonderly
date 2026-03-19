<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php


// Initialize dark mode session if not set
if(!isset($_SESSION['dark_mode'])){
    $_SESSION['dark_mode'] = false; // default light mode
}

// Toggle dark mode when user clicks button
if(isset($_GET['toggle_dark'])){
    $_SESSION['dark_mode'] = !$_SESSION['dark_mode'];
    // Reload the page without GET param
    $currentPage = basename($_SERVER['PHP_SELF']);
    header("Location: ".$currentPage);
    exit;
}

// Check current mode
$darkMode = $_SESSION['dark_mode'];
?>

<main id="mainContent">

 <!-- ================= HERO SECTION ================= -->
<section id="home" class="active">
  <div class="wrap">
    <div class="hero-flex">

      <!-- Hero Text -->
      <div class="hero-card">
        <h1>Explore Nepal’s Magic 🇳🇵</h1>
        <p>
          Discover hidden gems like Mustang’s ancient caves,
          Pokhara’s serene lakes, and Chitwan’s wild jungles.
        </p>
        <button class="btn" onclick="switchView('dest')">
          Start Exploring →
        </button>
      </div>

      <!-- Hero Slider -->
      <div class="slider-container">
        <div class="slider" id="heroSlider">

          <div class="slide active" style="background-image: url('assets/images/kathmandu.jpg');">
            <div class="slide-content">
              <h2>Spirit of Kathmandu</h2>
              <p>⛩️ Temples, culture & history</p>
            </div>
          </div>

          <div class="slide" style="background-image: url('assets/images/pokhara.jpg');">
            <div class="slide-content">
              <h2>Peaceful Pokhara</h2>
              <p>🏔️ Lakes, mountains & adventure</p>
            </div>
          </div>

          <div class="slide" style="background-image: url('assets/images/chitwan.jpg');">
            <div class="slide-content">
              <h2>Wild Chitwan</h2>
              <p>🐅 Jungle safari & wildlife</p>
            </div>
          </div>

        </div>

        <!-- Slide Indicators -->
        <div class="slider-indicators" id="indicators"></div>

        <!-- Slider Buttons -->
        <button class="slider-btn prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="slider-btn next" onclick="moveSlide(1)">&#10095;</button>
      </div>

    </div>
  </div>
</section>

<style>
/* Slider container */
.slider-container {
  position: relative;
  width: 100%;
  height: 450px;
  overflow: hidden;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Individual slides */
#heroSlider {
  position: relative;
  width: 100%;
  height: 100%;
}

.slide {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  opacity: 0;
  transform: scale(1.05);
  transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
}

.slide.active {
  opacity: 1;
  transform: scale(1);
  z-index: 1;
}

/* Slide overlay for better text readability */
.slide::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 50%, transparent 100%);
  z-index: 0;
}

/* Slide content */
.slide-content {
  position: absolute;
  bottom: 40px;
  left: 40px;
  right: 40px;
  color: #fff;
  text-shadow: 0 3px 8px rgba(0,0,0,0.5);
  z-index: 1;
  animation: slideUp 0.8s ease-in-out;
}

.slide-content h2 {
  font-size: 2.5em;
  margin: 0 0 10px 0;
  font-weight: bold;
  letter-spacing: 1px;
}

.slide-content p {
  font-size: 1.1em;
  margin: 0;
  opacity: 0.95;
  font-weight: 500;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Slider buttons */
.slider-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.85);
  border: none;
  font-size: 20px;
  padding: 10px 12px;
  cursor: pointer;
  border-radius: 50%;
  z-index: 2;
  transition: all 0.3s ease;
  color: #333;
  font-weight: bold;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.slider-btn:hover {
  background: rgba(255,255,255,1);
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 6px 15px rgba(0,0,0,0.3);
}

.slider-btn.prev { left: 15px; }
.slider-btn.next { right: 15px; }

/* Slide indicators (dots) */
.slider-indicators {
  position: absolute;
  bottom: 15px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 2;
}

.indicator {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.indicator.active {
  background: rgba(255,255,255,0.9);
  width: 30px;
  border-radius: 10px;
  transform: scale(1.1);
}

.indicator:hover {
  background: rgba(255,255,255,0.8);
}
</style>


<script>
let currentSlide = 0;
let autoSlideInterval;
const slides = document.querySelectorAll('#heroSlider .slide');

// Create indicators
function createIndicators() {
  const indicatorsContainer = document.getElementById('indicators');
  slides.forEach((_, index) => {
    const dot = document.createElement('div');
    dot.classList.add('indicator');
    if(index === 0) dot.classList.add('active');
    dot.onclick = () => {
      clearInterval(autoSlideInterval);
      showSlide(index);
      startAutoSlide();
    };
    indicatorsContainer.appendChild(dot);
  });
}

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.toggle('active', i === index);
  });
  
  // Update indicators
  document.querySelectorAll('.indicator').forEach((dot, i) => {
    dot.classList.toggle('active', i === index);
  });
  
  currentSlide = index;
}

// Next/Prev buttons
function moveSlide(step) {
  clearInterval(autoSlideInterval);
  let nextSlide = (currentSlide + step + slides.length) % slides.length;
  showSlide(nextSlide);
  startAutoSlide();
}

// Auto-slide every 3 seconds
function startAutoSlide() {
  autoSlideInterval = setInterval(() => {
    let nextSlide = (currentSlide + 1) % slides.length;
    showSlide(nextSlide);
  }, 3000);
}

// Initialize
createIndicators();
showSlide(0);
startAutoSlide();
</script>

  <!-- ================= DESTINATIONS ================= -->
  <section id="dest">
    <div class="wrap">
      <h1>
        Popular Destinations
        <span id="count" style="color:var(--accent)">0</span>
      </h1>

      <!-- FILTER BAR -->
      <div class="filter-bar" style="display:flex; gap:10px; margin-bottom:20px;">
        <input
          type="text"
          id="searchInput"
          placeholder="Search destination..."
          onkeyup="filterDestinations()"
          style="flex:1; padding:10px; border-radius:10px;"
        >

        <select id="priceFilter" onchange="filterDestinations()"
          style="padding:10px; border-radius:10px;">
          <option value="all">All Prices</option>
          <option value="6000">Under Rs. 6,000</option>
          <option value="15000">Under Rs. 15,000</option>
        </select>
      </div>

      <!-- DESTINATION CARDS -->
      <div class="grid" id="grid">
        <!-- JS / PHP will load destinations here -->
      </div>
    </div>
  </section>

  <!-- ================= MY BOOKINGS ================= -->
  <section id="mybookings">
    <div class="wrap">
      <h1>My Bookings</h1>
      <div id="bookingsList">
        <p class="muted">You have no bookings yet.</p>
      </div>
    </div>
  </section>

  <!-- ================= SAVED ================= -->
  <section id="saved">
    <div class="wrap">
      <h1>Your Wishlist ❤️</h1>
      <div class="grid" id="savedGrid"></div>
    </div>
  </section>

  <!-- ================= INFO ================= -->
  <section id="info">
    <div class="wrap">
      <h1>Essential Travel Information</h1>

      <div class="card" style="padding:20px; margin-bottom:15px;">
        <h3>🏔 Best Time to Visit</h3>
        <p>Spring (Mar–May) and Autumn (Sep–Nov) are the best seasons.</p>
      </div>

      <div class="card" style="padding:20px;">
        <h3>🛂 Travel Tips</h3>
        <p>Carry cash, respect local culture, and stay hydrated.</p>
      </div>
    </div>
  </section>

</main>

<!-- ================= BOOKING MODAL ================= -->
<div class="backdrop" id="backdrop">
  <div class="modal">

    <div class="modal-left">
      <h2 id="modalTitle"></h2>
      <img id="modalImg" src="" alt="">
      <div id="modalDesc"></div>
      <div id="modalHighlights" class="tags"></div>

      <button class="ghost" onclick="closeModal()" style="width:100%; margin-top:15px;">
        Close
      </button>
    </div>

    <div class="modal-right">
      <h3>Confirm Reservation</h3>

      <label>Travel Mode:</label><br>
      <input type="radio" name="method" value="flight" checked onchange="recalculate()"> ✈ Flight
      <input type="radio" name="method" value="bus" onchange="recalculate()"> 🚌 Bus

      <br><br>
      <label>Date:</label>
      <input type="date" id="bookDate" onchange="recalculate()">

      <br><br>
      <label>Passengers:</label>
      <input type="number" id="passengers" value="1" min="1" max="10" onchange="recalculate()">

      <br><br>
      <label>Class:</label>
      <select id="class" onchange="recalculate()">
        <option value="economy">Economy</option>
        <option value="first">First Class</option>
        <option value="vip">VIP</option>
      </select>

      <div class="price-box">
        Total: <span id="totalPrice">Rs. 0</span>
      </div>

      <button class="btn" style="width:100%;" onclick="confirmAndGenerate()">
        Book & Download PDF
      </button>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
