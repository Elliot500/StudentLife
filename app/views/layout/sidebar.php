  <!-- Sidebar -->
  <aside class="sidebar glass">
    <div class="sidebar-section">Espace</div>

    <a href="<?= BASE_URL ?>/dashboard"
       class="nav-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
      <span class="ico"><?= icon('home', 15) ?></span>
      Dashboard
    </a>

    <a href="<?= BASE_URL ?>/expenses"
       class="nav-item <?= ($currentPage ?? '') === 'expenses' ? 'active' : '' ?>">
      <span class="ico"><?= icon('wallet', 15) ?></span>
      Dépenses
    </a>

    <a href="<?= BASE_URL ?>/fridge"
       class="nav-item <?= ($currentPage ?? '') === 'fridge' ? 'active' : '' ?>">
      <span class="ico"><?= icon('fridge', 15) ?></span>
      Frigo
      <?php if (($fridgeAlerts ?? 0) > 0): ?>
        <span class="badge"><?= (int)($fridgeAlerts ?? 0) ?></span>
      <?php endif; ?>
    </a>

    <a href="<?= BASE_URL ?>/shopping"
       class="nav-item <?= ($currentPage ?? '') === 'shopping' ? 'active' : '' ?>">
      <span class="ico"><?= icon('cart', 15) ?></span>
      Courses
      <?php if (($shoppingTodo ?? 0) > 0): ?>
        <span class="badge"><?= (int)($shoppingTodo ?? 0) ?></span>
      <?php endif; ?>
    </a>

    <div class="sidebar-section">Compte</div>

    <a href="<?= BASE_URL ?>/auth/logout" class="nav-item">
      <span class="ico"><?= icon('logout', 15) ?></span>
      Se déconnecter
    </a>
  </aside>
