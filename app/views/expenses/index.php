<?php
$catLabels = [
    'alimentation' => 'Alimentation',
    'logement'     => 'Logement',
    'transport'    => 'Transport',
    'loisirs'      => 'Loisirs',
    'sante'        => 'Santé',
    'education'    => 'Éducation',
    'autre'        => 'Autre',
];
$flash          = $flash          ?? null;
$totalIncome    = $totalIncome    ?? 0;
$totalExpenses  = $totalExpenses  ?? 0;
$balance        = $totalIncome - $totalExpenses;
?>

<?php if (!empty($flash)): ?>
  <div class="flash <?= htmlspecialchars($flash['type']) ?>">
    <?= $flash['type'] === 'success' ? '✓' : '⚠️' ?>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

<!-- En-tête page -->
<div class="page-header">
  <div>
    <div class="page-title">Dépenses & Revenus</div>
    <div class="page-sub">
      Revenus : <strong style="color:#34d399;"><?= number_format($totalIncome, 2, ',', ' ') ?> €</strong>
      &nbsp;·&nbsp;
      Dépenses : <strong style="color:#f97316;"><?= number_format($totalExpenses, 2, ',', ' ') ?> €</strong>
      &nbsp;·&nbsp;
      Solde :
      <strong style="color:<?= $balance >= 0 ? '#34d399' : '#ef4444' ?>;">
        <?= $balance >= 0 ? '+' : '-' ?><?= number_format(abs($balance), 2, ',', ' ') ?> €
      </strong>
    </div>
  </div>
</div>

<!-- ─── FORMULAIRES CÔTE À CÔTE ─────────────────────── -->
<div class="two-col" style="margin-bottom:22px;">

  <!-- Ajouter un revenu -->
  <div class="glass form-card" style="margin-bottom:0;">
    <div class="card-head" style="margin-bottom:18px;">
      <div>
        <div class="eyebrow" style="color:#34d399;">Nouveau</div>
        <h3>Ajouter un revenu</h3>
      </div>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/expenses">
      <input type="hidden" name="type" value="income">
      <div class="form-grid">
        <div class="form-group">
          <label>Montant (€)</label>
          <input type="number" name="amount" step="0.01" min="0.01" placeholder="0,00" required>
        </div>
        <div class="form-group">
          <label>Source</label>
          <input type="text" name="source" placeholder="Bourse, Job, Aide…" required>
        </div>
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="date" value="<?= date('Y-m-d') ?>">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="pbtn" style="background:linear-gradient(135deg,#34d399,#10b981);color:#0a0a18;border-color:rgba(52,211,153,.3);box-shadow:0 10px 24px -8px rgba(52,211,153,.4);">
          <?= icon('plus', 13) ?> Enregistrer le revenu
        </button>
      </div>
    </form>
  </div>

  <!-- Ajouter une dépense -->
  <div class="glass form-card" style="margin-bottom:0;">
    <div class="card-head" style="margin-bottom:18px;">
      <div>
        <div class="eyebrow">Nouveau</div>
        <h3>Ajouter une dépense</h3>
      </div>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/expenses">
      <input type="hidden" name="type" value="expense">
      <div class="form-grid">
        <div class="form-group">
          <label>Montant (€)</label>
          <input type="number" name="amount" step="0.01" min="0.01" placeholder="0,00" required>
        </div>
        <div class="form-group">
          <label>Catégorie</label>
          <select name="category">
            <?php foreach ($catLabels as $val => $label): ?>
            <option value="<?= $val ?>"><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Description</label>
          <input type="text" name="description" placeholder="Ex: Courses Lidl">
        </div>
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="date" value="<?= date('Y-m-d') ?>">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="pbtn pbtn-primary">
          <?= icon('plus', 13) ?> Enregistrer la dépense
        </button>
      </div>
    </form>
  </div>

</div>

<!-- ─── LISTES CÔTE À CÔTE ───────────────────────────── -->
<div class="two-col">

  <!-- Historique revenus -->
  <div class="glass" style="padding:24px;">
    <div class="card-head">
      <div>
        <div class="eyebrow" style="color:#34d399;">Historique</div>
        <h3>Mes revenus</h3>
      </div>
    </div>
    <?php if (empty($incomes)): ?>
      <div class="empty-state"><div class="big">💰</div>Aucun revenu enregistré.</div>
    <?php else: ?>
      <table class="expense-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Source</th>
            <th style="text-align:right;">Montant</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($incomes as $inc): ?>
          <tr>
            <td style="color:#8b8fb0;white-space:nowrap;"><?= date('d/m/Y', strtotime($inc['date'])) ?></td>
            <td><?= htmlspecialchars($inc['source']) ?></td>
            <td style="text-align:right;font-weight:700;color:#34d399;font-feature-settings:'tnum';">
              +<?= number_format($inc['amount'], 2, ',', ' ') ?> €
            </td>
            <td style="text-align:right;">
              <a href="<?= BASE_URL ?>/expenses/deleteIncome/<?= $inc['id'] ?>"
                 class="pbtn pbtn-danger pbtn-sm"
                 onclick="return confirm('Supprimer ce revenu ?')">
                <?= icon('trash', 13) ?>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Historique dépenses -->
  <div class="glass" style="padding:24px;">
    <div class="card-head">
      <div>
        <div class="eyebrow">Historique</div>
        <h3>Mes dépenses</h3>
      </div>
    </div>
    <?php if (empty($expenses)): ?>
      <div class="empty-state"><div class="big">💸</div>Aucune dépense enregistrée.</div>
    <?php else: ?>
      <table class="expense-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Catégorie</th>
            <th style="text-align:right;">Montant</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($expenses as $exp): ?>
          <tr>
            <td style="color:#8b8fb0;white-space:nowrap;"><?= date('d/m/Y', strtotime($exp['date'])) ?></td>
            <td><?= htmlspecialchars($exp['description'] ?? '—') ?></td>
            <td><span class="cat-badge <?= $exp['category'] ?>"><?= $catLabels[$exp['category']] ?? $exp['category'] ?></span></td>
            <td style="text-align:right;font-weight:700;font-feature-settings:'tnum';"><?= number_format($exp['amount'], 2, ',', ' ') ?> €</td>
            <td style="text-align:right;">
              <a href="<?= BASE_URL ?>/expenses/delete/<?= $exp['id'] ?>"
                 class="pbtn pbtn-danger pbtn-sm"
                 onclick="return confirm('Supprimer cette dépense ?')">
                <?= icon('trash', 13) ?>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</div>
