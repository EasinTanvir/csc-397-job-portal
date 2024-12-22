<?php
session_start();

function isActive($link)
{
    return basename($_SERVER['PHP_SELF']) === $link ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/header.css">
    <title>Job Portal</title>
</head>

<body>
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">Job<span>Portal</span></a>
            <ul class="nav-links">
                <li><a href="index.php" class="<?= isActive('index.php') ?>">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php" class="<?= isActive('profile.php') ?>">Profile</a></li>
                <li><a href="dashboard.php" class="<?= isActive('dashboard.php') ?>">Dashboard</a></li>
                <li><a href="logout.php" class="<?= isActive('logout.php') ?>">Logout</a></li>
                <?php else: ?>
                <li><a href="login.php" class="<?= isActive('login.php') ?>">Login</a></li>
                <li><a href="register.php" class="<?= isActive('register.php') ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="main">
