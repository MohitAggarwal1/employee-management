<?php
require_once "../config/db.php";
requireAdminOrHr();

$pageTitle = "Employee Management";
require_once "../includes/header.php";
?>

<!-- Search Section -->
<section class="employee-search">
    <input type="text" id="search-redirect" placeholder="Search Employee by Name or Code..." onkeyup="window.location.href='employee_list.php?search=' + encodeURIComponent(this.value)">
    <button onclick="window.location.href='employee_list.php?search=' + encodeURIComponent(document.getElementById('search-redirect').value)">
        Search
    </button>
</section>

<!-- Employee Options -->
<section class="employee-cards">

    <div class="employee-card">
        <h3>Add Employee</h3>
        <p>Create a new employee record</p>
        <a href="add_employee.php">Open</a>
    </div>

    <div class="employee-card">
        <h3>Edit Employee</h3>
        <p>Update employee details</p>
        <a href="employee_list.php">Open</a>
    </div>

    <div class="employee-card">
        <h3>Delete Employee</h3>
        <p>Remove employee records</p>
        <a href="employee_list.php">Open</a>
    </div>

    <div class="employee-card">
        <h3>Employee List</h3>
        <p>View all employees</p>
        <a href="employee_list.php">Open</a>
    </div>

</section>

<?php
require_once "../includes/footer.php";
?>