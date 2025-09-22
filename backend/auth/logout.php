<?php
session_start();
require_once "../config/db.php";

// If user is logged in, clear their session_token in verifications
if (isset($_SESSION['user_id']) && $pdo) {
    $user_id = $_SESSION['user_id'];

    // Clear ALL session tokens for this user (so all tabs get logged out)
    $stmt = $pdo->prepare("
        UPDATE verifications
        SET session_token = NULL
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
}

// Destroy all session data but keep session active to store flash message
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Start a new session just for flash message
session_start();
$_SESSION['success'] = "You have been logged out successfully.";

// Redirect to login (no query string)
header("Location: ../../frontend/pages/login.php");
exit;
