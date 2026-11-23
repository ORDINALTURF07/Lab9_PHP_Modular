<?php
// modules/auth/login.php
$error = '';
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_login'] = $username;
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<section>
<h2>Login</h2>
<?php if ($error !== ''): ?>
  <p style="color: red;"><?= e($error) ?></p>
<?php endif; ?>
<form method="post" action="index.php?page=auth/login">
  <div class="input">
    <label>Username</label>
    <input type="text" name="username" required />
  </div>
  <div class="input">
    <label>Password</label>
    <input type="password" name="password" required />
  </div>
  <div class="submit">
    <input type="submit" name="login" value="Masuk" />
  </div>
</form>
</section>