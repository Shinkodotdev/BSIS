<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/config/db.php";
require_once "../../../backend/models/Repository.php";
$pageTitle = "Admin | Dashboard";
$pageDescription = "The Barangay Poblacion Sur Dashboard provides an overview of residents, officials, events, announcements, and community statistics for effective management.";
// -------------------------
// 📑 Pending Document Requests
// -------------------------
$pendingRequests = getDocumentByStatus($pdo, 'Pending', 50);
$approvedRequests = getDocumentByStatus($pdo, 'Approved', 20);
$rejectedRequests = getDocumentByStatus($pdo, 'Rejected', 20);
// -------------------------
// 👥 User Approvals
// -------------------------
$pendingUsers = getUsersByStatus($pdo, 'Pending', 50);
$approvedUsers = getUsersByStatus($pdo, 'Approved', 50);
$verifiedUsers = getUsersByStatus($pdo, 'Verified', 50);
$rejectedUsers = getUsersByStatus($pdo, 'Rejected', 50);
include 'admin-head.php';
?>
<body class="bg-gray-100 min-h-screen">
    <?php include('../../components/DashNav.php'); ?>
    <main class="flex-1 p-3 sm:p-6 md:p-8 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto space-y-10">
            <div class="bg-white shadow-md rounded-xl p-4 mb-6">
                <input id="universalSearch" type="text" placeholder="🔎 Search everything..."
                    class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <!-- 📑PENDING  Document Requests -->
            <?php include('../../components/approvals/pending_document_request.php'); ?>
            <!-- 👥 PENDING User Approvals -->
            <?php include('../../components/approvals/pending_user_approvals.php'); ?>
            <!--  📑Approved  Document Requests -->
            <?php include('../../components/approvals/approved_document_requests.php'); ?>
            <!-- 👥 Approved User Approvals -->
            <?php include('../../components/approvals/approved_user_approvals.php'); ?>
            <!-- 👥 Verified User Approvals -->
            <?php include('../../components/approvals/verified_user_approvals.php'); ?>
            <!-- Rejected Users  -->
            <?php include('../../components/approvals/rejected_user_approvals.php'); ?>
            <!-- Rejected Document Requests -->
            <?php include('../../components/approvals/rejected_document_requests.php'); ?>
        </div>
    </main>
    <!-- User Details Modal -->
    <?php include('../../assets/modals/user_view_modal.php'); ?>
    <!-- SCRIPT   -->
    <script src="../../assets/js/Approval_Search.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/Update_Request.js"></script>
    <script src="../../assets/js/Update_User_Approval.js"></script>
    <script src="../../assets/js/User_Reminder.js"></script>
    <script src="../../assets/js/View_User.js"></script>
</body>

</html>