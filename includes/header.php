<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Movie Diary'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<header class="main-header">
    <div class="container">
        <div class="header-content">
            <h1 class="logo"><a href="index.php">MovieDiary</a></h1>
            <nav class="main-nav">
                <?php if (isLoggedIn()): ?>
                    <span class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <?php if (isAdmin()): ?>
                        <a href="admin/index.php" class="admin-link">Admin Panel</a>
                    <?php endif; ?>
                    <a href="logout.php" class="auth-link">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="auth-link">Sign In</a>
                    <a href="register.php" class="auth-link">Sign Up</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>


