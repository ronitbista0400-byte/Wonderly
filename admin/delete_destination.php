<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../database/dbconnection.php';

// Check if destination ID is provided
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$destId = $_GET['id'];

// Fetch the destination to get image name
$stmt = $conn->prepare("SELECT image FROM destinations WHERE id=?");
$stmt->bind_param("i", $destId);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    header("Location: index.php");
    exit;
}

$destination = $result->fetch_assoc();

// Delete related bookings first to avoid orphaned data
$stmt = $conn->prepare("DELETE FROM bookings WHERE destination_id = ?");
$stmt->bind_param("i", $destId);
$stmt->execute();

// Delete the image file if exists
if(file_exists('uploads/'.$destination['image'])){
    unlink('uploads/'.$destination['image']);
}

// Delete destination
$stmt = $conn->prepare("DELETE FROM destinations WHERE id=?");
$stmt->bind_param("i", $destId);
$stmt->execute();

// Redirect back to admin panel
header("Location: index.php");
exit;
?>
