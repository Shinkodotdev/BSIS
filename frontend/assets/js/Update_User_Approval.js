function updateUser(userId, action) {
    Swal.fire({
        title: `Are you sure?`,
        text: `Do you want to mark this user as ${action}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'Approved' ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../../../backend/actions/update_user_status.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    user_id: userId,
                    action: action
                })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? `${action}d!` : 'Error',
                    text: data.message || `User has been ${action.toLowerCase()}.`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    if (data.success) {
                        location.reload(); // Refresh table
                    }
                });
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Something went wrong!',
                });
                console.error(err);
            });
        }
    });
}