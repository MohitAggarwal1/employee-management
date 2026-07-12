<?php
require_once "../config/db.php";
requireAdminOrHr();

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

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
    ORDER BY e.first_name ASC
");

$keyword = "%{$search}%";

$stmt->execute([
    $keyword,
    $keyword,
    $keyword,
    $keyword
]);

$employees = $stmt->fetchAll();

if (count($employees) > 0) {
    foreach ($employees as $row) {
?>
<tr>
    <td><?= htmlspecialchars($row["employee_code"]) ?></td>
    <td><?= htmlspecialchars($row["first_name"] . " " . $row["last_name"]) ?></td>
    <td><?= htmlspecialchars($row["department_name"] ?? 'N/A') ?></td>
    <td><?= htmlspecialchars($row["designation"]) ?></td>
    <td><?= htmlspecialchars($row["email"]) ?></td>
    <td><?= htmlspecialchars($row["phone"]) ?></td>
    <td>
        <span class="status <?= ($row["status"] == "Active") ? "present" : "absent" ?>">
            <?= htmlspecialchars($row["status"]) ?>
        </span>
    </td>
    <td>
        <a href="edit_employee.php?id=<?= $row["id"] ?>" class="edit-btn">Edit</a>
        <a href="delete_employee.php?id=<?= $row["id"] ?>" class="delete-btn" onclick="return confirmDelete();">Delete</a>
    </td>
</tr>
<?php
    }
} else {
?>
<tr>
    <td colspan="8" style="text-align:center;">
        No employee found.
    </td>
</tr>
<?php
}
?>