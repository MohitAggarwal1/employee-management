<?php
require_once "config/db.php";

requireAdminOrHr();

// Dashboard Statistics
$totalEmployees = totalEmployees($pdo);
$activeEmployees = activeEmployees($pdo);
$todayPresent = todayPresent($pdo);
$todayAbsent = todayAbsent($pdo);
$todayLate = todayLate($pdo);
$totalDepartments = totalDepartments($pdo);

// Fetch recent attendance
$stmt = $pdo->query("
    SELECT a.attendance_date, e.employee_code, e.first_name, e.last_name, a.status
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    ORDER BY a.attendance_date DESC, a.id DESC
    LIMIT 5
");
$recentAttendance = $stmt->fetchAll();

$pageTitle = "Dashboard";
require_once "includes/header.php";
?>

<!-- Cards -->
<section class="cards">
    <div class="card">
        <h3>Total Employees</h3>
        <p><?php echo $totalEmployees; ?></p>
    </div>

    <div class="card">
        <h3>Today Present</h3>
        <p><?php echo $todayPresent; ?></p>
    </div>

    <div class="card">
        <h3>Today Absent</h3>
        <p><?php echo $todayAbsent; ?></p>
    </div>

    <div class="card">
        <h3>Today Late</h3>
        <p><?php echo $todayLate; ?></p>
    </div>
</section>

<!-- Quick Actions Panel -->
<section class="employee-cards" style="margin-top: 30px; margin-bottom: 10px;">
    <div class="employee-card" style="padding: 25px;">
        <h3>👥 Manage Employees</h3>
        <p>Add, edit or view employee records</p>
        <a href="employees/employee.php">Open</a>
    </div>
    <div class="employee-card" style="padding: 25px;">
        <h3>📅 Mark Attendance</h3>
        <p>Record daily attendance for all staff</p>
        <a href="attendance/mark_attendance.php">Open</a>
    </div>
    <div class="employee-card" style="padding: 25px;">
        <h3>🏖️ Manage Leaves</h3>
        <p>Approve or reject leave applications</p>
        <a href="leaves/manage_leaves.php">Open</a>
    </div>
    <div class="employee-card" style="padding: 25px;">
        <h3>📋 Assign Tasks</h3>
        <p>Create and assign tasks to employees</p>
        <a href="tasks/assign_task.php">Open</a>
    </div>
    <div class="employee-card" style="padding: 25px;">
        <h3>📣 Complaints Box</h3>
        <p>Review employee complaints and feedback</p>
        <a href="complaints/view_complaints.php">Open</a>
    </div>
    <div class="employee-card" style="padding: 25px;">
        <h3>📊 Monthly Report</h3>
        <p>View full monthly attendance report</p>
        <a href="attendance/monthly_report.php">Open</a>
    </div>
</section>

<!-- Attendance Table -->
<section class="attendance">
    <h2>Recent Attendance</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($recentAttendance) > 0): ?>
                <?php foreach ($recentAttendance as $row): ?>
                    <tr>
                        <td><?php echo date("d F Y", strtotime($row['attendance_date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['employee_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
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
                    <td colspan="4">No attendance marked recently.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
require_once "includes/footer.php";
?>