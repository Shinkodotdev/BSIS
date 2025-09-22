<?php
session_start();
$allowedRoles = ['Admin']; 
$allowedStatus = ['Approved'];
require_once "../../../backend/auth/auth_check.php";

require '../../../backend/config/db.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Default messages
$success = $error = "";

// Auto-update officials whose term has ended
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

$stmt = $pdo->query("
    SELECT u.user_id, u.email, u.role, u.status,
        CONCAT(
            d.f_name, ' ',
            COALESCE(CONCAT(d.m_name, ' '), ''),
            d.l_name,
            IF(d.ext_name IS NOT NULL AND d.ext_name != '', CONCAT(' ', d.ext_name), '')
        ) AS full_name
    FROM users u
    LEFT JOIN user_details d ON u.user_id = d.user_id
    LEFT JOIN officials o ON u.user_id = o.user_id
    WHERE u.role != 'Admin'
        AND u.status = 'Pending'
        AND o.user_id IS NULL
    ORDER BY u.created_at DESC
    LIMIT 50
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all assigned positions
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

        <!-- Search -->
        <div class="mb-3 flex flex-col sm:flex-row justify-between gap-3">
            <input id="userSearch" type="text" placeholder="Search approved users..."
                class="w-full sm:w-1/3 border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table id="userTable" class="min-w-[900px] w-full text-xs sm:text-sm md:text-base border divide-y divide-gray-200">
                    <thead class="bg-slate-800 text-white sticky top-0 z-20">
                        <tr>
                            <th class="p-3 text-left sticky left-0 bg-slate-800 z-30">Name</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Role</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="p-3 text-center text-gray-500">No approved users available</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 whitespace-nowrap sticky left-0 bg-white z-10"><?= htmlspecialchars($user['full_name']) ?></td>
                                    <td class="p-3 whitespace-nowrap"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($user['role']) ?></td>
                                    <td class="p-3 text-green-600"><?= htmlspecialchars($user['status']) ?></td>
                                    <td class="p-3">
                                        <form method="post" class="flex gap-2 flex-wrap">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                            <select name="position" class="p-1 border rounded text-sm" required>
                                                <option value="" disabled selected>Select Position</option>
                                                <?php foreach ($positions as $pos): ?>
                                                    <?php
                                                    $uniquePositions = ["Barangay Captain", "SK Chairman"];
                                                    $disabled = (in_array($pos, $uniquePositions) && in_array($pos, $assignedPositions)) ? 'disabled' : '';
                                                    $label = $disabled ? ' (Taken)' : '';
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
    </div>
</main>

<script src="../../assets/js/TimeOut.js"></script>
</body>
</html>
