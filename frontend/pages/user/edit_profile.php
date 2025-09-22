<?php
    session_start();
    include('../../../backend/config/db.php');
    require_once "../../../backend/auth/auth_check.php";

    // ✅ Redirect if not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

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
    <title>Edit Profile | Barangay Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="../../assets/images/Logo.webp" type="image/x-icon">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">
    <div class="bg-white shadow-lg rounded-2xl p-6 sm:p-10 w-full max-w-6xl">
        <h1 class="text-3xl font-bold mb-8 text-center text-indigo-600">Edit My Profile</h1>
        <form action="../../../backend/actions/update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-8">
            <!-- ✅ Personal Info -->
            <section>
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="f_name" value="<?= htmlspecialchars($data['details']['f_name'] ?? '') ?>" placeholder="First Name" class="border rounded p-2 w-full">
                    <input type="text" name="m_name" value="<?= htmlspecialchars($data['details']['m_name'] ?? '') ?>" placeholder="Middle Name" class="border rounded p-2 w-full">
                    <input type="text" name="l_name" value="<?= htmlspecialchars($data['details']['l_name'] ?? '') ?>" placeholder="Last Name" class="border rounded p-2 w-full">
                    <input type="text" name="ext_name" value="<?= htmlspecialchars($data['details']['ext_name'] ?? '') ?>" placeholder="Extension" class="border rounded p-2 w-full">
                    <input type="text" name="gender" value="<?= htmlspecialchars($data['details']['gender'] ?? '') ?>" placeholder="Gender" class="border rounded p-2 w-full">
                    <input type="text" name="contact_no" value="<?= htmlspecialchars($data['details']['contact_no'] ?? '') ?>" placeholder="Contact No." class="border rounded p-2 w-full">
                    <input type="text" name="civil_status" value="<?= htmlspecialchars($data['details']['civil_status'] ?? '') ?>" placeholder="Civil Status" class="border rounded p-2 w-full">
                    <input type="text" name="occupation" value="<?= htmlspecialchars($data['details']['occupation'] ?? '') ?>" placeholder="Occupation" class="border rounded p-2 w-full">
                    <input type="text" name="nationality" value="<?= htmlspecialchars($data['details']['nationality'] ?? '') ?>" placeholder="Nationality" class="border rounded p-2 w-full">
                    <input type="text" name="religion" value="<?= htmlspecialchars($data['details']['religion'] ?? '') ?>" placeholder="Religion" class="border rounded p-2 w-full">
                </div>
            </section>

            <!-- ✅ Birth Info -->
            <section>
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Birth Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="date" name="birthdate" value="<?= htmlspecialchars($data['birth']['birth_date'] ?? '') ?>" class="border rounded p-2 w-full">
                    <input type="text" name="birth_place" value="<?= htmlspecialchars($data['birth']['birth_place'] ?? '') ?>" placeholder="Birth Place" class="border rounded p-2 w-full">
                </div>
            </section>

            <!-- ✅ Residency -->
            <section>
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Residency</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="house_no" value="<?= htmlspecialchars($data['residency']['house_no'] ?? '') ?>" placeholder="House No." class="border rounded p-2 w-full">
                    <input type="text" name="purok" value="<?= htmlspecialchars($data['residency']['purok'] ?? '') ?>" placeholder="Purok" class="border rounded p-2 w-full">
                    <input type="text" name="barangay" value="<?= htmlspecialchars($data['residency']['barangay'] ?? '') ?>" placeholder="Barangay" class="border rounded p-2 w-full">
                    <input type="text" name="municipality" value="<?= htmlspecialchars($data['residency']['municipality'] ?? '') ?>" placeholder="Municipality" class="border rounded p-2 w-full">
                    <input type="text" name="province" value="<?= htmlspecialchars($data['residency']['province'] ?? '') ?>" placeholder="Province" class="border rounded p-2 w-full">
                    <input type="text" name="years_residency" value="<?= htmlspecialchars($data['residency']['years_residency'] ?? '') ?>" placeholder="Years of Residency" class="border rounded p-2 w-full">
                    <input type="text" name="household_head" value="<?= htmlspecialchars($data['residency']['household_head'] ?? '') ?>" placeholder="Household Head" class="border rounded p-2 w-full">
                    <input type="text" name="house_type" value="<?= htmlspecialchars($data['residency']['house_type'] ?? '') ?>" placeholder="House Type" class="border rounded p-2 w-full">
                    <input type="text" name="ownership_status" value="<?= htmlspecialchars($data['residency']['ownership_status'] ?? '') ?>" placeholder="Ownership" class="border rounded p-2 w-full">
                    <input type="text" name="previous_address" value="<?= htmlspecialchars($data['residency']['previous_address'] ?? '') ?>" placeholder="Previous Address" class="border rounded p-2 w-full">
                </div>
            </section>

            <!-- ✅ Family Info -->
            <section>
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Family Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="fathers_name" value="<?= htmlspecialchars($data['family']['fathers_name'] ?? '') ?>" placeholder="Father's Name" class="border rounded p-2 w-full">
                    <input type="text" name="fathers_birthplace" value="<?= htmlspecialchars($data['family']['fathers_birthplace'] ?? '') ?>" placeholder="Father's Birthplace" class="border rounded p-2 w-full">
                    <input type="text" name="mothers_name" value="<?= htmlspecialchars($data['family']['mothers_name'] ?? '') ?>" placeholder="Mother's Name" class="border rounded p-2 w-full">
                    <input type="text" name="mothers_birthplace" value="<?= htmlspecialchars($data['family']['mothers_birthplace'] ?? '') ?>" placeholder="Mother's Birthplace" class="border rounded p-2 w-full">
                    <input type="text" name="spouse_name" value="<?= htmlspecialchars($data['family']['spouse_name'] ?? '') ?>" placeholder="Spouse's Name" class="border rounded p-2 w-full">
                    <input type="number" name="num_dependents" value="<?= htmlspecialchars($data['family']['num_dependents'] ?? '') ?>" placeholder="Dependents" class="border rounded p-2 w-full">
                    <input type="text" name="contact_person" value="<?= htmlspecialchars($data['family']['contact_person'] ?? '') ?>" placeholder="Emergency Contact" class="border rounded p-2 w-full">
                    <input type="text" name="emergency_contact_no" value="<?= htmlspecialchars($data['family']['emergency_contact_no'] ?? '') ?>" placeholder="Emergency Contact No." class="border rounded p-2 w-full">
                </div>
            </section>

            <!-- ✅ Health Info -->
            <section>
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Health Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="health_condition" value="<?= htmlspecialchars($data['health']['health_condition'] ?? '') ?>" placeholder="Health Condition" class="border rounded p-2 w-full">
                    <input type="text" name="common_health_issue" value="<?= htmlspecialchars($data['health']['common_health_issue'] ?? '') ?>" placeholder="Common Issues" class="border rounded p-2 w-full">
                    <input type="text" name="vaccination_status" value="<?= htmlspecialchars($data['health']['vaccination_status'] ?? '') ?>" placeholder="Vaccination Status" class="border rounded p-2 w-full">
                    <input type="number" step="0.01" name="height_cm" value="<?= htmlspecialchars($data['health']['height_cm'] ?? '') ?>" placeholder="Height (cm)" class="border rounded p-2 w-full">
                    <input type="number" step="0.01" name="weight_kg" value="<?= htmlspecialchars($data['health']['weight_kg'] ?? '') ?>" placeholder="Weight (kg)" class="border rounded p-2 w-full">
                    <input type="date" name="last_medical_checkup" value="<?= htmlspecialchars($data['health']['last_medical_checkup'] ?? '') ?>" class="border rounded p-2 w-full">
                    <textarea name="health_remarks" placeholder="Remarks" class="border rounded p-2 w-full"><?= htmlspecialchars($data['health']['health_remarks'] ?? '') ?></textarea>
                </div>
            </section>

            <!-- ✅ Income Info -->
            <section>
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Income Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="number" name="monthly_income" value="<?= htmlspecialchars($data['income']['monthly_income'] ?? '') ?>" placeholder="Monthly Income" class="border rounded p-2 w-full">
                    <input type="text" name="income_source" value="<?= htmlspecialchars($data['income']['income_source'] ?? '') ?>" placeholder="Income Source" class="border rounded p-2 w-full">
                    <input type="number" name="household_members" value="<?= htmlspecialchars($data['income']['household_members'] ?? '') ?>" placeholder="Household Members" class="border rounded p-2 w-full">
                    <input type="text" name="additional_income_sources" value="<?= htmlspecialchars($data['income']['additional_income_sources'] ?? '') ?>" placeholder="Additional Income Sources" class="border rounded p-2 w-full">
                    <input type="text" name="household_head_occupation" value="<?= htmlspecialchars($data['income']['household_head_occupation'] ?? '') ?>" placeholder="Household Head Occupation" class="border rounded p-2 w-full">
                    <label class="block">Upload Income Proof:
                        <input type="file" name="income_proof" class="mt-2 border rounded p-2 w-full">
                    </label>
                </div>
            </section>

            <!-- ✅ Identity Docs -->
            <section>
                <h2 class="text-2xl font-semibold text-indigo-500 border-b pb-2 mb-4">Identity Documents</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label>Front ID:
                        <input type="file" name="front_valid_id_path" class="mt-2 border rounded p-2 w-full">
                    </label>
                    <label>Back ID:
                        <input type="file" name="back_valid_id_path" class="mt-2 border rounded p-2 w-full">
                    </label>
                    <label>Selfie with ID:
                        <input type="file" name="selfie_with_id" class="mt-2 border rounded p-2 w-full">
                    </label>
                </div>
            </section>

            <div class="flex justify-center mt-6 space-x-4">
                <button type="submit" class="px-6 py-2 bg-indigo-500 text-white rounded-lg shadow hover:bg-indigo-600">Save Changes</button>
                <a href="profile.php" class="px-6 py-2 bg-gray-300 text-black rounded-lg shadow hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
