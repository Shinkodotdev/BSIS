function remindUser(email) {
    Swal.fire({
        title: "Send Reminder?",
        text: "This will send an email reminder to the user to complete their personal information.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#2563eb",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remind them"
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX to backend
            fetch("../../../backend/actions/remind_user.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "email=" + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Reminder Sent!", data.message, "success");
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(() => {
                Swal.fire("Error", "Something went wrong while sending the reminder.", "error");
            });
        }
    });
}