<tr class="hover:bg-gray-50 transition">
    <td class="px-3 py-2 whitespace-nowrap sticky left-0 bg-white z-10">
        <?= htmlspecialchars($row['full_name']) ?>
    </td>
    <td class="px-3 py-2 whitespace-nowrap"><?= htmlspecialchars($row['email']) ?></td>
    <td class="px-3 py-2"><?= htmlspecialchars($row['role']) ?></td>
    <td class="px-3 py-2 text-yellow-600"><?= htmlspecialchars($row['status']) ?></td>
    <td class="px-3 py-2"><?= htmlspecialchars(date("F j, Y", strtotime($row['created_at']))) ?></td>
    <td class="px-3 py-2 text-center space-x-2">
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
    </td>
</tr>