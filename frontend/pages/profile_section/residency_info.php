<section class="bg-white p-6 rounded-xl shadow-sm">
                <?php sectionHeader("fa-home", "Residency Information"); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-4"> <label for="house_no" class="block text-sm font-medium text-gray-700 mb-1">
                            House No.
                        </label>
                        <input
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="house_no" value="<?= htmlspecialchars($data['house_no'] ?? '') ?>"
                            placeholder="House No.">
                    </div>
                    <div class="mb-4"> <label for="purok" class="block text-sm font-medium text-gray-700 mb-1">
                            Purok
                        </label>
                        <!-- Purok Dropdown -->
                        <select name="purok" required
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Street/Purok</option>
                            <option value="PUROK1" <?= ($data['purok'] ?? '') === 'PUROK1' ? 'selected' : '' ?>>Purok 1
                            </option>
                            <option value="PUROK2" <?= ($data['purok'] ?? '') === 'PUROK2' ? 'selected' : '' ?>>Purok 2
                            </option>
                            <option value="PUROK3" <?= ($data['purok'] ?? '') === 'PUROK3' ? 'selected' : '' ?>>Purok 3
                            </option>
                            <option value="PUROK4" <?= ($data['purok'] ?? '') === 'PUROK4' ? 'selected' : '' ?>>Purok 4
                            </option>
                            <option value="PUROK5" <?= ($data['purok'] ?? '') === 'PUROK5' ? 'selected' : '' ?>>Purok 5
                            </option>
                            <option value="PUROK6" <?= ($data['purok'] ?? '') === 'PUROK6' ? 'selected' : '' ?>>Purok 6
                            </option>
                            <option value="PUROK7A" <?= ($data['purok'] ?? '') === 'PUROK7A' ? 'selected' : '' ?>>Purok 7A
                            </option>
                            <option value="PUROK7B" <?= ($data['purok'] ?? '') === 'PUROK7B' ? 'selected' : '' ?>>Purok 7B
                            </option>
                        </select>
                    </div>
                    <!-- Fixed Barangay, Municipality, and Province -->
                    <div class="mb-4"> <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">
                            Barangay
                        </label>
                        <input type="text"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="barangay" value="POBLACION SUR" readonly>
                    </div>
                    <div class="mb-4"> <label for="municipality" class="block text-sm font-medium text-gray-700 mb-1">
                            Municipality
                        </label>
                        <input type="text"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="municipality" value="TALAVERA" readonly>
                    </div>
                    <div class="mb-4"> <label for="province" class="block text-sm font-medium text-gray-700 mb-1">
                            Province
                        </label>
                        <input type="text"
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="province" value="NUEVA ECIJA" readonly>
                    </div>
                </div>
            </section>