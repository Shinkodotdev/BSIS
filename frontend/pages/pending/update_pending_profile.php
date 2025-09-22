<?php
session_start();
require_once "../../../backend/config/db.php";
require_once "../../../backend/auth/login.php";
require_once "./user_pending_check.php";
$status = $_SESSION['status'] ?? 'Pending';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Helper function to safely get POST values
function getPost($key)
{
    return $_POST[$key] ?? null;
}

// ✅ Handle File Uploads (keep old if not re-uploaded)
function handleFileUpload($fileKey, $oldPath, $subFolder)
{
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
        $targetDir = "../../../uploads/$subFolder/";
        $newFileName = uniqid() . "." . $ext;
        $targetPath = $targetDir . $newFileName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath);
        return $targetPath;
    }
    return $oldPath; // keep old if no new upload
}

// ✅ DETAILS
$stmt = $pdo->prepare("UPDATE user_details SET 
    f_name = ?, m_name = ?, l_name = ?, ext_name = ?, gender = ?, 
    contact_no = ?, civil_status = ?, occupation = ?, 
    nationality = ?, religion = ?
    WHERE user_id = ?");
$stmt->execute([
    getPost('f_name'),
    getPost('m_name'),
    getPost('l_name'),
    getPost('ext_name'),
    getPost('gender'),
    getPost('contact_no'),
    getPost('civil_status'),
    getPost('occupation'),
    getPost('nationality'),
    getPost('religion'),
    $user_id
]);

// ✅ BIRTH
$stmt = $pdo->prepare("UPDATE user_birthdates SET 
    birth_date = ?, birth_place = ?
    WHERE user_id = ?");
$stmt->execute([
    getPost('birth_date'),
    getPost('birth_place'),
    $user_id
]);

// ✅ RESIDENCY
$stmt = $pdo->prepare("UPDATE user_residency SET 
    house_no = ?, purok = ?, barangay = ?, municipality = ?, province = ?, 
    years_residency = ?, household_head = ?, house_type = ?, ownership_status = ?, previous_address = ?
    WHERE user_id = ?");
$stmt->execute([
    getPost('house_no'),
    getPost('purok'),
    getPost('barangay'),
    getPost('municipality'),
    getPost('province'),
    getPost('years_residency'),
    getPost('household_head'),
    getPost('house_type'),
    getPost('ownership_status'),
    getPost('previous_address'),
    $user_id
]);

// ✅ FAMILY
$stmt = $pdo->prepare("UPDATE user_family_info SET 
    fathers_name = ?, fathers_birthplace = ?, mothers_name = ?, mothers_birthplace = ?, 
    spouse_name = ?, num_dependents = ?, contact_person = ?, emergency_contact_no = ?
    WHERE user_id = ?");
$stmt->execute([
    getPost('fathers_name'),
    getPost('fathers_birthplace'),
    getPost('mothers_name'),
    getPost('mothers_birthplace'),
    getPost('spouse_name'),
    getPost('num_dependents'),
    getPost('contact_person'),
    getPost('emergency_contact_no'),
    $user_id
]);

// ✅ HEALTH
$stmt = $pdo->prepare("UPDATE user_health_info SET 
    health_condition = ?, common_health_issue = ?, vaccination_status = ?, height_cm = ?, weight_kg = ?, 
    last_medical_checkup = ?, health_remarks = ?
    WHERE user_id = ?");
$stmt->execute([
    getPost('health_condition'),
    getPost('common_health_issue'),
    getPost('vaccination_status'),
    getPost('height_cm'),
    getPost('weight_kg'),
    getPost('last_medical_checkup'),
    getPost('health_remarks'),
    $user_id
]);

// ✅ INCOME
// ✅ INCOME
$stmt = $pdo->prepare("UPDATE user_income_info SET 
    monthly_income = ?, income_source = ?, household_members = ?, additional_income_sources = ?, household_head_occupation = ?, income_proof = ?
    WHERE user_id = ?");
$stmt->execute([
    getPost('monthly_income'),
    getPost('income_source'),
    getPost('household_members'),
    getPost('additional_income_sources'),
    getPost('household_head_occupation'),
    handleFileUpload('income_proof', getPost('old_income_proof'), 'income'),
    $user_id
]);

// ✅ IDENTITY DOCUMENTS
$stmt = $pdo->prepare("UPDATE user_identity_docs SET 
    id_type = ?, front_valid_id_path = ?, back_valid_id_path = ?, selfie_with_id = ?
    WHERE user_id = ?");
$stmt->execute([
    getPost('id_type'),
    handleFileUpload('front_valid_id', getPost('old_front_valid_id'), 'ids/front'),
    handleFileUpload('back_valid_id', getPost('old_back_valid_id'), 'ids/back'),
    handleFileUpload('selfie_with_id', getPost('old_selfie_with_id'), 'ids/selfie'),
    $user_id
]);

// After update is successful
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
    icon: "success",
    title: "Profile updated successfully!",
    confirmButtonText: "OK"
}).then(() => {
    window.location.href = "pending.php";
});
</script>
</body>
</html>
';
exit;
