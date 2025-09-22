<?php
session_start();
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";
require_once "../../../backend/models/Repository.php";
require '../../../backend/config/db.php';

$pageTitle = "Admin | All Events List";
$pageDescription = "Manage Events for Barangay Poblacion Sur System";
include './admin-head.php';
$events = getAllEvents($pdo, null, 50);
?>

<body class="bg-gray-100">
    <?php include('../../components/DashNav.php'); ?>
    <main class="flex-1 p-3 sm:p-6 md:p-8 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto space-y-10">
            <section>
                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-3">📅 All Events</h1>
                <div class="bg-white shadow-md sm:shadow-xl rounded-xl p-4 sm:p-6">

                    <!-- Search -->
                    <?php include('../../components/document_search.php'); ?>

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto max-h-96 overflow-y-auto">
                        <table id="docTable" class="min-w-full text-sm border divide-y divide-gray-200">
                            <thead class="bg-gray-100 text-gray-700 sticky top-0 z-20">
                                <tr>
                                    <th class="px-3 py-2 text-left">Event Title</th>
                                    <th class="px-3 py-2 text-left">Description</th>
                                    <th class="px-3 py-2 text-left">Start</th>
                                    <th class="px-3 py-2 text-left">End</th>
                                    <th class="px-3 py-2 text-left">Location</th>
                                    <th class="px-3 py-2 text-center">Type</th>
                                    <th class="px-3 py-2 text-left">Audience</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                    <th class="px-3 py-2 text-left">Created At</th>
                                    <th class="px-3 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($events as $row): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td><?= htmlspecialchars($row['event_title']) ?></td>
                                        <td><?= htmlspecialchars($row['event_description']) ?></td>
                                        <td><?= date("M d, Y h:i A", strtotime($row['event_start'])) ?></td>
                                        <td><?= date("M d, Y h:i A", strtotime($row['event_end'])) ?></td>
                                        <td><?= htmlspecialchars($row['event_location']) ?></td>
                                        <td><?= htmlspecialchars($row['event_type']) ?></td>
                                        <td><?= htmlspecialchars($row['audience']) ?></td>
                                        <td><?= htmlspecialchars($row['status']) ?></td>
                                        <td><?= htmlspecialchars(date("F j, Y", strtotime($row['created_at']))) ?></td>

                                        <!-- Action Buttons -->
                                        <td class="space-x-1">
                                            <button
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs view-btn"
                                                data-event='<?= json_encode($row) ?>'>
                                                View
                                            </button>

                                            <button
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs edit-btn"
                                                data-event='<?= json_encode($row) ?>'>
                                                Edit
                                            </button>

                                            <?php if (!($row['status'] === 'Cancelled' && $row['is_archived'] == 1)): ?>
                                                <button
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs delete-btn"
                                                    data-id="<?= $row['event_id'] ?>">
                                                    Delete
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($events)): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-gray-500 py-4">No Events</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="grid gap-3 md:hidden max-h-96 overflow-y-auto">
                        <?php foreach ($events as $row): ?>
                            <div class="border rounded-lg p-3 shadow-sm bg-gray-50">
                                <p><span class="font-semibold">Title:</span> <?= htmlspecialchars($row['event_title']) ?>
                                </p>
                                <p><span class="font-semibold">Description:</span>
                                    <?= htmlspecialchars($row['event_description']) ?></p>
                                <p><span class="font-semibold">Start:</span>
                                    <?= date("F j, Y g:i A", strtotime($row['event_start'])) ?></p>
                                <p><span class="font-semibold">End:</span>
                                    <?= date("F j, Y g:i A", strtotime($row['event_end'])) ?></p>
                                <p><span class="font-semibold">Location:</span>
                                    <?= htmlspecialchars($row['event_location']) ?></p>
                                <p><span class="font-semibold">Type:</span> <?= htmlspecialchars($row['event_type']) ?></p>
                                <p><span class="font-semibold">Audience:</span> <?= htmlspecialchars($row['audience']) ?>
                                </p>
                                <p><span class="font-semibold">Status:</span>
                                    <span class="<?php
                                    echo match ($row['status']) {
                                        'Upcoming' => 'text-blue-600',
                                        'Ongoing' => 'text-green-600',
                                        'Completed' => 'text-gray-600',
                                        'Cancelled' => 'text-red-600',
                                        default => 'text-yellow-600'
                                    };
                                    ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </p>
                                <p><span class="font-semibold">Created:</span>
                                    <?= date("F j, Y", strtotime($row['created_at'])) ?></p>
                                <div class="flex justify-end space-x-2 mt-2">
                                    <button
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs view-btn"
                                        data-event='<?= json_encode($row) ?>'>
                                        View
                                    </button>

                                    <button
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs edit-btn"
                                        data-event='<?= json_encode($row) ?>'>
                                        Edit
                                    </button>

                                    <?php if ($row['status'] !== 'Archived'): ?>
                                        <button
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs delete-btn"
                                            onclick="confirmDelete('event', <?= $row['event_id'] ?>)">
                                            Delete
                                        </button>

                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($events)): ?>
                            <p class="text-center text-gray-500 py-4">No Events</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <!-- Event Modal -->
    <div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
            <button onclick="closeEventModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">✖</button>
            <h2 class="text-xl font-bold mb-4">Event Details</h2>
            <div id="eventDetails"></div>
        </div>
    </div>

    <script src="../../assets/js/Approval_Search.js"></script>
    <?php include('../../assets/modals/event_modal.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ===== View Event =====
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const eventData = JSON.parse(btn.dataset.event);

                document.getElementById('view_event_title').textContent = eventData.event_title;
                document.getElementById('view_event_description').textContent = eventData.event_description;
                document.getElementById('view_event_location').textContent = eventData.event_location;
                document.getElementById('view_event_schedule').textContent =
                    `${new Date(eventData.event_start).toLocaleString()} - ${new Date(eventData.event_end).toLocaleString()}`;
                document.getElementById('view_event_type').textContent = eventData.event_type;

                // ✅ Status
                const statusEl = document.getElementById('view_event_status');
                statusEl.textContent = eventData.status || "Unknown";
                statusEl.className = "font-medium " + (() => {
                    switch (eventData.status) {
                        case "Upcoming": return "text-blue-600";
                        case "Ongoing": return "text-green-600";
                        case "Completed": return "text-gray-600";
                        case "Cancelled": return "text-red-600";
                        default: return "text-yellow-600";
                    }
                })();

                // Event image
                const eventImg = document.getElementById('view_event_image');
                eventImg.src = eventData.event_image
                    ? `../uploads/events/${eventData.event_image}`
                    : '../../assets/images/home.jpg';

                eventImg.onerror = () => {
                    eventImg.src = `../../uploads/events/${eventData.event_image}`;
                    eventImg.onerror = () => {
                        eventImg.src = '../../assets/images/home.jpg';
                    };
                };


                // Attachment
                const attachmentLink = document.getElementById('view_event_attachment');
                if (eventData.attachment) {
                    attachmentLink.href = `../uploads/events/${eventData.attachment}`;
                    attachmentLink.textContent = "View Attachment";
                } else {
                    attachmentLink.href = "#";
                    attachmentLink.textContent = "No Attachment";
                }

                document.getElementById('viewEventModal').classList.remove('hidden', 'opacity-0');
                document.getElementById('viewEventModal').classList.add('flex');
            });
        });

        function closeViewEvent() {
            document.getElementById('viewEventModal').classList.add('hidden');
        }
        // ===== Edit Event =====
        // ===== Edit Event =====
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const eventData = JSON.parse(btn.dataset.event);

                // Fill fields
                document.getElementById('event_id').value = eventData.event_id;
                document.getElementById('event_title').value = eventData.event_title;
                document.getElementById('event_description').value = eventData.event_description;
                document.getElementById('event_location').value = eventData.event_location;
                document.getElementById('event_start').value = eventData.event_start.replace(' ', 'T').slice(0, 16);
                document.getElementById('event_end').value = eventData.event_end.replace(' ', 'T').slice(0, 16);
                document.getElementById('event_type').value = eventData.event_type;
                document.getElementById('status').value = eventData.status;
                document.getElementById('event_audience').value = eventData.audience;

                // Keep old image/attachment if not replaced
                document.getElementById('old_event_image').value = eventData.event_image || "";
                document.getElementById('old_attachment').value = eventData.attachment || "";

                // Change button to "Update"
                const saveBtn = document.querySelector('#eventForm button[type="submit"]');
                saveBtn.textContent = "Update";
                saveBtn.name = "update";

                // Show modal
                document.getElementById('eventFormModal').classList.remove('hidden');
                document.getElementById('eventFormModal').classList.add('flex');
            });
        });

        function closeEventForm() {
            // Reset form
            document.getElementById('eventForm').reset();
            document.getElementById('event_id').value = "";

            // Reset Save button back to "Save"
            const saveBtn = document.querySelector('#eventForm button[type="submit"]');
            saveBtn.textContent = "Save";
            saveBtn.name = "create";

            document.getElementById('eventFormModal').classList.add('hidden');
        }

    </script>
    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const eventId = btn.dataset.id;
                const url = "../../../backend/actions/event_crud.php?delete=" + eventId;

                Swal.fire({
                    title: "Are you sure?",
                    text: "This event will be archived and cancelled.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect with delete parameter
                        window.location.href = url;
                    }
                });
            });
        });
    </script>


</body>

</html>