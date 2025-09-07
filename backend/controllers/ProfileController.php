<?php
class ProfileController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function recordExists($table, $user_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $table WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function saveProfile($user_id, $postData, $files) {
        try {
            $this->pdo->beginTransaction();

            // ✅ Handle file uploads
            $uploadDir = __DIR__ . "/../../uploads/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            function uploadFile($file, $uploadDir) {
                if (!empty($file['name'])) {
                    $filename = time() . "_" . basename($file['name']);
                    $path = $uploadDir . $filename;
                    if (move_uploaded_file($file['tmp_name'], $path)) {
                        return "../uploads/" . $filename;
                    }
                }
                return null;
            }

            $photo          = uploadFile($files['photo'] ?? [], $uploadDir);
            $front_valid_id = uploadFile($files['front_valid_id_path'] ?? [], $uploadDir);
            $back_valid_id  = uploadFile($files['back_valid_id_path'] ?? [], $uploadDir);
            $selfie_with_id = uploadFile($files['selfie_with_id'] ?? [], $uploadDir);
            $income_proof   = uploadFile($files['income_proof'] ?? [], $uploadDir);

            // ✅ 1. user_details
            if ($this->recordExists("user_details", $user_id)) {
                $stmt = $this->pdo->prepare("
                    UPDATE user_details 
                    SET f_name=?, m_name=?, l_name=?, ext_name=?, gender=?, photo=?, contact_no=?,
                        civil_status=?, occupation=?, nationality=?, voter_status=?, religion=?, blood_type=?, educational_attainment=?
                    WHERE user_id=?
                ");
                $stmt->execute([
                    strtoupper($postData['f_name']), strtoupper($postData['m_name']), strtoupper($postData['l_name']),
                    $postData['ext_name'], $postData['gender'], $photo, $postData['contact_no'],
                    $postData['civil_status'], $postData['occupation'], $postData['nationality'], $postData['voter_status'],
                    $postData['religion'], $postData['blood_type'], $postData['educational_attainment'], $user_id
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_details 
                        (user_id, f_name, m_name, l_name, ext_name, gender, photo, contact_no,
                        civil_status, occupation, nationality, voter_status, religion, blood_type, educational_attainment)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, strtoupper($postData['f_name']), strtoupper($postData['m_name']), strtoupper($postData['l_name']),
                    $postData['ext_name'], $postData['gender'], $photo, $postData['contact_no'],
                    $postData['civil_status'], $postData['occupation'], $postData['nationality'], $postData['voter_status'],
                    $postData['religion'], $postData['blood_type'], $postData['educational_attainment']
                ]);
            }

            // ✅ 2. user_birthdates
            if ($this->recordExists("user_birthdates", $user_id)) {
                $stmt = $this->pdo->prepare("
                    UPDATE user_birthdates 
                    SET birth_date=?, birth_place=? 
                    WHERE user_id=?
                ");
                $stmt->execute([
                    $postData['birthdate'], strtoupper($postData['birth_place']), $user_id
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_birthdates (user_id, birth_date, birth_place)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, $postData['birthdate'], strtoupper($postData['birth_place'])
                ]);
            }

            // ✅ 3. user_identity_docs
            if ($this->recordExists("user_identity_docs", $user_id)) {
                $stmt = $this->pdo->prepare("
                    UPDATE user_identity_docs 
                    SET id_type=?, front_valid_id_path=?, back_valid_id_path=?, selfie_with_id=?
                    WHERE user_id=?
                ");
                $stmt->execute([
                    $postData['id_type'], $front_valid_id, $back_valid_id, $selfie_with_id, $user_id
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_identity_docs (user_id, id_type, front_valid_id_path, back_valid_id_path, selfie_with_id)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, $postData['id_type'], $front_valid_id, $back_valid_id, $selfie_with_id
                ]);
            }

            // ✅ 4. user_residency
            if ($this->recordExists("user_residency", $user_id)) {
                $stmt = $this->pdo->prepare("
                    UPDATE user_residency 
                    SET house_no=?, purok=?, barangay=?, municipality=?, province=?, years_residency=?, 
                        household_head=?, house_type=?, ownership_status=?, previous_address=? 
                    WHERE user_id=?
                ");
                $stmt->execute([
                    strtoupper($postData['house_no']), strtoupper($postData['purok']), $postData['barangay'],
                    $postData['municipality'], $postData['province'], $postData['years_residency'],
                    $postData['household_head'], $postData['house_type'], $postData['ownership_status'],
                    strtoupper($postData['previous_address']), $user_id
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_residency 
                        (user_id, house_no, purok, barangay, municipality, province, years_residency, 
                        household_head, house_type, ownership_status, previous_address)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, strtoupper($postData['house_no']), strtoupper($postData['purok']), $postData['barangay'],
                    $postData['municipality'], $postData['province'], $postData['years_residency'],
                    $postData['household_head'], $postData['house_type'], $postData['ownership_status'],
                    strtoupper($postData['previous_address'])
                ]);
            }

            // ✅ 5. user_family_info
            if ($this->recordExists("user_family_info", $user_id)) {
                $stmt = $this->pdo->prepare("
                    UPDATE user_family_info 
                    SET fathers_name=?, fathers_birthplace=?, mothers_name=?, mothers_birthplace=?, 
                        spouse_name=?, num_dependents=?, contact_person=?, emergency_contact_no=? 
                    WHERE user_id=?
                ");
                $stmt->execute([
                    strtoupper($postData['fathers_name']), strtoupper($postData['fathers_birthplace']),
                    strtoupper($postData['mothers_name']), strtoupper($postData['mothers_birthplace']),
                    strtoupper($postData['spouse_name']), $postData['num_dependents'],
                    strtoupper($postData['contact_person']), $postData['emergency_contact_no'], $user_id
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_family_info 
                        (user_id, fathers_name, fathers_birthplace, mothers_name, mothers_birthplace, 
                        spouse_name, num_dependents, contact_person, emergency_contact_no)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, strtoupper($postData['fathers_name']), strtoupper($postData['fathers_birthplace']),
                    strtoupper($postData['mothers_name']), strtoupper($postData['mothers_birthplace']),
                    strtoupper($postData['spouse_name']), $postData['num_dependents'],
                    strtoupper($postData['contact_person']), $postData['emergency_contact_no']
                ]);
            }

            // ✅ 6. user_health_info
            if ($this->recordExists("user_health_info", $user_id)) {
                $stmt = $this->pdo->prepare("
                    UPDATE user_health_info 
                    SET health_condition=?, common_health_issue=?, vaccination_status=?, height_cm=?, 
                        weight_kg=?, last_medical_checkup=?, health_remarks=? 
                    WHERE user_id=?
                ");
                $stmt->execute([
                    $postData['health_condition'], $postData['common_health_issue'], $postData['vaccination_status'],
                    $postData['height_cm'], $postData['weight_kg'], $postData['last_medical_checkup'],
                    strtoupper($postData['health_remarks']), $user_id
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_health_info 
                        (user_id, health_condition, common_health_issue, vaccination_status, height_cm, 
                        weight_kg, last_medical_checkup, health_remarks)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, $postData['health_condition'], $postData['common_health_issue'], $postData['vaccination_status'],
                    $postData['height_cm'], $postData['weight_kg'], $postData['last_medical_checkup'],
                    strtoupper($postData['health_remarks'])
                ]);
            }

            // ✅ 7. user_income_info
            if ($this->recordExists("user_income_info", $user_id)) {
                $stmt = $this->pdo->prepare("
                    UPDATE user_income_info 
                    SET monthly_income=?, income_source=?, household_members=?, additional_income_sources=?, 
                        household_head_occupation=?, income_proof=? 
                    WHERE user_id=?
                ");
                $stmt->execute([
                    $postData['monthly_income'], $postData['income_source'], $postData['household_members'],
                    strtoupper($postData['additional_income_sources']), strtoupper($postData['household_head_occupation']),
                    $income_proof, $user_id
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_income_info 
                        (user_id, monthly_income, income_source, household_members, additional_income_sources, 
                        household_head_occupation, income_proof)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id, $postData['monthly_income'], $postData['income_source'], $postData['household_members'],
                    strtoupper($postData['additional_income_sources']), strtoupper($postData['household_head_occupation']),
                    $income_proof
                ]);
            }

            $this->pdo->commit();
            return ['status' => 'success', 'message' => 'Profile saved successfully!'];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
