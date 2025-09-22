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
    WHERE is_archived != 1
      AND status != 'Cancelled'
      AND audience IN ('Public')
      AND (
          event_start IS NULL 
          OR event_end >= DATE_SUB(NOW(), INTERVAL 3 DAY)
      )
    ORDER BY event_start ASC
");
$stmt->execute();
$Events = $stmt->fetchAll(PDO::FETCH_ASSOC);


    ?>
<body class="bg-gray-50">
        <!-- EVENTS  -->
        <?php include('./landing-page-section/events_section.php'); ?>
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

            const image = e.event_image ? "../../uploads/events/" + e.event_image : "../../assets/images/home.jpg";

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
        ${e.attachment ? `<p><a href="../../uploads/events/${e.attachment}" target="_blank" class="text-blue-600 underline">📎 View Attachment</a></p>` : ""}
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