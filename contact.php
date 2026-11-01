<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Free Consultation';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare("INSERT INTO leads (name,email,phone,company,service_line,tier_id,message) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([
        trim($_POST['name'] ?? ''),
        trim($_POST['email'] ?? ''),
        trim($_POST['phone'] ?? ''),
        trim($_POST['company'] ?? ''),
        $_POST['service_line'] ?? null,
        ($_POST['tier_id'] ?? '') !== '' ? (int)$_POST['tier_id'] : null,
        trim($_POST['message'] ?? '')
    ]);
    $msg = "Thank you — your request is in. We'll email you a Zoom link for a free 10-minute consultation.";
}
$tiers = db()->query("SELECT * FROM pricing_tiers WHERE is_active=1 ORDER BY service_line, sort_order")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<h1>Book a Free 10-Minute Zoom Consultation</h1>
<?php if ($msg): ?><p class="notice"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<form method="post" class="form">
  <label>Name <input name="name" required></label>
  <label>Email <input type="email" name="email" required></label>
  <label>Phone <input name="phone"></label>
  <label>Company <input name="company"></label>
  <label>Service
    <select name="service_line">
      <option value="website">Website Development</option>
      <option value="indicator">Indicator Design (M&E)</option>
    </select>
  </label>
  <label>Package (optional)
    <select name="tier_id">
      <option value="">— none —</option>
      <?php foreach ($tiers as $t): ?>
        <option value="<?= $t['id'] ?>" <?= (($_GET['tier'] ?? '') == $t['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($t['name']) ?> — <?= number_format($t['price_bdt']) ?> BDT
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Message <textarea name="message" rows="4"></textarea></label>
  <button class="btn primary" type="submit">Request Zoom Link</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>
