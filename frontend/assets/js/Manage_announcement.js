        
        function openAnnouncementForm(a = null) {
            document.getElementById("announcementFormModal").classList.remove("hidden");
            document.getElementById("announcementFormModal").classList.add("flex");

            if (a) {
                document.getElementById("announcement_id").value = a.announcement_id;
                document.getElementById("announcement_title").value = a.announcement_title;
                document.getElementById("announcement_content").value = a.announcement_content;
                document.getElementById("priority").value = a.priority;
                document.getElementById("audience").value = a.audience;
            } else {
                document.getElementById("announcementForm").reset();
                document.getElementById("announcement_id").value = "";
            }
        }

        function closeAnnouncementForm() {
            document.getElementById("announcementFormModal").classList.add("hidden");
            document.getElementById("announcementFormModal").classList.remove("flex");
        }

        function openEventForm(e = null) {
            document.getElementById("eventFormModal").classList.remove("hidden");
            document.getElementById("eventFormModal").classList.add("flex");

            if (e) {
                document.getElementById("event_id").value = e.event_id;
                document.getElementById("event_title").value = e.event_title;
                document.getElementById("event_description").value = e.event_description;
                document.getElementById("event_start").value = e.event_start.replace(' ', 'T');
                document.getElementById("event_end").value = e.event_end.replace(' ', 'T');
                document.getElementById("event_location").value = e.event_location;
                document.getElementById("event_type").value = e.event_type;
                document.getElementById("event_audience").value = e.audience;
            } else {
                document.getElementById("eventForm").reset();
                document.getElementById("event_id").value = "";
            }
        }

        function closeEventForm() {
            document.getElementById("eventFormModal").classList.add("hidden");
            document.getElementById("eventFormModal").classList.remove("flex");
        }

        function openViewAnnouncement(a) {
            document.getElementById("viewAnnouncementModal").classList.remove("hidden");
            document.getElementById("viewAnnouncementModal").classList.add("flex");

            document.getElementById("view_announcement_title").innerText = a.announcement_title;
            document.getElementById("view_announcement_content").innerText = a.announcement_content;
            document.getElementById("view_announcement_location").innerText = a.announcement_location || "N/A";
            document.getElementById("view_announcement_author").innerText = a.f_name + " " + (a.l_name || "");
            document.getElementById("view_announcement_priority").innerText = a.priority;
            document.getElementById("view_announcement_image").src = a.announcement_image || "../../assets/images/home.jpg";
            document.getElementById("view_announcement_attachment").href = a.attachment || "#";
        }

        function closeViewAnnouncement() {
            document.getElementById("viewAnnouncementModal").classList.add("hidden");
            document.getElementById("viewAnnouncementModal").classList.remove("flex");
        }

        function openViewEvent(e) {
    document.getElementById("viewEventModal").classList.remove("hidden");
    document.getElementById("viewEventModal").classList.add("flex");

    document.getElementById("view_event_title").innerText = e.event_title;
    document.getElementById("view_event_description").innerText = e.event_description;
    document.getElementById("view_event_location").innerText = e.event_location || "N/A";
    document.getElementById("view_event_type").innerText = e.event_type || "General";
     // ✅ Correct field + correct modal span
    document.getElementById("view_event_status").innerText = e.status || "Upcoming";
    document.getElementById("view_event_schedule").innerText =
        new Date(e.event_start).toLocaleString() + " - " + new Date(e.event_end).toLocaleString();
    document.getElementById("view_event_image").src = e.event_image || "../../assets/images/home.jpg";
    document.getElementById("view_event_attachment").href = e.attachment || "#";
}

        function closeViewEvent() {
            document.getElementById("viewEventModal").classList.add("hidden");
            document.getElementById("viewEventModal").classList.remove("flex");
        }


        