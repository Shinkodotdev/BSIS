<?php
?>
<!-- PERSONAL MODAL -->
<div id="personalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
    <div class="bg-white p-6 rounded-lg w-full max-w-3xl overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-bold mb-4 ">Edit Personal Information</h2>
        <form action="update_profile.php" method="POST" class="space-y-4">
            <input type="hidden" name="section" value="personal">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">First Name</label>
                    <input type="text" name="f_name" value="<?= $data['f_name'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Middle Name</label>
                    <input type="text" name="m_name" value="<?= $data['m_name'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Last Name</label>
                    <input type="text" name="l_name" value="<?= $data['l_name'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Extension</label>
                    <select name="ext_name" class="border rounded-lg p-3 w-full uppercase">
                        <option value="">Ext. Name</option>
                        <option value="Jr" <?= strtoupper($userDetails['ext_name'] ?? '') === 'JR' ? 'selected' : '' ?>>Jr.</option>
                        <option value="Sr" <?= strtoupper($userDetails['ext_name'] ?? '') === 'SR' ? 'selected' : '' ?>>Sr.</option>
                        <option value="II" <?= strtoupper($userDetails['ext_name'] ?? '') === 'II' ? 'selected' : '' ?>>II</option>
                        <option value="III" <?= strtoupper($userDetails['ext_name'] ?? '') === 'III' ? 'selected' : '' ?>>III</option>
                        <option value="IV" <?= strtoupper($userDetails['ext_name'] ?? '') === 'IV' ? 'selected' : '' ?>>IV</option>
                    </select>
                </div>

                <div >
                    <label class="block font-semibold mb-1">Gender</label>
                    <input type="text" name="gender" value="<?= $data['gender'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Contact No</label>
                    <input type="text" name="contact_no" value="<?= $data['contact_no'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Civil Status</label>
                    <input type="text" name="civil_status" value="<?= $data['civil_status'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Occupation</label>
                    <input type="text" name="occupation" value="<?= $data['occupation'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Nationality</label>
                    <input type="text" name="nationality" value="<?= $data['nationality'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Voter Status</label>
                    <input type="text" name="voter_status" value="<?= $data['voter_status'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">PWD</label>
                    <input type="text" name="pwd_status" value="<?= $data['pwd_status'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Senior Citizen</label>
                    <input type="text" name="senior_citizen_status" value="<?= $data['senior_citizen_status'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Religion</label>
                    <input type="text" name="religion" value="<?= $data['religion'] ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Blood Type</label>
                    <input type="text" name="blood_type" value="<?= $data['blood_type'] ?>" class="w-full border p-2 rounded">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-semibold mb-1">Education</label>
                    <input type="text" name="educational_attainment" value="<?= $data['educational_attainment'] ?>" class="w-full border p-2 rounded">
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-2">
                <button type="button" onclick="closeModal('personalModal')" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</div>


<!-- BIRTH MODAL -->
<div id="birthModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-3xl overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-bold mb-4">Edit Birth Information</h2>
        <form action="update_profile.php" method="POST">
            <input type="hidden" name="section" value="birth">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">

            <label>Birth Date</label>
            <input type="date" name="birth_date" value="<?= $data['birth_date'] ?>" class="w-full border p-2 mb-2">

            <label>Birth Place</label>
            <input type="text" name="birth_place" value="<?= $data['birth_place'] ?>" class="w-full border p-2 mb-2">

            <div class="flex justify-end">
                <button type="button" onclick="closeModal('birthModal')" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- RESIDENCY MODAL -->
<div id="residencyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center overflow-y-auto">
    <div class="bg-white p-8 mt-4 rounded-lg w-full max-w-3xl overflow-y-auto max-h-[80vh]">
        <h2 class="text-xl font-bold mb-4">Edit Residency</h2>
        <form action="update_profile.php" method="POST">
            <input type="hidden" name="section" value="residency">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">

            <label>House No</label>
            <input type="text" name="house_no" value="<?= $data['house_no'] ?>" class="w-full border p-2 mb-2">

            <label>Purok</label>
            <input type="text" name="purok" value="<?= $data['purok'] ?>" class="w-full border p-2 mb-2">

            <label>Barangay</label>
            <input type="text" name="barangay" value="<?= $data['barangay'] ?>" class="w-full border p-2 mb-2">

            <label>Municipality</label>
            <input type="text" name="municipality" value="<?= $data['municipality'] ?>" class="w-full border p-2 mb-2">

            <label>Province</label>
            <input type="text" name="province" value="<?= $data['province'] ?>" class="w-full border p-2 mb-2">

            <label>Years of Residency</label>
            <input type="number" name="years_residency" value="<?= $data['years_residency'] ?>" class="w-full border p-2 mb-2">

            <label>Household Head</label>
            <input type="text" name="household_head" value="<?= $data['household_head'] ?>" class="w-full border p-2 mb-2">

            <label>House Type</label>
            <input type="text" name="house_type" value="<?= $data['house_type'] ?>" class="w-full border p-2 mb-2">

            <label>Ownership Status</label>
            <input type="text" name="ownership_status" value="<?= $data['ownership_status'] ?>" class="w-full border p-2 mb-2">

            <label>Previous Address</label>
            <input type="text" name="previous_address" value="<?= $data['previous_address'] ?>" class="w-full border p-2 mb-2">

            <div class="flex justify-end">
                <button type="button" onclick="closeModal('residencyModal')" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- FAMILY MODAL -->
<div id="familyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-8 mt-4 rounded-lg w-full max-w-3xl overflow-y-auto max-h-[80vh]">
        <h2 class="text-xl font-bold mb-4">Edit Family Information</h2>
        <form action="update_profile.php" method="POST">
            <input type="hidden" name="section" value="family">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">

            <label>Father's Name</label>
            <input type="text" name="fathers_name" value="<?= $data['fathers_name'] ?>" class="w-full border p-2 mb-2">

            <label>Father's Birthplace</label>
            <input type="text" name="fathers_birthplace" value="<?= $data['fathers_birthplace'] ?>" class="w-full border p-2 mb-2">

            <label>Mother's Name</label>
            <input type="text" name="mothers_name" value="<?= $data['mothers_name'] ?>" class="w-full border p-2 mb-2">

            <label>Mother's Birthplace</label>
            <input type="text" name="mothers_birthplace" value="<?= $data['mothers_birthplace'] ?>" class="w-full border p-2 mb-2">

            <label>Spouse Name</label>
            <input type="text" name="spouse_name" value="<?= $data['spouse_name'] ?>" class="w-full border p-2 mb-2">

            <label>Number of Dependents</label>
            <input type="number" name="num_dependents" value="<?= $data['num_dependents'] ?>" class="w-full border p-2 mb-2">

            <label>Emergency Contact Person</label>
            <input type="text" name="contact_person" value="<?= $data['contact_person'] ?>" class="w-full border p-2 mb-2">

            <label>Emergency Contact No</label>
            <input type="text" name="emergency_contact_no" value="<?= $data['emergency_contact_no'] ?>" class="w-full border p-2 mb-2">

            <div class="flex justify-end">
                <button type="button" onclick="closeModal('familyModal')" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- HEALTH MODAL -->
<div id="healthModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-8 mt-4 rounded-lg w-full max-w-3xl overflow-y-auto max-h-[80vh]">
        <h2 class="text-xl font-bold mb-4">Edit Health Information</h2>
        <form action="update_profile.php" method="POST">
            <input type="hidden" name="section" value="health">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">

            <label>Health Condition</label>
            <input type="text" name="health_condition" value="<?= $data['health_condition'] ?>" class="w-full border p-2 mb-2">

            <label>Common Health Issue</label>
            <input type="text" name="common_health_issue" value="<?= $data['common_health_issue'] ?>" class="w-full border p-2 mb-2">

            <label>Vaccination Status</label>
            <input type="text" name="vaccination_status" value="<?= $data['vaccination_status'] ?>" class="w-full border p-2 mb-2">

            <label>Height (cm)</label>
            <input type="number" step="0.01" name="height_cm" value="<?= $data['height_cm'] ?>" class="w-full border p-2 mb-2">

            <label>Weight (kg)</label>
            <input type="number" step="0.01" name="weight_kg" value="<?= $data['weight_kg'] ?>" class="w-full border p-2 mb-2">

            <label>Last Medical Checkup</label>
            <input type="date" name="last_medical_checkup" value="<?= $data['last_medical_checkup'] ?>" class="w-full border p-2 mb-2">

            <label>Remarks</label>
            <textarea name="health_remarks" class="w-full border p-2 mb-2"><?= $data['health_remarks'] ?></textarea>

            <div class="flex justify-end">
                <button type="button" onclick="closeModal('healthModal')" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- INCOME MODAL -->
<div id="incomeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-8 mt-4 rounded-lg w-full max-w-3xl overflow-y-auto max-h-[80vh]">
        <h2 class="text-xl font-bold mb-4">Edit Income Information</h2>
        <form action="update_profile.php" method="POST">
            <input type="hidden" name="section" value="income">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">

            <label>Monthly Income</label>
            <input type="number" step="0.01" name="monthly_income" value="<?= $data['monthly_income'] ?>" class="w-full border p-2 mb-2">

            <label>Income Source</label>
            <input type="text" name="income_source" value="<?= $data['income_source'] ?>" class="w-full border p-2 mb-2">

            <label>Household Members</label>
            <input type="number" name="household_members" value="<?= $data['household_members'] ?>" class="w-full border p-2 mb-2">

            <label>Additional Income Sources</label>
            <input type="text" name="additional_income_sources" value="<?= $data['additional_income_sources'] ?>" class="w-full border p-2 mb-2">

            <label>Head Occupation</label>
            <input type="text" name="household_head_occupation" value="<?= $data['household_head_occupation'] ?>" class="w-full border p-2 mb-2">

            <div class="flex justify-end">
                <button type="button" onclick="closeModal('incomeModal')" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- IDENTITY MODAL -->
<div id="identityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/2">
        <h2 class="text-xl font-bold mb-4">Edit Identity Documents</h2>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="section" value="identity">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">

            <label>ID Type</label>
            <input type="text" name="id_type" value="<?= $data['id_type'] ?>" class="w-full border p-2 mb-2">

            <label>Front ID</label>
            <input type="file" name="front_valid_id_path" class="w-full border p-2 mb-2">

            <label>Back ID</label>
            <input type="file" name="back_valid_id_path" class="w-full border p-2 mb-2">

            <label>Selfie with ID</label>
            <input type="file" name="selfie_with_id" class="w-full border p-2 mb-2">

            <div class="flex justify-end">
                <button type="button" onclick="closeModal('identityModal')" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>