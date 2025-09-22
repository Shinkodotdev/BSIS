<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/models/Repository.php";
require '../../../backend/config/db.php';

$pageTitle = "Admin | All Announcements List";
$pageDescription = "Manage Announcements for Barangay Poblacion Sur System";
include './admin-head.php';
$Announcements = getAllAnnouncements($pdo, null, 50);
?>
<body class="bg-gray-100">
    <?php include('../../components/DashNav.php'); ?>
    <main class="flex-1 p-3 sm:p-6 md:p-8 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto space-y-10">
            <section>
                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">👥 All Announcements</h1>
                <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-6">
                    <!-- Search -->
                    <?php include('../../components/document_search.php'); ?>
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto max-h-[30rem] overflow-y-auto rounded-lg">
                        <table id="docTable" class="min-w-full text-sm border divide-y divide-gray-200">
                            <thead class="bg-gray-100 text-gray-700 sticky top-0 z-20">
                                <tr>
                                    <th class="px-3 py-2 text-left">Title</th>
                                    <th class="px-3 py-2 text-left">Category</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                    <th class="px-3 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Announcements as $row): ?>
                                    <?php
                                    $status = $row['status'];
                                    $statusClasses = [
                                        'Published' => 'bg-green-100 text-green-700',
                                        'Archived' => 'bg-gray-200 text-gray-700',
                                        'Draft' => 'bg-yellow-100 text-yellow-700'
                                    ];
                                    ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <?= htmlspecialchars($row['announcement_title']) ?>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <?= htmlspecialchars($row['announcement_category']) ?>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 rounded text-xs font-medium <?= $statusClasses[$status] ?? 'bg-gray-100 text-gray-600' ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-center flex gap-2 justify-center">
                                            <button
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs view-btn"
                                                data-announcement='<?= json_encode($row) ?>'>
                                                View
                                            </button>
                                            <button
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs edit-btn"
                                                data-announcement='<?= json_encode($row) ?>'>
                                                Edit
                                            </button>
                                            <?php if ($status !== 'Archived'): ?>
                                                <button
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs delete-btn"
                                                    data-id="<?= $row['announcement_id'] ?>">
                                                    Delete
                                                </button>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Mobile Card View -->
                    <div class="grid gap-3 md:hidden max-h-[30rem] overflow-y-auto">
                        <?php foreach ($Announcements as $row): ?>
                            <?php
                            $status = $row['status'];
                            $statusClasses = [
                                'Published' => 'bg-green-100 text-green-700',
                                'Archived' => 'bg-gray-200 text-gray-700',
                                'Draft' => 'bg-yellow-100 text-yellow-700'
                            ];
                            ?>
                            <div class="border rounded-lg p-4 shadow-sm bg-gray-50 space-y-1">
                                <p><span class="font-semibold">📌 Title:</span>
                                    <?= htmlspecialchars($row['announcement_title']) ?></p>
                                <p><span class="font-semibold">📂 Category:</span>
                                    <?= htmlspecialchars($row['announcement_category']) ?></p>
                                <p><span class="font-semibold">📢 Status:</span>
                                    <span
                                        class="px-2 py-1 rounded text-xs font-medium <?= $statusClasses[$status] ?? 'bg-gray-100 text-gray-600' ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </p>
                                <!-- Actions -->
                                <div class="flex gap-2 pt-2">
                                    <button
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs view-btn"
                                        data-announcement='<?= json_encode($row) ?>'>
                                        View
                                    </button>
                                    <button
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs edit-btn"
                                        data-announcement='<?= htmlspecialchars(json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                        Edit
                                    </button>

                                    <?php if ($status !== 'Archived'): ?>
                                        <button
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs delete-btn"
                                            data-id="<?= $row['announcement_id'] ?>">
                                            Delete
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($Announcements)): ?>
                            <p class="text-center text-gray-500 py-4">No Announcements</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <?php include('../../assets/modals/All_Announcement_modal.php'); ?>
    <script src="../../assets/js/Approval_Search.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>