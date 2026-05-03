<?php
$user      = $_SESSION['user'] ?? ['name' => 'Utilisateur', 'email' => ''];
$nameParts = explode(' ', $user['name']);
$initials  = strtoupper(($nameParts[0][0] ?? '') . ($nameParts[1][0] ?? ''));
$firstName = $nameParts[0] ?? 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'StudentLife Hub') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/y2k.css">
</head>
<body>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="shell">

  <!-- Topbar -->
  <header class="topbar glass">
    <div class="logo">
      <div class="logo-mark">SL</div>
      <span>StudentLife<em>·</em>Hub</span>
    </div>
    <div class="spacer"></div>

    <!-- Animated glowing search bar -->
    <div id="poda">
      <div class="sg sg1"></div>
      <div class="sg sg2"></div>
      <div class="sg sg3"></div>
      <div class="sg sg4"></div>
      <div class="sg sg5"></div>
      <div class="sg sg6"></div>
      <div id="sg-main">
        <input placeholder="Rechercher…" type="text" id="sg-input" autocomplete="off">
        <div id="sg-mask"></div>
        <div id="sg-pink"></div>
        <div id="sg-search-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" fill="none">
            <circle stroke="url(#sg-g1)" r="8" cy="11" cx="11"/>
            <line stroke="url(#sg-g2)" y2="16.65" y1="22" x2="16.65" x1="22"/>
            <defs>
              <linearGradient gradientTransform="rotate(50)" id="sg-g1">
                <stop stop-color="#f8e7f8" offset="0%"/>
                <stop stop-color="#b6a9b7" offset="50%"/>
              </linearGradient>
              <linearGradient id="sg-g2">
                <stop stop-color="#b6a9b7" offset="0%"/>
                <stop stop-color="#837484" offset="50%"/>
              </linearGradient>
            </defs>
          </svg>
        </div>
      </div>
    </div>
    <button class="icon-pill" title="Notifications">
      <?= icon('bell', 16) ?>
      <?php if (($fridgeAlerts ?? 0) > 0): ?>
        <span class="dot"></span>
      <?php endif; ?>
    </button>
    <div class="user-pill">
      <div class="avatar"><?= htmlspecialchars($initials) ?></div>
      <div class="user-pill-name">
        <?= htmlspecialchars($firstName) ?>
        <small><?= htmlspecialchars($user['email']) ?></small>
      </div>
    </div>
  </header>
