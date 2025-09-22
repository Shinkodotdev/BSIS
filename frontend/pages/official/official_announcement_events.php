<?php
include('official-head.php');
include('../../components/DashNav.php');
include('../../../backend/config/db.php');

/* ✅ Fetch Announcements with Author Info (via users + user_details) */
$stmtA = $pdo->prepare("
    SELECT a.*,
           ud.f_name, ud.m_name, ud.l_name, ud.ext_name,
           u.role
    FROM announcements a
    LEFT JOIN users u ON a.author_id = u.user_id
    LEFT JOIN user_details ud ON u.user_id = ud.user_id
    WHERE a.is_archived = 0 
      AND a.audience IN ('Public','Officials')
      AND (a.valid_until IS NULL OR a.valid_until >= NOW())
    ORDER BY FIELD(a.priority, 'Urgent','High','Normal','Low'), a.created_at DESC
");
$stmtA->execute();
$Announcements = $stmtA->fetchAll(PDO::FETCH_ASSOC);

/* ✅ Fetch Events (Public + Resident + Official) */
$stmtE = $pdo->prepare("
    SELECT e.*, DATE_ADD(e.event_end, INTERVAL 3 DAY) AS keep_until
    FROM events e
    WHERE e.is_archived = 0
      AND (e.event_start IS NULL OR e.event_end >= DATE_SUB(NOW(), INTERVAL 3 DAY))
      AND e.audience IN ('Public','Officials')
    ORDER BY e.event_start ASC
");
$stmtE->execute();
$Events = $stmtE->fetchAll(PDO::FETCH_ASSOC);
?>


<body class="bg-gray-100">
    <main class="pt-24 px-4 sm:px-6 lg:px-10 space-y-12">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold text-center text-indigo-700 mb-10">📢 Announcement & 🎉 Events</h1>

            <!-- Add Buttons -->
            <div class="flex justify-end mb-4 gap-2">
                <button onclick="openAnnouncementForm()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ Add Announcement</button>
                <button onclick="openEventForm()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Add Event</button>
            </div>

            <!-- Announcements Section -->
            <section id="Announcements" class="mb-16">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">📢 Barangay Announcements</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (!empty($Announcements)): ?>
                        <?php foreach ($Announcements as $a): ?>
                            <?php
                            $image = !empty($a['announcement_image'])
                                ? '../../uploads/announcement/' . htmlspecialchars($a['announcement_image'])
                                : '../../assets/images/home.jpg';

                            $priorityColors = [
                                'Urgent' => 'bg-red-600 text-white',
                                'High'   => 'bg-orange-500 text-white',
                                'Normal' => 'bg-blue-500 text-white',
                                'Low'    => 'bg-gray-400 text-white'
                            ];
                            $priorityIcons = [
                                'Urgent' => 'fa-triangle-exclamation',
                                'High'   => 'fa-arrow-up',
                                'Normal' => 'fa-minus',
                                'Low'    => 'fa-arrow-down',
                            ];

                            // Author display
                            if ($a['role'] === 'Admin') {
                                $authorName = "Admin";
                            } else {
                                $authorName = trim($a['f_name'] . ' ' . ($a['m_name'] ? $a['m_name'][0] . '.' : '') . ' ' . $a['l_name'] . ' ' . $a['ext_name']);
                            }
                            ?>
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col hover:shadow-lg transition relative">
                                <img src="<?= $image ?>"
                                    alt="<?= htmlspecialchars($a['announcement_title']) ?>"
                                    class="w-full h-40 object-cover rounded-lg mb-3"
                                    onerror="this.onerror=null;this.src='../../uploads/images/default.jpg';">

                                <!-- Priority -->
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full <?= $priorityColors[$a['priority']] ?? 'bg-gray-400 text-white' ?> mb-2">
                                    <i class="fa-solid <?= $priorityIcons[$a['priority']] ?? 'fa-circle' ?>"></i>
                                    <?= htmlspecialchars($a['priority']) ?>
                                </span>

                                <!-- Title -->
                                <h2 class="text-lg font-semibold mb-1"><?= htmlspecialchars($a['announcement_title']) ?></h2>

                                <!-- Location -->
                                <?php if (!empty($a['announcement_location'])): ?>
                                    <p class="text-sm text-gray-500 mb-1">📍 <?= htmlspecialchars($a['announcement_location']) ?></p>
                                <?php endif; ?>

                                <!-- Content -->
                                <p class="text-gray-600 text-sm flex-grow"><?= nl2br(htmlspecialchars(substr($a['announcement_content'], 0, 120))) ?>...</p>

                                <!-- Author + Status -->
                                <div class="text-xs text-gray-500 mt-2">
                                    👤 <?= htmlspecialchars($authorName) ?> |
                                    <span class="<?= $a['status'] === 'Published' ? 'text-green-600' : 'text-gray-600' ?>">
                                        <?= htmlspecialchars($a['status']) ?>
                                    </span>
                                </div>

                                <!-- VIEW Edit/Archive Buttons -->
                                <div class="flex gap-2 mt-2 justify-end">
                                    <button onclick='openViewAnnouncement(<?= json_encode($a) ?>)'
                                        class="text-white bg-indigo-500 px-2 py-1 rounded hover:bg-indigo-600 text-xs">View</button>
                                    <button onclick='openAnnouncementForm(<?= json_encode($a) ?>)'
                                        class="text-white bg-yellow-500 px-2 py-1 rounded hover:bg-yellow-600 text-xs">Edit</button>
                                    <button onclick="confirmDelete('announcement', <?= $a['announcement_id'] ?>)"
                                        class="text-white bg-red-500 px-2 py-1 rounded hover:bg-red-600 text-xs">Archive</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center shadow-sm p-6 bg-white rounded-lg">
                            <h5 class="text-xl font-semibold text-gray-700">No Announcements Found</h5>
                            <p class="text-gray-500">Check back later for updates</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Events Section -->
            <section id="Events">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">🎉 Barangay Events</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (!empty($Events)): ?>
                        <?php foreach ($Events as $e): ?>
                            <?php
                            $image = !empty($e['event_image'])
                                ? '../uploads/' . htmlspecialchars($e['event_image'])
                                : '../../assets/images/home.jpg';
                            $now = new DateTime();
                            $event_end = new DateTime($e['event_end']);
                            $recent_threshold = (clone $event_end)->modify('+3 days');
                            $is_recent = $now > $event_end && $now <= $recent_threshold;
                            ?>
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col hover:shadow-lg transition relative">
                                <img src="<?= $image ?>"
                                    alt="<?= htmlspecialchars($e['event_title']) ?>"
                                    class="w-full h-40 object-cover rounded-lg mb-3"
                                    onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">

                                <h2 class="text-lg font-semibold mb-2"><?= htmlspecialchars($e['event_title']) ?>
                                    <?php if ($is_recent): ?><span class="text-sm text-gray-500">(Recent)</span><?php endif; ?>
                                </h2>

                                <p class="text-sm text-gray-600 mb-1">📅 <?= date("M d, Y h:i A", strtotime($e['event_start'])) ?> - <?= date("h:i A", strtotime($e['event_end'])) ?></p>
                                <p id="countdown-<?= $e['event_id'] ?>" class="text-sm font-semibold <?= $is_recent ? 'text-gray-400' : 'text-red-600' ?> mb-2"></p>
                                <p class="text-sm text-gray-600 mb-2">📍 <?= htmlspecialchars($e['event_location']) ?></p>
                                <p class="text-gray-500 text-sm flex-grow"><?= nl2br(htmlspecialchars(mb_substr($e['event_description'], 0, 100))) ?>...</p>

                                <!-- Edit/Archive Buttons -->
                                <div class="flex gap-2 mt-2 justify-end">
                                    <button onclick='openViewEvent(<?= json_encode($e) ?>)'
                                        class="text-white bg-indigo-500 px-2 py-1 rounded hover:bg-indigo-600 text-xs">View</button>
                                    <button onclick='openEventForm(<?= json_encode($e) ?>)'
                                        class="text-white bg-yellow-500 px-2 py-1 rounded hover:bg-yellow-600 text-xs">Edit</button>
                                    <button onclick="confirmDelete('event', <?= $e['event_id'] ?>)"
                                        class="text-white bg-red-500 px-2 py-1 rounded hover:bg-red-600 text-xs">Archive</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center shadow-sm p-6 bg-white rounded-lg">
                            <h5 class="text-xl font-semibold text-gray-700">No Events Found</h5>
                            <p class="text-gray-500">Check back later for updates</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <?php include('../../assets/modals/announcement_modal.php'); ?>
    <?php include('../../assets/modals/event_modal.php'); ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(type, id) {
            let url = "";
            if (type === "announcement") {
                url = "../../../backend/actions/announcement_crud.php?delete=" + id;
            } else if (type === "event") {
                url = "../../../backend/actions/event_crud.php?delete=" + id;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "This will archive the " + type + " and move it out of the active list.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e3342f",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, archive it"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
        const announcements = <?= json_encode($Announcements) ?>;
        const events = <?= json_encode($Events) ?>;

        function openAnnouncementForm(a = null) {
            document.getElementById("announcementFormModal").classList.remove("hidden");
            document.getElementById("announcementFormModal").classList.add("flex");

            if (a) {
                document.getElementById("announcement_id").value = a.announcement_id;
                document.getElementById("announcement_title").value = a.announcement_title;
                document.getElementById("announcement_content").value = a.announcement_content;
                document.getElementById("priority").value = a.priority;
                document.getElementById("audience").value = a.audience;
            } else {
                document.getElementById("announcementForm").reset();
                document.getElementById("announcement_id").value = "";
            }
        }

        function closeAnnouncementForm() {
            document.getElementById("announcementFormModal").classList.add("hidden");
            document.getElementById("announcementFormModal").classList.remove("flex");
        }

        function openEventForm(e = null) {
            document.getElementById("eventFormModal").classList.remove("hidden");
            document.getElementById("eventFormModal").classList.add("flex");

            if (e) {
                document.getElementById("event_id").value = e.event_id;
                document.getElementById("event_title").value = e.event_title;
                document.getElementById("event_description").value = e.event_description;
                document.getElementById("event_start").value = e.event_start.replace(' ', 'T');
                document.getElementById("event_end").value = e.event_end.replace(' ', 'T');
                document.getElementById("event_location").value = e.event_location;
                document.getElementById("event_type").value = e.event_type;
                document.getElementById("event_audience").value = e.audience;
            } else {
                document.getElementById("eventForm").reset();
                document.getElementById("event_id").value = "";
            }
        }

        function closeEventForm() {
            document.getElementById("eventFormModal").classList.add("hidden");
            document.getElementById("eventFormModal").classList.remove("flex");
        }

        function openViewAnnouncement(a) {
            document.getElementById("viewAnnouncementModal").classList.remove("hidden");
            document.getElementById("viewAnnouncementModal").classList.add("flex");

            document.getElementById("view_announcement_title").innerText = a.announcement_title;
            document.getElementById("view_announcement_content").innerText = a.announcement_content;
            document.getElementById("view_announcement_location").innerText = a.announcement_location || "N/A";
            document.getElementById("view_announcement_author").innerText = a.f_name + " " + (a.l_name || "");
            document.getElementById("view_announcement_priority").innerText = a.priority;
            document.getElementById("view_announcement_image").src = a.announcement_image || "../../assets/images/home.jpg";
            document.getElementById("view_announcement_attachment").href = a.attachment || "#";
        }

        function closeViewAnnouncement() {
            document.getElementById("viewAnnouncementModal").classList.add("hidden");
            document.getElementById("viewAnnouncementModal").classList.remove("flex");
        }

        function openViewEvent(e) {
            document.getElementById("viewEventModal").classList.remove("hidden");
            document.getElementById("viewEventModal").classList.add("flex");

            document.getElementById("view_event_title").innerText = e.event_title;
            document.getElementById("view_event_description").innerText = e.event_description;
            document.getElementById("view_event_location").innerText = e.event_location || "N/A";
            document.getElementById("view_event_type").innerText = e.event_type || "General";
            document.getElementById("view_event_schedule").innerText =
                new Date(e.event_start).toLocaleString() + " - " + new Date(e.event_end).toLocaleString();
            document.getElementById("view_event_image").src = e.event_image || "../../assets/images/home.jpg";
            document.getElementById("view_event_attachment").href = e.attachment || "#";
        }

        function closeViewEvent() {
            document.getElementById("viewEventModal").classList.add("hidden");
            document.getElementById("viewEventModal").classList.remove("flex");
        }
    </script>
</body>

</html>