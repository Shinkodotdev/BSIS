// TimeOut.js
    // Auto-hide messages after 4s
    setTimeout(() => {
        const msg = document.getElementById('msgBox');
        if (msg) msg.style.display = 'none';
    }, 4000);

    // Search filter
    function filterTable(inputId, tableId) {
        const input = document.getElementById(inputId);
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }
    document.getElementById("userSearch").addEventListener("keyup", () => filterTable("userSearch", "userTable"));