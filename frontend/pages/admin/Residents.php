<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/models/Repository.php";
require '../../../backend/config/db.php';

$pageTitle = "Admin | All Residents List";
$pageDescription = "Manage Residents for Barangay Poblacion Sur System";
include './admin-head.php';
$users = getAllUsers($pdo, 'Resident',50);
?>

<body class="bg-gray-100">
    <?php include('../../components/DashNav.php'); ?>
    <main class="flex-1 p-3 sm:p-6 md:p-8 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto space-y-10">

            <section>
                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">👥 All Residents
                </h1>
                <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6">

                    <!-- Search -->
                    <?php include('../../components/document_search.php'); ?>

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto max-h-96 overflow-y-auto">
                        <table id="docTable" class="min-w-full text-sm border divide-y divide-gray-200">
                            <?php include('../../components/resident_table.php'); ?>
                            <tbody>
                                <?php foreach ($users as $row): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-3 py-2 whitespace-nowrap sticky left-0 bg-white z-10">
                                            <?= htmlspecialchars($row['full_name']) ?>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap"><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($row['role']) ?></td>
                                        <td class="px-3 py-2 text-yellow-600"><?= htmlspecialchars($row['status']) ?></td>
                                        <td class="px-3 py-2">
                                            <?= htmlspecialchars(date("F j, Y", strtotime($row['created_at']))) ?></td>
                                        <td class="px-3 py-2 text-center space-x-2">
                                            <button onclick="viewUser(<?= $row['user_id'] ?>)"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="grid gap-3 md:hidden max-h-96 overflow-y-auto">
                        <?php foreach ($users as $row): ?>
                            <div class="border rounded-lg p-2 shadow-sm bg-gray-50">
                                <p><span class="font-semibold">Name:</span> <?= htmlspecialchars($row['full_name']) ?></p>
                                <p><span class="font-semibold">Document:</span>
                                    <?= htmlspecialchars($row['email']) ?></p>
                                <p><span class="font-semibold">Purpose:</span> <?= htmlspecialchars($row['role']) ?></p>
                                <p><span class="font-semibold">Status:</span> <span
                                        class="text-yellow-600"><?= htmlspecialchars($row['status']) ?></span></p>
                                <p><span class="font-semibold">Requested:</span>
                                    <?= htmlspecialchars(date("F j, Y", strtotime($row['created_at']))) ?></p>
                                <div class="flex justify-end gap-2 mt-2">
                                    <button id="openModalBtn" onclick="viewUser(<?= $row['user_id'] ?>, 'View')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        View
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($users)): ?>
                            <p class="text-center text-gray-500 py-4">No Residents</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
        </div>


    </main>
<div id="modalContainer"></div>

    <script src="../../assets/js/Approval_Search.js"></script>
    <script>
const modal = document.getElementById('profileModal');
const openBtn = document.getElementById('openModalBtn'); // Optional if trigger exists
const closeBtn = document.getElementById('closeModalBtn');

const modalBody = document.getElementById('modalBody');

function viewUser(userId) {
    fetch(`../../assets/modals/user_view_modal.php?user_id=${userId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('modalContainer').innerHTML = html;

            const modal = document.getElementById('profileModal');
            const closeBtn = document.getElementById('closeModalBtn');

            // Show the modal
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');

            // Close button functionality
            closeBtn.addEventListener('click', () => {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100');
            });

            // Close when clicking outside the modal content
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    modal.classList.remove('opacity-100');
                }
            });
        });
}

</script>

</body>

</html>