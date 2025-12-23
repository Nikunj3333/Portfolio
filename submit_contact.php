<?php
// submit_contact.php

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

$host = 'localhost';       // Database host
$db   = 'portfolio';    // Your database name
$user = 'root';    // Your database user
$pass = '';// Your database password

// Validate POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($name) || empty($email) || empty($message)) {
    $response['message'] = 'Please fill in all fields.';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please enter a valid email address.';
    echo json_encode($response);
    exit;
}

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$db;contact_us", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);
    
    $response['success'] = true;
    $response['message'] = 'Your message has been saved!';
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>