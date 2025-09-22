<?php include('official-head.php'); ?>

<body class="bg-gray-100">
    <?php include('../../components/DashNav.php'); ?>

    <main class="pt-24 px-4 sm:px-6 lg:px-10 space-y-8">
        <!-- Section Header -->
        <div class="bg-gradient-to-r from-indigo-50 to-white shadow-lg rounded-xl p-8 text-center">
            <h1 class="text-3xl font-extrabold mb-3 text-indigo-700">Request a Document</h1>
            <p class="text-gray-600 text-lg">Choose from the services below and submit your request with ease.</p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 p-6">

            <!-- Business Permit -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-briefcase text-indigo-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Business Permit</h3>
                <p class="text-gray-600 mb-4">Apply for or renew your barangay business permit quickly.</p>
                <button type="button" onclick="openRequestModal('Business Permit')"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition mt-auto">
                    Request Permit
                </button>
                <span
                    class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded px-2 py-1">
                    Required for business operations in the barangay.
                </span>
            </div>

            <!-- Barangay Clearance -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-id-card text-yellow-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Barangay Clearance</h3>
                <p class="text-gray-600 mb-4">For employment, business, or residency verification.</p>
                <button type="button" onclick="openRequestModal('Barangay Clearance')"
                    class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition mt-auto">
                    Request Clearance
                </button>
                <span
                    class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded px-2 py-1">
                    Confirms you are a resident in good standing.
                </span>
            </div>

            <!-- First Time Job Seeker -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-user-tie text-blue-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">First Time Job Seeker</h3>
                <p class="text-gray-600 mb-4">Certification for first-time job applicants.</p>
                <button type="button" onclick="openRequestModal('First Time Job Seeker')"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition mt-auto">
                    Request Certificate
                </button>
            </div>

            <!-- Certificate of Indigency -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-hand-holding-heart text-red-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Certificate of Indigency</h3>
                <p class="text-gray-600 mb-4">Proof of financial incapacity.</p>
                <button type="button" onclick="openRequestModal('Certificate of Indigency')"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition mt-auto">
                    Request Certificate
                </button>
            </div>

            <!-- Travel Permit -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-plane-departure text-indigo-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Travel Permit</h3>
                <p class="text-gray-600 mb-4">Required for out-of-town or restricted travel.</p>
                <button type="button" onclick="openRequestModal('Travel Permit')"
                    class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition mt-auto">
                    Request Permit
                </button>
            </div>

            <!-- Certificate of Living Together -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-users text-pink-500 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Certificate of Living Together</h3>
                <p class="text-gray-600 mb-4">Issued to couples living together without marriage.</p>
                <button type="button" onclick="openRequestModal('Certificate of Living Together')"
                    class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition mt-auto">
                    Request Certificate
                </button>
            </div>

            <!-- Proof of Income -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-money-bill-wave text-green-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Proof of Income</h3>
                <p class="text-gray-600 mb-4">Document certifying your household income.</p>
                <button type="button" onclick="openRequestModal('Proof of Income')"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition mt-auto">
                    Request Proof
                </button>
            </div>

            <!-- Same Person Certificate -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-user-check text-teal-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Same Person Certificate</h3>
                <p class="text-gray-600 mb-4">Confirms two names refer to the same individual.</p>
                <button type="button" onclick="openRequestModal('Same Person Certificate')"
                    class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition mt-auto">
                    Request Certificate
                </button>
            </div>

            <!-- Oath of Undertaking -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-scroll text-indigo-700 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Oath of Undertaking</h3>
                <p class="text-gray-600 mb-4">Affidavit declaring personal commitment or compliance.</p>
                <button type="button" onclick="openRequestModal('Oath of Undertaking')"
                    class="px-4 py-2 bg-indigo-700 text-white rounded hover:bg-indigo-800 transition mt-auto">
                    Request Oath
                </button>
            </div>

            <!-- Certificate of Guardianship -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-child text-purple-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Certificate of Guardianship</h3>
                <p class="text-gray-600 mb-4">Issued for legal guardians of minors.</p>
                <button type="button" onclick="openRequestModal('Certificate of Guardianship')"
                    class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition mt-auto">
                    Request Certificate
                </button>
            </div>

            <!-- Certificate of Residency -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-home text-blue-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Certificate of Residency</h3>
                <p class="text-gray-600 mb-4">Confirms you are a resident of the barangay.</p>
                <button type="button" onclick="openRequestModal('Certificate of Residency')"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition mt-auto">
                    Request Residency
                </button>
            </div>

            <!-- Endorsement Letter for Mayor -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-envelope-open-text text-orange-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Endorsement Letter for Mayor</h3>
                <p class="text-gray-600 mb-4">Official endorsement from barangay to the Mayor’s office.</p>
                <button type="button" onclick="openRequestModal('Endorsement Letter for Mayor')"
                    class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 transition mt-auto">
                    Request Endorsement
                </button>
            </div>

            <!-- Certificate for Electricity -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-bolt text-yellow-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Certificate for Electricity</h3>
                <p class="text-gray-600 mb-4">Required for electricity connection applications.</p>
                <button type="button" onclick="openRequestModal('Certificate for Electricity')"
                    class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition mt-auto">
                    Request Certificate
                </button>
            </div>

            <!-- Certificate of Low Income -->
            <div
                class="relative group bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition flex flex-col items-center text-center">
                <i class="fas fa-wallet text-purple-600 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Certificate of Low Income</h3>
                <p class="text-gray-600 mb-4">Proof of low household income for assistance programs.</p>
                <button type="button" onclick="openRequestModal('Certificate of Low Income')"
                    class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition mt-auto">
                    Request Low Income
                </button>
            </div>


        </div>
    </main>

   <!-- Request Modal -->
<div id="requestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 id="modalTitle" class="text-xl font-bold mb-4">Request Document</h2>

        <form id="requestForm" class="space-y-4">
            <input type="hidden" id="documentName" name="document_name">

            <!-- Purpose -->
            <div>
                <label for="purpose" class="block text-sm font-medium">Purpose</label>
                <textarea name="purpose" id="purpose" rows="3" 
                    class="w-full border rounded p-2" required></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRequestModal()" 
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit</button>
            </div>
        </form>
    </div>
</div>


    <script>
        function openRequestModal(documentName) {
            document.getElementById('documentName').value = documentName;
            document.getElementById('modalTitle').innerText = "Request: " + documentName;
            document.getElementById('requestModal').classList.remove('hidden');
        }

        function closeRequestModal() {
            document.getElementById('requestModal').classList.add('hidden');
        }

        // Handle form submit
        document.getElementById('requestForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../../../backend/requests/add_request.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
                .then(data => {
                    alert(data.message || 'Request submitted successfully!');
                    closeRequestModal();
                }).catch(err => {
                    console.error(err);
                    alert('Something went wrong.');
                });
        });
    </script>
</body>