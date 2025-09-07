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
                Welcome, <?= htmlspecialchars($userName) ?> (<?= htmlspecialchars($userRole) ?>)
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
    <aside id="sidebar"
        class="w-64  bg-slate-900 text-white flex flex-col transform -translate-x-full md:translate-x-0
        transition-transform duration-300 fixed md:relative top-16 md:top-0 h-[calc(100%-2rem)] md:h-[calc(100%-2rem)] lg:h-[calc(100%-2rem)] z-40">

        <!-- Navigation -->
        <nav class="flex-2 p-4 space-y-2">
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> <em>
                            Welcome to the your dashboard 
                            <?= htmlspecialchars($userName) ?></em>
                        </p>
                    </div>
            <!-- FOR ADMIN   -->
            <?php if ($userRole === 'Admin' && $userStatus === 'Approved'): ?>
                <a href="dashboard.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="profile.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">👤</span> Profile
                </a>
                <a href="manage_announcements.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">📢</span> Announcements
                </a>
                <a href="manage_events.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">📅</span> Events
                </a>
                <a href="manage_officials.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">🏛</span> Officials
                </a>
                <a href="manage_resident.php"
                    class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <i class="fa-solid fa-users mr-3 text-lg"></i>
                    <span class="font-medium">Residents</span>
                </a>

                <a href="manage_approvals.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">✅</span> Approvals
                </a>
                <a href="manage_approvals.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2"></span> Health Reports
                </a>
                <!-- FOR OFFICIAL  -->
            <?php elseif ($userRole === 'Official' && $userStatus === 'Approved'): ?>
                <a href="dashboard.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="profile.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">👤</span> Profile
                </a>
                <a href="announcements.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">📢</span> Announcements
                </a>
                <a href="events.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">📅</span> Events
                </a>
                <a href="events.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                    <span class="mr-2">📅</span> Events
                </a>
                <!-- FOR RESIDENT  -->
            <?php elseif ($userRole === 'Resident' && $userStatus === 'Approved'): ?>
                
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> In this Dashboard 
                            <em>you can request and download the documents real-time.</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> You can visit our
                            <em>Announcements, Profile, Health Survey, Announcements, Request Documents
                                Such As Brgy. Indigency, Travel Permit, First Time Job Seeker, etc.
                            </em>
                        </p>
                    </div>
                    <a href="dashboard.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                        <span class="mr-2">🏠</span> Dashboard
                    </a>
                    <a href="profile.php" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                        <span class="mr-2">👤</span> Profile
                    </a>
                <div class="space-y-2">
                    <!-- Announcements -->
                    <a href="../landing-page/Announcements.php" target="_blank" class="flex items-center px-4 py-2 rounded hover:bg-slate-700 transition">
                        <span class="mr-2">📢</span> Announcements
                    </a>
                    <!-- Documents Section -->
                    <div>
                        <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                            <div class="flex items-center">
                                <span class="mr-2">📄</span> Documents
                            </div>
                    </div>
                    <!-- Health Stats Section -->
                    <div>
                        <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 transition">
                            <div class="flex items-center">
                                <span class="mr-2">📊</span> Health Stats
                            </div>
                        </button>
                    </div>
                </div>
            <?php elseif ($userRole === 'Resident' && $userStatus === 'Verified'): ?>

                <div class="mt-10 space-y-4">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> Please complete all fields
                            <em>to verify and setup role by the Admin.</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> All fields with *
                            <em>are Required please fill them up to avoid repetition</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> Barangay Clearance requires complete
                            <em>User Details, Birth Info, Residency, and Valid ID.</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> Certificate of Indigency (Medical, Scholarship)
                            requires <em>Residency Info, Family Info, Health Survey, and Proof of Income (if applicable).</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> Residency Certificate requires at least
                            <em>3 years of residency record in your profile.</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> First Time Job Seeker / Oath of Undertaking
                            requires <em>Birth Info, Residency, and Valid ID upload.</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> Guardianship, Living Together, Same Person,
                            and Endorsement Letters require <em>Family Information details completed.</em>
                        </p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <p class="text-blue-700 text-md">
                            <strong>Note:</strong> Travel Permit requires <em>Residency Info and Emergency Contact Person filled out.</em>
                        </p>
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
    <script>
        // Sidebar toggle on mobile
        const sidebar = document.getElementById("sidebar");
        const sidebarToggle = document.getElementById("sidebarToggle");

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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