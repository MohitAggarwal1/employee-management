<?php
require_once "../config/db.php";
requireAdminOrHr();

// Check employee ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: employee_list.php");
    exit;
}

$id = (int)$_GET['id'];

$message = "";
$error = "";

// Fetch departments
$deptStmt = $pdo->query("SELECT id, department_name FROM departments ORDER BY department_name");
$departments = $deptStmt->fetchAll();

// Fetch employee
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$employee = $stmt->fetch();

if (!$employee) {
    die("Employee not found.");
}

// Update employee
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $employee_code = clean($_POST["employee_code"]);
    $first_name    = clean($_POST["first_name"]);
    $last_name     = clean($_POST["last_name"]);
    $gender        = clean($_POST["gender"]);
    $dob           = $_POST["dob"];
    $email         = clean($_POST["email"]);
    $phone         = clean($_POST["phone"]);
    $address       = clean($_POST["address"]);
    $department_id = !empty($_POST["department_id"]) ? (int)$_POST["department_id"] : null;
    $designation   = clean($_POST["designation"]);
    $joining_date  = $_POST["joining_date"];
    $salary        = !empty($_POST["salary"]) ? (float)$_POST["salary"] : null;
    $status        = clean($_POST["status"]);

    // Check duplicate employee code
    $check = $pdo->prepare("
        SELECT id
        FROM employees
        WHERE employee_code = ?
        AND id != ?
    ");
    $check->execute([$employee_code, $id]);

    if ($check->rowCount() > 0) {
        $error = "Employee Code already exists.";
    } else {
        $update = $pdo->prepare("
            UPDATE employees
            SET
                employee_code = ?,
                first_name = ?,
                last_name = ?,
                gender = ?,
                dob = ?,
                email = ?,
                phone = ?,
                address = ?,
                department_id = ?,
                designation = ?,
                joining_date = ?,
                salary = ?,
                status = ?
            WHERE id = ?
        ");

        $saved = $update->execute([
            $employee_code,
            $first_name,
            $last_name,
            $gender,
            $dob,
            $email,
            $phone,
            $address,
            $department_id,
            $designation,
            $joining_date,
            $salary,
            $status,
            $id
        ]);

        if ($saved) {
            header("Location: employee_list.php?msg=updated");
            exit;
        } else {
            $error = "Unable to update employee.";
        }
    }
}

$pageTitle = "Edit Employee";
require_once "../includes/header.php";
?>

<section class="employee-form-container">

    <?php if (!empty($error)): ?>
        <div class="error" style="background: #d9534f; color: white; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="employee-form">

        <div class="form-group">
            <label>Employee Code (ID)</label>
            <input type="text" name="employee_code" value="<?php echo htmlspecialchars($employee['employee_code']); ?>" required>
        </div>

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option <?php echo ($employee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option <?php echo ($employee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option <?php echo ($employee['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?php echo htmlspecialchars($employee['dob']); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="phone" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
        </div>

        <div class="form-group">
            <label>Department</label>
            <select name="department_id" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?php echo $dept['id']; ?>" <?php echo ($employee['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($dept['department_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Designation</label>
            <input type="text" name="designation" value="<?php echo htmlspecialchars($employee['designation']); ?>" required>
        </div>

        <div class="form-group">
            <label>Salary</label>
            <input type="number" step="0.01" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>" required>
        </div>

        <div class="form-group">
            <label>Joining Date</label>
            <input type="date" name="joining_date" value="<?php echo htmlspecialchars($employee['joining_date']); ?>" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="Active" <?php echo ($employee['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                <option value="Inactive" <?php echo ($employee['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group full-width">
            <label>Address</label>
            <textarea name="address" rows="4" placeholder="Enter Address"><?php echo htmlspecialchars($employee['address']); ?></textarea>
        </div>

        <div class="button-group">
            <button type="submit">Update Employee</button>
            <a href="employee_list.php" class="view-btn" style="text-align: center; line-height: 48px; text-decoration: none; border-radius: 12px; font-weight: bold; width: 140px; background: #888; color: white;">Cancel</a>
        </div>

    </form>

</section>

<?php
require_once "../includes/footer.php";
?>