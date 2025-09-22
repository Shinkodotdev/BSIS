<?php
session_start();
include('../../../backend/config/db.php');

// ✅ Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$status = $_SESSION['status'] ?? 'Pending'; 
$user_id = $_SESSION['user_id'];

// ✅ Fetch all profile-related data
$queries = [
    "details"   => "SELECT * FROM user_details WHERE user_id = :user_id LIMIT 1",
    "birth"     => "SELECT * FROM user_birthdates WHERE user_id = :user_id LIMIT 1",
    "identity"  => "SELECT * FROM user_identity_docs WHERE user_id = :user_id LIMIT 1",
    "residency" => "SELECT * FROM user_residency WHERE user_id = :user_id LIMIT 1",
    "family"    => "SELECT * FROM user_family_info WHERE user_id = :user_id LIMIT 1",
    "health"    => "SELECT * FROM user_health_info WHERE user_id = :user_id LIMIT 1",
    "income"    => "SELECT * FROM user_income_info WHERE user_id = :user_id LIMIT 1",
];

$data = [];
foreach ($queries as $key => $sql) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $data[$key] = $stmt->fetch();
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
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">
    <div class="bg-white shadow-lg rounded-2xl p-6 sm:p-10 w-full max-w-6xl">
        <h1 class="text-3xl font-bold mb-8 text-center text-indigo-600">My Profile</h1>
        <p>Please Check your information and wait for the email verification of the admin to be approved.</p>

        <?php if ($data['details']): ?>

            <!-- ✅ Personal Info -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><span class="font-semibold">First Name:</span> <?= htmlspecialchars($data['details']['f_name']); ?></div>
                    <div><span class="font-semibold">Middle Name:</span> <?= htmlspecialchars($data['details']['m_name'] ?? ''); ?></div>
                    <div><span class="font-semibold">Last Name:</span> <?= htmlspecialchars($data['details']['l_name']); ?></div>
                    <div><span class="font-semibold">Extension:</span> <?= htmlspecialchars($data['details']['ext_name'] ?? ''); ?></div>
                    <div><span class="font-semibold">Gender:</span> <?= htmlspecialchars($data['details']['gender']); ?></div>
                    <div><span class="font-semibold">Contact:</span> <?= htmlspecialchars($data['details']['contact_no']); ?></div>
                    <div><span class="font-semibold">Civil Status:</span> <?= htmlspecialchars($data['details']['civil_status']); ?></div>
                    <div><span class="font-semibold">Occupation:</span> <?= htmlspecialchars($data['details']['occupation']); ?></div>
                    <div><span class="font-semibold">Nationality:</span> <?= htmlspecialchars($data['details']['nationality']); ?></div>
                    <div><span class="font-semibold">Religion:</span> <?= htmlspecialchars($data['details']['religion']); ?></div>
                </div>
            </section>

            <!-- ✅ Birth Info -->
            <?php if ($data['birth']): ?>
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Birth Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Birthday:</span> <?= htmlspecialchars($data['birth']['birth_date']); ?></div>
                        <div><span class="font-semibold">Birth Place:</span> <?= htmlspecialchars($data['birth']['birth_place']); ?></div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- ✅ Residency -->
            <?php if ($data['residency']): ?>
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Residency</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">House No:</span> <?= htmlspecialchars($data['residency']['house_no']); ?></div>
                        <div><span class="font-semibold">Purok:</span> <?= htmlspecialchars($data['residency']['purok']); ?></div>
                        <div><span class="font-semibold">Barangay:</span> <?= htmlspecialchars($data['residency']['barangay']); ?></div>
                        <div><span class="font-semibold">Municipality:</span> <?= htmlspecialchars($data['residency']['municipality']); ?></div>
                        <div><span class="font-semibold">Province:</span> <?= htmlspecialchars($data['residency']['province']); ?></div>
                        <div><span class="font-semibold">Years of Residency:</span> <?= htmlspecialchars($data['residency']['years_residency']); ?></div>
                        <div><span class="font-semibold">Household Head:</span> <?= htmlspecialchars($data['residency']['household_head']); ?></div>
                        <div><span class="font-semibold">House Type:</span> <?= htmlspecialchars($data['residency']['house_type']); ?></div>
                        <div><span class="font-semibold">Ownership:</span> <?= htmlspecialchars($data['residency']['ownership_status']); ?></div>
                        <div><span class="font-semibold">Previous Address:</span> <?= htmlspecialchars($data['residency']['previous_address']); ?></div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- ✅ Family Info -->
            <?php if ($data['family']): ?>
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Family Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Father's Name:</span> <?= htmlspecialchars($data['family']['fathers_name']); ?></div>
                        <div><span class="font-semibold">Father's Birthplace:</span> <?= htmlspecialchars($data['family']['fathers_birthplace']); ?></div>
                        <div><span class="font-semibold">Mother's Name:</span> <?= htmlspecialchars($data['family']['mothers_name']); ?></div>
                        <div><span class="font-semibold">Mother's Birthplace:</span> <?= htmlspecialchars($data['family']['mothers_birthplace']); ?></div>
                        <div><span class="font-semibold">Spouse:</span> <?= htmlspecialchars($data['family']['spouse_name']); ?></div>
                        <div><span class="font-semibold">Dependents:</span> <?= htmlspecialchars($data['family']['num_dependents']); ?></div>
                        <div><span class="font-semibold">Emergency Contact:</span> <?= htmlspecialchars($data['family']['contact_person']); ?></div>
                        <div><span class="font-semibold">Emergency No:</span> <?= htmlspecialchars($data['family']['emergency_contact_no']); ?></div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- ✅ Health Info -->
            <?php if ($data['health']): ?>
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Health Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Condition:</span> <?= htmlspecialchars($data['health']['health_condition']); ?></div>
                        <div><span class="font-semibold">Common Issues:</span> <?= htmlspecialchars($data['health']['common_health_issue']); ?></div>
                        <div><span class="font-semibold">Vaccination:</span> <?= htmlspecialchars($data['health']['vaccination_status']); ?></div>
                        <div><span class="font-semibold">Height:</span> <?= htmlspecialchars($data['health']['height_cm']); ?> cm</div>
                        <div><span class="font-semibold">Weight:</span> <?= htmlspecialchars($data['health']['weight_kg']); ?> kg</div>
                        <div><span class="font-semibold">Last Checkup:</span> <?= htmlspecialchars($data['health']['last_medical_checkup']); ?></div>
                        <div class="sm:col-span-2"><span class="font-semibold">Remarks:</span> <?= htmlspecialchars($data['health']['health_remarks']); ?></div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- ✅ Income Info -->
            <?php if ($data['income']): ?>
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Income Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Monthly Income:</span> <?= htmlspecialchars($data['income']['monthly_income']); ?></div>
                        <div><span class="font-semibold">Source:</span> <?= htmlspecialchars($data['income']['income_source']); ?></div>
                        <div><span class="font-semibold">Household Members:</span> <?= htmlspecialchars($data['income']['household_members']); ?></div>
                        <div><span class="font-semibold">Additional Sources:</span> <?= htmlspecialchars($data['income']['additional_income_sources']); ?></div>
                        <div><span class="font-semibold">Household Head Occupation:</span> <?= htmlspecialchars($data['income']['household_head_occupation']); ?></div>
                        <div class="sm:col-span-2">
                            <span class="font-semibold">Income Proof:</span><br>
                            <?php if (!empty($data['income']['income_proof'])): ?>
                                <img src="<?= '/Barangay_Information_System/uploads/income_proof/' . basename($data['income']['income_proof']); ?>"
                                    class="w-full max-w-md h-auto rounded shadow-md mt-2">
                            <?php else: ?>
                                <span class="text-gray-500 italic">No proof uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- ✅ Identity Docs -->
            <?php if ($data['identity']): ?>
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Identity Documents</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div>
                            <span class="font-semibold">Front ID</span><br>
                            <?php if (!empty($data['identity']['front_valid_id_path'])): ?>
                                <img src="<?= '/Barangay_Information_System/uploads/front_id/' . basename($data['identity']['front_valid_id_path']); ?>"
                                    class="mx-auto w-full max-w-xs h-auto rounded shadow">
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="font-semibold">Back ID</span><br>
                            <?php if (!empty($data['identity']['back_valid_id_path'])): ?>
                                <img src="<?= '/Barangay_Information_System/uploads/back_id/' . basename($data['identity']['back_valid_id_path']); ?>"
                                    class="mx-auto w-full max-w-xs h-auto rounded shadow">
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="font-semibold">Selfie</span><br>
                            <?php if (!empty($data['identity']['selfie_with_id'])): ?>
                                <img src="<?= '/Barangay_Information_System/uploads/selfie_id/' . basename($data['identity']['selfie_with_id']); ?>"
                                    class="mx-auto w-full max-w-xs h-auto rounded shadow">
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-red-500 text-center">No profile data found. Please update your profile.</p>
        <?php endif; ?>

        <div class="flex justify-center mt-6 space-x-4">
            <a href="edit_profile.php" class="px-6 py-2 bg-indigo-500 text-white rounded-lg shadow hover:bg-indigo-600">Edit Profile</a>
        </div>
    </div>


</body>

</html>