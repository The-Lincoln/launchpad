<?php
require_once __DIR__ . '/../includes/auth.php';
$u = require_login('client');
$projects = db()->prepare("SELECT * FROM projects WHERE client_id = ? ORDER BY id DESC");
$projects->execute([$u['id']]);
$projects = $projects->fetchAll();
$proposals = db()->prepare("SELECT * FROM proposals WHERE client_id = ? ORDER BY id DESC");
$proposals->execute([$u['id']]);
$proposals = $proposals->fetchAll();
$pageTitle = 'My Dashboard';
include __DIR__ . '/../includes/header.php';
?>
<h1>Welcome, <?= htmlspecialchars($u['name']) ?></h1>
<h2>My Projects</h2>
<?php if (!$projects): ?><p>No projects yet. <a href="/contact.php">Book a consultation</a>.</p><?php endif; ?>
<ul>
<?php foreach ($projects as $p): ?>
  <li><strong><?= htmlspecialchars($p['title']) ?></strong> — <?= htmlspecialchars($p['service_line']) ?> · <?= htmlspecialchars($p['status']) ?></li>
<?php endforeach; ?>
</ul>

<h2>Proposals</h2>
<ul>
<?php foreach ($proposals as $p): ?>
  <li><strong><?= htmlspecialchars($p['title']) ?></strong> — <?= number_format($p['amount_bdt'] ?? 0) ?> BDT · <?= htmlspecialchars($p['status']) ?></li>
<?php endforeach; ?>
</ul>
<?php include __DIR__ . '/../includes/footer.php'; ?>
