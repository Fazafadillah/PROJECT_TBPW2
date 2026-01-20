<?php
if (session_status() === PHP_SESSION_NONE)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/part2/asset/css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">MW</div>
            <div>
                <div class="title">MedWell</div>
                <div class="sub">Sistem Klinik & Janji Berobat</div>
            </div>
        </div>

        <nav class="nav">
            <a href="/part2/index.php">Home</a>
            <?php if(isset($_SESSION{'login'}) && $_SESSION['login']===true): ?>
                <a href="/part2/auth/logout.php" class="btn">Logout</a>
            <?php else: ?>
                <a href="/part2/auth/login.php" class="btn">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    
<main class="container">
    