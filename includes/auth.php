<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function require_role($role) {
    require_login();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        die("Akses ditolak!");
    }
}
?>
