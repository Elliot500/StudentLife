<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — StudentLife Hub</title>
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
      <div class="logo-mark" style="width:48px;height:48px;border-radius:18px;font-size:18px;display:grid;place-items:center;background:linear-gradient(135deg,rgba(255,255,255,.25),rgba(255,255,255,.05)),linear-gradient(135deg,#6366f1,#22d3ee);color:white;font-weight:700;box-shadow:inset 0 1px 1px rgba(255,255,255,.4);position:relative;overflow:hidden;">SL</div>
      <span style="font-size:18px;font-weight:700;">StudentLife<em style="font-style:normal;background:linear-gradient(120deg,#22d3ee,#818cf8);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;">·</em>Hub</span>
    </div>

    <div class="auth-title">Bon retour 👋</div>
    <div class="auth-subtitle">Connecte-toi à ton espace étudiant</div>

    <?php if (isset($error) && $error): ?>
      <div class="flash error" style="margin-bottom:20px;">
        <span>⚠️</span> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/auth/login">
      <div class="form-group" style="margin-bottom:14px;">
        <label>Email</label>
        <input type="email" name="email" placeholder="alice@example.com" required autocomplete="email">
      </div>
      <div class="form-group" style="margin-bottom:22px;">
        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      </div>
      <button type="submit" class="pbtn pbtn-primary w-full" style="justify-content:center;">
        Se connecter
      </button>
    </form>

    <div class="auth-link">
      Pas encore de compte ?
      <a href="<?= BASE_URL ?>/auth/register">Créer un compte</a>
    </div>

  </div>
</div>
<script>
document.querySelectorAll('.glass').forEach(function(c){
  c.addEventListener('pointermove',function(e){var r=c.getBoundingClientRect();c.style.setProperty('--mx',(e.clientX-r.left)+'px');c.style.setProperty('--my',(e.clientY-r.top)+'px');});
  c.addEventListener('pointerleave',function(){c.style.setProperty('--mx','-500px');c.style.setProperty('--my','-500px');});
});
</script>
</body>
</html>
