<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Pricing';
$tiers = db()->query("SELECT * FROM pricing_tiers WHERE is_active=1 ORDER BY service_line, sort_order")->fetchAll();
$grouped = [];
foreach ($tiers as $t) $grouped[$t['service_line']][] = $t;
include __DIR__ . '/includes/header.php';
?>
<h1>Pricing</h1>
<?php foreach ($grouped as $line => $items): ?>
  <h2><?= $line === 'website' ? 'Website Development' : 'Indicator Design (M&E)' ?></h2>
  <div class="cards">
  <?php foreach ($items as $t): ?>
    <div class="card">
      <h3><?= htmlspecialchars($t['name']) ?></h3>
      <p class="price"><?= number_format($t['price_bdt']) ?> BDT</p>
      <ul>
        <?php foreach (explode("\n", $t['features']) as $f): if (trim($f)==='') continue; ?>
          <li><?= htmlspecialchars($f) ?></li>
        <?php endforeach; ?>
      </ul>
      <a class="btn primary" href="/contact.php?tier=<?= $t['id'] ?>">Get this package</a>
    </div>
  <?php endforeach; ?>
  </div>
<?php endforeach; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
