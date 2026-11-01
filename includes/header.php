<?php require_once __DIR__ . '/config.php'; $u = current_user(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? SITE_NAME) ?> — <?= SITE_TAGLINE ?></title>
<meta name="description" content="SCITBD — AI-powered website development and M&E indicator design. Technology + Humanity = Lasting Impact.">
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header">
  <div class="wrap">
    <a class="brand" href="/index.php">SCITBD</a>
    <nav>
      <a href="/services.php">Services</a>
      <a href="/pricing.php">Pricing</a>
      <a href="/contact.php">Free 10-min Zoom</a>
      <?php if ($u): ?>
        <?php if ($u['role']==='admin'): ?><a href="/admin/index.php">Admin</a>
        <?php else: ?><a href="/client/dashboard.php">My Dashboard</a><?php endif; ?>
        <a href="/logout.php">Logout</a>
      <?php else: ?>
        <a href="/login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="wrap">
