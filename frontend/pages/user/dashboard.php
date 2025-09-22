<?php
session_start();
require_once "../../../backend/config/db.php";
require_once "../../../backend/auth/login.php";
require_once "../../../backend/auth/auth_check.php";
require_once "./user_check.php";
// ✅ If all checks passed → fetch user details
$status = $_SESSION['status'] ?? 'Verified'; 
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
?>
<!DOCTYPE html>
<html lang="en">
<?php include('./user_head.php'); ?>

<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <?php include('../../components/DashNav.php'); ?>
    <!-- Main Content -->
    <div class="min-h-screen pt-16 px-4 sm:px-6 lg:px-8">
        <main id="main-content" class="w-full pb-24">
            <!-- Profile Form -->
            <form action="../../../backend/actions/save_profile.php" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3  justify-items-center">

                <!-- 1. User Details -->
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition duration-300 w-full max-w-md">
                    <h2 class="text-MD font-bold mb-4">USER DETAILS</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="f_name" value="<?php echo strtoupper($userDetails['f_name'] ?? ''); ?>" placeholder="First Name *" class="border rounded-lg p-3 w-full uppercase" required>
                        <input type="text" name="m_name" value="<?php echo strtoupper($userDetails['m_name'] ?? ''); ?>" placeholder="Middle Name" class="border rounded-lg p-3 w-full uppercase">
                        <input type="text" name="l_name" value="<?php echo strtoupper($userDetails['l_name'] ?? ''); ?>" placeholder="Last Name *" class="border rounded-lg p-3 w-full uppercase" required>
                        <select name="ext_name" class="border rounded-lg p-3 w-full uppercase">
                            <option value="">Ext. Name</option>
                            <option value="Jr" <?php echo strtoupper($userDetails['ext_name'] ?? '') === 'JR' ? 'selected' : ''; ?>>Jr.</option>
                            <option value="Sr" <?php echo strtoupper($userDetails['ext_name'] ?? '') === 'SR' ? 'selected' : ''; ?>>Sr.</option>
                            <option value="II" <?php echo strtoupper($userDetails['ext_name'] ?? '') === 'II' ? 'selected' : ''; ?>>II</option>
                            <option value="III" <?php echo strtoupper($userDetails['ext_name'] ?? '') === 'III' ? 'selected' : ''; ?>>III</option>
                            <option value="IV" <?php echo strtoupper($userDetails['ext_name'] ?? '') === 'IV' ? 'selected' : ''; ?>>IV</option>
                        </select>
                        <select name="gender" class="border rounded-lg p-3 w-full" required>
                            <option value="">SELECT GENDER *</option>
                            <option value="MALE" <?php echo ($userDetails['gender'] ?? '') === 'MALE' ? 'selected' : ''; ?>>MALE</option>
                            <option value="FEMALE" <?php echo ($userDetails['gender'] ?? '') === 'FEMALE' ? 'selected' : ''; ?>>FEMALE</option>
                            <option value="LGBTQ" <?php echo ($userDetails['gender'] ?? '') === 'LGBTQ' ? 'selected' : ''; ?>>LGBTQ</option>
                        </select>
                        <select id="civil_status" name="civil_status" class="border rounded-lg p-3 w-full uppercase" required>
                            <option value="">CIVIL STATUS *</option>
                            <option value="Single" <?php echo ($userDetails['civil_status'] ?? '') === 'Single' ? 'selected' : ''; ?>>Single</option>
                            <option value="Married" <?php echo ($userDetails['civil_status'] ?? '') === 'Married' ? 'selected' : ''; ?>>Married</option>
                            <option value="Widowed" <?php echo ($userDetails['civil_status'] ?? '') === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                            <option value="Separated" <?php echo ($userDetails['civil_status'] ?? '') === 'Separated' ? 'selected' : ''; ?>>Separated</option>
                        </select>
                        <select name="occupation" class="border rounded-lg p-3 w-full uppercase" required>
                            <option value="">OCUPATION *</option>
                            <option value="Student" <?php echo ($userDetails['occupation'] ?? '') === 'Student' ? 'selected' : ''; ?>>Student</option>
                            <option value="Farmer" <?php echo ($userDetails['occupation'] ?? '') === 'Farmer' ? 'selected' : ''; ?>>Farmer</option>
                            <option value="Teacher" <?php echo ($userDetails['occupation'] ?? '') === 'Teacher' ? 'selected' : ''; ?>>Teacher</option>
                            <option value="Government Employee" <?php echo ($userDetails['occupation'] ?? '') === 'Government Employee' ? 'selected' : ''; ?>>Government Employee</option>
                            <option value="Private Employee" <?php echo ($userDetails['occupation'] ?? '') === 'Private Employee' ? 'selected' : ''; ?>>Private Employee</option>
                            <option value="Business Owner" <?php echo ($userDetails['occupation'] ?? '') === 'Business Owner' ? 'selected' : ''; ?>>Business Owner</option>
                            <option value="Unemployed" <?php echo ($userDetails['occupation'] ?? '') === 'Unemployed' ? 'selected' : ''; ?>>Unemployed</option>
                        </select>
                        <select name="nationality" class="border rounded-lg p-3 w-full uppercase" required>
                            <option value="">NATIONALITY *</option>
                            <option value="Filipino" <?php echo ($userDetails['nationality'] ?? '') === 'Filipino' ? 'selected' : ''; ?>>Filipino</option>
                            <option value="American" <?php echo ($userDetails['nationality'] ?? '') === 'American' ? 'selected' : ''; ?>>American</option>
                            <option value="Chinese" <?php echo ($userDetails['nationality'] ?? '') === 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
                            <option value="Japanese" <?php echo ($userDetails['nationality'] ?? '') === 'Japanese' ? 'selected' : ''; ?>>Japanese</option>
                            <option value="Korean" <?php echo ($userDetails['nationality'] ?? '') === 'Korean' ? 'selected' : ''; ?>>Korean</option>
                            <option value="Indian" <?php echo ($userDetails['nationality'] ?? '') === 'Indian' ? 'selected' : ''; ?>>Indian</option>
                        </select>
                        <select name="religion" class="border rounded-lg p-3 w-full uppercase" required>
                            <option value="">RELIGION *</option>
                            <option value="Roman Catholic" <?php echo ($userDetails['religion'] ?? '') === 'Roman Catholic' ? 'selected' : ''; ?>>Roman Catholic</option>
                            <option value="Christian" <?php echo ($userDetails['religion'] ?? '') === 'Christian' ? 'selected' : ''; ?>>Christian</option>
                            <option value="Iglesia ni Cristo" <?php echo ($userDetails['religion'] ?? '') === 'Iglesia ni Cristo' ? 'selected' : ''; ?>>Iglesia ni Cristo</option>
                            <option value="Islam" <?php echo ($userDetails['religion'] ?? '') === 'Islam' ? 'selected' : ''; ?>>Islam</option>
                            <option value="Buddhism" <?php echo ($userDetails['religion'] ?? '') === 'Buddhism' ? 'selected' : ''; ?>>Buddhism</option>
                            <option value="Hinduism" <?php echo ($userDetails['religion'] ?? '') === 'Hinduism' ? 'selected' : ''; ?>>Hinduism</option>
                            <option value="Judaism" <?php echo ($userDetails['religion'] ?? '') === 'Judaism' ? 'selected' : ''; ?>>Judaism</option>
                            <option value="Muslim" <?php echo ($userDetails['religion'] ?? '') === 'Muslim' ? 'selected' : ''; ?>>Muslim</option>
                        </select>
                        <select name="blood_type" class="border rounded-lg p-3 w-full">
                            <option value="">BLOOD TYPE</option>
                            <option value="A+" <?php echo ($userDetails['blood_type'] ?? '') === 'A+' ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo ($userDetails['blood_type'] ?? '') === 'A-' ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo ($userDetails['blood_type'] ?? '') === 'B+' ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo ($userDetails['blood_type'] ?? '') === 'B-' ? 'selected' : ''; ?>>B-</option>
                            <option value="O+" <?php echo ($userDetails['blood_type'] ?? '') === 'O+' ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo ($userDetails['blood_type'] ?? '') === 'O-' ? 'selected' : ''; ?>>O-</option>
                            <option value="AB+" <?php echo ($userDetails['blood_type'] ?? '') === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo ($userDetails['blood_type'] ?? '') === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                        </select>
                        <select name="educational_attainment" class="border rounded-lg p-3 w-full">
                            <option value="">EDUCATION ATTAINMENT</option>
                            <option value="ELEMENTARY" <?php echo ($userDetails['educational_attainment'] ?? '') === 'ELEMENTARY' ? 'selected' : ''; ?>>ELEMENTARY</option>
                            <option value="HIGH SCHOOL" <?php echo ($userDetails['educational_attainment'] ?? '') === 'HIGH SCHOOL' ? 'selected' : ''; ?>>HIGH SCHOOL</option>
                            <option value="COLLEGE" <?php echo ($userDetails['educational_attainment'] ?? '') === 'COLLEGE' ? 'selected' : ''; ?>>COLLEGE</option>
                            <option value="VOCATIONAL" <?php echo ($userDetails['educational_attainment'] ?? '') === 'VOCATIONAL' ? 'selected' : ''; ?>>VOCATIONAL</option>
                            <option value="POSTGRADUATE" <?php echo ($userDetails['educational_attainment'] ?? '') === 'POSTGRADUATE' ? 'selected' : ''; ?>>POSTGRADUATE</option>
                        </select>
                        <select name="voter_status" class="border rounded-lg p-3 w-full" required>
                            <option value="">VOTER STATUS *</option>
                            <option value="YES" <?php echo ($userDetails['voter_status'] ?? '') === 'YES' ? 'selected' : ''; ?>>YES</option>
                            <option value="NO" <?php echo ($userDetails['voter_status'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                        </select>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 border border-r-0 rounded-l bg-gray-100">+63</span>
                            <input type="text" name="contact_no" value="<?php echo $userDetails['contact_no'] ?? ''; ?>" placeholder="9123456789" class="border rounded-r-lg p-3 w-full" pattern="[0-9]{10}" maxlength="10" required>
                        </div>
                    </div>
                </div>

                <!-- 2. Birth Information -->
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition duration-300 w-full max-w-md">
                    <h2 class="text-md font-bold mb-3">BIRTH INFORMATION</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <input type="date" name="birthdate" value="<?php echo $userDetails['birthdate'] ?? ''; ?>" class="border rounded p-2 w-full" required>
                        <input type="text" name="birth_place"
                            value="<?php echo strtoupper($userDetails['birth_place'] ?? ''); ?>"
                            placeholder="BIRTH PLACE *"
                            class="border rounded p-2 w-full uppercase"
                            required>
                    </div>

                    <h2 class="text-md font-bold mt-2 mb-3">PROOF OF IDENTITY</h2>

                    <!-- Profile Photo -->
                    <div>
                        <label class="block mb-1 font-medium text-sm">PROFILE PHOTO</label>
                        <input type="file" name="photo" class="border rounded p-2 w-full" accept="image/*">
                    </div>

                    <!-- Type of Valid ID -->
                    <div>
                        <label class="block mb-1 mt-2 font-medium text-sm">TYPE OF VALID ID *</label>
                        <select name="id_type" class="border rounded p-2 w-full" required>
                            <option value="">Select ID Type</option>
                            <option value="PhilHealth ID" <?php echo ($userDetails['id_type'] ?? '') === 'PhilHealth ID' ? 'selected' : ''; ?>>PhilHealth ID</option>
                            <option value="SSS ID" <?php echo ($userDetails['id_type'] ?? '') === 'SSS ID' ? 'selected' : ''; ?>>SSS ID</option>
                            <option value="TIN ID" <?php echo ($userDetails['id_type'] ?? '') === 'TIN ID' ? 'selected' : ''; ?>>TIN ID</option>
                            <option value="Driver's License" <?php echo ($userDetails['id_type'] ?? '') === "Driver's License" ? 'selected' : ''; ?>>Driver's License</option>
                            <option value="UMID" <?php echo ($userDetails['id_type'] ?? '') === 'UMID' ? 'selected' : ''; ?>>UMID</option>
                            <option value="Voter's ID" <?php echo ($userDetails['id_type'] ?? '') === "Voter's ID" ? 'selected' : ''; ?>>Voter's ID</option>
                            <option value="Postal ID" <?php echo ($userDetails['id_type'] ?? '') === 'Postal ID' ? 'selected' : ''; ?>>Postal ID</option>
                            <option value="National ID" <?php echo ($userDetails['id_type'] ?? '') === 'National ID' ? 'selected' : ''; ?>>National ID</option>
                            <option value="Student ID" <?php echo ($userDetails['id_type'] ?? '') === 'Student ID' ? 'selected' : ''; ?>>Student ID</option>
                        </select>
                    </div>
                    <!-- Front Valid ID -->
                    <div>
                        <label class="block mb-1 mt-2 font-medium text-sm">FRONT OF VALID ID *</label>
                        <input type="file" name="front_valid_id_path" class="border rounded p-2 w-full" accept="image/*" required>
                    </div>
                    <!-- Back Valid ID -->
                    <div>
                        <label class="block mb-1 mt-2 font-medium text-sm">BACK OF VALID ID *</label>
                        <input type="file" name="back_valid_id_path" class="border rounded p-2 w-full" accept="image/*" required>
                    </div>
                    <!-- Selfie with Valid ID -->
                    <div>
                        <label class="block mb-1 mt-2 font-medium text-sm">SELFIE HOLDING YOUR VALID ID *</label>
                        <input type="file" name="selfie_with_id" class="border rounded p-2 w-full" accept="image/*" required>
                    </div>
                </div>

                <!-- 3. Address / Residency -->
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition duration-300 w-full max-w-md">
                    <h2 class="text-md font-bold mb-4">RESIDENCY INFORMATION</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <input type="text" name="house_no" value="<?php echo $userDetails['house_no'] ?? ''; ?>" placeholder="House No. / Street *" class="border rounded p-2 w-full uppercase">
                        <input type="text" name="purok" value="<?php echo $userDetails['purok'] ?? ''; ?>" placeholder="Purok / Sitio *" class="border rounded p-2 w-full uppercase" required>
                        <input type="text" name="barangay" value="POBLACION SUR" class="border rounded p-2 w-full uppercase" readonly>
                        <input type="text" name="municipality" value="TALAVERA" class="border rounded p-2 w-full uppercase" readonly>
                        <input type="text" name="province" value="NUEVA ECIJA" class="border rounded p-2 w-full uppercase" readonly>
                        <input type="number" name="years_residency" value="<?php echo $userDetails['years_residency'] ?? ''; ?>" placeholder="YEARS OF RESIDENCY" class="border rounded p-2 w-full">
                        <select name="household_head" class="border rounded p-2 w-full" required>
                            <option value="">ARE YOU HOUSEHOLD HEAD? *</option>
                            <option value="YES" <?php echo ($userDetails['household_head'] ?? '') === 'YES' ? 'selected' : ''; ?>>YES</option>
                            <option value="NO" <?php echo ($userDetails['household_head'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                        </select>
                        <select name="house_type" class="border rounded p-2 w-full" required>
                            <option value="">HOUSE TYPE *</option>
                            <option value="HOUSE" <?php echo ($userDetails['house_type'] ?? '') === 'HOUSE' ? 'selected' : ''; ?>>HOUSE</option>
                            <option value="APARTMENT" <?php echo ($userDetails['house_type'] ?? '') === 'APARTMENT' ? 'selected' : ''; ?>>APARTMENT</option>
                            <option value="DORMITORY" <?php echo ($userDetails['house_type'] ?? '') === 'DORMITORY' ? 'selected' : ''; ?>>DORMITORY</option>
                            <option value="OTHER" <?php echo ($userDetails['house_type'] ?? '') === 'OTHER' ? 'selected' : ''; ?>>OTHER</option>
                        </select>
                        <select name="ownership_status" class="border rounded p-2 w-full" required>
                            <option value="">OWNERSHIP STATUS *</option>
                            <option value="OWNED" <?php echo ($userDetails['ownership_status'] ?? '') === 'OWNED' ? 'selected' : ''; ?>>OWNED</option>
                            <option value="RENTED" <?php echo ($userDetails['ownership_status'] ?? '') === 'RENTED' ? 'selected' : ''; ?>>RENTED</option>
                            <option value="LIVING WITH RELATIVES" <?php echo ($userDetails['ownership_status'] ?? '') === 'LIVING WITH RELATIVES' ? 'selected' : ''; ?>>LIVING WITH RELATIVES</option>
                        </select>
                        <input type="text" name="previous_address" value="<?php echo $userDetails['previous_address'] ?? ''; ?>" placeholder="Previous Address (if any)" class="border rounded p-2 w-full uppercase">
                    </div>
                </div>

                <!-- 4. Family Information -->
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition duration-300 w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">Family Information</h2>

                    <!-- Parents -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <input type="text" name="fathers_name"
                            value="<?php echo strtoupper($userDetails['fathers_name'] ?? ''); ?>"
                            placeholder="FATHER'S NAME *"
                            class="border rounded p-2 w-full uppercase" required>

                        <input type="text" name="fathers_birthplace"
                            value="<?php echo strtoupper($userDetails['fathers_birthplace'] ?? ''); ?>"
                            placeholder="FATHER'S BIRTHPLACE *"
                            class="border rounded p-2 w-full uppercase" required>

                        <input type="text" name="mothers_name"
                            value="<?php echo strtoupper($userDetails['mothers_name'] ?? ''); ?>"
                            placeholder="MOTHER'S MAIDEN NAME *"
                            class="border rounded p-2 w-full uppercase" required>

                        <input type="text" name="mothers_birthplace"
                            value="<?php echo strtoupper($userDetails['mothers_birthplace'] ?? ''); ?>"
                            placeholder="MOTHER'S BIRTHPLACE *"
                            class="border rounded p-2 w-full uppercase" required>
                    </div>

                    <!-- Spouse + Dependents -->
                    <div id="spouse_dependents_group" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <input type="text" name="spouse_name"
                            value="<?php echo strtoupper($userDetails['spouse_name'] ?? ''); ?>"
                            placeholder="SPOUSE NAME"
                            class="border rounded p-2 w-full uppercase">

                        <input type="number" name="num_dependents"
                            value="<?php echo $userDetails['num_dependents'] ?? ''; ?>"
                            placeholder="DEPENDENTS"
                            class="border rounded p-2 w-full">
                    </div>

                    <!-- Emergency Contacts -->
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-3">Emergency Contact</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input type="text" name="contact_person"
                                value="<?php echo strtoupper($userDetails['contact_person'] ?? ''); ?>"
                                placeholder="EMERGENCY CONTACT PERSON *"
                                class="border rounded p-2 w-full uppercase" required>

                            <div class="flex">
                                <span class="inline-flex items-center px-3 border border-r-0 rounded-l bg-gray-100">+63</span>
                                <input type="text"
                                    name="emergency_contact_no"
                                    value="<?php echo $userDetails['emergency_contact_no'] ?? ''; ?>"
                                    placeholder="9123456789"
                                    class="border rounded-r-lg p-3 w-full"
                                    pattern="[0-9]{10}"
                                    maxlength="10"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Health Survey -->
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition duration-300 w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">Health Survey</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- PWD & Senior -->
                        <select name="pwd_status" class="border rounded p-2 w-full" required>
                            <option value="">PWD STATUS <span class="text-red-500">*</span></option>
                            <option value="Yes" <?php echo ($userDetails['pwd_status'] ?? '') === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="No" <?php echo ($userDetails['pwd_status'] ?? '') === 'No' ? 'selected' : ''; ?>>No</option>
                        </select>

                        <select name="senior_citizen_status" class="border rounded p-2 w-full" required>
                            <option value="">SENIOR CITIZEN <span class="text-red-500">*</span></option>
                            <option value="Yes" <?php echo ($userDetails['senior_citizen_status'] ?? '') === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="No" <?php echo ($userDetails['senior_citizen_status'] ?? '') === 'No' ? 'selected' : ''; ?>>No</option>
                        </select>

                        <input type="hidden" name="is_alive" value="1">

                        <!-- Health Condition -->
                        <select name="health_condition" class="border rounded p-2 w-full" required>
                            <option value="">HEALTH CONDITION *</option>
                            <option value="Healthy" <?php echo ($userDetails['health_condition'] ?? '') === 'Healthy' ? 'selected' : ''; ?>>Healthy</option>
                            <option value="Minor Illness" <?php echo ($userDetails['health_condition'] ?? '') === 'Minor Illness' ? 'selected' : ''; ?>>Minor Illness</option>
                            <option value="Chronic Illness" <?php echo ($userDetails['health_condition'] ?? '') === 'Chronic Illness' ? 'selected' : ''; ?>>Chronic Illness</option>
                            <option value="Disabled" <?php echo ($userDetails['health_condition'] ?? '') === 'Disabled' ? 'selected' : ''; ?>>Disabled</option>
                        </select>

                        <!-- Common Issues -->
                        <select name="common_health_issue" class="border rounded p-2 w-full" required>
                            <option value="">COMMON ISSUE *</option>
                            <option value="None" <?php echo ($userDetails['common_health_issue'] ?? '') === 'None' ? 'selected' : ''; ?>>None</option>
                            <option value="Diabetes" <?php echo ($userDetails['common_health_issue'] ?? '') === 'Diabetes' ? 'selected' : ''; ?>>Diabetes</option>
                            <option value="Hypertension" <?php echo ($userDetails['common_health_issue'] ?? '') === 'Hypertension' ? 'selected' : ''; ?>>Hypertension</option>
                            <option value="Asthma" <?php echo ($userDetails['common_health_issue'] ?? '') === 'Asthma' ? 'selected' : ''; ?>>Asthma</option>
                            <option value="Heart Disease" <?php echo ($userDetails['common_health_issue'] ?? '') === 'Heart Disease' ? 'selected' : ''; ?>>Heart Disease</option>
                        </select>

                        <!-- Vaccination -->
                        <select name="vaccination_status" class="border rounded p-2 w-full" required>
                            <option value="">VACCINATION *</option>
                            <option value="Not Vaccinated" <?php echo ($userDetails['vaccination_status'] ?? '') === 'Not Vaccinated' ? 'selected' : ''; ?>>Not Vaccinated</option>
                            <option value="Partially Vaccinated" <?php echo ($userDetails['vaccination_status'] ?? '') === 'Partially Vaccinated' ? 'selected' : ''; ?>>Partially Vaccinated</option>
                            <option value="Fully Vaccinated" <?php echo ($userDetails['vaccination_status'] ?? '') === 'Fully Vaccinated' ? 'selected' : ''; ?>>Fully Vaccinated</option>
                            <option value="Boostered" <?php echo ($userDetails['vaccination_status'] ?? '') === 'Boostered' ? 'selected' : ''; ?>>Boostered</option>

                        </select>

                        <!-- Height & Weight -->
                        <input type="number" step="0.1" name="height_cm"
                            value="<?php echo $userDetails['height_cm'] ?? ''; ?>"
                            placeholder="HEIGHT (cm) *"
                            class="border rounded p-2 w-full uppercase" required>

                        <input type="number" step="0.1" name="weight_kg"
                            value="<?php echo $userDetails['weight_kg'] ?? ''; ?>"
                            placeholder="WEIGHT (kg) *"
                            class="border rounded p-2 w-full uppercase" required>

                        <!-- Last Checkup -->
                        <div class="sm:col-span-2">
                            <label class="block mb-1 font-medium">LAST MEDICAL CHECKUP</label>
                            <input type="date" name="last_medical_checkup"
                                value="<?php echo $userDetails['last_medical_checkup'] ?? ''; ?>"
                                class="border rounded p-2 w-full">
                        </div>

                        <!-- Remarks -->
                        <textarea name="health_remarks"
                            class="border rounded p-2 w-full sm:col-span-2 uppercase"
                            rows="3"
                            placeholder="ADDITIONAL HEALTH REMARKS..."><?php echo strtoupper($userDetails['health_remarks'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- 6. Income & Household Info -->
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition duration-300 w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">INCOME & HOUSEHOLD</h2>
                    <div class="grid grid-cols-1 gap-4">

                        <!-- Monthly Income -->
                        <input type="number" name="monthly_income"
                            value="<?php echo $userDetails['monthly_income'] ?? ''; ?>"
                            placeholder="MONTHLY INCOME (PHP) *"
                            class="border rounded p-2 w-full uppercase" required>

                        <!-- Primary Income Source -->
                        <select name="income_source" class="border rounded p-2 w-full uppercase" required>
                            <option value="">PRIMARY INCOME SOURCE *</option>
                            <option value="Employment" <?php echo ($userDetails['income_source'] ?? '') === 'Employment' ? 'selected' : ''; ?>>Employment</option>
                            <option value="Business" <?php echo ($userDetails['income_source'] ?? '') === 'Business' ? 'selected' : ''; ?>>Business</option>
                            <option value="Farming" <?php echo ($userDetails['income_source'] ?? '') === 'Farming' ? 'selected' : ''; ?>>Farming</option>
                            <option value="Remittance" <?php echo ($userDetails['income_source'] ?? '') === 'Remittance' ? 'selected' : ''; ?>>Remittance</option>
                            <option value="Government Aid" <?php echo ($userDetails['income_source'] ?? '') === 'Government Aid' ? 'selected' : ''; ?>>Government Aid</option>
                            <option value="None" <?php echo ($userDetails['income_source'] ?? '') === 'None' ? 'selected' : ''; ?>>None</option>
                        </select>

                        <!-- Household Members -->
                        <input type="number" name="household_members"
                            value="<?php echo $userDetails['household_members'] ?? ''; ?>"
                            placeholder="TOTAL HOUSEHOLD MEMBERS *"
                            class="border rounded p-2 w-full uppercase" required>

                        <!-- Additional Sources (Optional) -->
                        <input type="text" name="additional_income_sources"
                            value="<?php echo $userDetails['additional_income_sources'] ?? ''; ?>"
                            placeholder="ADDITIONAL INCOME SOURCES (Optional)"
                            class="border rounded p-2 w-full uppercase">

                        <!-- Household Head Occupation (Optional) -->
                        <input type="text" name="household_head_occupation"
                            value="<?php echo $userDetails['household_head_occupation'] ?? ''; ?>"
                            placeholder="HOUSEHOLD HEAD OCCUPATION (Optional)"
                            class="border rounded p-2 w-full uppercase">

                        <!-- Proof of Income (Optional) -->
                        <div>
                            <label class="block mb-1 font-medium">PROOF OF INCOME (Optional)</label>
                            <input type="file" name="income_proof"
                                class="border rounded p-2 w-full"
                                accept="image/*,application/pdf">
                        </div>
                    </div>
                </div>

                <!-- Submit Card -->
                <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 w-full max-w-md bg-white shadow-lg rounded-xl p-4 flex justify-center">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-500 w-full">
                        Save Profile
                    </button>
                </div>
            </form>
            <!-- SweetAlert2 Reminder -->
            <script>
                Swal.fire({
                    icon: 'info',
                    title: 'Profile Incomplete',
                    text: 'This is your <?php echo $status; ?> dashboard. Please complete your profile to be approved by the Admin and also to show your role.',
                    confirmButtonText: 'Okay',
                    timer: 8000
                });
            </script>
        </main>
    </div>
    <script src="../../assets/js/userDashboard.js"></script>
</body>

</html>