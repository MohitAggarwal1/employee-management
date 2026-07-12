<?php
require_once "../config/db.php";
requireAdminOrHr();

// Search
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search != "") {
    $stmt = $pdo->prepare("
        SELECT
            e.*,
            d.department_name
        FROM employees e
        LEFT JOIN departments d
            ON e.department_id = d.id
        WHERE
            e.employee_code LIKE ?
            OR e.first_name LIKE ?
            OR e.last_name LIKE ?
            OR d.department_name LIKE ?
        ORDER BY e.id DESC
    ");
    $keyword = "%{$search}%";
    $stmt->execute([$keyword, $keyword, $keyword, $keyword]);
} else {
    $stmt = $pdo->query("
        SELECT
            e.*,
            d.department_name
        FROM employees e
        LEFT JOIN departments d
            ON e.department_id = d.id
        ORDER BY e.id DESC
    ");
}

$employees = $stmt->fetchAll();

$pageTitle = "Employee List";
require_once "../includes/header.php";
?>

<section class="employee-list-container">

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="success" style="background: #d9534f; color: white; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            Employee deleted successfully.
        </div>
    <?php endif; ?>

    <div class="list-top">
        <input type="text" id="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Employee by Name, Code or Department...">
        <button onclick="searchEmployee()">Search</button>
    </div>

    <table class="employee-table">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="employeeData">
            <?php if (count($employees) > 0): ?>
                <?php foreach ($employees as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["employee_code"]); ?></td>
                        <td><?php echo htmlspecialchars($row["first_name"] . " " . $row["last_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["department_name"] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($row["designation"]); ?></td>
                        <td><?php echo htmlspecialchars($row["email"]); ?></td>
                        <td><?php echo htmlspecialchars($row["phone"]); ?></td>
                        <td>
                            <span class="status <?php echo ($row["status"] == "Active") ? "present" : "absent"; ?>">
                                <?php echo htmlspecialchars($row["status"]); ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit_employee.php?id=<?php echo $row["id"]; ?>" class="edit-btn">Edit</a>
                            <a href="delete_employee.php?id=<?php echo $row["id"]; ?>" class="delete-btn" onclick="return confirmDelete();">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">No employees found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</section>

<?php
require_once "../includes/footer.php";
?>