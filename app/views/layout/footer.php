
</div><!-- /.shell -->

<script>
// ── Hamburger menu mobile ──────────────────────────────
(function () {
  var btn     = document.getElementById('hamburger-btn');
  var sidebar = document.querySelector('.sidebar');
  var overlay = document.getElementById('sidebar-overlay');
  if (!btn || !sidebar) return;

  function open()  { sidebar.classList.add('open');  overlay.classList.add('active');  btn.classList.add('open');  document.body.style.overflow = 'hidden'; }
  function close() { sidebar.classList.remove('open'); overlay.classList.remove('active'); btn.classList.remove('open'); document.body.style.overflow = ''; }

  btn.addEventListener('click', function () { sidebar.classList.contains('open') ? close() : open(); });
  overlay.addEventListener('click', close);

  // Ferme si on clique sur un lien du sidebar
  sidebar.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', close); });
}());

// ── Theme toggle ───────────────────────────────────────
(function () {
  var html   = document.documentElement;
  var btn    = document.getElementById('theme-toggle');
  var sun    = document.getElementById('icon-sun');
  var moon   = document.getElementById('icon-moon');

  function applyTheme(t) {
    html.setAttribute('data-theme', t);
    localStorage.setItem('slh-theme', t);
    if (t === 'dark') {
      if (sun)  sun.style.display  = '';
      if (moon) moon.style.display = 'none';
    } else {
      if (sun)  sun.style.display  = 'none';
      if (moon) moon.style.display = '';
    }
  }

  // Init avec le thème sauvegardé
  applyTheme(localStorage.getItem('slh-theme') || 'dark');

  if (btn) {
    btn.addEventListener('click', function () {
      var current = html.getAttribute('data-theme');
      applyTheme(current === 'dark' ? 'light' : 'dark');
    });
  }
}());
</script>

<script>
(function () {
  var cards = document.querySelectorAll('.glass');
  cards.forEach(function (card) {
    card.addEventListener('pointermove', function (e) {
      var r = card.getBoundingClientRect();
      card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      card.style.setProperty('--my', (e.clientY - r.top)  + 'px');
    });
    card.addEventListener('pointerleave', function () {
      card.style.setProperty('--mx', '-500px');
      card.style.setProperty('--my', '-500px');
    });
  });
}());
</script>

</body>
</html>
