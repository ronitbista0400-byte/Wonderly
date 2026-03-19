<?php
include 'database/dbconnection.php';
session_start();

$error = '';

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM website_users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Wanderly</title>
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

input[type="email"], input[type="password"] {
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
</style>
</head>
<body>

<div class="container">
    <h1>Login</h1>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
