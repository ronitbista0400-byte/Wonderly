<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../database/dbconnection.php';

// Get the destination ID from URL
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: index.php");
    exit;
}

$destId = $_GET['id'];
$msg = '';

// Fetch existing destination data
$stmt = $conn->prepare("SELECT * FROM destinations WHERE id=?");
$stmt->bind_param("i", $destId);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0){
    header("Location: index.php");
    exit;
}
$destination = $result->fetch_assoc();

// Handle form submission
if(isset($_POST['update'])){
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // ===== VALIDATION =====
    if(empty($name)){
        $msg = "Destination name cannot be empty!";
    } elseif (!preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        $msg = "Destination name can only contain letters, numbers, and spaces!";
    } elseif (!is_numeric($price) || $price <= 0) {
        $msg = "Price must be a positive number!";
    } elseif ($price > 1000000) {
        $msg = "Price is too large!";
    } elseif (empty($description)) {
        $msg = "Description cannot be empty!";
    } else {
        // Check if a new image is uploaded
        $imgName = $destination['image'];
        if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
            $imgName = time().'_'.basename($_FILES['image']['name']);
            $imgTmp = $_FILES['image']['tmp_name'];
            $imgPath = '../assets/'.$imgName;

            // Validate image type
            $allowedTypes = ['image/jpeg','image/jpg','image/png','image/gif'];
            if(!in_array($_FILES['image']['type'], $allowedTypes)){
                $msg = "Only JPG, PNG, GIF images are allowed!";
            } elseif(move_uploaded_file($imgTmp, $imgPath)){
                // Delete old image
                if(file_exists('../assets/'.$destination['image'])){
                    unlink('../assets/'.$destination['image']);
                }
            } else {
                $msg = "Failed to upload new image!";
            }
        }

        // Update database if no error
        if($msg == ''){
            $stmt = $conn->prepare("UPDATE destinations SET name=?, price=?, description=?, image=? WHERE id=?");
            $stmt->bind_param("sissi", $name, $price, $description, $imgName, $destId);
            if($stmt->execute()){
                header("Location: add_destination.php");
                exit;
            } else {
                $msg = "Database error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Destination | Admin Panel</title>
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

input, textarea {
    width: 100%;
    padding: 12px 14px;
    margin: 10px 0;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.12);
    font-size: 14px;
}

textarea {
    min-height: 120px;
    resize: vertical;
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

.msg {
    text-align: center;
    margin-bottom: 15px;
    color: red;
    font-weight: bold;
}

.current-img {
    display: block;
    margin-bottom: 15px;
    max-width: 100%;
    border-radius: 10px;
}
</style>
</head>
<body>

<?php
include 'aside.php';
?>

<div class="main-content">
    <h1>Edit Destination</h1>

    <div class="panel">
        <?php if($msg != '') echo "<div class='msg'>$msg</div>"; ?>

        <form method="post" enctype="multipart/form-data">
            <label>Destination Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($destination['name']); ?>" placeholder="Destination Name" required>

            <label>Price (Rs.)</label>
            <input type="number" name="price" value="<?php echo $destination['price']; ?>" placeholder="Price in Rs." required>

            <label>Description</label>
            <textarea name="description" placeholder="Description" rows="5" required><?php echo htmlspecialchars($destination['description']); ?></textarea>

            <p>Current Image:</p>
            <img src="../assets/<?php echo $destination['image']; ?>" class="current-img" alt="Current Image">

            <label>New Image (optional)</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="update">Update Destination</button>
        </form>
    </div>
</div>

</body>
</html>
