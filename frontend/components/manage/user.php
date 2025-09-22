<div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6">
                    <!-- Search -->
                    <?php include('../../components/document_search.php'); ?>
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto max-h-96 overflow-y-auto">
                        <table id="docTable" class="min-w-full text-sm border divide-y divide-gray-200">
                            <?php include('../../components/resident_table.php'); ?>
                            <tbody>
                                <?php foreach ($users as $row): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-3 py-2 whitespace-nowrap sticky left-0 bg-white z-10">
                                            <?= htmlspecialchars($row['full_name']) ?>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap"><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($row['role']) ?></td>
                                        <td class="px-3 py-2 text-yellow-600"><?= htmlspecialchars($row['status']) ?></td>
                                        <td class="px-3 py-2">
                                            <?= htmlspecialchars(date("F j, Y", strtotime($row['created_at']))) ?></td>
                                        <td class="px-3 py-2 text-center space-x-2">
                                            <button onclick="viewUser(<?= $row['user_id'] ?>)"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                                View
                                            </button>
                                            <?php if ($row['status'] === 'Pending'): ?>
                                                <button onclick="remindUser('<?= htmlspecialchars($row['email']) ?>')"
                                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-2 py-1 rounded text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            <button onclick="editUser(<?= $row['user_id'] ?>)"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                                Edit
                                            </button>
                                            <?php if ($row['status'] === 'Rejected' && $row['is_archived'] == 1): ?>
                                                <!-- Restore Button -->
                                                <button onclick="restoreUser(<?= $row['user_id'] ?>)"
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                                    Restore
                                                </button>
                                            <?php else: ?>
                                                <!-- Delete (Archive) Button -->
                                                <button onclick="deleteUser(<?= $row['user_id'] ?>)"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                    Delete
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="grid gap-3 md:hidden max-h-96 overflow-y-auto">
                        <?php foreach ($users as $row): ?>
                            <div class="border rounded-lg p-2 shadow-sm bg-gray-50">
                                <p><span class="font-semibold">Name:</span> <?= htmlspecialchars($row['full_name']) ?></p>
                                <p><span class="font-semibold">Email:</span>
                                    <?= htmlspecialchars($row['email']) ?></p>
                                <p><span class="font-semibold">Role:</span> <?= htmlspecialchars($row['role']) ?></p>
                                <p><span class="font-semibold">Status:</span> <span
                                        class="text-yellow-600"><?= htmlspecialchars($row['status']) ?></span></p>
                                <p><span class="font-semibold">Requested:</span>
                                    <?= htmlspecialchars(date("F j, Y", strtotime($row['created_at']))) ?></p>
                                <div class="flex justify-end gap-2 mt-2">
                                    <button onclick="viewUser(<?= $row['user_id'] ?>)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        View
                                    </button>
                                    <button onclick="editUser(<?= $row['user_id'] ?>)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                        Edit
                                    </button>
                                    <?php if ($row['status'] === 'Rejected' && $row['is_archived'] == 1): ?>
                                        <!-- Restore Button -->
                                        <button onclick="restoreUser(<?= $row['user_id'] ?>)"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                            Restore
                                        </button>
                                    <?php else: ?>
                                        <!-- Delete (Archive) Button -->
                                        <button onclick="deleteUser(<?= $row['user_id'] ?>)"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($users)): ?>
                            <p class="text-center text-gray-500 py-4">No Residents</p>
                        <?php endif; ?>
                    </div>
                </div>