<section id="Events">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">🎉 Barangay Events</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if (!empty($Events)): ?>
            <?php foreach ($Events as $e): ?>
                <?php
                $image = !empty($e['event_image'])
                    ? '../../uploads/events/' . htmlspecialchars($e['event_image'])
                    : '../../assets/images/home.jpg';

                $now = new DateTime();
                $event_end = new DateTime($e['event_end']);
                $recent_threshold = (clone $event_end)->modify('+3 days');
                $is_recent = $now > $event_end && $now <= $recent_threshold;
                ?>
                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col hover:shadow-lg transition relative">
                    
                    <!-- ✅ Fixed image path -->
                    <img src="<?= $image ?>"
                        alt="<?= htmlspecialchars($e['event_title']) ?>"
                        class="w-full h-40 object-cover rounded-lg mb-3"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">

                    <h2 class="text-lg font-semibold mb-2">
                        <?= htmlspecialchars($e['event_title']) ?>
                        <?php if ($is_recent): ?>
                            <span class="text-sm text-gray-500">(Recent)</span>
                        <?php endif; ?>
                    </h2>

                    <p class="text-sm text-gray-600 mb-1">
                        📅 <?= date("M d, Y h:i A", strtotime($e['event_start'])) ?> - <?= date("h:i A", strtotime($e['event_end'])) ?>
                    </p>

                    <p id="countdown-<?= $e['event_id'] ?>" class="text-sm font-semibold <?= $is_recent ? 'text-gray-400' : 'text-red-600' ?> mb-2"></p>
                    
                    <p class="text-sm text-gray-600 mb-2">📍 <?= htmlspecialchars($e['event_location']) ?></p>
                    
                    <p class="text-gray-500 text-sm flex-grow">
                        <?= nl2br(htmlspecialchars(mb_substr($e['event_description'], 0, 100))) ?>...
                    </p>

                    <!-- Edit/Archive Buttons -->
                    <div class="flex gap-2 mt-2 justify-end">
                        <button onclick='openViewEvent(<?= json_encode($e) ?>)'
                            class="text-white bg-indigo-500 px-2 py-1 rounded hover:bg-indigo-600 text-xs">View</button>
                        <button onclick='openEventForm(<?= json_encode($e) ?>)'
                            class="text-white bg-yellow-500 px-2 py-1 rounded hover:bg-yellow-600 text-xs">Edit</button>
                        <button onclick="confirmDelete('event', <?= $e['event_id'] ?>)"
                            class="text-white bg-red-500 px-2 py-1 rounded hover:bg-red-600 text-xs">Archive</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center shadow-sm p-6 bg-white rounded-lg">
                <h5 class="text-xl font-semibold text-gray-700">No Events Found</h5>
                <p class="text-gray-500">Check back later for updates</p>
            </div>
        <?php endif; ?>
    </div>
</section>
