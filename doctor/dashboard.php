<?php
include "../includes/auth.php";
require_login();
if($_SESSION['role'] !== "doctor") die("Akses ditolak!");
include "../config/db.php";
include "../includes/header.php";

$id_doctor = (int)$_SESSION['id_doctor'];

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE id_doctor=$id_doctor"))['total'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE id_doctor=$id_doctor AND status='approved'"))['total'];
?>

<div class="card">
  <h2>Dashboard Dokter</h2>
  <p>Selamat datang, <b>Dr. <?= $_SESSION['nama']; ?></b> 👨‍⚕️</p>

  <div class="grid" style="margin-top:16px;">
    <div class="card">
      <h3>Total Appointments</h3>
      <p><b><?= $total ?></b></p>
    </div>
    <div class="card">
      <h3>Approved (Siap diperiksa)</h3>
      <p><b><?= $pending ?></b></p>
      <a class="btn" href="appointments.php">Lihat Appointments</a>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
