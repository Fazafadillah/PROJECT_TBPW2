<?php
include "../includes/auth.php";
require_role("pasien");
include "../config/db.php";
include "../includes/header.php";

$id_user = (int)$_SESSION['id_user'];

$q = mysqli_query($conn, "
  SELECT a.id_appointment, a.tanggal, a.jam, d.nama as dokter, d.spesialis,
         r.id_record, r.diagnosa, r.tindakan, r.catatan_dokter
  FROM appointments a
  JOIN doctors d ON a.id_doctor=d.id_doctor
  JOIN medical_records r ON a.id_appointment=r.id_appointment
  WHERE a.id_user=$id_user
  ORDER BY r.created_at DESC
");
?>

<div class="card">
  <h2>Rekam Medis Saya</h2>

  <?php if(mysqli_num_rows($q) === 0): ?>
    <p style="color:#64748b">Belum ada rekam medis.</p>
  <?php endif; ?>

  <?php while($row=mysqli_fetch_assoc($q)): ?>
    <div class="card" style="margin-top:14px;">
      <h3><?= $row['tanggal'] ?> <?= $row['jam'] ?></h3>
      <p><b>Dokter:</b> <?= htmlspecialchars($row['dokter']) ?> (<?= htmlspecialchars($row['spesialis']) ?>)</p>
      <p><b>Diagnosa:</b> <?= nl2br(htmlspecialchars($row['diagnosa'])) ?></p>
      <p><b>Tindakan:</b> <?= nl2br(htmlspecialchars($row['tindakan'])) ?></p>
      <p><b>Catatan:</b> <?= nl2br(htmlspecialchars($row['catatan_dokter'])) ?></p>

      <h4>Resep</h4>
      <?php
        $resep = mysqli_query($conn, "SELECT * FROM prescriptions WHERE id_record=".$row['id_record']." ORDER BY id_prescription DESC");
      ?>
      <?php if(mysqli_num_rows($resep) > 0): ?>
        <ul>
          <?php while($r=mysqli_fetch_assoc($resep)): ?>
          <li>
            <b><?= htmlspecialchars($r['nama_obat']) ?></b> - <?= htmlspecialchars($r['dosis']) ?> (<?= htmlspecialchars($r['aturan_pakai']) ?>) x<?= $r['jumlah'] ?>
          </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p style="color:#64748b">Tidak ada resep.</p>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>

  <div style="margin-top:16px;">
    <a class="btn" href="dashboard.php">← Kembali</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
