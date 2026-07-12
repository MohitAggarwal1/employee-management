<?php
$base = getBaseUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Attend Ease'; ?></title>
    <link rel="icon" type="image/png" href="<?php echo $base; ?>images/favicon.png">
    <link rel="stylesheet" href="<?php echo $base; ?>css/style.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Attend Ease</h2>
            <button class="sidebar-close" id="sidebarClose" aria-label="Close Menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <ul>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee'): ?>
                <li><a href="<?php echo $base; ?>employee_dashboard.php">Dashboard</a></li>
                <li><a href="<?php echo $base; ?>attendance/my_attendance.php">My Attendance</a></li>
                <li><a href="<?php echo $base; ?>leaves/apply_leave.php">Apply Leave</a></li>
                <li><a href="<?php echo $base; ?>complaints/submit_complaint.php">Submit Complaint</a></li>
                <li><a href="<?php echo $base; ?>tasks/my_tasks.php">My Tasks</a></li>
            <?php else: ?>
                <li><a href="<?php echo $base; ?>dashboard.php">Dashboard</a></li>
                <li><a href="<?php echo $base; ?>attendance/my_attendance.php">My Attendance</a></li>
                <li><a href="<?php echo $base; ?>employees/employee.php">Employees</a></li>
                <li><a href="<?php echo $base; ?>leaves/manage_leaves.php">Manage Leaves</a></li>
                <li><a href="<?php echo $base; ?>complaints/view_complaints.php">Complaints Box</a></li>
                <li><a href="<?php echo $base; ?>tasks/assign_task.php">Assign Tasks</a></li>
            <?php endif; ?>
            <li><a href="<?php echo $base; ?>profile.php">Profile</a></li>
            <li><a href="<?php echo $base; ?>logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="top-header">
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?></h1>
            <div class="user" style="padding: 10px 20px; border-radius: 10px;">
                Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
            </div>
        </header>
