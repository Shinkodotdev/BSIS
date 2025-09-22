function formatDate(dateStr){ 
    if (!dateStr) return "N/A";
    const date = new Date(dateStr);
    if (isNaN(date)) return "N/A";
    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric"
    });
}

function viewUser(userId) {
    fetch("../../../backend/actions/get_user_details.php?id=" + userId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire('Error', data.error, 'error');
                return;
            }

            const baseURL = "/Barangay_Information_System";

            // Profile photo
            const profilePhoto = data.photo 
                ? `${baseURL}/uploads/profile/${data.photo}`
                : `${baseURL}/assets/images/user.png`;

            // Identity docs
            const frontID = data.front_valid_id_path ? `${baseURL}/uploads/ids/front/${data.front_valid_id_path}` : "";
            const backID  = data.back_valid_id_path ? `${baseURL}/uploads/ids/back/${data.back_valid_id_path}` : "";
            const selfieID = data.selfie_with_id ? `${baseURL}/uploads/ids/selfie/${data.selfie_with_id}` : "";

            // Modal content
            let content = `
                <!-- Profile Photo -->
                <div class="flex justify-center mb-6">
                    <img src="${profilePhoto}" alt="Profile Photo" class="w-32 h-32 object-cover rounded-full shadow-lg border-4 border-indigo-200">
                </div>

                <!-- Personal Information -->
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Personal Information</h3>
                    <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                        <p><span class="font-medium">Name:</span> ${data.f_name} ${data.m_name || ""} ${data.l_name} ${data.ext_name || ""}</p>
                        <p><span class="font-medium">Email:</span> ${data.email}</p>
                        <p><span class="font-medium">Gender:</span> ${data.gender || "N/A"}</p>
                        <p><span class="font-medium">Contact:</span> ${data.contact_no || "N/A"}</p>
                        <p><span class="font-medium">Civil Status:</span> ${data.civil_status || "N/A"}</p>
                        <p><span class="font-medium">Occupation:</span> ${data.occupation || "N/A"}</p>
                        <p><span class="font-medium">Nationality:</span> ${data.nationality || "N/A"}</p>
                        <p><span class="font-medium">Religion:</span> ${data.religion || "N/A"}</p>
                        <p><span class="font-medium">Blood Type:</span> ${data.blood_type || "N/A"}</p>
                        <p><span class="font-medium">Education:</span> ${data.educational_attainment || "N/A"}</p>
                    </div>
                </div>

    <!-- Birth Information -->
    <div class="bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Birth Information</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <p><span class="font-medium">Birth Date:</span> ${formatDate(data.birth_date)}</p>
            <p><span class="font-medium">Birth Place:</span> ${data.birth_place || "N/A"
                }</p>
        </div>
    </div>

    <!-- Residency -->
    <div class="bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Residency</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <p><span class="font-medium">Bldg/House No:</span> ${data.house_no || "N/A"
                }  </p>
            <p><span class="font-medium">Purok:</span>${data.purok || "N/A"}</p>
            <p><span class="font-medium">Barangay:</span>${data.barangay || "N/A"
                }</p>
            <p><span class="font-medium">Municipality:</span> ${data.municipality || "N/A"
                }</p>
            <p><span class="font-medium">Province:</span> ${data.province || "N/A"
                }</p>
            <p><span class="font-medium">Years of Residency:</span> ${data.years_residency || "N/A"
                }</p>
            <p><span class="font-medium">Household Head:</span> ${data.household_head || "N/A"
                }</p>
            <p><span class="font-medium">House Type:</span> ${data.house_type || "N/A"
                }</p>
            <p><span class="font-medium">Ownership:</span> ${data.ownership_status || "N/A"
                }</p>
            <p><span class="font-medium">Previous Address:</span> ${data.previous_address || "N/A"
                }</p>
        </div>
    </div>

    <!-- Family Information -->
    <div class="bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Family Information</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <p><span class="font-medium">Father:</span> ${data.fathers_name || "N/A"
                } </p>
                <p><span class="font-medium">Father's Birth Place:</span>${data.fathers_birthplace || "N/A"
                })</p>
            <p><span class="font-medium">Mother:</span> ${data.mothers_name || "N/A"
                }</p>
                <p><span class="font-medium">Mother's Birth Place:</span> ${data.mothers_birthplace || "N/A"
                })</p>
            <p><span class="font-medium">Spouse:</span> ${data.spouse_name || "N/A"
                }</p>
            <p><span class="font-medium">Dependents:</span> ${data.num_dependents || "N/A"
                }</p>
            <p><span class="font-medium">Emergency Contact:</span> ${data.contact_person || "N/A"
                } - ${data.emergency_contact_no || "N/A"}</p>
        </div>
    </div>

    <!-- Health Information -->
    <div class="bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Health Information</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <p><span class="font-medium">Condition:</span> ${data.health_condition || "N/A"
                }</p>
            <p><span class="font-medium">Common Issues:</span> ${data.common_health_issue || "N/A"
                }</p>
            <p><span class="font-medium">Vaccination:</span> ${data.vaccination_status || "N/A"
                }</p>
            <p><span class="font-medium">Height:</span> ${data.height_cm || "N/A"
                } cm</p>
            <p><span class="font-medium">Weight:</span> ${data.weight_kg || "N/A"
                } kg</p>
            <p><span class="font-medium">Last Checkup:</span> ${formatDate(data.last_medical_checkup)}</p>
            <p><span class="font-medium">Remarks:</span> ${data.health_remarks || "N/A"
                }</p>
        </div>
    </div>

    <!-- Income Information -->
    <div class="bg-gray-50 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Income Information</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <p><span class="font-medium">Monthly Income:</span> ${data.monthly_income || "N/A"
                }</p>
            <p><span class="font-medium">Source:</span> ${data.income_source || "N/A"
                }</p>
            <p><span class="font-medium">Household Members:</span> ${data.household_members || "N/A"
                }</p>
            <p><span class="font-medium">Additional Sources:</span> ${data.additional_income_sources || "N/A"
                }</p>
            <p><span class="font-medium">Household Head Occupation:</span> ${data.household_head_occupation || "N/A"
                }</p>
        </div>
    </div>

    <!-- Identity Documents -->
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Identity Documents</h3>
                    <p><span class="font-medium">ID Type:</span> ${data.id_type || "N/A"}</p>
                    <div class="flex gap-4 mt-3">
                        ${frontID ? `<img src="${frontID}" class="w-32 h-20 object-cover border rounded-lg shadow">` : ""}
                        ${backID ? `<img src="${backID}" class="w-32 h-20 object-cover border rounded-lg shadow">` : ""}
                        ${selfieID ? `<img src="${selfieID}" class="w-32 h-20 object-cover border rounded-lg shadow">` : ""}
                    </div>
                </div>
            </div>

            `;

            document.getElementById("userDetailsContent").innerHTML = content;
            document.getElementById("userDetailsModal").classList.remove("hidden");
            document.getElementById("userDetailsModal").classList.add("flex");
        });
}
function closeUserModal() {
    document.getElementById("userDetailsModal").classList.add("hidden");
    document.getElementById("userDetailsModal").classList.remove("flex");
}
