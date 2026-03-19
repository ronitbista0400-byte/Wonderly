<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'database/dbconnection.php';


$user_name  = $_SESSION['user_name'] ?? 'Guest';
$user_email = $_SESSION['user_email'] ?? 'guest@example.com';

// Booking submission
if(isset($_POST['book_now']) && isset($_POST['destination_id'])) {

    $destination_id = (int)$_POST['destination_id'];
    $passengers     = (int)$_POST['passengers'];
    $travel_date    = $_POST['travel_date'];
    $mode           = $_POST['mode'];
    $class          = $_POST['class'];

    // Validate date - must be today or in the future
    $today = new DateTime('today');
    $booking_date = DateTime::createFromFormat('Y-m-d', $travel_date);
    
    if(!$booking_date || $booking_date < $today) {
        echo "<script>alert('Please select a date today or in the future. Past dates cannot be booked.');</script>";
    } else {
        // Get price from database securely
        $stmt = $conn->prepare("SELECT price FROM destinations WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $destination_id);
        $stmt->execute();
        $price_row = $stmt->get_result()->fetch_assoc();
        $base_price = $price_row['price'] ?? 0;

        // Apply class multiplier
        $class_multiplier = 1.0;
        if($class === 'first') {
            $class_multiplier = 1.5; // First Class: 50% more
        } elseif($class === 'vip') {
            $class_multiplier = 2.0; // VIP: Double the price
        }

        // Apply mode multiplier
        $mode_multiplier = 1.0;
        if($mode === 'flight') {
            $mode_multiplier = 2.0; // Flight: Double the price
        }

        // Calculate final price with both multipliers
        $price = $base_price * $class_multiplier * $mode_multiplier;
        $total_price = $price * $passengers;

        // Insert booking
        $stmt = $conn->prepare("
            INSERT INTO bookings 
            (user_name,user_email,destination_id,passengers,travel_date,mode,class,total_price)
            VALUES (?,?,?,?,?,?,?,?)
        ");
        $stmt->bind_param("ssissssd", $user_name, $user_email, $destination_id, $passengers, $travel_date, $mode, $class, $total_price);
        if($stmt->execute()){
            echo "<script>window.bookingSuccess=true;</script>";
        } else {
            echo "<script>alert('Error: Could not save booking.');</script>";
        }
    }
}

// Fetch destinations
$destinations = $conn->query("SELECT * FROM destinations ORDER BY id DESC");
?>

<main >
  <section class="active">
    <div class="wrap">

      <h1>Destinations <span id="count" style="color:var(--accent)"><?php echo $destinations->num_rows; ?></span></h1>

      <!-- Search Bar -->
      <div class="filter-bar" style="margin-bottom:20px; display:flex; gap:10px;">
        <form method="GET" action="search.php" style="flex:1; display:flex; gap:10px;">
            <input type="text" name="query" placeholder="Search destinations..." style="flex:1; padding:10px; border-radius:10px; border:1px solid var(--accent); background: var(--card); color: var(--text);" required>
            <button type="submit" class="btn">Search</button>
        </form>
      </div>

      <!-- Destinations Grid -->
      <div class="grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:20px;">
        <?php while($row = $destinations->fetch_assoc()): ?>
        <?php
          $imagePath = "assets/{$row['image']}";
          if(!file_exists($imagePath) || empty($row['image'])){
              $imagePath = "assets/images/placeholder.jpg";
          }
        ?>
        <div class="card" style="border-radius:10px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
          <div class="media" style="background-image:url('<?php echo $imagePath; ?>'); height:200px; background-size:cover; background-position:center;"></div>
          <div style="padding:15px;">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p style="color: var(--muted);"><?php echo htmlspecialchars($row['description']); ?></p>
            <strong>Rs. <?php echo number_format($row['price']); ?></strong>
            <br><br>
            <button class="btn" onclick="openBookingModal(<?php echo $row['id']; ?>, <?php echo $row['price']; ?>, '<?php echo $imagePath; ?>')">💺 Book</button>
            <button class="ghost" onclick="saveDestination('<?php echo $row['name']; ?>', '<?php echo $imagePath; ?>')">💖 Save</button>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
</main>

<!-- Booking Modal -->
<div class="backdrop" id="bookingModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
  <div class="modal" style="display:flex; max-width:900px; background:#fff; border-radius:10px; overflow:hidden;">
    <div class="modal-left" style="flex:1; padding:20px;">
      <h2 id="modalTitle"></h2>
      <img id="modalImg" src="" alt="" style="width:100%; height:250px; object-fit:cover; border-radius:10px;">
      <div id="modalDesc"></div>
    </div>
    <div class="modal-right" style="flex:1; padding:20px; background:#f9f9f9;">
      <div class="booking-panel">
        <h3>Confirm Reservation</h3>
        <form method="post" id="bookingForm">
          <input type="hidden" name="destination_id" id="formDestinationId">

          <label>Date:</label>
          <input type="date" name="travel_date" id="bookDate" required min="">
          <br><label>Passengers:</label>
          <input type="number" name="passengers" id="passengers" value="1" min="1" max="10" required>
          <br><label>Class:</label>
          <select name="class" id="class">
            <option value="economy">Economy</option>
            <option value="first">First Class</option>
            <option value="vip">VIP</option>
          </select>
          <br><label>Mode:</label>
          <select name="mode" id="mode">
            <option value="flight">Flight</option>
            <option value="bus">Bus</option>
          </select>

          <div class="price-box" style="margin:15px 0;">Total: <span id="totalPrice">Rs. 0</span></div>
          <button class="btn" type="submit" name="book_now">Book & Download PDF</button>
          <button type="button" class="ghost" onclick="closeModal()">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
let currentPrice = 0;

// Set minimum date to today
function setMinDate() {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  const minDate = `${year}-${month}-${day}`;
  document.getElementById('bookDate').min = minDate;
}

// Validate date - prevent past dates
document.getElementById('bookDate').addEventListener('change', function() {
  const selectedDate = new Date(this.value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  
  if(selectedDate < today) {
    alert('⚠️ You cannot book for past dates. Please select today or a future date.');
    this.value = '';
  }
});

function saveDestination(name,image){
  let saved = JSON.parse(localStorage.getItem('savedDestinations')) || [];
  if(!saved.some(i=>i.name===name)){
    saved.push({name:name,image:image});
    localStorage.setItem('savedDestinations', JSON.stringify(saved));
    alert('✨ ' + name + " added to your wishlist!");
  } else {
    alert('⭐ ' + name + " is already in your wishlist!");
  }
}

function openBookingModal(id, price, image){
  currentPrice = price;
  document.getElementById('modalTitle').innerText = 'Booking';
  document.getElementById('modalImg').src = image;
  document.getElementById('modalDesc').innerHTML = `<p style="color: var(--muted);">Price per passenger: Rs. ${price}</p>`;

  document.getElementById('formDestinationId').value = id;

  document.getElementById('bookingModal').style.display = 'flex';
  setMinDate();
  updateTotal();
}

function closeModal(){
  document.getElementById('bookingModal').style.display = 'none';
}

// Calculate price multipliers based on class and mode
function calculatePrice() {
  let basePrice = currentPrice;
  let classSelect = document.getElementById('class').value;
  let modeSelect = document.getElementById('mode').value;
  
  // Class multiplier
  let classMultiplier = 1.0;
  if(classSelect === 'first') {
    classMultiplier = 1.5; // First Class: 50% more
  } else if(classSelect === 'vip') {
    classMultiplier = 2.0; // VIP: Double
  }
  
  // Mode multiplier
  let modeMultiplier = 1.0;
  if(modeSelect === 'flight') {
    modeMultiplier = 2.0; // Flight: Double
  }
  
  // Combined price
  return basePrice * classMultiplier * modeMultiplier;
}

function updateTotal(){
  let passengers = parseInt(document.getElementById('passengers').value);
  let pricePerPassenger = calculatePrice();
  let total = pricePerPassenger * passengers;
  document.getElementById('totalPrice').innerText = "Rs. " + Math.round(total);
}

document.getElementById('passengers').addEventListener('input', updateTotal);
document.getElementById('class').addEventListener('change', updateTotal);
document.getElementById('mode').addEventListener('change', updateTotal);

document.getElementById('bookingForm').addEventListener('submit', function(e){
  // Validate date before submission
  const selectedDate = new Date(document.getElementById('bookDate').value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  
  if(!document.getElementById('bookDate').value) {
    e.preventDefault();
    alert('⚠️ Please select a travel date.');
    return false;
  }
  
  if(selectedDate < today) {
    e.preventDefault();
    alert('⚠️ You cannot book for past dates. Please select today or a future date.');
    return false;
  }

  setTimeout(()=>{
    let destId = document.getElementById('formDestinationId').value;
    let date = document.getElementById('bookDate').value;
    let passengers = document.getElementById('passengers').value;
    let classType = document.getElementById('class').value;
    let mode = document.getElementById('mode').value;
    let pricePerPassenger = calculatePrice();
    let total = pricePerPassenger * passengers;

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(22);
    doc.text("Wanderly Ticket", 105, 20, null, null, "center");
    doc.setFontSize(16);
    doc.text(`Destination ID: ${destId}`, 20, 40);
    doc.text(`Date: ${date}`, 20, 50);
    doc.text(`Passengers: ${passengers}`, 20, 60);
    doc.text(`Class: ${classType}`, 20, 70);
    doc.text(`Mode: ${mode}`, 20, 80);
    doc.text(`Total Paid: Rs. ${total}`, 20, 90);
    doc.save(`ticket_${destId}.pdf`);
  },500);
});
</script>
