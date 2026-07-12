<?php
require_once "config/db.php";
requireLogin();

// Fetch logged in user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$user = $stmt->fetch();

if (!$user) {
    redirect("logout.php");
}

$pageTitle = "My Profile";
require_once "includes/header.php";
?>

<section class="profile-container">

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-image">
            👤
        </div>
        <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
        <p><?php echo htmlspecialchars(ucfirst($user['role'])); ?></p>
        <span class="status-badge">Active</span>
    </div>

    <!-- Profile Details -->
    <div class="profile-details">
        <!-- Personal Information -->
        <div class="profile-section">
            <h3>Account Information</h3>
            <div class="profile-grid">
                <div>
                    <label>User ID</label>
                    <p>USR<?php echo str_pad($user['id'], 3, '0', STR_PAD_LEFT); ?></p>
                </div>
                <div>
                    <label>Full Name</label>
                    <p><?php echo htmlspecialchars($user['full_name']); ?></p>
                </div>
                <div>
                    <label>Role</label>
                    <p><?php echo htmlspecialchars(ucfirst($user['role'])); ?></p>
                </div>
                <div>
                    <label>Created At</label>
                    <p><?php echo date("d F Y", strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="profile-section">
            <h3>Account Settings</h3>
            <div class="profile-grid">
                <div>
                    <label>Username</label>
                    <p><?php echo htmlspecialchars($user['username']); ?></p>
                </div>
                <div>
                    <label>Password</label>
                    <p>••••••••</p>
                </div>
            </div>
        </div>

        <div class="profile-buttons">
            <button onclick="alert('Feature coming soon!')">Edit Profile</button>
            <button class="password-btn" onclick="window.location.href='change_password.php'">Change Password</button>
        </div>

    </div>

</section>

<?php
require_once "includes/footer.php";
?>