<?php
include "../includes/auth.php";
require_role("admin");
include "../config/db.php";
include "../includes/header.php";

$jumlah_pasien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='pasien'"))['total'];
$jumlah_dokter = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM doctors"))['total'];
$jumlah_appointment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments"))['total'];
?>

<div class="card">
  <h2>Dashboard Admin</h2>
  <p>Halo, <b><?= $_SESSION['nama']; ?></b> 👋</p>

  <div class="grid" style="margin-top:16px;">
    <div class="card">
      <h3>Pasien</h3>
      <p>Total: <b><?= $jumlah_pasien ?></b></p>
      <a class="btn" href="users.php">Kelola Pasien</a>
    </div>
    <div class="card">
      <h3>Dokter</h3>
      <p>Total: <b><?= $jumlah_dokter ?></b></p>
      <a class="btn" href="doctors.php">Kelola Dokter</a>
    </div>
    <div class="card">
      <h3>Appointments</h3>
      <p>Total: <b><?= $jumlah_appointment ?></b></p>
      <a class="btn" href="appointments.php">Lihat Appointments</a>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
