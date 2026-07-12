<?php
require_once "../config/db.php";
requireAdminOrHr();

// Fetch all complaints, joining employee info only for non-anonymous ones
$stmt = $pdo->query("
    SELECT
        c.*,
        CASE
            WHEN c.is_anonymous = 1 THEN NULL
            ELSE e.first_name
        END AS first_name,
        CASE
            WHEN c.is_anonymous = 1 THEN NULL
            ELSE e.last_name
        END AS last_name,
        CASE
            WHEN c.is_anonymous = 1 THEN NULL
            ELSE e.employee_code
        END AS employee_code,
        CASE
            WHEN c.is_anonymous = 1 THEN NULL
            ELSE d.department_name
        END AS department_name
    FROM complaints c
    LEFT JOIN employees e ON c.employee_id = e.id
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY c.id DESC
");
$complaints = $stmt->fetchAll();

$pageTitle = "Complaints Box";
require_once "../includes/header.php";
?>

<section class="employee-list-container">
    <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
        <h2 style="color:#274368;">Complaints Box</h2>
        <span style="background: #e9ecef; padding: 6px 14px; border-radius: 20px; font-size: 14px; color: #555;">
            Total: <?php echo count($complaints); ?>
        </span>
        <span style="background: #fff3cd; border: 1px solid #ffecb5; padding: 6px 14px; border-radius: 20px; font-size: 13px; color: #856404;">
            🔒 Anonymous complaints show "Unknown Employee"
        </span>
    </div>

    <table class="employee-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Title</th>
                <th>Description</th>
                <th>Type</th>
                <th>Submitted On</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($complaints) > 0): ?>
                <?php foreach ($complaints as $i => $c): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td>
                            <?php if ($c['is_anonymous']): ?>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="background:#6c757d; color:white; padding:3px 8px; border-radius:8px; font-size:12px;">🔒</span>
                                    <span style="color:#999; font-style:italic;">Anonymous</span>
                                </div>
                            <?php else: ?>
                                <div style="font-weight:bold;"><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></div>
                                <div style="font-size:12px; color:#888;"><?php echo htmlspecialchars($c['employee_code']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($c['is_anonymous']): ?>
                                <span style="color:#aaa;">—</span>
                            <?php else: ?>
                                <?php echo htmlspecialchars($c['department_name'] ?? 'N/A'); ?>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($c['title']); ?></td>
                        <td style="max-width:250px; word-break:break-word;"><?php echo htmlspecialchars($c['description']); ?></td>
                        <td>
                            <?php if ($c['is_anonymous']): ?>
                                <span class="badge late" style="padding:5px 10px; border-radius:10px; color:white; font-weight:bold;">Anonymous</span>
                            <?php else: ?>
                                <span class="badge halfday" style="padding:5px 10px; border-radius:10px; color:white; font-weight:bold;">Named</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d M Y', strtotime($c['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center; color:#777; padding:30px;">No complaints submitted yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once "../includes/footer.php"; ?>
