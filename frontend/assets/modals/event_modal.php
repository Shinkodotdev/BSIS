<!-- Event Form Modal (similar to announcement) -->
<div id="eventFormModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
        <button onclick="closeEventForm()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <h2 class="text-2xl font-bold mb-4">Event Form</h2>
        <form id="eventForm" method="POST" enctype="multipart/form-data" action="../../../backend/actions/event_crud.php">
            <input type="hidden" name="event_id" id="event_id">

            <!-- Title -->
            <div class="mb-4">
                <label class="font-semibold">Title</label>
                <input type="text" name="event_title" id="event_title" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="font-semibold">Description</label>
                <textarea name="event_description" id="event_description" class="w-full border rounded px-3 py-2" required></textarea>
            </div>

            <!-- Start & End Date -->
            <div class="mb-4 grid grid-cols-2 gap-2">
                <div>
                    <label class="font-semibold">Start</label>
                    <input type="datetime-local" name="event_start" id="event_start" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="font-semibold">End</label>
                    <input type="datetime-local" name="event_end" id="event_end" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label class="font-semibold">Location</label>
                <input type="text" name="event_location" id="event_location" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Event Type -->
            <div class="mb-4">
                <label class="font-semibold">Event Type</label>
                <select name="event_type" id="event_type" class="w-full border rounded px-3 py-2" required>
                    <option value="Community">Community</option>
                    <option value="Cultural">Cultural</option>
                    <option value="Health">Health</option>
                    <option value="Sports">Sports</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="font-semibold">Status</label>
                <select name="status" id="status" class="w-full border rounded px-3 py-2" required>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Audience -->
            <div class="mb-4">
                <label class="font-semibold">Audience</label>
                <select name="audience" id="event_audience" class="w-full border rounded px-3 py-2" required>
                    <option value="Public">Public</option>
                    <?php if ($_SESSION['role'] !== 'Official'): ?>
                        <option value="Residents">Residents</option>
                    <?php endif; ?>
                    <option value="Officials">Officials</option>
                </select>
            </div>

            <!-- Event Image -->
<div class="mb-4">
    <label class="font-semibold">Event Image (optional)</label>
    <input type="file" name="event_image" class="w-full">
    <!-- Hidden input to store old event image -->
    <input type="hidden" name="old_event_image" id="old_event_image">
</div>

<!-- Attachment -->
<div class="mb-4">
    <label class="font-semibold">Attachment (optional)</label>
    <input type="file" name="attachment" class="w-full">
    <!-- Hidden input to store old attachment -->
    <input type="hidden" name="old_attachment" id="old_attachment">
</div>


            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <button type="submit" name="create" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
                <button type="button" onclick="closeEventForm()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
            </div>
        </form>
    </div>
</div>


<!-- View Event Modal -->
<div id="viewEventModal" 
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
        <button onclick="closeViewEvent()" 
            class="absolute top-3 right-3 text-gray-600 hover:text-black">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <h2 class="text-2xl font-bold mb-4 text-green-700" id="view_event_title"></h2>
        <img id="view_event_image" class="w-full h-60 object-cover rounded-lg mb-4" 
            src="" alt="Event Image" onerror="this.src='../../assets/images/home.jpg'">
        <p class="text-gray-600 mb-2" id="view_event_description"></p>
        <p class="text-sm text-gray-500 mb-1">📍 <span id="view_event_location"></span></p>
        <p class="text-sm text-gray-500 mb-1">📅 <span id="view_event_schedule"></span></p>
        <p class="text-sm text-gray-500 mb-1">🎯 Type: <span id="view_event_type"></span></p>
        <p class="text-sm text-gray-500 mb-1">🎯 Status: <span id="view_event_status"></span></p>
        <p class="text-sm">📂 <a id="view_event_attachment" href="#" target="_blank" class="text-blue-600 underline">View Attachment</a></p>
    </div>
</div>
