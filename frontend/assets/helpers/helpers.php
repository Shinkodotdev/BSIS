<?php
// ✅ Helpers
function displayOrNA($value) {
    return $value ? htmlspecialchars($value) : "<span class='text-gray-400 italic'>N/A</span>";
}
function sectionHeader($icon, $title) {
    echo "<h2 class='text-lg font-semibold mb-4 flex items-center text-indigo-700'>
            <i class='fa-solid $icon mr-2'></i> $title
          </h2>";
}

?>
