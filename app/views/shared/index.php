<?php
$group              = $group              ?? null;
$flash              = $flash              ?? null;
$balances           = $balances           ?? [];
$members            = $members            ?? [];
$expenses           = $expenses           ?? [];
$sentInvitations    = $sentInvitations    ?? [];
$pendingInvitations = $pendingInvitations ?? [];
$currentUid         = $currentUid         ?? 0;
?>

<!-- Invitations reçues en attente -->
<?php foreach ($pendingInvitations as $inv): ?>
<div class="alert-blob" style="background:linear-gradient(135deg,rgba(99,102,241,.15),rgba(34,211,238,.08));border-color:rgba(129,140,248,.3);margin-bottom:14px;">
  <div class="a-ico" style="background:linear-gradient(135deg,#6366f1,#22d3ee);font-size:20px;">👥</div>
  <div style="flex:1;">
    <strong style="color:#e0e7ff;">
      <?= htmlspecialchars($inv['inviter_name']) ?> t'invite à rejoindre « <?= htmlspecialchars($inv['group_name']) ?> »
    </strong>
    <small style="color:#a5b4fc;display:block;margin-top:2px;">Reçue le <?= date('d/m/Y', strtotime($inv['created_at'])) ?></small>
  </div>
  <div style="display:flex;gap:8px;">
    <form method="POST" action="<?= BASE_URL ?>/shared">
      <input type="hidden" name="action" value="accept">
      <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
      <button type="submit" class="pbtn pbtn-primary pbtn-sm">✓ Accepter</button>
    </form>
    <form method="POST" action="<?= BASE_URL ?>/shared">
      <input type="hidden" name="action" value="decline">
      <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
      <button type="submit" class="pbtn pbtn-danger pbtn-sm">✕ Refuser</button>
    </form>
  </div>
</div>
<?php endforeach; ?>

<?php if ($flash): ?>
  <div class="flash <?= htmlspecialchars($flash['type']) ?>">
    <?= match($flash['type']) { 'success' => '✓', 'info' => 'ℹ️', default => '⚠️' } ?>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <div class="page-title">Colocation</div>
    <div class="page-sub"><?= $group ? htmlspecialchars($group['name']) : 'Aucun groupe' ?></div>
  </div>
</div>

<?php if (!$group && empty($pendingInvitations)): ?>
  <!-- Pas de groupe et pas d'invitation -->
  <div class="glass" style="padding:40px;max-width:480px;margin:0 auto;text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">🏠</div>
    <h3 style="font-size:20px;font-weight:700;color:#f5f7ff;margin:0 0 8px;">Crée ta colocation</h3>
    <p style="color:#8b8fb0;font-size:14px;margin-bottom:24px;">Crée un groupe et invite tes colocataires par email.</p>
    <form method="POST" action="<?= BASE_URL ?>/shared">
      <input type="hidden" name="action" value="create_group">
      <div class="form-group" style="margin-bottom:16px;text-align:left;">
        <label>Nom du groupe</label>
        <input type="text" name="group_name" placeholder="Ex: Coloc Rue de la Paix" required>
      </div>
      <button type="submit" class="pbtn pbtn-primary w-full" style="justify-content:center;">
        <?= icon('plus', 14) ?> Créer le groupe
      </button>
    </form>
  </div>

<?php elseif (!$group && !empty($pendingInvitations)): ?>
  <!-- Invitation(s) en attente, pas encore dans un groupe -->
  <div class="glass" style="padding:32px;text-align:center;margin-top:8px;">
    <p style="color:#8b8fb0;">Accepte une invitation ci-dessus pour rejoindre une colocation, ou crée la tienne.</p>
    <form method="POST" action="<?= BASE_URL ?>/shared" style="margin-top:20px;max-width:360px;margin-left:auto;margin-right:auto;">
      <input type="hidden" name="action" value="create_group">
      <div style="display:flex;gap:10px;">
        <input type="text" name="group_name" placeholder="Nom du groupe" required
          style="flex:1;padding:11px 14px;border-radius:12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:#e8ebf5;font-size:13px;outline:none;font-family:inherit;">
        <button type="submit" class="pbtn pbtn-primary" style="white-space:nowrap;"><?= icon('plus', 13) ?> Créer</button>
      </div>
    </form>
  </div>

<?php else: ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:18px;margin-bottom:22px;">

  <!-- Membres + inviter -->
  <div class="glass" style="padding:24px;">
    <div class="card-head" style="margin-bottom:20px;">
      <div><div class="eyebrow">Groupe</div><h3>Membres (<?= count($members) ?>)</h3></div>
    </div>

    <!-- Liste membres -->
    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:22px;">
      <?php foreach ($members as $m): ?>
      <div style="display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#22d3ee);display:grid;place-items:center;font-weight:700;font-size:12px;color:#fff;flex-shrink:0;">
          <?php $n = explode(' ', $m['name']); echo strtoupper(($n[0][0] ?? '') . ($n[1][0] ?? '')); ?>
        </div>
        <div style="flex:1;">
          <div style="font-size:14px;font-weight:600;color:#f5f7ff;"><?= htmlspecialchars($m['name']) ?></div>
          <div style="font-size:12px;color:#8b8fb0;"><?= htmlspecialchars($m['email']) ?></div>
        </div>
        <?php if ($m['id'] === $currentUid): ?>
          <span style="font-size:11px;padding:3px 10px;border-radius:99px;background:rgba(99,102,241,.15);color:#a5b4fc;font-weight:600;">Moi</span>
        <?php elseif ((int)$group['created_by'] === $currentUid): ?>
          <form method="POST" action="<?= BASE_URL ?>/shared">
            <input type="hidden" name="action" value="remove_member">
            <input type="hidden" name="member_id" value="<?= $m['id'] ?>">
            <button type="submit" class="pbtn pbtn-danger pbtn-sm" onclick="return confirm('Retirer <?= htmlspecialchars($m['name']) ?> ?')"><?= icon('trash', 12) ?></button>
          </form>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Inviter par email -->
    <?php if ((int)$group['created_by'] === $currentUid): ?>
    <div style="border-top:1px solid rgba(255,255,255,.06);padding-top:18px;">
      <div class="eyebrow" style="margin-bottom:10px;">Inviter un colocataire</div>
      <form method="POST" action="<?= BASE_URL ?>/shared" style="display:flex;gap:10px;">
        <input type="hidden" name="action" value="invite">
        <input type="email" name="email" placeholder="email@exemple.com" required
          style="flex:1;padding:11px 14px;border-radius:12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:#e8ebf5;font-size:13px;outline:none;font-family:inherit;">
        <button type="submit" class="pbtn pbtn-primary" style="white-space:nowrap;">
          <?= icon('plus', 13) ?> Inviter
        </button>
      </form>
      <p style="font-size:12px;color:#6b6f8e;margin-top:8px;">
        L'invitation sera envoyée à l'utilisateur — il devra l'accepter.
      </p>
    </div>
    <?php endif; ?>

    <!-- Invitations envoyées en attente -->
    <?php $pending = array_filter($sentInvitations, fn($i) => $i['status'] === 'pending'); ?>
    <?php if (!empty($pending)): ?>
    <div style="border-top:1px solid rgba(255,255,255,.06);padding-top:16px;margin-top:16px;">
      <div class="eyebrow" style="margin-bottom:10px;">Invitations en attente</div>
      <?php foreach ($pending as $inv): ?>
      <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);margin-bottom:8px;">
        <span style="font-size:20px;">⏳</span>
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:600;color:#f5f7ff;"><?= htmlspecialchars($inv['invitee_name']) ?></div>
          <div style="font-size:11.5px;color:#8b8fb0;"><?= htmlspecialchars($inv['invitee_email']) ?></div>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/shared">
          <input type="hidden" name="action" value="cancel_invite">
          <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
          <button type="submit" class="pbtn pbtn-sm" style="font-size:11px;" onclick="return confirm('Annuler l\'invitation ?')">Annuler</button>
        </form>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Balances -->
  <div class="glass" style="padding:24px;">
    <div class="card-head" style="margin-bottom:16px;">
      <div><div class="eyebrow">Résumé</div><h3>Qui doit quoi ?</h3></div>
    </div>
    <?php if (empty($balances)): ?>
      <div class="empty-state" style="padding:16px;"><div class="big">⚖️</div>Tout est équilibré !</div>
    <?php else: ?>
      <?php foreach ($balances as $b): $net = $b['paid'] - $b['owes']; ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.06);">
        <span style="font-size:13px;font-weight:600;color:#f5f7ff;"><?= htmlspecialchars($b['name']) ?></span>
        <span style="font-size:15px;font-weight:800;color:<?= $net >= 0 ? '#34d399' : '#f97316' ?>;">
          <?= $net >= 0 ? '+' : '-' ?><?= number_format(abs($net), 2, ',', ' ') ?> €
        </span>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

<!-- Ajouter dépense -->
<div class="glass form-card" style="margin-bottom:18px;">
  <div class="card-head" style="margin-bottom:18px;"><div><div class="eyebrow">Nouveau</div><h3>Ajouter une dépense partagée</h3></div></div>
  <form method="POST" action="<?= BASE_URL ?>/shared">
    <input type="hidden" name="action" value="add">
    <div class="form-grid">
      <div class="form-group">
        <label>Montant (€)</label>
        <input type="number" name="amount" step="0.01" min="0.01" placeholder="0,00" required>
      </div>
      <div class="form-group" style="flex:2;">
        <label>Description</label>
        <input type="text" name="description" placeholder="Ex: Courses Lidl" required>
      </div>
      <div class="form-group">
        <label>Date</label>
        <input type="date" name="date" value="<?= date('Y-m-d') ?>">
      </div>
    </div>
    <div class="form-actions">
      <span class="text-muted text-sm">Divisé entre <?= count($members) ?> membre<?= count($members) > 1 ? 's' : '' ?></span>
      <button type="submit" class="pbtn pbtn-primary"><?= icon('plus', 13) ?> Ajouter</button>
    </div>
  </form>
</div>

<!-- Historique -->
<div class="glass" style="padding:24px;">
  <div class="card-head"><div><div class="eyebrow">Historique</div><h3>Dépenses du groupe</h3></div></div>
  <?php if (empty($expenses)): ?>
    <div class="empty-state"><div class="big">🤝</div>Aucune dépense partagée.</div>
  <?php else: ?>
    <table class="expense-table">
      <thead><tr><th>Date</th><th>Description</th><th>Payé par</th><th style="text-align:right;">Total</th><th style="text-align:right;">Ta part</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($expenses as $exp): ?>
        <tr>
          <td><?= date('d/m/Y', strtotime($exp['date'])) ?></td>
          <td><?= htmlspecialchars($exp['description']) ?></td>
          <td><?= htmlspecialchars($exp['payer_name']) ?></td>
          <td style="text-align:right;font-weight:700;"><?= number_format($exp['amount'], 2, ',', ' ') ?> €</td>
          <td style="text-align:right;color:#8b8fb0;"><?= number_format($exp['amount'] / max(1, count($members)), 2, ',', ' ') ?> €</td>
          <td style="text-align:right;">
            <form method="POST" action="<?= BASE_URL ?>/shared" style="display:inline;">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="expense_id" value="<?= $exp['id'] ?>">
              <button type="submit" class="pbtn pbtn-danger pbtn-sm" onclick="return confirm('Supprimer ?')"><?= icon('trash', 13) ?></button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php endif; ?>
