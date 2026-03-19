<?php
include 'database/dbconnection.php';
session_start();

$error = '';
$success = '';

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM website_users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $error = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO website_users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if($stmt->execute()){
            $success = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Wanderly</title>
<style>
body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #72EDF2 0%, #5151E5 100%);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin:0;
}

.container {
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    text-align: center;
}

h1 {
    margin-bottom: 20px;
    color: #333;
}

input[type="text"], input[type="email"], input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 16px;
}

button {
    width: 100%;
    padding: 12px;
    background: #5151E5;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    margin-top: 15px;
    transition: 0.3s;
}

button:hover {
    background: #3333ff;
}

p {
    margin-top: 15px;
    font-size: 14px;
}

p a {
    color: #5151E5;
    text-decoration: none;
    font-weight: bold;
}

p a:hover {
    text-decoration: underline;
}

.error {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
}

.success {
    color: green;
    font-size: 14px;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="container">
    <h1>Register</h1>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <?php if($success) echo "<div class='success'>$success</div>"; ?>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
