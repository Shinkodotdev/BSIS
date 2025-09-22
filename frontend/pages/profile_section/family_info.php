<section>
                <div class="bg-white shadow rounded-xl p-6">
                    <legend class="font-semibold text-lg mb-4 text-indigo-600 border-b pb-2">Family Information</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="mb-4"> <label for="fathers_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Father's Name
                            </label>
                            <input type="text" name="fathers_name" value="<?= htmlspecialchars($data['fathers_name']); ?>"
                                placeholder="Father's Name"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4"> <label for="fathers_birthplace"
                                class="block text-sm font-medium text-gray-700 mb-1">
                                Father's Birthplace
                            </label>
                            <input type="text" name="fathers_birthplace"
                                value="<?= htmlspecialchars($data['fathers_birthplace']); ?>"
                                placeholder="Father's Birthplace"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4"> <label for="mothers_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Mother's Maiden Name
                            </label>
                            <input type="text" name="mothers_name" value="<?= htmlspecialchars($data['mothers_name']); ?>"
                                placeholder="Mother's Name"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4"> <label for="mothers_birthplace"
                                class="block text-sm font-medium text-gray-700 mb-1">
                                Mother's Birthplace
                            </label>
                            <input type="text" name="mothers_birthplace"
                                value="<?= htmlspecialchars($data['mothers_birthplace']); ?>"
                                placeholder="Mother's Birthplace"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <!-- Spouse Name -->
                        <div id="spouse_field" class="mb-4">
                            <label for="spouse_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Name of Spouse (if married)
                            </label>
                            <input type="text" name="spouse_name" value="<?= htmlspecialchars($data['spouse_name']); ?>"
                                placeholder="Spouse"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4"> <label for="num_dependents" class="block text-sm font-medium text-gray-700 mb-1">
                                No. of Dependents
                            </label>
                            <input type="number" name="num_dependents"
                                value="<?= htmlspecialchars($data['num_dependents']); ?>" placeholder="Dependents"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4"> <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">
                                Contact Person
                            </label>
                            <input type="text" name="contact_person"
                                value="<?= htmlspecialchars($data['contact_person']); ?>"
                                placeholder="Emergency Contact Person"
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="emergency_contact_no" class="block text-sm font-medium text-gray-700 mb-1">
                                Emergency Contact
                            </label>
                            <div
                                class="flex items-center w-full border rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 overflow-hidden">
                                <span class="px-3 text-gray-600 text-sm bg-gray-100 border-r">+63</span>
                                <input type="tel" id="emergency_contact_no" name="emergency_contact_no"
                                    value="<?= htmlspecialchars(preg_replace('/^\+63/', '', $data['emergency_contact_no'] ?? '')); ?>"
                                    placeholder="9XXXXXXXXX" class="flex-1 px-3 py-2 bg-gray-100 focus:outline-none"
                                    pattern="[0-9]{10}" maxlength="10" inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                        </div>

                    </div>
                </div>
            </section>