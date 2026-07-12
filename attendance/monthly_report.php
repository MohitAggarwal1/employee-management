<?php
require_once "../config/db.php";
requireAdminOrHr();

$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year  = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

// Get number of days in the month
$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Fetch active employees
$stmt = $pdo->query("
    SELECT
        id,
        employee_code,
        first_name,
        last_name
    FROM employees
    WHERE status='Active'
    ORDER BY first_name
");
$employees = $stmt->fetchAll();

// Fetch attendance data for the month and year
$attendanceStmt = $pdo->prepare("
    SELECT
        employee_id,
        attendance_date,
        status
    FROM attendance
    WHERE MONTH(attendance_date) = ?
    AND YEAR(attendance_date) = ?
");
$attendanceStmt->execute([$month, $year]);

$attendance = [];
foreach ($attendanceStmt as $row) {
    $day = (int)date("j", strtotime($row['attendance_date']));
    $attendance[$row['employee_id']][$day] = $row['status'];
}

$pageTitle = "Monthly Attendance Report";
require_once "../includes/header.php";
?>

<section class="attendance" style="margin-bottom: 20px;">

    <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; margin-bottom: 25px;">
        <div class="form-group" style="width: 180px;">
            <label style="font-weight: bold; color: #274368; display: block; margin-bottom: 5px;">Month</label>
            <select name="month" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc; background: white;">
                <?php for($m=1; $m<=12; $m++): ?>
                    <option value="<?= $m ?>" <?= ($m == $month) ? "selected" : ""; ?>>
                        <?= date("F", mktime(0, 0, 0, $m, 1)); ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group" style="width: 150px;">
            <label style="font-weight: bold; color: #274368; display: block; margin-bottom: 5px;">Year</label>
            <select name="year" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc; background: white;">
                <?php
                $current = (int)date("Y");
                for($y=$current-5; $y<=$current+1; $y++):
                ?>
                    <option value="<?= $y ?>" <?= ($y == $year) ? "selected" : ""; ?>>
                        <?= $y ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <button type="submit" style="width: auto; padding: 12px 25px; margin-top: 0; background: #33A58C; color: white; border-radius: 10px; font-weight: bold; cursor: pointer;">
            View Report
        </button>
        <button type="button" onclick="printPage()" style="width: auto; padding: 12px 25px; margin-top: 0; background: #274368; color: white; border-radius: 10px; font-weight: bold; cursor: pointer;">
            Print
        </button>
    </form>

    <div style="width: 100%; overflow-x: auto; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        <table class="employee-table" style="min-width: 1000px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="position: sticky; left: 0; background: #33A58C; z-index: 10; min-width: 150px;">Employee</th>
                    <?php for($d=1; $d<=$days; $d++): ?>
                        <th style="padding: 10px 5px; text-align: center; font-size: 13px;"><?= $d ?></th>
                    <?php endfor; ?>
                    <th style="padding: 10px 5px; text-align: center; background: #274368;">P</th>
                    <th style="padding: 10px 5px; text-align: center; background: #d9534f;">A</th>
                    <th style="padding: 10px 5px; text-align: center; background: #f4a62a;">L</th>
                    <th style="padding: 10px 5px; text-align: center; background: #5bc0de;">HD</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($employees) > 0): ?>
                    <?php foreach($employees as $emp): ?>
                        <tr>
                            <td style="position: sticky; left: 0; background: white; z-index: 5; font-weight: bold; text-align: left; border-right: 2px solid #eee;">
                                <?= htmlspecialchars($emp['first_name']." ".$emp['last_name']) ?>
                            </td>
                            <?php
                            $p = 0; $a = 0; $l = 0; $h = 0;
                            for($d=1; $d<=$days; $d++):
                                $status = $attendance[$emp['id']][$d] ?? "";
                                $class = "";
                                $text = "-";

                                switch($status) {
                                    case "Present":
                                        $class = "present"; $text = "P"; $p++; break;
                                    case "Absent":
                                        $class = "absent"; $text = "A"; $a++; break;
                                    case "Late":
                                        $class = "late"; $text = "L"; $l++; break;
                                    case "Half Day":
                                        $class = "halfday"; $text = "HD"; $h++; break;
                                    default:
                                        $class = ""; $text = "-";
                                }
                            ?>
                                <td style="padding: 8px 4px; text-align: center;">
                                    <?php if ($text != "-"): ?>
                                        <span class="badge <?= $class ?>" style="display: inline-block; width: 24px; height: 24px; line-height: 24px; text-align: center; border-radius: 50%; color: white; font-size: 11px; font-weight: bold; padding: 0;">
                                            <?= $text ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #ccc;">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                            <td style="font-weight: bold; color: #274368;"><?= $p ?></td>
                            <td style="font-weight: bold; color: #d9534f;"><?= $a ?></td>
                            <td style="font-weight: bold; color: #f4a62a;"><?= $l ?></td>
                            <td style="font-weight: bold; color: #5bc0de;"><?= $h ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $days + 5 ?>" style="text-align: center; padding: 20px;">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</section>

<?php
require_once "../includes/footer.php";
?>