<section class="bg-white p-6 rounded-xl shadow-sm">
                <?php sectionHeader("fa-baby", "Birth Information"); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-4"> <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Birth Date
                        </label>
                        <input
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            type="date" name="birth_date" value="<?= htmlspecialchars($data['birth_date'] ?? '') ?>">
                    </div>
                    <div class="mb-4"> <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">
                            Birth Place
                        </label>
                        <input
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            name="birth_place" value="<?= htmlspecialchars($data['birth_place'] ?? '') ?>"
                            placeholder="Birthplace">
                    </div>
                </div>
            </section>