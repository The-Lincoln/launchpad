<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$db = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['action'] ?? '') === 'update') {
        $stmt = $db->prepare("UPDATE pricing_tiers SET name=?, price_bdt=?, features=?, is_active=?, sort_order=?, service_line=? WHERE id=?");
        $stmt->execute([
            $_POST['name'], (int)$_POST['price_bdt'], $_POST['features'],
            isset($_POST['is_active']) ? 1 : 0, (int)$_POST['sort_order'],
            $_POST['service_line'], (int)$_POST['id']
        ]);
    } elseif (($_POST['action'] ?? '') === 'create') {
        $stmt = $db->prepare("INSERT INTO pricing_tiers (service_line,name,price_bdt,features,sort_order,is_active) VALUES (?,?,?,?,?,1)");
        $stmt->execute([$_POST['service_line'], $_POST['name'], (int)$_POST['price_bdt'], $_POST['features'], (int)$_POST['sort_order']]);
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $db->prepare("DELETE FROM pricing_tiers WHERE id=?")->execute([(int)$_POST['id']]);
    }
    header('Location: /admin/pricing.php'); exit;
}

$tiers = $db->query("SELECT * FROM pricing_tiers ORDER BY service_line, sort_order")->fetchAll();
$pageTitle = 'Admin · Pricing';
include __DIR__ . '/../includes/header.php';
?>
<h1>Pricing Tiers</h1>

<h2>Add new tier</h2>
<form method="post" class="form">
  <input type="hidden" name="action" value="create">
  <label>Service
    <select name="service_line"><option value="website">Website</option><option value="indicator">Indicator</option></select>
  </label>
  <label>Name <input name="name" required></label>
  <label>Price (BDT) <input type="number" name="price_bdt" required></label>
  <label>Features (one per line) <textarea name="features" rows="4" required></textarea></label>
  <label>Sort order <input type="number" name="sort_order" value="99"></label>
  <button class="btn primary">Create</button>
</form>

<h2>Existing tiers</h2>
<?php foreach ($tiers as $t): ?>
<form method="post" class="form tier-row">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="id" value="<?= $t['id'] ?>">
  <label>Service
    <select name="service_line">
      <option value="website" <?= $t['service_line']==='website'?'selected':'' ?>>Website</option>
      <option value="indicator" <?= $t['service_line']==='indicator'?'selected':'' ?>>Indicator</option>
    </select>
  </label>
  <label>Name <input name="name" value="<?= htmlspecialchars($t['name']) ?>"></label>
  <label>Price <input type="number" name="price_bdt" value="<?= $t['price_bdt'] ?>"></label>
  <label>Features <textarea name="features" rows="4"><?= htmlspecialchars($t['features']) ?></textarea></label>
  <label>Sort <input type="number" name="sort_order" value="<?= $t['sort_order'] ?>"></label>
  <label><input type="checkbox" name="is_active" <?= $t['is_active']?'checked':'' ?>> Active</label>
  <button class="btn primary">Save</button>
</form>
<form method="post" onsubmit="return confirm('Delete this tier?')">
  <input type="hidden" name="action" value="delete">
  <input type="hidden" name="id" value="<?= $t['id'] ?>">
  <button class="btn danger">Delete</button>
</form>
<hr>
<?php endforeach; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
