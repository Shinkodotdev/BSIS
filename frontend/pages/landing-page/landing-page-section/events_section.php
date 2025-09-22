    <main>
        <section id="Events">
            <div class="container mx-auto p-6">
                <h1 class="text-3xl font-bold mb-4 text-center text-indigo-700">🎉 Barangay Events</h1>
                <p class="text-gray-600 text-center mb-8">
                    Stay updated with upcoming activities and events in your barangay.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (!empty($Events)): ?>
                        <?php foreach ($Events as $event): ?>
                            <?php
                            $image = !empty($event['event_image'])
                                ? '../../uploads/events/' . htmlspecialchars($event['event_image'])
                                : '../../assets/images/home.jpg';

                            $now = new DateTime();
                            $event_end = new DateTime($event['event_end']);
                            $recent_threshold = (clone $event_end)->modify('+3 days');
                            $is_recent = $now > $event_end && $now <= $recent_threshold;
                            ?>
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col cursor-pointer hover:shadow-lg transition"
                                onclick="openEventModal(<?= $event['event_id'] ?>)">

                                <!-- Image -->
                                <img src="<?= $image ?>"
                                    alt="<?= htmlspecialchars($event['event_title']) ?>"
                                    class="w-full h-40 object-cover rounded-lg mb-3"
                                    onerror="this.onerror=null;this.src='../../assets/images/home.jpg';">

                                <!-- Title -->
                                <h2 class="text-lg font-semibold mb-2">
                                    <?= htmlspecialchars($event['event_title']) ?>
                                    <?php if ($is_recent): ?>
                                        <span class="text-sm text-gray-500">(Recent)</span>
                                    <?php endif; ?>
                                </h2>

                                <!-- Date -->
                                <p class="text-sm text-gray-600 mb-1">
                                    📅 <?= date("M d, Y h:i A", strtotime($event['event_start'])) ?>
                                    - <?= date("h:i A", strtotime($event['event_end'])) ?>
                                </p>

                                <!-- Countdown -->
                                <p id="countdown-<?= $event['event_id'] ?>"
                                    class="text-sm font-semibold <?= $is_recent ? 'text-gray-400' : 'text-red-600' ?> mb-2"></p>

                                <!-- Location -->
                                <p class="text-sm text-gray-600 mb-2">
                                    📍 <?= htmlspecialchars($event['event_location']) ?>
                                </p>

                                <!-- Content Preview -->
                                <p class="text-gray-500 text-sm flex-grow">
                                    <?= nl2br(htmlspecialchars(mb_substr($event['event_description'], 0, 100))) ?>...
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full">
                            <div class="text-center shadow-sm p-6 bg-white rounded-lg">
                                <h5 class="text-xl font-semibold text-gray-700">No Events Found</h5>
                                <p class="text-gray-500">Check back later for updates</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
            <button onclick="closeEventModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
            <div id="modalContent"></div>
        </div>
    </div>