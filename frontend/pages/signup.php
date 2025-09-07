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
            <h2 class="text-2xl font-bold text-blue-500">Create Account</h2>
            <p class="text-gray-300 text-sm">
                Register for Barangay Poblacion Sur Talavera Information System
            </p>
        </div>

        <!-- Form -->
        <form action="../../backend/actions/signup_process.php" method="POST" class="space-y-3">
            <!-- Name Section -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-gray-200">First Name *</label>
                    <input type="text" name="firstName" placeholder="First name" required
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100 uppercase">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-200">Last Name *</label>
                    <input type="text" name="lastName" placeholder="Last name" required
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100 uppercase">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-gray-200">Middle Name</label>
                    <input type="text" name="middleName" placeholder="Middle name"
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100 uppercase">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-200">Suffix</label>
                    <select name="extensionName" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100">
                        <option value="">None</option>
                        <option value="Jr">Jr.</option>
                        <option value="Sr">Sr.</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-gray-200">Email Address *</label>
                    <input type="email" name="email" placeholder="Enter your email" required
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-200">Phone Number *</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 bg-gray-200 text-gray-700 text-sm">
                            +63
                        </span>
                        <input type="tel" name="phone" placeholder="9123456789" pattern="[0-9]{10}" maxlength="10" required
                            class="w-full border rounded-r-lg px-3 py-2 text-sm bg-gray-100" inputmode="numeric">
                    </div>
                    <small class="text-xs text-gray-400">Enter 10 digits (e.g., 9123456789)</small>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="text-sm font-medium text-gray-200">Address *</label>
                <div class="grid grid-cols-2 gap-3 mt-1">
                    <input type="text" name="house_no" placeholder="House/Blg No." required
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100">

                    <select name="purok" required class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100">
                        <option value="">Select Street/Purok</option>
                        <option value="purok1">Purok 1</option>
                        <option value="purok2">Purok 2</option>
                        <option value="purok3">Purok 3</option>
                        <option value="purok4">Purok 4</option>
                        <option value="purok5">Purok 5</option>
                        <option value="purok6">Purok 6</option>
                        <option value="purok7a">Purok 7A</option>
                        <option value="purok7b">Purok 7B</option>
                    </select>
                </div>
            </div>


            <!-- Password -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-gray-200">Password *</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Create a password" required
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100"
                            oninput="validatePassword()" />
                        <button type="button" onclick="togglePassword('password')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            👁
                        </button>
                    </div>

                    <!-- Password Checklist -->
                    <ul id="passwordChecklist" class="mt-2 text-xs text-gray-300 space-y-1">
                        <li id="length">❌ At least 8 characters</li>
                        <li id="uppercase">❌ At least 1 uppercase letter</li>
                        <li id="number">❌ At least 1 number</li>
                        <li id="special">❌ At least 1 special character (!@#$%)</li>
                    </ul>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-200">Confirm Password *</label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100">
                        <button type="button" onclick="togglePassword('confirmPassword')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            👁
                        </button>
                    </div>
                </div>
            </div>

            <!-- Error -->
            <?php if (isset($_GET['error'])): ?>
                <p class="text-red-500 text-sm"><?= htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>

            <!-- Submit -->
            <button type="submit"
                class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 text-white font-medium hover:shadow-lg transition">
                Create Account
            </button>

            <!-- Footer -->
            <p class="text-center text-sm text-gray-300">
                Already have an account?
                <a href="login.php" class="text-blue-400 hover:underline font-medium">Sign in here</a>
            </p>
        </form>
    </div>
    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
            <h2 class="text-xl font-bold text-green-600 mb-4">Success 🎉</h2>
            <p id="successMessage" class="text-gray-700"></p>
            <button onclick="closeModal('successModal')"
                class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                OK
            </button>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
            <h2 class="text-xl font-bold text-red-600 mb-4">Error ❌</h2>
            <p id="errorMessage" class="text-gray-700"></p>
            <button onclick="closeModal('errorModal')"
                class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Try Again
            </button>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }

        function validatePassword() {
            const password = document.getElementById("password").value;

            const rules = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
            };

            for (const [id, valid] of Object.entries(rules)) {
                const item = document.getElementById(id);
                item.textContent = (valid ? "✅ " : "❌ ") + item.textContent.replace(/^✅ |^❌ /, "");
                item.classList.toggle("text-green-400", valid);
                item.classList.toggle("text-red-400", !valid);
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has("success")) {
                document.getElementById("successMessage").textContent = urlParams.get("success");
                document.getElementById("successModal").classList.remove("hidden");
            }

            if (urlParams.has("error")) {
                document.getElementById("errorMessage").textContent = urlParams.get("error");
                document.getElementById("errorModal").classList.remove("hidden");
            }
        };
    </script>

</body>

</html>