<?php
// populationController.php
header('Content-Type: application/json');
require_once "../config/db.php";
require_once "../auth/auth_check.php";

// Only allow Admin
$allowedRoles = ['Admin'];
$allowedStatus = ['Approved'];
require_once "../auth/auth_check.php";

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$filterType = $data['filter_type'] ?? 'all';

// Base WHERE clause
$whereAlive = " WHERE u.is_alive = 1 AND u.is_deleted = 0 AND u.is_archived = 0 ";
$whereDead  = " WHERE u.is_alive = 0 AND u.is_deleted = 0 AND u.is_archived = 0 ";
$params = [];

// Build filters
switch ($filterType) {
    case 'month':
        $whereAlive .= " AND DATE_FORMAT(b.birth_date, '%Y-%m') = :month ";
        $whereDead  .= " AND DATE_FORMAT(u.dead_at, '%Y-%m') = :month ";
        $params['month'] = $data['month'];
        break;
    case 'year':
        $whereAlive .= " AND YEAR(b.birth_date) = :year ";
        $whereDead  .= " AND YEAR(u.dead_at) = :year ";
        $params['year'] = $data['year'];
        break;
    case 'day':
        $whereAlive .= " AND DATE(b.birth_date) = :day ";
        $whereDead  .= " AND DATE(u.dead_at) = :day ";
        $params['day'] = $data['day'];
        break;
    case 'range':
        $whereAlive .= " AND DATE(b.birth_date) BETWEEN :start AND :end ";
        $whereDead  .= " AND DATE(u.dead_at) BETWEEN :start AND :end ";
        $params['start'] = $data['start'];
        $params['end']   = $data['end'];
        break;
    case 'all':
    default:
        // no extra filter
        break;
}

// Query Alive grouped by period
$stmtAlive = $pdo->prepare("
    SELECT DATE_FORMAT(b.birth_date, '%Y-%m') AS period, COUNT(*) AS alive
    FROM users u
    LEFT JOIN user_birthdates b ON u.user_id = b.user_id
    $whereAlive
    GROUP BY period
    ORDER BY period ASC
");
$stmtAlive->execute($params);
$aliveData = $stmtAlive->fetchAll(PDO::FETCH_KEY_PAIR); // period => alive count

// Query Dead grouped by period
$stmtDead = $pdo->prepare("
    SELECT DATE_FORMAT(u.dead_at, '%Y-%m') AS period, COUNT(*) AS dead
    FROM users u
    $whereDead
    GROUP BY period
    ORDER BY period ASC
");
$stmtDead->execute($params);
$deadData = $stmtDead->fetchAll(PDO::FETCH_KEY_PAIR); // period => dead count

// Merge periods to ensure labels include all
$labels = array_unique(array_merge(array_keys($aliveData), array_keys($deadData)));
sort($labels);

// Prepare final arrays
$alive = $dead = [];
foreach ($labels as $label) {
    $alive[] = isset($aliveData[$label]) ? (int)$aliveData[$label] : 0;
    $dead[]  = isset($deadData[$label]) ? (int)$deadData[$label] : 0;
}

echo json_encode([
    'labels' => $labels,
    'alive' => $alive,
    'dead' => $dead
]);

