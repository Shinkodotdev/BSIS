<section>
                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">
                    👥 Rejected User Account
                </h1>
                <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6">

                    <!-- Search -->
                    <div class="mb-3 flex flex-col sm:flex-row justify-between gap-3">
                        <input id="approvedUserSearch" type="text" placeholder="Search users..."
                            class="w-full sm:w-1/3 border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto max-h-96 overflow-y-auto">
                        <table id="approvedUserTable" class="min-w-full text-sm border divide-y divide-gray-200">
                            <thead class="bg-gray-100 text-gray-700 sticky top-0 z-20">
                                <tr>
                                    <th class="px-3 py-2 text-left sticky left-0 bg-gray-100 z-30">Name</th>
                                    <th class="px-3 py-2 text-left">Email</th>
                                    <th class="px-3 py-2 text-left">Role</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rejectedUsers as $row): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-3 py-2 whitespace-nowrap sticky left-0 bg-white z-10">
                                            <?= htmlspecialchars($row['full_name']) ?>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap"><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($row['role']) ?></td>
                                        <td class="px-3 py-2 text-yellow-600"><?= htmlspecialchars($row['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($rejectedUsers)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">No pending user approvals
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="grid gap-3 md:hidden max-h-96 overflow-y-auto">
                        <?php foreach ($rejectedUsers as $row): ?>
                            <div class="border rounded-lg p-3 shadow-sm bg-gray-50">
                                <p><span class="font-semibold">Name:</span> <?= htmlspecialchars($row['full_name']) ?></p>
                                <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($row['email']) ?></p>
                                <p><span class="font-semibold">Role:</span> <?= htmlspecialchars($row['role']) ?></p>
                                <p><span class="font-semibold">Status:</span> <span
                                        class="text-yellow-600"><?= htmlspecialchars($row['status']) ?></span></p>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($rejectedUsers)): ?>
                            <p class="text-center text-gray-500 py-4">No pending user approvals</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>