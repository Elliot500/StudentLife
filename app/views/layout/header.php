<?php
$user      = $_SESSION['user'] ?? ['name' => 'Utilisateur', 'email' => ''];
$nameParts = explode(' ', $user['name']);
$initials  = strtoupper(($nameParts[0][0] ?? '') . ($nameParts[1][0] ?? ''));
$firstName = $nameParts[0] ?? 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Applique le thème sauvegardé avant le rendu pour éviter le flash -->
  <script>
    (function(){
      var t = localStorage.getItem('slh-theme') || 'dark';
      document.documentElement.setAttribute('data-theme', t);
    })();
  </script>
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

  <!-- Overlay sidebar mobile -->
  <div class="sidebar-overlay" id="sidebar-overlay"></div>

  <!-- Topbar -->
  <header class="topbar glass">
    <button class="hamburger" id="hamburger-btn" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
    <div class="logo">
      <img src="<?= BASE_URL ?>/img/logo-dark.png"  class="logo-img-dark"  alt="StudentLife Hub" style="width:36px;height:36px;border-radius:10px;object-fit:contain;">
      <img src="<?= BASE_URL ?>/img/logo-light.png" class="logo-img-light" alt="StudentLife Hub" style="width:36px;height:36px;border-radius:10px;object-fit:contain;">
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
    <!-- Toggle dark / light -->
    <button class="theme-toggle" id="theme-toggle" title="Mode clair / sombre">
      <!-- Soleil (affiché en mode sombre) -->
      <svg id="icon-sun" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
      </svg>
      <!-- Lune (affiché en mode clair) -->
      <svg id="icon-moon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
      </svg>
    </button>

    <button class="icon-pill" title="Notifications">
      <?= icon('bell', 16) ?>
      <?php if (($fridgeAlerts ?? 0) > 0): ?>
        <span class="dot"></span>
      <?php endif; ?>
    </button>
    <a href="<?= BASE_URL ?>/profile" class="user-pill" style="text-decoration:none;" title="Mon profil">
      <div class="avatar"><?= htmlspecialchars($initials) ?></div>
      <div class="user-pill-name">
        <?= htmlspecialchars($firstName) ?>
        <small><?= htmlspecialchars($user['email']) ?></small>
      </div>
    </a>
  </header>
