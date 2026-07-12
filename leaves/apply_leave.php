<?php
require_once "../config/db.php";
requireLogin();

if ($_SESSION['role'] !== 'employee') {
    redirect("../dashboard.php");
}

$employee_id = (int)$_SESSION['employee_id'];
$message = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_type   = clean($_POST["leave_type"]);
    $start_date   = $_POST["start_date"];
    $end_date     = $_POST["end_date"];
    $reason       = clean($_POST["reason"]);

    if (strtotime($end_date) < strtotime($start_date)) {
        $error = "End date cannot be before start date.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO leaves (employee_id, leave_type, start_date, end_date, reason, status)
            VALUES (?, ?, ?, ?, ?, 'Pending')
        ");
        $saved = $stmt->execute([$employee_id, $leave_type, $start_date, $end_date, $reason]);
        if ($saved) {
            $message = "Leave application submitted successfully! Waiting for admin approval.";
        } else {
            $error = "Failed to submit leave. Please try again.";
        }
    }
}

// Fetch leave history for this employee
$leaveStmt = $pdo->prepare("SELECT * FROM leaves WHERE employee_id = ? ORDER BY id DESC");
$leaveStmt->execute([$employee_id]);
$leaves = $leaveStmt->fetchAll();

$pageTitle = "Apply Leave";
require_once "../includes/header.php";
?>

<?php if (!empty($message)): ?>
    <div class="success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Apply Leave Form -->
<section class="employee-form-container" style="margin-bottom: 30px;">
    <h2 style="color:#274368; margin-bottom: 25px; border-bottom: 2px solid #33A58C; padding-bottom: 10px;">Apply for Leave</h2>

    <form action="" method="POST" class="employee-form">
        <div class="form-group">
            <label>Leave Type</label>
            <select name="leave_type" required>
                <option value="">Select Leave Type</option>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Casual Leave">Casual Leave</option>
                <option value="Annual Leave">Annual Leave</option>
                <option value="Maternity Leave">Maternity Leave</option>
                <option value="Emergency Leave">Emergency Leave</option>
                <option value="Unpaid Leave">Unpaid Leave</option>
            </select>
        </div>

        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" min="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" min="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group full-width">
            <label>Reason</label>
            <textarea name="reason" rows="4" placeholder="Explain your reason for leave..."></textarea>
        </div>

        <div class="button-group">
            <button type="submit">Submit Leave Application</button>
            <button type="reset" class="reset-btn">Clear</button>
        </div>
    </form>
</section>

<!-- Leave History Table -->
<section class="attendance">
    <h2>My Leave History</h2>
    <table class="employee-table">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Applied On</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($leaves) > 0): ?>
                <?php foreach ($leaves as $leave):
                    $days = (int)((strtotime($leave['end_date']) - strtotime($leave['start_date'])) / 86400) + 1;
                    $statusClass = match($leave['status']) {
                        'Approved' => 'present',
                        'Rejected' => 'absent',
                        default => 'late'
                    };
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                        <td><?php echo date('d M Y', strtotime($leave['start_date'])); ?></td>
                        <td><?php echo date('d M Y', strtotime($leave['end_date'])); ?></td>
                        <td><?php echo $days; ?></td>
                        <td><?php echo htmlspecialchars($leave['reason'] ?: '—'); ?></td>
                        <td>
                            <span class="badge <?php echo $statusClass; ?>" style="padding:5px 10px; border-radius:10px; color:white; font-weight:bold;">
                                <?php echo htmlspecialchars($leave['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($leave['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center; color:#777;">No leave applications found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once "../includes/footer.php"; ?>
