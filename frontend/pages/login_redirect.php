<?php
session_start();
if (!isset($_SESSION['success']) || !isset($_SESSION['redirect_after_success'])) {
    header("Location: login.php");
    exit;
}

$successMessage = $_SESSION['success'];
$redirectUrl = $_SESSION['redirect_after_success'];

// Clear session messages so it doesn’t repeat
unset($_SESSION['success'], $_SESSION['redirect_after_success']);
?>

<?php include '../components/Head.php'; ?>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success 🎉',
        text: <?php echo json_encode($successMessage); ?>,
        confirmButtonColor: '#2563eb',
        allowOutsideClick: false
    }).then(() => {
        window.location.href = <?php echo json_encode($redirectUrl); ?>;
    });
</script>
</body>
</html>
