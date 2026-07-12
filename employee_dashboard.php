<?php
require_once "config/db.php";
requireLogin();

if ($_SESSION['role'] !== 'employee') {
    redirect("dashboard.php");
}

$employee_id = (int)$_SESSION['employee_id'];
$message = "";
$error = "";

// 1. Check if attendance marked today
$today = currentDate();
$attStmt = $pdo->prepare("SELECT status FROM attendance WHERE employee_id = ? AND attendance_date = ?");
$attStmt->execute([$employee_id, $today]);
$todayAttendance = $attStmt->fetch();

// Check-in logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'check_in') {
    if ($todayAttendance) {
        $error = "Attendance already marked for today: " . $todayAttendance['status'];
    } else {
        // Simple rules: if checked in after 9:15 AM, mark as "Late", else "Present"
        $current_time = currentTime();
        $status = "Present";
        if (strtotime($current_time) > strtotime("09:15:00")) {
            $status = "Late";
        }

        $insert = $pdo->prepare("INSERT INTO attendance (employee_id, attendance_date, status) VALUES (?, ?, ?)");
        $saved = $insert->execute([$employee_id, $today, $status]);

        if ($saved) {
            $message = "Checked in successfully as '$status' at $current_time.";
            // Refresh state
            $attStmt->execute([$employee_id, $today]);
            $todayAttendance = $attStmt->fetch();
        } else {
            $error = "Failed to mark attendance.";
        }
    }
}

// 2. Statistics for current month
$current_month = date('m');
$current_year = date('Y');

// Total days
$stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE employee_id = ? AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?");
$stmt->execute([$employee_id, $current_month, $current_year]);
$totalDays = $stmt->fetchColumn();

// Present
$stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE employee_id = ? AND status = 'Present' AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?");
$stmt->execute([$employee_id, $current_month, $current_year]);
$presentDays = $stmt->fetchColumn();

// Late
$stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE employee_id = ? AND status = 'Late' AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?");
$stmt->execute([$employee_id, $current_month, $current_year]);
$lateDays = $stmt->fetchColumn();

// Half Day
$stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE employee_id = ? AND status = 'Half Day' AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?");
$stmt->execute([$employee_id, $current_month, $current_year]);
$halfDays = $stmt->fetchColumn();

$attendancePercentage = 0;
if ($totalDays > 0) {
    $attendancePercentage = round((($presentDays + $lateDays + ($halfDays * 0.5)) / $totalDays) * 100);
}

// 3. Fetch active tasks
$taskStmt = $pdo->prepare("SELECT * FROM tasks WHERE employee_id = ? AND status != 'Completed' ORDER BY id DESC LIMIT 5");
$taskStmt->execute([$employee_id]);
$assignedTasks = $taskStmt->fetchAll();

$pageTitle = "Employee Dashboard";
require_once "includes/header.php";
?>

<?php if (!empty($message)): ?>
    <div class="success" style="background: #d1e7dd; color: #0f5132; padding: 12px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #badbcc;">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="error" style="background: #f8d7da; color: #842029; padding: 12px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #f5c2c7;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<!-- Quick Check In widget -->
<section class="employee-search" style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; padding: 25px;">
    <div>
        <h2 style="color: #274368; margin-bottom: 5px;">Today's Attendance</h2>
        <p style="color: #666;">Date: <?php echo date("d F Y"); ?></p>
    </div>
    <div>
        <?php if ($todayAttendance): ?>
            <div style="background: #33A58C; color: white; padding: 12px 25px; border-radius: 12px; font-weight: bold; font-size: 16px;">
                Checked In: <?php echo htmlspecialchars($todayAttendance['status']); ?>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <input type="hidden" name="action" value="check_in">
                <button type="submit" style="width: auto; padding: 14px 35px; background: #33A58C; color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 0; box-shadow: 0 4px 10px rgba(51, 165, 140, 0.3);">
                    Check In / Mark Present
                </button>
            </form>
        <?php endif; ?>
    </div>
</section>

<!-- Summary Stats -->
<section class="cards" style="margin-bottom: 30px;">
    <div class="card">
        <h3>Days Tracked</h3>
        <p><?php echo $totalDays; ?></p>
    </div>
    <div class="card">
        <h3>Present</h3>
        <p><?php echo $presentDays; ?></p>
    </div>
    <div class="card">
        <h3>Late Days</h3>
        <p><?php echo $lateDays; ?></p>
    </div>
    <div class="card">
        <h3>Attendance Rate</h3>
        <p><?php echo $attendancePercentage; ?>%</p>
    </div>
</section>

<!-- Active Tasks Assigned -->
<section class="attendance">
    <h2>Pending Tasks Assigned</h2>
    <table class="employee-table">
        <thead>
            <tr>
                <th>Task Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($assignedTasks) > 0): ?>
                <?php foreach ($assignedTasks as $task): ?>
                    <tr>
                        <td style="font-weight: bold;"><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td>
                            <span class="status late" style="font-size: 12px; text-transform: capitalize; padding: 5px 10px;">
                                <?php echo htmlspecialchars($task['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="tasks/my_tasks.php?task_id=<?php echo $task['id']; ?>&status=Completed" onclick="return confirm('Mark this task as completed?');" class="view-btn" style="background: #33A58C;">
                                Complete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #777;">No pending tasks assigned to you. Enjoy your day!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
require_once "includes/footer.php";
?>
