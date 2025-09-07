<?php
session_start();
require_once "../../../backend/config/db.php";
require_once "../../../backend/auth/login.php";
redirectIfNotLoggedIn(['../login.php'], $pdo);

$user_id = $_SESSION['user_id'];
$name    = $_SESSION['name'] ?? "Resident";


// Fetch recent document requests for the logged-in user only
$stmt = $pdo->prepare("
    SELECT document_name, status, requested_at 
    FROM document_requests 
    WHERE user_id = :user_id 
    ORDER BY requested_at DESC 
    LIMIT 5
");
$stmt->execute(['user_id' => $user_id]);
$documentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard | Barangay Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="icon" href="../../assets/images/Logo.webp" type="image/x-icon">
</head>
<body class="bg-gray-100">

<?php include('../../components/DashNav.php'); ?>

<main class="pt-24 px-4 sm:px-6 lg:px-10 space-y-8">

    <!-- Welcome Section -->


    <!-- Quick Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Request Document -->
        <div onclick="openRequestModal()" class="bg-white shadow-lg rounded-xl p-5 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-xl transition cursor-pointer">
            <div>
                <h2 class="text-xs sm:text-sm font-medium text-gray-500 uppercase">Request Document</h2>
                <p class="text-2xl sm:text-3xl font-extrabold text-gray-800 mt-2">New</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full text-blue-500 text-3xl">
                <i class="fa-solid fa-file-circle-plus"></i>
            </div>
        </div>
    </div>

    <!-- Recent Document Requests -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Recent Document Requests</h2>
        <?php if(count($documentRequests) > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left p-2">Document</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">Requested At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($documentRequests as $doc): ?>
                    <tr class="border-t">
                        <td class="p-2"><?= htmlspecialchars($doc['document_name']) ?></td>
                        <td class="p-2"><?= htmlspecialchars($doc['status']) ?></td>
                        <td class="p-2"><?= date('M d, Y', strtotime($doc['requested_at'])) ?></td>
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

<!-- Request Document Modal -->
<div id="requestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-auto p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg md:max-w-xl lg:max-w-2xl p-6 relative mx-auto my-6">
        <button onclick="closeRequestModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <form action="submit_request.php" method="POST" class="space-y-4 overflow-auto max-h-[80vh]">
            <div x-data="{ open: false, selected: '', options: [
                'Barangay Indigency (Format)','First Time Job Seeker','Oath of Undertaking','Non Residency Certificate',
                'Travel Permit','Cert of Guardianship','Brgy Clearance','Indigency for Medical','Indigency for CHED',
                'Same Person Certificate','Residency','Endorsment Letter for Mayor','Certificate for Electricity',
                'Certificate of Low Income','Cert of Good Moral','Cert of Living Together Proof of Income'
            ]}" class="relative w-full">
                <label class="block text-gray-700 mb-1 font-medium">Document Name</label>
                <div @click="open = !open" class="w-full border border-gray-300 rounded-lg p-2 pl-3 pr-10 text-gray-700 cursor-pointer flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <span x-text="selected || 'Select a document'"></span>
                    <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="text-gray-400"></i>
                </div>
                <ul x-show="open" @click.outside="open = false" x-transition class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto text-gray-700">
                    <template x-for="option in options" :key="option">
                        <li @click="selected = option; open = false" class="px-3 py-2 hover:bg-blue-100 cursor-pointer" :class="selected === option ? 'bg-blue-200 font-semibold' : ''" x-text="option"></li>
                    </template>
                </ul>
                <input type="hidden" name="document_name" :value="selected" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Purpose</label>
                <textarea name="purpose" class="w-full border p-2 rounded resize-none" rows="4" required></textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full md:w-auto">Submit Request</button>
        </form>
    </div>
</div>

<script>
    // Modal Functions
    function openRequestModal() {
        document.getElementById('requestModal').classList.remove('hidden');
        document.getElementById('requestModal').classList.add('flex');
    }
    function closeRequestModal() {
        document.getElementById('requestModal').classList.add('hidden');
        document.getElementById('requestModal').classList.remove('flex');
    }

    // Carousel Logic
    const slidePositions = {};
    function nextSlide(id) { slidePositions[id] = (slidePositions[id]||0)+1; updateCarousel(id); }
    function prevSlide(id) { slidePositions[id] = (slidePositions[id]||0)-1; updateCarousel(id); }
    function updateCarousel(id){
        const carousel = document.getElementById(id);
        if(slidePositions[id]>=carousel.children.length) slidePositions[id]=0;
        if(slidePositions[id]<0) slidePositions[id]=carousel.children.length-1;
        carousel.style.transform = `translateX(-${carousel.children[0].offsetWidth*slidePositions[id]}px)`;
    }
    setInterval(()=>nextSlide('eventsCarousel'),5000);
    setInterval(()=>nextSlide('announcementsCarousel'),7000);

    // Logout Confirmation
    function confirmLogout() {
        Swal.fire({
            title:'Are you sure?',
            text:"You will be logged out.",
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            cancelButtonColor:'#3085d6',
            confirmButtonText:'Yes, log me out',
            cancelButtonText:'Cancel'
        }).then((result)=>{
            if(result.isConfirmed) window.location.href='../../../backend/auth/logout.php';
        });
    }
</script>
</body>
</html>
