<?php $goals = $goals ?? []; $flash = $flash ?? null; ?>

<?php if ($flash): ?>
  <div class="flash <?= htmlspecialchars($flash['type']) ?>">
    <?= $flash['type'] === 'success' ? '✓' : '⚠️' ?> <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <div class="page-title">Objectifs d'épargne</div>
    <div class="page-sub"><?= count($goals) ?> objectif<?= count($goals) > 1 ? 's' : '' ?> en cours</div>
  </div>
</div>

<!-- Créer un objectif -->
<div class="glass form-card mb-4">
  <div class="card-head" style="margin-bottom:18px;">
    <div><div class="eyebrow">Nouveau</div><h3>Créer un objectif</h3></div>
  </div>
  <form method="POST" action="<?= BASE_URL ?>/savings">
    <input type="hidden" name="action" value="create">
    <div class="form-grid">
      <div class="form-group">
        <label>Emoji</label>
        <input type="text" name="emoji" value="🎯" maxlength="4" style="width:80px;">
      </div>
      <div class="form-group" style="flex:2;">
        <label>Nom de l'objectif</label>
        <input type="text" name="name" placeholder="Ex: Voyage au Japon" required>
      </div>
      <div class="form-group">
        <label>Montant cible (€)</label>
        <input type="number" name="target_amount" step="0.01" min="1" placeholder="1 500,00" required>
      </div>
      <div class="form-group">
        <label>Date limite</label>
        <input type="date" name="deadline">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="pbtn pbtn-primary"><?= icon('plus', 13) ?> Créer l'objectif</button>
    </div>
  </form>
</div>

<!-- Liste des objectifs -->
<?php if (empty($goals)): ?>
  <div class="glass" style="padding:24px;">
    <div class="empty-state"><div class="big">🎯</div>Aucun objectif pour l'instant. Crée-en un !</div>
  </div>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:18px;">
    <?php foreach ($goals as $goal):
      $pct     = (new SavingsGoalModel())->progressPct($goal);
      $remain  = max(0, $goal['target_amount'] - $goal['current_amount']);
      $done    = $goal['current_amount'] >= $goal['target_amount'];
      $daysLeft = $goal['deadline'] ? (new DateTime($goal['deadline']))->diff(new DateTime('today'))->days * ((new DateTime($goal['deadline'])) >= new DateTime('today') ? 1 : -1) : null;
    ?>
    <div class="glass" style="padding:28px 32px;">
      <div style="display:flex;align-items:center;gap:18px;margin-bottom:20px;">
        <span style="font-size:36px;"><?= htmlspecialchars($goal['emoji']) ?></span>
        <div style="flex:1;">
          <div style="font-size:18px;font-weight:700;color:#f5f7ff;"><?= htmlspecialchars($goal['name']) ?></div>
          <?php if ($goal['deadline']): ?>
            <div class="text-muted text-sm">
              <?= $daysLeft !== null && $daysLeft >= 0 ? "⏳ $daysLeft jours restants" : '⚠️ Dépassé' ?>
              — échéance <?= date('d/m/Y', strtotime($goal['deadline'])) ?>
            </div>
          <?php endif; ?>
        </div>
        <div style="text-align:right;">
          <div style="font-size:22px;font-weight:800;color:#f5f7ff;"><?= number_format($goal['current_amount'], 2, ',', ' ') ?> €</div>
          <div class="text-muted text-sm">sur <?= number_format($goal['target_amount'], 2, ',', ' ') ?> €</div>
        </div>
      </div>

      <!-- Barre de progression -->
      <div style="background:rgba(255,255,255,.06);border-radius:99px;height:10px;margin-bottom:18px;overflow:hidden;">
        <div style="height:100%;border-radius:99px;width:<?= $pct ?>%;
          background:<?= $done ? 'linear-gradient(90deg,#34d399,#10b981)' : 'linear-gradient(90deg,#6366f1,#22d3ee)' ?>;
          transition:width .6s ease;"></div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:12px;color:#8b8fb0;"><?= $pct ?>% atteint <?= $done ? '🎉' : "· Il reste " . number_format($remain, 2, ',', ' ') . " €" ?></span>

        <div style="display:flex;gap:8px;">
          <?php if (!$done): ?>
          <form method="POST" action="<?= BASE_URL ?>/savings" style="display:flex;gap:6px;align-items:center;">
            <input type="hidden" name="action" value="contribute">
            <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">
            <input type="number" name="amount" step="0.01" min="0.01" placeholder="Montant €"
              style="width:120px;padding:7px 12px;border-radius:10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:#e8ebf5;font-size:13px;outline:none;">
            <button type="submit" class="pbtn pbtn-sm" style="background:linear-gradient(135deg,#34d399,#10b981);color:#0a0a18;">
              <?= icon('plus', 12) ?> Ajouter
            </button>
          </form>
          <?php endif; ?>
          <form method="POST" action="<?= BASE_URL ?>/savings">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">
            <button type="submit" class="pbtn pbtn-danger pbtn-sm" onclick="return confirm('Supprimer ?')"><?= icon('trash', 13) ?></button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
