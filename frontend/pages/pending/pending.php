<?php
session_start();
require_once "../../../backend/config/db.php";
require_once "../../../backend/auth/login.php";
require_once "./user_pending_check.php";

$status = $_SESSION['status'] ?? 'Pending';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT 
        d.*, 
        r.house_no, r.purok, r.barangay, r.municipality, r.province, 
        r.years_residency, r.household_head, r.house_type, 
        r.ownership_status, r.previous_address,

        b.birth_date, b.birth_place,
        
        i.id_type, i.front_valid_id_path, i.back_valid_id_path, i.selfie_with_id,
        
        f.fathers_name, f.fathers_birthplace, f.mothers_name, f.mothers_birthplace,
        f.spouse_name, f.num_dependents, f.contact_person, f.emergency_contact_no,
        
        h.health_condition, h.common_health_issue,
        h.vaccination_status, h.height_cm, h.weight_kg, h.last_medical_checkup, h.health_remarks,
        
        inc.monthly_income, inc.income_source, inc.household_members,
        inc.additional_income_sources, inc.household_head_occupation, inc.income_proof
        
    FROM user_details d
    LEFT JOIN user_residency r ON d.user_id = r.user_id
    LEFT JOIN user_birthdates b ON d.user_id = b.user_id
    LEFT JOIN user_identity_docs i ON d.user_id = i.user_id
    LEFT JOIN user_family_info f ON d.user_id = f.user_id
    LEFT JOIN user_health_info h ON d.user_id = h.user_id
    LEFT JOIN user_income_info inc ON d.user_id = inc.user_id
    WHERE d.user_id = ?
");
$stmt->execute([$user_id]);
$userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

function displayOrNA($value)
{
    return !empty($value) ? htmlspecialchars($value) : 'N/A';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Barangay Information System Poblacion Sur">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Profile | Barangay Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="../../assets/images/Logo.webp" type="image/x-icon">
</head>

<body class="bg-gradient-to-r from-indigo-100 to-blue-100 min-h-screen flex flex-col items-center justify-center p-6">

    <div class="bg-white shadow-2xl rounded-2xl p-6 sm:p-10 w-full max-w-6xl">
        <h1 class="text-4xl font-bold mb-6 text-center text-indigo-700 flex items-center justify-center gap-3">
            <i class="fa-solid fa-user-circle text-indigo-600"></i> My Profile
        </h1>

        <!-- ✅ Profile Photo -->
        <div class="flex flex-col items-center mb-8">
            <?php if (!empty($userDetails['photo'])): ?>
                <img src="<?= '/Barangay_Information_System/uploads/profile/' . basename($userDetails['photo']); ?>"
                    class="w-32 h-32 object-cover rounded-full shadow-lg border-4 border-indigo-200 hover:scale-105 transition-transform duration-300">
            <?php else: ?>
                <div
                    class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 shadow-inner text-lg">
                    <i class="fa-solid fa-user"></i>
                </div>
                <p class="text-gray-500 italic mt-2">No profile photo uploaded</p>
            <?php endif; ?>
        </div>

        <!-- ✅ Status -->
        <p class="text-center mb-8 text-lg">
            <?php if ($status === 'Pending'): ?>
                <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg shadow-sm">
                    <i class="fa-solid fa-clock"></i> Please check your information and wait for admin approval.
                </span>
            <?php else: ?>
                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg shadow-sm">
                    <i class="fa-solid fa-check-circle"></i> Your profile is Pending. You may review your details below.
                </span>
            <?php endif; ?>
        </p>

        <?php if ($userDetails): ?>

            <!-- Reusable section style -->
            <?php
            function sectionHeader($icon, $title)
            {
                echo "<h2 class='text-2xl font-semibold text-indigo-600 border-b pb-2 mb-4 flex items-center gap-2'>
                <i class='fa-solid $icon'></i> $title
            </h2>";
            }
            ?>

            <!-- ✅ Personal Info -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <?php sectionHeader("fa-id-card", "Personal Information"); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><span class="font-semibold">First Name:</span> <?= displayOrNA($userDetails['f_name']); ?></div>
                    <div><span class="font-semibold">Middle Name:</span> <?= displayOrNA($userDetails['m_name']); ?></div>
                    <div><span class="font-semibold">Last Name:</span> <?= displayOrNA($userDetails['l_name']); ?></div>
                    <div><span class="font-semibold">Extension:</span> <?= displayOrNA($userDetails['ext_name']); ?></div>
                    <div><span class="font-semibold">Gender:</span> <?= displayOrNA($userDetails['gender']); ?></div>
                    <div><span class="font-semibold">Contact:</span> <?= displayOrNA($userDetails['contact_no']); ?></div>
                    <div><span class="font-semibold">Civil Status:</span> <?= displayOrNA($userDetails['civil_status']); ?></div>
                    <div><span class="font-semibold">Occupation:</span> <?= displayOrNA($userDetails['occupation']); ?></div>
                    <div><span class="font-semibold">Nationality:</span> <?= displayOrNA($userDetails['nationality']); ?></div>
                    <div><span class="font-semibold">Religion:</span> <?= displayOrNA($userDetails['religion']); ?></div>
                </div>
            </section>


            <!-- ✅ Birth Info -->
            <?php if ($userDetails['birth_date'] || $userDetails['birth_place']): ?>
                <section class="bg-gray-50 p-6 rounded-xl shadow-sm mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Birth Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Birthday:</span> <?= htmlspecialchars($userDetails['birth_date']); ?></div>
                        <div><span class="font-semibold">Birth Place:</span> <?= htmlspecialchars($userDetails['birth_place']); ?></div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- ✅ Residency -->
            <?php if ($userDetails['house_no'] || $userDetails['barangay']): ?>
                <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                    <?php sectionHeader("fa-house", "Residency"); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">House No:</span> <?= displayOrNA($userDetails['house_no']); ?></div>
                        <div><span class="font-semibold">Purok:</span> <?= displayOrNA($userDetails['purok']); ?></div>
                        <div><span class="font-semibold">Barangay:</span> <?= displayOrNA($userDetails['barangay']); ?></div>
                        <div><span class="font-semibold">Municipality:</span> <?= displayOrNA($userDetails['municipality']); ?></div>
                        <div><span class="font-semibold">Province:</span> <?= displayOrNA($userDetails['province']); ?></div>
                        <div><span class="font-semibold">Years of Residency:</span> <?= displayOrNA($userDetails['years_residency']); ?></div>
                        <div><span class="font-semibold">Household Head:</span> <?= displayOrNA($userDetails['household_head']); ?></div>
                        <div><span class="font-semibold">House Type:</span> <?= displayOrNA($userDetails['house_type']); ?></div>
                        <div><span class="font-semibold">Ownership:</span> <?= displayOrNA($userDetails['ownership_status']); ?></div>
                        <div><span class="font-semibold">Previous Address:</span> <?= displayOrNA($userDetails['previous_address']); ?></div>
                    </div>
                </section>

            <?php endif; ?>

            <!-- ✅ Family Info -->
            <?php if ($userDetails['fathers_name'] || $userDetails['mothers_name']): ?>
                <section class="bg-gray-50 p-6 rounded-xl shadow-sm mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Family Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Father's Name:</span> <?= displayOrNA($userDetails['fathers_name']); ?></div>
                        <div><span class="font-semibold">Father's Birthplace:</span> <?= displayOrNA($userDetails['fathers_birthplace']); ?></div>
                        <div><span class="font-semibold">Mother's Name:</span> <?= displayOrNA($userDetails['mothers_name']); ?></div>
                        <div><span class="font-semibold">Mother's Birthplace:</span> <?= displayOrNA($userDetails['mothers_birthplace']); ?></div>
                        <div><span class="font-semibold">Spouse:</span> <?= displayOrNA($userDetails['spouse_name']); ?></div>
                        <div><span class="font-semibold">Dependents:</span> <?= displayOrNA($userDetails['num_dependents']); ?></div>
                        <div><span class="font-semibold">Emergency Contact:</span> <?= displayOrNA($userDetails['contact_person']); ?></div>
                        <div><span class="font-semibold">Emergency No:</span> <?= displayOrNA($userDetails['emergency_contact_no']); ?></div>
                    </div>
                </section>

            <?php endif; ?>

            <!-- ✅ Health Info -->
            <?php if ($userDetails['health_condition'] || $userDetails['vaccination_status']): ?>
                <section class="bg-gray-50 p-6 rounded-xl shadow-sm mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Health Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Condition:</span> <?= displayOrNA($userDetails['health_condition']); ?></div>
                        <div><span class="font-semibold">Common Issues:</span> <?= displayOrNA($userDetails['common_health_issue']); ?></div>
                        <div><span class="font-semibold">Vaccination:</span> <?= displayOrNA($userDetails['vaccination_status']); ?></div>
                        <div><span class="font-semibold">Height:</span> <?= displayOrNA($userDetails['height_cm']); ?> <?= !empty($userDetails['height_cm']) ? 'cm' : '' ?></div>
                        <div><span class="font-semibold">Weight:</span> <?= displayOrNA($userDetails['weight_kg']); ?> <?= !empty($userDetails['weight_kg']) ? 'kg' : '' ?></div>
                        <div><span class="font-semibold">Last Checkup:</span> <?= displayOrNA($userDetails['last_medical_checkup']); ?></div>
                        <div class="sm:col-span-2"><span class="font-semibold">Remarks:</span> <?= displayOrNA($userDetails['health_remarks']); ?></div>
                    </div>
                </section>

            <?php endif; ?>

            <!-- ✅ Income Info -->
            <?php if ($userDetails['monthly_income'] || $userDetails['income_source']): ?>
                <section class="mb-10">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl shadow-sm">
                        <h2 class="text-2xl font-bold text-indigo-600 flex items-center gap-2 mb-6">
                            <i class="fas fa-coins text-yellow-500"></i> Income Information
                        </h2>
                        <div>
                            <p class="text-sm text-gray-500">Monthly Income</p>
                            <p class="font-semibold text-gray-800"><?= displayOrNA($userDetails['monthly_income']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Source</p>
                            <p class="font-semibold text-gray-800"><?= displayOrNA($userDetails['income_source']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Household Members</p>
                            <p class="font-semibold text-gray-800"><?= displayOrNA($userDetails['household_members']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Additional Sources</p>
                            <p class="font-semibold text-gray-800"><?= displayOrNA($userDetails['additional_income_sources']); ?></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-sm text-gray-500">Household Head Occupation</p>
                            <p class="font-semibold text-gray-800"><?= displayOrNA($userDetails['household_head_occupation']); ?></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-sm text-gray-500">Income Proof</p>
                            <?php if (!empty($userDetails['income_proof'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['income_proof']), '/'); ?>"
                                    class="w-48 h-auto rounded-lg shadow-md mt-2 border">
                            <?php else: ?>
                                <p class="text-gray-500 italic">No proof uploaded</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

            <?php endif; ?>

            <!-- ✅ Identity Docs -->
            <?php if ($userDetails['id_type'] || $userDetails['front_valid_id_path'] || $userDetails['back_valid_id_path'] || $userDetails['selfie_with_id']): ?>
                <section class="mb-10">

                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm mb-4">
                        <h2 class="text-2xl font-bold text-indigo-600 flex items-center gap-2 mb-6">
                            <i class="fas fa-id-card text-green-500"></i> Identity Documents
                        </h2>
                        <p class="text-sm text-gray-500">ID Type</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($userDetails['id_type'] ?? 'Not specified'); ?></p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <!-- Front ID -->
                        <div class="text-center bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                            <p class="font-semibold text-gray-700 mb-2">Front ID</p>
                            <?php if (!empty($userDetails['front_valid_id_path'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['front_valid_id_path']), '/'); ?>"
                                    class="mx-auto w-full max-w-xs h-auto rounded-lg border">
                            <?php else: ?>
                                <p class="text-gray-400 italic">Not uploaded</p>
                            <?php endif; ?>
                        </div>

                        <!-- Back ID -->
                        <div class="text-center bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                            <p class="font-semibold text-gray-700 mb-2">Back ID</p>
                            <?php if (!empty($userDetails['back_valid_id_path'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['back_valid_id_path']), '/'); ?>"
                                    class="mx-auto w-full max-w-xs h-auto rounded-lg border">
                            <?php else: ?>
                                <p class="text-gray-400 italic">Not uploaded</p>
                            <?php endif; ?>
                        </div>

                        <!-- Selfie -->
                        <div class="text-center bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                            <p class="font-semibold text-gray-700 mb-2">Selfie with ID</p>
                            <?php if (!empty($userDetails['selfie_with_id'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['selfie_with_id']), '/'); ?>"
                                    class="mx-auto w-full max-w-xs h-auto rounded-lg border">
                            <?php else: ?>
                                <p class="text-gray-400 italic">Not uploaded</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- ✅ Action Buttons -->
            <div class="flex justify-center mt-8 space-x-4">
                <a href="edit_pending_profile.php"
                    class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                </a>
                <button onclick="confirmLogout()"
                    class="px-6 py-3 bg-red-600  rounded text-white hover:bg-red-500 transition">
                    Logout
                </button>
            </div>

        <?php else: ?>
            <p class="text-red-500 text-center text-lg">No profile data found. Please update your profile.</p>
        <?php endif; ?>
    </div>
    </div>

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
</body>

</html>