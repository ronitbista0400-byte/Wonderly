<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../database/dbconnection.php';

// Handle actions
$action = $_GET['action'] ?? '';
$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if($action && $bookingId){
    if($action === 'delete'){
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
    }
    if($action === 'cancel'){
        $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
    }
    if($action === 'confirm'){
        $stmt = $conn->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
    }
    header('Location: bookings.php');
    exit;
}

// Fetch bookings with details
$sql = "SELECT b.*, u.name AS user_name, u.email AS user_email, d.name AS destName
        FROM bookings b
        LEFT JOIN users u ON b.user_id = u.id
        LEFT JOIN destinations d ON b.destination_id = d.id
        ORDER BY b.created_at DESC";
$bookings = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Bookings | Wanderly</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* Admin bookings specific */
.page-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
  gap: 20px;
}

.page-title h1 {
  margin: 0;
}

.search-row {
  display: flex;
  gap: 12px;
  align-items: center;
  flex-wrap: wrap;
}

.search-row input {
  padding: 10px 14px;
  border-radius: 12px;
  border: 1px solid rgba(0, 0, 0, 0.18);
  width: 300px;
}

.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.6px;
}

.status-confirmed { background: rgba(34, 197, 94, 0.16); color: #16a34a; }
.status-cancelled { background: rgba(239, 68, 68, 0.16); color: #b91c1c; }
.status-pending { background: rgba(245, 158, 11, 0.16); color: #b45309; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Wanderly Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="add_destination.php">Add Destination</a>
    <a href="bookings.php" class="active">Bookings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">

  <div class="page-title">
    <div>
      <h1>Bookings</h1>
      <p style="color: var(--muted);">Manage and review the latest reservations.</p>
    </div>
    <div class="search-row">
      <input id="bookingSearch" type="text" placeholder="Search by name, email, destination..." />
      <button class="btn" onclick="resetBookingSearch()">Reset</button>
    </div>
  </div>

  <div class="table-wrapper">
    <table id="bookingsTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Email</th>
          <th>Destination</th>
          <th>Date</th>
          <th>Passengers</th>
          <th>Total</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($b = $bookings->fetch_assoc()): ?>
          <?php
            $status = $b['status'] ?? 'confirmed';
            $statusClass = 'status-confirmed';
            if($status === 'cancelled') $statusClass = 'status-cancelled';
            if($status === 'pending') $statusClass = 'status-pending';

            $customerName = $b['user_name'] ?: ($b['user_email'] ?: 'Guest');
            $customerEmail = $b['user_email'] ?: '—';
            $dest = $b['destName'] ?: '—';
            $date = $b['travel_date'] ?: '—';
          ?>
          <tr>
            <td><?php echo (int)$b['id']; ?></td>
            <td><?php echo htmlspecialchars($customerName); ?></td>
            <td><?php echo htmlspecialchars($customerEmail); ?></td>
            <td><?php echo htmlspecialchars($dest); ?></td>
            <td><?php echo htmlspecialchars($date); ?></td>
            <td><?php echo (int)$b['passengers']; ?></td>
            <td>Rs. <?php echo number_format($b['total_price'], 2); ?></td>
            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst($status); ?></span></td>
            <td class="actions">
              <?php if($status !== 'confirmed'): ?>
                <a href="?action=confirm&booking_id=<?php echo $b['id']; ?>">Confirm</a>
              <?php endif; ?>
              <?php if($status !== 'cancelled'): ?>
                <a href="?action=cancel&booking_id=<?php echo $b['id']; ?>" class="danger">Cancel</a>
              <?php endif; ?>
              <a href="?action=delete&booking_id=<?php echo $b['id']; ?>" class="danger">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>

<script>
const bookingSearch = document.getElementById('bookingSearch');
const bookingsTable = document.getElementById('bookingsTable');

bookingSearch.addEventListener('input', function() {
  const value = this.value.toLowerCase();
  const rows = bookingsTable.querySelectorAll('tbody tr');

  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(value) ? '' : 'none';
  });
});

function resetBookingSearch() {
  bookingSearch.value = '';
  bookingSearch.dispatchEvent(new Event('input'));
}
</script>

</body>
</html>
