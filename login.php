
<?php
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirectTo('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        $database = new Database();
        $conn = $database->getConnection();

        $query = "SELECT user_id, email, password, full_name, is_admin FROM users WHERE email = ?";
        $params = array($email);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt && sqlsrv_has_rows($stmt)) {
            $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            // Use the correct column name here as well
            if (verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['is_admin'] = $user['is_admin'];

                redirectTo('index.php');
            } else {
                $error = 'Invalid credentials.';
            }
        } else {
            $error = 'Invalid credentials.';
        }

        sqlsrv_close($conn);
    }
}

$pageTitle = 'Login';
include 'includes/header.php';
?>

<main class="auth-main">
    <div class="container">
        <div class="auth-form">
            <h2>Sign In</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <p class="auth-switch">Don't have an account? <a href="register.php">Sign up here</a></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>