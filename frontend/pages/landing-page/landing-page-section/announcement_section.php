<main>
        <section id="Announcements">
            <div class="container mx-auto p-6">
                <h1 class="text-3xl font-bold mb-4 text-center text-indigo-700">📢 Barangay Announcements</h1>
                <p class="text-gray-600 text-center mb-8">
                    Stay updated with the latest announcements in your barangay.
                </p>
                <!-- ✅ Tailwind Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (!empty($Announcements)): ?>
                        <?php foreach ($Announcements as $announcement): ?>
                            <?php
                            // Fallback image
                            $image = !empty($announcement['announcement_image'])
                                ? '../../uploads/announcement/' . htmlspecialchars($announcement['announcement_image'])
                                : '../../assets/images/home.jpg';

                            // Priority Badge Colors
                            $priorityColors = [
                                'Urgent' => 'bg-red-600 text-white',
                                'High'   => 'bg-orange-500 text-white',
                                'Normal' => 'bg-blue-500 text-white',
                                'Low'    => 'bg-gray-400 text-white'
                            ];
                            $priorityClass = $priorityColors[$announcement['priority']] ?? 'bg-gray-400 text-white';

                            // Priority Icons
                            $priorityIcons = [
                                'Urgent' => 'fa-triangle-exclamation', // ⚠️
                                'High'   => 'fa-arrow-up',             // ⬆️
                                'Normal' => 'fa-minus',                // ➖
                                'Low'    => 'fa-arrow-down',           // ⬇️
                            ];
                            $priorityIcon = $priorityIcons[$announcement['priority']] ?? 'fa-circle';
                            ?>

                            <!-- ✅ Card -->
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col cursor-pointer hover:shadow-lg transition"
                                onclick="openAnnouncementModal(<?= $announcement['announcement_id'] ?>)">

                                <!-- Image -->
                                <img src="<?= $image ?>"
                                    alt="<?= htmlspecialchars($announcement['announcement_title']) ?>"
                                    class="w-full h-40 object-cover rounded-lg mb-3"
                                    onerror="this.onerror=null; this.src='../../assets/images/home.jpg';">

                                <!-- Priority Badge -->
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full <?= $priorityClass ?> mb-2">
                                    <i class="fa-solid <?= $priorityIcon ?>"></i>
                                    <?= htmlspecialchars($announcement['priority']) ?>
                                </span>

                                <!-- Title -->
                                <h2 class="text-lg font-semibold mb-2">
                                    <?= htmlspecialchars($announcement['announcement_title']) ?>
                                </h2>

                                <!-- Content -->
                                <p class="text-gray-600 text-sm flex-grow">
                                    <?= nl2br(htmlspecialchars(substr($announcement['announcement_content'], 0, 120))) ?>...
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- No Announcements -->
                        <div class="col-span-full">
                            <div class="text-center shadow-sm p-6 bg-white rounded-lg">
                                <h5 class="text-xl font-semibold text-gray-700">No Announcements Found</h5>
                                <p class="text-gray-500">Check back later for updates</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <!-- ✅ Modal -->
    <div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
            <button onclick="closeAnnouncementModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
            <div id="modalContent"></div>
        </div>
    </div>