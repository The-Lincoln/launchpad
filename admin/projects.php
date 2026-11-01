<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$db = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['action'] ?? '') === 'create') {
        $db->prepare("INSERT INTO projects (client_id,title,service_line,status,notes) VALUES (?,?,?,?,?)")
           ->execute([(int)$_POST['client_id'], $_POST['title'], $_POST['service_line'], $_POST['status'], $_POST['notes']]);
    } elseif (($_POST['action'] ?? '') === 'status') {
        $db->prepare("UPDATE projects SET status=? WHERE id=?")->execute([$_POST['status'], (int)$_POST['id']]);
    }
    header('Location: /admin/projects.php'); exit;
}
$clients = $db->query("SELECT id,name FROM users WHERE role='client'")->fetchAll();
$projects = $db->query("SELECT p.*, u.name AS client_name FROM projects p JOIN users u ON u.id=p.client_id ORDER BY p.id DESC")->fetchAll();
$pageTitle = 'Admin · Projects';
include __DIR__ . '/../includes/header.php';
?>
<h1>Projects</h1>
<form method="post" class="form">
  <input type="hidden" name="action" value="create">
  <label>Client
    <select name="client_id" required>
      <?php foreach ($clients as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?>
    </select>
  </label>
  <label>Title <input name="title" required></label>
  <label>Service
    <select name="service_line"><option value="website">Website</option><option value="indicator">Indicator</option></select>
  </label>
  <label>Status
    <select name="status"><option>planning</option><option>active</option><option>delivered</option></select>
  </label>
  <label>Notes <textarea name="notes" rows="2"></textarea></label>
  <button class="btn primary">Create</button>
</form>

<table class="table">
<tr><th>#</th><th>Client</th><th>Title</th><th>Service</th><th>Status</th><th>Update</th></tr>
<?php foreach ($projects as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['client_name']) ?></td>
  <td><?= htmlspecialchars($p['title']) ?></td>
  <td><?= htmlspecialchars($p['service_line']) ?></td>
  <td><?= htmlspecialchars($p['status']) ?></td>
  <td>
    <form method="post">
      <input type="hidden" name="action" value="status">
      <input type="hidden" name="id" value="<?= $p['id'] ?>">
      <select name="status">
        <?php foreach (['planning','active','delivered'] as $s): ?>
          <option <?= $p['status']===$s?'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn">Set</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
