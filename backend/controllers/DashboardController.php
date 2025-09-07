<?php
require_once "../../../backend/config/db.php";

class DashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Generic function to get count
    private function getCount($query)
    {
        return (int) $this->pdo->query($query)->fetchColumn();
    }

    // Dashboard statistics
    public function getStats()
    {
        return [
            'residents' => $this->getCount("
                SELECT COUNT(*) FROM users 
                WHERE role = 'Resident' AND is_deleted = 0 AND is_archived = 0
            "),
            'officials' => $this->getCount("
                SELECT COUNT(*) 
                FROM officials o
                INNER JOIN users u ON u.user_id = o.user_id
                WHERE u.is_deleted = 0 AND u.is_archived = 0 AND u.status = 'Approved'
            "),
            'events' => $this->getCount("
                SELECT COUNT(*) FROM events 
                WHERE status = 'Upcoming' AND is_deleted = 0 AND is_archived = 0
            "),
            'announcements' => $this->getCount("
                SELECT COUNT(*) FROM announcements 
                WHERE status = 'Published' AND is_deleted = 0 AND is_archived = 0
            "),
            'population' => $this->getCount("
                SELECT COUNT(*) FROM users 
                WHERE is_alive = 1 AND is_deleted = 0 AND is_archived = 0
            "),
            'pendingRequests' => $this->getCount("
                SELECT COUNT(*) FROM users 
                WHERE status = 'Pending' AND is_deleted = 0 AND is_archived = 0
            "),
            'approvedRequests' => $this->getCount("
                SELECT COUNT(*) FROM approvals WHERE status = 'Approved'
            "),
            'approvedUsers' => $this->getCount("
                SELECT COUNT(*) FROM users 
                WHERE status = 'Approved' AND is_deleted = 0 AND is_archived = 0
            "),
            'verifiedUsers' => $this->getCount("
                SELECT COUNT(*) FROM users 
                WHERE status = 'Verified' AND is_deleted = 0 AND is_archived = 0
            "),
            'healthReports' => 0 // Placeholder
        ];
    }
}
