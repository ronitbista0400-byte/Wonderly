<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $darkMode = isset($_POST['darkMode']) ? (int)$_POST['darkMode'] : 0;
    $_SESSION['dark_mode'] = $darkMode === 1 ? true : false;
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'darkMode' => $_SESSION['dark_mode']]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
