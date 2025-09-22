<?php
require_once "../../../backend/config/db.php";
session_start();

// Assume user_id comes from session
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch logged-in user health info
$stmt = $pdo->prepare("SELECT * FROM user_health_info WHERE user_id = ?");
$stmt->execute([$user_id]);
$health = $stmt->fetch(PDO::FETCH_ASSOC);

// Risk factors (BMI calculation if height & weight exist)
$bmi = null;
$bmi_category = "N/A";
if (!empty($health['height_cm']) && !empty($health['weight_kg'])) {
    $height_m = $health['height_cm'] / 100;
    $bmi = $health['weight_kg'] / ($height_m * $height_m);
    if ($bmi < 18.5) $bmi_category = "Underweight";
    elseif ($bmi < 24.9) $bmi_category = "Normal";
    elseif ($bmi < 29.9) $bmi_category = "Overweight";
    else $bmi_category = "Obese";
}

// COMMUNITY STATS
$stats = [];

// Gender
$stmt = $pdo->query("SELECT gender, COUNT(*) as total FROM user_details GROUP BY gender");
$stats['gender'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Blood Type
$stmt = $pdo->query("SELECT blood_type, COUNT(*) as total FROM user_details GROUP BY blood_type");
$stats['bloodtype'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// PWD
$stmt = $pdo->query("SELECT pwd_status, COUNT(*) as total FROM user_details GROUP BY pwd_status");
$stats['pwd'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build PWD data safely
$pwdData = ['PWD' => 0, 'Non-PWD' => 0];
foreach ($stats['pwd'] as $row) {
    if (strtolower($row['pwd_status']) === 'yes') {
        $pwdData['PWD'] = $row['total'];
    } else {
        $pwdData['Non-PWD'] = $row['total'];
    }
}

// Health Conditions
$stmt = $pdo->query("
    SELECT 
        SUM(CASE WHEN health_condition = 'Healthy' THEN 1 ELSE 0 END) as healthy,
        SUM(CASE WHEN health_condition = 'Minor Illness' THEN 1 ELSE 0 END) as minor,
        SUM(CASE WHEN health_condition = 'Chronic Illness' THEN 1 ELSE 0 END) as chronic,
        SUM(CASE WHEN health_condition = 'Disabled' THEN 1 ELSE 0 END) as disabled
    FROM user_health_info
");
$stats['condition'] = $stmt->fetch(PDO::FETCH_ASSOC);

// Common Issues
$stmt = $pdo->query("
    SELECT 
        SUM(CASE WHEN common_health_issue LIKE '%diabetes%' THEN 1 ELSE 0 END) as diabetes,
        SUM(CASE WHEN common_health_issue LIKE '%hypertension%' THEN 1 ELSE 0 END) as hypertension,
        SUM(CASE WHEN common_health_issue LIKE '%asthma%' THEN 1 ELSE 0 END) as asthma,
        SUM(CASE WHEN common_health_issue LIKE '%heart%' THEN 1 ELSE 0 END) as heart
    FROM user_health_info
");
$stats['issues'] = $stmt->fetch(PDO::FETCH_ASSOC);

// Average Height & Ranges
$stmt = $pdo->query("SELECT AVG(height_cm) as avg_height FROM user_health_info WHERE height_cm > 0");
$stats['avg_height'] = $stmt->fetch(PDO::FETCH_ASSOC)['avg_height'] ?? 0;

$stmt = $pdo->query("
    SELECT 
        SUM(CASE WHEN height_cm < 150 THEN 1 ELSE 0 END) as short,
        SUM(CASE WHEN height_cm BETWEEN 150 AND 170 THEN 1 ELSE 0 END) as average,
        SUM(CASE WHEN height_cm > 170 THEN 1 ELSE 0 END) as tall
    FROM user_health_info
");
$stats['height_ranges'] = $stmt->fetch(PDO::FETCH_ASSOC);

// Average Weight & Ranges (using BMI categories)
$stmt = $pdo->query("SELECT AVG(weight_kg) as avg_weight FROM user_health_info WHERE weight_kg > 0");
$stats['avg_weight'] = $stmt->fetch(PDO::FETCH_ASSOC)['avg_weight'] ?? 0;

$stmt = $pdo->query("
    SELECT 
        SUM(CASE WHEN (weight_kg/(POWER(height_cm/100,2))) < 18.5 THEN 1 ELSE 0 END) as underweight,
        SUM(CASE WHEN (weight_kg/(POWER(height_cm/100,2))) BETWEEN 18.5 AND 24.9 THEN 1 ELSE 0 END) as normal,
        SUM(CASE WHEN (weight_kg/(POWER(height_cm/100,2))) BETWEEN 25 AND 29.9 THEN 1 ELSE 0 END) as overweight,
        SUM(CASE WHEN (weight_kg/(POWER(height_cm/100,2))) >= 30 THEN 1 ELSE 0 END) as obese
    FROM user_health_info
    WHERE height_cm > 0 AND weight_kg > 0
");
$stats['bmi_ranges'] = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BSIS - Health Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<?php include('admin-head.php'); ?>

<body class="bg-gray-100 font-sans">
    <?php include('../../components/DashNav.php'); ?>

    <main class="pt-24 px-4 sm:px-6 lg:px-10 space-y-8">

        <!-- PERSONAL HEALTH REPORT -->
        <div class="container mx-auto bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-4">My Health Report</h1>

            <?php if ($health): ?>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm md:text-base">
                    <p><b>Health Condition:</b> <?= htmlspecialchars($health['health_condition']) ?></p>
                    <p><b>Common Health Issue:</b> <?= htmlspecialchars($health['common_health_issue']) ?></p>
                    <p><b>Vaccination Status:</b> <?= htmlspecialchars($health['vaccination_status']) ?></p>
                    <p><b>Height (cm):</b> <?= htmlspecialchars($health['height_cm']) ?></p>
                    <p><b>Weight (kg):</b> <?= htmlspecialchars($health['weight_kg']) ?></p>
                    <p><b>BMI:</b> <?= $bmi ? round($bmi, 1) . " ($bmi_category)" : "N/A" ?></p>
                </div>

                <!-- Personal Health Charts -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                    <div class="p-4 border rounded">
                        <canvas id="myConditionChart"></canvas>
                    </div>
                    <div class="p-4 border rounded">
                        <canvas id="myVaccinationChart"></canvas>
                    </div>
                    <div class="p-4 border rounded col-span-1 md:col-span-2">
                        <canvas id="myIssuesChart"></canvas>
                    </div>
                    <div class="p-4 border rounded">
                        <canvas id="myBMIChart"></canvas>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No health information available.</p>
            <?php endif; ?>
        </div>

        <!-- COMMUNITY HEALTH STATISTICS -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <div class="p-4 border rounded"><canvas id="genderChart"></canvas></div>
    <div class="p-4 border rounded"><canvas id="bloodChart"></canvas></div>
    <div class="p-4 border rounded"><canvas id="conditionChart"></canvas></div>
    <div class="p-4 border rounded col-span-1 md:col-span-2"><canvas id="issuesChart"></canvas></div>
    <div class="p-4 border rounded"><canvas id="heightChart"></canvas></div>
    <div class="p-4 border rounded"><canvas id="heightRangeChart"></canvas></div>
    <div class="p-4 border rounded"><canvas id="weightChart"></canvas></div>
    <div class="p-4 border rounded"><canvas id="bmiRangeChart"></canvas></div>
    <div class="p-4 border rounded"><canvas id="pwdChart"></canvas></div>
</div>

        </div>
    </main>

    <script>
        // ===== MY HEALTH CHARTS =====
        <?php if ($health): ?>
        new Chart(document.getElementById('myConditionChart'), {
            type: 'pie',
            data: {
                labels: ['<?= $health['health_condition'] ?>'],
                datasets: [{
                    data: [1],
                    backgroundColor: ['#3b82f6']
                }]
            }
        });

        new Chart(document.getElementById('myVaccinationChart'), {
            type: 'doughnut',
            data: {
                labels: ['Vaccinated', 'Not Vaccinated'],
                datasets: [{
                    data: [
                        <?= strtolower($health['vaccination_status']) === "yes" ? 1 : 0 ?>,
                        <?= strtolower($health['vaccination_status']) === "yes" ? 0 : 1 ?>
                    ],
                    backgroundColor: ['#10b981', '#ef4444']
                }]
            }
        });

        new Chart(document.getElementById('myIssuesChart'), {
            type: 'bar',
            data: {
                labels: ['Diabetes', 'Hypertension', 'Asthma', 'Heart Disease'],
                datasets: [{
                    data: [
                        <?= stripos($health['common_health_issue'], 'diabetes') !== false ? 1 : 0 ?>,
                        <?= stripos($health['common_health_issue'], 'hypertension') !== false ? 1 : 0 ?>,
                        <?= stripos($health['common_health_issue'], 'asthma') !== false ? 1 : 0 ?>,
                        <?= stripos($health['common_health_issue'], 'heart') !== false ? 1 : 0 ?>
                    ],
                    backgroundColor: ['#3b82f6', '#f97316', '#10b981', '#ef4444']
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: 1 } } }
        });

        new Chart(document.getElementById('myBMIChart'), {
            type: 'bar',
            data: {
                labels: ['<?= $bmi_category ?>'],
                datasets: [{
                    label: 'BMI',
                    data: [<?= $bmi ? round($bmi, 1) : 0 ?>],
                    backgroundColor: '#6366f1'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
        <?php endif; ?>

        // ===== COMMUNITY HEALTH CHARTS =====
        new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($stats['gender'], 'gender')) ?>,
                datasets: [{
                    data: <?= json_encode(array_map('intval', array_column($stats['gender'], 'total'))) ?>,
                    backgroundColor: ['#3b82f6', '#f472b6', '#facc15']
                }]
            }
        });

        new Chart(document.getElementById('bloodChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($stats['bloodtype'], 'blood_type')) ?>,
                datasets: [{
                    label: 'Count',
                    data: <?= json_encode(array_map('intval', array_column($stats['bloodtype'], 'total'))) ?>,
                    backgroundColor: '#10b981'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('conditionChart'), {
            type: 'pie',
            data: {
                labels: ['Healthy', 'Minor Illness', 'Chronic Illness', 'Disabled'],
                datasets: [{
                    data: [
                        <?= (int)$stats['condition']['healthy'] ?>,
                        <?= (int)$stats['condition']['minor'] ?>,
                        <?= (int)$stats['condition']['chronic'] ?>,
                        <?= (int)$stats['condition']['disabled'] ?>
                    ],
                    backgroundColor: ['#22c55e', '#fbbf24', '#ef4444', '#6b7280']
                }]
            }
        });

        new Chart(document.getElementById('issuesChart'), {
            type: 'bar',
            data: {
                labels: ['Diabetes', 'Hypertension', 'Asthma', 'Heart Disease'],
                datasets: [{
                    label: 'Cases',
                    data: [
                        <?= (int)$stats['issues']['diabetes'] ?>,
                        <?= (int)$stats['issues']['hypertension'] ?>,
                        <?= (int)$stats['issues']['asthma'] ?>,
                        <?= (int)$stats['issues']['heart'] ?>
                    ],
                    backgroundColor: ['#3b82f6', '#f97316', '#10b981', '#ef4444']
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('pwdChart'), {
            type: 'doughnut',
            data: {
                labels: ['PWD', 'Non-PWD'],
                datasets: [{
                    data: [<?= (int)$pwdData['PWD'] ?>, <?= (int)$pwdData['Non-PWD'] ?>],
                    backgroundColor: ['#8b5cf6', '#d1d5db']
                }]
            }
        });
        // Average Height Chart
new Chart(document.getElementById('heightChart'), {
    type: 'bar',
    data: {
        labels: ['Average Height (cm)'],
        datasets: [{
            label: 'Height',
            data: [<?= round($stats['avg_height'], 1) ?>],
            backgroundColor: '#3b82f6'
        }]
    },
    options: { responsive: true, plugins: { title: { display: true, text: 'Community Average Height' } } }
});

// Height Range Chart
new Chart(document.getElementById('heightRangeChart'), {
    type: 'pie',
    data: {
        labels: ['Short (<150cm)', 'Average (150–170cm)', 'Tall (>170cm)'],
        datasets: [{
            data: [
                <?= (int)$stats['height_ranges']['short'] ?>,
                <?= (int)$stats['height_ranges']['average'] ?>,
                <?= (int)$stats['height_ranges']['tall'] ?>
            ],
            backgroundColor: ['#f97316', '#10b981', '#3b82f6']
        }]
    },
    options: { responsive: true, plugins: { title: { display: true, text: 'Height Distribution' } } }
});

// Average Weight Chart
new Chart(document.getElementById('weightChart'), {
    type: 'bar',
    data: {
        labels: ['Average Weight (kg)'],
        datasets: [{
            label: 'Weight',
            data: [<?= round($stats['avg_weight'], 1) ?>],
            backgroundColor: '#facc15'
        }]
    },
    options: { responsive: true, plugins: { title: { display: true, text: 'Community Average Weight' } } }
});

// BMI Range Chart
new Chart(document.getElementById('bmiRangeChart'), {
    type: 'doughnut',
    data: {
        labels: ['Underweight', 'Normal', 'Overweight', 'Obese'],
        datasets: [{
            data: [
                <?= (int)$stats['bmi_ranges']['underweight'] ?>,
                <?= (int)$stats['bmi_ranges']['normal'] ?>,
                <?= (int)$stats['bmi_ranges']['overweight'] ?>,
                <?= (int)$stats['bmi_ranges']['obese'] ?>
            ],
            backgroundColor: ['#60a5fa', '#22c55e', '#fbbf24', '#ef4444']
        }]
    },
    options: { responsive: true, plugins: { title: { display: true, text: 'BMI Distribution' } } }
});

    </script>
</body>
</html>
