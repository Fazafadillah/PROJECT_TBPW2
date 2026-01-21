<?php
include "../config/db.php";
include "../includes/header.php";

if(isset($_POST['register'])){
  $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

  $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if(mysqli_num_rows($cek) > 0){
    echo "<p style='color:red;font-weight:700'>Email sudah terdaftar!</p>";
  } else {
    mysqli_query($conn, "INSERT INTO users (nama,email,password,role) VALUES ('$nama','$email','$pass','pasien')");
    echo "<p style='color:green;font-weight:700'>Berhasil daftar! Silakan login.</p>";
  }
}
?>

<div class="card">
  <h2>Register Pasien</h2>
  <form method="POST">
    <label>Nama</label>
    <input name="nama" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" name="register">Daftar</button>
  </form>
</div>

<?php include "../includes/footer.php"; ?>
