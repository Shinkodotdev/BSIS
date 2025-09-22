<?php
session_start();
header('Content-Type: application/json');

require_once "../config/db.php"; 

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in first.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$document_name = trim($_POST['document_name'] ?? '');
$purpose = trim($_POST['purpose'] ?? '');

// Validate input
if (empty($document_name) || empty($purpose)) {
    echo json_encode(['status' => 'error', 'message' => 'Document name and purpose are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO document_requests 
            (user_id, document_name, purpose, status, requested_at, is_deleted) 
        VALUES 
            (:user_id, :document_name, :purpose, 'Pending', NOW(), 0)
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':document_name' => $document_name,
        ':purpose' => $purpose
    ]);

    echo json_encode([
        'status' => 'success', 
        'message' => 'Your document request has been submitted successfully!'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
