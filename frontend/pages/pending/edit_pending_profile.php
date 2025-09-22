<?php
session_start();
require_once "../../../backend/config/db.php";
require_once "../../../backend/auth/login.php";
require_once "./user_pending_check.php";

// ✅ Always reset user status to Pending when opening this form
if (isset($_SESSION['user_id'])) {
    $updateStatus = $pdo->prepare("UPDATE users SET status = 'Pending' WHERE user_id = ?");
    $updateStatus->execute([$_SESSION['user_id']]);
    $_SESSION['status'] = "Pending"; // keep session in sync
}

$status   = $_SESSION['status'] ?? 'Pending';
$user_id  = $_SESSION['user_id'] ?? null;

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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Barangay Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="../../assets/images/Logo.webp" type="image/x-icon">
</head>

<body class="bg-gray-100 p-6">

    <div class="bg-white shadow-lg rounded-2xl p-6 sm:p-10 w-full max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center text-indigo-600">Edit My Profile</h1>

        <form action="./update_pending_profile.php" method="POST" enctype="multipart/form-data" class="space-y-8">

            <!-- ✅ Personal Info -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-semibold text-indigo-500 border-b pb-2 mb-4">Personal Information</h2>

                <!-- ✅ Profile Photo -->
                <div class="mb-4 flex flex-col items-center text-center">
                    <label class="block font-semibold mb-2">Profile Photo</label>
                    <?php if (!empty($userDetails['photo'])): ?>
                        <img src="<?= '/Barangay_Information_System/uploads/profile/' . basename($userDetails['photo']); ?>"
                            class="w-32 h-32 object-cover rounded-full shadow mb-2">
                        <input type="hidden" name="old_photo" value="<?= htmlspecialchars($userDetails['photo']); ?>">
                    <?php else: ?>
                        <p class="text-gray-500 italic mb-2">No profile photo uploaded</p>
                    <?php endif; ?>
                    <input type="file" name="photo" class="border p-2 rounded w-full sm:w-64">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="f_name" value="<?= htmlspecialchars($userDetails['f_name'] ?? '') ?>" placeholder="First Name" class="border p-2 rounded">
                    <input type="text" name="m_name" value="<?= htmlspecialchars($userDetails['m_name'] ?? '') ?>" placeholder="Middle Name" class="border p-2 rounded">
                    <input type="text" name="l_name" value="<?= htmlspecialchars($userDetails['l_name'] ?? '') ?>" placeholder="Last Name" class="border p-2 rounded">
                    <input type="text" name="ext_name" value="<?= htmlspecialchars($userDetails['ext_name'] ?? '') ?>" placeholder="Extension (e.g., Jr.)" class="border p-2 rounded">
                    <input type="text" name="gender" value="<?= htmlspecialchars($userDetails['gender'] ?? '') ?>" placeholder="Gender" class="border p-2 rounded">
                    <input type="text" name="contact_no" value="<?= htmlspecialchars($userDetails['contact_no'] ?? '') ?>" placeholder="Contact No" class="border p-2 rounded">
                    <input type="text" name="civil_status" value="<?= htmlspecialchars($userDetails['civil_status'] ?? '') ?>" placeholder="Civil Status" class="border p-2 rounded">
                    <input type="text" name="occupation" value="<?= htmlspecialchars($userDetails['occupation'] ?? '') ?>" placeholder="Occupation" class="border p-2 rounded">
                </div>
            </section>

            <!-- ✅ Birth Info -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-semibold text-indigo-500 border-b pb-2 mb-4">Birth Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="date" name="birth_date" value="<?= htmlspecialchars($userDetails['birth_date'] ?? '') ?>" class="border p-2 rounded">
                    <input type="text" name="birth_place" value="<?= htmlspecialchars($userDetails['birth_place'] ?? '') ?>" placeholder="Birth Place" class="border p-2 rounded">
                </div>
            </section>

            <!-- ✅ Residency -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-semibold text-indigo-500 border-b pb-2 mb-4">Residency</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="house_no" value="<?= htmlspecialchars($userDetails['house_no'] ?? '') ?>" placeholder="House No" class="border p-2 rounded">
                    <input type="text" name="purok" value="<?= htmlspecialchars($userDetails['purok'] ?? '') ?>" placeholder="Purok" class="border p-2 rounded">
                </div>
            </section>

            <!-- ✅ Family Info -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-semibold text-indigo-500 border-b pb-2 mb-4">Family Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="fathers_name" value="<?= htmlspecialchars($userDetails['fathers_name'] ?? '') ?>" placeholder="Father's Name" class="border p-2 rounded">
                    <input type="text" name="mothers_name" value="<?= htmlspecialchars($userDetails['mothers_name'] ?? '') ?>" placeholder="Mother's Name" class="border p-2 rounded">
                    <input type="text" name="spouse_name" value="<?= htmlspecialchars($userDetails['spouse_name'] ?? '') ?>" placeholder="Spouse Name" class="border p-2 rounded">
                </div>
            </section>

            <!-- ✅ Health Info -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-semibold text-indigo-500 border-b pb-2 mb-4">Health Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="health_condition" value="<?= htmlspecialchars($userDetails['health_condition'] ?? '') ?>" placeholder="Health Condition" class="border p-2 rounded">
                    <input type="text" name="common_health_issue" value="<?= htmlspecialchars($userDetails['common_health_issue'] ?? '') ?>" placeholder="Common Health Issues" class="border p-2 rounded">
                </div>
            </section>

            <!-- ✅ Income Info -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-semibold text-indigo-500 border-b pb-2 mb-4">Income Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="monthly_income" value="<?= htmlspecialchars($userDetails['monthly_income'] ?? '') ?>" placeholder="Monthly Income" class="border p-2 rounded">
                    <input type="text" name="income_source" value="<?= htmlspecialchars($userDetails['income_source'] ?? '') ?>" placeholder="Income Source" class="border p-2 rounded">
                </div>
                <div class="mt-4">
                    <label class="block font-semibold">Upload Income Proof</label>
                    <?php if (!empty($userDetails['income_proof'])): ?>
                        <img src="<?= '/Barangay_Information_System/uploads/income/' . basename($userDetails['income_proof']); ?>" class="w-40 h-auto rounded mb-2">
                        <input type="hidden" name="old_income_proof" value="<?= htmlspecialchars($userDetails['income_proof']); ?>">
                    <?php endif; ?>
                    <input type="file" name="income_proof" class="border p-2 rounded w-full">
                </div>
            </section>

            <!-- ✅ Identity Docs -->
            <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Identity Documents</h2>
                <div class="mb-4">
                    <label class="block mb-1 mt-2 font-medium text-sm">TYPE OF VALID ID *</label>
                    <select name="id_type" class="border rounded p-2 w-full" required>
                        <option value="">Select ID Type</option>
                        <option value="PhilHealth ID" <?= ($userDetails['id_type'] ?? '') === 'PhilHealth ID' ? 'selected' : ''; ?>>PhilHealth ID</option>
                        <option value="SSS ID" <?= ($userDetails['id_type'] ?? '') === 'SSS ID' ? 'selected' : ''; ?>>SSS ID</option>
                        <option value="TIN ID" <?= ($userDetails['id_type'] ?? '') === 'TIN ID' ? 'selected' : ''; ?>>TIN ID</option>
                        <option value="Driver's License" <?= ($userDetails['id_type'] ?? '') === "Driver's License" ? 'selected' : ''; ?>>Driver's License</option>
                        <option value="UMID" <?= ($userDetails['id_type'] ?? '') === 'UMID' ? 'selected' : ''; ?>>UMID</option>
                        <option value="Voter's ID" <?= ($userDetails['id_type'] ?? '') === "Voter's ID" ? 'selected' : ''; ?>>Voter's ID</option>
                        <option value="Postal ID" <?= ($userDetails['id_type'] ?? '') === 'Postal ID' ? 'selected' : ''; ?>>Postal ID</option>
                        <option value="National ID" <?= ($userDetails['id_type'] ?? '') === 'National ID' ? 'selected' : ''; ?>>National ID</option>
                        <option value="Student ID" <?= ($userDetails['id_type'] ?? '') === 'Student ID' ? 'selected' : ''; ?>>Student ID</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Front ID -->
                    <div>
                        <label class="block font-semibold">Front ID</label>
                        <?php if (!empty($userDetails['front_valid_id_path'])): ?>
                            <img src="<?= '/Barangay_Information_System/uploads/ids/front/' . basename($userDetails['front_valid_id_path']); ?>"
                                class="mx-auto w-full max-w-xs h-auto rounded shadow mb-2">
                            <input type="hidden" name="old_front_valid_id" value="<?= htmlspecialchars($userDetails['front_valid_id_path']); ?>">
                        <?php else: ?>
                            <p class="text-gray-500 italic mb-2">Not uploaded</p>
                        <?php endif; ?>
                        <input type="file" name="front_valid_id" class="border p-2 rounded w-full">
                    </div>

                    <!-- Back ID -->
                    <div>
                        <label class="block font-semibold">Back ID</label>
                        <?php if (!empty($userDetails['back_valid_id_path'])): ?>
                            <img src="<?= '/Barangay_Information_System/uploads/ids/back/' . basename($userDetails['back_valid_id_path']); ?>"
                                class="mx-auto w-full max-w-xs h-auto rounded shadow mb-2">
                            <input type="hidden" name="old_back_valid_id" value="<?= htmlspecialchars($userDetails['back_valid_id_path']); ?>">
                        <?php else: ?>
                            <p class="text-gray-500 italic mb-2">Not uploaded</p>
                        <?php endif; ?>
                        <input type="file" name="back_valid_id" class="border p-2 rounded w-full">
                    </div>

                    <!-- Selfie with ID -->
                    <div>
                        <label class="block font-semibold">Selfie with ID</label>
                        <?php if (!empty($userDetails['selfie_with_id'])): ?>
                            <img src="<?= '/Barangay_Information_System/uploads/ids/selfie/' . basename($userDetails['selfie_with_id']); ?>"
                                class="mx-auto w-full max-w-xs h-auto rounded shadow mb-2">
                            <input type="hidden" name="old_selfie_with_id" value="<?= htmlspecialchars($userDetails['selfie_with_id']); ?>">
                        <?php else: ?>
                            <p class="text-gray-500 italic mb-2">Not uploaded</p>
                        <?php endif; ?>
                        <input type="file" name="selfie_with_id" class="border p-2 rounded w-full">
                    </div>
                </div>
            </section>

            <!-- ✅ Buttons -->
            <div class="flex justify-center space-x-4 mt-8">
                <a href="pending.php" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">Save</button>
            </div>
        </form>
    </div>

</body>

</html>