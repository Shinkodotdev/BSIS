<div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 bg-white p-6 rounded-xl shadow-md mb-10">
                <!-- Profile Photo -->
                <div class="flex flex-col items-center sm:items-start">
                    <?php if (!empty($data['photo'])): ?>
                        <img src="<?= '/Barangay_Information_System/uploads/profile/' . basename($data['photo']); ?>"
                            class="w-28 h-28 sm:w-32 sm:h-32 object-cover rounded-full shadow-lg border-4 border-indigo-200">
                    <?php else: ?>
                        <div
                            class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 shadow-inner">
                            <i class="fa-solid fa-user text-2xl"></i>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo"
                        class="mt-3 text-sm w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <!-- Info -->
                <div class="mt-4 sm:mt-0 text-center sm:text-left flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold text-indigo-800">
                        Edit Profile
                    </h1>
                    <p class="text-gray-600 text-base sm:text-lg">
                        <?= displayOrNA($data['role']); ?>
                    </p>
                    <!-- Email -->
                    <input
                        class="block w-full border rounded px-3 py-2 mt-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                        placeholder="Email">

                    <!-- Password -->
                    <input
                        class="block w-full border rounded px-6 py-2 mt-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        type="password" name="password" value="<?= htmlspecialchars($data['password'] ?? '') ?>"
                        placeholder="Password" readonly disabled>
                    <!-- Forgot Password Button -->
                    <a href="../forgot_password.php" target="_blank"
                        class="inline-block mt-6 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Reset Password
                    </a>
                </div>
            </div>
<section class="bg-white p-6 rounded-xl shadow-sm">
                <?php sectionHeader("fa-id-card", "Personal Information"); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-4"> <label for="f_name" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name
                        </label>
                        <input
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="f_name" value="<?= htmlspecialchars($data['f_name'] ?? '') ?>"
                            placeholder="First Name">
                    </div>
                    <div class="mb-4"> <label for="m_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Middle Name
                        </label>
                        <input
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="m_name" value="<?= htmlspecialchars($data['m_name'] ?? '') ?>"
                            placeholder="Middle Name">
                    </div>
                    <div class="mb-4"> <label for="l_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name
                        </label>
                        <input
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="l_name" value="<?= htmlspecialchars($data['l_name'] ?? '') ?>"
                            placeholder="Last Name">
                    </div>
                    <!-- Extension -->
                    <div class="mb-4"> <label for="ext_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Ext. Name
                        </label>
                        <select name="ext_name"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Extension</option>
                            <?php
                            $extOptions = ["JR", "SR", "I", "II", "III", "IV"];
                            foreach ($extOptions as $opt) {
                                $selected = ($data['ext_name'] ?? '') === $opt ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- VOTER  -->
                    <div class="mb-4"> <label for="voter_status" class="block text-sm font-medium text-gray-700 mb-1">
                            Voter Status
                        </label>
                        <select name="voter_status"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Voter Status</option>
                            <option value="Yes" <?= ($data['voter_status'] ?? '') === "Yes" ? "selected" : "" ?>>Yes
                            </option>
                            <option value="No" <?= ($data['voter_status'] ?? '') === "No" ? "selected" : "" ?>>No
                            </option>
                        </select>
                    </div>
                    <!-- PWD  -->
                    <div class="mb-4"> <label for="pwd_status" class="block text-sm font-medium text-gray-700 mb-1">
                            PWD Status
                        </label>
                        <select name="pwd_status"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">PWD Status</option>
                            <option value="Yes" <?= ($data['pwd_status'] ?? '') === "Yes" ? "selected" : "" ?>>Yes</option>
                            <option value="No" <?= ($data['pwd_status'] ?? '') === "No" ? "selected" : "" ?>>No
                            </option>
                        </select>
                    </div>
                    <!-- Senior Citizen  -->
                    <div class="mb-4"> <label for="senior_citizen_status"
                            class="block text-sm font-medium text-gray-700 mb-1">
                            Senior Citizen Status
                        </label>
                        <select name="senior_citizen_status"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Senior Citizen Status</option>
                            <option value="Yes" <?= ($data['senior_citizen_status'] ?? '') === "Yes" ? "selected" : "" ?>>
                                Yes</option>
                            <option value="No" <?= ($data['senior_citizen_status'] ?? '') === "No" ? "selected" : "" ?>>No
                            </option>
                        </select>
                    </div>

                    <!-- Gender -->
                    <div class="mb-4"> <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                            Gender
                        </label>
                        <select name="gender"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Gender</option>
                            <option value="Male" <?= ($data['gender'] ?? '') === "Male" ? "selected" : "" ?>>Male</option>
                            <option value="Female" <?= ($data['gender'] ?? '') === "Female" ? "selected" : "" ?>>Female
                            </option>
                            <option value="Other" <?= ($data['gender'] ?? '') === "Other" ? "selected" : "" ?>>Other
                            </option>
                        </select>
                    </div>
                    <!-- Contact No. -->
                    <div class="mb-4">
                        <label for="contact_no" class="block text-sm font-medium text-gray-700 mb-1">
                            Contact No.
                        </label>

                        <div
                            class="flex items-center w-full border rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 overflow-hidden">
                            <span class="px-3 text-gray-600 text-sm bg-gray-100 border-r">+63</span>
                            <input type="tel" id="contact_no" name="contact_no"
                                value="<?= htmlspecialchars(preg_replace('/^\+63/', '', $data['contact_no'] ?? '')); ?>"
                                placeholder="9XXXXXXXXX" class="flex-1 px-3 py-2 bg-gray-100 focus:outline-none"
                                pattern="[0-9]{10}" maxlength="10" inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>
                    </div>
                    <!-- Civil Status -->
                    <div class="mb-4">
                        <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1">
                            Civil Status
                        </label>
                        <select id="civil_status" name="civil_status"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Civil Status</option>
                            <?php
                            $civilOptions = ["Single", "Married", "Widowed", "Separated", "Divorced"];
                            foreach ($civilOptions as $opt) {
                                $selected = ($data['civil_status'] ?? '') === $opt ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Education Attainment    -->
                    <div class="mb-4"> <label for="educational_attainment"
                            class="block text-sm font-medium text-gray-700 mb-1">
                            Educational Attainment
                        </label>
                        <select name="educational_attainment"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Educational Attainment</option>
                            <?php
                            $educationOptions = [
                                "No Formal Education",
                                "Elementary",
                                "High School",
                                "Vocational",
                                "College",
                                "Post-Graduate"
                            ];
                            foreach ($educationOptions as $opt) {
                                $selected = ($data['educational_attainment'] ?? '') === $opt ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Occupation -->
                    <div class="mb-4"> <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">
                            Occupation
                        </label>
                        <select name="occupation"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Occupation</option>
                            <?php
                            $jobOptions = ["Student", "Farmer", "Teacher", "Government Employee", "Private Employee", "Self-employed", "Unemployed", "Other"];
                            foreach ($jobOptions as $opt) {
                                $selected = ($data['occupation'] ?? '') === $opt ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Nationality -->
                    <div class="mb-4"> <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">
                            Nationality
                        </label>
                        <select name="nationality"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Nationality</option>
                            <?php
                            $nationOptions = ["Filipino", "American", "Chinese", "Japanese", "Korean", "Other"];
                            foreach ($nationOptions as $opt) {
                                $selected = ($data['nationality'] ?? '') === $opt ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Religion -->
                    <div class="mb-4"> <label for="religion" class="block text-sm font-medium text-gray-700 mb-1">
                            Religion
                        </label>
                        <select name="religion"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Religion</option>
                            <?php
                            $religionOptions = ["Roman Catholic", "Christian", "Islam", "Iglesia ni Cristo", "Buddhist", "Hindu", "Other"];
                            foreach ($religionOptions as $opt) {
                                $selected = ($data['religion'] ?? '') === $opt ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </section>