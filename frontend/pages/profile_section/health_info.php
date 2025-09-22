<section>
                <div class="bg-white shadow rounded-xl p-6">
                    <legend class="font-semibold text-lg mb-4 text-indigo-600 border-b pb-2">Health Information</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Blood Type -->
                        <div class="mb-4"> <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Blood Type
                            </label>
                            <select name="blood_type"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Blood Type</option>
                                <?php
                                $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                foreach ($bloodTypes as $blood) {
                                    $selected = ($data['blood_type'] ?? '') === $blood ? 'selected' : '';
                                    echo "<option value='$blood' $selected>$blood</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Health Condition -->
                        <div class="mb-4"> <label for="health_condition"
                                class="block text-sm font-medium text-gray-700 mb-1">
                                Health Condition
                            </label>
                            <select name="health_condition"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Health Condition</option>
                                <?php
                                $conditions = ['Healthy', 'Disabled', 'Pregnant', 'Senior Citizen', 'With Chronic Illness'];
                                foreach ($conditions as $condition) {
                                    $selected = ($data['health_condition'] ?? '') === $condition ? 'selected' : '';
                                    echo "<option value='$condition' $selected>$condition</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Common Health Issue -->
                        <div class="mb-4"> <label for="common_health_issue"
                                class="block text-sm font-medium text-gray-700 mb-1">
                                Common Health Issue
                            </label>
                            <select name="common_health_issue"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Common Health Issue</option>
                                <?php
                                $issues = ['Diabetes', 'Hypertension', 'Asthma', 'Heart Disease', 'None'];
                                foreach ($issues as $issue) {
                                    $selected = ($data['common_health_issue'] ?? '') === $issue ? 'selected' : '';
                                    echo "<option value='$issue' $selected>$issue</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-4"> <label for="vaccination_status"
                                class="block text-sm font-medium text-gray-700 mb-1">
                                Vaccination Status
                            </label>
                            <select name="vaccination_status"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Vaccination Status</option>
                                <?php
                                $vaccOptions = ["Not Vaccinated", "Partially Vaccinated", "Fully Vaccinated", "Boostered"];
                                foreach ($vaccOptions as $opt) {
                                    $selected = ($data['vaccination_status'] ?? '') === $opt ? 'selected' : '';
                                    echo "<option value='$opt' $selected>$opt</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-4"> <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-1">
                                Weight (kg)
                            </label>
                            <input type="number" step="0.1" name="weight_kg"
                                value="<?= htmlspecialchars($data['weight_kg']); ?>" placeholder="Weight (kg)"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4"> <label for="height_cm" class="block text-sm font-medium text-gray-700 mb-1">
                                Height (cm)
                            </label>
                            <input type="number" step="0.1" name="height_cm"
                                value="<?= htmlspecialchars($data['height_cm']); ?>" placeholder="Height (cm)"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4"> <label for="last_medical_checkup"
                                class="block text-sm font-medium text-gray-700 mb-1">
                                Last Medical Checkup
                            </label>
                            <input type="date" name="last_medical_checkup"
                                value="<?= htmlspecialchars(date('Y-m-d', strtotime($data['last_medical_checkup']))); ?>">
                        </div>
                        <div class="mb-4"> <label for="health_remarks" class="block text-sm font-medium text-gray-700 mb-1">
                                Health Remarks
                            </label>
                            <textarea name="health_remarks" placeholder="Health Remarks"
                                class="w-full border rounded px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"><?= htmlspecialchars($data['health_remarks']); ?></textarea>
                        </div>
                    </div>
                </div>
            </section>