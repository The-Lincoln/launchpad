<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$db = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['action'] ?? '') === 'create') {
        $db->prepare("INSERT INTO proposals (client_id,title,body,amount_bdt,status) VALUES (?,?,?,?,?)")
           ->execute([(int)$_POST['client_id'], $_POST['title'], $_POST['body'], (int)$_POST['amount_bdt'], $_POST['status']]);
    } elseif (($_POST['action'] ?? '') === 'status') {
        $db->prepare("UPDATE proposals SET status=? WHERE id=?")->execute([$_POST['status'], (int)$_POST['id']]);
    }
    header('Location: /admin/proposals.php'); exit;
}
$clients = $db->query("SELECT id,name FROM users WHERE role='client'")->fetchAll();
$props = $db->query("SELECT p.*, u.name AS client_name FROM proposals p JOIN users u ON u.id=p.client_id ORDER BY p.id DESC")->fetchAll();
$pageTitle = 'Admin · Proposals';
include __DIR__ . '/../includes/header.php';
?>
<h1>Proposals</h1>
<form method="post" class="form">
  <input type="hidden" name="action" value="create">
  <label>Client
    <select name="client_id" required>
      <?php foreach ($clients as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?>
    </select>
  </label>
  <label>Title <input name="title" required></label>
  <label>Amount (BDT) <input type="number" name="amount_bdt" required></label>
  <label>Status
    <select name="status"><option>draft</option><option>sent</option><option>accepted</option><option>rejected</option></select>
  </label>
  <label>Body <textarea name="body" rows="4" required></textarea></label>
  <button class="btn primary">Create</button>
</form>

<table class="table">
<tr><th>#</th><th>Client</th><th>Title</th><th>Amount</th><th>Status</th><th>Update</th></tr>
<?php foreach ($props as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['client_name']) ?></td>
  <td><?= htmlspecialchars($p['title']) ?></td>
  <td><?= number_format($p['amount_bdt']) ?> BDT</td>
  <td><?= htmlspecialchars($p['status']) ?></td>
  <td>
    <form method="post">
      <input type="hidden" name="action" value="status">
      <input type="hidden" name="id" value="<?= $p['id'] ?>">
      <select name="status">
        <?php foreach (['draft','sent','accepted','rejected'] as $s): ?>
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
