<?php
session_start();
// Specify which roles are allowed on this page
$allowedRoles = ['Admin']; 

// Optional: override default allowed status
$allowedStatus = ['Approved'];

// Include the reusable auth check
require_once "../../../backend/auth/auth_check.php";

include '../../../backend/config/db.php';

// Fetch all non-deleted announcements
$stmt = $pdo->prepare("
    SELECT * FROM announcements 
    WHERE is_deleted = 0 
    ORDER BY FIELD(priority, 'Urgent','High','Normal','Low'), created_at DESC
");
$stmt->execute();
$Announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = "Admin | Manage Announcements";
$pageDescription = "Manage announcements for Barangay Poblacion Sur System";
include 'admin-head.php';
?>

<body class="bg-gray-100">
<?php include '../../components/DashNav.php'; ?>

<main class="w-full p-8 md:p-10">
    <section id="AnnouncementManagement" class="container mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-indigo-700">📢 Manage Barangay Announcements</h1>
            <button onclick="openAnnouncementModal()" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Add Announcement
            </button>
        </div>

        <!-- Responsive Table -->
        <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Title</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold hidden sm:table-cell">Priority</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold hidden md:table-cell">Audience</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold hidden lg:table-cell">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold hidden xl:table-cell">Valid Until</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if(!empty($Announcements)): ?>
                        <?php foreach($Announcements as $announcement): ?>
                            <?php
                            $priorityColors = [
                                'Urgent' => 'bg-red-600 text-white',
                                'High'   => 'bg-orange-500 text-white',
                                'Normal' => 'bg-blue-500 text-white',
                                'Low'    => 'bg-gray-400 text-white'
                            ];
                            $priorityClass = $priorityColors[$announcement['priority']] ?? 'bg-gray-400 text-white';
                            ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 break-words"><?= htmlspecialchars($announcement['announcement_title']) ?></td>
                                <td class="px-4 py-2 hidden sm:table-cell">
                                    <span class="px-2 py-1 text-xs rounded <?= $priorityClass ?>">
                                        <?= $announcement['priority'] ?>
                                    </span>
                                </td>
                                <td class="px-4 py-2 hidden md:table-cell"><?= htmlspecialchars($announcement['audience']) ?></td>
                                <td class="px-4 py-2 hidden lg:table-cell"><?= htmlspecialchars($announcement['status']) ?></td>
                                <td class="px-4 py-2 hidden xl:table-cell"><?= $announcement['valid_until'] ?? 'N/A' ?></td>
                                <td class="px-4 py-2 text-center space-x-1 flex justify-center flex-wrap gap-1">
                                    <button onclick="openAnnouncementModal(<?= $announcement['announcement_id'] ?>)" 
                                            class="bg-yellow-400 px-2 py-1 rounded text-white hover:bg-yellow-500 text-xs sm:text-sm">Edit</button>
                                    <button onclick="deleteAnnouncement(<?= $announcement['announcement_id'] ?>)" 
                                            class="bg-red-600 px-2 py-1 rounded text-white hover:bg-red-700 text-xs sm:text-sm">Delete</button>
                                    <button onclick="archiveAnnouncement(<?= $announcement['announcement_id'] ?>)" 
                                            class="bg-gray-400 px-2 py-1 rounded text-white hover:bg-gray-500 text-xs sm:text-sm">Archive</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center px-4 py-6 text-gray-500">No Announcements Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- Modal for Create/Edit -->
<div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 relative">
        <button onclick="closeAnnouncementModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <form id="announcementForm" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="announcement_id" id="announcement_id">
            <div>
                <label class="block text-gray-700 mb-1">Title</label>
                <input type="text" name="announcement_title" id="announcement_title" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Content</label>
                <textarea name="announcement_content" id="announcement_content" class="w-full border px-3 py-2 rounded" rows="4"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-1">Priority</label>
                    <select name="priority" id="priority" class="w-full border px-3 py-2 rounded">
                        <option value="Urgent">Urgent</option>
                        <option value="High">High</option>
                        <option value="Normal" selected>Normal</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Audience</label>
                    <select name="audience" id="audience" class="w-full border px-3 py-2 rounded">
                        <option value="Public">Public</option>
                        <option value="Residents">Residents</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-1">Status</label>
                    <input type="text" name="status" id="status" class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Valid Until</label>
                    <input type="datetime-local" name="valid_until" id="valid_until" class="w-full border px-3 py-2 rounded">
                </div>
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Attachment</label>
                <input type="file" name="attachment" id="attachment" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Save Announcement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const announcements = <?= json_encode($Announcements) ?>;

function openAnnouncementModal(id = null) {
    document.getElementById('announcementForm').reset();
    if(id) {
        const a = announcements.find(x => x.announcement_id == id);
        if(a) {
            document.getElementById('announcement_id').value = a.announcement_id;
            document.getElementById('announcement_title').value = a.announcement_title;
            document.getElementById('announcement_content').value = a.announcement_content;
            document.getElementById('priority').value = a.priority;
            document.getElementById('audience').value = a.audience;
            document.getElementById('status').value = a.status;
            if(a.valid_until) document.getElementById('valid_until').value = a.valid_until.replace(' ', 'T');
        }
    }
    document.getElementById('announcementModal').classList.remove('hidden');
    document.getElementById('announcementModal').classList.add('flex');
}

function closeAnnouncementModal() {
    document.getElementById('announcementModal').classList.add('hidden');
    document.getElementById('announcementModal').classList.remove('flex');
}

document.getElementById('announcementForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../../../backend/announcements/save_announcement.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
});

function deleteAnnouncement(id) {
    if(confirm("Are you sure you want to delete this announcement?")) {
        fetch(`../../../backend/announcements/delete_announcement.php?id=${id}`)
            .then(res => res.json())
            .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }
}

function archiveAnnouncement(id) {
    if(confirm("Archive this announcement?")) {
        fetch(`../../../backend/announcements/archive_announcement.php?id=${id}`)
            .then(res => res.json())
            .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }
}
</script>

</body>
</html>
