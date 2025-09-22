<div class="bg-white shadow rounded-xl p-6">
                <legend class="font-semibold text-lg mb-4 text-indigo-600 border-b pb-2">Income Information</legend>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="monthly_income" class="block text-sm font-medium text-gray-700 mb-1">
                            Monthly Income
                        </label>
                        <input type="text" name="monthly_income"
                            value="<?= htmlspecialchars($data['monthly_income']); ?>" placeholder="Monthly Income"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="income_source" class="block text-sm font-medium text-gray-700 mb-1">
                            Source of Income
                        </label>
                        <select id="income_source" name="income_source" class="border rounded p-2 w-full uppercase"
                            required>
                            <option value="">Select Income Source</option>
                            <option value="Employment" <?= ($data['income_source'] ?? '') === 'Employment' ? 'selected' : ''; ?>>Employment</option>
                            <option value="Business" <?= ($data['income_source'] ?? '') === 'Business' ? 'selected' : ''; ?>>
                                Business</option>
                            <option value="Farming" <?= ($data['income_source'] ?? '') === 'Farming' ? 'selected' : ''; ?>>
                                Farming</option>
                            <option value="Remittance" <?= ($data['income_source'] ?? '') === 'Remittance' ? 'selected' : ''; ?>>Remittance</option>
                            <option value="Government Aid" <?= ($data['income_source'] ?? '') === 'Government Aid' ? 'selected' : ''; ?>>Government Aid</option>
                            <option value="None" <?= ($data['income_source'] ?? '') === 'None' ? 'selected' : ''; ?>>None
                            </option>
                        </select>
                    </div>
                    <!-- Household Head Occupation -->
                    <div class="mb-4">
                        <label for="household_head_occupation" class="block text-sm font-medium text-gray-700 mb-1">
                            Household Head Occupation
                        </label>
                        <input type="text" id="household_head_occupation" name="household_head_occupation"
                            value="<?= htmlspecialchars($data['household_head_occupation'] ?? ''); ?>"
                            placeholder="Household Head Occupation"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Household Members -->
                    <div class="mb-4">
                        <label for="household_members" class="block text-sm font-medium text-gray-700 mb-1">
                            Household Members
                        </label>
                        <input type="number" id="household_members" name="household_members"
                            value="<?= htmlspecialchars($data['household_members']); ?>" placeholder="Household Members"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Additional Income Sources -->
                    <div class="mb-4">
                        <label for="additional_income_sources" class="block text-sm font-medium text-gray-700 mb-1">
                            Additional Income Sources
                        </label>
                        <input type="text" id="additional_income_sources" name="additional_income_sources"
                            value="<?= htmlspecialchars($data['additional_income_sources']); ?>"
                            placeholder="Additional Sources"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Income Proof Upload -->
                    <div class="flex flex-col">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Income Proof</label>
                        <input type="file" name="income_proof" accept="image/*"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>