<section>
    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">
        👥 Pending User Account Approvals
    </h1>
    <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6">

        <!-- Search -->
        <div class="mb-3 flex flex-col sm:flex-row justify-between gap-3">
            <input id="userSearch" type="text" placeholder="Search users..."
                class="w-full sm:w-1/3 border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto max-h-96 overflow-y-auto">
            <table id="userTable" class="min-w-full text-sm border divide-y divide-gray-200">
                <?php include('../../components/resident_table.php'); ?>
                <tbody>
                    <?php foreach ($pendingUsers as $row): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-2 whitespace-nowrap sticky left-0 bg-white z-10">
                                <?= htmlspecialchars($row['full_name']) ?>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['role']) ?></td>
                            <td class="px-3 py-2 text-yellow-600"><?= htmlspecialchars($row['status']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars(date("F j, Y", strtotime($row['created_at']))) ?>
                            </td>
                            <td class="px-3 py-2 text-center space-x-2">
                                <button onclick="viewUser(<?= $row['user_id'] ?>)"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                    View
                                </button>
                                <!-- Reminder Bell Icon -->
                                <button onclick="remindUser('<?= htmlspecialchars($row['email']) ?>')"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-2 py-1 rounded text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pendingUsers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No pending user approvals</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="grid gap-3 md:hidden max-h-96 overflow-y-auto">
            <?php foreach ($pendingUsers as $row): ?>
                <div class="border rounded-lg p-3 shadow-sm bg-gray-50">
                    <p><span class="font-semibold">Name:</span> <?= htmlspecialchars($row['full_name']) ?></p>
                    <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($row['email']) ?></p>
                    <p><span class="font-semibold">Role:</span> <?= htmlspecialchars($row['role']) ?></p>
                    <p><span class="font-semibold">Status:</span>
                        <span class="text-yellow-600"><?= htmlspecialchars($row['status']) ?></span>
                    </p>
                    <div class="flex justify-end gap-2 mt-2">
                        <button onclick="viewUser(<?= $row['user_id'] ?>)"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                            View
                        </button>
                        <!-- Bell Icon for Reminder -->
                        <button onclick="remindUser('<?= htmlspecialchars($row['email']) ?>')"
                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-2 py-1 rounded text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        <?php if ($row['status'] === 'Rejected' && $row['is_archived'] == 1): ?>
                            <button onclick="restoreUser(<?= $row['user_id'] ?>)"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                Restore
                            </button>
                        <?php else: ?>
                            <button onclick="updateUser(<?= $row['user_id'] ?>, 'Approved')"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                Approve
                            </button>
                            <button onclick="updateUser(<?= $row['user_id'] ?>, 'Denied')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                Deny
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($pendingUsers)): ?>
                <p class="text-center text-gray-500 py-4">No pending user approvals</p>
            <?php endif; ?>
        </div>
    </div>
</section>