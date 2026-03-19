<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'database/dbconnection.php';

// Protect page: redirect if not logged in
if(!isset($_SESSION['user_email'])){
    header('Location: login.php');
    exit;
}

$userEmail = $_SESSION['user_email'];
$userName = $_SESSION['user_name'] ?? 'Traveler';

// Fetch booking stats
$stmt = $conn->prepare("SELECT COUNT(*) AS totalBookings, SUM(total_price) AS totalSpent FROM bookings WHERE user_email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

$totalBookings = $stats['totalBookings'] ?? 0;
$totalSpent = $stats['totalSpent'] ?? 0;
?>

<main>
  <section class="active">
    <div class="wrap">
      <div style="display:flex; gap:30px; align-items:flex-start; flex-wrap:wrap;">
        <div style="flex:1; min-width:320px; max-width:420px;">
          <div class="card" style="padding:30px;">
            <h2 style="margin-top:0;">Hello, <?php echo htmlspecialchars($userName); ?> 👋</h2>
            <p style="color: var(--muted);">Here is your profile info and booking summary.</p>

            <div style="margin-top:25px;">
              <strong>Email</strong>
              <p style="margin:5px 0 18px 0; color: var(--muted);"><?php echo htmlspecialchars($userEmail); ?></p>

              <strong>Total Bookings</strong>
              <p style="margin:5px 0 18px 0; color: var(--muted);"><?php echo (int)$totalBookings; ?></p>

              <strong>Total Spent</strong>
              <p style="margin:5px 0 18px 0; color: var(--muted);">Rs. <?php echo number_format($totalSpent); ?></p>
            </div>

            <a href="booking.php" class="btn" style="width:100%;">View My Bookings</a>
            <a href="logout.php" class="ghost" style="width:100%; margin-top:10px;">Logout</a>
          </div>
        </div>

        <div style="flex:2; min-width:320px;">
          <div class="card" style="padding:30px;">
            <h2 style="margin-top:0;">Quick Actions</h2>
            <p style="color: var(--muted);">Use these shortcuts to manage your travel plans.</p>
            <div style="display:flex; gap:15px; flex-wrap:wrap; margin-top:20px;">
              <a href="places.php" class="btn" style="flex:1; min-width:140px;">Browse Destinations</a>
              <a href="saved.php" class="ghost" style="flex:1; min-width:140px;">View Saved</a>
              <a href="search.php" class="btn" style="flex:1; min-width:140px;">Search Trips</a>
              <a href="info.php" class="ghost" style="flex:1; min-width:140px;">Travel Guide</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
