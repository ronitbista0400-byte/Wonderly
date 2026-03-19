<style>
    .sidebar {
    width: 220px;
    background: #1a1a2e;
    color: white;
    min-height: 100vh;
    padding-top: 30px;
    position: fixed;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 22px;
}

.sidebar a {
    display: block;
    padding: 12px 20px;
    color: white;
    margin: 5px 0;
    border-radius: 8px;
    transition: 0.2s;
    text-decoration: none;
}

.sidebar a:hover, .sidebar a.active {
    background: #ff2d7a;
}

.main-content {
    margin-left: 220px;
    padding: 30px;
    width: 100%;
}
</style>
<div class="sidebar">
    <h2>Wanderly Admin</h2>
    <a href="index.php" class="<?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
    <a href="add_destination.php" class="<?php echo $currentPage == 'add_destination' ? 'active' : ''; ?>">Add Destination</a>
    <a href="user_management.php" class="<?php echo $currentPage == 'user_management' ? 'active' : ''; ?>">User Management</a>
    <a href="logout.php">Logout</a>
</div>