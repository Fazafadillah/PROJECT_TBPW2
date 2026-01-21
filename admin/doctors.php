<?php
include "../includes/auth.php";
require_role("admin");
include "../config/db.php";
include "../includes/header.php";

if(isset($_POST['tambah'])){
  $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
  $spesialis = mysqli_real_escape_string($conn, trim($_POST['spesialis']));
  $no_hp = mysqli_real_escape_string($conn, trim($_POST['no_hp']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

  $cek = mysqli_query($conn, "SELECT * FROM doctors WHERE email='$email'");
  if(mysqli_num_rows($cek) > 0){
    echo "<p style='color:red;font-weight:700'>Email dokter sudah terdaftar!</p>";
  } else {
    mysqli_query($conn, "INSERT INTO doctors (nama,spesialis,no_hp,email,password,status_aktif)
      VALUES ('$nama','$spesialis','$no_hp','$email','$password',1)");
    echo "<p style='color:green;font-weight:700'>Dokter berhasil ditambahkan!</p>";
  }
}


if(isset($_GET['hapus'])){
  $id = (int)$_GET['hapus'];
  mysqli_query($conn, "DELETE FROM doctors WHERE id_doctor=$id");
  header("Location: doctors.php");
  exit;
}


if(isset($_POST['update'])){
  $id_doctor = (int)$_POST['id_doctor'];
  $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
  $spesialis = mysqli_real_escape_string($conn, trim($_POST['spesialis']));
  $no_hp = mysqli_real_escape_string($conn, trim($_POST['no_hp']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $status = isset($_POST['status_aktif']) ? 1 : 0;

  mysqli_query($conn, "UPDATE doctors SET
    nama='$nama',
    spesialis='$spesialis',
    no_hp='$no_hp',
    email='$email',
    status_aktif=$status
    WHERE id_doctor=$id_doctor
  ");

  header("Location: doctors.php");
  exit;
}

if(isset($_POST['reset_password'])){
  $id_doctor = (int)$_POST['id_doctor'];
  $newpass = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);

  mysqli_query($conn, "UPDATE doctors SET password='$newpass' WHERE id_doctor=$id_doctor");
  header("Location: doctors.php");
  exit;
}

$editDoctor = null;
if(isset($_GET['edit'])){
  $id = (int)$_GET['edit'];
  $q = mysqli_query($conn, "SELECT * FROM doctors WHERE id_doctor=$id LIMIT 1");
  if(mysqli_num_rows($q) === 1){
    $editDoctor = mysqli_fetch_assoc($q);
  }
}

$data = mysqli_query($conn, "SELECT * FROM doctors ORDER BY id_doctor DESC");
?>

<div class="card">
  <h2>Kelola Dokter</h2>

  <div class="grid" style="margin-top:16px;">
    <div class="card">
      <h3>Tambah Dokter</h3>
      <form method="POST">
        <label>Nama Dokter</label>
        <input name="nama" required>

        <label>Spesialis</label>
        <input name="spesialis" required>

        <label>No HP</label>
        <input name="no_hp">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="tambah">Tambah</button>
      </form>
    </div>

    <div class="card">
      <h3>Edit Dokter</h3>

      <?php if(!$editDoctor): ?>
        <p style="color:#64748b">Klik tombol <b>Edit</b> pada tabel dokter untuk mengubah data.</p>
      <?php else: ?>
        <form method="POST">
          <input type="hidden" name="id_doctor" value="<?= $editDoctor['id_doctor'] ?>">

          <label>Nama Dokter</label>
          <input name="nama" value="<?= htmlspecialchars($editDoctor['nama']) ?>" required>

          <label>Spesialis</label>
          <input name="spesialis" value="<?= htmlspecialchars($editDoctor['spesialis']) ?>" required>

          <label>No HP</label>
          <input name="no_hp" value="<?= htmlspecialchars($editDoctor['no_hp']) ?>">

          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($editDoctor['email']) ?>" required>

          <label style="display:flex;align-items:center;gap:8px;margin-top:10px">
            <input type="checkbox" name="status_aktif" <?= $editDoctor['status_aktif'] ? 'checked' : '' ?>>
            Aktif
          </label>

          <button type="submit" name="update">Update</button>
          <a class="btn" style="background:#64748b" href="doctors.php">Batal</a>
        </form>

        <hr style="margin:16px 0;border:1px solid #d1fae5">

        <h4>Reset Password Dokter</h4>
        <form method="POST">
          <input type="hidden" name="id_doctor" value="<?= $editDoctor['id_doctor'] ?>">
          <label>Password baru</label>
          <input type="text" name="new_password" placeholder="contoh: dokter123" required>
          <button type="submit" name="reset_password" style="background:#0ea5e9">Reset Password</button>
        </form>
      <?php endif; ?>
    </div>
  </div>

  <div class="card" style="margin-top:16px;">
    <h3>Daftar Dokter</h3>
    <div style="overflow:auto">
      <table border="1" cellpadding="10" cellspacing="0" style="width:100%;border-collapse:collapse">
        <tr style="background:#dcfce7">
          <th>ID</th><th>Nama</th><th>Spesialis</th><th>Email</th><th>Status</th><th>Aksi</th>
        </tr>
        <?php while($d = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $d['id_doctor'] ?></td>
          <td><?= htmlspecialchars($d['nama']) ?></td>
          <td><?= htmlspecialchars($d['spesialis']) ?></td>
          <td><?= htmlspecialchars($d['email']) ?></td>
          <td><?= $d['status_aktif'] ? "Aktif" : "Nonaktif" ?></td>
          <td style="display:flex;gap:6px;flex-wrap:wrap">
            <a class="btn" style="padding:6px 10px;background:#0ea5e9"
               href="?edit=<?= $d['id_doctor'] ?>">Edit</a>

            <a class="btn" style="padding:6px 10px;background:#ef4444"
               href="?hapus=<?= $d['id_doctor'] ?>"
               onclick="return confirm('Hapus dokter ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>

  <div style="margin-top:16px;">
    <a class="btn" href="dashboard.php">← Kembali Dashboard</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
