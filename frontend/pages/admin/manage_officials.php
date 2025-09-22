<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";
require '../../../backend/config/db.php';
require_once "../../../backend/models/Repository.php";
$users = getManageUsers($pdo, 'Official', 100);
$pageTitle = "Admin | Manage Residents";
$pageDescription = "Manage Residents for Barangay Poblacion Sur System";
include 'admin-head.php';
?>
<body class="bg-gray-100">
    <?php include('../../components/DashNav.php'); ?>
    <main class="flex-1 p-3 sm:p-6 md:p-8 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto space-y-10">
            <section>
                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">👥 Manage Officials
                </h1>
                <?php include('../../components/manage/user.php');?>
            </section>
        </div>
    </main>
    <div id="modalContainer"></div>
    <?php include('../../assets/modals/user_view_modal.php');?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/Approval_Search.js"></script>
    <script src="../../assets/js/Open_profile_modal.js"></script>
    <script src="../../assets/js/User_Reminder.js"></script>
</body>
</html>