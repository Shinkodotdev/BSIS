<section>
    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">📑 Pending Document Requests
        Approval
    </h1>
    <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6">

        <!-- Search -->
        <?php include('../../components/document_search.php'); ?>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto max-h-96 overflow-y-auto">
            <table id="docTable" class="min-w-full text-sm border divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="px-3 py-2 text-left">Document</th>
                        <th class="px-3 py-2 text-left">Purpose</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Requested</th>
                        <th class="px-3 py-2 text-left">Processed By</th>
                        <th class="px-3 py-2 text-left">Processed At</th>
                        <th class="px-3 py-2 text-left">Remarks</th>
                        <th class="px-3 py-2 text-left">Attachment</th>
                        <th class="px-3 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingRequests as $row): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-2"><?= htmlspecialchars($row['user_name']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['document_name']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['purpose']) ?></td>
                            <td class="px-3 py-2 text-yellow-600"><?= htmlspecialchars($row['status']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['requested_at']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['processed_by'] ?? 'Admin') ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars(date("F j, Y", strtotime($row['processed_at']))) ?>
                            </td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['remarks'] ?? 'N/A') ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['attachment_path'] ?? 'N/A') ?></td>

                            <td class="px-3 py-2 text-center space-x-2">
                                <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Approved')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-2 sm:px-3 py-1 rounded text-xs">Approve</button>
                                <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Denied')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 py-1 rounded text-xs">Deny</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="grid gap-3 md:hidden max-h-96 overflow-y-auto">
            <?php foreach ($pendingRequests as $row): ?>
                <div class="border rounded-lg p-2 shadow-sm bg-gray-50">
                    <p><span class="font-semibold">Name:</span> <?= htmlspecialchars($row['user_name']) ?></p>
                    <p><span class="font-semibold">Document:</span>
                        <?= htmlspecialchars($row['document_name']) ?></p>
                    <p><span class="font-semibold">Purpose:</span> <?= htmlspecialchars($row['purpose']) ?></p>
                    <p><span class="font-semibold">Status:</span> <span
                            class="text-yellow-600"><?= htmlspecialchars($row['status']) ?></span></p>
                    <p><span class="font-semibold">Requested:</span>
                        <?= htmlspecialchars($row['requested_at']) ?></p>
                    <div class="flex justify-end gap-2 mt-2">
                        <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Approved')"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</button>
                        <button onclick="updateRequest(<?= $row['request_id'] ?>, 'Denied')"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Deny</button>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($pendingRequests)): ?>
                <p class="text-center text-gray-500 py-4">No pending requests</p>
            <?php endif; ?>
        </div>
    </div>
</section>