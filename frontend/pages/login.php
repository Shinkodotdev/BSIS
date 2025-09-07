<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<?php include '../components/Head.php'; ?>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-lg">
        <!-- Header -->
        <div class="text-center space-y-2 mb-6">
            <div class="flex justify-center">
                <img src="../assets/images/Logo.webp" alt="Barangay Logo" class="w-16 h-16 rounded-full">
            </div>
            <h2 class="text-2xl font-bold text-blue-500">Welcome Back</h2>
            <p class="text-gray-300">Sign in to access the Barangay Information System</p>
        </div>

        <!-- Form -->
        <form action="../../backend/actions/login_process.php" method="POST" class="space-y-4">
            <!-- Email -->
            <div class="space-y-1">
                <label for="email" class="text-sm font-medium text-gray-200">Email Address</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">📧</span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                        class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-100">
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <label for="password" class="text-sm font-medium text-gray-200">Password</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-100">
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        👁
                    </button>
                </div>
            </div>

            <!-- Forgot Password -->
            <div class="text-right">
                <a href="forgot_password.php" class="text-sm text-blue-400 hover:underline font-medium">Forgot password?</a>
            </div>

            <!-- Submit -->
            <div class="flex flex-col space-y-4">
                <button
                    type="submit"
                    class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 text-white font-medium hover:shadow-lg transition">
                    Sign In
                </button>

                <div class="text-center text-sm text-gray-300">
                    Don’t have an account?
                    <a href="signup.php" class="text-blue-400 hover:underline font-medium">Sign up here</a>
                </div>
            </div>
        </form>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePassword() {
            const pwd = document.getElementById("password");
            pwd.type = pwd.type === "password" ? "text" : "password";
        }

        window.onload = function() {
            <?php if(isset($_SESSION['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success 🎉',
                    text: '<?php echo $_SESSION['success']; ?>',
                    confirmButtonColor: '#2563eb'
                });
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error ❌',
                    text: '<?php echo $_SESSION['error']; ?>',
                    confirmButtonColor: '#dc2626'
                });
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        };
    </script>
</body>
</html>
