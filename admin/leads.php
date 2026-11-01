<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$db = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $db->prepare("UPDATE leads SET status=? WHERE id=?")->execute([$_POST['status'], (int)$_POST['id']]);
    header('Location: /admin/leads.php'); exit;
}
$leads = $db->query("SELECT * FROM leads ORDER BY id DESC")->fetchAll();
$pageTitle = 'Admin · Leads';
include __DIR__ . '/../includes/header.php';
?>
<h1>Leads</h1>
<table class="table">
<tr><th>#</th><th>Name</th><th>Email</th><th>Service</th><th>Message</th><th>Status</th><th>Update</th></tr>
<?php foreach ($leads as $l): ?>
<tr>
  <td><?= $l['id'] ?></td>
  <td><?= htmlspecialchars($l['name']) ?></td>
  <td><?= htmlspecialchars($l['email']) ?></td>
  <td><?= htmlspecialchars($l['service_line']) ?></td>
  <td><?= htmlspecialchars($l['message']) ?></td>
  <td><?= htmlspecialchars($l['status']) ?></td>
  <td>
    <form method="post">
      <input type="hidden" name="id" value="<?= $l['id'] ?>">
      <select name="status">
        <?php foreach (['new','contacted','won','lost'] as $s): ?>
          <option <?= $l['status']===$s?'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn">Set</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
