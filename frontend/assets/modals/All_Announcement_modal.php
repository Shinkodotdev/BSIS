<!-- Modal -->
<div id="viewModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-5 pt-10 overflow-y-auto">
    <div class="bg-white w-full max-w-1xl rounded-lg shadow-lg p-6 relative">
        <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>
        <h2 class="text-xl font-bold mb-4">📢 Announcement Details</h2>
        <div id="modalContent" class="space-y-2 text-sm text-gray-700 grid grid-cols-2"></div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-start md:items-center justify-center z-50 p-5 overflow-y-auto">
    <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg p-6 relative mt-10 md:mt-0">
        <!-- Close button -->
        <button id="closeEditModal"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl leading-none">&times;</button>

        <!-- Title -->
        <h2 class="text-lg sm:text-xl md:text-2xl font-bold mb-6 text-center md:text-left">
            ✏️ Edit Announcement
        </h2>

        <!-- Form -->
        <form id="editForm" class="grid grid-cols-2 md:grid-cols-4 gap-4" enctype="multipart/form-data">
            <input type="hidden" name="announcement_id" id="edit_id">

            <!-- Title -->
            <div class="col-span-2 md:col-span-4">
                <label class="font-semibold">Title</label>
                <input type="text" name="announcement_title" id="edit_title" class="w-full border rounded px-3 py-2"
                    required>
            </div>

            <!-- Content -->
            <div class="col-span-2 md:col-span-4">
                <label class="font-semibold">Content</label>
                <textarea name="announcement_content" id="edit_content" class="w-full border rounded px-3 py-2" rows="4"
                    required></textarea>
            </div>

            <!-- Category -->
            <div>
                <label class="font-semibold">Category</label>
                <select name="announcement_category" id="edit_category" class="w-full border rounded px-3 py-2"
                    required>
                    <option value="General">General</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Event">Event</option>
                    <option value="Health">Health</option>
                    <option value="Other">Other</option>
                </select>
            </div>


            <!-- Location -->
            <div>
                <label class="font-semibold">Location</label>
                <input type="text" name="announcement_location" id="edit_location"
                    class="w-full border rounded px-3 py-2">
            </div>

            <!-- Audience -->
            <div>
                <label class="font-semibold">Audience</label>
                <select name="audience" id="edit_audience" class="w-full border rounded px-3 py-2">
                    <option value="Public">Public</option>
                    <option value="Residents">Residents</option>
                    <option value="Officials">Officials</option>
                </select>
            </div>

            <!-- Priority -->
            <div>
                <label class="font-semibold">Priority</label>
                <select name="priority" id="edit_priority" class="w-full border rounded px-3 py-2">
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>

            <!-- Image -->
            <div>
                <label class="font-semibold">Image</label>
                <input type="file" name="announcement_image" id="edit_image" class="w-full border rounded px-3 py-2">
                <div id="currentImage" class="mt-2"></div>
            </div>

            <!-- Attachment -->
            <div>
                <label class="font-semibold">Attachment</label>
                <input type="file" name="attachment" id="edit_attachment" class="w-full border rounded px-3 py-2">
                <div id="currentAttachment" class="mt-2"></div>
            </div>

            <!-- Status -->
            <div>
                <label class="font-semibold">Status</label>
                <select name="status" id="edit_status" class="w-full border rounded px-3 py-2">
                    <option value="Published">Published</option>
                    <option value="Draft">Draft</option>
                    <option value="Archived">Archived</option>
                </select>
            </div>

            <!-- Valid Until -->
            <div>
                <label class="font-semibold">Valid Until</label>
                <input type="date" name="valid_until" id="edit_valid_until" class="w-full border rounded px-3 py-2">
            </div>

            <!-- Submit Button -->
            <div class="col-span-2 md:col-span-4 flex justify-center">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // === Edit Logic (robust) ===
    const editModal = document.getElementById("editModal");
    const closeEditModal = document.getElementById("closeEditModal");

    function toInputDate(value) {
        if (!value) return "";
        if (value === "0000-00-00" || value === "0000-00-00 00:00:00") return "";
        const first10 = value.slice(0, 10);
        if (/^\d{4}-\d{2}-\d{2}$/.test(first10)) return first10;
        const d = new Date(value);
        if (!isNaN(d)) {
            return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
        }
        return "";
    }

    function parseDataAnnouncement(raw) {
        if (!raw) return null;
        try {
            return JSON.parse(raw);
        } catch {
            const unescaped = raw.replace(/&quot;/g, '"').replace(/&#039;/g, "'");
            try {
                return JSON.parse(unescaped);
            } catch (err2) {
                console.error("Failed to parse data-announcement", err2, raw);
                return null;
            }
        }
    }

    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const data = parseDataAnnouncement(btn.getAttribute("data-announcement"));
            if (!data) return;

            document.getElementById("edit_id").value = data.announcement_id ?? "";
            document.getElementById("edit_title").value = data.announcement_title ?? "";
            document.getElementById("edit_content").value = data.announcement_content ?? "";
            document.getElementById("edit_category").value = data.announcement_category ?? "";
            document.getElementById("edit_location").value = data.announcement_location ?? "";
            document.getElementById("edit_status").value = data.status ?? "";
            document.getElementById("edit_audience").value = data.audience ?? "";
            document.getElementById("edit_priority").value = data.priority ?? "";
            document.getElementById("edit_valid_until").value = toInputDate(data.valid_until ?? "");

            // Image preview
            document.getElementById("currentImage").innerHTML = data.announcement_image
                ? `<img src="../../uploads/announcement/${data.announcement_image}" alt="Current Image" class="mt-2 rounded border max-h-32 object-contain">
               <input type="hidden" name="existing_image" value="${data.announcement_image}">`
                : `<span class="text-gray-500">No image uploaded</span>`;

            // Attachment preview
            document.getElementById("currentAttachment").innerHTML = data.attachment
                ? `<a href="../../uploads/announcement/${data.attachment}" target="_blank" class="text-blue-600 underline">Download current attachment</a>
               <input type="hidden" name="existing_attachment" value="${data.attachment}">`
                : `<span class="text-gray-500">No attachment uploaded</span>`;

            editModal.classList.remove("hidden");
        });
    });

    // Close modal
    closeEditModal.addEventListener("click", () => editModal.classList.add("hidden"));

    // Submit update
    document.getElementById("editForm").addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const res = await fetch("../../../backend/actions/update_announcement.php", {
                method: "POST",
                body: formData
            });
            const result = await res.json();

            if (result.success) {
                Swal.fire("✅ Updated!", result.message, "success").then(() => location.reload());
            } else {
                Swal.fire("❌ Error", result.message, "error");
            }
        } catch (err) {
            Swal.fire("⚠️ Failed", "Something went wrong!", "error");
        }
    });
</script>
<script>
    function formatDate(dateString) {
        if (!dateString) return "N/A";
        const date = new Date(dateString);
        return date.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric"
        });
    }
    const modal = document.getElementById("viewModal");
    const modalContent = document.getElementById("modalContent");
    const closeModal = document.getElementById("closeModal");

    document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const data = JSON.parse(btn.getAttribute("data-announcement"));
            modalContent.innerHTML = `
                    <p><span class="font-semibold">📌 Title:</span> ${data.announcement_title}</p>
                    <p><span class="font-semibold">📝 Content:</span> ${data.announcement_content}</p>
                    <p><span class="font-semibold">📂 Category:</span> ${data.announcement_category}</p>
                    <p><span class="font-semibold">📍 Location:</span> ${data.announcement_location}</p>
                    

                    <p><span class="font-semibold">👤 Author:</span> ${data.full_name}</p>
                    <p><span class="font-semibold">📢 Status:</span> ${data.status}</p>
                    <p><span class="font-semibold">⚡ Priority:</span> ${data.priority}</p>
                    <p><span class="font-semibold">🎯 Audience:</span> ${data.audience}</p>
                    <p><span class="font-semibold">📅 Valid Until:</span> ${formatDate(data.valid_until)}</p>
            <p><span class="font-semibold">🕒 Created:</span> ${formatDate(data.created_at)}</p>
            <p><span class="font-semibold">🕒 Updated:</span> ${formatDate(data.updated_at)}</p>
            <!-- Show Image -->
                <div class="col-span-2">
                <p class="font-semibold">🖼️ Image:</p>
                ${data.announcement_image
                    ? `<img src="../../uploads/announcement/${data.announcement_image}" 
                            alt="Announcement Image" 
                            class="mt-2 rounded-lg border shadow-sm max-h-56 object-contain">`
                    : `<span class="text-gray-500">No Image Available</span>`}
                </div>

                <!-- Show Attachment -->
                <div class="col-span-2 mt-4">
                <p class="font-semibold">📎 Attachment:</p>
                ${data.attachment
                    ? `<a href="../../uploads/announcement/${data.attachment}" 
                        target="_blank" 
                        class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        ⬇ Download Attachment
                    </a>`
                    : `<span class="text-gray-500">No Attachment Available</span>`}
                </div>

                `;
            modal.classList.remove("hidden");
        });
    });
    closeModal.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });
</script>
<script>
    // === Archive Logic ===
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.getAttribute("data-id");

            Swal.fire({
                title: "Are you sure?",
                text: "This announcement will be archived (not permanently deleted).",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e3342f",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, archive it!"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch("../../../backend/actions/delete_announcement.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: new URLSearchParams({
                                id: id,
                                action: "archive"
                            })
                        });

                        const data = await res.json();

                        if (data.success) {
                            Swal.fire("📦 Archived!", data.message, "success")
                                .then(() => location.reload());
                        } else {
                            Swal.fire("❌ Error", data.message || "Unknown error occurred", "error");
                        }
                    } catch (err) {
                        Swal.fire("⚠️ Failed", "Server error occurred!", "error");
                    }
                }
            });
        });
    });


</script>