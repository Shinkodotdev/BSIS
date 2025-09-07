<?php
session_start();
// Specify which roles are allowed on this page
$allowedRoles = ['Admin']; 

// Optional: override default allowed status
$allowedStatus = ['Approved'];

// Include the reusable auth check
require_once "../../../backend/auth/auth_check.php";

require '../../../backend/config/db.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Default messages
$success = $error = "";

// 1️⃣ Automatically update officials whose term has ended
$today = date('Y-m-d');
$updateStmt = $pdo->prepare("
    UPDATE officials o
    INNER JOIN users u ON o.user_id = u.user_id
    SET u.role = CONCAT('Former ', o.position)
    WHERE o.end_of_term < :today
");
$updateStmt->execute([':today' => $today]);

// Handle assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_official'])) {
    $userId = $_POST['user_id'] ?? null;
    $position = trim($_POST['position'] ?? '');
    $startOfTerm = $_POST['start_of_term'] ?? null;
    $endOfTerm = $_POST['end_of_term'] ?? null;

    if ($userId && $position && $startOfTerm && $endOfTerm) {
        try {
            $pdo->beginTransaction();

            // Check if already an official
            $check = $pdo->prepare("SELECT COUNT(*) FROM officials WHERE user_id = :user_id");
            $check->execute([':user_id' => $userId]);
            if ($check->fetchColumn() > 0) {
                throw new Exception("This user is already assigned as an Official.");
            }

            // Insert into officials
            $stmt = $pdo->prepare("
                INSERT INTO officials (user_id, position, start_of_term, end_of_term)
                VALUES (:user_id, :position, :start_of_term, :end_of_term)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':position' => $position,
                ':start_of_term' => $startOfTerm,
                ':end_of_term' => $endOfTerm
            ]);

            // Update role
            $stmt = $pdo->prepare("UPDATE users SET role = 'Official' WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);

            $pdo->commit();
            $success = "✅ User assigned as Official successfully!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "❌ Error: " . $e->getMessage();
        }
    } else {
        $error = "⚠️ Please fill all required fields.";
    }
}

// Fetch all users who are not Admin and not already assigned as Official
$stmt = $pdo->query("
    SELECT u.user_id, u.email, u.role, 
        d.f_name, d.m_name, d.l_name, d.ext_name
    FROM users u
    LEFT JOIN user_details d ON u.user_id = d.user_id
    LEFT JOIN officials o ON u.user_id = o.user_id
    WHERE u.role != 'Admin' AND o.user_id IS NULL
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all assigned positions to disable them in dropdown
$assignedPositionsStmt = $pdo->query("SELECT position FROM officials");
$assignedPositions = $assignedPositionsStmt->fetchAll(PDO::FETCH_COLUMN);

// All possible positions
$positions = ["Barangay Captain", "Barangay Councilor", "SK Chairman", "SK Councilor"];
?>



<?php
$pageTitle = "Admin | Manage Officials";
$pageDescription = "Manage Officials for Barangay Poblacion Sur System";
include 'admin-head.php';
?>

<body class="bg-gray-100">
    <?php include('../../components/DashNav.php'); ?>

    <main class="flex-1 p-4 sm:p-6 md:ml-14 mt-16 md:mt-0 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">👥 Manage Officials</h1>

            <!-- Messages -->
            <?php if (!empty($success)): ?>
                <div id="msgBox" class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php elseif (!empty($error)): ?>
                <div id="msgBox" class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Users Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full border-collapse">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="p-3 text-left">Name</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Role</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        <?= htmlspecialchars($user['f_name'] .
                                            ($user['m_name'] ? ' ' . $user['m_name'] : '') .
                                            ' ' . $user['l_name'] .
                                            ($user['ext_name'] ? ', ' . $user['ext_name'] : '')) ?>
                                    </td>

                                    <td class="p-3"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($user['role']) ?></td>
                                    <td class="p-3">
                                        <form method="post" class="flex gap-2 flex-wrap">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                            <select name="position" class="p-1 border rounded text-sm" required>
                                                <option value="" disabled selected>Select Position</option>
                                                <?php foreach ($positions as $pos): ?>
                                                    <?php
                                                    // Disable if the position is already taken for unique positions
                                                    $uniquePositions = ["Barangay Captain", "SK Chairman"];
                                                    $disabled = (in_array($pos, $uniquePositions) && in_array($pos, $assignedPositions)) ? 'disabled' : '';
                                                    $label = (in_array($pos, $uniquePositions) && in_array($pos, $assignedPositions)) ? ' (Taken)' : '';
                                                    ?>
                                                    <option value="<?= $pos ?>" <?= $disabled ?>><?= $pos . $label ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <input type="date" name="start_of_term" class="p-1 border rounded text-sm" required>
                                            <input type="date" name="end_of_term" class="p-1 border rounded text-sm" required>
                                            <button type="submit" name="assign_official"
                                                class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-500">
                                                Set as Official
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide messages after 4s
        setTimeout(() => {
            const msg = document.getElementById('msgBox');
            if (msg) msg.style.display = 'none';
        }, 4000);
    </script>
</body>

</html>