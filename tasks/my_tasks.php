<?php
require_once "../config/db.php";
requireLogin();

if ($_SESSION['role'] !== 'employee') {
    redirect("../dashboard.php");
}

$employee_id = (int)$_SESSION['employee_id'];
$message = "";

// Handle status update
if (isset($_GET['status']) && isset($_GET['task_id'])) {
    $task_id    = (int)$_GET['task_id'];
    $new_status = clean($_GET['status']);

    if (in_array($new_status, ['In Progress', 'Completed'])) {
        // Make sure this task belongs to this employee
        $check = $pdo->prepare("SELECT id FROM tasks WHERE id = ? AND employee_id = ?");
        $check->execute([$task_id, $employee_id]);
        if ($check->fetch()) {
            $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?")->execute([$new_status, $task_id]);
            $message = "Task status updated to '$new_status'.";
        }
    }
}

// Fetch all tasks for this employee
$taskStmt = $pdo->prepare("SELECT * FROM tasks WHERE employee_id = ? ORDER BY id DESC");
$taskStmt->execute([$employee_id]);
$tasks = $taskStmt->fetchAll();

// Quick stats
$totalTasks     = count($tasks);
$pendingTasks   = count(array_filter($tasks, fn($t) => $t['status'] === 'Pending'));
$inProgressTasks= count(array_filter($tasks, fn($t) => $t['status'] === 'In Progress'));
$completedTasks = count(array_filter($tasks, fn($t) => $t['status'] === 'Completed'));

$pageTitle = "My Tasks";
require_once "../includes/header.php";
?>

<?php if (!empty($message)): ?>
    <div class="success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Stats Cards -->
<section class="cards" style="margin-bottom: 30px;">
    <div class="card">
        <h3>Total Tasks</h3>
        <p><?php echo $totalTasks; ?></p>
    </div>
    <div class="card">
        <h3>Pending</h3>
        <p style="color: #d9534f;"><?php echo $pendingTasks; ?></p>
    </div>
    <div class="card">
        <h3>In Progress</h3>
        <p style="color: #f4a62a;"><?php echo $inProgressTasks; ?></p>
    </div>
    <div class="card">
        <h3>Completed</h3>
        <p style="color: #33A58C;"><?php echo $completedTasks; ?></p>
    </div>
</section>

<!-- Tasks Table -->
<section class="attendance">
    <h2>All My Tasks</h2>
    <table class="employee-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Task Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Assigned On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($tasks) > 0): ?>
                <?php foreach ($tasks as $i => $task):
                    $statusClass = match($task['status']) {
                        'Completed'  => 'present',
                        'In Progress' => 'late',
                        default      => 'absent'
                    };
                ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($task['title']); ?></td>
                        <td style="max-width:280px; word-break:break-word;">
                            <?php echo htmlspecialchars($task['description'] ?: '—'); ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $statusClass; ?>" style="padding:5px 10px; border-radius:10px; color:white; font-weight:bold; display:inline-block;">
                                <?php echo htmlspecialchars($task['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($task['created_at'])); ?></td>
                        <td>
                            <?php if ($task['status'] === 'Pending'): ?>
                                <a href="?task_id=<?php echo $task['id']; ?>&status=In+Progress"
                                   class="edit-btn"
                                   style="text-decoration:none; padding:7px 12px; border-radius:8px; font-size:13px; display:inline-block; background:#f39c12;">
                                    Start
                                </a>
                                <a href="?task_id=<?php echo $task['id']; ?>&status=Completed"
                                   onclick="return confirm('Mark this task as completed?');"
                                   class="view-btn"
                                   style="text-decoration:none; padding:7px 12px; border-radius:8px; font-size:13px; display:inline-block; background:#33A58C; margin-left:5px;">
                                    Complete
                                </a>
                            <?php elseif ($task['status'] === 'In Progress'): ?>
                                <a href="?task_id=<?php echo $task['id']; ?>&status=Completed"
                                   onclick="return confirm('Mark this task as completed?');"
                                   class="view-btn"
                                   style="text-decoration:none; padding:7px 14px; border-radius:8px; font-size:13px; display:inline-block; background:#33A58C;">
                                    Mark Complete ✓
                                </a>
                            <?php else: ?>
                                <span style="color:#33A58C; font-weight:bold;">✓ Done</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#777; padding:30px;">
                        No tasks assigned to you yet. Enjoy your free time! 🎉
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once "../includes/footer.php"; ?>
