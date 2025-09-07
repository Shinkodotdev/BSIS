<form action="../../backend/actions/save_profile.php" method="POST" enctype="multipart/form-data" class="space-y-6">

    <!-- User Details -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">User Details</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <input type="text" name="f_name" value="<?= strtoupper($userDetails['f_name'] ?? '') ?>" placeholder="First Name" class="border rounded p-2 w-full uppercase" required>
            <input type="text" name="m_name" value="<?= strtoupper($userDetails['m_name'] ?? '') ?>" placeholder="Middle Name" class="border rounded p-2 w-full uppercase">
            <input type="text" name="l_name" value="<?= strtoupper($userDetails['l_name'] ?? '') ?>" placeholder="Last Name" class="border rounded p-2 w-full uppercase" required>
            <input type="text" name="ext_name" value="<?= strtoupper($userDetails['ext_name'] ?? '') ?>" placeholder="Ext. Name" class="border rounded p-2 w-full uppercase">
            <select name="gender" class="border rounded p-2 w-full">
                <option value="">Select Gender</option>
                <option value="Male" <?= ($userDetails['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($userDetails['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= ($userDetails['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                <option value="Prefer not to say" <?= ($userDetails['gender'] ?? '') === 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
            </select>
            <select name="civil_status" class="border rounded p-2 w-full uppercase" required>
                <option value="">Select Civil Status</option>
                <option value="Single" <?= ($userDetails['civil_status'] ?? '') === 'Single' ? 'selected' : '' ?>>Single</option>
                <option value="Married" <?= ($userDetails['civil_status'] ?? '') === 'Married' ? 'selected' : '' ?>>Married</option>
                <option value="Widowed" <?= ($userDetails['civil_status'] ?? '') === 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                <option value="Separated" <?= ($userDetails['civil_status'] ?? '') === 'Separated' ? 'selected' : '' ?>>Separated</option>
            </select>
            <select name="occupation" class="border rounded p-2 w-full uppercase">
                <option value="">Select Occupation / Profession</option>
                <?php 
                $occupations = ["Student","Farmer","Teacher","Government Employee","Private Employee","Business Owner","Unemployed","Other"];
                foreach($occupations as $occupation): ?>
                    <option value="<?= $occupation ?>" <?= ($userDetails['occupation'] ?? '') === $occupation ? 'selected' : '' ?>><?= $occupation ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="nationality" value="<?= $userDetails['nationality'] ?? '' ?>" placeholder="Nationality" class="border rounded p-2 w-full">
            <select name="voter_status" class="border rounded p-2 w-full">
                <option value="">Voter Status</option>
                <option value="Yes" <?= ($userDetails['voter_status'] ?? '') === 'Yes' ? 'selected' : '' ?>>Yes</option>
                <option value="No" <?= ($userDetails['voter_status'] ?? '') === 'No' ? 'selected' : '' ?>>No</option>
            </select>
            <div class="flex">
                <span class="inline-flex items-center px-3 border border-r-0 rounded-l bg-gray-100 text-gray-700">+63</span>
                <input type="tel" name="contact_no" value="<?= $userDetails['contact_no'] ?? '' ?>" placeholder="9123456789" class="border rounded-r p-2 w-full" pattern="[0-9]{10}" maxlength="10" required>
            </div>
        </div>
    </div>

    <!-- Birth Information -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Birth Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="date" name="birthdate" value="<?= $userDetails['birthdate'] ?? '' ?>" class="border rounded p-2 w-full" required>
            <input type="text" name="birth_place" value="<?= $userDetails['birth_place'] ?? '' ?>" placeholder="Birth Place" class="border rounded p-2 w-full">
            <input type="file" name="birth_certificate_path" class="border rounded p-2 w-full">
            <input type="text" name="phil_sys_no" value="<?= $userDetails['phil_sys_no'] ?? '' ?>" placeholder="PhilSys Number / National ID" class="border rounded p-2 w-full">
        </div>
    </div>

    <!-- Residency Information -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Residency Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="text" name="house_no" value="<?= $userDetails['house_no'] ?? '' ?>" placeholder="House Number / Street / Subdivision*" class="border rounded p-2 w-full uppercase">
            <input type="text" name="purok" value="<?= $userDetails['purok'] ?? '' ?>" placeholder="Purok / Sitio / Zone" class="border rounded p-2 w-full uppercase" required>
            <input type="text" name="barangay" value="Poblacion Sur" placeholder="Barangay" class="border rounded p-2 w-full uppercase" readonly>
            <input type="text" name="municipality" value="Talavera" placeholder="Municipality / City" class="border rounded p-2 w-full uppercase" readonly>
            <input type="text" name="province" value="Nueva Ecija" placeholder="Province" class="border rounded p-2 w-full uppercase" readonly>
            <input type="number" name="years_residency" value="<?= $userDetails['years_residency'] ?? '' ?>" placeholder="Years of Residency" class="border rounded p-2 w-full">
        </div>
    </div>

    <!-- Family Information -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Family Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="text" name="fathers_name" value="<?= $userDetails['fathers_name'] ?? '' ?>" placeholder="Father's Name" class="border rounded p-2 w-full">
            <input type="text" name="mothers_name" value="<?= $userDetails['mothers_name'] ?? '' ?>" placeholder="Mother's Name" class="border rounded p-2 w-full">
            <input type="text" name="spouse_name" value="<?= $userDetails['spouse_name'] ?? '' ?>" placeholder="Spouse Name (if married)" class="border rounded p-2 w-full">
            <input type="number" name="num_dependents" value="<?= $userDetails['num_dependents'] ?? '' ?>" placeholder="Number of Dependents" class="border rounded p-2 w-full">
        </div>
    </div>

    <!-- Documents -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-white shadow-md rounded-xl p-6">
        <div>
            <label class="block mb-1 font-medium">Profile Photo</label>
            <input type="file" name="photo" class="border rounded p-2 w-full">
        </div>
        <div>
            <label class="block mb-1 font-medium">Valid ID</label>
            <input type="file" name="valid_id_path" class="border rounded p-2 w-full">
        </div>
    </div>

    <!-- Health / Status -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Health / Status Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <select name="pwd_status" class="border rounded p-2 w-full">
                <option value="">PWD Status</option>
                <option value="Yes" <?= ($userDetails['pwd_status'] ?? '') === 'Yes' ? 'selected' : '' ?>>Yes</option>
                <option value="No" <?= ($userDetails['pwd_status'] ?? '') === 'No' ? 'selected' : '' ?>>No</option>
            </select>
            <select name="senior_citizen_status" class="border rounded p-2 w-full">
                <option value="">Senior Citizen Status</option>
                <option value="Yes" <?= ($userDetails['senior_citizen_status'] ?? '') === 'Yes' ? 'selected' : '' ?>>Yes</option>
                <option value="No" <?= ($userDetails['senior_citizen_status'] ?? '') === 'No' ? 'selected' : '' ?>>No</option>
            </select>
            <select name="is_alive" class="border rounded p-2 w-full">
                <option value="1" <?= ($userDetails['is_alive'] ?? 1) == 1 ? 'selected' : '' ?>>Alive</option>
                <option value="0" <?= ($userDetails['is_alive'] ?? 1) == 0 ? 'selected' : '' ?>>Deceased</option>
            </select>
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Emergency Contacts</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="text" name="contact_person" value="<?= $userDetails['contact_person'] ?? '' ?>" placeholder="Emergency Contact Person" class="border rounded p-2 w-full">
            <div class="flex">
                <span class="inline-flex items-center px-3 border border-r-0 rounded-l bg-gray-100 text-gray-700">+63</span>
                <input type="tel" name="emergency_contact_no" value="<?= $userDetails['emergency_contact_no'] ?? '' ?>" placeholder="9123456789" class="border rounded-r p-2 w-full" pattern="[0-9]{10}" maxlength="10" required>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition w-full">Save Profile</button>
    </div>
</form>
