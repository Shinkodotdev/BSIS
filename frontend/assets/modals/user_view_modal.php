<?php
require_once "../../../backend/config/db.php";
require_once "../../../backend/models/Repository.php";
$user_id = $_GET['user_id'] ?? 0;
$userDetails = getUserProfileById($pdo, $user_id);
if (file_exists(__DIR__ . '/../helpers/helpers.php')) {
    include __DIR__ . '/../helpers/helpers.php';
} elseif (file_exists(__DIR__ . '/../../assets/helpers/helpers.php')) {
    include __DIR__ . '/../../assets/helpers/helpers.php';
} else {
    die("helpers.php not found!");
}
?>
<!-- Modal Background -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col">

        <!-- Fixed Header -->
        <div class="flex items-center justify-between bg-indigo-600 text-white p-4 sticky top-0 z-20">
            <h1 class="text-xl sm:text-2xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-user-circle"></i> Profile
            </h1>
            <button id="closeModalBtn" class="text-white text-2xl hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Scrollable Body -->
        <div class="overflow-y-auto p-6 space-y-6 flex-1">

            <!-- Profile Photo -->
            <div class="flex flex-col items-center mb-4">
                <?php if (!empty($userDetails['photo'])): ?>
                    <img src="<?= '/Barangay_Information_System/uploads/profile/' . basename($userDetails['photo']); ?>"
                        class="w-32 h-32 object-cover rounded-full shadow-lg border-4 border-indigo-200">
                <?php else: ?>
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 shadow-inner text-lg">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <p class="text-gray-500 italic mt-2 text-center">No profile photo uploaded</p>
                <?php endif; ?>
            </div>

            <!-- Personal Info -->
            <section class="bg-gray-50 p-4 rounded-xl shadow-sm">
                <?php sectionHeader("fa-id-card", "Personal Information"); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div><span class="font-semibold">First Name:</span> <?= displayOrNA($userDetails['f_name']); ?></div>
                    <div><span class="font-semibold">Middle Name:</span> <?= displayOrNA($userDetails['m_name']); ?></div>
                    <div><span class="font-semibold">Last Name:</span> <?= displayOrNA($userDetails['l_name']); ?></div>
                    <div><span class="font-semibold">Extension:</span> <?= displayOrNA($userDetails['ext_name']); ?></div>
                    <div><span class="font-semibold">Email:</span> <?= displayOrNA($userDetails['email']); ?></div>
                    <div><span class="font-semibold">Role:</span> <?= displayOrNA($userDetails['role']); ?></div>
                    <div><span class="font-semibold">Status:</span> <?= displayOrNA($userDetails['user_status']); ?></div>
                    <div><span class="font-semibold">Gender:</span> <?= displayOrNA($userDetails['gender']); ?></div>
                    <div><span class="font-semibold">Contact:</span> <?= displayOrNA($userDetails['contact_no']); ?></div>
                    <div><span class="font-semibold">Civil Status:</span> <?= displayOrNA($userDetails['civil_status']); ?></div>
                    <div><span class="font-semibold">Occupation:</span> <?= displayOrNA($userDetails['occupation']); ?></div>
                    <div><span class="font-semibold">Nationality:</span> <?= displayOrNA($userDetails['nationality']); ?></div>
                    <div><span class="font-semibold">Religion:</span> <?= displayOrNA($userDetails['religion']); ?></div>
                    <div><span class="font-semibold">Blood Type:</span> <?= displayOrNA($userDetails['blood_type']); ?></div>
                    <div><span class="font-semibold">Voter:</span> <?= displayOrNA($userDetails['voter_status']); ?></div>
                    <div><span class="font-semibold">PWD:</span> <?= displayOrNA($userDetails['pwd_status']); ?></div>
                    <div><span class="font-semibold">Senior Citizen:</span> <?= displayOrNA($userDetails['senior_citizen_status']); ?></div>
                    <div><span class="font-semibold">Educational Attainment:</span> <?= displayOrNA($userDetails['educational_attainment']); ?></div>
                </div>
            </section>

            <!-- Birth Info -->
            <?php if ($userDetails['birth_date'] || $userDetails['birth_place']): ?>
                <section class="bg-gray-50 p-4 rounded-xl shadow-sm">
                    <?php sectionHeader("fa-birthday-cake", "Birth Information"); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div><span class="font-semibold">Birthday:</span> <?= displayOrNA($userDetails['birth_date']); ?></div>
                        <div><span class="font-semibold">Birth Place:</span> <?= displayOrNA($userDetails['birth_place']); ?></div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Residency Info -->
            <?php if ($userDetails['house_no'] || $userDetails['barangay']): ?>
                <section class="bg-gray-50 p-4 rounded-xl shadow-sm">
                    <?php sectionHeader("fa-house", "Residency"); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
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

            <!-- Family Info -->
            <?php if ($userDetails['fathers_name'] || $userDetails['mothers_name']): ?>
                <section class="bg-gray-50 p-4 rounded-xl shadow-sm">
                    <?php sectionHeader("fa-users", "Family Information"); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
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

            <!-- Health Info -->
            <?php if ($userDetails['health_condition'] || $userDetails['vaccination_status']): ?>
                <section class="bg-gray-50 p-4 rounded-xl shadow-sm">
                    <?php sectionHeader("fa-heartbeat", "Health Information"); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div><span class="font-semibold">Health Condition:</span> <?= displayOrNA($userDetails['health_condition']); ?></div>
                        <div><span class="font-semibold">Common Issues:</span> <?= displayOrNA($userDetails['common_health_issue']); ?></div>
                        <div><span class="font-semibold">Vaccination:</span> <?= displayOrNA($userDetails['vaccination_status']); ?></div>
                        <div><span class="font-semibold">Height:</span> <?= displayOrNA($userDetails['height_cm']); ?> <?= !empty($userDetails['height_cm']) ? 'cm' : '' ?></div>
                        <div><span class="font-semibold">Weight:</span> <?= displayOrNA($userDetails['weight_kg']); ?> <?= !empty($userDetails['weight_kg']) ? 'kg' : '' ?></div>
                        <div><span class="font-semibold">Last Checkup:</span> <?= displayOrNA($userDetails['last_medical_checkup']); ?></div>
                        <div class="sm:col-span-2"><span class="font-semibold">Health Remarks:</span> <?= displayOrNA($userDetails['health_remarks']); ?></div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Income Info -->
            <?php if ($userDetails['monthly_income'] || $userDetails['income_source']): ?>
                <section class="bg-gray-50 p-4 rounded-xl shadow-sm">
                    <?php sectionHeader("fa-coins", "Income Information"); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div><span class="font-semibold">Monthly Income:</span> <?= displayOrNA($userDetails['monthly_income']); ?></div>
                        <div><span class="font-semibold">Source:</span> <?= displayOrNA($userDetails['income_source']); ?></div>
                        <div><span class="font-semibold">Household Members:</span> <?= displayOrNA($userDetails['household_members']); ?></div>
                        <div><span class="font-semibold">Additional Sources:</span> <?= displayOrNA($userDetails['additional_income_sources']); ?></div>
                        <div class="sm:col-span-2"><span class="font-semibold">Household Head Occupation:</span> <?= displayOrNA($userDetails['household_head_occupation']); ?></div>
                        <div class="sm:col-span-2">
                            <span class="font-semibold">Income Proof:</span>
                            <?php if (!empty($userDetails['income_proof'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['income_proof']), '/'); ?>" class="w-48 h-auto rounded-lg border mt-2">
                            <?php else: ?>
                                <p class="text-gray-500 italic">No proof uploaded</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Identity Docs -->
            <?php if ($userDetails['id_type'] || $userDetails['front_valid_id_path'] || $userDetails['back_valid_id_path'] || $userDetails['selfie_with_id']): ?>
                <section class="bg-gray-50 p-4 rounded-xl shadow-sm">
                    <?php sectionHeader("fa-id-card", "Identity Documents"); ?>
                    <p class="font-semibold">ID Type:</p>
                    <p class="mb-4"><?= displayOrNA($userDetails['id_type']); ?></p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Front ID -->
                        <div class="text-center p-2 bg-white rounded-lg shadow">
                            <p class="font-semibold mb-2">Front ID</p>
                            <?php if (!empty($userDetails['front_valid_id_path'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['front_valid_id_path']), '/'); ?>" class="mx-auto w-full max-w-xs h-auto rounded-lg border">
                            <?php else: ?>
                                <p class="text-gray-400 italic">Not uploaded</p>
                            <?php endif; ?>
                        </div>
                        <!-- Back ID -->
                        <div class="text-center p-2 bg-white rounded-lg shadow">
                            <p class="font-semibold mb-2">Back ID</p>
                            <?php if (!empty($userDetails['back_valid_id_path'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['back_valid_id_path']), '/'); ?>" class="mx-auto w-full max-w-xs h-auto rounded-lg border">
                            <?php else: ?>
                                <p class="text-gray-400 italic">Not uploaded</p>
                            <?php endif; ?>
                        </div>
                        <!-- Selfie with ID -->
                        <div class="text-center p-2 bg-white rounded-lg shadow">
                            <p class="font-semibold mb-2">Selfie with ID</p>
                            <?php if (!empty($userDetails['selfie_with_id'])): ?>
                                <img src="/Barangay_Information_System/<?= ltrim(str_replace('../', '', $userDetails['selfie_with_id']), '/'); ?>" class="mx-auto w-full max-w-xs h-auto rounded-lg border">
                            <?php else: ?>
                                <p class="text-gray-400 italic">Not uploaded</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>
</div>

