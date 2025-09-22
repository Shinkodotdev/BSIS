<?php
include('resident-head.php');
include('../../components/DashNav.php');
include('../../../backend/config/db.php');

/* ✅ Fetch Announcements (Public + Resident) */
$stmtA = $pdo->prepare("
    SELECT * FROM announcements 
    WHERE is_deleted = 0 
      AND audience IN ('Public','Resident')
      AND (valid_until IS NULL OR valid_until >= NOW())
    ORDER BY FIELD(priority, 'Urgent','High','Normal','Low'), created_at DESC
");
$stmtA->execute();
$Announcements = $stmtA->fetchAll(PDO::FETCH_ASSOC);

/* ✅ Fetch Events (Public + Resident) */
$stmtE = $pdo->prepare("
    SELECT *, DATE_ADD(event_end, INTERVAL 3 DAY) AS keep_until
    FROM events
    WHERE is_deleted = 0
      AND (
          event_start IS NULL 
          OR event_end >= DATE_SUB(NOW(), INTERVAL 3 DAY)
      )
      AND audience IN ('Public','Resident')
    ORDER BY event_start ASC
");
$stmtE->execute();
$Events = $stmtE->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="bg-gray-100">
    <main class="pt-24 px-4 sm:px-6 lg:px-10 space-y-12">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold text-center text-indigo-700 mb-10">📢 Announcement & 🎉 Events</h1>

            <!-- ✅ Announcements Section -->
            <section id="Announcements" class="mb-16">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">📢 Barangay Announcements</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (!empty($Announcements)): ?>
                        <?php foreach ($Announcements as $a): ?>
                            <?php
                            $image = !empty($a['announcement_image'])
                                ? '../../uploads/images/' . htmlspecialchars($a['announcement_image'])
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
                            ?>
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col cursor-pointer hover:shadow-lg transition"
                                 onclick="openAnnouncementModal(<?= $a['announcement_id'] ?>)">
                                <img src="<?= $image ?>" 
                                     alt="<?= htmlspecialchars($a['announcement_title']) ?>" 
                                     class="w-full h-40 object-cover rounded-lg mb-3"
                                     onerror="this.onerror=null;this.src='../../uploads/images/default.jpg';">

                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full <?= $priorityColors[$a['priority']] ?? 'bg-gray-400 text-white' ?> mb-2">
                                    <i class="fa-solid <?= $priorityIcons[$a['priority']] ?? 'fa-circle' ?>"></i>
                                    <?= htmlspecialchars($a['priority']) ?>
                                </span>

                                <h2 class="text-lg font-semibold mb-2"><?= htmlspecialchars($a['announcement_title']) ?></h2>
                                <p class="text-gray-600 text-sm flex-grow"><?= nl2br(htmlspecialchars(substr($a['announcement_content'], 0, 120))) ?>...</p>
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

            <!-- ✅ Events Section -->
            <section id="Events">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">🎉 Barangay Events</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (!empty($Events)): ?>
                        <?php foreach ($Events as $e): ?>
                            <?php
                            $image = !empty($e['event_image'])
                                ? 'uploads/' . htmlspecialchars($e['event_image'])
                                : '../../assets/images/home.jpg';
                            $now = new DateTime();
                            $event_end = new DateTime($e['event_end']);
                            $recent_threshold = (clone $event_end)->modify('+3 days');
                            $is_recent = $now > $event_end && $now <= $recent_threshold;
                            ?>
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col cursor-pointer hover:shadow-lg transition"
                                 onclick="openEventModal(<?= $e['event_id'] ?>)">
                                <img src="<?= $image ?>" 
                                     alt="<?= htmlspecialchars($e['event_title']) ?>" 
                                     class="w-full h-40 object-cover rounded-lg mb-3"
                                     onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">

                                <h2 class="text-lg font-semibold mb-2">
                                    <?= htmlspecialchars($e['event_title']) ?>
                                    <?php if ($is_recent): ?><span class="text-sm text-gray-500">(Recent)</span><?php endif; ?>
                                </h2>
                                <p class="text-sm text-gray-600 mb-1">📅 <?= date("M d, Y h:i A", strtotime($e['event_start'])) ?> - <?= date("h:i A", strtotime($e['event_end'])) ?></p>
                                <p id="countdown-<?= $e['event_id'] ?>" class="text-sm font-semibold <?= $is_recent ? 'text-gray-400' : 'text-red-600' ?> mb-2"></p>
                                <p class="text-sm text-gray-600 mb-2">📍 <?= htmlspecialchars($e['event_location']) ?></p>
                                <p class="text-gray-500 text-sm flex-grow"><?= nl2br(htmlspecialchars(mb_substr($e['event_description'], 0, 100))) ?>...</p>
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

    <!-- ✅ Announcement Modal -->
    <div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg w-full sm:w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
            <button onclick="closeAnnouncementModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
            <div id="modalContentAnnouncement"></div>
        </div>
    </div>

    <!-- ✅ Event Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg w-full sm:w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
            <button onclick="closeEventModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
            <div id="modalContentEvent"></div>
        </div>
    </div>

    <script>
        const announcements = <?= json_encode($Announcements) ?>;
        const events = <?= json_encode($Events) ?>;

        // ✅ Announcement Modal
        function openAnnouncementModal(id) {
            const a = announcements.find(x => x.announcement_id == id);
            if (!a) return;
            const image = a.announcement_image ? "../../uploads/images/" + a.announcement_image : "../../assets/images/home.jpg";
            document.getElementById("modalContentAnnouncement").innerHTML = `
                <img src="${image}" class="w-full h-64 object-cover rounded-lg mb-4" onerror="this.onerror=null;this.src='../../uploads/images/default.jpg';">
                <h2 class="text-2xl font-bold mb-2">${a.announcement_title}</h2>
                <p class="text-gray-700 mb-4">${a.announcement_content}</p>
                <p><strong>Status:</strong> ${a.status}</p>
                <p><strong>Author:</strong> ${a.author_id ?? 'Unknown'}</p>
                <p><strong>Priority:</strong> ${a.priority}</p>
                <p><strong>Audience:</strong> ${a.audience}</p>
                <p><strong>Posted:</strong> ${a.created_at}</p>
                ${a.valid_until ? `<p><strong>Valid Until:</strong> ${a.valid_until}</p>` : ""}
                ${a.announcement_location ? `<p><strong>Location:</strong> ${a.announcement_location}</p>` : ""}
                ${a.attachment ? `<p><a href="../../uploads/attachments/${a.attachment}" target="_blank" class="text-blue-600 underline">📎 View Attachment</a></p>` : ""}
            `;
            document.getElementById("announcementModal").classList.remove("hidden");
            document.getElementById("announcementModal").classList.add("flex");
        }
        function closeAnnouncementModal() {
            document.getElementById("announcementModal").classList.add("hidden");
            document.getElementById("announcementModal").classList.remove("flex");
        }

        // ✅ Event Modal
        function updateCountdown(startTime, endTime, elementId) {
            const el = document.getElementById(elementId);
            if (!el) return;
            const interval = setInterval(() => {
                const now = new Date().getTime();
                const start = new Date(startTime).getTime();
                const end = new Date(endTime).getTime();
                let text = "";
                if (now < start) {
                    const diff = start - now;
                    text = `🟢 Upcoming in ${formatTime(diff)}`;
                } else if (now >= start && now <= end) {
                    const diff = end - now;
                    text = `🟡 Ongoing - ends in ${formatTime(diff)}`;
                } else if (now > end && now <= end + 3 * 24 * 60 * 60 * 1000) {
                    text = "🔵 Recent Event";
                    clearInterval(interval);
                } else {
                    text = "🔴 Event Expired";
                    clearInterval(interval);
                }
                el.textContent = text;
            }, 1000);

            function formatTime(ms) {
                const d = Math.floor(ms / (1000 * 60 * 60 * 24));
                const h = Math.floor((ms % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((ms % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((ms % (1000 * 60)) / 1000);
                return `${d}d ${h}h ${m}m ${s}s`;
            }
        }
        events.forEach(e => {
            updateCountdown(e.event_start, e.event_end, `countdown-${e.event_id}`);
        });

        function openEventModal(id) {
            const e = events.find(ev => ev.event_id == id);
            if (!e) return;
            const image = e.event_image ? "uploads/" + e.event_image : "../../assets/images/home.jpg";
            document.getElementById("modalContentEvent").innerHTML = `
                <img src="${image}" class="w-full h-64 object-cover rounded-lg mb-4" onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">
                <h2 class="text-2xl font-bold mb-2">${e.event_title}</h2>
                <p class="text-gray-700 mb-4">${e.event_description}</p>
                <p><strong>📅 Start:</strong> ${e.event_start}</p>
                <p><strong>⏰ End:</strong> ${e.event_end}</p>
                <p id="modal-countdown-${e.event_id}" class="text-red-600 font-semibold mb-2"></p>
                <p><strong>📍 Location:</strong> ${e.event_location}</p>
                <p><strong>🎯 Type:</strong> ${e.event_type}</p>
                ${e.attachment ? `<p><a href="uploads/${e.attachment}" target="_blank" class="text-blue-600 underline">📎 View Attachment</a></p>` : ""}
            `;
            updateCountdown(e.event_start, e.event_end, `modal-countdown-${e.event_id}`);
            document.getElementById("eventModal").classList.remove("hidden");
            document.getElementById("eventModal").classList.add("flex");
        }
        function closeEventModal() {
            document.getElementById("eventModal").classList.add("hidden");
            document.getElementById("eventModal").classList.remove("flex");
        }
    </script>
</body>
</html>
