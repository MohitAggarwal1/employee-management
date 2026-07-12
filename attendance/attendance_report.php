<?php
require_once "../config/db.php";
requireAdminOrHr();

$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Fetch attendance
$stmt = $pdo->prepare("
    SELECT
        e.employee_code,
        e.first_name,
        e.last_name,
        d.department_name,
        e.designation,
        a.status
    FROM attendance a
    INNER JOIN employees e
        ON a.employee_id = e.id
    LEFT JOIN departments d
        ON e.department_id = d.id
    WHERE a.attendance_date = ?
    ORDER BY e.first_name
");

$stmt->execute([$date]);
$records = $stmt->fetchAll();

// Summary
$summary = $pdo->prepare("
    SELECT
        status,
        COUNT(*) total
    FROM attendance
    WHERE attendance_date = ?
    GROUP BY status
");

$summary->execute([$date]);

$stats = [
    "Present" => 0,
    "Absent" => 0,
    "Late" => 0,
    "Half Day" => 0
];

foreach ($summary as $row) {
    if (array_key_exists($row["status"], $stats)) {
        $stats[$row["status"]] = $row["total"];
    }
}

$pageTitle = "Daily Attendance Report";
require_once "../includes/header.php";
?>

<section class="attendance" style="margin-bottom: 20px;">

    <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; margin-bottom: 25px;">
        <div class="form-group" style="width: 250px;">
            <label style="font-weight: bold; color: #274368; display: block; margin-bottom: 5px;">Select Date</label>
            <input
                type="date"
                name="date"
                value="<?= htmlspecialchars($date) ?>"
                style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc; background: white;"
            >
        </div>
        <button type="submit" style="width: auto; padding: 12px 25px; margin-top: 0; background: #33A58C; color: white; border-radius: 10px; font-weight: bold; cursor: pointer;">
            View Report
        </button>
        <button type="button" class="button" onclick="printPage()" style="width: auto; padding: 12px 25px; margin-top: 0; background: #274368; color: white; border-radius: 10px; font-weight: bold; cursor: pointer;">
            Print Report
        </button>
    </form>

    <!-- Cards inside report container -->
    <div class="cards" style="margin-bottom: 30px;">
        <div class="card">
            <h3>Present</h3>
            <p><?= $stats["Present"] ?></p>
        </div>
        <div class="card">
            <h3>Absent</h3>
            <p><?= $stats["Absent"] ?></p>
        </div>
        <div class="card">
            <h3>Late</h3>
            <p><?= $stats["Late"] ?></p>
        </div>
        <div class="card">
            <h3>Half Day</h3>
            <p><?= $stats["Half Day"] ?></p>
        </div>
    </div>

    <table class="employee-table">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($records) > 0): ?>
                <?php foreach($records as $row): 
                    $badgeClass = "";
                    switch($row["status"]) {
                        case "Present": $badgeClass = "present"; break;
                        case "Absent": $badgeClass = "absent"; break;
                        case "Late": $badgeClass = "late"; break;
                        case "Half Day": $badgeClass = "halfday"; break;
                    }
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row["employee_code"]) ?></td>
                        <td><?= htmlspecialchars($row["first_name"] . " " . $row["last_name"]) ?></td>
                        <td><?= htmlspecialchars($row["department_name"] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($row["designation"]) ?></td>
                        <td>
                            <span class="badge <?= $badgeClass ?>" style="padding: 5px 10px; border-radius: 10px; color: white; font-weight: bold; display: inline-block;">
                                <?= htmlspecialchars($row["status"]) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No attendance records found for this date.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</section>

<?php
require_once "../includes/footer.php";
?>