<?php
require_once "../../backend/config/db.php";
date_default_timezone_set('Asia/Manila');

// Get request_id from GET
$request_id = $_GET['request_id'] ?? 1;
if (!$request_id) {
    exit("Invalid request ID");
}

// Fetch document request info
$sql_request = "
    SELECT dr.document_name, dr.requested_at, ud.f_name, ud.m_name, ud.l_name, ud.ext_name, ud.gender, ub.birth_date
    FROM document_requests dr
    JOIN user_details ud ON dr.user_id = ud.user_id
    LEFT JOIN user_birthdates ub ON ud.user_id = ub.user_id
    WHERE dr.request_id = :request_id
    LIMIT 1
";
$stmt = $pdo->prepare($sql_request);
$stmt->execute(['request_id' => $request_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$request) {
    exit("Request not found");
}

// Calculate age
$age = !empty($request['birth_date']) ? (new DateTime($request['birth_date']))->diff(new DateTime("now", new DateTimeZone('Asia/Manila')))->y : "N/A";

// Courtesy titles
$gender = $request['gender'] ?? '';
if ($gender === 'Male') {
    $courtesy_titles = 'Mr.';
    $courtesy_title = 'his';
    $courtesy_gender = 'he';
} elseif ($gender === 'Female') {
    $courtesy_titles = 'Ms.';
    $courtesy_title = 'her';
    $courtesy_gender = 'she';
} else {
    $courtesy_titles = 'Mr./Ms.';
    $courtesy_title = 'his/her';
    $courtesy_gender = 'he/she';
}

// Full name
// Prepare full name with middle initial
$middle_initial = !empty($request['m_name']) ? strtoupper(substr($request['m_name'], 0, 1)) . '.' : '';
$full_name = trim($request['f_name'] . ' ' . $middle_initial . ' ' . $request['l_name'] . ' ' . ($request['ext_name'] ?? ''));


// Fetch Barangay Captain
$sql_captain = "
    SELECT CONCAT(ud.f_name,' ',COALESCE(ud.m_name,''),' ',ud.l_name,' ',COALESCE(ud.ext_name,'')) AS full_name
    FROM officials o
    JOIN users u ON o.user_id = u.user_id
    JOIN user_details ud ON u.user_id = ud.user_id
    WHERE o.position = 'Barangay Captain'
    LIMIT 1
";
$captain_name = $pdo->query($sql_captain)->fetch(PDO::FETCH_ASSOC)['full_name'] ?? 'Barangay Captain';

// Fetch Barangay Secretary
$sql_secretary = "
    SELECT CONCAT(ud.f_name,' ',COALESCE(ud.m_name,''),' ',ud.l_name,' ',COALESCE(ud.ext_name,'')) AS full_name
    FROM officials o
    JOIN users u ON o.user_id = u.user_id
    JOIN user_details ud ON u.user_id = ud.user_id
    WHERE o.position = 'Barangay Secretary'
    LIMIT 1
";
$secretary_name = $pdo->query($sql_secretary)->fetch(PDO::FETCH_ASSOC)['full_name'] ?? 'Barangay Secretary';

// Prepare document info
$document_name = $request['document_name'];
$document_date = date('F j, Y', strtotime($request['requested_at']));

// Embed images as Base64
$logo_path = '../../frontend/assets/images/Logo.jpg';
$talavera_path = '../../frontend/assets/images/talavera.png';
$logo_data = base64_encode(file_get_contents($logo_path));
$logo_type = pathinfo($logo_path, PATHINFO_EXTENSION);
$talavera_data = base64_encode(file_get_contents($talavera_path));
$talavera_type = pathinfo($talavera_path, PATHINFO_EXTENSION);

// Document content function
function getDocumentContent($document_name, $full_name, $age, $courtesy_titles, $courtesy_title, $courtesy_gender, $document_date)
{
    switch ($document_name) {
        case "First Time Job Seeker":
            return "
            <div class='text-center mb-6'>
                <h1 class='text-3xl font-bold uppercase'>Barangay Certification</h1>
                <h2 class='text-md font-semibold mb-6'>First Time Jobseekers Assistance Act - R.A. 11261</h2>
                <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
            </div>
            <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
                This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is a qualified availed of RA 11261.
            </p>
            <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
                I further certify that the holder/bearer was informed of <b>{$courtesy_title}</b> rights, including duties and responsibilities through the Oath of Undertaking <b>{$courtesy_gender}</b> has signed.
            </p>
            <div class='mb-12 text-lg'>
                <p>Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.</p>
            </div>
            <p class='text-sm text-gray-700 mt-4'>This certification is valid only until <b>" . date('F j, Y', strtotime('+1 year')) . "</b> (One (1) year from the date of issuance).</p>
            ";

        case "Certificate of Indigency":
            return "
            <div class='text-center mb-6'>
                    <h1 class='text-3xl font-bold uppercase'>Certificate of Indigency</h1>
                    <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
                </div>

                <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
                    This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is known to be financially underprivileged and is hereby recognized as qualified for Barangay indigency assistance.
                </p>

                <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
                    This certification is issued for the purpose of applying for educational, medical, or other social assistance programs, as may be required by government or private institutions.
                </p>

                <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
                    Issued this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
                </p>

            ";

        case "Travel Permit":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Travel Permit</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is hereby permitted to travel on <b>{$document_date}</b> for personal, educational, or official purposes as may be required.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        The bearer of this certificate is requested to present this document to relevant authorities when necessary. This permit is issued by the Barangay Poblacion Sur Office for proper verification and compliance with local ordinances.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Issued this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Certificate of Living Together":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Certificate of Living Together</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is living together with the members of the household as recorded in the official Barangay records.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This certificate is issued upon the request of the concerned individual for verification of household composition and residency purposes.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Issued this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Proof of Income":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Proof of Income</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has an income of <b>__________________</b> as recorded in the official Barangay records.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This certificate is issued upon the request of the concerned individual for income verification purposes.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Issued this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Same Person Certificate":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Same Person Certificate</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that the individual named <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has been verified and confirmed to be the same person as per official Barangay records.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Issued this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Oath of Undertaking":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Oath of Undertaking</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        I, <b>{$courtesy_titles} {$full_name}</b>, of legal age and a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, do hereby solemnly swear and undertake to fulfill all my duties, responsibilities, and obligations as required under the relevant laws and Barangay regulations.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Certificate of Guardianship":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Certificate of Guardianship</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, of legal age and a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, is the lawful guardian of <b>________________</b> as per Barangay records and relevant legal documents.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Barangay Clearance":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Barangay Clearance</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has been verified and found to have no derogatory records as per Barangay records.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This clearance is issued for whatever legal purpose it may serve.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";

        case "Certificate of Residency":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Certificate of Residency</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, {$age} years old, is a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, and has been residing in this Barangay as per our official records.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Endorsement Letter for Mayor":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Endorsement Letter for Mayor</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to formally endorse <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, to the Municipal Mayor for official matters requiring attention or assistance.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Certificate for Electricity":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Certificate for Electricity</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has an active electricity connection at the said residence.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        case "Certificate of Low Income":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Certificate of Low Income</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, belongs to a low-income household based on Barangay records.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Signed this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";

        case "Business Permit":
            return "
    <div class='text-center mb-6'>
        <h1 class='text-3xl font-bold uppercase'>Business Permit</h1>
        <div class='text-right text-sm text-gray-800 mt-2'>{$document_date}</div>
    </div>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This is to certify that <b>{$courtesy_titles} {$full_name}</b>, of legal age and a resident of Barangay Poblacion Sur, Talavera, Nueva Ecija, has been granted permission to operate a business within the jurisdiction of this Barangay.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        The business is registered under the name <b>________________________</b> and is located at <b>________________________</b>. The said business is hereby authorized to engage in lawful trade and services in accordance with Barangay and Municipal ordinances.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        This permit shall remain valid until <b>" . date('F j, Y', strtotime('+1 year')) . "</b>, unless revoked for violation of existing rules and regulations.
    </p>

    <p class='text-justify' style='line-height:1.6; font-size:1.1em;'>
        Issued this <span class='font-semibold'>" . date('jS') . "</span> day of <span class='font-semibold'>" . date('F Y') . "</span> in the City/Municipality of Talavera, Province of Nueva Ecija.
    </p>
    ";


        default:
            return "<p class='text-justify' style='line-height:1.6; font-size:1.1em;'>Document format not available for <b>{$document_name}</b>.</p>";
    }
}

$document_content = getDocumentContent($document_name, $full_name, $age, $courtesy_titles, $courtesy_title, $courtesy_gender, $document_date);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $document_name; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../../assets/images/Logo.webp" type="image/x-icon">
    <link rel="icon" href="../assets/images/Logo.webp" type="image/x-icon">
    <link rel="icon" href="./frontend/assets/images/Logo.webp" type="image/x-icon">
</head>

<body class="bg-gray-100">

    <div id="documentModal" class="min-h-screen flex flex-col fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full p-8 relative overflow-y-auto">

            <!-- Document Header -->
            <header class="flex items-center justify-between mb-8 border-b border-gray-300 pb-4">
                <img src="data:image/<?php echo $logo_type; ?>;base64,<?php echo $logo_data; ?>"
                    class="h-24 w-24 object-contain">
                <div class="text-center">
                    <h1 class="text-2xl font-bold uppercase">Municipality of Talavera</h1>
                    <h2 class="text-lg font-semibold uppercase">Province of Nueva Ecija</h2>
                    <h3 class="text-sm italic uppercase">Barangay Poblacion Sur</h3>
                </div>
                <img src="data:image/<?php echo $talavera_type; ?>;base64,<?php echo $talavera_data; ?>"
                    class="h-24 w-24 object-contain">
            </header>

            <!-- Document Content -->
            <main
                class="bg-white rounded-lg p-8 text-justify text-gray-800 font-serif leading-relaxed shadow-md border border-gray-200">
                <!-- Document Body -->
                <div class="mb-8 space-y-4 text-lg">
                    <?php echo $document_content; ?>
                </div>

                <!-- Signatures -->
                <div class="grid grid-cols-2 gap-12 mt-12">
                    <div class="text-center">
                        <p class="font-medium uppercase text-gray-700">Prepared by:</p>
                        <p class="underline font-semibold mt-6 text-lg"><?php echo $secretary_name; ?></p>
                        <p class="text-md text-gray-700">Barangay Secretary</p>
                    </div>
                    <div class="text-center">
                        <p class="font-medium uppercase text-gray-700">Approved by:</p>
                        <p class="underline font-semibold mt-6 text-lg"><?php echo $captain_name; ?></p>
                        <p class="text-md text-gray-700">Barangay Captain</p>
                    </div>
                </div>

            </main>

            <!-- Download Button -->
            <div class="flex justify-end gap-4 mt-6">
                <a href="download_document.php?request_id=<?php echo $request_id; ?>"
                    class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition duration-200">
                    Download Word
                </a>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('documentModal').classList.remove('hidden');
            document.getElementById('documentModal').classList.add('flex');
        }

        openModal();
    </script>
</body>

</html>