<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

if(isset($_POST['login'])){
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $password = trim($_POST['password']);


  $q1 = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
  if(mysqli_num_rows($q1) === 1){
    $u = mysqli_fetch_assoc($q1);
    if(password_verify($password, $u['password'])){
      $_SESSION['login'] = true;
      $_SESSION['role'] = $u['role'];
      $_SESSION['id_user'] = $u['id_user'];
      $_SESSION['nama'] = $u['nama'];

      if($u['role'] === 'admin') header("Location: ../admin/dashboard.php");
      else header("Location: ../patient/dashboard.php");
      exit;
    }
  }

  $q2 = mysqli_query($conn, "SELECT * FROM doctors WHERE email='$email' LIMIT 1");
  if(mysqli_num_rows($q2) === 1){
    $d = mysqli_fetch_assoc($q2);
    if(password_verify($password, $d['password'])){
      $_SESSION['login'] = true;
      $_SESSION['role'] = "doctor";
      $_SESSION['id_doctor'] = $d['id_doctor'];
      $_SESSION['nama'] = $d['nama'];

      header("Location: ../doctor/dashboard.php");
      exit;
    }
  }

  echo "<p style='color:red;font-weight:700'>Login gagal! Email/password salah.</p>";
}
?>

<div class="card">
  <h2>Login MedWell</h2>
  <form method="POST">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" name="login">Login</button>
  </form>

  <p style="margin-top:10px;color:var(--muted)">
    Belum punya akun? <a href="register.php">Register</a>
  </p>
</div>

<?php include "../includes/footer.php"; ?>
