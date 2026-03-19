<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../database/dbconnection.php';

// Get user ID
if(!isset($_GET['id'])){
    header("Location: user_management.php");
    exit;
}

$userId = (int)$_GET['id'];
$msg = '';

// Fetch user
$stmt = $conn->prepare("SELECT * FROM website_users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0){
    header("Location: user_management.php");
    exit;
}
$user = $result->fetch_assoc();

// Handle update
if(isset($_POST['update'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if($password != ''){
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE website_users SET name=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $hashed, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE website_users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $userId);
    }

    if($stmt->execute()){
        $msg = "User updated successfully.";
    } else {
        $msg = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User | Admin Panel</title>
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
    max-width: 500px;
}

.panel h2 {
    margin-top: 0;
    font-size: 24px;
    letter-spacing: 0.5px;
}

input {
    width: 100%;
    padding: 12px 14px;
    margin: 10px 0;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.12);
    font-size: 14px;
}

button {
    background: #ff2d7a;
    color: #fff;
    padding: 12px 18px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    width: 100%;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 26px rgba(255,45,122,0.25);
}

.success {
    color: #16a34a;
    text-align: center;
    margin-bottom: 16px;
}

.error {
    color: #b91c1c;
    text-align: center;
    margin-bottom: 16px;
}
</style>
</head>
<body>
<?php
include 'aside.php';
?>
<div class="sidebar">
    <h2>Wanderly Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="add_destination.php">Add Destination</a>
    <a href="user_management.php" class="active">User Management</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <h1>Edit User</h1>

    <div class="panel">
        <?php if($msg): ?>
          <div class="<?php echo strpos($msg, 'success') !== false ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <form method="post">
          <label>Name</label>
          <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

          <label>Email</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

          <label>New Password (leave blank to keep current)</label>
          <input type="password" name="password" placeholder="Enter new password">

          <button name="update" type="submit">Update User</button>
        </form>
    </div>
</div>

</body>
</html>
</content>
<parameter name="filePath">c:\xampp\htdocs\travel\admin\edit_user.php