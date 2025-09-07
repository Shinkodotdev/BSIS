<?php
function redirectIfNotLoggedIn($loginPages = ['../login.php'], $pdo = null) {
    // ✅ Session check
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['session_token'])) {
        redirectToFirstPage($loginPages);
    }

    // ✅ Database check: session token must match the one in verifications table
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT session_token FROM verifications WHERE user_id = ? ORDER BY verification_id DESC LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || $row['session_token'] !== $_SESSION['session_token']) {
            redirectToFirstPage($loginPages);
        }
    }
}

// Helper redirect function
function redirectToFirstPage($loginPages) {
    foreach ($loginPages as $page) {
        if (file_exists($page) || filter_var($page, FILTER_VALIDATE_URL)) {
            header("Location: $page");
            exit;
        }
    }
    exit('No login page available.');
}
