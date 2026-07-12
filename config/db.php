<?php
session_start();

require_once __DIR__ . "/config.php";

try {

    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {

    die("
    <h2>Database Connection Failed</h2>

    <p>" . htmlspecialchars($e->getMessage()) . "</p>

    <p>Please check your database settings in <b>config/config.php</b>.</p>
    ");
}

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

/**
 * Get the base URL path relative to current script location.
 * Returns "" from root files, "../" from one-level sub-folders.
 */
function getBaseUrl()
{
    // Determine depth relative to project root
    $docRoot = realpath(__DIR__ . '/..');
    $scriptDir = realpath(dirname($_SERVER['SCRIPT_FILENAME']));

    if ($docRoot === $scriptDir) {
        return '';
    }

    // Count directory levels difference
    $docRootParts = explode(DIRECTORY_SEPARATOR, $docRoot);
    $scriptParts = explode(DIRECTORY_SEPARATOR, $scriptDir);
    $depth = count($scriptParts) - count($docRootParts);

    return str_repeat('../', $depth);
}

function clean($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdminOrHr()
{
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'hr');
}

function requireLogin()
{
    if (!isLoggedIn()) {
        $base = getBaseUrl();
        redirect($base . "login.php");
    }
}

function requireAdminOrHr()
{
    requireLogin();
    if (!isAdminOrHr()) {
        $base = getBaseUrl();
        redirect($base . "employee_dashboard.php");
    }
}

function currentDate()
{
    return date("Y-m-d");
}

function currentTime()
{
    return date("H:i:s");
}

function flash($name = '', $message = '', $type = 'success')
{
    if (!empty($name)) {

        if (!empty($message)) {

            $_SESSION[$name] = $message;
            $_SESSION[$name . "_type"] = $type;

        } elseif (isset($_SESSION[$name])) {

            $type = $_SESSION[$name . "_type"];

            echo "<div class='alert {$type}'>" .
                    $_SESSION[$name] .
                 "</div>";

            unset($_SESSION[$name]);
            unset($_SESSION[$name . "_type"]);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Dashboard Functions
|--------------------------------------------------------------------------
*/

function totalEmployees($pdo)
{
    return $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
}

function activeEmployees($pdo)
{
    return $pdo->query("SELECT COUNT(*) FROM employees WHERE status='Active'")
               ->fetchColumn();
}

function todayPresent($pdo)
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM attendance
        WHERE attendance_date=CURDATE()
        AND status='Present'
    ");

    $stmt->execute();

    return $stmt->fetchColumn();
}

function todayAbsent($pdo)
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM attendance
        WHERE attendance_date=CURDATE()
        AND status='Absent'
    ");

    $stmt->execute();

    return $stmt->fetchColumn();
}

function todayLate($pdo)
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM attendance
        WHERE attendance_date=CURDATE()
        AND status='Late'
    ");

    $stmt->execute();

    return $stmt->fetchColumn();
}

function totalDepartments($pdo)
{
    return $pdo->query("SELECT COUNT(*) FROM departments")
               ->fetchColumn();
}