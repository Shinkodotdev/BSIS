<?php
session_start();
header('Content-Type: application/json');

require_once "../config/db.php"; // adjust path

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$admin_id = $_SESSION['user_id'];
$request_id = intval($_POST['request_id'] ?? 0);
$status = $_POST['status'] ?? '';
$remarks = trim($_POST['remarks'] ?? '');
$attachment_path = trim($_POST['attachment_path'] ?? '');

// Validate
if ($request_id <= 0 || !in_array($status, ['Approved', 'Denied'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request or status.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE document_requests 
        SET status = :status, 
            processed_by = :processed_by, 
            processed_at = NOW(),
            remarks = :remarks,
            attachment_path = :attachment_path
        WHERE request_id = :request_id
    ");

    $stmt->execute([
        ':status' => $status,
        ':processed_by' => $admin_id,
        ':remarks' => $remarks ?: null,
        ':attachment_path' => $attachment_path ?: null,
        ':request_id' => $request_id
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Request updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
