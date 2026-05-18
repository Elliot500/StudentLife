<?php
$flash         = $flash         ?? null;
$user          = $user          ?? [];
$totalExpenses = $totalExpenses ?? 0;
$totalIncome   = $totalIncome   ?? 0;
$expenseCount  = $expenseCount  ?? 0;
$goalsCount    = $goalsCount    ?? 0;

$nameParts = explode(' ', $user['name'] ?? 'U');
$initials  = strtoupper(($nameParts[0][0] ?? '') . ($nameParts[1][0] ?? ''));
$since     = isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : '—';
$balance   = $totalIncome - $totalExpenses;
?>

<?php if ($flash): ?>
  <div class="flash <?= htmlspecialchars($flash['type']) ?>">
    <?= $flash['type'] === 'success' ? '✓' : '⚠️' ?>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

<!-- ── En-tête profil ── -->
<div class="glass" style="padding:32px;margin-bottom:22px;display:flex;align-items:center;gap:28px;flex-wrap:wrap;">
  <!-- Avatar grand format -->
  <div style="width:88px;height:88px;border-radius:28px;flex-shrink:0;
    background:linear-gradient(135deg,#6366f1,#22d3ee);
    display:grid;place-items:center;
    font-size:28px;font-weight:900;color:#fff;
    box-shadow:inset 0 2px 2px rgba(255,255,255,.4), 0 10px 30px -10px rgba(99,102,241,.7);">
    <?= htmlspecialchars($initials) ?>
  </div>
  <div style="flex:1;min-width:180px;">
    <div style="font-size:24px;font-weight:800;letter-spacing:-.02em;color:#f5f7ff;margin-bottom:4px;">
      <?= htmlspecialchars($user['name'] ?? '') ?>
    </div>
    <div style="font-size:14px;color:#8b8fb0;margin-bottom:8px;">
      <?= htmlspecialchars($user['email'] ?? '') ?>
    </div>
    <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:99px;background:rgba(99,102,241,.12);border:1px solid rgba(129,140,248,.2);font-size:11px;font-weight:700;color:#a5b4fc;letter-spacing:.08em;text-transform:uppercase;">
      🎓 Membre depuis le <?= $since ?>
    </div>
  </div>
</div>

<!-- ── Stats rapides ── -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
  <?php
  $stats = [
    ['💸', number_format($totalExpenses, 2, ',', ' ') . ' €', 'Dépenses ce mois', '#f97316'],
    ['💰', number_format($totalIncome,   2, ',', ' ') . ' €', 'Revenus ce mois',  '#34d399'],
    ['📋', $expenseCount,                                      'Transactions totales', '#22d3ee'],
    ['🎯', $goalsCount,                                        'Objectifs d\'épargne', '#818cf8'],
  ];
  foreach ($stats as $s): ?>
  <div class="glass" style="padding:20px 16px;text-align:center;">
    <div style="font-size:28px;margin-bottom:8px;"><?= $s[0] ?></div>
    <div style="font-size:18px;font-weight:800;color:<?= $s[3] ?>;letter-spacing:-.02em;margin-bottom:4px;"><?= $s[1] ?></div>
    <div style="font-size:11.5px;color:#8b8fb0;"><?= $s[2] ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- ── Deux colonnes : infos + mdp ── -->
<div class="two-col" style="margin-bottom:22px;">

  <!-- Modifier les infos -->
  <div class="glass form-card">
    <div class="card-head" style="margin-bottom:20px;">
      <div><div class="eyebrow">Profil</div><h3>Mes informations</h3></div>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/profile">
      <input type="hidden" name="action" value="update_info">
      <div class="form-group" style="margin-bottom:14px;">
        <label>Prénom et nom</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
      </div>
      <div class="form-group" style="margin-bottom:22px;">
        <label>Adresse email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
      </div>
      <div class="form-actions">
        <button type="submit" class="pbtn pbtn-primary">
          <?= icon('check', 13) ?> Enregistrer
        </button>
      </div>
    </form>
  </div>

  <!-- Changer le mot de passe -->
  <div class="glass form-card">
    <div class="card-head" style="margin-bottom:20px;">
      <div><div class="eyebrow">Sécurité</div><h3>Changer le mot de passe</h3></div>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/profile">
      <input type="hidden" name="action" value="change_password">
      <div class="form-group" style="margin-bottom:14px;">
        <label>Mot de passe actuel</label>
        <input type="password" name="current_password" placeholder="••••••••" required autocomplete="current-password">
      </div>
      <div class="form-group" style="margin-bottom:14px;">
        <label>Nouveau mot de passe</label>
        <input type="password" name="new_password" placeholder="6 caractères minimum" required autocomplete="new-password">
      </div>
      <div class="form-group" style="margin-bottom:22px;">
        <label>Confirmer</label>
        <input type="password" name="confirm_password" placeholder="••••••••" required autocomplete="new-password">
      </div>
      <div class="form-actions">
        <button type="submit" class="pbtn pbtn-primary">
          <?= icon('check', 13) ?> Modifier
        </button>
      </div>
    </form>
  </div>

</div>

<!-- ── Zone danger ── -->
<div class="glass" style="padding:24px;border-color:rgba(244,63,94,.2);background:rgba(244,63,94,.04);">
  <div class="card-head" style="margin-bottom:14px;">
    <div>
      <div class="eyebrow" style="color:#ef4444;">Zone danger</div>
      <h3 style="color:#fca5a5;">Supprimer mon compte</h3>
    </div>
  </div>
  <p style="font-size:13.5px;color:#8b8fb0;margin-bottom:18px;line-height:1.6;">
    Cette action est <strong style="color:#fca5a5;">irréversible</strong>. Toutes tes données
    (dépenses, frigo, courses, objectifs) seront définitivement supprimées.
  </p>
  <form method="POST" action="<?= BASE_URL ?>/profile"
    onsubmit="return confirm('Es-tu sûr ? Cette action est irréversible.')">
    <input type="hidden" name="action" value="delete_account">
    <button type="submit" class="pbtn pbtn-danger">
      <?= icon('trash', 13) ?> Supprimer mon compte
    </button>
  </form>
</div>
