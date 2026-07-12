<?php
require_once "../config/db.php";
requireAdminOrHr();

$message = "";
$error = "";

// Handle approve/reject actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_id = (int)$_POST['leave_id'];
    $action   = clean($_POST['action']); // 'Approved' or 'Rejected'

    if (in_array($action, ['Approved', 'Rejected'])) {
        $stmt = $pdo->prepare("UPDATE leaves SET status = ? WHERE id = ?");
        $stmt->execute([$action, $leave_id]);
        $message = "Leave application has been {$action}.";
    }
}

// Fetch all leave applications with employee details
$stmt = $pdo->query("
    SELECT l.*, e.first_name, e.last_name, e.employee_code, d.department_name
    FROM leaves l
    JOIN employees e ON l.employee_id = e.id
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY l.id DESC
");
$leaves = $stmt->fetchAll();

$pageTitle = "Manage Leaves";
require_once "../includes/header.php";
?>

<?php if (!empty($message)): ?>
    <div class="success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<section class="employee-list-container">
    <div style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <h2 style="color:#274368;">All Leave Applications</h2>
        <span style="background: #e9ecef; padding: 6px 14px; border-radius: 20px; font-size: 14px; color: #555;">
            Total: <?php echo count($leaves); ?>
        </span>
    </div>

    <table class="employee-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Dept.</th>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
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
                        <td>
                            <div style="font-weight: bold;"><?php echo htmlspecialchars($leave['first_name'] . ' ' . $leave['last_name']); ?></div>
                            <div style="font-size: 12px; color: #888;"><?php echo htmlspecialchars($leave['employee_code']); ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($leave['department_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                        <td><?php echo date('d M Y', strtotime($leave['start_date'])); ?></td>
                        <td><?php echo date('d M Y', strtotime($leave['end_date'])); ?></td>
                        <td><?php echo $days; ?></td>
                        <td style="max-width: 200px; white-space: normal; word-break: break-word;">
                            <?php echo htmlspecialchars($leave['reason'] ?: '—'); ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $statusClass; ?>" style="padding:5px 10px; border-radius:10px; color:white; font-weight:bold; display:inline-block;">
                                <?php echo htmlspecialchars($leave['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($leave['status'] === 'Pending'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                                    <button type="submit" name="action" value="Approved" style="background:#33A58C; color:white; border:none; padding:7px 14px; border-radius:8px; cursor:pointer; font-weight:bold; width:auto; margin-top:0;">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" style="display: inline; margin-left: 5px;">
                                    <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                                    <button type="submit" name="action" value="Rejected" style="background:#d9534f; color:white; border:none; padding:7px 14px; border-radius:8px; cursor:pointer; font-weight:bold; width:auto; margin-top:0;">
                                        Reject
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color: #aaa; font-size: 13px;">Done</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align:center; color:#777; padding: 30px;">No leave applications yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once "../includes/footer.php"; ?>
