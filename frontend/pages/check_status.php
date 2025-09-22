<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../backend/config/db.php'; // adjust path to your db.php

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['redirect' => true]);
    exit;
}

// Fetch the current status from the database
$stmt = $pdo->prepare("SELECT status FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$status = $stmt->fetchColumn();

// Check if allowedStatus is passed via GET or POST
if (isset($_GET['allowedStatus'])) {
    $allowedStatus = json_decode($_GET['allowedStatus'], true);
} elseif (isset($_POST['allowedStatus'])) {
    $allowedStatus = json_decode($_POST['allowedStatus'], true);
} else {
    // Default allowed statuses
    $allowedStatus = ['Approved', 'Verified'];
}

// Validate status
if (!in_array($status, $allowedStatus)) {
    echo json_encode(['redirect' => true]);
} else {
    echo json_encode(['redirect' => false]);
}
