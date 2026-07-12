<?php
require_once "../config/db.php";
requireAdminOrHr();

$message = "";
$error = "";

// Fetch departments
$deptStmt = $pdo->query("SELECT id, department_name FROM departments ORDER BY department_name");
$departments = $deptStmt->fetchAll();

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
    $check = $pdo->prepare("SELECT id FROM employees WHERE employee_code = ?");
    $check->execute([$employee_code]);

    if ($check->rowCount() > 0) {
        $error = "Employee Code already exists.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO employees
            (
                employee_code,
                first_name,
                last_name,
                gender,
                dob,
                email,
                phone,
                address,
                department_id,
                designation,
                joining_date,
                salary,
                status
            )
            VALUES
            (
                ?,?,?,?,?,?,?,?,?,?,?,?,?
            )
        ");

        $saved = $stmt->execute([
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
            $status
        ]);

        if ($saved) {
            $employee_id = $pdo->lastInsertId();
            
            // Create user login record
            $username = strtolower($employee_code); // e.g. emp001
            $default_password = password_hash("password123", PASSWORD_DEFAULT);
            $full_name = $first_name . ' ' . $last_name;
            
            $userStmt = $pdo->prepare("
                INSERT INTO users (full_name, username, password, role, employee_id)
                VALUES (?, ?, ?, 'employee', ?)
            ");
            $userStmt->execute([$full_name, $username, $default_password, $employee_id]);
            
            $message = "Employee added successfully. Login username: '$username', password: 'password123'.";
        } else {
            $error = "Unable to save employee.";
        }
    }
}

$pageTitle = "Add Employee";
require_once "../includes/header.php";
?>

<section class="employee-form-container">

    <?php if (!empty($message)): ?>
        <div class="success" style="background: #33A58C; color: white; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error" style="background: #d9534f; color: white; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="employee-form">

        <div class="form-group">
            <label>Employee Code (ID)</label>
            <input type="text" name="employee_code" placeholder="Enter Employee Code (e.g. EMP001)" required>
        </div>

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" placeholder="Enter First Name" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="Enter Last Name" required>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter Email" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="phone" placeholder="Enter Phone Number" required>
        </div>

        <div class="form-group">
            <label>Department</label>
            <select name="department_id" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Designation</label>
            <input type="text" name="designation" placeholder="Enter Designation" required>
        </div>

        <div class="form-group">
            <label>Salary</label>
            <input type="number" step="0.01" name="salary" placeholder="Enter Salary" required>
        </div>

        <div class="form-group">
            <label>Joining Date</label>
            <input type="date" name="joining_date" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="form-group full-width">
            <label>Address</label>
            <textarea name="address" rows="4" placeholder="Enter Address"></textarea>
        </div>

        <div class="button-group">
            <button type="submit">Save Employee</button>
            <button type="reset" class="reset-btn">Reset</button>
        </div>

    </form>

</section>

<?php
require_once "../includes/footer.php";
?>