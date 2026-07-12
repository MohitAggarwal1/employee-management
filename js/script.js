/* ==========================================
   Employee Attendance Management System
   script.js
========================================== */

document.addEventListener("DOMContentLoaded", function () {

    console.log("Employee Attendance Management System Loaded");

    // Auto hide alerts after 3 seconds
    const alerts = document.querySelectorAll(".success, .error");

    alerts.forEach(function (alert) {

        setTimeout(function () {

            alert.style.display = "none";

        }, 3000);

    });

});


/* ==========================================
   Confirm Delete
========================================== */

function confirmDelete() {

    return confirm("Are you sure you want to delete this record?");

}


/* ==========================================
   Search Employee Table
========================================== */

function searchEmployee() {

    let search = document.getElementById("search").value;

    let xhr = new XMLHttpRequest();

    xhr.open(
        "GET",
        "search_employee.php?search=" + encodeURIComponent(search),
        true
    );

    xhr.onload = function () {

        if (this.status == 200) {

            document.getElementById("employeeData").innerHTML = this.responseText;

        }

    };

    xhr.send();

}

document.addEventListener("DOMContentLoaded", function () {

    let search = document.getElementById("search");

    if (search) {

        search.addEventListener("keyup", searchEmployee);

    }

});

/* ==========================================
   Reset Form
========================================== */

function resetForm(formId) {

    let form = document.getElementById(formId);

    if (form) {

        form.reset();

    }

}


/* ==========================================
   Validate Employee Form
========================================== */

function validateEmployeeForm() {

    let name = document.getElementById("name");

    let email = document.getElementById("email");

    let phone = document.getElementById("phone");

    if (name && name.value.trim() === "") {

        alert("Employee Name is required.");

        name.focus();

        return false;

    }

    if (email && email.value.trim() !== "") {

        let pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!pattern.test(email.value)) {

            alert("Enter a valid Email Address.");

            email.focus();

            return false;

        }

    }

    if (phone && phone.value.trim() !== "") {

        let phonePattern = /^[0-9]{10}$/;

        if (!phonePattern.test(phone.value)) {

            alert("Phone number must contain exactly 10 digits.");

            phone.focus();

            return false;

        }

    }

    return true;

}


/* ==========================================
   Select / Deselect All Attendance
========================================== */

function markAll(status) {

    let selects = document.querySelectorAll(".attendance-status");

    selects.forEach(function (select) {

        select.value = status;

    });

}


/* ==========================================
   Print Report
========================================== */

function printPage() {

    window.print();

}


/* ==========================================
   Live Date & Time
========================================== */

function updateDateTime() {

    let dateTime = document.getElementById("currentDateTime");

    if (!dateTime) return;

    let now = new Date();

    dateTime.innerHTML = now.toLocaleString();

}

setInterval(updateDateTime, 1000);


/* ==========================================
   Toggle Password
========================================== */

function togglePassword(id) {

    let input = document.getElementById(id);

    if (!input) return;

    if (input.type === "password") {

        input.type = "text";

    } else {

        input.type = "password";

    }

}


/* ==========================================
   Highlight Active Menu
========================================== */

let currentPage = window.location.pathname.split("/").pop();

let links = document.querySelectorAll("nav a");

links.forEach(function (link) {

    let href = link.getAttribute("href");

    if (href === currentPage) {

        link.style.background = "#0056b3";

    }

});


/* ==========================================
   Mobile Sidebar Navigation Toggle
   ========================================== */

document.addEventListener("DOMContentLoaded", function () {
    const mobileMenuToggle = document.getElementById("mobileMenuToggle");
    const sidebarClose = document.getElementById("sidebarClose");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    function toggleSidebar() {
        if (sidebar) sidebar.classList.toggle("active");
        if (sidebarOverlay) sidebarOverlay.classList.toggle("active");
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener("click", toggleSidebar);
    }
    if (sidebarClose) {
        sidebarClose.addEventListener("click", toggleSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", toggleSidebar);
    }

    // Auto-close sidebar on screen resize if screen becomes desktop sized
    window.addEventListener("resize", function() {
        if (window.innerWidth > 768) {
            if (sidebar && sidebar.classList.contains("active")) {
                sidebar.classList.remove("active");
            }
            if (sidebarOverlay && sidebarOverlay.classList.contains("active")) {
                sidebarOverlay.classList.remove("active");
            }
        }
    });
});