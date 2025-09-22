function updateRequest(requestId, action) {
    Swal.fire({
        title: `Are you sure?`,
        text: `Do you want to mark this request as ${action}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'Approved' ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../../../backend/actions/update_request.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    request_id: requestId,
                    action: action
                })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? `${action}d!` : 'Error',
                    text: data.message || `Request has been ${action.toLowerCase()}.`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    if (data.success) {
                        location.reload(); // refresh to update table
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