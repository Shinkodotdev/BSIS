<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
$user_id = $_SESSION['user_id'];
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/config/db.php";
require_once "../../../backend/models/Repository.php";
$data = getUserProfileById($pdo, $user_id);
include('../../assets/helpers/helpers.php');
include('./admin-head.php');
include('../../components/DashNav.php');
?>
<body class="bg-gradient-to-r from-indigo-50 via-white to-indigo-50 min-h-screen font-sans text-gray-800">
    <div class="max-w-5xl mx-auto px-6 py-10">
        <!-- ✅ Header -->
        <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-8">
            <!-- Personal Info -->
            <?php include('../profile_section/personal_info.php');?>
            <!-- Birth Info -->
            <?php include('../profile_section/birth_info.php');?>
            <!-- Residency -->
            <?php include('../profile_section/residency_info.php');?>
            <!-- Family Info -->
            <?php include('../profile_section/family_info.php');?>
            <!-- Health Info -->
            <?php include('../profile_section/health_info.php');?>
            <!-- Income Info -->
            <?php include('../profile_section/income_info.php');?>
            <!-- Identity Docs -->
            <?php include('../profile_section/identity_info.php');?>
            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="admin-profile.php"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">Save
                    Changes</button>
            </div>
        </form>
    </div>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        document.addEventListener("DOMContentLoaded", function() {
            const civilStatus = document.getElementById("civil_status");
            const spouseField = document.getElementById("spouse_field");

            function toggleSpouseField() {
                if (civilStatus.value === "Single") {
                    spouseField.classList.add("hidden");
                } else {
                    spouseField.classList.remove("hidden");
                }
            }

            // Run on page load
            toggleSpouseField();

            // Run on change
            civilStatus.addEventListener("change", toggleSpouseField);
        });
    </script>
</body>

</html>