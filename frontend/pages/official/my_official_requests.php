<?php
session_start();
require_once "../../../backend/config/db.php";
require_once "../../../backend/auth/login.php";
require_once "../../../backend/auth/auth_check.php";

// Redirect if not logged in
redirectIfNotLoggedIn(['../login.php'], $pdo);

// Get user info from session
$user_id = $_SESSION['user_id'];
$name    = $_SESSION['name'] ?? "Official";
$role    = $_SESSION['role'] ?? null;
$status  = $_SESSION['status'] ?? null;

// Allowed roles and statuses
$allowedRoles = ['Official'];
$allowedStatus = ['Approved'];

// Redirect if role not allowed
if (!in_array($role, $allowedRoles)) {
    header("Location: ../../pages/unauthorized_page.php");
    exit;
}

// Redirect if status not allowed
if (!in_array($status, $allowedStatus)) {
    header("Location: ../../pages/unauthorized_page.php");
    exit;
}

// Fetch ALL document requests for the logged-in user
$stmt = $pdo->prepare("
    SELECT request_id, document_name, status, requested_at 
    FROM document_requests 
    WHERE user_id = :user_id 
    ORDER BY requested_at DESC
");
$stmt->execute(['user_id' => $user_id]);
$documentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include('official-head.php'); ?>
<body class="bg-gray-100">
    <?php include('../../components/DashNav.php'); ?>
    <main class="pt-24 px-4 sm:px-6 lg:px-10 space-y-8">
        <div class="container">
            <!-- Document Requests Table -->
            <div class="bg-white shadow-md rounded-xl p-6 mt-8">
                <h2 class="text-xl font-bold mb-4">All Document Requests</h2>
                <?php if (count($documentRequests) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="text-left p-2">Document</th>
                                    <th class="text-left p-2">Status</th>
                                    <th class="text-left p-2">Requested At</th>
                                    <th class="text-center p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documentRequests as $doc): ?>
                                    <tr class="border-t">
                                        <td class="p-2"><?= htmlspecialchars($doc['document_name']) ?></td>
                                        <td class="p-2">
                                            <?php 
                                                $statusClass = match($doc['status']) {
                                                    'Approved' => 'text-green-600 font-semibold',
                                                    'Rejected' => 'text-red-600 font-semibold',
                                                    'Pending'  => 'text-yellow-600 font-semibold',
                                                    default    => 'text-gray-600'
                                                };
                                            ?>
                                            <span class="<?= $statusClass ?>">
                                                <?= htmlspecialchars($doc['status']) ?>
                                            </span>
                                        </td>
                                        <td class="p-2"><?= date('M d, Y', strtotime($doc['requested_at'])) ?></td>
                                        <td class="p-2 text-center">
                                            <?php if ($doc['status'] === 'Approved'): ?>
                                                <a href="../../components/document_format.php?request_id=<?= $doc['request_id'] ?>" 
                                                    target="_blank"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                    View
                                                </a>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-sm">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">You have no document requests yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
