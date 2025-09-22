<body class="bg-gray-50 font-sans">
<!-- Page Header -->
<section class="bg-indigo-700 text-white py-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Barangay Services</h1>
        <p class="text-lg md:text-xl">Explore the services we provide to our community.</p>
    </div>
</section>
<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

            <!-- Business Permit -->
            <div onclick="showLoginPrompt()" class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                <i class="fas fa-briefcase text-indigo-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Business Permit</h3>
                <p class="text-gray-600 mb-4">Apply for or renew your barangay business permit quickly and easily.</p>
                <button type="button" onclick="showLoginPrompt()" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition mt-auto">Request Service</button>
            </div>

            <!-- Clearance -->
            <div onclick="showLoginPrompt()" class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                <i class="fas fa-id-card text-yellow-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Barangay Clearance</h3>
                <p class="text-gray-600 mb-4">Request official clearance for employment, business, or residency purposes.</p>
                <button type="button" onclick="showLoginPrompt()" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition mt-auto">Request Service</button>
            </div>

            <!-- Documents -->
            <div onclick="showLoginPrompt()" class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                <i class="fas fa-file-alt text-green-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Documents</h3>
                <p class="text-gray-600 mb-4">Request barangay forms, permits, and official documents conveniently online.</p>
                <button type="button" onclick="showLoginPrompt()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition mt-auto">Request Documents</button>
            </div>

            <!-- Indigency Certificate -->
            <div onclick="showLoginPrompt()" class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                <i class="fas fa-hand-holding-heart text-red-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Certificate of Indigency</h3>
                <p class="text-gray-600 mb-4">Request a certificate to verify indigent status for assistance programs.</p>
                <button type="button" onclick="showLoginPrompt()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition mt-auto">Request Certificate</button>
            </div>

            <!-- Residency -->
            <div onclick="showLoginPrompt()" class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                <i class="fas fa-home text-pink-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Residency Certificate</h3>
                <p class="text-gray-600 mb-4">Request an official certificate confirming your residency in the barangay.</p>
                <button type="button" onclick="showLoginPrompt()" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition mt-auto">Request Certificate</button>
            </div>

            <!-- Health Services -->
            <div onclick="showLoginPrompt()" class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition cursor-pointer flex flex-col items-center text-center">
                <i class="fas fa-notes-medical text-teal-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Health Services</h3>
                <p class="text-gray-600 mb-4">Access barangay health programs, vaccination schedules, and medical assistance.</p>
                <button type="button" onclick="showLoginPrompt()" class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 transition mt-auto">Request Service</button>
            </div>

        </div>
    </div>
</section>
