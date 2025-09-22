<!-- Announcement Form Modal -->
<div id="announcementFormModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
        <!-- Close Button -->
        <button onclick="closeAnnouncementForm()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <!-- Title -->
        <h2 class="text-2xl font-bold mb-4">Announcement Form</h2>

        <form id="announcementForm" method="POST" enctype="multipart/form-data" action="../../../backend/actions/announcement_crud.php">
            <input type="hidden" name="announcement_id" id="announcement_id">

            <!-- Announcement Title -->
            <div class="mb-4">
                <label class="font-semibold">Title</label>
                <input type="text" name="announcement_title" id="announcement_title" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Announcement Content -->
            <div class="mb-4">
                <label class="font-semibold">Content</label>
                <textarea name="announcement_content" id="announcement_content" class="w-full border rounded px-3 py-2" required></textarea>
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label class="font-semibold">Location</label>
                <input type="text" name="announcement_location" id="announcement_location" class="w-full border rounded px-3 py-2">
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label class="font-semibold">Announcement Image (optional)</label>
                <input type="file" name="announcement_image" class="w-full">
            </div>

            <!-- Priority -->
            <div class="mb-4">
                <label class="font-semibold">Priority</label>
                <select name="priority" id="priority" class="w-full border rounded px-3 py-2" required>
                    <option value="Urgent">Urgent</option>
                    <option value="High">High</option>
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                </select>
            </div>

            <!-- Audience -->
            <div class="mb-4">
                <label class="font-semibold">Audience</label>
                <select name="audience" id="audience" class="w-full border rounded px-3 py-2" required>
                    <option value="Public">Public</option>
                    <?php if ($_SESSION['role'] !== 'Official'): ?>
                        <option value="Residents">Residents</option>
                    <?php endif; ?>
                    <option value="Officials">Officials</option>
                </select>
            </div>


            <!-- Status -->
            <div class="mb-4">
                <label class="font-semibold">Status</label>
                <select name="status" id="status" class="w-full border rounded px-3 py-2" required>
                    <option value="Published">Published</option>
                    <option value="Draft">Draft</option>
                </select>
            </div>

            <!-- ✅ Valid Until -->
            <div class="mb-4">
                <label class="font-semibold">Valid Until</label>
                <input type="datetime-local" name="valid_until" id="valid_until" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500">Leave empty if the announcement should not expire</p>
            </div>

            <!-- Attachment -->
            <div class="mb-4">
                <label class="font-semibold">Attachment (optional)</label>
                <input type="file" name="attachment" class="w-full">
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-2">
                <button type="submit" name="create" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
                <button type="button" onclick="closeAnnouncementForm()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- View Announcement Modal -->
<div id="viewAnnouncementModal" 
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
        <button onclick="closeViewAnnouncement()" 
            class="absolute top-3 right-3 text-gray-600 hover:text-black">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <h2 class="text-2xl font-bold mb-4 text-indigo-700" id="view_announcement_title"></h2>
        <img id="view_announcement_image" class="w-full h-60 object-cover rounded-lg mb-4" 
            src="" alt="Announcement Image" onerror="this.src='../../assets/images/home.jpg'">
        <p class="text-gray-600 mb-2" id="view_announcement_content"></p>
        <p class="text-sm text-gray-500 mb-2">📍 <span id="view_announcement_location"></span></p>
        <p class="text-sm text-gray-500 mb-2">👤 <span id="view_announcement_author"></span></p>
        <p class="text-sm mb-2">📌 Priority: <span id="view_announcement_priority" class="font-semibold"></span></p>
        <p class="text-sm">📂 <a id="view_announcement_attachment" href="#" target="_blank" class="text-blue-600 underline">View Attachment</a></p>
    </div>
</div>
