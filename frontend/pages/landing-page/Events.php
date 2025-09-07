<?php
include '../../components/Head.php';
include '../../components/Navbar.php';
include '../../../backend/config/db.php';
//THE DIFFERENCE BETWEEN PUBLIC AND RESIDENTS IS THAT IT IS ABLE ALSO TO NON_RESIDENT EVEN JUST PASSING BY THE SYSTEM
// ✅ Fetch events: upcoming, ongoing, or ended within the last 3 days
$stmt = $pdo->prepare("
    SELECT *, 
        DATE_ADD(event_end, INTERVAL 3 DAY) AS keep_until
    FROM events
    WHERE is_deleted = 0
    AND (
        event_start IS NULL 
        OR event_end >= DATE_SUB(NOW(), INTERVAL 3 DAY)
    )
        AND audience IN ('Public')
    ORDER BY event_start ASC
");
$stmt->execute();
$Events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body class="bg-gray-50">
    <main>
        <section id="Events">
            <div class="container mx-auto p-6">
                <h1 class="text-3xl font-bold mb-4 text-center text-indigo-700">🎉 Barangay Events</h1>
                <p class="text-gray-600 text-center mb-8">
                    Stay updated with upcoming activities and events in your barangay.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (!empty($Events)): ?>
                        <?php foreach ($Events as $event): ?>
                            <?php
                            $image = !empty($event['event_image'])
                                ? 'uploads/' . htmlspecialchars($event['event_image'])
                                : '../../assets/images/home.jpg';

                            $now = new DateTime();
                            $event_end = new DateTime($event['event_end']);
                            $recent_threshold = (clone $event_end)->modify('+3 days');
                            $is_recent = $now > $event_end && $now <= $recent_threshold;
                            ?>
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col cursor-pointer hover:shadow-lg transition"
                                onclick="openEventModal(<?= $event['event_id'] ?>)">

                                <!-- Image -->
                                <img src="<?= $image ?>"
                                    alt="<?= htmlspecialchars($event['event_title']) ?>"
                                    class="w-full h-40 object-cover rounded-lg mb-3"
                                    onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">

                                <!-- Title -->
                                <h2 class="text-lg font-semibold mb-2">
                                    <?= htmlspecialchars($event['event_title']) ?>
                                    <?php if ($is_recent): ?>
                                        <span class="text-sm text-gray-500">(Recent)</span>
                                    <?php endif; ?>
                                </h2>

                                <!-- Date -->
                                <p class="text-sm text-gray-600 mb-1">
                                    📅 <?= date("M d, Y h:i A", strtotime($event['event_start'])) ?>
                                    - <?= date("h:i A", strtotime($event['event_end'])) ?>
                                </p>

                                <!-- Countdown -->
                                <p id="countdown-<?= $event['event_id'] ?>"
                                    class="text-sm font-semibold <?= $is_recent ? 'text-gray-400' : 'text-red-600' ?> mb-2"></p>

                                <!-- Location -->
                                <p class="text-sm text-gray-600 mb-2">
                                    📍 <?= htmlspecialchars($event['event_location']) ?>
                                </p>

                                <!-- Content Preview -->
                                <p class="text-gray-500 text-sm flex-grow">
                                    <?= nl2br(htmlspecialchars(mb_substr($event['event_description'], 0, 100))) ?>...
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full">
                            <div class="text-center shadow-sm p-6 bg-white rounded-lg">
                                <h5 class="text-xl font-semibold text-gray-700">No Events Found</h5>
                                <p class="text-gray-500">Check back later for updates</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
            <button onclick="closeEventModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
        const events = <?= json_encode($Events) ?>;

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
                    // Recent events within 3 days
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

        // Initialize countdowns
        events.forEach(e => {
            updateCountdown(e.event_start, e.event_end, `countdown-${e.event_id}`);
        });

        function openEventModal(id) {
            const e = events.find(ev => ev.event_id == id);
            if (!e) return;

            const image = e.event_image ? "uploads/" + e.event_image : "../../assets/images/home.jpg";

            document.getElementById("modalContent").innerHTML = `
        <img src="${image}" class="w-full h-64 object-cover rounded-lg mb-4" 
        onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">
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