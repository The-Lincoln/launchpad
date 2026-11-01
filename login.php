<?php
require_once __DIR__ . '/includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (attempt_login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
        $u = current_user();
        header('Location: ' . ($u['role']==='admin' ? '/admin/index.php' : '/client/dashboard.php'));
        exit;
    }
    $error = 'Invalid credentials.';
}
$pageTitle = 'Login';
include __DIR__ . '/includes/header.php';
?>
<h1>Login</h1>
<?php if (!empty($error)): ?><p class="notice error"><?= $error ?></p><?php endif; ?>
<form method="post" class="form">
  <label>Email <input type="email" name="email" required></label>
  <label>Password <input type="password" name="password" required></label>
  <button class="btn primary" type="submit">Login</button>
</form>
<p><a href="/register.php">Create a client account</a></p>
<?php include __DIR__ . '/includes/footer.php'; ?>
