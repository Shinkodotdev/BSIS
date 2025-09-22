<?php
require_once "../../backend/config/db.php";
date_default_timezone_set('Asia/Manila');

$request_id = $_GET['request_id'] ?? 0;
if (!$request_id) { exit("Invalid request ID"); }

$stmt = $pdo->prepare("
    SELECT dr.document_name, dr.requested_at, ud.f_name, ud.m_name, ud.l_name, ud.ext_name, ud.gender, ub.birth_date
    FROM document_requests dr
    JOIN user_details ud ON dr.user_id = ud.user_id
    LEFT JOIN user_birthdates ub ON ud.user_id = ub.user_id
    WHERE dr.request_id = :request_id
    LIMIT 1
");
$stmt->execute(['request_id' => $request_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$request) { exit("Request not found"); }

// Calculate age
$age = !empty($request['birth_date']) ? (new DateTime($request['birth_date']))->diff(new DateTime("now", new DateTimeZone('Asia/Manila')))->y : "N/A";

// Courtesy titles
$gender = $request['gender'] ?? '';
if ($gender === 'Male') { $courtesy_titles='Mr.'; $courtesy_title='his'; $courtesy_gender='he'; }
elseif ($gender==='Female') { $courtesy_titles='Ms.'; $courtesy_title='her'; $courtesy_gender='she'; }
else { $courtesy_titles='Mr./Ms.'; $courtesy_title='his/her'; $courtesy_gender='he/she'; }

// Full name with middle initial
$middle_initial = !empty($request['m_name']) ? strtoupper(substr($request['m_name'],0,1)).'.' : '';
$full_name = trim($request['f_name'].' '.$middle_initial.' '.$request['l_name'].' '.($request['ext_name'] ?? ''));

// Fetch Barangay Captain
$captain_name = $pdo->query("
    SELECT CONCAT(ud.f_name,' ',COALESCE(ud.m_name,''),' ',ud.l_name,' ',COALESCE(ud.ext_name,'')) AS full_name
    FROM officials o
    JOIN users u ON o.user_id = u.user_id
    JOIN user_details ud ON u.user_id = ud.user_id
    WHERE o.position = 'Barangay Captain'
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC)['full_name'] ?? 'Barangay Captain';

// Fetch Barangay Secretary
$secretary_name = $pdo->query("
    SELECT CONCAT(ud.f_name,' ',COALESCE(ud.m_name,''),' ',ud.l_name,' ',COALESCE(ud.ext_name,'')) AS full_name
    FROM officials o
    JOIN users u ON o.user_id = u.user_id
    JOIN user_details ud ON u.user_id = ud.user_id
    WHERE o.position = 'Barangay Secretary'
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC)['full_name'] ?? 'Barangay Secretary';

$document_name = $request['document_name'];
$document_date = date('F j, Y', strtotime($request['requested_at']));

// Function to generate document content for each type
function getDocumentContent($document_name, $full_name, $age, $courtesy_titles, $courtesy_title, $courtesy_gender, $document_date) {
    switch ($document_name) {
        case "First Time Job Seeker":
            return "
            <p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is a qualified availed of RA 11261.</p>
            <p>I further certify that the holder/bearer was informed of <b>{$courtesy_title}</b> rights, including duties and responsibilities through the Oath of Undertaking <b>{$courtesy_gender}</b> has signed.</p>";
        case "Certificate of Indigency":
            return "
            <p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is known to be financially underprivileged and is hereby recognized as qualified for Barangay indigency assistance.</p>
            <p>This certification is issued for the purpose of applying for educational, medical, or other social assistance programs, as may be required by government or private institutions.</p>";
        case "Travel Permit":
            return "
            <p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is hereby permitted to travel on <b>{$document_date}</b> for personal, educational, or official purposes.</p>
            <p>The bearer of this certificate is requested to present this document to relevant authorities when necessary.</p>";
        case "Certificate of Living Together":
            return "
            <p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is living together with the members of the household as recorded in the official Barangay records.</p>";
        case "Proof of Income":
            return "<p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has an income of <b>__________________</b> as per Barangay records.</p>";
        case "Same Person Certificate":
            return "<p>This is to certify that the individual named <b>{$courtesy_titles} {$full_name}</b> has been verified and confirmed to be the same person as per official Barangay records.</p>";
        case "Oath of Undertaking":
            return "<p>I, <b>{$courtesy_titles} {$full_name}</b>, of legal age and a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, do hereby solemnly swear and undertake to fulfill all my duties, responsibilities, and obligations as required under the relevant laws and Barangay regulations.</p>";
        case "Certificate of Guardianship":
            return "<p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, of legal age and a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is the lawful guardian of <b>________________</b> as per Barangay records and relevant legal documents.</p>";
        case "Barangay Clearance":
            return "<p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has been verified and found to have no derogatory records as per Barangay records.</p>
                    <p>This clearance is issued for whatever legal purpose it may serve.</p>";
        case "Certificate of Residency":
            return "<p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, is a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, and has been residing in this Barangay as per official records.</p>";
        case "Endorsement Letter for Mayor":
            return "<p>This is to formally endorse <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, to the Municipal Mayor for official matters requiring attention or assistance.</p>";
        case "Certificate for Electricity":
            return "<p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has an active electricity connection at the said residence.</p>";
        case "Certificate of Low Income":
            return "<p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, belongs to a low-income household based on Barangay records.</p>";
        case "Business Permit":
            return "
            <p>This is to certify that <b>{$courtesy_titles} {$full_name}</b>, of legal age and a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has been granted a <b>Business Permit</b> to legally operate a business within the jurisdiction of this Barangay.</p>
            <p>This permit is issued on <b>{$document_date}</b> and is valid for one (1) year, subject to renewal and compliance with existing Barangay, Municipal, and National laws and regulations.</p>
            <p>The permit holder is obliged to abide by the ordinances and policies set forth by the Barangay and the Local Government Unit.</p>";
        default:
            return "<p>Document format not available for <b>{$document_name}</b>.</p>";
    }
}

$document_content = getDocumentContent($document_name, $full_name, $age, $courtesy_titles, $courtesy_title, $courtesy_gender, $document_date);

// Word export headers
header("Content-Type: application/msword");
header("Content-Disposition: attachment; filename={$document_name}_request_{$request_id}.doc");
header("Pragma: no-cache");
header("Expires: 0");

echo '<html>
<head>
<meta charset="UTF-8">
<style>
body { 
    font-family: "Times New Roman", serif; 
    line-height: 1.6; 
    margin: 0; 
}

.header-table { 
    width: 100%; 
    text-align: center; 
    border-collapse: collapse; 
    margin-bottom: 20px; 
    line-height: 1; 
}

.header-table td { 
    vertical-align: middle; 
}

.header-center { 
    width: 70%; 
    text-align: center; 
    vertical-align: middle; 
    padding: 0 10px; 
    line-height: 1.2; 
}

.header-center b { 
    font-size: 1.2em; 
}

h1,h2,h3,h4 { 
    margin: 0; 
    padding: 0; 
    text-align: center; 
}

.content { 
    text-align: justify; 
    margin-top: 20px; 
    font-size: 1.1em; 
    margin-bottom: 30px;
}

.signature-table { 
    width: 100%; 
    margin-top: 100px; 
    align-items: center;
}

.signature-table td { 
    text-align: center; 
    vertical-align: top; 
}

.signature-name { 
    text-decoration: underline; 
    font-weight: bold; 
    margin-top: 40px; 
    display: block; 
}

.date-valid { 
    margin-top: 15px; 
    font-style: italic; 
    font-size: 0.9em; 
}

.header-logo { 
    width: 80px; 
    height: 80px; 
    object-fit: contain; 
}
</style>

<body>
<table class="header-table">
<tr>
    <td>
        <img src="http://localhost/Barangay_Information_System/frontend/assets/images/Logo.jpg" alt="DPWH Logo" class="header-logo" width="80" height="80">
    </td>
    <td class="header-center">
        <b>MUNICIPALITY OF TALAVERA</b><br>
        PROVINCE OF NUEVA ECIJA<br>
        <b>BARANGAY POBLACION SUR</b><br>
    </td>
    <td>
        <img src="http://localhost/Barangay_Information_System/frontend/assets/images/talavera.png" alt="Bagong Pilipinas Logo" class="header-logo" width="80" height="80">
    </td>
</tr>
</table>


<h1 style="font-weight:bold; text-transform:uppercase; text-align:center;">'.$document_name.'</h1>';

if($document_name === 'First Time Job Seeker'){
    echo '<p style="text-align:center; font-style:italic; margin-bottom:20px;">First Time Jobseekers Assistance Act - R.A. 11261</p>';
}

echo '<p style="text-align:right;">'.$document_date.'</p>
<div class="content">
'.$document_content.'
<p>Signed this '.date("jS").' day of '.date("F Y").' in the City/Municipality of Talavera, Province of Nueva Ecija.</p>';

if(in_array($document_name, ['First Time Job Seeker','Certificate of Indigency'])){
    echo '<p class="date-valid">This certification is valid only until '.date("F j, Y", strtotime("+1 year")).' (One (1) year from the date of issuance)</p>';
}

echo '</div>

<table class="signature-table">
<tr>
<td>
<p>Prepared by:</p>
<span class="signature-name">'.$secretary_name.'</span>
<p>Barangay Secretary</p>
</td>
<td>
<p>Approved by:</p>
<span class="signature-name">'.$captain_name.'</span>
<p>Barangay Captain</p>
</td>
</tr>
</table>

</body>
</html>';
exit();
?>
