<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription — StudentLife Hub</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/y2k.css">
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="auth-page">
  <div class="auth-card glass">

    <div class="auth-logo">
      <div class="logo-mark" style="width:48px;height:48px;border-radius:18px;font-size:18px;display:grid;place-items:center;background:linear-gradient(135deg,rgba(255,255,255,.25),rgba(255,255,255,.05)),linear-gradient(135deg,#6366f1,#22d3ee);color:white;font-weight:700;">SL</div>
      <span style="font-size:18px;font-weight:700;">StudentLife<em style="font-style:normal;background:linear-gradient(120deg,#22d3ee,#818cf8);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;">·</em>Hub</span>
    </div>

    <div class="auth-title">Crée ton compte ✨</div>
    <div class="auth-subtitle">Rejoins la communauté StudentLife Hub</div>

    <?php if (isset($error) && $error): ?>
      <div class="flash error" style="margin-bottom:20px;">
        <span>⚠️</span> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/auth/register">
      <div class="form-group" style="margin-bottom:14px;">
        <label>Prénom et nom</label>
        <input type="text" name="name" placeholder="Alice Martin" required>
      </div>
      <div class="form-group" style="margin-bottom:14px;">
        <label>Email</label>
        <input type="email" name="email" placeholder="alice@example.com" required autocomplete="email">
      </div>
      <div class="form-group" style="margin-bottom:14px;">
        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="6 caractères minimum" required>
      </div>
      <div class="form-group" style="margin-bottom:22px;">
        <label>Confirmer le mot de passe</label>
        <input type="password" name="confirm" placeholder="••••••••" required>
      </div>
      <button type="submit" class="pbtn pbtn-primary w-full" style="justify-content:center;">
        Créer mon compte
      </button>
    </form>

    <div class="auth-link">
      Déjà un compte ?
      <a href="<?= BASE_URL ?>/auth/login">Se connecter</a>
    </div>

  </div>
</div>
</body>
</html>
