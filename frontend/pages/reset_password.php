<?php
session_start();
require_once "../../backend/controllers/AuthController.php";

$token = isset($_GET['token']) ? trim($_GET['token']) : null;
$tokenValid = false;

if ($token) {
    try {
        $auth = new AuthController();
        $reset = $auth->isTokenValid($token);

        if ($reset) {
            $tokenValid = true;
        } else {
            $_SESSION['error'] = "Invalid or expired reset token.";
            // Redirect to forgot password to avoid showing invalid form
            header("Location: ../../frontend/pages/forgot_password.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: ../../frontend/pages/forgot_password.php");
        exit;
    }
} else {
    $_SESSION['error'] = "No reset token provided.";
    header("Location: ../../frontend/pages/forgot_password.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../components/Head.php'; ?>

<body class="bg-gray-900 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-lg">
    <div class="text-center space-y-2 mb-6">
        <h2 class="text-2xl font-bold text-blue-500">Reset Password</h2>
        <p class="text-gray-300">Enter your new password below</p>
    </div>

    <form action="../../backend/actions/reset_password_process.php" method="POST" class="space-y-4">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

        <!-- New Password -->
        <div class="space-y-1">
            <label for="password" class="text-sm font-medium text-gray-200">New Password</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                <input type="password" id="password" name="password" placeholder="Enter new password" required
                    class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-100">
                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">👁</button>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <label for="confirm" class="text-sm font-medium text-gray-200">Confirm Password</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                <input type="password" id="confirm" name="confirm" placeholder="Confirm new password" required
                    class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-100">
                <button type="button" onclick="togglePassword('confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">👁</button>
            </div>
        </div>

        <button type="submit"
            class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 text-white font-medium hover:shadow-lg transition">
            Reset Password
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

window.onload = function() {
    <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo $_SESSION['success']; ?>',
            confirmButtonColor: '#2563eb'
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $_SESSION['error']; ?>',
            confirmButtonColor: '#dc2626'
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
}
</script>

</body>
</html>
