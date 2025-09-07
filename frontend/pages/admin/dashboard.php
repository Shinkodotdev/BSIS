<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/controllers/DashboardController.php";

// Instantiate controller
$dashboard = new DashboardController($pdo);

// Fetch all stats
$stats = $dashboard->getStats();

// Page metadata
$pageTitle = "Admin | Dashboard";
$pageDescription = "The Barangay Poblacion Sur Dashboard provides an overview of residents, officials, events, announcements, and community statistics for effective management.";

// Include head
include 'admin-head.php';
?>


<body class="bg-gray-100">
    <!-- HEADER & NAVBAR -->
    <?php include('../../components/DashNav.php'); ?>

    <main class="flex-1 p-4 sm:p-6 md:ml-10 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6">📊 Dashboard Overview</h1>

            <!-- Responsive Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php
                // Cards Config
                $cards = [
                    ['title' => 'Residents', 'count' => $stats['residents'], 'desc' => 'Total registered residents', 'color' => 'blue', 'icon' => 'fa-users'],
                    ['title' => 'Officials', 'count' => $stats['officials'], 'desc' => 'Barangay elected officials', 'color' => 'green', 'icon' => 'fa-user-tie'],
                    ['title' => 'Events', 'count' => $stats['events'], 'desc' => 'Upcoming community events', 'color' => 'yellow', 'icon' => 'fa-calendar-days'],
                    ['title' => 'Announcements', 'count' => $stats['announcements'], 'desc' => 'Published barangay news', 'color' => 'purple', 'icon' => 'fa-bullhorn'],
                ];

                foreach ($cards as $card): ?>
                    <div class="bg-white shadow-lg rounded-xl p-5 sm:p-6 flex items-center justify-between 
                                border-l-4 border-<?= $card['color'] ?>-500 hover:shadow-xl transition">
                        <div>
                            <h2 class="text-xs sm:text-sm font-medium text-gray-500 uppercase"><?= $card['title'] ?></h2>
                            <p class="text-2xl sm:text-3xl font-extrabold text-gray-800 mt-2"><?= $card['count'] ?></p>
                            <p class="text-[11px] sm:text-xs text-gray-400 mt-1"><?= $card['desc'] ?></p>
                        </div>
                        <div class="bg-<?= $card['color'] ?>-100 p-3 rounded-full text-<?= $card['color'] ?>-500 text-2xl sm:text-3xl">
                            <i class="fa-solid <?= $card['icon'] ?>"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <!-- Population Statistics with Filters -->
<div class="bg-white shadow-lg rounded-xl p-6 mt-8">
    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center justify-between">
        📈 Population Statistics (Born & Deceased)

        <!-- Filters -->
        <form id="filterForm" class="flex flex-wrap items-center gap-3 text-sm">
            <select name="filter_type" id="filter_type" class="border rounded px-2 py-1">
                <option value="month">Month</option>
                <option value="year">Year</option>
                <option value="day">Day</option>
                <option value="range">Date Range</option>
            </select>

            <input type="month" id="monthFilter" class="border rounded px-2 py-1 hidden">
            <input type="number" id="yearFilter" class="border rounded px-2 py-1 hidden" min="2000" max="<?= date('Y') ?>" placeholder="Year">
            <input type="date" id="dayFilter" class="border rounded px-2 py-1 hidden">

            <!-- Date Range -->
            <div id="rangeFilter" class="hidden flex items-center gap-2">
                <input type="date" id="startDate" class="border rounded px-2 py-1">
                <span>to</span>
                <input type="date" id="endDate" class="border rounded px-2 py-1">
            </div>

            <button type="button" id="applyFilter" class="bg-blue-500 text-white px-3 py-1 rounded">Apply</button>
        </form>
    </h2>

    <!-- Chart -->
    <div class="h-96">
        <canvas id="populationChart"></canvas>
    </div>
</div>
        </div>
    </main>
<script src="../../assets/js/Chart.js"></script>
<script>
function checkUserStatus() {
    fetch('../../pages/check_status.php')
        .then(res => res.json())
        .then(data => {
            if (data.redirect) {
                // Redirect to unauthorized page
                window.location.href = '../../pages/unauthorized_page.php';
            }
        })
        .catch(err => console.error(err));
}

// Poll every 10 seconds (adjust interval if needed)
setInterval(checkUserStatus, 10000);
</script>

</body>

</html>