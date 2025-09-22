<?php
session_start();
require_once "../../../backend/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // --- Handle File Upload ---
        $photoPath = null;
        if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "../../../uploads/profile/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . "_" . basename($_FILES['photo']['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photoPath = "../uploads/profile/" . $fileName;
            } else {
                throw new Exception("Failed to upload profile photo.");
            }
        }
        // --- Handle Identity Docs Upload ---
        $frontIDPath = null;
        $backIDPath = null;
        $selfieIDPath = null;

        $baseDir = "../../../uploads/ids/";

// Front ID
$frontDir = $baseDir . "front/";
if (!is_dir($frontDir)) mkdir($frontDir, 0777, true);
if (!empty($_FILES['front_valid_id_path']['name']) && $_FILES['front_valid_id_path']['error'] === UPLOAD_ERR_OK) {
    $fileName = time() . "_front_" . basename($_FILES['front_valid_id_path']['name']);
    $targetFile = $frontDir . $fileName;
    if (move_uploaded_file($_FILES['front_valid_id_path']['tmp_name'], $targetFile)) {
        $frontIDPath = "../uploads/ids/front/" . $fileName;
    }
}

// Back ID
$backDir = $baseDir . "back/";
if (!is_dir($backDir)) mkdir($backDir, 0777, true);
if (!empty($_FILES['back_valid_id_path']['name']) && $_FILES['back_valid_id_path']['error'] === UPLOAD_ERR_OK) {
    $fileName = time() . "_back_" . basename($_FILES['back_valid_id_path']['name']);
    $targetFile = $backDir . $fileName;
    if (move_uploaded_file($_FILES['back_valid_id_path']['tmp_name'], $targetFile)) {
        $backIDPath = "../uploads/ids/back/" . $fileName;
    }
}

// Selfie with ID
$selfieDir = $baseDir . "selfie/";
if (!is_dir($selfieDir)) mkdir($selfieDir, 0777, true);
if (!empty($_FILES['selfie_with_id']['name']) && $_FILES['selfie_with_id']['error'] === UPLOAD_ERR_OK) {
    $fileName = time() . "_selfie_" . basename($_FILES['selfie_with_id']['name']);
    $targetFile = $selfieDir . $fileName;
    if (move_uploaded_file($_FILES['selfie_with_id']['tmp_name'], $targetFile)) {
        $selfieIDPath = "../uploads/ids/selfie/" . $fileName;
    }
}
// --- Handle Income Proof Upload ---
$incomeProofPath = null;
$incomeProofDir = "../../../uploads/income/";

if (!is_dir($incomeProofDir)) {
    mkdir($incomeProofDir, 0777, true);
}

if (!empty($_FILES['income_proof']['name']) && $_FILES['income_proof']['error'] === UPLOAD_ERR_OK) {
    $fileName = time() . "_income_" . basename($_FILES['income_proof']['name']);
    $targetFile = $incomeProofDir . $fileName;

    if (move_uploaded_file($_FILES['income_proof']['tmp_name'], $targetFile)) {
        $incomeProofPath = "../uploads/income/" . $fileName;
    }
}



        // 1. Update users
        $sqlUsers = "UPDATE users 
                 SET email = :email, updated_at = NOW() 
                 WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sqlUsers);
        $stmt->execute([
            ':email' => $_POST['email'],
            ':user_id' => $user_id
        ]);

        // 2. Update user_details
        $sqlDetails = "UPDATE user_details SET
        f_name = :f_name,
        m_name = :m_name,
        l_name = :l_name,
        ext_name = :ext_name,
        gender = :gender,
        contact_no = :contact_no,
        civil_status = :civil_status,
        occupation = :occupation,
        nationality = :nationality,
        voter_status = :voter_status,
        pwd_status = :pwd_status,
        senior_citizen_status = :senior_citizen_status,
        religion = :religion,
        blood_type = :blood_type,
        educational_attainment = :educational_attainment,
        photo = COALESCE(:photo, photo),
        updated_at = NOW()
        WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sqlDetails);
        $stmt->execute([
            ':f_name' => $_POST['f_name'],
            ':m_name' => $_POST['m_name'],
            ':l_name' => $_POST['l_name'],
            ':ext_name' => $_POST['ext_name'],
            ':gender' => $_POST['gender'],
            ':contact_no' => $_POST['contact_no'],
            ':civil_status' => $_POST['civil_status'],
            ':occupation' => $_POST['occupation'],
            ':nationality' => $_POST['nationality'],
            ':voter_status' => $_POST['voter_status'],
            ':pwd_status' => $_POST['pwd_status'],
            ':senior_citizen_status' => $_POST['senior_citizen_status'],
            ':religion' => $_POST['religion'],
            ':blood_type' => $_POST['blood_type'],
            ':educational_attainment' => $_POST['educational_attainment'],
            ':photo' => $photoPath ?: null,
            ':user_id' => $user_id
        ]);


        // 3. Update user_birthdates
        $sqlBirth = "UPDATE user_birthdates 
                 SET birth_date = :birth_date, birth_place = :birth_place, updated_at = NOW()
                 WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sqlBirth);
        $stmt->execute([
            ':birth_date' => $_POST['birth_date'],
            ':birth_place' => $_POST['birth_place'],
            ':user_id' => $user_id
        ]);

        // 4. Update user_residency
        $sqlRes = "UPDATE user_residency SET
        house_no = :house_no,
        purok = :purok,
        barangay = :barangay,
        municipality = :municipality,
        province = :province,
        updated_at = NOW()
        WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sqlRes);
        $stmt->execute([
            ':house_no' => $_POST['house_no'],
            ':purok' => $_POST['purok'],
            ':barangay' => $_POST['barangay'],
            ':municipality' => $_POST['municipality'],
            ':province' => $_POST['province'],
            ':user_id' => $user_id
        ]);

        // 5. Update user_family_info
        $sqlFam = "UPDATE user_family_info SET
        fathers_name = :fathers_name,
        fathers_birthplace = :fathers_birthplace,
        mothers_name = :mothers_name,
        mothers_birthplace = :mothers_birthplace,
        spouse_name = :spouse_name,
        num_dependents = :num_dependents,
        contact_person = :contact_person,
        emergency_contact_no = :emergency_contact_no,
        updated_at = NOW()
        WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sqlFam);
        $stmt->execute([
            ':fathers_name' => $_POST['fathers_name'],
            ':fathers_birthplace' => $_POST['fathers_birthplace'],
            ':mothers_name' => $_POST['mothers_name'],
            ':mothers_birthplace' => $_POST['mothers_birthplace'],
            ':spouse_name' => $_POST['spouse_name'],
            ':num_dependents' => $_POST['num_dependents'],
            ':contact_person' => $_POST['contact_person'],
            ':emergency_contact_no' => $_POST['emergency_contact_no'],
            ':user_id' => $user_id
        ]);

        // 6. Update user_health_info
        $sqlHealth = "UPDATE user_health_info SET
        health_condition = :health_condition,
        common_health_issue = :common_health_issue,
        vaccination_status = :vaccination_status,
        height_cm = :height_cm,
        weight_kg = :weight_kg,
        last_medical_checkup = :last_medical_checkup,
        health_remarks = :health_remarks,
        updated_at = NOW()
        WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sqlHealth);
        $stmt->execute([
            ':health_condition' => $_POST['health_condition'],
            ':common_health_issue' => $_POST['common_health_issue'],
            ':vaccination_status' => $_POST['vaccination_status'],
            ':height_cm' => $_POST['height_cm'],
            ':weight_kg' => $_POST['weight_kg'],
            ':last_medical_checkup' => $_POST['last_medical_checkup'],
            ':health_remarks' => $_POST['health_remarks'],
            ':user_id' => $user_id
        ]);
        // 7. Update user_identity_docs
        $sqlIdentity = "UPDATE user_identity_docs SET
    id_type = :id_type,
    front_valid_id_path = COALESCE(:front_valid_id_path, front_valid_id_path),
    back_valid_id_path = COALESCE(:back_valid_id_path, back_valid_id_path),
    selfie_with_id = COALESCE(:selfie_with_id, selfie_with_id),
    updated_at = NOW()
    WHERE user_id = :user_id";

        $stmt = $pdo->prepare($sqlIdentity);
        $stmt->execute([
            ':id_type' => $_POST['id_type'],
            ':front_valid_id_path' => $frontIDPath ?: null,
            ':back_valid_id_path' => $backIDPath ?: null,
            ':selfie_with_id' => $selfieIDPath ?: null,
            ':user_id' => $user_id
        ]);
// 8. Update user_income_info
$sqlIncome = "UPDATE user_income_info SET
    monthly_income = :monthly_income,
    income_source = :income_source,
    household_head_occupation = :household_head_occupation,
    household_members = :household_members,
    additional_income_sources = :additional_income_sources,
    income_proof = COALESCE(:income_proof, income_proof),
    updated_at = NOW()
    WHERE user_id = :user_id";

$stmt = $pdo->prepare($sqlIncome);
$stmt->execute([
    ':monthly_income' => $_POST['monthly_income'],
    ':income_source' => $_POST['income_source'],
    ':household_head_occupation' => $_POST['household_head_occupation'],
    ':household_members' => $_POST['household_members'],
    ':additional_income_sources' => $_POST['additional_income_sources'],
    ':income_proof' => $incomeProofPath ?: null,
    ':user_id' => $user_id
]);

        $pdo->commit();

        echo "<!DOCTYPE html>
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: 'Profile Updated!',
    text: 'Your profile information was successfully saved.',
    confirmButtonColor: '#4F46E5'
}).then(() => {
    window.location.href = 'admin-profile.php';
});
</script>
</body>
</html>";
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: '" . addslashes($e->getMessage()) . "',
            confirmButtonColor: '#EF4444'
        }).then(() => {
            window.history.back();
        });
    </script>";
        exit();
    }
}
