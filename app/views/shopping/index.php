<?php if ($flash): ?>
  <div class="flash <?= htmlspecialchars($flash['type']) ?>">
    <?= $flash['type'] === 'success' ? '✓' : '⚠️' ?>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

<?php
$todo   = array_filter($items, fn($i) => $i['status'] === 'a_acheter');
$done   = array_filter($items, fn($i) => $i['status'] === 'achete');
?>

<div class="page-header">
  <div>
    <div class="page-title">Liste de courses 🛒</div>
    <div class="page-sub">
      <?= count($todo) ?> à acheter · <?= count($done) ?> déjà achetés
    </div>
  </div>
</div>

<!-- Formulaire ajout -->
<div class="glass form-card">
  <div class="card-head" style="margin-bottom:18px;">
    <div>
      <div class="eyebrow">Nouvel article</div>
      <h3>Ajouter à la liste</h3>
    </div>
  </div>
  <form method="POST" action="<?= BASE_URL ?>/shopping">
    <div class="form-grid">
      <div class="form-group">
        <label>Article</label>
        <input type="text" name="name" placeholder="Ex: Pain complet" required>
      </div>
      <div class="form-group">
        <label>Quantité</label>
        <input type="number" name="quantity" step="0.5" min="0.5" value="1">
      </div>
      <div class="form-group">
        <label>Unité</label>
        <input type="text" name="unit" placeholder="kg, L, pièces…">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="pbtn pbtn-primary">
        <?= icon('plus', 13) ?> Ajouter
      </button>
    </div>
  </form>
</div>

<!-- À acheter -->
<?php if (!empty($todo)): ?>
<div class="glass" style="padding:24px;margin-bottom:18px;">
  <div class="card-head">
    <div>
      <div class="eyebrow">À acheter</div>
      <h3><?= count($todo) ?> article<?= count($todo) > 1 ? 's' : '' ?></h3>
    </div>
  </div>
  <div class="shopping-list">
    <?php foreach ($todo as $item): ?>
    <div class="shop-row">
      <a href="<?= BASE_URL ?>/shopping/toggle/<?= $item['id'] ?>" class="bubble" title="Marquer comme acheté">
        <?= icon('check', 12) ?>
      </a>
      <span class="rname"><?= htmlspecialchars($item['name']) ?></span>
      <span class="rqty"><?= htmlspecialchars($item['quantity'] . ($item['unit'] ? ' ' . $item['unit'] : '')) ?></span>
      <a href="<?= BASE_URL ?>/shopping/delete/<?= $item['id'] ?>"
         style="color:#6b6f8e;padding:4px;"
         onclick="return confirm('Supprimer cet article ?')">
        <?= icon('trash', 14) ?>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Déjà achetés -->
<?php if (!empty($done)): ?>
<div class="glass" style="padding:24px;">
  <div class="card-head">
    <div>
      <div class="eyebrow">Déjà achetés</div>
      <h3><?= count($done) ?> article<?= count($done) > 1 ? 's' : '' ?></h3>
    </div>
  </div>
  <div class="shopping-list">
    <?php foreach ($done as $item): ?>
    <div class="shop-row done">
      <a href="<?= BASE_URL ?>/shopping/toggle/<?= $item['id'] ?>" class="bubble done" title="Remettre à acheter">
        <?= icon('check', 12) ?>
      </a>
      <span class="rname"><?= htmlspecialchars($item['name']) ?></span>
      <span class="rqty"><?= htmlspecialchars($item['quantity'] . ($item['unit'] ? ' ' . $item['unit'] : '')) ?></span>
      <a href="<?= BASE_URL ?>/shopping/delete/<?= $item['id'] ?>"
         style="color:#6b6f8e;padding:4px;"
         onclick="return confirm('Supprimer cet article ?')">
        <?= icon('trash', 14) ?>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php if (empty($items)): ?>
<div class="glass" style="padding:24px;">
  <div class="empty-state"><div class="big">🛒</div>Ta liste de courses est vide !</div>
</div>
<?php endif; ?>
