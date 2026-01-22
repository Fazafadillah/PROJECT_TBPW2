<?php
include "../includes/auth.php";
require_login();
if($_SESSION['role'] !== "doctor") die("Akses ditolak!");

include "../config/db.php";
include "../includes/header.php";

$id_doctor = (int)$_SESSION['id_doctor'];

$q = mysqli_query($conn, "
  SELECT a.*, u.nama as pasien
  FROM appointments a
  JOIN users u ON a.id_user=u.id_user
  WHERE a.id_doctor=$id_doctor
  ORDER BY a.tanggal DESC, a.jam DESC
");
?>

<div class="card">
  <h2>Appointments Dokter</h2>

  <div style="overflow:auto;margin-top:12px;">
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%;border-collapse:collapse">
      <tr style="background:#dcfce7">
        <th>ID</th>
        <th>Pasien</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Keluhan</th>
        <th>Status</th>
        <th>Rekam Medis</th>
      </tr>

      <?php while($row=mysqli_fetch_assoc($q)): ?>
      <tr>
        <td><?= $row['id_appointment'] ?></td>
        <td><?= htmlspecialchars($row['pasien']) ?></td>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['jam'] ?></td>
        <td><?= htmlspecialchars($row['keluhan']) ?></td>
        <td><b><?= $row['status'] ?></b></td>
        <td>
          <?php if($row['status'] === 'approved' || $row['status'] === 'done'): ?>
            <a class="btn" style="padding:6px 10px" href="record_add.php?id=<?= $row['id_appointment'] ?>">Input Rekam</a>
          <?php else: ?>
            <span style="color:#64748b">Tunggu approve</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
