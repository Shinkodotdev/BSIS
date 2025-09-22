<?php
session_start();
require_once "../../../backend/config/db.php";
require_once "../../../backend/auth/login.php";
require_once "../../../backend/auth/auth_check.php";
// Redirect if not logged in
redirectIfNotLoggedIn(['../login.php'], $pdo);

// Get user info from session
$user_id = $_SESSION['user_id'];
$name    = $_SESSION['name'] ?? "Resident";
$role    = $_SESSION['role'] ?? null;
$status  = $_SESSION['status'] ?? null;

// Allowed roles and statuses
$allowedRoles = ['Resident'];
$allowedStatus = ['Approved'];

// Redirect if role not allowed
if (!in_array($role, $allowedRoles)) {
    header("Location: ../../pages/unauthorized_page.php"); // make sure this file exists
    exit;
}

// Redirect if status not allowed
if (!in_array($status, $allowedStatus)) {
    header("Location: ../../pages/unauthorized_page.php");
    exit;
}


// Fetch announcements for Residents and Public
$announcementsStmt = $pdo->prepare("
    SELECT announcement_id, announcement_title, announcement_content, announcement_category, announcement_image, created_at
    FROM announcements
    WHERE (audience = 'Residents' OR audience = 'Public')
        AND is_deleted = 0
        AND is_archived = 0
        AND status = 'Published'
    ORDER BY priority DESC, created_at DESC
    LIMIT 5
");
$announcementsStmt->execute();
$announcements = $announcementsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch events for Residents and Public
$eventsStmt = $pdo->prepare("
    SELECT event_id, event_title, event_description, event_start, event_end, event_location, event_type, event_image
    FROM events
    WHERE (audience = 'Residents' OR audience = 'Public')
        AND is_deleted = 0
        AND is_archived = 0
        AND status IN ('Upcoming', 'Ongoing')
    ORDER BY event_start ASC
    LIMIT 5
");
$eventsStmt->execute();
$events = $eventsStmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch recent document requests for the logged-in user (avoid undefined variable)
try {
    $docStmt = $pdo->prepare("
        SELECT request_id, document_name, status, requested_at
        FROM document_requests
        WHERE user_id = :user_id
            AND is_deleted = 0
        ORDER BY requested_at DESC
        LIMIT 5
    ");
    $docStmt->execute(['user_id' => $user_id]);
    $documentRequests = $docStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // On error, fall back to an empty array so count()/foreach won't explode
    error_log("Failed to fetch document requests: " . $e->getMessage());
    $documentRequests = [];
}

?>

<?php include('resident-head.php'); ?>

<body class="bg-gray-100">

    <?php include('../../components/DashNav.php'); ?>

    <main class="pt-24 px-4 sm:px-6 lg:px-10 space-y-8">
        <!-- ANNOUNCEMENTS AND EVENTS  -->
        <?php include('../../components/announcement_event_showcase.php'); ?>

        <!-- Quick Action / Highlight Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Announcements & Events -->
            <a href="../landing-page/Announcements.php" target="_blank"
                class="bg-yellow-50 border-l-4 border-yellow-400 shadow-lg rounded-xl p-5 flex items-center justify-between hover:shadow-xl transition">
                <div>
                    <h2 class="text-xs sm:text-sm font-medium text-yellow-700 uppercase">Announcements & Events</h2>
                    <p class="text-2xl sm:text-3xl font-extrabold text-yellow-800 mt-2">View</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full text-yellow-500 text-3xl">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </a>

            <!-- Health Survey Reports -->
            <a href="health/view_reports.php"
                class="bg-green-50 border-l-4 border-green-400 shadow-lg rounded-xl p-5 flex items-center justify-between hover:shadow-xl transition">
                <div>
                    <h2 class="text-xs sm:text-sm font-medium text-green-700 uppercase">Health Reports</h2>
                    <p class="text-2xl sm:text-3xl font-extrabold text-green-800 mt-2">View</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full text-green-500 text-3xl">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </a>

            <!-- Request Documents -->
            <div onclick="openRequestModal()"
                class="bg-blue-50 border-l-4 border-blue-400 shadow-lg rounded-xl p-5 flex items-center justify-between cursor-pointer hover:shadow-xl transition">
                <div>
                    <h2 class="text-xs sm:text-sm font-medium text-blue-700 uppercase">Request Document</h2>
                    <p class="text-2xl sm:text-3xl font-extrabold text-blue-800 mt-2">New</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full text-blue-500 text-3xl">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
            </div>

            <!-- Profile -->
            <a href="profile.php"
                class="bg-pink-50 border-l-4 border-pink-400 shadow-lg rounded-xl p-5 flex items-center justify-between hover:shadow-xl transition">
                <div>
                    <h2 class="text-xs sm:text-sm font-medium text-pink-700 uppercase">Profile</h2>
                    <p class="text-2xl sm:text-3xl font-extrabold text-pink-800 mt-2">View</p>
                </div>
                <div class="bg-pink-100 p-3 rounded-full text-pink-500 text-3xl">
                    <i class="fa-solid fa-user"></i>
                </div>
            </a>

        </div>
        <!-- Recent Document Requests Table -->
        <div class="bg-white shadow-md rounded-xl p-6 mt-8">
            <h2 class="text-xl font-bold mb-4">Recent Document Requests</h2>

            <?php if (!empty($documentRequests)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="text-left p-2">Document</th>
                                <th class="text-left p-2">Status</th>
                                <th class="text-left p-2">Requested At</th>
                                <th class="text-left p-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documentRequests as $doc): ?>
                                <tr class="border-t">
                                    <td class="p-2"><?= htmlspecialchars($doc['document_name'] ?? 'N/A') ?></td>
                                    <td class="p-2"><?= htmlspecialchars($doc['status'] ?? 'N/A') ?></td>
                                    <td class="p-2"><?= !empty($doc['requested_at']) ? date('M d, Y', strtotime($doc['requested_at'])) : 'N/A' ?></td>
                                    <td class="p-2">
                                        <a
                                            href="../../components/document_format.php?request_id=<?= urlencode($doc['request_id']) ?>"
                                            target="_blank"
                                            class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No recent document requests.</p>
            <?php endif; ?>
        </div>


    </main>
    <!-- Document Request Modal -->
    <div id="requestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Request Document</h2>

            <form id="requestForm" class="space-y-4">
                <!-- Select Document -->
                <div>
                    <label for="documentName" class="block text-sm font-medium">Select Document</label>
                    <div class="max-h-40 overflow-y-auto border rounded">
                        <select name="document_name" id="documentName" class="w-full p-2" size="6" required>
                            <option value="First Time Job Seeker">First Time Job Seeker</option>
                            <option value="Certificate of Indigency">Certificate of Indigency</option>
                            <option value="Travel Permit">Travel Permit</option>
                            <option value="Certificate of Living Together">Certificate of Living Together</option>
                            <option value="Proof of Income">Proof of Income</option>
                            <option value="Same Person Certificate">Same Person Certificate</option>
                            <option value="Oath of Undertaking">Oath of Undertaking</option>
                            <option value="Certificate of Guardianship">Certificate of Guardianship</option>
                            <option value="Certificate of Residency">Certificate of Residency</option>
                            <option value="Endorsement Letter for Mayor">Endorsement Letter for Mayor</option>
                            <option value="Certificate for Electricity">Certificate for Electricity</option>
                            <option value="Certificate of Low Income">Certificate of Low Income</option>
                            <option value="Business Permit">Business Permit</option>
                            <option value="Barangay Clearance">Barangay Clearance</option>
                        </select>
                    </div>
                </div>


                <!-- Purpose -->
                <div>
                    <label for="purpose" class="block text-sm font-medium">Purpose</label>
                    <textarea name="purpose" id="purpose" rows="3" class="w-full border rounded p-2" required></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRequestModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Simple carousel auto-slide for announcements and events
        const slidePositions = {};

        function nextSlide(id) {
            const carousel = document.getElementById(id);
            if (!carousel) return;
            const container = carousel.children[0];
            slidePositions[id] = (slidePositions[id] || 0) + 1;
            if (slidePositions[id] >= container.children.length) slidePositions[id] = 0;
            container.style.transform = `translateX(-${container.children[0].offsetWidth * slidePositions[id]}px)`;
        }
        setInterval(() => nextSlide('announcementsCarousel'), 5000);
        setInterval(() => nextSlide('eventsCarousel'), 7000);
    </script>

    <script>
        function openRequestModal() {
            const modal = document.getElementById('requestModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');
        }

        function closeRequestModal() {
            const modal = document.getElementById('requestModal');
            modal.classList.remove('flex', 'items-center', 'justify-center');
            modal.classList.add('hidden');
        }

        // Handle form submit
        document.getElementById('requestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../../../backend/requests/add_request.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Request submitted successfully!');
                    closeRequestModal();
                    location.reload(); // refresh to show in "Recent Requests"
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong.');
                });
        });
    </script>
</body>

</html>