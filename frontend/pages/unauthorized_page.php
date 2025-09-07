<?php
// unauthorized.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #1f2937; /* dark gray */
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1rem;
            color: #9ca3af;
        }
    </style>
</head>
<body>

<script>
    // Show SweetAlert warning
    Swal.fire({
        icon: 'error',
        title: 'Unauthorized ❌',
        text: 'You are not allowed to access this page.',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
        didClose: () => {
            // Redirect after 3 seconds
            window.location.href = 'login.php';
        }
    });
</script>
</body>
</html>
