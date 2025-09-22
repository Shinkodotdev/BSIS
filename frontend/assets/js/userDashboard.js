document.addEventListener("DOMContentLoaded", function () {
    const civilStatus = document.getElementById("civil_status");
    const spouseDependentsGroup = document.getElementById("spouse_dependents_group");

    function toggleFields() {
        if (civilStatus.value === "Single") {
            spouseDependentsGroup.style.display = "none";
        } else {
            spouseDependentsGroup.style.display = "grid";
        }
    }
    toggleFields();
    civilStatus.addEventListener("change", toggleFields);
});

