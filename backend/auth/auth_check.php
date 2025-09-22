<?php
// ✅ Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'], $_SESSION['role'], $_SESSION['status'])) {
    header("Location: ../../pages/unauthorized_page.php");
    exit;
}

/**
 * Check role(s) allowed for this page
 */
if (isset($allowedRoles) && !in_array($_SESSION['role'], $allowedRoles)) {
    header("Location: ../../pages/unauthorized_page.php");
    exit;
}

// Optional: check for status
$allowedStatus = $allowedStatus ?? ['Approved', 'Verified'];
if (!in_array($_SESSION['status'], $allowedStatus)) {
    header("Location: ../../pages/unauthorized_page.php");
    exit;
}

// ✅ Define global variables for use in other includes
$userName = $_SESSION['name'] ?? 'User';
$userRole = $_SESSION['role'] ?? 'Resident';
$userStatus = $_SESSION['status'] ?? 'Verified';
$userId   = $_SESSION['user_id'] ?? null; // <-- Add this
// ✅ Include JS for real-time status check
echo '<script>
function checkUserStatus() {
    fetch("../../../frontend/pages/check_status.php")
        .then(res => res.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = "../../frontend/pages/unauthorized_page.php";
            }
        })
        .catch(err => console.error(err));
}
setInterval(checkUserStatus, 10000); // every 10 seconds
</script>';
