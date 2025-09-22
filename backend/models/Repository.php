<?php
function getUsersByStatus($pdo, $status, $limit = 50)
{
    $sql = "SELECT u.user_id, u.email, u.role, u.status, u.created_at,
                    CONCAT(ud.f_name, ' ', COALESCE(CONCAT(ud.m_name, ' '), ''), ud.l_name) AS full_name
                FROM users u
                JOIN user_details ud ON u.user_id = ud.user_id
                WHERE u.status = :status
                ORDER BY u.created_at DESC
                LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDocumentByStatus($pdo, $status, $limit = 50)
{
    $sql = "SELECT dr.request_id, 
                        dr.document_name, 
                        dr.purpose, 
                        dr.status, 
                        dr.requested_at, 
                        dr.processed_at,
                        dr.remarks,
                        CONCAT(ud.f_name, ' ', COALESCE(CONCAT(ud.m_name, ' '), ''), ud.l_name,
                                IF(ud.ext_name IS NOT NULL AND ud.ext_name != '', CONCAT(' ', ud.ext_name), '')
                        ) AS user_name,
                        CONCAT(ad.f_name, ' ', COALESCE(CONCAT(ad.m_name, ' '), ''), ad.l_name,
                                IF(ad.ext_name IS NOT NULL AND ad.ext_name != '', CONCAT(' ', ad.ext_name), '')
                        ) AS approved_by
                    FROM document_requests dr
                    JOIN user_details ud ON dr.user_id = ud.user_id
                    LEFT JOIN user_details ad ON dr.processed_by = ad.user_id
                    WHERE dr.status = :status
                    ORDER BY dr.processed_at DESC
                    LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllUsers($pdo, $status, $limit = 50)
{
    $sql = "SELECT u.user_id, u.email, u.role, u.status, u.is_Alive, u.created_at, u.updated_at, u.archived_at, u.dead_at,
                    CONCAT(ud.f_name, ' ', COALESCE(CONCAT(ud.m_name, ' '), ''), ud.l_name) AS full_name
                FROM users u
                JOIN user_details ud ON u.user_id = ud.user_id
                WHERE u.role = :status
                ORDER BY u.created_at DESC
                LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAllEvents($pdo, $status = null, $limit = 50) 
{
    $sql = "SELECT 
        e.event_id, 
        e.event_title, 
        e.event_description, 
        e.event_start, 
        e.event_end, 
        e.event_location, 
        e.event_type, 
        e.event_image, 
        e.attachment, 
        e.audience, 
        e.status,
        e.is_archived, 
        e.is_deleted, 
        e.created_at
    FROM events e";

    if ($status !== null) {
        $sql .= " WHERE e.status = :status";
    }

    $sql .= " ORDER BY e.created_at DESC LIMIT :limit";

    $stmt = $pdo->prepare($sql);

    if ($status !== null) {
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getAllAnnouncements($pdo, $status = null, $limit = 50)
{
    $sql = "SELECT 
                a.announcement_id,
                a.announcement_title,
                a.announcement_content,
                a.announcement_category,
                a.announcement_location,
                a.announcement_image,
                a.attachment,
                a.status,
                a.priority,
                a.valid_until,
                a.audience,
                a.is_archived,
                a.created_at,
                a.updated_at,
                a.archived_at,

                -- 👇 Join users + user_details to fetch the full name
                CONCAT(ud.f_name, ' ', COALESCE(CONCAT(ud.m_name, ' '), ''), ud.l_name, 
                       IF(ud.ext_name IS NOT NULL AND ud.ext_name != '', CONCAT(' ', ud.ext_name), '')
                ) AS full_name

            FROM announcements a
            JOIN users u ON a.author_id = u.user_id
            JOIN user_details ud ON u.user_id = ud.user_id";

    if ($status !== null) {
        $sql .= " WHERE a.status = :status";
    }

    $sql .= " ORDER BY a.created_at DESC LIMIT :limit";

    $stmt = $pdo->prepare($sql);

    if ($status !== null) {
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getUserProfileById($pdo, $user_id) {
        $sql = "
            SELECT 
                u.user_id, u.email, u.password, u.role, u.status AS user_status, u.is_alive, u.created_at, u.updated_at,

                ud.f_name, ud.m_name, ud.l_name, ud.ext_name, ud.gender, ud.photo, ud.contact_no, ud.civil_status,
                ud.occupation, ud.nationality, ud.voter_status, ud.pwd_status, 
                ud.senior_citizen_status, ud.religion, ud.blood_type, ud.educational_attainment,

                ub.birth_date, ub.birth_place,

                ur.house_no, ur.purok, ur.barangay, ur.municipality, ur.province, ur.years_residency, 
                ur.household_head, ur.house_type, ur.ownership_status, ur.previous_address,

                uf.fathers_name, uf.fathers_birthplace, uf.mothers_name, uf.mothers_birthplace, 
                uf.spouse_name, uf.num_dependents, uf.contact_person, uf.emergency_contact_no,

                uh.health_condition, uh.common_health_issue, uh.vaccination_status, uh.height_cm, uh.weight_kg, 
                uh.last_medical_checkup, uh.health_remarks,

                ui.monthly_income, ui.income_source, ui.household_members, ui.additional_income_sources,
                ui.household_head_occupation, ui.income_proof,

                uid.id_type, uid.front_valid_id_path, uid.back_valid_id_path, uid.selfie_with_id

            FROM users u
            LEFT JOIN user_details ud ON u.user_id = ud.user_id
            LEFT JOIN user_birthdates ub ON u.user_id = ub.user_id
            LEFT JOIN user_residency ur ON u.user_id = ur.user_id
            LEFT JOIN user_family_info uf ON u.user_id = uf.user_id
            LEFT JOIN user_health_info uh ON u.user_id = uh.user_id
            LEFT JOIN user_income_info ui ON u.user_id = ui.user_id
            LEFT JOIN user_identity_docs uid ON u.user_id = uid.user_id
            WHERE u.user_id = ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
function getManageUsers($pdo, $role = null, $limit = 50)
{
    $sql = "SELECT 
                u.user_id, 
                u.email, 
                u.role, 
                u.status,
                u.is_archived,
                u.created_at,
                CONCAT(
                    ud.f_name, ' ',
                    COALESCE(CONCAT(ud.m_name, ' '), ''),
                    ud.l_name,
                    IF(ud.ext_name IS NOT NULL AND ud.ext_name != '', CONCAT(' ', ud.ext_name), '')
                ) AS full_name
            FROM users u
            LEFT JOIN user_details ud ON u.user_id = ud.user_id
            WHERE u.role = :role
            ORDER BY u.created_at DESC
            LIMIT :limit";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':role', $role, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>