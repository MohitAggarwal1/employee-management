<?php
require_once "../config/db.php";
requireAdminOrHr();

// Check if ID exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: employee_list.php");
    exit;
}

$id = (int)$_GET['id'];

// Check employee exists
$stmt = $pdo->prepare("SELECT id FROM employees WHERE id = ?");
$stmt->execute([$id]);

if (!$stmt->fetch()) {
    header("Location: employee_list.php");
    exit;
}

try {
    $delete = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $delete->execute([$id]);

    header("Location: employee_list.php?msg=deleted");
    exit;
} catch (PDOException $e) {
    $pageTitle = "Delete Error";
    require_once "../includes/header.php";
    echo '<div class="error" style="background: #d9534f; color: white; padding: 15px; border-radius: 5px; margin: 20px 0;">';
    echo '<h4>Error Deleting Employee</h4>';
    echo '<p>Could not delete the employee because they have attendance records or other dependencies.</p>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<a href="employee_list.php" class="landing-btn" style="margin-top: 15px; background: #274368;">Back to List</a>';
    echo '</div>';
    require_once "../includes/footer.php";
}
?>