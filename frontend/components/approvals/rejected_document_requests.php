<section>
                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">
                    📑 Rejected Document Requests
                </h1>
                <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6">
                    <!-- Search -->
                    <div class="mb-3 flex flex-col sm:flex-row justify-between gap-3">
                        <input id="approvedDocSearch" type="text" placeholder="Search users..."
                            class="w-full sm:w-1/3 border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto max-h-96 overflow-y-auto">
                        <table id="approvedDocTable" class="min-w-full text-sm border divide-y divide-gray-200">
                            <thead class="bg-gray-100 text-gray-700 sticky top-0 z-20">
                                <tr>
                                    <th class="px-3 py-2 text-left">Name</th>
                                    <th class="px-3 py-2 text-left">Document</th>
                                    <th class="px-3 py-2 text-left">Purpose</th>
                                    <th class="px-3 py-2 text-left">Rejected By</th>
                                    <th class="px-3 py-2 text-left">Rejected At</th>
                                    <th class="px-3 py-2 text-left">Requested</th>
                                    <th class="px-3 py-2 text-left">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rejectedRequests as $row): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-3 py-2"><?= htmlspecialchars($row['user_name']) ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($row['document_name']) ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($row['purpose']) ?></td>
                                        <td class="px-3 py-2 text-green-600">
                                            <?= htmlspecialchars($row['approved_by'] ?? 'Admin') ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?= htmlspecialchars(date("F j, Y g:i A", strtotime($row['processed_at']))) ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?= htmlspecialchars(date("F j, Y", strtotime($row['requested_at']))) ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?= htmlspecialchars($row['remarks'] ?? 'N/A') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Mobile Card View -->
                    <div class="grid gap-3 md:hidden max-h-96 overflow-y-auto">
                        <?php foreach ($rejectedRequests as $row): ?>
                            <div class="border rounded-lg p-2 shadow-sm bg-gray-50">
                                <p><span class="font-semibold">Name:</span> <?= htmlspecialchars($row['user_name']) ?></p>
                                <p><span class="font-semibold">Document:</span>
                                    <?= htmlspecialchars($row['document_name']) ?></p>
                                <p><span class="font-semibold">Purpose:</span> <?= htmlspecialchars($row['purpose']) ?></p>
                                <p><span class="font-semibold">Approved By:</span>
                                    <span class="text-green-600"><?= htmlspecialchars($row['approved_by'] ?? '-') ?></span>
                                </p>
                                <p><span class="font-semibold">Approved At:</span>
                                    <?= htmlspecialchars(date("F j, Y g:i A", strtotime($row['processed_at']))) ?></p>
                                <p><span class="font-semibold">Requested:</span>
                                    <?= htmlspecialchars(date("F j, Y", strtotime($row['requested_at']))) ?></p>
                                <p> <span class="font-semibold">Remarks:</span>
                                    <?= htmlspecialchars($row['remarks'] ?? 'N/A') ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($rejectedRequests)): ?>
                            <p class="text-center text-gray-500 py-4">No approved requests</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>