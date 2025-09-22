document.addEventListener("DOMContentLoaded", () => {
    // Generic table search
    function setupSearch(inputId, tableId) {
        const input = document.getElementById(inputId);
        const table = document.getElementById(tableId);
        if (!input || !table) return;

        input.addEventListener("keyup", () => {
            const filter = input.value.toLowerCase();
            const rows = table.getElementsByTagName("tr");
            Array.from(rows).forEach((row, index) => {
                if (index === 0) return; // skip header
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    }

    // Generic card search (for mobile view)
    function setupCardSearch(inputId, containerSelector) {
        const input = document.getElementById(inputId);
        const container = document.querySelector(containerSelector);
        if (!input || !container) return;

        input.addEventListener("keyup", () => {
            const filter = input.value.toLowerCase();
            const cards = container.querySelectorAll("div.border");
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? "" : "none";
            });
        });
    }

    // Pending Docs
    setupSearch("docSearch", "docTable");
    setupCardSearch("docSearch", "section:nth-of-type(1) .grid");

    // Pending Users
    setupSearch("userSearch", "userTable");
    setupCardSearch("userSearch", "section:nth-of-type(2) .grid");

    // Approved Docs
    setupSearch("approvedDocSearch", "approvedDocTable");
    setupCardSearch("approvedDocSearch", "section:nth-of-type(3) .grid");

    // Approved Users
    setupSearch("approvedUserSearch", "approvedUserTable");
    setupCardSearch("approvedUserSearch", "section:nth-of-type(4) .grid");

     // Verified Users
    setupSearch("verifiedUserSearch", "verifiedUserTable");
    setupCardSearch("verifiedUserSearch", "section:nth-of-type(5) .grid");
});

document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("universalSearch");

    if (!input) return;

    input.addEventListener("keyup", () => {
        const filter = input.value.toLowerCase();

        // Search ALL tables
        document.querySelectorAll("table").forEach(table => {
            const rows = table.getElementsByTagName("tr");
            Array.from(rows).forEach((row, index) => {
                if (index === 0) return; // skip header
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Search ALL mobile card views
        document.querySelectorAll("div.grid").forEach(grid => {
            const cards = grid.querySelectorAll("div.border");
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? "" : "none";
            });
        });
    });
});