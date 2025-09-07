<?php
session_start();
require_once "../config/db.php";

// If user is logged in, remove the session token from verifications table
if (isset($_SESSION['user_id']) && $pdo) {
    $user_id = $_SESSION['user_id'];

    // Clear the latest verification session_token
    $stmt = $pdo->prepare("
        UPDATE verifications
        SET session_token = NULL
        WHERE verification_id = (
            SELECT v.verification_id 
            FROM verifications v
            WHERE v.user_id = ?
            ORDER BY v.verification_id DESC
            LIMIT 1
        )
    ");
    $stmt->execute([$user_id]);
}

// Destroy all session data
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Redirect to login page
header("Location: ../../frontend/pages/login.php");
exit;
