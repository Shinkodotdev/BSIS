const modal = document.getElementById('profileModal');
const openBtn = document.getElementById('openModalBtn'); // Optional if trigger exists
const closeBtn = document.getElementById('closeModalBtn');
const modalBody = document.getElementById('modalBody');

function viewUser(userId) {
    fetch(`../../assets/modals/user_view_modal.php?user_id=${userId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('modalContainer').innerHTML = html;

            const modal = document.getElementById('profileModal');
            const closeBtn = document.getElementById('closeModalBtn');

            // Show the modal
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');

            // Close button functionality
            closeBtn.addEventListener('click', () => {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100');
            });

            // Close when clicking outside the modal content
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    modal.classList.remove('opacity-100');
                }
            });
        });
}
function deleteUser(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be archived, not permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, archive it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX to backend
            fetch('../../../backend/actions/user/archive_user.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user_id=' + userId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Archived!', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error!', 'Request failed.', 'error'));
        }
    });
}
function restoreUser(userId) {
    Swal.fire({
        title: "Restore User?",
        text: "This will restore the archived user and set their status back to Pending.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#16a34a", // green
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, restore"
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX to backend
            fetch("../../../backend/actions/user/restore_user.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "user_id=" + encodeURIComponent(userId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Restored!", data.message, "success").then(() => {
                        // Reload page or update table dynamically
                        location.reload();
                    });
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(() => {
                Swal.fire("Error", "Something went wrong while restoring the user.", "error");
            });
        }
    });
}

function editUser(userId) {
    Swal.fire({
        title: 'Edit User',
        text: "Do you want to edit this user’s details?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, edit'
    }).then((result) => {
        if (result.isConfirmed) {
            // Option A: Open edit modal
            openEditModal(userId);

            // Option B: Redirect
            // window.location.href = 'edit_user.php?id=' + userId;
        }
    });
}