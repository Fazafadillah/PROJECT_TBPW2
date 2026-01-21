<?php
include "../includes/auth.php";
require_role("pasien");
include "../config/db.php";
include "../includes/header.php";

$id_user = (int)$_SESSION['id_user'];

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE id_user=$id_user"))['total'];
$done  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE id_user=$id_user AND status='done'"))['total'];
?>

<div class="card">
  <h2>Dashboard Pasien</h2>
  <p>Halo, <b><?= $_SESSION['nama']; ?></b> 👋</p>

  <div class="grid" style="margin-top:16px;">
    <div class="card">
      <h3>Janji Berobat</h3>
      <p>Total: <b><?= $total ?></b></p>
      <a class="btn" href="appointment_add.php">Buat Appointment</a>
    </div>
    <div class="card">
      <h3>Riwayat Selesai</h3>
      <p><b><?= $done ?></b></p>
      <a class="btn" href="records.php">Lihat Rekam Medis</a>
    </div>
  </div>

  <div style="margin-top:16px;">
    <a class="btn" href="appointment_list.php">Lihat Semua Appointment</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
