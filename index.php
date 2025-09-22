<?php
$navbarLinks = [
    ['path' => 'index.php', 'label' => 'Home'],
    ['path' => 'frontend/pages/landing-page/About.php', 'label' => 'About us'],
    ['path' => 'frontend/pages/landing-page/Announcements.php', 'label' => 'Announcements'],
    ['path' => 'frontend/pages/landing-page/Events.php', 'label' => 'Events'],
    ['path' => 'frontend/pages/landing-page/Contact.php', 'label' => 'Contact us'],
];
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<?php include './frontend/components/Head.php'; ?>

<body class="bg-gray-100 text-gray-800">
    <!-- Navbar -->
    <header>
        <nav class="bg-slate-900 text-white shadow-md flex justify-between items-center px-10 py-5">
            <div class="flex items-center gap-4">
                <img src="./frontend/assets/images/Logo.webp" alt="Barangay Logo" class="h-10 w-10 rounded-full">
                <span class="font-bold text-md">BIS</span>
            </div>
            <!-- Desktop Navbar -->
            <ul class="hidden lg:flex space-x-6 font-medium text-sm">
                <?php foreach ($navbarLinks as $nav):
                    $isActive = ($current_page === basename($nav['path']))
                        ? 'text-white font-bold bg-green-500 rounded-full px-4 py-2'
                        : 'hover:bg-green-500 hover:text-white rounded-full px-4 py-2';
                ?>
                    <li>
                        <a href="<?= $nav['path'] ?>" class="<?= $isActive ?> hover:text-gray-300">
                            <?= $nav['label'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="./frontend/pages/login.php" class="px-4 py-2 rounded-full bg-slate-600 text-white shadow-md hover:bg-green-500 hover:text-white">
                        Login
                    </a>
                </li>
            </ul>
            <!-- Mobile Toggle Button -->
            <button class="lg:hidden text-white focus:outline-none"
                onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </nav>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden bg-slate-900 text-white hov p-4 lg:hidden">
            <?php foreach ($navbarLinks as $nav):
                $isActive = ($current_page === basename($nav['path']))
                    ? 'text-white font-bold bg-green-500 rounded-full px-4 py-2'
                    : 'hover:bg-green-500 hover:text-white rounded-full px-4 py-2';
            ?>
                <a href="<?= $nav['path'] ?>"
                    class="block py-2 px-4 <?= $isActive ?>">
                    <?= $nav['label'] ?>
                </a>
            <?php endforeach; ?>
            <a href="./frontend/pages/login.php" class="px-4 py-2 rounded-full bg-slate-600 text-white shadow-md hover:bg-green-500 hover:text-white  mx-2">
                Login
            </a>
        </div>
    </header>
    <!-- Main  -->
    <main>
        <!-- Hero Section -->
        <section class="relative bg-slate-700 text-white">
            <img src="./frontend/assets/images/home.jpg"
                alt="Barangay Hero" class="w-full h-96 object-cover brightness-50">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Brgy. Poblacion Sur</h1>
                <span class="text-md -mt-3 font-bold">Talavera, Nueva Ecija</span>
                <p class="text-lg md:text-2xl mb-6">Information | Connection | Community</p>
            </div>
        </section>
        <!-- Quick Info Section -->
        <section id="info-section" class="py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-12 text-indigo-700">Quick Information</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                    <!-- Services & Documents Card -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                        <i class="fas fa-concierge-bell text-indigo-600 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Services & Documents</h3>
                        <p class="text-gray-600 mb-4">Request barangay forms, permits, and access various programs and services conveniently online.</p>
                        <a href="./frontend/pages/landing-page/Services.php" class="text-indigo-700 font-semibold hover:underline">View Services</a>
                    </div>
                    <!-- Emergency Contacts Card -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center"
                        onclick="openContactsModal()">
                        <i class="fas fa-phone-alt text-red-500 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Emergency Contacts</h3>
                        <p class="text-gray-600 mb-4">Quick access to fire, police, health, and disaster helplines.</p>
                        <span class="text-indigo-700 font-semibold hover:underline cursor-pointer">View Contacts</span>
                    </div>
                    <!-- AI Health Survey -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                        <i class="fas fa-notes-medical text-red-500 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">AI Health Survey</h3>
                        <p class="text-gray-600 mb-4">Take our health survey to provide insights for the community.</p>
                        <button onclick="showLoginPrompt()" class="text-indigo-700 font-semibold hover:underline">Take Survey</button>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer -->
        <?php include './frontend/components/Footer.php'; ?>
    </main>
    <!-- Emergency Contacts Modal -->
    <div id="contacts-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-11/12 sm:w-96 p-6 relative">
            <h2 class="text-2xl font-bold mb-4 text-indigo-700">Emergency Contacts</h2>
            <button onclick="closeContactsModal()"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
            <ul class="flex flex-col gap-3">
                <li>
                    <a href="tel:09985985412" class="text-indigo-700 hover:underline flex justify-between">
                        Police <span>0998-598-5412</span>
                    </a>
                    <a href="tel:09536658826" class="text-indigo-700 hover:underline flex justify-between">
                        Police <span>0953-665-8826</span>
                    </a>
                </li>
                <li>
                    <a href="tel:09324675515" class="text-indigo-700 hover:underline flex justify-between">
                        Fire Department <span>0932-467-5515</span>
                    </a>
                </li>
                <li>
                    <a href="tel:09753163503" class="text-indigo-700 hover:underline flex justify-between">
                        Health Services <span>0975-316-3503</span>
                    </a>
                </li>
                <li>
                    <a href="tel:09155686067" class="text-indigo-700 hover:underline flex justify-between">
                        MDRRMO <span>0915-568-6067</span>
                    </a>
                    <a href="tel:09535093193" class="text-indigo-700 hover:underline flex justify-between">
                        MDRRMO <span>0953-509-3193</span>
                    </a>
                    <a href="tel:09558918793" class="text-indigo-700 hover:underline flex justify-between">
                        MDRRMO <span>0955-891-8793</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Login Prompt Modal -->
    <?php include './frontend/components/LoginPrompt_Modal.php'; ?>

    <script>
        function openContactsModal() {
            document.getElementById('contacts-modal').classList.remove('hidden');
            document.getElementById('contacts-modal').classList.add('flex');
        }

        function closeContactsModal() {
            document.getElementById('contacts-modal').classList.add('hidden');
            document.getElementById('contacts-modal').classList.remove('flex');
        }
    </script>
</body>




</html>