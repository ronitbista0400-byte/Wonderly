<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../database/dbconnection.php';

// Count total destinations
$destCount = $conn->query("SELECT COUNT(*) as total FROM destinations")->fetch_assoc()['total'];
$bookingCount = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];

// Destinations list with booking counts (normalized view)
$destinations = $conn->query(
    "SELECT d.*, COUNT(b.id) AS booking_count " .
    "FROM destinations d " .
    "LEFT JOIN bookings b ON b.destination_id = d.id " .
    "GROUP BY d.id " .
    "ORDER BY d.created_at DESC"
);

// Current page for sidebar
$currentPage = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel | Wanderly</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* ===== ADMIN PANEL STYLES ===== */
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background: #f4f6f8;
    display: flex;
}


.main-content {
    margin-left: 220px;
    padding: 30px;
    width: 100%;
}

.dashboard-cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.card {
    flex: 1 1 200px;
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.card h3 {
    margin-bottom: 10px;
    color: #ff2d7a;
}

.card p {
    font-size: 20px;
    font-weight: bold;
}

/* Table + Booking Search */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 50px;
    gap: 20px;
    flex-wrap: wrap;
}

.section-header h2 {
    margin: 0;
    font-size: 24px;
    letter-spacing: 0.5px;
}

.section-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-actions input {
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.15);
    width: 260px;
    transition: all 0.25s ease;
}

.section-actions input:focus {
    outline: none;
    border-color: rgba(255,45,122,0.6);
    box-shadow: 0 0 0 4px rgba(255,45,122,0.15);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ff2d7a, #ff8a63);
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 12px 26px rgba(255,45,122,0.25);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 30px rgba(255,45,122,0.30);
}

.table-wrapper {
    max-height: 580px;
    overflow-y: auto;
    border-radius: 16px;
    border: 2px solid rgba(255,45,122,0.2);
    box-shadow: 0 18px 45px rgba(0,0,0,0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    min-width: 900px;
}

th, td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid rgba(0,0,0,0.08);
}

th {
    background: rgba(255,45,122,0.95);
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-size: 13px;
}

tbody tr:nth-child(even) {
    background: rgba(255,45,122,0.05);
}

tbody tr:hover {
    background: rgba(255,45,122,0.12);
}

.actions a {
    display: inline-block;
    margin-right: 10px;
    padding: 8px 14px;
    background: #ff2d7a;
    color: white;
    border-radius: 8px;
    font-size: 13px;
    text-decoration: none;
    transition: 0.2s;
}

.actions a.danger {
    background: #e02424;
}

.actions a:hover {
    opacity: 0.9;
}

@media (max-width: 1100px) {
    table {
        min-width: 0;
    }
}

</style>
</head>
<body>
<?php
include 'aside.php';
?>
<div class="main-content">
    <h1>Welcome, Ronit </h1>

    <!-- DASHBOARD CARDS -->
    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Destinations</h3>
            <p><?php echo $destCount; ?></p>
        </div>
        <div class="card">
            <h3>Total Bookings</h3>
            <p><?php echo $bookingCount; ?></p>
        </div>
    </div>

    <!-- DESTINATIONS SECTION -->
    <div class="section-header">
      <h2>All Destinations</h2>
      <div class="section-actions">
        <input id="destSearch" type="text" placeholder="Search destinations..." />
        <a class="btn" href="add_destination.php">+ Add Destination</a>
      </div>
    </div>

    <div class="table-wrapper">
      <table id="destTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Bookings</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $destinations->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row['id']; ?></td>
            <td><img src="../assets/<?php echo htmlspecialchars($row['image']); ?>" style="width:60px;height:40px;object-fit:cover;border-radius:10px;border:1px solid rgba(0,0,0,0.12);" alt=""></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>Rs. <?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo (int)$row['booking_count']; ?></td>
            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))); ?></td>
            <td class="actions">
              <a href="edit_destination.php?id=<?php echo $row['id']; ?>">Edit</a>
              <a href="delete_destination.php?id=<?php echo $row['id']; ?>" class="danger" onclick="return confirm('Delete this destination and all bookings?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- BOOKINGS TABLE -->
    <div class="section-header">
      <h2>All Bookings</h2>
      <div class="section-actions">
        <input id="bookingSearch" type="text" placeholder="Search bookings..." />
        <button class="btn" onclick="resetBookingSearch()">Reset</button>
      </div>
    </div>

    <div class="table-wrapper">
      <table id="bookingsTable">
        <thead>
          <tr>
            <th>User</th>
            <th>Email</th>
            <th>Amount</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $bookings = $conn->query("SELECT b.*, d.name as destName, d.image as destImage, d.price as destPrice FROM bookings b INNER JOIN destinations d ON b.destination_id=d.id ORDER BY b.id DESC");
        while($b = $bookings->fetch_assoc()){
            $safeName = htmlspecialchars($b['user_name'] ?: 'Guest');
            $safeEmail = htmlspecialchars($b['user_email'] ?: 'guest@example.com');
            $safeDest = htmlspecialchars($b['destName']);
            $safeDate = htmlspecialchars($b['travel_date']);
            echo "<tr>
                    <td>{$safeName}</td>
                    <td>{$safeEmail}</td>
                    <td>Rs. {$b['total_price']}</td>
                    <td class='actions'>
                      <a href='#' onclick='viewBooking({$b['id']}, \"{$safeDest}\", \"{$safeDate}\", {$b['passengers']}, {$b['total_price']}, \"" . htmlspecialchars($b['mode'] ?: 'N/A') . "\", \"" . htmlspecialchars($b['class'] ?: 'N/A') . "\")'>View</a>
                      <a href='?action=delete&booking_id={$b['id']}' class='danger'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
      </table>
    </div>

    <!-- VIEW BOOKING MODAL -->
    <div id="bookingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
      <div style="background:white; padding:30px; border-radius:16px; max-width:500px; width:90%; box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <h3 style="margin-top:0; color:#ff2d7a;">Booking Details</h3>
        <div id="modalContent"></div>
        <button onclick="closeModal()" style="margin-top:20px; background:#ff2d7a; color:white; border:none; padding:10px 20px; border-radius:10px; cursor:pointer;">Close</button>
      </div>
    </div>

    <script>
      const destSearch = document.getElementById('destSearch');
      const bookingSearch = document.getElementById('bookingSearch');
      const destTable = document.getElementById('destTable');
      const bookingsTable = document.getElementById('bookingsTable');

      function filterTable(searchInput, table) {
        const value = searchInput.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
          const text = row.innerText.toLowerCase();
          row.style.display = text.includes(value) ? '' : 'none';
        });
      }

      destSearch.addEventListener('input', () => filterTable(destSearch, destTable));
      bookingSearch.addEventListener('input', () => filterTable(bookingSearch, bookingsTable));

      function resetBookingSearch() {
        bookingSearch.value = '';
        bookingSearch.dispatchEvent(new Event('input'));
      }

      function resetDestSearch() {
        destSearch.value = '';
        destSearch.dispatchEvent(new Event('input'));
      }

      function viewBooking(id, dest, date, passengers, total, mode, classType) {
        const content = `
          <p><strong>Destination:</strong> ${dest}</p>
          <p><strong>Travel Date:</strong> ${date}</p>
          <p><strong>Passengers:</strong> ${passengers}</p>
          <p><strong>Total Amount:</strong> Rs. ${total}</p>
          <p><strong>Mode:</strong> ${mode}</p>
          <p><strong>Class:</strong> ${classType}</p>
        `;
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('bookingModal').style.display = 'flex';
      }

      function closeModal() {
        document.getElementById('bookingModal').style.display = 'none';
      }
    </script>
</div>

</body>
</html>
