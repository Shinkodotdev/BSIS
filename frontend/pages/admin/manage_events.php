
<?php
session_start();
// Specify which roles are allowed on this page
$allowedRoles = ['Admin']; 

// Optional: override default allowed status
$allowedStatus = ['Approved'];

// Include the reusable auth check
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/config/db.php"; // adjust path to your db.php



// Fetch all non-deleted events for management
$stmt = $pdo->prepare("SELECT * FROM events WHERE is_deleted = 0 ORDER BY event_start DESC");
$stmt->execute();
$Events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = "Admin | Manage Events";
$pageDescription = "Manage events for Barangay Poblacion Sur System";
include 'admin-head.php';
?>

<body class="bg-gray-100">
    <?php include '../../components/DashNav.php';?>
<main>
    <section id="EventManagement" class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-indigo-700">📝 Manage Barangay Events</h1>
            <button onclick="openEventModal()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Add Event
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (!empty($Events)): ?>
                <?php foreach ($Events as $event): ?>
                    <?php
                    $image = !empty($event['event_image']) ? 'uploads/' . htmlspecialchars($event['event_image']) : '../../assets/images/home.jpg';
                    ?>
                    <div class="bg-white shadow-md rounded-lg p-4 flex flex-col hover:shadow-lg transition">
                        <img src="<?= $image ?>" class="w-full h-40 object-cover rounded-lg mb-3" 
                             onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">
                        <h2 class="text-lg font-semibold mb-2"><?= htmlspecialchars($event['event_title']) ?></h2>
                        <p class="text-sm text-gray-600 mb-1">
                            📅 <?= date("M d, Y h:i A", strtotime($event['event_start'])) ?> - <?= date("h:i A", strtotime($event['event_end'])) ?>
                        </p>
                        <p class="text-sm text-gray-600 mb-2">📍 <?= htmlspecialchars($event['event_location']) ?></p>
                        <div class="mt-auto flex justify-between">
                            <button onclick="openEventModal(<?= $event['event_id'] ?>)" 
                                    class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500 text-sm">Edit</button>
                            <button onclick="deleteEvent(<?= $event['event_id'] ?>)" 
                                    class="bg-red-600 px-3 py-1 rounded text-white hover:bg-red-700 text-sm">Delete</button>
                            <button onclick="archiveEvent(<?= $event['event_id'] ?>)" 
                                    class="bg-gray-400 px-3 py-1 rounded text-white hover:bg-gray-500 text-sm">Archive</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center shadow-sm p-6 bg-white rounded-lg">
                    <h5 class="text-xl font-semibold text-gray-700">No Events Found</h5>
                    <p class="text-gray-500">Create a new event using the Add Event button</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Modal for Create/Edit -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
        <button onclick="closeEventModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <form id="eventForm" enctype="multipart/form-data">
            <input type="hidden" name="event_id" id="event_id">
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Event Title</label>
                <input type="text" name="event_title" id="event_title" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea name="event_description" id="event_description" class="w-full border px-3 py-2 rounded" rows="4"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Start Date & Time</label>
                <input type="datetime-local" name="event_start" id="event_start" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">End Date & Time</label>
                <input type="datetime-local" name="event_end" id="event_end" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Location</label>
                <input type="text" name="event_location" id="event_location" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Audience</label>
                <select name="audience" id="audience" class="w-full border px-3 py-2 rounded">
                    <option value="Public">Public</option>
                    <option value="Residents">Residents</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Event Image</label>
                <input type="file" name="event_image" id="event_image" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Save Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEventModal(id = null) {
    document.getElementById('eventForm').reset();
    if(id) {
        const e = <?= json_encode($Events) ?>.find(ev => ev.event_id == id);
        if(e) {
            document.getElementById('event_id').value = e.event_id;
            document.getElementById('event_title').value = e.event_title;
            document.getElementById('event_description').value = e.event_description;
            document.getElementById('event_start').value = e.event_start.replace(' ', 'T');
            document.getElementById('event_end').value = e.event_end.replace(' ', 'T');
            document.getElementById('event_location').value = e.event_location;
            document.getElementById('audience').value = e.audience;
        }
    }
    document.getElementById('eventModal').classList.remove('hidden');
    document.getElementById('eventModal').classList.add('flex');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.add('hidden');
    document.getElementById('eventModal').classList.remove('flex');
}

document.getElementById('eventForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../../../backend/events/save_event.php', {
        method: 'POST',
        body: formData
    }).then(res => res.json()).then(data => {
        if(data.success) location.reload();
        else alert(data.message);
    });
});

function deleteEvent(id) {
    if(confirm("Are you sure you want to delete this event?")) {
        fetch(`../../../backend/events/delete_event.php?id=${id}`)
        .then(res => res.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }
}

function archiveEvent(id) {
    if(confirm("Archive this event?")) {
        fetch(`../../../backend/events/archive_event.php?id=${id}`)
        .then(res => res.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }
}
</script>
</body>
</html>
