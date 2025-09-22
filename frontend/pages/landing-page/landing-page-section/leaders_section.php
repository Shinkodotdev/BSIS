<section class="mb-12">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Meet Our Leaders</h2>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8 text-center">
        <?php
        require_once '../../../backend/config/db.php';

        // All required positions
        $requiredPositions = [
            "Barangay Captain",
            "Barangay Secretary",
            "Barangay Councilor",
            "SK Chairman",
            "SK Councilor"
        ];

        // Fetch officials from DB
        $stmt = $pdo->prepare("
        SELECT 
            o.position,
            CONCAT(d.f_name, ' ', IFNULL(d.m_name,''), ' ', d.l_name, ' ', IFNULL(d.ext_name,'')) AS full_name,
            d.photo
        FROM officials o
        JOIN users u ON o.user_id = u.user_id
        JOIN user_details d ON u.user_id = d.user_id
        WHERE u.status = 'Approved' AND u.role = 'Official'
    ");
        $stmt->execute();
        $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map existing officials by position
        $officialMap = [];
        foreach ($officials as $o) {
            $officialMap[$o['position']] = $o;
        }

        // Loop through required positions
        foreach ($requiredPositions as $position):
            $official = $officialMap[$position] ?? null;
            $photo = $official && !empty($official['photo'])
                ? '../../uploads/profile/' . htmlspecialchars($official['photo'])
                : '../../assets/images/home.jpg';
            $full_name = $official['full_name'] ?? 'Vacant';
        ?>
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($position) ?>" class="w-24 h-24 mx-auto rounded-full mb-4">
                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($full_name) ?></h3>
                <p class="text-gray-600 text-sm"><?= htmlspecialchars($position) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>