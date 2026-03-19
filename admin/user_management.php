<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../database/dbconnection.php';

// Handle delete
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_id'])){
    $userId = (int)$_GET['user_id'];
    $stmt = $conn->prepare("DELETE FROM website_users WHERE id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    header("Location: user_management.php?deleted=1");
    exit;
}

// Fetch users
$users = $conn->query("SELECT * FROM website_users ORDER BY created_at DESC");

// Current page for sidebar
$currentPage = 'user_management';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management | Admin Panel</title>
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

.panel {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
}

.panel h2 {
    margin-top: 0;
    font-size: 24px;
    letter-spacing: 0.5px;
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

.success {
    color: #16a34a;
    text-align: center;
    margin-bottom: 16px;
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
    <h1>User Management</h1>

    <?php if(isset($_GET['deleted'])): ?>
      <div class="success">User deleted successfully.</div>
    <?php endif; ?>

    <div class="panel">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while($user = $users->fetch_assoc()): ?>
                <tr>
                  <td><?php echo (int)$user['id']; ?></td>
                  <td><?php echo htmlspecialchars($user['name']); ?></td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($user['created_at']))); ?></td>
                  <td class="actions">
                    <a class="edit" href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                    <a class="delete" href="?action=delete&user_id=<?php echo $user['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
    </div>
</div>

</body>
</html>
</content>
<parameter name="filePath">c:\xampp\htdocs\travel\admin\user_management.php