<?php
require_once __DIR__ . '/admin_functions.php';
checkAdminAccess();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Admin Panel - MovieDiary'; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Force browser to reload CSS (no cache) -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

</head>
<body>
<header class="main-header">
    <div class="container">
        <div class="header-content">
            <h1 class="logo"><a href="../index.php">MovieDiary</a> <span class="admin-badge">Admin</span></h1>
            <nav class="main-nav">
                <?php if (isLoggedIn()): ?>
                    <span class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <a href="index.php" class="admin-link active">Admin Panel</a>
                    <a href="../logout.php" class="auth-link">Logout</a>
                <?php else: ?>
                    <a href="../login.php" class="auth-link">Sign In</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>