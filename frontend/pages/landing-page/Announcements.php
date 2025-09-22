    <?php
        include '../../components/Head.php';
        include '../../components/Navbar.php';
        include '../../../backend/config/db.php';
        $stmt = $pdo->prepare("
            SELECT * FROM announcements 
            WHERE is_archived = 0 
                AND audience IN ('Public')
                AND (valid_until IS NULL OR valid_until >= NOW())
            ORDER BY FIELD(priority, 'Urgent','High','Normal','Low'), created_at DESC
        ");
        $stmt->execute();
        $Announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
<body class="bg-gray-50">
    <?php include('./landing-page-section/announcement_section.php');?>
    <script>
        const announcements = <?= json_encode($Announcements) ?>;

        function openAnnouncementModal(id) {
            const a = announcements.find(x => x.announcement_id == id);
            if (!a) return;

            let image = a.announcement_image ?
                "../../uploads/announcement/" + a.announcement_image :
                "../../assets/images/Logo.jpg";

            let html = `
            <img src="${image}" 
            class="w-full h-64 object-cover rounded-lg mb-4"
            onerror="this.onerror=null; this.src='../../uploads/images/default.jpg';">
            <h2 class="text-2xl font-bold mb-2">${a.announcement_title}</h2>
            <p class="text-gray-700 mb-4">${a.announcement_content}</p>
            <p><strong>Status:</strong> ${a.status}</p>
            <p><strong>Author:</strong> ${a.author_id ?? 'Unknown'}</p>
            <p><strong>Priority:</strong> ${a.priority}</p>
            <p><strong>Audience:</strong> ${a.audience}</p>
            <p><strong>Posted:</strong> ${a.created_at}</p>
            ${a.valid_until ? `<p><strong>Valid Until:</strong> ${a.valid_until}</p>` : ""}
            ${a.announcement_location ? `<p><strong>Location:</strong> ${a.announcement_location}</p>` : ""}
            ${a.attachment ? `<p><a href="../../uploads/announcement/${a.attachment}" target="_blank" class="text-blue-600 underline">📎 View Attachment</a></p>` : ""}
        `;
            document.getElementById("modalContent").innerHTML = html;
            document.getElementById("announcementModal").classList.remove("hidden");
            document.getElementById("announcementModal").classList.add("flex");
        }

        function closeAnnouncementModal() {
            document.getElementById("announcementModal").classList.add("hidden");
            document.getElementById("announcementModal").classList.remove("flex");
        }
    </script>
</body>
</html>