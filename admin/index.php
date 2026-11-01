<?php
require_once __DIR__ . '/../includes/auth.php';
$u = require_login('admin');
$counts = [
  'leads' => db()->query("SELECT COUNT(*) FROM leads")->fetchColumn(),
  'clients' => db()->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn(),
  'projects' => db()->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
  'proposals' => db()->query("SELECT COUNT(*) FROM proposals")->fetchColumn(),
];
$pageTitle = 'Admin Dashboard';
include __DIR__ . '/../includes/header.php';
?>
<h1>Admin Dashboard</h1>
<div class="cards">
  <div class="card"><h3><?= $counts['leads'] ?></h3><p>Leads</p><a href="/admin/leads.php">Manage</a></div>
  <div class="card"><h3><?= $counts['clients'] ?></h3><p>Clients</p><a href="/admin/clients.php">Manage</a></div>
  <div class="card"><h3><?= $counts['projects'] ?></h3><p>Projects</p><a href="/admin/projects.php">Manage</a></div>
  <div class="card"><h3><?= $counts['proposals'] ?></h3><p>Proposals</p><a href="/admin/proposals.php">Manage</a></div>
  <div class="card"><h3>Pricing</h3><p>Edit tiers &amp; prices</p><a href="/admin/pricing.php">Manage</a></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
