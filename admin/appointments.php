<?php
include "../includes/auth.php";
require_role("admin");
include "../config/db.php";
include "../includes/header.php";

if (isset($_GET['set'])) {
  $id = (int)$_GET['id'];
  $set = mysqli_real_escape_string($conn, $_GET['set']);
  mysqli_query($conn, "UPDATE appointments SET status='$set' WHERE id_appointment=$id");
  header("Location: appointments.php");
  exit;
}

$q = mysqli_query($conn, "
  SELECT a.*, u.nama as pasien, d.nama as dokter, d.spesialis
  FROM appointments a
  JOIN users u ON a.id_user=u.id_user
  JOIN doctors d ON a.id_doctor=d.id_doctor
  ORDER BY a.created_at DESC
");
?>

<div class="card">
  <h2>Appointments</h2>

  <div style="overflow:auto;margin-top:12px;">
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%;border-collapse:collapse">
      <tr style="background:#dcfce7">
        <th>ID</th>
        <th>Pasien</th>
        <th>Dokter</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Keluhan</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>

      <?php while($row=mysqli_fetch_assoc($q)): ?>
      <tr>
        <td><?= $row['id_appointment'] ?></td>
        <td><?= htmlspecialchars($row['pasien']) ?></td>
        <td><?= htmlspecialchars($row['dokter']) ?> (<?= htmlspecialchars($row['spesialis']) ?>)</td>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['jam'] ?></td>
        <td><?= htmlspecialchars($row['keluhan']) ?></td>
        <td><b><?= $row['status'] ?></b></td>
        <td style="display:flex;gap:6px;flex-wrap:wrap">
          <a class="btn" style="padding:6px 10px" href="?id=<?= $row['id_appointment'] ?>&set=approved">Approve</a>
          <a class="btn" style="padding:6px 10px;background:#0ea5e9" href="?id=<?= $row['id_appointment'] ?>&set=done">Done</a>
          <a class="btn" style="padding:6px 10px;background:#ef4444" href="?id=<?= $row['id_appointment'] ?>&set=cancel">Cancel</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <div style="margin-top:16px;">
    <a class="btn" href="dashboard.php">← Kembali Dashboard</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
