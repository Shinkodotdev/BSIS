<!-- Announcements & Events Showcase -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Announcements Carousel -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Announcements</h2>
        <?php if(count($announcements) > 0): ?>
            <div id="announcementsCarousel" class="overflow-hidden relative">
                <div class="flex transition-transform duration-500">
                    <?php foreach($announcements as $ann): 
                        $image = $ann['announcement_image'] ? "../../uploads/announcements/{$ann['announcement_image']}" : "../../../frontend/assets/images/Logo.webp";
                    ?>
                        <div class="min-w-full p-4 border rounded-lg mr-4 bg-yellow-50 flex flex-col lg:flex-row gap-4">
                            <img src="<?= $image ?>" alt="Announcement Image" class="w-full lg:w-48 h-32 object-cover rounded-lg flex-shrink-0">
                            <div>
                                <h3 class="font-semibold text-yellow-700"><?= htmlspecialchars($ann['announcement_title']) ?></h3>
                                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($ann['announcement_content']) ?></p>
                                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($ann['announcement_category']) ?></p>
                                <p class="text-xs text-gray-400 mt-2"><?= date('M d, Y', strtotime($ann['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-gray-500">No announcements available.</p>
        <?php endif; ?>
    </div>

    <!-- Events Carousel -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Events</h2>
        <?php if(count($events) > 0): ?>
            <div id="eventsCarousel" class="overflow-hidden relative">
                <div class="flex transition-transform duration-500">
                    <?php foreach($events as $event): 
                        $image = $event['event_image'] ? "../../uploads/events/{$event['event_image']}" : "../../../frontend/assets/images/Logo.webp";
                    ?>
                        <div class="min-w-full p-4 border rounded-lg mr-4 bg-green-50 flex flex-col lg:flex-row gap-4">
                            <img src="<?= $image ?>" alt="Event Image" class="w-full lg:w-48 h-32 object-cover rounded-lg flex-shrink-0">
                            <div>
                                <h3 class="font-semibold text-green-700"><?= htmlspecialchars($event['event_title']) ?></h3>
                                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($event['event_description']) ?></p>
                                <p class="text-xs text-gray-400 mt-2">
                                    <?= date('M d, Y H:i', strtotime($event['event_start'])) ?> 
                                    to <?= date('M d, Y H:i', strtotime($event['event_end'])) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-gray-500">No events available.</p>
        <?php endif; ?>
    </div>
</div>