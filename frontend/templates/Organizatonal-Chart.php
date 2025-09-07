<?php
// db connection
$conn = new mysqli("localhost", "root", "", "barangay_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch officials
$result = $conn->query("SELECT * FROM officials");
$officials = [];
while ($row = $result->fetch_assoc()) {
    $officials[$row['role']][] = $row; // group by role
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Barangay Organizational Chart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-100" x-data="{ showModal: false, selected: {} }">
    <section class="p-6">
        <!-- Back Button -->
        <div class="fixed top-4 left-4 z-20">
            <a href="../../public/index.php"
                class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg shadow-md hover:bg-gray-700 transition">
                <!-- Back Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>

        <!-- Title -->
        <h1 class="text-2xl sm:text-3xl font-bold text-center mb-10">
            Barangay Brookside - City of Baguio
        </h1>

        <!-- Barangay Captain -->
        <?php if (!empty($officials['Punong Barangay'])):
            $captain = $officials['Punong Barangay'][0]; ?>
            <div class="flex justify-center mb-16 relative">
                <div class="bg-white rounded-lg shadow-md p-4 w-40 text-center cursor-pointer hover:shadow-lg transition"
                    @click='selected = <?= json_encode($captain) ?>; showModal = true'>
                    <img src="uploads/<?= $captain['image'] ?>" class="w-20 h-20 mx-auto rounded-full mb-2" alt="">
                    <h2 class="font-bold text-sm"><?= $captain['name'] ?></h2>
                    <p class="text-xs text-gray-600"><?= $captain['role'] ?></p>
                </div>
                <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 w-0.5 h-8 bg-gray-400"></div>
            </div>
        <?php endif; ?>

        <!-- Secretary & Treasurer -->
        <div class="flex justify-center items-start mb-16 relative">
            <div class="absolute top-0 left-1/4 right-1/4 border-t-2 border-gray-400"></div>

            <?php foreach (['Secretary', 'Treasurer'] as $role):
                if (!empty($officials[$role])):
                    $o = $officials[$role][0]; ?>
                    <div class="flex flex-col items-center mx-8">
                        <div class="w-0.5 h-8 bg-gray-400"></div>
                        <div class="bg-white rounded-lg shadow-md p-4 w-32 text-center cursor-pointer hover:shadow-lg transition"
                            @click='selected = <?= json_encode($o) ?>; showModal = true'>
                            <img src="uploads/<?= $o['image'] ?>" class="w-16 h-16 mx-auto rounded-full mb-2" alt="">
                            <h2 class="font-bold text-xs"><?= $o['name'] ?></h2>
                            <p class="text-[11px] text-gray-600"><?= $o['role'] ?></p>
                        </div>
                    </div>
            <?php endif;
            endforeach; ?>
        </div>

        <!-- Kagawads -->
        <div class="flex flex-wrap justify-center gap-6 relative ">
            <div class="absolute -top-4 left-0 right-0 border-t-2 border-gray-400 "></div>
            <?php foreach ($officials as $role => $list):
                if (!in_array($role, ['Punong Barangay', 'Secretary', 'Treasurer'])):
                    foreach ($list as $o): ?>
                        <div class="flex flex-col items-center">
                            <div class="w-0.5 h-8  bg-gray-400"></div>
                            <div class="bg-white rounded-lg shadow-md p-4 w-32 h-40 text-center cursor-pointer hover:shadow-lg transition"
                                @click='selected = <?= json_encode($o) ?>; showModal = true'>
                                <img src="uploads/<?= $o['image'] ?>" class="w-16 h-16 mx-auto rounded-full mb-2" alt="">
                                <h2 class="font-bold text-xs"><?= $o['name'] ?></h2>
                                <p class="text-[10px] text-gray-600 text-wrap"><?= $o['role'] ?></p>
                            </div>
                        </div>
            <?php endforeach;
                endif;
            endforeach; ?>
        </div>
    </section>

    <!-- Modal -->
    <div x-show="showModal" x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden relative">

            <!-- Close Button -->
            <button @click="showModal = false"
                class="absolute top-3 right-3 text-gray-500 hover:text-black text-lg">
                ✖
            </button>

            <!-- Header with Profile Image -->
            <div class="flex flex-col items-center p-6 border-b">
                <img :src="'uploads/' + (selected.image ?? 'user.png')"
                    class="w-20 h-20 rounded-full mb-3 object-cover shadow-md" alt="Profile">
                <h2 class="text-xl font-bold text-center" x-text="selected.name"></h2>
                <p class="text-gray-600 text-sm" x-text="selected.role"></p>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-3 max-h-[70vh] overflow-y-auto">
                <p class="text-gray-700"><strong>Email:</strong> <span x-text="selected.email"></span></p>
                <p class="text-gray-700"><strong>Phone:</strong> <span x-text="selected.phone"></span></p>
            </div>
        </div>
    </div>

</body>

</html>