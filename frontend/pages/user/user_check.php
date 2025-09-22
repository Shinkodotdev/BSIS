<?php 
// Redirect if not logged in
redirectIfNotLoggedIn(['../login.php'], $pdo);

// Get session values
$user_id       = $_SESSION['user_id'] ?? null;
$session_token = $_SESSION['session_token'] ?? null;

// Allowed roles and statuses
$allowedRoles  = ['Resident'];   // customize per page
$allowedStatus = ['Verified'];   // customize per page

// Path to unauthorized page
$unauthorizedPath = "../../pages/unauthorized_page.php";

// 🔎 Get role & status from users table
$stmt = $pdo->prepare("SELECT role, status FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$userCheck = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userCheck) {
    session_unset();
    session_destroy();
    header("Location: ../login.php?error=user_not_found");
    exit;
}

$dbRole   = $userCheck['role'];
$dbStatus = $userCheck['status'];

// 🔎 Role check
if (!in_array($dbRole, $allowedRoles)) {
    header("Location: $unauthorizedPath");
    exit;
}

// 🔎 Status check
if (!in_array($dbStatus, $allowedStatus)) {
    header("Location: $unauthorizedPath");
    exit;
}

// 🔎 Session token check (verifications table)
$stmt = $pdo->prepare("SELECT session_token FROM verifications WHERE user_id = ?");
$stmt->execute([$user_id]);
$dbToken = $stmt->fetchColumn();

if (!$dbToken || $dbToken !== $session_token) {
    // Destroy session if mismatch (possible hijack or invalid login)
    session_unset();
    session_destroy();
    header("Location: ../login.php?error=session_invalid");
    exit;
}
?>