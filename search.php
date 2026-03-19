<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'database/dbconnection.php';


$searchQuery = $_GET['query'] ?? '';

$results = [];
if(!empty($searchQuery)){
    $sql = "SELECT * FROM destinations WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%$searchQuery%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<main>
  <section class="active">
    <div class="wrap">
      <h1>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h1>

      <?php if($results && $results->num_rows > 0): ?>
        <div class="grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:20px;">
          <?php while($row = $results->fetch_assoc()): ?>
          <div class="card" style="border-radius:10px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
            <div class="media" style="background-image:url('assets/<?php echo $row['image']; ?>'); height:200px; background-size:cover; background-position:center;"></div>
            <div style="padding:15px;">
              <h3><?php echo htmlspecialchars($row['name']); ?></h3>
              <p style="color: var(--muted);"><?php echo htmlspecialchars($row['description']); ?></p>
              <strong>Rs. <?php echo number_format($row['price']); ?></strong>
              <br><br>
              <button class="btn" onclick="openBookingModal('<?php echo $row['name']; ?>', <?php echo $row['price']; ?>, '<?php echo $row['image']; ?>')">💺 Book</button>
              <button class="ghost" onclick="saveDestination('<?php echo $row['name']; ?>')">💖 Save</button>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p style="color:var(--muted);">No destinations found matching "<?php echo htmlspecialchars($searchQuery); ?>".</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
