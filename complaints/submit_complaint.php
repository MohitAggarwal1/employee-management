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
    $title        = clean($_POST["title"]);
    $description  = clean($_POST["description"]);
    $is_anonymous = isset($_POST["is_anonymous"]) ? 1 : 0;

    if (empty($title)) {
        $error = "Complaint title is required.";
    } else {
        // If anonymous, don't store employee_id
        $emp_id_to_store = $is_anonymous ? null : $employee_id;

        $stmt = $pdo->prepare("
            INSERT INTO complaints (employee_id, title, description, is_anonymous)
            VALUES (?, ?, ?, ?)
        ");
        $saved = $stmt->execute([$emp_id_to_store, $title, $description, $is_anonymous]);

        if ($saved) {
            $message = $is_anonymous
                ? "Complaint submitted anonymously. The admin will review it."
                : "Complaint submitted successfully. The admin will review it.";
        } else {
            $error = "Failed to submit complaint. Please try again.";
        }
    }
}

// Fetch own complaints (excluding anonymous ones if employee checks)
$cStmt = $pdo->prepare("SELECT * FROM complaints WHERE employee_id = ? ORDER BY id DESC");
$cStmt->execute([$employee_id]);
$myComplaints = $cStmt->fetchAll();

$pageTitle = "Submit Complaint";
require_once "../includes/header.php";
?>

<?php if (!empty($message)): ?>
    <div class="success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Complaint Form -->
<section class="employee-form-container" style="margin-bottom: 30px;">
    <h2 style="color:#274368; margin-bottom: 25px; border-bottom: 2px solid #33A58C; padding-bottom: 10px;">Submit a Complaint</h2>

    <form action="" method="POST" style="display: flex; flex-direction: column; gap: 20px;">

        <div class="form-group" style="grid-column: unset;">
            <label>Complaint Title <span style="color:red;">*</span></label>
            <input type="text" name="title" placeholder="Brief title of your complaint" required
                   style="padding:14px; border:none; border-radius:12px; background:white; font-size:16px; outline:none; box-shadow:0 4px 10px rgba(0,0,0,0.08); width:100%;">
        </div>

        <div class="form-group" style="grid-column: unset;">
            <label>Description</label>
            <textarea name="description" rows="5" placeholder="Describe your complaint in detail..."
                      style="padding:14px; border:none; border-radius:12px; background:white; font-size:16px; outline:none; box-shadow:0 4px 10px rgba(0,0,0,0.08); resize:none; width:100%;"></textarea>
        </div>

        <!-- Anonymous Toggle -->
        <div style="background: #fff3cd; border: 1px solid #ffecb5; border-radius: 12px; padding: 18px; display: flex; align-items: flex-start; gap: 14px;">
            <input type="checkbox" name="is_anonymous" id="is_anonymous"
                   style="width: 20px; height: 20px; margin-top: 2px; cursor: pointer; accent-color: #274368; flex-shrink: 0;">
            <label for="is_anonymous" style="cursor: pointer; margin: 0;">
                <span style="font-weight: bold; color: #664d03; display: block; margin-bottom: 4px;">Submit Anonymously</span>
                <span style="color: #856404; font-size: 14px;">
                    Check this box if you want to submit this complaint without revealing your identity. The admin will see it but won't know it came from you.
                </span>
            </label>
        </div>

        <div style="display: flex; gap: 15px;">
            <button type="submit" style="width: auto; padding: 14px 35px; background: #274368; color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 0;">
                Submit Complaint
            </button>
            <button type="reset" class="reset-btn" style="width: auto; padding: 14px 25px; border-radius: 12px; margin-top: 0;">
                Clear
            </button>
        </div>
    </form>
</section>

<!-- My Complaint History -->
<section class="attendance">
    <h2>My Submitted Complaints</h2>
    <table class="employee-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Type</th>
                <th>Submitted On</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($myComplaints) > 0): ?>
                <?php foreach ($myComplaints as $c): ?>
                    <tr>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($c['title']); ?></td>
                        <td style="max-width:300px; word-break:break-word;"><?php echo htmlspecialchars($c['description']); ?></td>
                        <td>
                            <span class="badge <?php echo $c['is_anonymous'] ? 'late' : 'halfday'; ?>" style="padding:5px 10px; border-radius:10px; color:white; font-weight:bold;">
                                <?php echo $c['is_anonymous'] ? 'Anonymous' : 'Named'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y, h:i A', strtotime($c['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center; color:#777;">You haven't submitted any complaints.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once "../includes/footer.php"; ?>
