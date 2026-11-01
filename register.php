<?php
require_once __DIR__ . '/includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = db()->prepare("INSERT INTO users (name,email,password_hash,company,phone,role) VALUES (?,?,?,?,?,'client')");
        $stmt->execute([
            trim($_POST['name']), trim($_POST['email']),
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            trim($_POST['company'] ?? ''), trim($_POST['phone'] ?? '')
        ]);
        attempt_login($_POST['email'], $_POST['password']);
        header('Location: /client/dashboard.php'); exit;
    } catch (Exception $e) { $error = 'Email may already be registered.'; }
}
$pageTitle = 'Register';
include __DIR__ . '/includes/header.php';
?>
<h1>Create Client Account</h1>
<?php if (!empty($error)): ?><p class="notice error"><?= $error ?></p><?php endif; ?>
<form method="post" class="form">
  <label>Name <input name="name" required></label>
  <label>Email <input type="email" name="email" required></label>
  <label>Company <input name="company"></label>
  <label>Phone <input name="phone"></label>
  <label>Password <input type="password" name="password" required minlength="6"></label>
  <button class="btn primary" type="submit">Register</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>
