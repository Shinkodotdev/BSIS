<?php
session_start();
require_once "../../../backend/config/db.php";

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch all user info
$sql = "
    SELECT 
        u.user_id, u.email, u.role, u.status, u.is_alive, u.created_at, u.updated_at,

        ud.f_name, ud.m_name, ud.l_name, ud.ext_name, ud.gender, ud.photo, ud.contact_no, ud.civil_status,
        ud.occupation, ud.nationality, ud.voter_status, ud.valid_id_path, ud.pwd_status, 
        ud.senior_citizen_status, ud.religion, ud.blood_type, ud.educational_attainment,

        ub.birth_date, ub.birth_place,

        ur.house_no, ur.purok, ur.barangay, ur.municipality, ur.province, ur.years_residency, 
        ur.household_head, ur.house_type, ur.ownership_status, ur.previous_address,

        uf.fathers_name, uf.fathers_birthplace, uf.mothers_name, uf.mothers_birthplace, 
        uf.spouse_name, uf.num_dependents, uf.contact_person, uf.emergency_contact_no,

        uh.health_condition, uh.common_health_issue, uh.vaccination_status, uh.height_cm, uh.weight_kg, 
        uh.last_medical_checkup, uh.health_remarks,

        ui.monthly_income, ui.income_source, ui.household_members, ui.additional_income_sources,
        ui.household_head_occupation, ui.income_proof,

        uid.id_type, uid.front_valid_id_path, uid.back_valid_id_path, uid.selfie_with_id

    FROM users u
    LEFT JOIN user_details ud ON u.user_id = ud.user_id
    LEFT JOIN user_birthdates ub ON u.user_id = ub.user_id
    LEFT JOIN user_residency ur ON u.user_id = ur.user_id
    LEFT JOIN user_family_info uf ON u.user_id = uf.user_id
    LEFT JOIN user_health_info uh ON u.user_id = uh.user_id
    LEFT JOIN user_income_info ui ON u.user_id = ui.user_id
    LEFT JOIN user_identity_docs uid ON u.user_id = uid.user_id
    WHERE u.user_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "No profile data found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BSIS - Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php include('resident-head.php'); ?>

<body class="bg-gray-100 font-sans">
    <?php include('../../components/DashNav.php'); ?>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">User Profile</h1>

        <!-- PERSONAL INFO -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 relative">
            <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
            <button onclick="openModal('personalModal')" class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">Edit</button>
            <p><b>Name:</b> <?= $data['f_name'] . " " . ($data['m_name'] ?? '') . " " . $data['l_name'] . " " . ($data['ext_name'] ?? '') ?></p>
            <p><b>Email:</b> <?= $data['email'] ?></p>
            <p><b>Gender:</b> <?= $data['gender'] ?? 'N/A' ?></p>
            <p><b>Contact No:</b> <?= $data['contact_no'] ?? 'N/A' ?></p>
            <p><b>Civil Status:</b> <?= $data['civil_status'] ?? 'N/A' ?></p>
            <p><b>Occupation:</b> <?= $data['occupation'] ?? 'N/A' ?></p>
            <p><b>Nationality:</b> <?= $data['nationality'] ?? 'N/A' ?></p>
            <p><b>Voter Status:</b> <?= $data['voter_status'] ?? 'N/A' ?></p>
            <p><b>PWD:</b> <?= $data['pwd_status'] ?? 'N/A' ?></p>
            <p><b>Senior Citizen:</b> <?= $data['senior_citizen_status'] ?? 'N/A' ?></p>
            <p><b>Religion:</b> <?= $data['religion'] ?? 'N/A' ?></p>
            <p><b>Blood Type:</b> <?= $data['blood_type'] ?? 'N/A' ?></p>
            <p><b>Education:</b> <?= $data['educational_attainment'] ?? 'N/A' ?></p>
        </div>

        <!-- BIRTH INFO -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 relative">
            <h2 class="text-xl font-semibold mb-4">Birth Information</h2>
            <button onclick="openModal('birthModal')" class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">Edit</button>
            <p><b>Date of Birth:</b> <?= $data['birth_date'] ?? 'N/A' ?></p>
            <p><b>Place of Birth:</b> <?= $data['birth_place'] ?? 'N/A' ?></p>
        </div>

        <!-- RESIDENCY INFO -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 relative">
            <h2 class="text-xl font-semibold mb-4">Residency</h2>
            <button onclick="openModal('residencyModal')" class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">Edit</button>
            <p><b>House No:</b> <?= $data['house_no'] ?? 'N/A' ?></p>
            <p><b>Purok:</b> <?= $data['purok'] ?? 'N/A' ?></p>
            <p><b>Barangay:</b> <?= $data['barangay'] ?? 'N/A' ?></p>
            <p><b>Municipality:</b> <?= $data['municipality'] ?? 'N/A' ?></p>
            <p><b>Province:</b> <?= $data['province'] ?? 'N/A' ?></p>
            <p><b>Years of Residency:</b> <?= $data['years_residency'] ?? 'N/A' ?></p>
            <p><b>Household Head:</b> <?= $data['household_head'] ?? 'N/A' ?></p>
            <p><b>House Type:</b> <?= $data['house_type'] ?? 'N/A' ?></p>
            <p><b>Ownership Status:</b> <?= $data['ownership_status'] ?? 'N/A' ?></p>
            <p><b>Previous Address:</b> <?= $data['previous_address'] ?? 'N/A' ?></p>
        </div>

        <!-- FAMILY INFO -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 relative">
            <h2 class="text-xl font-semibold mb-4">Family Information</h2>
            <button onclick="openModal('familyModal')" class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">Edit</button>
            <p><b>Father:</b> <?= $data['fathers_name'] ?? 'N/A' ?> (<?= $data['fathers_birthplace'] ?? 'N/A' ?>)</p>
            <p><b>Mother:</b> <?= $data['mothers_name'] ?? 'N/A' ?> (<?= $data['mothers_birthplace'] ?? 'N/A' ?>)</p>
            <p><b>Spouse:</b> <?= $data['spouse_name'] ?? 'N/A' ?></p>
            <p><b>Dependents:</b> <?= $data['num_dependents'] ?? 'N/A' ?></p>
            <p><b>Emergency Contact:</b> <?= $data['contact_person'] ?? 'N/A' ?> (<?= $data['emergency_contact_no'] ?? 'N/A' ?>)</p>
        </div>

        <!-- HEALTH INFO -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 relative">
            <h2 class="text-xl font-semibold mb-4">Health Information</h2>
            <button onclick="openModal('healthModal')" class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">Edit</button>
            <p><b>Condition:</b> <?= $data['health_condition'] ?? 'N/A' ?></p>
            <p><b>Common Issues:</b> <?= $data['common_health_issue'] ?? 'N/A' ?></p>
            <p><b>Vaccination:</b> <?= $data['vaccination_status'] ?? 'N/A' ?></p>
            <p><b>Height:</b> <?= $data['height_cm'] ?? 'N/A' ?> cm</p>
            <p><b>Weight:</b> <?= $data['weight_kg'] ?? 'N/A' ?> kg</p>
            <p><b>Last Checkup:</b> <?= $data['last_medical_checkup'] ?? 'N/A' ?></p>
            <p><b>Remarks:</b> <?= $data['health_remarks'] ?? 'N/A' ?></p>
        </div>

        <!-- INCOME INFO -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 relative">
            <h2 class="text-xl font-semibold mb-4">Income Information</h2>
            <button onclick="openModal('incomeModal')" class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">Edit</button>
            <p><b>Monthly Income:</b> <?= $data['monthly_income'] ?? 'N/A' ?></p>
            <p><b>Source:</b> <?= $data['income_source'] ?? 'N/A' ?></p>
            <p><b>Household Members:</b> <?= $data['household_members'] ?? 'N/A' ?></p>
            <p><b>Additional Sources:</b> <?= $data['additional_income_sources'] ?? 'N/A' ?></p>
            <p><b>Head Occupation:</b> <?= $data['household_head_occupation'] ?? 'N/A' ?></p>
        </div>

        <!-- IDENTITY DOCS -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 relative">
            <h2 class="text-xl font-semibold mb-4">Identity Documents</h2>
            <button onclick="openModal('identityModal')"
                class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">
                Edit
            </button>

            <p><b>ID Type:</b> <?= $data['id_type'] ?? 'N/A' ?></p>

            <!-- Front ID -->
            <p><b>Front ID:</b></p>
            <?php if (!empty($data['front_valid_id_path'])): ?>
                <img src="<?= '/Barangay_Information_System/uploads/ids/front/' . basename($data['front_valid_id_path']); ?>"
                    class="mx-auto w-full max-w-xs h-auto rounded shadow mb-4">
            <?php else: ?>
                <p class="text-gray-500">N/A</p>
            <?php endif; ?>

            <!-- Back ID -->
            <p><b>Back ID:</b></p>
            <?php if (!empty($data['back_valid_id_path'])): ?>
                <img src="<?= '/Barangay_Information_System/uploads/ids/back/' . basename($data['back_valid_id_path']); ?>"
                    class="mx-auto w-full max-w-xs h-auto rounded shadow mb-4">
            <?php else: ?>
                <p class="text-gray-500">N/A</p>
            <?php endif; ?>

            <!-- Selfie with ID -->
            <p><b>Selfie with ID:</b></p>
            <?php if (!empty($data['selfie_with_id'])): ?>
                <img src="<?= '/Barangay_Information_System/uploads/ids/selfie/' . basename($data['selfie_with_id']); ?>"
                    class="mx-auto w-full max-w-xs h-auto rounded shadow">
            <?php else: ?>
                <p class="text-gray-500">N/A</p>
            <?php endif; ?>
        </div>



        <!-- ===== MODALS ===== -->
        <?php include('../../assets/modals/personal_profile_modal.php'); ?>

        <script>
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
            }
        </script>
</body>

</html>