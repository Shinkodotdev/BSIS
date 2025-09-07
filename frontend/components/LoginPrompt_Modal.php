<div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-80 text-center">
        <h2 class="text-lg font-bold mb-4">Login Required</h2>
        <p class="mb-6">You need to log in before taking the health survey. Do you want to log in now?</p>
        <div class="flex justify-center gap-4">
            <button onclick="redirectToLogin()" class="bg-indigo-700 text-white px-4 py-2 rounded hover:bg-indigo-600">Yes</button>
            <button onclick="closeLoginModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">No</button>
        </div>
    </div>
</div>
<script>
function showLoginPrompt() {
    const modal = document.getElementById('login-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLoginModal() {
    const modal = document.getElementById('login-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function redirectToLogin() {
    // Try both possible paths
    const paths = [
        './frontend/pages/login.php',
        '../login.php'
    ];

    // Check which one exists by trying to fetch it
    let found = false;
    paths.forEach(path => {
        fetch(path, { method: 'HEAD' })
        .then(response => {
            if (!found && response.ok) {
                window.open(path, '_blank');
                found = true;
            }
        })
        .catch(() => {});
    });

    // Fallback if none found
    setTimeout(() => {
        if (!found) alert('Login page not found.');
    }, 500);
}
</script>