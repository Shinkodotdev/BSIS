<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/models/Repository.php";
require_once "../../../backend/controllers/DashboardController.php";
require_once "../../../backend/config/db.php";
// Instantiate controller
$dashboard = new DashboardController($pdo);
// Fetch all stats
$stats = $dashboard->getStats();
// Page metadata
$pageTitle = "Admin | Dashboard";
$pageDescription = "The Barangay Poblacion Sur Dashboard provides an overview of residents, officials, events, announcements, and community statistics for effective management.";
// Include head
include 'admin-head.php';
$requests = getDocumentByStatus($pdo, 'Pending', 50);
?>

<body class="bg-gray-100 min-h-screen">
    <?php include('../../components/DashNav.php'); ?>
    <main class="flex-1 p-4 sm:p-6 md:ml-10 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto space-y-8">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">📊 Dashboard Overview</h1>
            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                <?php
                $cards = [
                    ['title' => 'Residents', 'count' => $stats['residents'], 'desc' => 'Total registered residents', 'color' => 'blue', 'icon' => 'fa-users'],
                    ['title' => 'Officials', 'count' => $stats['officials'], 'desc' => 'Barangay elected officials', 'color' => 'green', 'icon' => 'fa-user-tie'],
                    ['title' => 'Events', 'count' => $stats['events'], 'desc' => 'Upcoming community events', 'color' => 'yellow', 'icon' => 'fa-calendar-days'],
                    ['title' => 'Announcements', 'count' => $stats['announcements'], 'desc' => 'Published barangay news', 'color' => 'purple', 'icon' => 'fa-bullhorn'],
                ];
                foreach ($cards as $card):
                ?>
                    <a href="./<?= $card['title'] ?>.php" target="_blank" rel="noopener noreferrer"
                        class="block bg-white shadow-md sm:shadow-lg rounded-xl p-4 sm:p-6 flex justify-between items-center border-l-4 border-<?= $card['color'] ?>-500 hover:shadow-xl transition flex-wrap">
                        <div class="w-full sm:w-auto">
                            <h2 class="text-xs sm:text-sm font-medium text-gray-500 uppercase">
                                <?= $card['title'] ?>
                            </h2>
                            <p class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-800 mt-1">
                                <?= $card['count'] ?>
                            </p>
                            <p class="text-[10px] sm:text-xs text-gray-400 mt-1">
                                <?= $card['desc'] ?>
                            </p>
                        </div>
                        <div class="bg-<?= $card['color'] ?>-100 p-2 sm:p-3 rounded-full text-<?= $card['color'] ?>-500 text-xl sm:text-2xl md:text-3xl mt-2 sm:mt-0">
                            <i class="fa-solid <?= $card['icon'] ?>"></i>
                        </div>
                    </a>


                <?php endforeach; ?>
            </div>
            <!-- Population Statistics -->
            <div class="bg-white shadow-md sm:shadow-lg rounded-xl p-4 sm:p-6 mt-6">
                <h2 class="text-base sm:text-lg font-bold text-gray-800 mb-4">📈 Population Statistics</h2>

                <form id="filterForm" class="flex flex-wrap gap-2 sm:gap-3 mb-4 text-xs sm:text-sm">
                    <select name="filter_type" id="filter_type" class="border rounded px-2 py-1 w-full sm:w-auto">
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                        <option value="day">Day</option>
                        <option value="range">Date Range</option>
                    </select>

                    <input type="month" id="monthFilter" class="border rounded px-2 py-1 hidden w-full sm:w-auto">
                    <input type="number" id="yearFilter" class="border rounded px-2 py-1 hidden w-full sm:w-auto"
                        min="1800" max="<?= date('Y') ?>" placeholder="Year">
                    <input type="date" id="dayFilter" class="border rounded px-2 py-1 hidden w-full sm:w-auto">

                    <div id="rangeFilter"
                        class="hidden flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                        <input type="date" id="startDate" class="border rounded px-2 py-1 w-full sm:w-auto">
                        <span class="hidden sm:inline">to</span>
                        <input type="date" id="endDate" class="border rounded px-2 py-1 w-full sm:w-auto">
                    </div>

                    <button type="button" id="applyFilter"
                        class="bg-blue-500 text-white px-3 py-1 rounded w-full sm:w-auto">Apply</button>
                </form>

                <div class="h-64 sm:h-96">
                    <canvas id="populationChart"></canvas>
                </div>
            </div>
            <!-- Recent Document Requests -->
            <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6 mt-6">
                <h2 class="text-base sm:text-lg font-bold text-gray-800 mb-4">📑 Recent Document Requests</h2>
                <!-- Desktop Table (hidden on small screens) -->
                <div class="overflow-x-auto hidden sm:block">
                    <table
                        class="min-w-full border border-gray-200 rounded-lg divide-y divide-gray-200 text-xs sm:text-sm md:text-base">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left">Name</th>
                                <th class="px-2 sm:px-4 py-2 text-left">Document</th>
                                <th class="px-2 sm:px-4 py-2 text-left hidden sm:table-cell">Purpose</th>
                                <th class="px-2 sm:px-4 py-2 text-left">Status</th>
                                <th class="px-2 sm:px-4 py-2 text-left hidden md:table-cell">Requested</th>
                                <th class="px-2 sm:px-4 py-2 text-left hidden lg:table-cell">Remarks</th>
                                <th class="px-2 sm:px-4 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($requests as $row): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-2 sm:px-4 py-2 whitespace-nowrap">
                                        <?= htmlspecialchars($row['user_name']) ?>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 whitespace-nowrap">
                                        <?= htmlspecialchars($row['document_name']) ?>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 hidden sm:table-cell">
                                        <?= htmlspecialchars($row['purpose']) ?>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2">
                                        <span
                                            class="px-2 py-1 text-[10px] sm:text-xs rounded-lg 
                            <?= $row['status'] === 'Approved' ? 'bg-green-100 text-green-700' : ($row['status'] === 'Denied' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 whitespace-nowrap hidden md:table-cell">
                                        <?= htmlspecialchars(date("F j, Y", strtotime($row['requested_at']))) ?>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 hidden lg:table-cell">
                                        <?= htmlspecialchars($row['remarks'] ?? 'N/A') ?>
                                    </td>
                                    <td class="flex justify-end gap-2 mt-2 p-2">

                                        <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Approved')"
                                            class="bg-green-500 hover:bg-green-600 text-white px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs md:text-sm">Approve</button>
                                        <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Denied')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs md:text-sm">Deny</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Stack (shown on small screens) -->
                <div class="grid gap-3 sm:hidden">
                    <?php foreach ($requests as $row): ?>
                        <div class="bg-gray-50 border rounded-lg p-3 shadow-sm">
                            <p><span class="font-semibold">Name:</span> <?= htmlspecialchars($row['user_name']) ?></p>
                            <p><span class="font-semibold">Document:</span> <?= htmlspecialchars($row['document_name']) ?>
                            </p>
                            <p><span class="font-semibold">Purpose:</span> <?= htmlspecialchars($row['purpose']) ?></p>
                            <p><span class="font-semibold">Status:</span>
                                <span
                                    class="px-2 py-1 rounded-lg text-[10px] 
                    <?= $row['status'] === 'Approved' ? 'bg-green-100 text-green-700' : ($row['status'] === 'Denied' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </p>
                            <p><span class="font-semibold">Requested:</span>
                                <?= htmlspecialchars(date("F j, Y", strtotime($row['requested_at']))) ?></p>
                            <p><span class="font-semibold">Remarks:</span> <?= htmlspecialchars($row['remarks'] ?? 'N/A') ?>
                            </p>
                            <div class="flex justify-end gap-2 mt-2">
                                <button onclick="viewUser(<?= $row['request_id'] ?>)"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                    View
                                </button>
                                <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Approved')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</button>
                                <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Denied')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Deny</button>

                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($requests)): ?>
                        <p class="text-center text-gray-500 py-4">No document requests</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php include('../../assets/modals/user_view_modal.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/Chart.js"></script>
    <script src="../../assets/js/View_User.js"></script>
    <script>
        function updateRequest(requestId, action) {
            Swal.fire({
                title: `Are you sure you want to ${action.toLowerCase()} this request?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: action === 'Approved' ? '#22c55e' : '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Yes, ${action}!`
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`../../../backend/actions/update_request.php`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                request_id: requestId,
                                action: action
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Success!',
                                    `Request has been ${action.toLowerCase()}.`,
                                    'success'
                                ).then(() => location.reload());
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message || 'Something went wrong.',
                                    'error'
                                );
                            }
                        })
                        .catch(err => Swal.fire('Error!', err.message, 'error'));
                }
            });
        }
    </script>
</body>

</html>