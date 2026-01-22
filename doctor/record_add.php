<?php
include "../includes/auth.php";
require_login();
if($_SESSION['role'] !== "doctor") die("Akses ditolak!");

include "../config/db.php";
include "../includes/header.php";

$id_appointment = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$cek = mysqli_query($conn, "
  SELECT a.*, u.nama as pasien, d.nama as dokter
  FROM appointments a
  JOIN users u ON a.id_user=u.id_user
  JOIN doctors d ON a.id_doctor=d.id_doctor
  WHERE a.id_appointment=$id_appointment
  LIMIT 1
");
if(mysqli_num_rows($cek) === 0) die("Appointment tidak ditemukan.");
$appt = mysqli_fetch_assoc($cek);

// cek apakah sudah ada record
$cekRec = mysqli_query($conn, "SELECT * FROM medical_records WHERE id_appointment=$id_appointment LIMIT 1");
$record_exist = mysqli_num_rows($cekRec) === 1;
$record = $record_exist ? mysqli_fetch_assoc($cekRec) : null;

// simpan rekam medis
if(isset($_POST['simpan_record'])){
  $tekanan = mysqli_real_escape_string($conn, $_POST['tekanan_darah']);
  $berat = mysqli_real_escape_string($conn, $_POST['berat_badan']);
  $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
  $tindakan = mysqli_real_escape_string($conn, $_POST['tindakan']);
  $catatan = mysqli_real_escape_string($conn, $_POST['catatan_dokter']);

  if($record_exist){
    mysqli_query($conn, "UPDATE medical_records SET 
      tekanan_darah='$tekanan',
      berat_badan='$berat',
      diagnosa='$diagnosa',
      tindakan='$tindakan',
      catatan_dokter='$catatan'
      WHERE id_appointment=$id_appointment
    ");
  } else {
    mysqli_query($conn, "INSERT INTO medical_records 
      (id_appointment, tekanan_darah, berat_badan, diagnosa, tindakan, catatan_dokter)
      VALUES ($id_appointment, '$tekanan', '$berat', '$diagnosa', '$tindakan', '$catatan')
    ");
    mysqli_query($conn, "UPDATE appointments SET status='done' WHERE id_appointment=$id_appointment");
  }

  header("Location: record_add.php?id=".$id_appointment);
  exit;
}

// tambah resep
if(isset($_POST['tambah_resep'])){
  $qrec = mysqli_query($conn, "SELECT * FROM medical_records WHERE id_appointment=$id_appointment LIMIT 1");
  if(mysqli_num_rows($qrec) === 0){
    echo "<p style='color:red;font-weight:700'>Isi rekam medis dulu sebelum tambah resep.</p>";
  } else {
    $rec = mysqli_fetch_assoc($qrec);
    $id_record = $rec['id_record'];

    $nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
    $dosis = mysqli_real_escape_string($conn, $_POST['dosis']);
    $aturan = mysqli_real_escape_string($conn, $_POST['aturan_pakai']);
    $jumlah = (int)$_POST['jumlah'];

    mysqli_query($conn, "INSERT INTO prescriptions (id_record,nama_obat,dosis,aturan_pakai,jumlah)
      VALUES ($id_record,'$nama_obat','$dosis','$aturan',$jumlah)
    ");
    header("Location: record_add.php?id=".$id_appointment);
    exit;
  }
}

// ambil resep
$resep = null;
$qRec2 = mysqli_query($conn, "SELECT * FROM medical_records WHERE id_appointment=$id_appointment LIMIT 1");
if(mysqli_num_rows($qRec2) === 1){
  $rec2 = mysqli_fetch_assoc($qRec2);
  $resep = mysqli_query($conn, "SELECT * FROM prescriptions WHERE id_record=".$rec2['id_record']." ORDER BY id_prescription DESC");
}
?>

<div class="card">
  <h2>Rekam Medis & Resep</h2>
  <p><b>Pasien:</b> <?= htmlspecialchars($appt['pasien']) ?> | <b>Tanggal:</b> <?= $appt['tanggal'] ?> <?= $appt['jam'] ?></p>

  <div class="grid" style="margin-top:16px;">
    <div class="card">
      <h3>Input Rekam Medis</h3>
      <form method="POST">
        <label>Tekanan Darah</label>
        <input name="tekanan_darah" value="<?= $record_exist ? htmlspecialchars($record['tekanan_darah']) : '' ?>">

        <label>Berat Badan</label>
        <input name="berat_badan" value="<?= $record_exist ? htmlspecialchars($record['berat_badan']) : '' ?>">

        <label>Diagnosa</label>
        <textarea name="diagnosa"><?= $record_exist ? htmlspecialchars($record['diagnosa']) : '' ?></textarea>

        <label>Tindakan</label>
        <textarea name="tindakan"><?= $record_exist ? htmlspecialchars($record['tindakan']) : '' ?></textarea>

        <label>Catatan Dokter</label>
        <textarea name="catatan_dokter"><?= $record_exist ? htmlspecialchars($record['catatan_dokter']) : '' ?></textarea>

        <button type="submit" name="simpan_record">Simpan Rekam Medis</button>
      </form>
    </div>

    <div class="card">
      <h3>Tambah Resep</h3>
      <form method="POST">
        <label>Nama Obat</label>
        <input name="nama_obat" required>

        <label>Dosis</label>
        <input name="dosis" placeholder="contoh: 3x1">

        <label>Aturan Pakai</label>
        <input name="aturan_pakai" placeholder="sesudah makan">

        <label>Jumlah</label>
        <input type="number" name="jumlah" value="1" min="1">

        <button type="submit" name="tambah_resep">Tambah Resep</button>
      </form>

      <hr style="margin:16px 0;border:1px solid #d1fae5">

      <h4>Daftar Resep</h4>
      <?php if($resep && mysqli_num_rows($resep)>0): ?>
        <ul>
          <?php while($r=mysqli_fetch_assoc($resep)): ?>
          <li>
            <b><?= htmlspecialchars($r['nama_obat']) ?></b> - <?= htmlspecialchars($r['dosis']) ?> (<?= htmlspecialchars($r['aturan_pakai']) ?>) x<?= $r['jumlah'] ?>
          </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p style="color:#64748b">Belum ada resep.</p>
      <?php endif; ?>
    </div>
  </div>

  <div style="margin-top:16px;">
    <a class="btn" href="appointments.php">← Kembali</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
