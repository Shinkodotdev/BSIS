<div class="max-w-5xl mx-auto px-6 py-10">
    <!-- ✅ Profile Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 bg-white p-6 rounded-xl shadow-md mb-10">
        <!-- Profile Photo -->
        <div class="flex flex-col items-center sm:items-start">
            <?php if (!empty($data['photo'])): ?>
                <img src="<?= '/Barangay_Information_System/uploads/profile/' . basename($data['photo']); ?>"
                    class="w-28 h-28 sm:w-32 sm:h-32 object-cover rounded-full shadow-lg border-4 border-indigo-200 hover:scale-105 transition-transform duration-300">
            <?php else: ?>
                <div
                    class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 shadow-inner text-lg">
                    <i class="fa-solid fa-user text-2xl"></i>
                </div>
                <p class="text-gray-500 italic mt-2 text-sm sm:text-base">No profile photo uploaded</p>
            <?php endif; ?>
        </div>

        <!-- Profile Info -->
        <div class="mt-4 sm:mt-0 text-center sm:text-left flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-indigo-800 break-words">
                <?= displayOrNA($data['f_name'] . " " . $data['m_name'] . " " . $data['l_name'] . " " . $data['ext_name']); ?>
            </h1>
            <p class="text-gray-600 text-base sm:text-lg"><?= displayOrNA($data['role']); ?></p>
            <!-- Email with Eye Toggle -->
            <div class="mb-2">
                <span class="font-semibold">Email:</span>
                <div class="relative inline-block">
                    <input type="password" id="emailField" value="<?= htmlspecialchars($data['email']); ?>"
                        class="border rounded px-2 py-1 pr-8" readonly>
                    <button type="button" onclick="toggleVisibility('emailField', this)"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Password always hidden -->
            <div class="mb-2">
                <span class="font-semibold">Password:</span>
                <div class="relative inline-block">
                    <input type="password" id="passwordField" value="<?= htmlspecialchars($data['password']); ?>"
                        class="border rounded px-2 py-1" readonly disabled>
                </div>
            </div>
            <!-- Status -->
            <div class="mt-4">
                <?php if (strtolower($status) === 'pending'): ?>
                    <span
                        class="bg-yellow-100 text-yellow-700 px-3 py-1 sm:px-4 sm:py-2 rounded-lg shadow-sm text-sm sm:text-base">
                        <i class="fa-solid fa-clock"></i> Please check your information and wait for admin approval.
                    </span>
                <?php else: ?>
                    <span
                        class="bg-green-100 text-green-700 px-3 py-1 sm:px-4 sm:py-2 rounded-lg shadow-sm text-sm sm:text-base">
                        <i class="fa-solid fa-check-circle"></i> <?= htmlspecialchars(ucfirst($status)); ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- ✅ Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="./edit_profile.php"
                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition">
                    <i class="fa-solid fa-pen-to-square mr-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>


    <!-- ✅ Profile Sections -->
    <?php if ($data): ?>
        <!-- Personal Info -->
        <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
            <?php sectionHeader("fa-id-card", "Personal Information"); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><span class="font-semibold">First Name:</span> <?= displayOrNA($data['f_name']); ?></div>
                <div><span class="font-semibold">Middle Name:</span> <?= displayOrNA($data['m_name']); ?></div>
                <div><span class="font-semibold">Last Name:</span> <?= displayOrNA($data['l_name']); ?></div>
                <div><span class="font-semibold">Extension:</span> <?= displayOrNA($data['ext_name']); ?></div>
                <div><span class="font-semibold">Gender:</span> <?= displayOrNA($data['gender']); ?></div>
                <div><span class="font-semibold">Contact:</span> <?= displayOrNA($data['contact_no']); ?></div>
                <div><span class="font-semibold">Civil Status:</span> <?= displayOrNA($data['civil_status']); ?></div>
                <div><span class="font-semibold">Occupation:</span> <?= displayOrNA($data['occupation']); ?></div>
                <div><span class="font-semibold">Nationality:</span> <?= displayOrNA($data['nationality']); ?></div>
                <div><span class="font-semibold">Religion:</span> <?= displayOrNA($data['religion']); ?></div>
                <div><span class="font-semibold">Blood Type:</span> <?= displayOrNA($data['blood_type']); ?></div>
                <div><span class="font-semibold">Voter:</span> <?= displayOrNA($data['voter_status']); ?></div>
                <div><span class="font-semibold">PWD:</span> <?= displayOrNA($data['pwd_status']); ?></div>
                <div><span class="font-semibold">Senior Citizen:</span>
                    <?= displayOrNA($data['senior_citizen_status']); ?></div>
                <div><span class="font-semibold">Educational Attainment:</span>
                    <?= displayOrNA($data['educational_attainment']); ?></div>
            </div>
        </section>

        <!-- Birth Info -->
        <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
            <?php sectionHeader("fa-baby", "Birth Information"); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><span class="font-semibold">Birthdate:</span> <?= displayOrNA($data['birth_date']); ?></div>
                <div><span class="font-semibold">Birthplace:</span> <?= displayOrNA($data['birth_place']); ?></div>
            </div>
        </section>

        <!-- Residency -->
        <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
            <?php sectionHeader("fa-home", "Residency Information"); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><span class="font-semibold">House No.:</span> <?= displayOrNA($data['house_no']); ?></div>
                <div><span class="font-semibold">Purok:</span> <?= displayOrNA($data['purok']); ?></div>
                <div><span class="font-semibold">Barangay:</span> <?= displayOrNA($data['barangay']); ?></div>
                <div><span class="font-semibold">Municipality:</span> <?= displayOrNA($data['municipality']); ?></div>
                <div><span class="font-semibold">Province:</span> <?= displayOrNA($data['province']); ?></div>
                <div><span class="font-semibold">Years of Residency:</span>
                    <?= displayOrNA($data['years_residency']); ?></div>
                <div><span class="font-semibold">House Type:</span> <?= displayOrNA($data['house_type']); ?></div>
                <div><span class="font-semibold">Ownership Status:</span> <?= displayOrNA($data['ownership_status']); ?>
                </div>
                <div><span class="font-semibold">Previous Address:</span> <?= displayOrNA($data['previous_address']); ?>
                </div>
            </div>
        </section>

        <!-- Family Info -->
        <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
            <?php sectionHeader("fa-users", "Family Information"); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><span class="font-semibold">Father's Name:</span> <?= displayOrNA($data['fathers_name']); ?></div>
                <div><span class="font-semibold">Father
                        Birthplace:</span><?= displayOrNA($data['fathers_birthplace']); ?></div>
                <div><span class="font-semibold">Mother's Maiden Name:</span> <?= displayOrNA($data['mothers_name']); ?>
                </div>
                <div><span class="font-semibold">Mothers
                        Birthplace:</span><?= displayOrNA($data['mothers_birthplace']); ?></div>
                <div><span class="font-semibold">Spouse Name:</span> <?= displayOrNA($data['spouse_name']); ?></div>
                <div><span class="font-semibold">No. Dependents:</span> <?= displayOrNA($data['num_dependents']); ?>
                </div>
                <div><span class="font-semibold">Contact Person:</span> <?= displayOrNA($data['contact_person']); ?>
                </div>
                <div><span class="font-semibold">Emergency Contact
                        No.:</span><?= displayOrNA($data['emergency_contact_no']); ?></div>
            </div>
        </section>

        <!-- Health Info -->
        <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
            <?php sectionHeader("fa-heartbeat", "Health Information"); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><span class="font-semibold">Health Condition:</span> <?= displayOrNA($data['health_condition']); ?>
                </div>
                <div><span class="font-semibold">Common Health Issue:</span>
                    <?= displayOrNA($data['common_health_issue']); ?></div>
                <div><span class="font-semibold">Common Health Issue:</span>
                    <?= displayOrNA($data['common_health_issue']); ?></div>
                <div><span class="font-semibold">Weight(kg):</span> <?= displayOrNA($data['weight_kg']); ?></div>
                <div><span class="font-semibold">Height(cm):</span> <?= displayOrNA($data['height_cm']); ?></div>
                <div>
                    <span class="font-semibold">Last Medical Checkup:</span>
                    <?= !empty($data['last_medical_checkup'])
                        ? date("F d, Y", strtotime($data['last_medical_checkup']))
                        : "N/A"; ?>
                </div>
                <div><span class="font-semibold">Health Remarks:</span> <?= displayOrNA($data['health_remarks']); ?>
                </div>
            </div>
        </section>

        <!-- Income Info -->
        <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
            <?php sectionHeader("fa-coins", "Income Information"); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><span class="font-semibold">Monthly Income:</span> <?= displayOrNA($data['monthly_income']); ?>
                </div>
                <div><span class="font-semibold">Souce of Income:</span> <?= displayOrNA($data['income_source']); ?>
                </div>
                <div><span class="font-semibold">Household Members
                        :</span><?= displayOrNA($data['household_members']); ?></div>
                <div><span class="font-semibold">Additional Souce of
                        Income:</span><?= displayOrNA($data['additional_income_sources']); ?></div>
                <div><span class="font-semibold">Household Head
                        Occupation:</span><?= displayOrNA($data['household_head_occupation']); ?></div>
                <div>
                    <span class="font-semibold">Proof of Income:</span>
                    <?php if (!empty($data['income_proof'])): ?>
                        <img src="<?= '/Barangay_Information_System/uploads/income/' . basename($data['income_proof']); ?>"
                            alt="ID Image" class="mt-2 w-48 border rounded shadow-sm">
                    <?php else: ?>
                        <?= displayOrNA(null); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Identity Docs -->
        <section class="mb-8 bg-gray-50 p-6 rounded-xl shadow-sm">
            <?php sectionHeader("fa-id-badge", "Identity Documents"); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><span class="font-semibold">ID Type:</span> <?= displayOrNA($data['id_type']); ?></div>
                <div>
                    <span class="font-semibold">Front ID Image:</span>
                    <?php if (!empty($data['front_valid_id_path'])): ?>
                        <img src="<?= '/Barangay_Information_System/uploads/ids/front/' . basename($data['front_valid_id_path']); ?>"
                            alt="ID Image" class="mt-2 w-48 border rounded shadow-sm">
                    <?php else: ?>
                        <?= displayOrNA(null); ?>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="font-semibold">Back ID Image:</span>
                    <?php if (!empty($data['back_valid_id_path'])): ?>
                        <img src="<?= '/Barangay_Information_System/uploads/ids/back/' . basename($data['back_valid_id_path']); ?>"
                            alt="ID Image" class="mt-2 w-48 border rounded shadow-sm">
                    <?php else: ?>
                        <?= displayOrNA(null); ?>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="font-semibold">Selfie With ID Image:</span>
                    <?php if (!empty($data['selfie_with_id'])): ?>
                        <img src="<?= '/Barangay_Information_System/uploads/ids/selfie/' . basename($data['selfie_with_id']); ?>"
                            alt="ID Image" class="mt-2 w-48 border rounded shadow-sm">
                    <?php else: ?>
                        <?= displayOrNA(null); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <p class="text-red-500 font-semibold">No profile data found.</p>
    <?php endif; ?>
</div>
<script>
    function toggleVisibility(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon = btn.querySelector("i");

        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>