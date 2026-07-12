<?php
require_once "../config/db.php";
requireAdminOrHr();

$message = "";

// Selected Date
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Save Attendance
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_date = $_POST["attendance_date"];

    if (isset($_POST["status"]) && is_array($_POST["status"])) {
        foreach ($_POST["status"] as $employee_id => $status) {
            // Check if attendance already exists
            $check = $pdo->prepare("
                SELECT id
                FROM attendance
                WHERE employee_id = ?
                AND attendance_date = ?
            ");
            $check->execute([$employee_id, $attendance_date]);

            if ($check->rowCount() > 0) {
                // Update
                $update = $pdo->prepare("
                    UPDATE attendance
                    SET status = ?
                    WHERE employee_id = ?
                    AND attendance_date = ?
                ");
                $update->execute([
                    $status,
                    $employee_id,
                    $attendance_date
                ]);
            } else {
                // Insert
                $insert = $pdo->prepare("
                    INSERT INTO attendance
                    (
                        employee_id,
                        attendance_date,
                        status
                    )
                    VALUES
                    (?, ?, ?)
                ");
                $insert->execute([
                    $employee_id,
                    $attendance_date,
                    $status
                ]);
            }
        }
        $message = "Attendance saved successfully.";
    }
}

// Get Active Employees
$stmt = $pdo->query("
    SELECT
        id,
        employee_code,
        first_name,
        last_name,
        designation
    FROM employees
    WHERE status='Active'
    ORDER BY first_name
");
$employees = $stmt->fetchAll();

$pageTitle = "Mark Attendance";
require_once "../includes/header.php";
?>

<section class="attendance">

    <?php if(!empty($message)): ?>
        <div class="success" style="background: #33A58C; color: white; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="employee-form" style="display: block;">

        <div class="form-group" style="margin-bottom: 20px; width: 250px;">
            <label style="font-weight: bold; color: #274368; display: block; margin-bottom: 5px;">Select Date</label>
            <input
                type="date"
                name="attendance_date"
                value="<?= htmlspecialchars($date) ?>"
                onchange="window.location.href='mark_attendance.php?date=' + this.value"
                required
                style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc; background: white;"
            >
        </div>

        <div style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px;">
            <button type="button" class="action-btn present-btn" onclick="markAll('Present')" style="width: auto; padding: 10px 20px; background: #33A58C; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold;">
                Present All
            </button>
            <button type="button" class="action-btn absent-btn" onclick="markAll('Absent')" style="width: auto; padding: 10px 20px; background: #d9534f; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold;">
                Absent All
            </button>
            <button type="button" class="action-btn late-btn" onclick="markAll('Late')" style="width: auto; padding: 10px 20px; background: #f4a62a; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold;">
                Late All
            </button>
            <button type="button" class="action-btn halfday-btn" onclick="markAll('Half Day')" style="width: auto; padding: 10px 20px; background: #5bc0de; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold;">
                Half Day All
            </button>
        </div>

        <table class="employee-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($employees) > 0): ?>
                    <?php foreach($employees as $emp): 
                        // Fetch current status for the selected date
                        $attendance = $pdo->prepare("
                            SELECT status
                            FROM attendance
                            WHERE employee_id=?
                            AND attendance_date=?
                        ");
                        $attendance->execute([
                            $emp['id'],
                            $date
                        ]);
                        $row = $attendance->fetch();
                        $currentStatus = $row['status'] ?? "Present";
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['employee_code']); ?></td>
                            <td><?= htmlspecialchars($emp['first_name']." ".$emp['last_name']); ?></td>
                            <td><?= htmlspecialchars($emp['designation']); ?></td>
                            <td>
                                <select
                                    name="status[<?= $emp['id']; ?>]"
                                    class="attendance-status"
                                    style="padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: white;"
                                >
                                    <option value="Present" <?= ($currentStatus=="Present")?"selected":""; ?>>Present</option>
                                    <option value="Absent" <?= ($currentStatus=="Absent")?"selected":""; ?>>Absent</option>
                                    <option value="Late" <?= ($currentStatus=="Late")?"selected":""; ?>>Late</option>
                                    <option value="Half Day" <?= ($currentStatus=="Half Day")?"selected":""; ?>>Half Day</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No active employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <button type="submit" style="width: 200px; padding: 14px; background: #274368; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">
                Save Attendance
            </button>
        </div>

    </form>

</section>

<?php
require_once "../includes/footer.php";
?>