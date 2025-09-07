<!DOCTYPE html>
<html lang="en">
<?php include '../../components/Head.php'; ?>

<body class="bg-gray-50">
    <?php include '../../components/Navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Hero Section -->
        <section class="text-center mb-12">
            <h1 class="text-4xl font-bold text-blue-600">About Barangay Poblacion Sur</h1>
            <p class="mt-4 text-gray-600 max-w-3xl mx-auto">
                Barangay Poblacion Sur is one of the eight barangays in the town center of Talavera, Nueva Ecija. 
                Located in the southern part of the municipality, it combines history, tradition, and progress in one vibrant community.
            </p>
        </section>

        <!-- History Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">📜 History</h2>
            <div class="bg-white shadow-md p-6 rounded-xl hover:shadow-lg transition">
                <p class="text-gray-600 mb-4">
                    The poblacion of Talavera was originally one large central community stretching from Calipahan in the north to La Torre in the south. 
                    In 1972, it was subdivided into eight barangays to better manage population and governance, including Poblacion Sur, locally called “Dulong Bayan” or “may Kamposanto.”
                </p>
                <p class="text-gray-600 mb-4">
                    Some of the earliest families include Alejandro, Aquino, Cipriano, dela Cruz, Manabat, Mendez, Mendoza, Santos, Sebastian, Puselero, Valencia, and Villacorte clans. Their contributions helped shape the community from farming and trade to local leadership.
                </p>
                <p class="text-gray-600">
                    Historic leaders such as Elias Ferrer and Timoteo Parungao served as municipal heads of Talavera, reflecting Poblacion Sur’s longstanding role in governance.
                </p>
            </div>
        </section>

        <!-- Geography & Demographics -->
        <section class="mb-12 grid md:grid-cols-2 gap-8">
            <div class="bg-white shadow-md p-6 rounded-xl hover:shadow-lg transition">
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">🌍 Geography & Location</h2>
                <ul class="text-gray-600 list-disc list-inside">
                    <li>Coordinates: 15.5766° N, 120.9183° E</li>
                    <li>Elevation: ~45 meters above sea level</li>
                    <li>Boundaries:
                        <ul class="list-disc ml-5">
                            <li>North – Pag-asa (Poblacion) and Sampaloc (Poblacion)</li>
                            <li>South – Barangay Concepcion (Santo Domingo) and Barangay La Torre</li>
                            <li>East – Barangay Bantug</li>
                            <li>West – Maestrang Kikay (Poblacion)</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="bg-white shadow-md p-6 rounded-xl hover:shadow-lg transition">
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">👥 Population & Community</h2>
                <p class="text-gray-600 mb-3">
                    As of the 2020 Census, Poblacion Sur has 3,651 residents (~2.76% of Talavera's population). The barangay has steadily grown from 2,244 in 1990 with an annual growth rate of 1.45% between 2015 and 2020.
                </p>
                <p class="text-gray-600">
                    Families maintain strong cultural ties and participate actively in local fiestas, events, and religious celebrations, fostering the spirit of bayanihan and pakikipagkapwa.
                </p>
            </div>
        </section>

        <!-- Economy & Services -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">🏢 Economy & Services</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-blue-600">💼 Local Businesses</h3>
                    <p class="text-gray-600 mt-2">Small-scale enterprises, retail shops, and food stalls contribute to the local economy.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-blue-600">🏫 Education & Institutions</h3>
                    <p class="text-gray-600 mt-2">Poblacion Sur Elementary School and nearby secondary/tertiary institutions ensure accessible education for all residents.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-blue-600">⛪ Culture & Community</h3>
                    <p class="text-gray-600 mt-2">Churches, chapels, and community centers serve as landmarks for spiritual growth and social gatherings.</p>
                </div>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="grid md:grid-cols-2 gap-8 mb-12">
            <div class="bg-white shadow-md p-6 rounded-xl hover:shadow-lg transition">
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">🎯 Our Mission</h2>
                <p class="text-gray-600">To improve access to barangay services, promote transparency, and empower citizens through digital solutions.</p>
            </div>
            <div class="bg-white shadow-md p-6 rounded-xl hover:shadow-lg transition">
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">🌟 Our Vision</h2>
                <p class="text-gray-600">A connected, empowered, and resilient barangay that thrives in the digital age.</p>
            </div>
        </section>

        <!-- Leaders Section -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Meet Our Leaders</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8 text-center">
            <?php
            require_once '../../../backend/config/db.php';

            // Fetch officials joined with user details
            $stmt = $pdo->prepare("
                SELECT u.user_id, 
                    CONCAT(d.f_name, ' ', IFNULL(d.m_name,''), ' ', d.l_name, ' ', IFNULL(d.ext_name,'')) AS full_name,
                    d.photo,
                    o.position
                FROM officials o
                JOIN users u ON o.user_id = u.user_id
                JOIN user_details d ON u.user_id = d.user_id
                WHERE u.status = 'Approved' AND u.role = 'Official'
                ORDER BY FIELD(o.position, 'Barangay Captain', 'Kagawad', 'SK Chairman')
            ");
            $stmt->execute();
            $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($officials as $official):
                $photo = !empty($official['photo']) ? $official['photo'] : 'https://via.placeholder.com/150';
            ?>
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($official['position']) ?>" class="w-24 h-24 mx-auto rounded-full mb-4">
                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($official['full_name']) ?></h3>
                <p class="text-gray-600 text-sm"><?= htmlspecialchars($official['position']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>



        <!-- CTA Section -->
        <section class="text-center bg-blue-600 text-white p-10 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold mb-4">Got Suggestions?</h2>
            <p class="mb-6">Help us improve the Smart Barangay Information System by sharing your ideas.</p>
            <a href="Contact.php" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg shadow hover:bg-gray-100 transition">Contact Us</a>
        </section>

    </main>

<?php include '../../components/Footer.php'; ?>
</body>
</html>
