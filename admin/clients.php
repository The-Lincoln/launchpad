<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$clients = db()->query("SELECT * FROM users WHERE role='client' ORDER BY id DESC")->fetchAll();
$pageTitle = 'Admin · Clients';
include __DIR__ . '/../includes/header.php';
?>
<h1>Clients</h1>
<table class="table">
<tr><th>#</th><th>Name</th><th>Email</th><th>Company</th><th>Phone</th><th>Joined</th></tr>
<?php foreach ($clients as $c): ?>
<tr>
  <td><?= $c['id'] ?></td>
  <td><?= htmlspecialchars($c['name']) ?></td>
  <td><?= htmlspecialchars($c['email']) ?></td>
  <td><?= htmlspecialchars($c['company']) ?></td>
  <td><?= htmlspecialchars($c['phone']) ?></td>
  <td><?= htmlspecialchars($c['created_at']) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
