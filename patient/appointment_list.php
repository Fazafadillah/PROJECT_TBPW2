<?php
include "../includes/auth.php";
require_role("pasien");
include "../config/db.php";
include "../includes/header.php";

$id_user = (int)$_SESSION['id_user'];

$q = mysqli_query($conn, "
  SELECT a.*, d.nama as dokter, d.spesialis
  FROM appointments a
  JOIN doctors d ON a.id_doctor=d.id_doctor
  WHERE a.id_user=$id_user
  ORDER BY a.created_at DESC
");
?>

<div class="card">
  <h2>Daftar Appointment</h2>

  <div style="overflow:auto;margin-top:12px;">
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%;border-collapse:collapse">
      <tr style="background:#dcfce7">
        <th>ID</th>
        <th>Dokter</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Keluhan</th>
        <th>Status</th>
      </tr>

      <?php while($row=mysqli_fetch_assoc($q)): ?>
      <tr>
        <td><?= $row['id_appointment'] ?></td>
        <td><?= htmlspecialchars($row['dokter']) ?> (<?= htmlspecialchars($row['spesialis']) ?>)</td>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['jam'] ?></td>
        <td><?= htmlspecialchars($row['keluhan']) ?></td>
        <td><b><?= $row['status'] ?></b></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <div style="margin-top:16px;">
    <a class="btn" href="dashboard.php">← Kembali</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
