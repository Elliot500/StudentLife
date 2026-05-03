<?php
$fridge = new FridgeModel();
$catEmojis = [
    'fruits_legumes' => '🥗', 'produits_frais' => '🧀',
    'surgeles' => '❄️', 'epicerie' => '🥫',
    'boissons' => '🥤', 'autre' => '📦',
];
$catLabels = [
    'fruits_legumes' => 'Fruits & légumes',
    'produits_frais' => 'Produits frais',
    'surgeles'       => 'Surgelés',
    'epicerie'       => 'Épicerie',
    'boissons'       => 'Boissons',
    'autre'          => 'Autre',
];
?>

<?php if ($flash): ?>
  <div class="flash <?= htmlspecialchars($flash['type']) ?>">
    <?= $flash['type'] === 'success' ? '✓' : '⚠️' ?>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <div class="page-title">Mon Frigo 🧊</div>
    <div class="page-sub"><?= count($items) ?> produit<?= count($items) > 1 ? 's' : '' ?> stocké<?= count($items) > 1 ? 's' : '' ?></div>
  </div>
</div>

<!-- Formulaire ajout -->
<div class="glass form-card">
  <div class="card-head" style="margin-bottom:18px;">
    <div>
      <div class="eyebrow">Nouveau produit</div>
      <h3>Ajouter un aliment</h3>
    </div>
  </div>
  <form method="POST" action="<?= BASE_URL ?>/fridge">
    <div class="form-grid">
      <div class="form-group">
        <label>Nom du produit</label>
        <input type="text" name="name" placeholder="Ex: Yaourts nature" required>
      </div>
      <div class="form-group">
        <label>Quantité</label>
        <input type="number" name="quantity" step="0.5" min="0.5" value="1">
      </div>
      <div class="form-group">
        <label>Unité</label>
        <input type="text" name="unit" placeholder="g, L, pièces…">
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
        <label>Date de péremption</label>
        <input type="date" name="expiry_date">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="pbtn pbtn-primary">
        <?= icon('plus', 13) ?> Ajouter au frigo
      </button>
    </div>
  </form>
</div>

<!-- Grille des produits -->
<div class="glass" style="padding:24px;">
  <div class="card-head">
    <div>
      <div class="eyebrow">Inventaire</div>
      <h3>Contenu du frigo</h3>
    </div>
  </div>

  <?php if (empty($items)): ?>
    <div class="empty-state"><div class="big">🧊</div>Ton frigo est vide — ajoute des produits !</div>
  <?php else: ?>
    <div class="frig-grid">
      <?php foreach ($items as $item):
        $status = $fridge->expiryStatus($item['expiry_date']);
        $days   = $fridge->daysUntilExpiry($item['expiry_date']);
        $emoji  = $catEmojis[$item['category']] ?? '📦';
      ?>
      <div class="frig-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
          <div class="emo"><?= $emoji ?></div>
          <a href="<?= BASE_URL ?>/fridge/delete/<?= $item['id'] ?>"
             style="color:#6b6f8e;padding:4px;"
             onclick="return confirm('Supprimer ce produit ?')">
            <?= icon('trash', 14) ?>
          </a>
        </div>
        <div>
          <div class="nm"><?= htmlspecialchars($item['name']) ?></div>
          <div class="meta"><?= htmlspecialchars($item['quantity'] . ($item['unit'] ? ' ' . $item['unit'] : '')) ?></div>
        </div>
        <?php if ($status === 'bad'): ?>
          <span class="exp-pill bad">❌ Périmé</span>
        <?php elseif ($status === 'warn'): ?>
          <span class="exp-pill warn">⚠️ Dans <?= $days ?>j</span>
        <?php elseif ($days !== null): ?>
          <span class="exp-pill ok">✓ Dans <?= $days ?>j</span>
        <?php else: ?>
          <span class="exp-pill ok">✓ Sans date</span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
