<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'database/dbconnection.php';


// Protect page: redirect if not logged in
if(!isset($_SESSION['user_email'])){
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION['user_email'];

// Fetch bookings for this user
$sql = "
    SELECT b.*, d.name AS destName 
    FROM bookings b
    LEFT JOIN destinations d ON b.destination_id = d.id
    WHERE b.user_email = ?
    ORDER BY b.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$bookings = $stmt->get_result();
?>

<main>
  <section class="active">
    <div class="wrap">

      <h1>My Bookings 🛫</h1>
      <p style="color: var(--muted); margin-bottom: 20px;">
        All your confirmed reservations will appear here.
      </p>

      <div id="bookingsList">

        <?php if($bookings->num_rows > 0): ?>
            <?php while($b = $bookings->fetch_assoc()): ?>
            <div class="booking-item">
              <div>
                <h3><?php echo htmlspecialchars($b['destName']); ?></h3>
                <p style="color: var(--muted); margin:0;">
                  <?php echo ucfirst($b['mode']); ?> | <?php echo ucfirst($b['class']); ?> Class
                </p>
                <p style="color: var(--muted); margin:0;">
                  Date: <?php echo $b['travel_date']; ?> | <?php echo $b['passengers']; ?> Passenger<?php echo $b['passengers'] > 1 ? 's' : ''; ?>
                </p>
              </div>
              <div>
                <strong>Rs. <?php echo number_format($b['total_price']); ?></strong>
                <br>
                <button class="ghost" onclick="downloadTicket('<?php echo addslashes($b['destName']); ?>', '<?php echo $b['travel_date']; ?>', <?php echo $b['passengers']; ?>, '<?php echo $b['class']; ?>', '<?php echo ucfirst($b['mode']); ?>', <?php echo $b['total_price']; ?>)">
                  Download PDF
                </button>
              </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="muted" id="noBookingsMsg">
              You currently have no bookings.
            </p>
        <?php endif; ?>

      </div>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function downloadTicket(dest, date, passengers, classType, mode, total) {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  doc.setFontSize(22);
  doc.text("Wanderly Ticket", 105, 20, null, null, "center");

  doc.setFontSize(16);
  doc.text(`Destination: ${dest}`, 20, 40);
  doc.text(`Date: ${date}`, 20, 50);
  doc.text(`Passengers: ${passengers}`, 20, 60);
  doc.text(`Class: ${classType}`, 20, 70);
  doc.text(`Mode: ${mode}`, 20, 80);
  doc.text(`Total Paid: Rs. ${total}`, 20, 90);

  doc.save(`${dest}_ticket.pdf`);
}
</script>
