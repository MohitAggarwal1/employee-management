<?php
require_once "../config/db.php";
requireAdminOrHr();

$message = "";
$error = "";

// Fetch active employees for dropdown
$empStmt = $pdo->query("SELECT id, employee_code, first_name, last_name FROM employees WHERE status='Active' ORDER BY first_name");
$employees = $empStmt->fetchAll();

// Handle task assignment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = (int)$_POST["employee_id"];
    $title       = clean($_POST["title"]);
    $description = clean($_POST["description"]);

    if (!$employee_id || empty($title)) {
        $error = "Please select an employee and enter a task title.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO tasks (employee_id, title, description, status)
            VALUES (?, ?, ?, 'Pending')
        ");
        $saved = $stmt->execute([$employee_id, $title, $description]);
        if ($saved) {
            $message = "Task assigned successfully to the employee!";
        } else {
            $error = "Failed to assign task.";
        }
    }
}

// Fetch all tasks with employee details
$taskStmt = $pdo->query("
    SELECT t.*, e.first_name, e.last_name, e.employee_code, d.department_name
    FROM tasks t
    JOIN employees e ON t.employee_id = e.id
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY t.id DESC
");
$allTasks = $taskStmt->fetchAll();

$pageTitle = "Assign Tasks";
require_once "../includes/header.php";
?>

<?php if (!empty($message)): ?>
    <div class="success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Assign Task Form -->
<section class="employee-form-container" style="margin-bottom: 30px;">
    <h2 style="color:#274368; margin-bottom: 25px; border-bottom: 2px solid #33A58C; padding-bottom: 10px;">Assign New Task</h2>

    <form action="" method="POST" class="employee-form">

        <div class="form-group">
            <label>Select Employee</label>
            <select name="employee_id" required>
                <option value="">— Select Employee —</option>
                <?php foreach ($employees as $emp): ?>
                    <option value="<?php echo $emp['id']; ?>">
                        <?php echo htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name'] . ' (' . $emp['employee_code'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Task Title <span style="color:red;">*</span></label>
            <input type="text" name="title" placeholder="Enter task title" required>
        </div>

        <div class="form-group full-width">
            <label>Task Description</label>
            <textarea name="description" rows="4" placeholder="Describe the task in detail (optional)..."></textarea>
        </div>

        <div class="button-group">
            <button type="submit">Assign Task</button>
            <button type="reset" class="reset-btn">Clear</button>
        </div>
    </form>
</section>

<!-- All Tasks Table -->
<section class="attendance">
    <h2>All Assigned Tasks</h2>
    <table class="employee-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Dept.</th>
                <th class="task-title-col">Task Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Assigned On</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($allTasks) > 0): ?>
                <?php foreach ($allTasks as $task):
                    $statusClass = match($task['status']) {
                        'Completed'  => 'present',
                        'In Progress' => 'late',
                        default      => 'absent'
                    };
                ?>
                    <tr>
                        <td>
                            <div style="font-weight:bold;"><?php echo htmlspecialchars($task['first_name'] . ' ' . $task['last_name']); ?></div>
                            <div style="font-size:12px; color:#888;"><?php echo htmlspecialchars($task['employee_code']); ?></div>
                            <div class="mobile-task-title">
                                <span style="font-size: 11px; font-weight: normal; color: #666; display: block; margin-bottom: 2px;">Task:</span>
                                <?php echo htmlspecialchars($task['title']); ?>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($task['department_name'] ?? 'N/A'); ?></td>
                        <td class="task-title-col" style="font-weight:bold;"><?php echo htmlspecialchars($task['title']); ?></td>
                        <td style="max-width:220px; word-break:break-word;"><?php echo htmlspecialchars($task['description'] ?: '—'); ?></td>
                        <td>
                            <span class="badge <?php echo $statusClass; ?>" style="padding:5px 10px; border-radius:10px; color:white; font-weight:bold;">
                                <?php echo htmlspecialchars($task['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($task['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#777; padding:30px;">No tasks assigned yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once "../includes/footer.php"; ?>
