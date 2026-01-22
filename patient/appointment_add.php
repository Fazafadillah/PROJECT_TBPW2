<?php
include "../includes/auth.php";
require_role("pasien");
include "../config/db.php";
include "../includes/header.php";

$id_user = (int)$_SESSION['id_user'];
$dokter = mysqli_query($conn, "SELECT * FROM doctors WHERE status_aktif=1 ORDER BY nama ASC");

if(isset($_POST['buat'])){
  $id_doctor = (int)$_POST['id_doctor'];
  $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
  $jam = mysqli_real_escape_string($conn, $_POST['jam']);
  $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);

  mysqli_query($conn, "INSERT INTO appointments (id_user,id_doctor,tanggal,jam,keluhan,status)
    VALUES ($id_user,$id_doctor,'$tanggal','$jam','$keluhan','pending')
  ");

  echo "<p style='color:green;font-weight:700'>Appointment berhasil dibuat! Tunggu approval admin.</p>";
}
?>

<div class="card">
  <h2>Buat Appointment</h2>

  <form method="POST">
    <label>Pilih Dokter</label>
    <select name="id_doctor" required>
      <option value="">-- pilih --</option>
      <?php while($d=mysqli_fetch_assoc($dokter)): ?>
        <option value="<?= $d['id_doctor'] ?>">
          <?= htmlspecialchars($d['nama']) ?> (<?= htmlspecialchars($d['spesialis']) ?>)
        </option>
      <?php endwhile; ?>
    </select>

    <label>Tanggal</label>
    <input type="date" name="tanggal" required>

    <label>Jam</label>
    <input type="time" name="jam" required>

    <label>Keluhan</label>
    <textarea name="keluhan" placeholder="contoh: pusing, demam..." required></textarea>

    <button type="submit" name="buat">Buat Appointment</button>
  </form>

  <div style="margin-top:16px;">
    <a class="btn" href="dashboard.php">← Kembali</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
