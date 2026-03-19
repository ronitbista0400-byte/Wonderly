<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    // Validate email
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // You can store this in a database or log file
        $newsletter_log = __DIR__ . '/../newsletter_subscribers.txt';
        
        // Add email with timestamp (simple storage)
        if(!file_exists($newsletter_log)) {
            file_put_contents($newsletter_log, "");
        }
        
        // Check if already subscribed
        $existing = file_get_contents($newsletter_log);
        if(strpos($existing, $email) === false) {
            file_put_contents($newsletter_log, $email . ' | ' . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Subscribed successfully!']);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid email address']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
