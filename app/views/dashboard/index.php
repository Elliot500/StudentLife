<?php
// ─── Helpers donut ────────────────────────────────────
function polar(float $cx, float $cy, float $r, float $t): array {
    $a = ($t - 0.25) * M_PI * 2;
    return [$cx + $r * cos($a), $cy + $r * sin($a)];
}
function donutArc(float $s0, float $s1): string {
    [$x0,$y0] = polar(50,50,42,$s0); [$x1,$y1] = polar(50,50,42,$s1);
    [$x2,$y2] = polar(50,50,28,$s1); [$x3,$y3] = polar(50,50,28,$s0);
    $lg = ($s1-$s0) > 0.5 ? 1 : 0;
    return "M $x0 $y0 A 42 42 0 $lg 1 $x1 $y1 L $x2 $y2 A 28 28 0 $lg 0 $x3 $y3 Z";
}

// Couleurs par catégorie
$catColors = [
    'alimentation' => ['#6ee7b7','#10b981'],
    'logement'     => ['#93c5fd','#3b82f6'],
    'transport'    => ['#67e8f9','#06b6d4'],
    'loisirs'      => ['#c4b5fd','#8b5cf6'],
    'sante'        => ['#fde68a','#f59e0b'],
    'education'    => ['#a5b4fc','#6366f1'],
    'autre'        => ['#d1d5db','#9ca3af'],
];
$catLabels = [
    'alimentation' => 'Alimentation',
    'logement'     => 'Logement',
    'transport'    => 'Transport',
    'loisirs'      => 'Loisirs',
    'sante'        => 'Santé',
    'education'    => 'Éducation',
    'autre'        => 'Autre',
];

// ─── Prépare segments donut ───────────────────────────
$sum = array_sum(array_column($byCategory, 'total'));
$segments = [];
$cum = 0;
foreach ($byCategory as $cat) {
    $s0 = $sum > 0 ? $cum / $sum : 0;
    $cum += $cat['total'];
    $s1 = $sum > 0 ? $cum / $sum : 0;
    $segments[] = ['cat' => $cat['category'], 'total' => $cat['total'], 's0' => $s0, 's1' => $s1];
}

// ─── Données frigo avec statut ────────────────────────
$fridge = new FridgeModel();
$catEmojis = [
    'fruits_legumes' => '🥗', 'produits_frais' => '🧀',
    'surgeles' => '❄️', 'epicerie' => '🥫',
    'boissons' => '🥤', 'autre' => '📦',
];

$daysFr = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
$monthsFr = ['','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
$now = new DateTime();
$dayName  = $daysFr[(int)$now->format('N') - 1];
$day      = $now->format('j');
$monthNum = (int)$now->format('n');
$year     = $now->format('Y');
$dateStr  = ucfirst($dayName) . ' ' . $day . ' ' . $monthsFr[$monthNum] . ' ' . $year;

$user = $_SESSION['user'] ?? ['name' => 'Utilisateur'];
$firstName = explode(' ', $user['name'])[0];
?>

<!-- Bandeau bienvenue -->
<div class="welcome glass">
  <div>
    <div class="greet">Bonjour <?= htmlspecialchars($firstName) ?> ✨</div>
    <div class="greet-sub"><?= $dateStr ?> · Voici un résumé de ton mois</div>
  </div>
  <div style="display:flex;gap:8px;">
    <a href="<?= BASE_URL ?>/expenses" class="pbtn">
      <?= icon('wallet', 13) ?> Dépenses
    </a>
    <a href="<?= BASE_URL ?>/shopping" class="pbtn pbtn-primary">
      <?= icon('cart', 14) ?> Liste de courses
    </a>
  </div>
</div>

<!-- Stats 3 colonnes -->
<div class="stats">
  <div class="glass stat s1">
    <div class="stat-label">
      <span class="stat-icon" style="color:#059669;"><?= icon('down', 14) ?></span>
      Revenus
    </div>
    <div class="stat-value">
      <?= number_format(floor($income), 0, ',', ' ') ?>
      <span class="cents">,<?= substr(number_format($income, 2, '.', ''), -2) ?> €</span>
    </div>
    <div class="stat-foot">
      <span>Ce mois-ci</span>
      <span class="delta-pill up"><?= icon('up', 10) ?> stable</span>
    </div>
  </div>
  <div class="glass stat s2">
    <div class="stat-label">
      <span class="stat-icon" style="color:#f97316;"><?= icon('up', 14) ?></span>
      Dépenses
    </div>
    <div class="stat-value">
      <?= number_format(floor($totalExpenses), 0, ',', ' ') ?>
      <span class="cents">,<?= substr(number_format($totalExpenses, 2, '.', ''), -2) ?> €</span>
    </div>
    <div class="stat-foot">
      <span><?= count($byCategory) ?> catégories</span>
      <span class="delta-pill down"><?= icon('up', 10) ?> ce mois</span>
    </div>
  </div>
  <div class="glass stat s3">
    <div class="stat-label">
      <span class="stat-icon" style="color:#6366f1;"><?= icon('target', 14) ?></span>
      Solde restant
    </div>
    <div class="stat-value" style="background:<?= $balance >= 0 ? 'linear-gradient(120deg,#6366f1,#22d3ee)' : 'linear-gradient(120deg,#f97316,#ef4444)' ?>;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;">
      <?= $balance >= 0 ? '+' : '-' ?><?= number_format(floor(abs($balance)), 0, ',', ' ') ?>
      <span class="cents" style="-webkit-text-fill-color:#6b6f8e;">,<?= substr(number_format(abs($balance), 2, '.', ''), -2) ?> €</span>
    </div>
    <div class="stat-foot">
      <span>sur <?= number_format($income, 0, ',', ' ') ?> € de revenus</span>
      <span class="delta-pill <?= $balance >= 0 ? 'up' : 'down' ?>"><?= icon('check', 10) ?> <?= $balance >= 0 ? 'dans la cible' : 'dépassé' ?></span>
    </div>
  </div>
</div>

<!-- ── Score + Prévisionnel ── -->
<div style="display:grid;grid-template-columns:220px 1fr;gap:18px;margin-bottom:22px;">

  <!-- Score mensuel -->
  <div class="glass" style="padding:24px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
    <?php
      $scoreColor = $score >= 70 ? '#34d399' : ($score >= 40 ? '#f59e0b' : '#ef4444');
      $scoreLabel = $score >= 70 ? '🏆 Excellent' : ($score >= 40 ? '⚡ Correct' : '⚠️ Attention');
      $dash = round($score * 2.51); // 251 = circonférence 2π×40
    ?>
    <div class="eyebrow">Score du mois</div>
    <svg width="90" height="90" viewBox="0 0 90 90">
      <circle cx="45" cy="45" r="40" fill="none" stroke="rgba(255,255,255,.06)" stroke-width="8"/>
      <circle cx="45" cy="45" r="40" fill="none" stroke="<?= $scoreColor ?>" stroke-width="8"
        stroke-dasharray="<?= $dash ?> 251" stroke-dashoffset="62.75" stroke-linecap="round"
        style="filter:drop-shadow(0 0 6px <?= $scoreColor ?>88);"/>
      <text x="45" y="50" text-anchor="middle" font-size="20" font-weight="900" fill="<?= $scoreColor ?>"><?= $score ?></text>
    </svg>
    <span style="font-size:12px;font-weight:600;color:<?= $scoreColor ?>;"><?= $scoreLabel ?></span>
  </div>

  <!-- Prévisionnel -->
  <div class="glass" style="padding:24px;">
    <div class="eyebrow">Prévisionnel</div>
    <h3 style="font-size:15px;font-weight:700;color:#f5f7ff;margin:0 0 16px;">À la fin du mois...</h3>
    <?php
      $projColor = $projected <= $income ? '#34d399' : '#ef4444';
      $projLabel = $projected <= $income ? '✓ Tu restera dans ton budget' : '⚠️ Tu dépasseras ton budget';
    ?>
    <div style="display:flex;align-items:baseline;gap:10px;margin-bottom:10px;">
      <span style="font-size:36px;font-weight:900;color:<?= $projColor ?>;"><?= number_format($projected, 0, ',', ' ') ?> €</span>
      <span style="color:#8b8fb0;font-size:13px;">de dépenses projetées</span>
    </div>
    <div style="font-size:13px;color:<?= $projColor ?>;margin-bottom:14px;"><?= $projLabel ?></div>
    <div style="background:rgba(255,255,255,.06);border-radius:99px;height:8px;overflow:hidden;">
      <div style="height:100%;border-radius:99px;width:<?= min(100, round($projected / max(1,$income) * 100)) ?>%;background:<?= $projColor ?>;"></div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:11px;color:#6b6f8e;">
      <span>0 €</span><span>Budget : <?= number_format($income, 0, ',', ' ') ?> €</span>
    </div>
  </div>
</div>

<!-- ── Alertes budget ── -->
<?php if (!empty($budgetAlerts ?? [])): ?>
<div style="display:flex;flex-direction:column;gap:10px;margin-bottom:22px;">
  <?php foreach ($budgetAlerts as $alert): ?>
  <div class="alert-blob" style="background:linear-gradient(135deg,rgba(<?= $alert['pct'] >= 100 ? '244,63,94' : '245,158,11' ?>,.15),rgba(<?= $alert['pct'] >= 100 ? '225,29,72' : '249,115,22' ?>,.08));border-color:rgba(<?= $alert['pct'] >= 100 ? '244,63,94' : '245,158,11' ?>,.25);">
    <div class="a-ico" style="background:linear-gradient(135deg,<?= $alert['pct'] >= 100 ? '#ef4444,#dc2626' : '#f59e0b,#d97706' ?>);"><?= $alert['pct'] >= 100 ? '🔴' : '🟡' ?></div>
    <div style="flex:1;">
      <strong style="color:<?= $alert['pct'] >= 100 ? '#fca5a5' : '#fde68a' ?>;">
        <?= htmlspecialchars(ucfirst($alert['category'])) ?> — <?= $alert['pct'] ?>% du budget utilisé
      </strong>
      <small style="color:<?= $alert['pct'] >= 100 ? '#f87171' : '#fbbf24' ?>;">
        <?= number_format($alert['spent'], 2, ',', ' ') ?> € sur <?= number_format($alert['limit'], 2, ',', ' ') ?> € alloués
      </small>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── Objectifs d'épargne ── -->
<?php if (!empty($goals ?? [])): ?>
<div class="glass" style="padding:24px;margin-bottom:22px;">
  <div class="card-head">
    <div><div class="eyebrow">Épargne</div><h3>Objectifs en cours</h3></div>
    <a href="<?= BASE_URL ?>/savings" class="pbtn pbtn-sm">Tout voir <?= icon('arr-r', 11) ?></a>
  </div>
  <?php $savingsModel = new SavingsGoalModel(); ?>
  <?php foreach ($goals as $goal): $pct = $savingsModel->progressPct($goal); ?>
  <div style="margin-bottom:14px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
      <span style="font-size:13px;font-weight:600;color:#f5f7ff;"><?= htmlspecialchars($goal['emoji']) ?> <?= htmlspecialchars($goal['name']) ?></span>
      <span style="font-size:12px;color:#8b8fb0;"><?= number_format($goal['current_amount'], 0, ',', ' ') ?> / <?= number_format($goal['target_amount'], 0, ',', ' ') ?> €</span>
    </div>
    <div style="background:rgba(255,255,255,.06);border-radius:99px;height:7px;overflow:hidden;">
      <div style="height:100%;border-radius:99px;width:<?= $pct ?>%;background:linear-gradient(90deg,#6366f1,#22d3ee);"></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── Graphe 6 mois ── -->
<?php if (!empty($history ?? [])): ?>
<div class="glass" style="padding:24px;margin-bottom:22px;">
  <div class="card-head">
    <div><div class="eyebrow">Historique</div><h3>Dépenses sur 6 mois</h3></div>
  </div>
  <?php
    $maxVal = max(array_column($history, 'total'));
    $monthsFrCourt = ['','jan','fév','mar','avr','mai','jun','jul','aoû','sep','oct','nov','déc'];
  ?>
  <div style="display:flex;align-items:flex-end;gap:10px;height:100px;padding-top:10px;">
    <?php foreach ($history as $h):
      $barH = $maxVal > 0 ? round($h['total'] / $maxVal * 90) : 0;
      $isCurrentMonth = date('Y-m') === $h['month'];
      [$y, $m] = explode('-', $h['month']);
    ?>
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;">
      <span style="font-size:10px;color:#8b8fb0;"><?= number_format($h['total'], 0, ',', ' ') ?></span>
      <div style="width:100%;height:<?= $barH ?>px;border-radius:6px 6px 0 0;
        background:<?= $isCurrentMonth ? 'linear-gradient(180deg,#6366f1,#22d3ee)' : 'rgba(99,102,241,.3)' ?>;
        min-height:4px;transition:height .4s;"></div>
      <span style="font-size:10px;font-weight:600;color:<?= $isCurrentMonth ? '#a5b4fc' : '#6b6f8e' ?>;"><?= $monthsFrCourt[(int)$m] ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Alerte frigo -->
<?php if ($fridgeAlerts > 0): ?>
<div class="alert-blob">
  <div class="a-ico">⚠️</div>
  <div style="flex:1;">
    <strong><?= $fridgeAlerts ?> produit<?= $fridgeAlerts > 1 ? 's' : '' ?> à surveiller dans ton frigo</strong>
    <small>Pense à les utiliser avant qu'ils ne périment.</small>
  </div>
  <a href="<?= BASE_URL ?>/fridge" class="pbtn">
    Voir le frigo <?= icon('arr-r', 12) ?>
  </a>
</div>
<?php endif; ?>

<!-- 2 colonnes : donut + courses -->
<div class="two-col">
  <!-- Donut chart -->
  <div class="glass donut-wrap">
    <div class="card-head">
      <div>
        <div class="eyebrow">Analyse</div>
        <h3>Dépenses par catégorie</h3>
      </div>
      <span class="pbtn" style="padding:6px 14px;font-size:12px;"><?= date('M Y') ?></span>
    </div>

    <?php if ($sum > 0): ?>
    <div class="donut-row">
      <svg class="donut-svg" viewBox="0 0 100 100">
        <defs>
          <?php foreach ($segments as $i => $seg):
            $colors = $catColors[$seg['cat']] ?? ['#818cf8','#6366f1'];
          ?>
          <linearGradient id="dg<?= $i ?>" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%"   stop-color="<?= $colors[0] ?>"/>
            <stop offset="100%" stop-color="<?= $colors[1] ?>"/>
          </linearGradient>
          <?php endforeach; ?>
        </defs>
        <?php foreach ($segments as $i => $seg): ?>
        <path d="<?= donutArc($seg['s0'], $seg['s1']) ?>"
              fill="url(#dg<?= $i ?>)"
              stroke="rgba(255,255,255,.7)" stroke-width="0.6"/>
        <?php endforeach; ?>
        <circle cx="50" cy="50" r="26" fill="rgba(255,255,255,.85)"/>
        <text x="50" y="47" text-anchor="middle" font-size="5.5" fill="#9a8ab8" font-weight="600">Total</text>
        <text x="50" y="58" text-anchor="middle" font-size="10" fill="#1a1530" font-weight="700"><?= number_format($sum, 0, ',', ' ') ?> €</text>
      </svg>

      <div class="donut-legend">
        <?php foreach ($segments as $i => $seg):
          $colors = $catColors[$seg['cat']] ?? ['#818cf8','#6366f1'];
          $pct    = $sum > 0 ? round($seg['total'] / $sum * 100) : 0;
        ?>
        <div class="legend-row">
          <span class="swatch" style="background:linear-gradient(135deg,<?= $colors[0] ?>,<?= $colors[1] ?>);"></span>
          <span><?= $catLabels[$seg['cat']] ?? $seg['cat'] ?></span>
          <span class="pct"><?= $pct ?>%</span>
          <span class="amt"><?= number_format($seg['total'], 0, ',', ' ') ?> €</span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php else: ?>
    <div class="empty-state"><div class="big">📊</div>Aucune dépense ce mois-ci</div>
    <?php endif; ?>
  </div>

  <!-- Shopping mini -->
  <div class="glass shop-card">
    <div class="card-head">
      <div>
        <div class="eyebrow">Aperçu</div>
        <h3>Liste de courses</h3>
      </div>
      <a href="<?= BASE_URL ?>/shopping" class="pbtn" style="padding:6px 14px;font-size:12px;">
        Tout voir <?= icon('arr-r', 11) ?>
      </a>
    </div>

    <?php if (empty($shopItems)): ?>
      <div class="empty-state" style="padding:20px;"><div class="big">🛒</div>Liste vide</div>
    <?php else: ?>
      <?php foreach ($shopItems as $item):
        $done = $item['status'] === 'achete';
      ?>
      <div class="shop-mini-row <?= $done ? 'done' : '' ?>">
        <span class="bubble <?= $done ? 'done' : '' ?>">
          <?= $done ? icon('check', 12) : '' ?>
        </span>
        <span class="sname"><?= htmlspecialchars($item['name']) ?></span>
        <span class="qty"><?= htmlspecialchars($item['quantity'] . ($item['unit'] ? ' ' . $item['unit'] : '')) ?></span>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Grille frigo -->
<div class="glass" style="padding:24px;">
  <div class="card-head">
    <div>
      <div class="eyebrow">Inventaire</div>
      <h3>Aperçu du frigo</h3>
    </div>
    <a href="<?= BASE_URL ?>/fridge" class="pbtn pbtn-primary">
      <?= icon('plus', 13) ?> Ajouter un produit
    </a>
  </div>

  <?php if (empty($fridgeItems)): ?>
    <div class="empty-state"><div class="big">🧊</div>Ton frigo est vide !</div>
  <?php else: ?>
    <div class="frig-grid">
      <?php foreach ($fridgeItems as $item):
        $status = $fridge->expiryStatus($item['expiry_date']);
        $days   = $fridge->daysUntilExpiry($item['expiry_date']);
        $emoji  = $catEmojis[$item['category']] ?? '📦';
      ?>
      <div class="frig-card">
        <div class="emo"><?= $emoji ?></div>
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
