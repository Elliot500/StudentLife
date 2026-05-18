<?php
$flash      = $flash ?? null;
$days       = $days ?? [];
$grid       = $grid ?? [];
$monday     = $monday ?? date('Y-m-d');
$weekOffset = $weekOffset ?? 0;

$daysFr  = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];
$mealsFr = ['breakfast' => '🌅 Petit-déj', 'lunch' => '☀️ Déjeuner', 'dinner' => '🌙 Dîner'];
?>

<?php if ($flash): ?>
  <div class="flash <?= htmlspecialchars($flash['type']) ?>">
    <?= $flash['type'] === 'success' ? '✓' : '⚠️' ?> <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <div class="page-title">Agenda repas</div>
    <div class="page-sub">Semaine du <?= date('d/m', strtotime($monday)) ?> au <?= date('d/m/Y', strtotime($monday . ' +6 days')) ?></div>
  </div>
  <div style="display:flex;gap:8px;">
    <a href="<?= BASE_URL ?>/meals?week=<?= $weekOffset - 1 ?>" class="pbtn"><?= icon('up', 13) ?> Semaine préc.</a>
    <a href="<?= BASE_URL ?>/meals?week=0" class="pbtn">Aujourd'hui</a>
    <a href="<?= BASE_URL ?>/meals?week=<?= $weekOffset + 1 ?>" class="pbtn">Semaine suiv. <?= icon('down', 13) ?></a>
  </div>
</div>

<div class="glass" style="padding:24px;overflow-x:auto;">
  <table style="width:100%;border-collapse:collapse;min-width:700px;">
    <thead>
      <tr>
        <th style="width:110px;padding:10px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:#6b6f8e;border-bottom:1px solid rgba(255,255,255,.06);">Repas</th>
        <?php foreach ($days as $i => $day): ?>
          <th style="padding:10px 8px;text-align:center;border-bottom:1px solid rgba(255,255,255,.06);<?= $day === date('Y-m-d') ? 'color:#22d3ee;' : 'color:#8b8fb0;' ?>font-size:12px;font-weight:700;">
            <?= $daysFr[$i] ?><br><span style="font-size:11px;font-weight:400;"><?= date('d/m', strtotime($day)) ?></span>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($mealsFr as $type => $label): ?>
      <tr>
        <td style="padding:10px;font-size:12px;font-weight:600;color:#8b8fb0;border-bottom:1px solid rgba(255,255,255,.04);"><?= $label ?></td>
        <?php foreach ($days as $day): ?>
          <?php $meal = $grid[$day][$type] ?? null; ?>
          <td style="padding:6px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:top;">
            <?php if ($meal): ?>
              <div style="background:rgba(99,102,241,.12);border:1px solid rgba(129,140,248,.2);border-radius:10px;padding:8px 10px;font-size:12px;color:#e8ebf5;position:relative;group:relative;">
                <?= htmlspecialchars($meal['name']) ?>
                <form method="POST" action="<?= BASE_URL ?>/meals?week=<?= $weekOffset ?>" style="display:inline;float:right;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="date" value="<?= $day ?>">
                  <input type="hidden" name="meal_type" value="<?= $type ?>">
                  <button type="submit" style="background:none;border:none;cursor:pointer;color:#6b6f8e;font-size:12px;padding:0;line-height:1;" title="Supprimer">✕</button>
                </form>
              </div>
            <?php else: ?>
              <form method="POST" action="<?= BASE_URL ?>/meals?week=<?= $weekOffset ?>">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="date" value="<?= $day ?>">
                <input type="hidden" name="meal_type" value="<?= $type ?>">
                <input type="text" name="name" placeholder="+ Ajouter"
                  style="width:100%;padding:6px 8px;border-radius:8px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);color:#8b8fb0;font-size:11.5px;outline:none;cursor:pointer;"
                  onblur="if(this.value.trim()) this.form.submit();">
              </form>
            <?php endif; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
