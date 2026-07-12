<?php
require_once "../config/db.php";
requireLogin(); // both admin and employee can view attendance

// Fetch all active employees for selection
$empStmt = $pdo->query("SELECT id, employee_code, first_name, last_name FROM employees WHERE status='Active' ORDER BY first_name");
$employees = $empStmt->fetchAll();

// Default employee ID (first one if not specified)
$selected_employee_id = isset($_GET['employee_id']) ? (int)$_GET['employee_id'] : ($employees[0]['id'] ?? 0);

// If logged in as employee, override selection to their own employee ID
if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee') {
    $selected_employee_id = (int)$_SESSION['employee_id'];
}

// Default Month and Year filter
$selected_month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$selected_year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

// Statistics
$totalDays = 0;
$presentDays = 0;
$lateDays = 0;
$absentDays = 0;
$halfDays = 0;
$attendancePercentage = 0;
$history = [];

if ($selected_employee_id > 0) {
    // Total working days tracked in this month/year for this employee
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM attendance 
        WHERE employee_id = ? 
        AND MONTH(attendance_date) = ? 
        AND YEAR(attendance_date) = ?
    ");
    $stmt->execute([$selected_employee_id, $selected_month, $selected_year]);
    $totalDays = $stmt->fetchColumn();

    // Counts by status
    $stmt = $pdo->prepare("
        SELECT status, COUNT(*) as count 
        FROM attendance 
        WHERE employee_id = ? 
        AND MONTH(attendance_date) = ? 
        AND YEAR(attendance_date) = ?
        GROUP BY status
    ");
    $stmt->execute([$selected_employee_id, $selected_month, $selected_year]);
    $counts = $stmt->fetchAll();

    foreach ($counts as $c) {
        if ($c['status'] == 'Present') $presentDays = $c['count'];
        elseif ($c['status'] == 'Late') $lateDays = $c['count'];
        elseif ($c['status'] == 'Absent') $absentDays = $c['count'];
        elseif ($c['status'] == 'Half Day') $halfDays = $c['count'];
    }

    // Attendance % calculation
    if ($totalDays > 0) {
        $attendancePercentage = round((($presentDays + $lateDays + ($halfDays * 0.5)) / $totalDays) * 100);
    }

    // Attendance History list
    $stmt = $pdo->prepare("
        SELECT * 
        FROM attendance 
        WHERE employee_id = ? 
        AND MONTH(attendance_date) = ? 
        AND YEAR(attendance_date) = ?
        ORDER BY attendance_date DESC
    ");
    $stmt->execute([$selected_employee_id, $selected_month, $selected_year]);
    $history = $stmt->fetchAll();
}

$pageTitle = "My Attendance";
require_once "../includes/header.php";
?>

<!-- Filter Box -->
<section class="employee-search" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 15px;">
    <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 15px; width: 100%;">
        <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee'): ?>
        <div style="flex: 1; min-width: 200px;">
            <label style="font-weight: bold; color: #274368; display: block; margin-bottom: 5px;">Select Employee</label>
            <select name="employee_id" onchange="this.form.submit()" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc; background: white;">
                <?php if (count($employees) > 0): ?>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?php echo $emp['id']; ?>" <?php echo ($emp['id'] == $selected_employee_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name'] . ' (' . $emp['employee_code'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No employees found</option>
                <?php endif; ?>
            </select>
        </div>
        <?php endif; ?>

        <div style="width: 150px;">
            <label style="font-weight: bold; color: #274368; display: block; margin-bottom: 5px;">Month</label>
            <select name="month" onchange="this.form.submit()" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc; background: white;">
                <?php for($m=1; $m<=12; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php echo ($m == $selected_month) ? 'selected' : ''; ?>>
                        <?php echo date("F", mktime(0, 0, 0, $m, 1)); ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div style="width: 120px;">
            <label style="font-weight: bold; color: #274368; display: block; margin-bottom: 5px;">Year</label>
            <select name="year" onchange="this.form.submit()" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc; background: white;">
                <?php
                $currYear = date("Y");
                for($y=$currYear-2; $y<=$currYear+1; $y++): ?>
                    <option value="<?php echo $y; ?>" <?php echo ($y == $selected_year) ? 'selected' : ''; ?>>
                        <?php echo $y; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
    </form>
</section>

<!-- Summary Cards -->
<section class="cards">
    <div class="card">
        <h3>Tracked Days</h3>
        <p><?php echo $totalDays; ?></p>
    </div>

    <div class="card">
        <h3>Present</h3>
        <p><?php echo $presentDays; ?></p>
    </div>

    <div class="card">
        <h3>Late / Half Day</h3>
        <p><?php echo $lateDays . ' / ' . $halfDays; ?></p>
    </div>

    <div class="card">
        <h3>Attendance Rate</h3>
        <p><?php echo $attendancePercentage; ?>%</p>
    </div>
</section>

<!-- Attendance History Table -->
<section class="attendance">
    <h2>Attendance History</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($history) > 0): ?>
                <?php foreach ($history as $row): ?>
                    <tr>
                        <td><?php echo date("d F Y", strtotime($row['attendance_date'])); ?></td>
                        <td>
                            <?php
                            $badgeClass = "";
                            switch ($row['status']) {
                                case 'Present': $badgeClass = 'present'; break;
                                case 'Absent': $badgeClass = 'absent'; break;
                                case 'Late': $badgeClass = 'late'; break;
                                case 'Half Day': $badgeClass = 'halfday'; break;
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>" style="padding: 5px 10px; border-radius: 10px; color: white; font-weight: bold;">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="text-align: center;">No attendance records found for this month.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
require_once "../includes/footer.php";
?>