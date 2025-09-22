<?php
// Dynamically find the auth_check.php file
$authPath = __DIR__ . '/../../backend/auth/auth_check.php'; // adjust as needed
if (!file_exists($authPath)) {
    $authPath = __DIR__ . '/../../../backend/auth/auth_check.php'; // fallback for deeper pages
}

require_once $authPath;
?>
<!-- HEADER -->
<header class="fixed top-0 left-0 w-full z-50 shadow-md bg-slate-900 text-white">
    <div class="flex items-center justify-between px-6 py-4 lg:px-10">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="../../assets/images/Logo.webp" alt="Barangay Logo" class="h-10 w-10 rounded-full">
            <span class="font-bold text-lg">Barangay Information System</span>
        </div>

        <!-- Desktop Menu -->
        <ul class="hidden md:flex items-center gap-6 font-medium text-sm">
            <li class="font-semibold">
                WELCOME, <?= htmlspecialchars($userName) ?>
            </li>
        </ul>

        <!-- Mobile menu button -->
        <button id="sidebarToggle" class="md:hidden text-white text-2xl focus:outline-none">
            <i class="fa fa-bars"></i>
        </button>
    </div>
</header>

<div class="flex min-h-screen pt-16"> <!-- pt-16 = navbar height -->
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-slate-900 text-white flex flex-col transform -translate-x-full md:translate-x-0
    transition-transform duration-300 fixed md:relative top-16 md:top-0 h-[calc(100%-4rem)] md:h-auto z-40">
        <!-- Navigation -->
        <nav class="flex-2 p-4 space-y-2 overflow-y-auto">
            <div class="mt-6">
                <div class=" backdrop-blur-md text-white rounded-xl p-4 shadow-lg border border-white/20">
                    <p class="text-sm font-semibold tracking-wide">
                        HI <strong class="uppercase"><?= htmlspecialchars($userRole) ?></strong>
                    </p>
                    <p class="text-xs mt-1 opacity-90">
                        WELCOME TO YOUR <strong class="uppercase"><?= htmlspecialchars($userStatus) ?></strong>
                        DASHBOARD
                    </p>
                </div>
            </div>


            <!-- FOR ADMIN   -->
            <?php if ($userRole === 'Admin' && $userStatus === 'Approved'): ?>
                <a href="dashboard.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="admin-profile.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">👤</span> Profile
                </a>
                <div x-data="{ openAnnouncements: false }">
                    <button @click="openAnnouncements = !openAnnouncements"
                        class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                        <div class="flex items-center">
                            <span class="mr-2">📢📅</span> Announcements & Events
                        </div>
                        <span x-show="!openAnnouncements">➕</span>
                        <span x-show="openAnnouncements">➖</span>
                    </button>
                    <div x-show="openAnnouncements" class="ml-6 mt-1 space-y-1">
                        <a href="manage_announcements.php" class="block px-2 py-1 text-sm hover:text-blue-400">
                            📢 📅 Announcements & Events
                        </a>
                        <a href="Announcements.php" class="block px-2 py-1 text-sm hover:text-blue-400">
                            📢 Manage Announcements
                        </a>
                        <a href="Events.php" class="block px-2 py-1 text-sm hover:text-blue-400">
                            📅 Manage Events
                        </a>
                    </div>
                </div>

                <div x-data="{ openOfficials: false }">
                    <button @click="openOfficials = !openOfficials"
                        class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                        <div class="flex items-center">
                            <span class="mr-2">🏛</span> Officials
                        </div>
                        <span x-show="!openOfficials">➕</span>
                        <span x-show="openOfficials">➖</span>
                    </button>

                    <div x-show="openOfficials" x-transition class="ml-6 mt-1 space-y-1">
                        <a href="set_officials.php" class="block px-2 py-1 text-sm hover:text-blue-400">Set Officials</a>
                        <a href="manage_officials.php" class="block px-2 py-1 text-sm hover:text-blue-400">Manage Officials</a>
                        <a href="Officials.php" class="block px-2 py-1 text-sm hover:text-blue-400">Officials List</a>
                    </div>
                </div>
                <div x-data="{ openResidents: false }">
                    <button @click="openResidents = !openResidents"
                        class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-users mr-3 text-lg"></i>
                            <span class="font-medium">Residents</span>
                        </div>
                        <span x-show="!openResidents">➕</span>
                        <span x-show="openResidents">➖</span>
                    </button>

                    <div x-show="openResidents" x-transition class="ml-6 mt-1 space-y-1">
                        <a href="manage_resident.php" class="block px-2 py-1 text-sm hover:text-blue-400">Manage Residents</a>
                        <a href="Residents.php" class="block px-2 py-1 text-sm hover:text-blue-400">Resident List</a>
                    </div>
                </div>
                <a href="manage_approval.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">✅</span> Approvals
                </a>
                <a href="health_reports.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">📊</span> Health Reports
                </a>

                <!-- FOR APPROVED OFFICIAL  -->
            <?php elseif ($userRole === 'Official' && $userStatus === 'Approved'): ?>
                <div class="space-y-4 mt-4">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-sm">
                            <strong>Note:</strong> As an <strong>Approved Official</strong>, you now have full access to:
                        <ul class="list-disc ml-6 mt-2 text-xs text-blue-600">
                            <li>Managing Announements and Events</li>
                            <li>Request and download official documents (e.g., Barangay Indigency, Travel Permit, First Time
                                Job Seeker, etc.)</li>
                            <li>Check your health survey reports</li>
                            <li>Answer or update your health survey again anytime</li>
                        </ul>
                        </p>
                    </div>
                </div>
                <a href="dashboard.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="official_profile.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">👤</span> Profile
                </a>
                <a href="official_announcement_events.php"
                    class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">📢📅</span> Announcements & Events
                </a>

                <div x-data="{ openDocs: false }">
                    <button @click="openDocs = !openDocs"
                        class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                        <div class="flex items-center">
                            <span class="mr-2">📄</span> Documents
                        </div>
                        <span x-show="!openDocs">➕</span>
                        <span x-show="openDocs">➖</span>
                    </button>
                    <div x-show="openDocs" class="ml-6 mt-1 space-y-1">
                        <a href="official_request.php" class="block px-2 py-1 text-sm hover:text-blue-400">Request
                            Documents</a>
                        <a href="my_official_requests.php" class="block px-2 py-1 text-sm hover:text-blue-400">My
                            Requests</a>
                    </div>
                </div>
                <div x-data="{ openHealth: false }">
                    <button @click="openHealth = !openHealth"
                        class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                        <div class="flex items-center">
                            <span class="mr-2">📊</span> Health Survey
                        </div>
                        <span x-show="!openHealth">➕</span>
                        <span x-show="openHealth">➖</span>
                    </button>
                    <div x-show="openHealth" class="ml-6 mt-1 space-y-1">
                        <a href="official_health_reports.php" class="block px-2 py-1 text-sm hover:text-blue-400">View
                            Reports</a>
                    </div>
                </div>

                <!-- FOR APPROVED RESIDENT -->
            <?php elseif ($userRole === 'Resident' && $userStatus === 'Approved'): ?>
                <div class="space-y-4 mt-4">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-sm">
                            <strong>Note:</strong> As an <strong>Approved Resident</strong>, you now have full access to:
                        <ul class="list-disc ml-6 mt-2 text-xs text-blue-600">
                            <li>Request and download official documents (e.g., Barangay Indigency, Travel Permit, First Time
                                Job Seeker, etc.)</li>
                            <li>View announcements and upcoming events</li>
                            <li>Check your health survey reports</li>
                            <li>Answer or update your health survey again anytime</li>
                        </ul>
                        </p>
                    </div>
                </div>
                <a href="dashboard.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="profile.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">👤</span> Profile
                </a>
                <div class="space-y-2">
                    <a href="announcement_events.php"
                        class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                        <span class="mr-2">📢</span> Announcements & Events
                    </a>
                    <div x-data="{ openDocs: false }">
                        <button @click="openDocs = !openDocs"
                            class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                            <div class="flex items-center">
                                <span class="mr-2">📄</span> Documents
                            </div>
                            <span x-show="!openDocs">➕</span>
                            <span x-show="openDocs">➖</span>
                        </button>
                        <div x-show="openDocs" class="ml-6 mt-1 space-y-1">
                            <a href="request.php" class="block px-2 py-1 text-sm hover:text-blue-400">Request Documents</a>
                            <a href="my_requests.php" class="block px-2 py-1 text-sm hover:text-blue-400">My Requests</a>
                        </div>
                    </div>
                    <div x-data="{ openHealth: false }">
                        <button @click="openHealth = !openHealth"
                            class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                            <div class="flex items-center">
                                <span class="mr-2">📊</span> Health Survey
                            </div>
                            <span x-show="!openHealth">➕</span>
                            <span x-show="openHealth">➖</span>
                        </button>
                        <div x-show="openHealth" class="ml-6 mt-1 space-y-1">
                            <a href="health_reports.php" class="block px-2 py-1 text-sm hover:text-blue-400">View
                                Reports</a>
                        </div>
                    </div>
                </div>
                <!-- Verified User  -->
            <?php elseif ($userRole === 'Resident' && $userStatus === 'Verified'): ?>
                <div class="mt-10">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-md">
                        <p class="text-blue-700 text-sm mb-3">
                            <strong>📌 Important Note:</strong> To request certificates and clearances, please ensure your
                            profile is <em>complete and updated</em>.
                            Requirements vary depending on the document:
                        </p>
                        <ul class="list-disc list-inside text-blue-700 space-y-1 text-xs">
                            <li><strong>Barangay Clearance</strong> </li>
                            <li><strong>Certificate of Indigency</strong></li>
                            <li><strong>Residency Certificate</strong> </li>
                            <li><strong>First Time Job Seeker</strong> </li>
                            <li><strong>Guardianship</strong> </li>
                            <li><strong>Travel Permit</strong></li>
                            <li><strong>Endorsement Letters</strong></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Logout -->
            <div class="p-4 border-t border-slate-700">
                <button onclick="confirmLogout()"
                    class="w-full bg-red-600 py-1 rounded text-white hover:bg-red-500 transition">
                    Logout
                </button>
            </div>
        </nav>
    </aside>
    <!-- SCRIPT -->
    <script>
        const sidebar = document.getElementById("sidebar");
        const sidebarToggle = document.getElementById("sidebarToggle");
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });
        // Close sidebar when clicking outside (mobile only)
        document.addEventListener("click", (e) => {
            if (window.innerWidth < 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.add("-translate-x-full");
                }
            }
        });
        //LOGOUT
        function confirmLogout() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../logout.php';
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>