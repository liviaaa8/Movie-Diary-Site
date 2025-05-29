<?php
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirectTo('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($fullName) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        $database = new Database();
        $conn = $database->getConnection();

        // Check if email already exists
        $checkQuery = "SELECT user_id FROM users WHERE email = ?";
        $checkParams = array($email);
        $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);

        if ($checkStmt && sqlsrv_has_rows($checkStmt)) {
            $error = 'Email already exists.';
        } else {
            // Insert new user
            $hashedPassword = hashPassword($password);

            // Update the column name to match your database structure
            $insertQuery = "INSERT INTO users (email, password, full_name, is_admin, created_at) VALUES (?, ?, ?, 0, GETDATE())";
            $insertParams = array($email, $hashedPassword, $fullName);
            $insertStmt = sqlsrv_query($conn, $insertQuery, $insertParams);

            if ($insertStmt) {
                $success = 'Registration successful! You can now log in.';
            } else {
                $error = 'Registration failed. Please try again.';
                // Uncomment this line for debugging
                // $error .= ' SQL Error: ' . print_r(sqlsrv_errors(), true);
            }
        }

        sqlsrv_close($conn);
    }
}

$pageTitle = 'Register';
include 'includes/header.php';
?>

    <main class="auth-main">
        <div class="container">
            <div class="auth-form">
                <h2>Create Account</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name" required
                               value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>

                <p class="auth-switch">Already have an account? <a href="login.php">Sign in here</a></p>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>