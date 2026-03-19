<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../database/dbconnection.php';

$msg = '';
$type = '';

if(isset($_POST['add'])){

    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);

    // ===== VALIDATION =====
    if(empty($name) || empty($price) || empty($description)){
        $msg = "All fields are required";
        $type = 'error';
    }
    elseif(strlen($name) < 3){
        $msg = "Destination name must be at least 3 characters";
        $type = 'error';
    }
    elseif(!is_numeric($price) || $price <= 0){
        $msg = "Price must be a positive number";
        $type = 'error';
    }
    elseif($price > 1000000){
        $msg = "Price is too high";
        $type = 'error';
    }
    elseif(strlen($description) < 10){
        $msg = "Description must be at least 10 characters";
        $type = 'error';
    }
    elseif($_FILES['image']['name'] == ''){
        $msg = "Please choose an image";
        $type = 'error';
    }
    else{

        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if(!in_array($ext,$allowed)){
            $msg = "Only JPG, PNG or WEBP allowed";
            $type = 'error';
        }
        elseif($_FILES['image']['size'] > 2*1024*1024){
            $msg = "Image must be less than 2MB";
            $type = 'error';
        }
        else{

            $newName = time().".".$ext;
            $uploadPath = "../assets/".$newName;

            if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)){

                $stmt = $conn->prepare("INSERT INTO destinations (name,price,description,image) VALUES (?,?,?,?)");
                $stmt->bind_param("siss",$name,$price,$description,$newName);

                if($stmt->execute()){
                    $msg = "Destination added successfully";
                    $type = 'success';
                } else {
                    $msg = "Database error";
                    $type = 'error';
                }

            } else {
                $msg = "Image upload failed";
                $type = 'error';
            }
        }
    }
}

$destinations = $conn->query("SELECT d.*, COUNT(b.id) AS booking_count FROM destinations d LEFT JOIN bookings b ON b.destination_id = d.id GROUP BY d.id ORDER BY d.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin - Destinations</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:linear-gradient(135deg,#667eea,#764ba2);display:flex;}

.main{margin-left:220px;padding:30px;width:100%;}
.header{color:#fff;margin-bottom:20px;}
.header h1{font-size:28px;}

.card{background:#fff;border-radius:20px;padding:25px;box-shadow:0 20px 40px rgba(0,0,0,0.2);margin-bottom:25px;}

.form input,.form textarea{width:100%;padding:12px;border-radius:10px;border:1px solid #ddd;margin-top:10px;transition:0.3s;}
.form input:focus,.form textarea:focus{border-color:#667eea;outline:none;box-shadow:0 0 0 3px rgba(102,126,234,0.2);} 

button{margin-top:15px;padding:12px;width:100%;border:none;border-radius:12px;background:linear-gradient(135deg,#ff416c,#ff4b2b);color:#fff;font-weight:600;cursor:pointer;transition:0.3s;}
button:hover{transform:scale(1.03);} 

.alert{padding:12px;border-radius:10px;margin-bottom:15px;text-align:center;font-weight:500;}
.alert.success{background:#d1fae5;color:#065f46;}
.alert.error{background:#fee2e2;color:#991b1b;} 

.table{width:100%;border-collapse:collapse;}
.table th,.table td{padding:12px;text-align:left;}
.table th{background:#667eea;color:#fff;}
.table tr:nth-child(even){background:#f9fafb;}
.table tr:hover{background:#eef2ff;} 

.badge{background:#ff4b2b;color:#fff;padding:5px 10px;border-radius:20px;font-size:12px;} 

.actions a{padding:6px 10px;border-radius:8px;color:#fff;text-decoration:none;font-size:12px;margin-right:5px;}
.edit{background:#3b82f6;}
.delete{background:#ef4444;} 

img{width:60px;height:40px;border-radius:8px;object-fit:cover;}
</style>
</head>
<body>

<?php include 'aside.php'; ?>

<div class="main">

<div class="header">
<h1>🌍 Manage Destinations</h1>
</div>

<div class="card">
<?php if($msg): ?>
<div class="alert <?php echo $type; ?>"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="form">
<label>Name</label>
<input type="text" name="name" required minlength="3">

<label>Price</label>
<input type="number" name="price" min="1" max="1000000" required>

<label>Description</label>
<textarea name="description" required minlength="10"></textarea>

<label>Image</label>
<input type="file" name="image" accept="image/*" required>

<button name="add">Add Destination</button>
</form>
</div>

<div class="card">
<h3>📍 Destination List</h3>
<br>
<table class="table">
<thead>
<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Bookings</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

<?php while($row = $destinations->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><img src="../assets/<?php echo $row['image']; ?>"></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td>Rs. <?php echo number_format($row['price']); ?></td>
<td><span class="badge"><?php echo $row['booking_count']; ?></span></td>
<td><?php echo date('Y-m-d',strtotime($row['created_at'])); ?></td>
<td class="actions">
<a href="edit_destination.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
<a href="delete_destination.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</div>

</body>
</html>