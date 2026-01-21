<?php
include "../includes/auth.php";
require_role("admin");
include "../config/db.php";
include "../includes/header.php";

if(isset($_GET['hapus'])){
  $id = (int)$_GET['hapus'];
  mysqli_query($conn, "DELETE FROM users WHERE id_user=$id AND role='pasien'");
  header("Location: users.php");
  exit;
}

if(isset($_POST['update'])){
  $id_user = (int)$_POST['id_user'];
  $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));

  mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email' WHERE id_user=$id_user AND role='pasien'");
  header("Location: users.php");
  exit;
}

$editUser = null;
if(isset($_GET['edit'])){
  $id = (int)$_GET['edit'];
  $q = mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id AND role='pasien' LIMIT 1");
  if(mysqli_num_rows($q) === 1){
    $editUser = mysqli_fetch_assoc($q);
  }
}

$pasien = mysqli_query($conn, "SELECT * FROM users WHERE role='pasien' ORDER BY id_user DESC");
?>

<div class="card">
  <h2>Kelola Data Pasien</h2>

  <?php if($editUser): ?>
    <div class="card" style="margin-top:16px;">
      <h3>Edit Pasien</h3>
      <form method="POST">
        <input type="hidden" name="id_user" value="<?= $editUser['id_user'] ?>">

        <label>Nama</label>
        <input name="nama" value="<?= htmlspecialchars($editUser['nama']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($editUser['email']) ?>" required>

        <button type="submit" name="update">Update</button>
        <a class="btn" style="background:#64748b" href="users.php">Batal</a>
      </form>
    </div>
  <?php endif; ?>

  <div style="overflow:auto;margin-top:16px;">
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%;border-collapse:collapse">
      <tr style="background:#dcfce7">
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Tanggal Daftar</th>
        <th>Aksi</th>
      </tr>

      <?php while($u=mysqli_fetch_assoc($pasien)): ?>
      <tr>
        <td><?= $u['id_user'] ?></td>
        <td><?= htmlspecialchars($u['nama']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['created_at'] ?></td>
        <td style="display:flex;gap:6px;flex-wrap:wrap">
          <a class="btn" style="padding:6px 10px;background:#0ea5e9"
             href="?edit=<?= $u['id_user'] ?>">Edit</a>

          <a class="btn" style="padding:6px 10px;background:#ef4444"
             href="?hapus=<?= $u['id_user'] ?>"
             onclick="return confirm('Yakin hapus pasien ini?')">Hapus</a>
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
