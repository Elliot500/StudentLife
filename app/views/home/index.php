<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentLife Hub — Ta vie étudiante, organisée.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/y2k.css">

  <script type="importmap">
  {
    "imports": {
      "three":          "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
      "three/addons/":  "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
    }
  }
  </script>

  <style>
    #lp { position: relative; z-index: 2; overflow-x: hidden; }

    /* ── Keyframes ────────────────────────────────────── */
    @keyframes fadeDown  { from{opacity:0;transform:translateY(-24px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeUp    { from{opacity:0;transform:translateY(32px)}  to{opacity:1;transform:translateY(0)} }
    @keyframes fadeIn    { from{opacity:0} to{opacity:1} }
    @keyframes gradShift { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
    @keyframes bounce    { 0%,100%{transform:rotate(45deg) translateY(0)} 50%{transform:rotate(45deg) translateY(7px)} }
    @keyframes glowPulse { 0%,100%{box-shadow:0 20px 60px -15px rgba(99,102,241,.55)} 50%{box-shadow:0 24px 80px -12px rgba(34,211,238,.5)} }
    @keyframes ringPulse { 0%,100%{transform:scale(1);opacity:.5} 50%{transform:scale(1.07);opacity:.85} }
    @keyframes spin      { to{transform:rotate(360deg)} }

    /* ── Scroll reveal ────────────────────────────────── */
    [data-sr] {
      opacity: 0; transform: translateY(48px);
      transition: opacity .75s cubic-bezier(.22,1,.36,1), transform .75s cubic-bezier(.22,1,.36,1);
    }
    [data-sr].in          { opacity:1; transform:translateY(0); }
    [data-sr][data-d="1"] { transition-delay:.12s; }
    [data-sr][data-d="2"] { transition-delay:.24s; }
    [data-sr][data-d="3"] { transition-delay:.36s; }

    /* ── HERO — centré ────────────────────────────────── */
    .lp-hero {
      min-height: 100vh;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      text-align: center;
      padding: 100px 24px 120px;
      position: relative;
    }
    .hero-logo {
      position: absolute; top: 32px; left: 40px;
      display: flex; align-items: center; gap: 12px;
      font-size: 15px; font-weight: 600; color: #e8ebf5;
      animation: fadeIn .8s ease both; z-index: 5;
    }
    .hero-logo-mark {
      width: 36px; height: 36px; border-radius: 12px;
      background: linear-gradient(135deg,rgba(255,255,255,.25),rgba(255,255,255,.05)),
                  linear-gradient(135deg,#6366f1,#22d3ee);
      display: grid; place-items: center; color:#fff; font-weight:700; font-size:13px;
      box-shadow: inset 0 1px 1px rgba(255,255,255,.4), 0 6px 16px -6px rgba(99,102,241,.6);
    }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 7px 18px; border-radius: 99px;
      background: rgba(99,102,241,.12); border: 1px solid rgba(129,140,248,.28);
      color: #a5b4fc; font-size: 13px; font-weight: 600; margin-bottom: 32px;
      animation: fadeDown .9s cubic-bezier(.22,1,.36,1) .1s both;
    }
    .hero-dot {
      width:7px; height:7px; border-radius:50%; background:#22d3ee;
      box-shadow:0 0 8px rgba(34,211,238,.8); animation:glowPulse 2s ease-in-out infinite;
    }
    .hero-title {
      font-size: clamp(52px, 9vw, 108px);
      font-weight: 900; line-height: 1.02; letter-spacing: -0.038em; color: #f5f7ff;
      margin: 0 0 8px;
      animation: fadeUp .9s cubic-bezier(.22,1,.36,1) .2s both;
    }
    .hero-grad {
      display: block;
      background: linear-gradient(120deg, #22d3ee, #818cf8, #34d399, #22d3ee);
      background-size: 250% 100%;
      -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
      animation: gradShift 5s linear infinite;
    }
    .hero-sub {
      font-size: clamp(16px, 2vw, 20px); color: #8b8fb0;
      max-width: 520px; margin: 22px auto 44px; line-height: 1.7;
      animation: fadeUp .9s cubic-bezier(.22,1,.36,1) .35s both;
    }
    .hero-actions {
      display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;
      animation: fadeUp .9s cubic-bezier(.22,1,.36,1) .5s both;
    }
    .btn-primary {
      display: inline-flex; align-items: center; gap: 10px;
      padding: 16px 36px; border-radius: 99px; font-size: 15px; font-weight: 700;
      background: linear-gradient(135deg, #6366f1, #22d3ee); color: #fff; text-decoration: none;
      box-shadow: inset 0 1px 1px rgba(255,255,255,.3), 0 18px 40px -12px rgba(99,102,241,.6);
      transition: transform .25s, box-shadow .25s; position: relative; overflow: hidden;
      animation: glowPulse 3s ease-in-out 1s infinite;
    }
    .btn-primary::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,.18),transparent 55%); pointer-events:none; }
    .btn-primary:hover   { transform:translateY(-3px) scale(1.02); box-shadow:0 24px 60px -12px rgba(99,102,241,.7); }
    .btn-ghost { font-size:14px; font-weight:600; color:#8b8fb0; text-decoration:none; transition:color .2s; }
    .btn-ghost:hover { color:#e8ebf5; }
    .hero-scroll {
      position:absolute; bottom:36px; left:50%; transform:translateX(-50%);
      display:flex; flex-direction:column; align-items:center; gap:10px;
      color:#6b6f8e; font-size:11px; letter-spacing:.1em; text-transform:uppercase;
      animation:fadeIn 1s ease 1.4s both;
    }
    .scroll-chev {
      width:18px; height:18px;
      border-right:1.5px solid #6b6f8e; border-bottom:1.5px solid #6b6f8e;
      transform:rotate(45deg); animation:bounce 1.8s ease-in-out infinite;
    }

    /* ── SECTIONS ─────────────────────────────────────── */
    .lp-section   { padding: 120px 40px; max-width: 1100px; margin: 0 auto; }
    .lp-sep       { width:1px; height:120px; margin:0 auto; background:linear-gradient(to bottom,transparent,rgba(255,255,255,.1),transparent); }
    .section-label {
      display:inline-flex; align-items:center; gap:8px;
      font-size:11px; font-weight:700; text-transform:uppercase;
      letter-spacing:.18em; color:#22d3ee; margin-bottom:20px;
    }
    .section-label::before { content:''; display:block; width:20px; height:1.5px; background:#22d3ee; }
    .section-title { font-size:clamp(32px,5vw,60px); font-weight:800; letter-spacing:-.03em; color:#f5f7ff; line-height:1.1; margin:0 0 20px; }
    .section-sub   { font-size:17px; color:#8b8fb0; max-width:520px; line-height:1.6; }

    /* ── FEATURES — colonne unique ─────────────────────── */
    .feature-stack { display:flex; flex-direction:column; gap:20px; margin-top:64px; }

    /* Card Budget — texte gauche + pièce droite */
    .feat-budget {
      display: grid;
      grid-template-columns: 1fr 420px;
      align-items: center;
      gap: 0;
      padding: 0;
      min-height: 440px;
      overflow: hidden;
    }
    .feat-budget-text {
      padding: 52px 52px 52px 52px;
    }
    .feat-budget-3d {
      position: relative;
      display: flex; align-items: center; justify-content: center;
      height: 100%; min-height: 440px;
      background: linear-gradient(135deg, rgba(99,102,241,.06), rgba(34,211,238,.04));
      border-left: 1px solid rgba(255,255,255,.06);
    }
    .coin-halo {
      position:absolute; width:270px; height:270px; border-radius:50%;
      background:radial-gradient(circle, rgba(99,102,241,.18) 0%, rgba(34,211,238,.08) 45%, transparent 70%);
      animation:ringPulse 4s ease-in-out infinite;
    }
    .coin-halo-outer {
      position:absolute; width:350px; height:350px; border-radius:50%;
      border:1px solid rgba(129,140,248,.1);
      animation:ringPulse 4s ease-in-out 1s infinite;
    }
    #coin-canvas {
      position:relative; z-index:2; display:block;
      width:340px; height:340px;
      filter: drop-shadow(0 0 24px rgba(99,102,241,.5))
              drop-shadow(0 0 50px rgba(34,211,238,.18));
    }
    .coin-loading {
      position:absolute; z-index:3; display:flex; flex-direction:column; align-items:center; gap:12px;
      color:#6b6f8e; font-size:13px;
    }
    .coin-spinner {
      width:36px; height:36px; border-radius:50%;
      border:2px solid rgba(99,102,241,.2); border-top-color:#6366f1;
      animation:spin .8s linear infinite;
    }

    /* Card Frigo — modèle gauche + texte droite (layout miroir du budget) */
    .feat-fridge {
      display: grid;
      grid-template-columns: 440px 1fr;
      align-items: center;
      gap: 0; padding: 0;
      min-height: 440px; overflow: visible;
    }
    .feat-fridge-3d {
      position: relative;
      display: flex; align-items: center; justify-content: center;
      height: 100%; min-height: 440px;
      overflow: visible;
      background: linear-gradient(135deg, rgba(34,211,238,.05), rgba(52,211,153,.03));
      border-right: 1px solid rgba(255,255,255,.06);
    }
    .feat-fridge-text {
      padding: 52px;
    }
    .feat-fridge-text h3 { font-size:26px; font-weight:800; letter-spacing:-.03em; color:#f5f7ff; margin:0 0 14px; }
    .feat-fridge-text p  { font-size:15.5px; color:#8b8fb0; line-height:1.7; margin:0; max-width:420px; }
    #fridge-canvas {
      position: relative; z-index: 2; display: block;
      width: 410px; height: 370px;
      filter: drop-shadow(0 0 22px rgba(34,211,238,.45))
              drop-shadow(0 0 50px rgba(52,211,153,.15));
    }
    .fridge-loading {
      position:absolute; z-index:3; display:flex; flex-direction:column; align-items:center; gap:12px;
      color:#6b6f8e; font-size:13px;
    }

    /* Card Courses — texte gauche + caddie droite */
    .feat-cart {
      display: grid;
      grid-template-columns: 1fr 440px;
      align-items: center;
      gap: 0; padding: 0;
      min-height: 440px; overflow: visible;
    }
    .feat-cart-text {
      padding: 52px;
    }
    .feat-cart-text h3 { font-size:26px; font-weight:800; letter-spacing:-.03em; color:#f5f7ff; margin:0 0 14px; }
    .feat-cart-text p  { font-size:15.5px; color:#8b8fb0; line-height:1.7; margin:0; max-width:420px; }
    .feat-cart-3d {
      position: relative;
      display: flex; align-items: center; justify-content: center;
      height: 100%; min-height: 440px;
      overflow: visible;
      background: linear-gradient(135deg, rgba(99,102,241,.05), rgba(34,211,238,.03));
      border-left: 1px solid rgba(255,255,255,.06);
    }
    #cart-canvas {
      position: relative; z-index: 2; display: block;
      width: 410px; height: 370px;
      filter: drop-shadow(0 0 22px rgba(99,102,241,.4))
              drop-shadow(0 0 50px rgba(34,211,238,.15));
    }
    .cart-loading {
      position:absolute; z-index:3; display:flex; flex-direction:column; align-items:center; gap:12px;
      color:#6b6f8e; font-size:13px;
    }

    /* Card Coloc — maison gauche + texte droite */
    .feat-coloc {
      display: grid;
      grid-template-columns: 440px 1fr;
      align-items: center;
      gap: 0; padding: 0;
      min-height: 440px; overflow: visible;
    }
    .feat-coloc-3d {
      position: relative;
      display: flex; align-items: center; justify-content: center;
      height: 100%; min-height: 440px;
      overflow: visible;
      background: linear-gradient(135deg, rgba(52,211,153,.04), rgba(99,102,241,.04));
      border-right: 1px solid rgba(255,255,255,.06);
    }
    .feat-coloc-text {
      padding: 52px;
    }
    .feat-coloc-text h3 { font-size:26px; font-weight:800; letter-spacing:-.03em; color:#f5f7ff; margin:0 0 14px; }
    .feat-coloc-text p  { font-size:15.5px; color:#8b8fb0; line-height:1.7; margin:0; max-width:420px; }
    #coloc-canvas {
      position: relative; z-index: 2; display: block;
      width: 410px; height: 370px;
      filter: drop-shadow(0 0 22px rgba(52,211,153,.35))
              drop-shadow(0 0 50px rgba(99,102,241,.15));
    }
    .coloc-loading {
      position:absolute; z-index:3; display:flex; flex-direction:column; align-items:center; gap:12px;
      color:#6b6f8e; font-size:13px;
    }

    /* Cards normales */
    .feat-row {
      display: grid;
      grid-template-columns: 100px 1fr auto;
      align-items: center;
      gap: 32px;
      padding: 40px 44px;
    }
    .feat-row-icon {
      width:80px; height:80px; border-radius:24px; flex-shrink:0;
      display:grid; place-items:center; font-size:36px;
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(255,255,255,.08);
      box-shadow: inset 0 1px 1px rgba(255,255,255,.08);
    }
    .feat-row-body h3 { font-size:22px; font-weight:700; letter-spacing:-.02em; color:#f5f7ff; margin:0 0 10px; }
    .feat-row-body p  { font-size:15px; color:#8b8fb0; line-height:1.65; margin:0; }
    .feat-tag {
      display:inline-block; white-space:nowrap; padding:5px 14px; border-radius:99px;
      font-size:11.5px; font-weight:700;
      background:rgba(99,102,241,.12); border:1px solid rgba(129,140,248,.2); color:#a5b4fc;
      align-self:center;
    }
    .feat-budget-text .feat-tag { display:inline-block; margin-top:24px; }
    .feat-budget-text h3 { font-size:26px; font-weight:800; letter-spacing:-.03em; color:#f5f7ff; margin:0 0 14px; }
    .feat-budget-text p  { font-size:15.5px; color:#8b8fb0; line-height:1.7; margin:0; max-width:420px; }

    /* ── CONTAINER SCROLL — How it works ─────────────── */
    .cs-outer {
      padding: 40px 40px 140px;
      max-width: 1000px;
      margin: 0 auto;
      position: relative;
    }
    .cs-header {
      text-align: center;
      margin-bottom: 10px;
      will-change: transform;
    }
    .cs-perspective { perspective: 1000px; }
    .cs-card-tilt {
      will-change: transform;
      transform-origin: 50% 0;
      transform: rotateX(35deg) scale(1.15);
      padding: 52px 48px;
      box-shadow:
        0 0 #0000004d, 0 9px 20px #0000004a,
        0 37px 37px #00000042, 0 84px 50px #00000026;
    }
    .cs-steps-grid {
      display: grid;
      grid-template-columns: 1fr 1px 1fr 1px 1fr;
      align-items: start;
      gap: 0;
    }
    .cs-step-col { padding: 0 36px; }
    .cs-step-col:first-child { padding-left: 0; }
    .cs-step-col:last-child  { padding-right: 0; }
    .cs-col-div {
      background: linear-gradient(to bottom, transparent, rgba(255,255,255,.1), transparent);
      align-self: stretch;
    }
    .cs-num {
      font-size: 68px; font-weight: 900; letter-spacing: -0.06em; line-height: 1;
      background: linear-gradient(120deg, #22d3ee, #6366f1);
      -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
      margin-bottom: 20px;
    }
    .cs-step-col h3 { font-size: 18px; font-weight: 700; color: #f5f7ff; margin: 0 0 10px; }
    .cs-step-col p  { font-size: 14.5px; color: #8b8fb0; line-height: 1.65; margin: 0; }

    /* ── STATS ────────────────────────────────────────── */
    .lp-stats-wrap { display:grid; grid-template-columns:repeat(3,1fr); gap:2px; }
    .stat-block {
      padding:52px 40px; text-align:center;
      border:1px solid rgba(255,255,255,.06);
      background:rgba(20,22,38,.35); backdrop-filter:blur(20px);
    }
    .stat-block:first-child { border-radius:28px 0 0 28px; }
    .stat-block:last-child  { border-radius:0 28px 28px 0; }
    .stat-num {
      display:inline;
      font-size:72px; font-weight:900; letter-spacing:-.05em; line-height:1;
      background:linear-gradient(120deg,#22d3ee,#818cf8);
      -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .stat-unit { font-size:36px; font-weight:800; color:#818cf8; margin-left:4px; }
    .stat-lbl  { display:block; margin-top:12px; font-size:14px; color:#8b8fb0; }

    /* ── CTA FINAL ────────────────────────────────────── */
    .lp-cta { min-height:85vh; display:flex; align-items:center; justify-content:center; text-align:center; padding:100px 24px; position:relative; }
    .cta-glow { position:absolute; width:600px; height:600px; border-radius:50%; background:radial-gradient(#6366f1,transparent 70%); filter:blur(100px); opacity:.18; pointer-events:none; top:50%; left:50%; transform:translate(-50%,-50%); }
    .cta-inner { max-width:680px; position:relative; z-index:2; }
    .cta-title { font-size:clamp(36px,6vw,72px); font-weight:900; letter-spacing:-.04em; color:#f5f7ff; margin:0 0 20px; line-height:1.05; }
    .cta-sub   { font-size:18px; color:#8b8fb0; margin:0 0 48px; line-height:1.6; }
    .cta-btn {
      display:inline-flex; align-items:center; gap:12px;
      padding:22px 56px; border-radius:99px; font-size:18px; font-weight:800;
      background:linear-gradient(135deg,#6366f1,#22d3ee); color:#fff; text-decoration:none;
      box-shadow:inset 0 1px 1px rgba(255,255,255,.3), 0 24px 60px -15px rgba(99,102,241,.6);
      transition:transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s;
      animation:glowPulse 3s ease-in-out infinite; position:relative; overflow:hidden;
    }
    .cta-btn::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,.15),transparent 60%); pointer-events:none; }
    .cta-btn:hover   { transform:translateY(-4px) scale(1.03); box-shadow:0 32px 80px -15px rgba(99,102,241,.75); }
    .cta-btn svg     { transition:transform .3s; }
    .cta-btn:hover svg { transform:translateX(5px); }
    .cta-login   { margin-top:20px; font-size:13.5px; color:#6b6f8e; }
    .cta-login a { color:#818cf8; text-decoration:none; }
    .cta-login a:hover { text-decoration:underline; }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width:900px) {
      .feat-budget { grid-template-columns:1fr; }
      .feat-budget-3d { min-height:320px; border-left:none; border-top:1px solid rgba(255,255,255,.06); }
      #coin-canvas { width:260px; height:260px; }
      .coin-halo { width:200px; height:200px; }
      .coin-halo-outer { width:280px; height:280px; }
      .feat-row { grid-template-columns:72px 1fr; }
      .feat-tag  { display:none; }
      .lp-stats-wrap { grid-template-columns:1fr; gap:10px; }
      .stat-block:first-child,.stat-block:last-child { border-radius:18px; }
    }
  </style>
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div id="lp">

  <!-- ══════════════ HERO centré ══════════════ -->
  <section class="lp-hero">

    <div class="hero-logo">
      <div class="hero-logo-mark">SL</div>
      <span>StudentLife<em style="font-style:normal;background:linear-gradient(120deg,#22d3ee,#818cf8);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;">·</em>Hub</span>
    </div>

    <div class="hero-badge"><span class="hero-dot"></span> Conçu par les étudiants, pour les étudiants</div>

    <h1 class="hero-title">
      Ta vie étudiante,
      <span class="hero-grad">enfin maîtrisée.</span>
    </h1>

    <p class="hero-sub">
      Finances, frigo, courses — tout centralisé, tout clair.
      Reprends le contrôle sans te prendre la tête.
    </p>

    <div class="hero-actions">
      <a href="#features" class="btn-primary">
        Voir les fonctionnalités
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
      </a>
      <a href="<?= BASE_URL ?>/auth/login" class="btn-ghost">Déjà inscrit →</a>
    </div>

    <div class="hero-scroll"><span>Défiler</span><div class="scroll-chev"></div></div>
  </section>

  <!-- ══════════════ FEATURES — colonne ══════════════ -->
  <div class="lp-sep"></div>

  <section class="lp-section" id="features">
    <div data-sr>
      <div class="section-label">Fonctionnalités</div>
      <h2 class="section-title">Tout ce qu'il te faut,<br>au même endroit.</h2>
      <p class="section-sub">Quatre modules pensés pour la réalité de la vie étudiante.</p>
    </div>

    <div class="feature-stack">

      <!-- CARD 1 : Budget + pièce 3D -->
      <div class="feat-budget glass" data-sr>
        <div class="feat-budget-text">
          <div style="width:52px;height:52px;border-radius:16px;display:grid;place-items:center;font-size:24px;margin-bottom:22px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);">💰</div>
          <h3>Optimisation budgétaire</h3>
          <p>Enregistre tes revenus et dépenses, visualise par catégorie avec des graphiques, et garde un solde en temps réel. Plus d'excuse pour finir dans le rouge à la fin du mois !</p>
          <span class="feat-tag">Revenus · Dépenses · Solde</span>
        </div>
        <div class="feat-budget-3d">
          <div class="coin-halo-outer"></div>
          <div class="coin-halo"></div>
          <div class="coin-loading" id="coin-loading">
            <div class="coin-spinner"></div>
            <span>Chargement…</span>
          </div>
          <canvas id="coin-canvas"></canvas>
        </div>
      </div>

      <!-- CARD 2 : Frigo — modèle 3D gauche, texte droite -->
      <div class="feat-fridge glass" data-sr data-d="1">
        <div class="feat-fridge-3d">
          <div class="fridge-loading" id="fridge-loading">
            <div class="coin-spinner"></div>
            <span>Chargement…</span>
          </div>
          <canvas id="fridge-canvas"></canvas>
        </div>
        <div class="feat-fridge-text">
          <div style="width:52px;height:52px;border-radius:16px;display:grid;place-items:center;font-size:24px;margin-bottom:22px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);">🧊</div>
          <h3>Inventaire frigo</h3>
          <p>Ajoute tes aliments, suis les dates de péremption et reçois des alertes avant qu'il soit trop tard. Fini le gaspillage, fini les surprises désagréables.</p>
          <span class="feat-tag" style="margin-top:24px;">Alertes · Péremption · Stock</span>
        </div>
      </div>

      <!-- CARD 3 : Courses — caddie 3D droite, texte gauche -->
      <div class="feat-cart glass" data-sr data-d="2">
        <div class="feat-cart-text">
          <div style="width:52px;height:52px;border-radius:16px;display:grid;place-items:center;font-size:24px;margin-bottom:22px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);">🛒</div>
          <h3>Liste de courses</h3>
          <p>Crée et gère ta liste, coche au fur et à mesure. Simple, rapide, efficace. La corvée du supermarché, enfin sous contrôle.</p>
          <span class="feat-tag" style="margin-top:24px;">Checklist · Priorités</span>
        </div>
        <div class="feat-cart-3d">
          <div class="cart-loading" id="cart-loading">
            <div class="coin-spinner"></div>
            <span>Chargement…</span>
          </div>
          <canvas id="cart-canvas"></canvas>
        </div>
      </div>

      <!-- CARD 4 : Coloc — maison 3D gauche, texte droite -->
      <div class="feat-coloc glass" data-sr data-d="3">
        <div class="feat-coloc-3d">
          <div class="coloc-loading" id="coloc-loading">
            <div class="coin-spinner"></div>
            <span>Chargement…</span>
          </div>
          <canvas id="coloc-canvas"></canvas>
        </div>
        <div class="feat-coloc-text">
          <div style="width:52px;height:52px;border-radius:16px;display:grid;place-items:center;font-size:24px;margin-bottom:22px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);">👥</div>
          <h3>Vie en coloc</h3>
          <p>Partagez dépenses et frigo entre colocataires. Synchronisez vos listes. La cohabitation sans les frictions.</p>
          <span class="feat-tag" style="margin-top:24px;">Partage · Coloc · Sync</span>
        </div>
      </div>

    </div>
  </section>

  <!-- ══════════════ HOW IT WORKS — container scroll ══════════════ -->
  <div class="lp-sep"></div>
  <div class="cs-outer">
    <div class="cs-header" id="cs-header">
      <div class="section-label" style="justify-content:center;">Comment ça marche</div>
      <h2 class="section-title" style="text-align:center;">Opérationnel en 3 minutes.</h2>
    </div>
    <div class="cs-perspective">
      <div class="cs-card-tilt glass" id="cs-card">
        <div class="cs-steps-grid">

          <div class="cs-step-col">
            <div class="cs-num">01</div>
            <h3>Crée ton compte</h3>
            <p>Inscription en 30 secondes — juste un email et un mot de passe. Aucune carte, aucun engagement.</p>
          </div>

          <div class="cs-col-div"></div>

          <div class="cs-step-col">
            <div class="cs-num">02</div>
            <h3>Configure ton espace</h3>
            <p>Rentre tes revenus, ajoute tes premières dépenses et tes articles en stock. L'interface est intuitive.</p>
          </div>

          <div class="cs-col-div"></div>

          <div class="cs-step-col">
            <div class="cs-num">03</div>
            <h3>Reprends le contrôle</h3>
            <p>Visualise ton budget, reçois tes alertes frigo, coche ta liste de courses. Ta vie, optimisée.</p>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- ══════════════ STATS ══════════════ -->
  <div class="lp-sep"></div>
  <section class="lp-section" style="padding-bottom:0;">
    <div data-sr style="text-align:center;margin-bottom:52px;">
      <div class="section-label" style="justify-content:center;">En chiffres</div>
      <h2 class="section-title" style="text-align:center;">La simplicité, c'est nos chiffres.</h2>
    </div>
    <div class="lp-stats-wrap" data-sr>
      <div class="stat-block">
        <span class="stat-num" data-count="3">0</span><span class="stat-unit"> min</span>
        <span class="stat-lbl">Pour tout configurer</span>
      </div>
      <div class="stat-block">
        <span class="stat-num" data-count="4">0</span><span class="stat-unit"> modules</span>
        <span class="stat-lbl">Budget · Frigo · Courses · Coloc</span>
      </div>
      <div class="stat-block">
        <span class="stat-num" data-count="0">0</span><span class="stat-unit"> €</span>
        <span class="stat-lbl">Coût total, pour toujours</span>
      </div>
    </div>
  </section>

  <!-- ══════════════ CTA FINAL ══════════════ -->
  <div class="lp-sep" style="height:160px;"></div>
  <section class="lp-cta">
    <div class="cta-glow"></div>
    <div class="cta-inner" data-sr>
      <h2 class="cta-title">Prêt à reprendre<br>le contrôle ?</h2>
      <p class="cta-sub">Rejoins StudentLife Hub et arrête de subir ta vie étudiante.<br>C'est gratuit, c'est maintenant.</p>
      <a href="<?= BASE_URL ?>/auth/register" class="cta-btn">
        Commencer gratuitement
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <p class="cta-login">Déjà un compte ? <a href="<?= BASE_URL ?>/auth/login">Se connecter</a></p>
    </div>
  </section>

</div>

<!-- Scroll reveal + Counters -->
<script>
const srObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); srObs.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('[data-sr]').forEach(el => srObs.observe(el));

function runCounter(el) {
  const t = parseInt(el.dataset.count, 10);
  if (isNaN(t) || t === 0) return;
  const d = 1400, s = performance.now();
  const tick = n => { const p = Math.min((n-s)/d,1), e = 1-Math.pow(1-p,3); el.textContent = Math.round(e*t); if(p<1) requestAnimationFrame(tick); };
  requestAnimationFrame(tick);
}
const cntObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { runCounter(e.target); cntObs.unobserve(e.target); } });
}, { threshold: 0.5 });
document.querySelectorAll('[data-count]').forEach(el => cntObs.observe(el));

document.querySelector('a[href="#features"]')?.addEventListener('click', e => {
  e.preventDefault();
  document.getElementById('features')?.scrollIntoView({ behavior: 'smooth' });
});

// ── Container scroll — tilt 3D ─────────────────────────
(function() {
  const outer  = document.querySelector('.cs-outer');
  const header = document.getElementById('cs-header');
  const card   = document.getElementById('cs-card');
  if (!outer || !card) return;

  function updateCS() {
    const rect     = outer.getBoundingClientRect();
    const progress = Math.max(0, Math.min(1, (window.innerHeight - rect.top) / rect.height));
    const rotateX  = 35 * (1 - progress);
    const scale    = 1.15 - 0.15 * progress;
    const transY   = -130 * progress;
    if (header) header.style.transform = `translateY(${transY}px)`;
    card.style.transform = `rotateX(${rotateX}deg) scale(${scale})`;
  }

  window.addEventListener('scroll', updateCS, { passive: true });
  updateCS();
})();
</script>

<!-- Three.js — pièce 3D dans la card Budget -->
<script type="module">
import * as THREE  from 'three';
import { GLTFLoader }  from 'three/addons/loaders/GLTFLoader.js';
import { DRACOLoader } from 'three/addons/loaders/DRACOLoader.js';

const canvas  = document.getElementById('coin-canvas');
const loading = document.getElementById('coin-loading');

const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
renderer.setSize(canvas.offsetWidth, canvas.offsetHeight);
renderer.outputColorSpace    = THREE.SRGBColorSpace;
renderer.toneMapping         = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.5;

const scene  = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(38, 1, 0.1, 100);
camera.position.set(0, 0.5, 6.5);
camera.lookAt(0, 0, 0);

// Lumières
scene.add(new THREE.AmbientLight(0xffffff, 0.4));
const key  = new THREE.DirectionalLight(0xffd580, 5);   key.position.set(3, 4, 3);  scene.add(key);
const fill = new THREE.DirectionalLight(0x22d3ee, 2.5); fill.position.set(-4, 1, 2); scene.add(fill);
const rim  = new THREE.DirectionalLight(0x818cf8, 2);   rim.position.set(1, -3, -2); scene.add(rim);
const pt   = new THREE.PointLight(0x22d3ee, 1.5, 10);   pt.position.set(0, -2, 1);   scene.add(pt);

// Chargement GLB
const draco = new DRACOLoader();
draco.setDecoderPath('https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/libs/draco/');
const loader = new GLTFLoader();
loader.setDRACOLoader(draco);

let coin = null;

loader.load('<?= BASE_URL ?>/models/1_euro_coin.glb', gltf => {
  loading.style.display = 'none';
  coin = gltf.scene;

  const box    = new THREE.Box3().setFromObject(coin);
  const size   = box.getSize(new THREE.Vector3());
  const center = box.getCenter(new THREE.Vector3());
  const scale  = 3 / Math.max(size.x, size.y, size.z);
  coin.scale.setScalar(scale);
  coin.position.sub(center.multiplyScalar(scale));
  // Correction manuelle : le modèle reste légèrement haut après centrage
  coin.position.y -= 0.08;
  coin.rotation.x = 0.1;

  scene.add(coin);
});

// Suivi du scroll
let scrollY = 0;
window.addEventListener('scroll', () => { scrollY = window.scrollY; }, { passive: true });

// Boucle
const clock = new THREE.Clock();
let autoY   = 0;

(function animate() {
  requestAnimationFrame(animate);
  const dt = clock.getDelta();
  if (coin) {
    autoY          += dt * 0.4;
    coin.rotation.y = autoY + scrollY * 0.004;
    // Inclinaison X légère et stable, sans oscillation verticale excessive
    coin.rotation.x = 0.1 + Math.sin(scrollY * 0.0015) * 0.2;
    coin.rotation.z = Math.sin(clock.getElapsedTime() * 0.5) * 0.03;
  }
  renderer.render(scene, camera);
})();

window.addEventListener('resize', () => {
  renderer.setSize(canvas.offsetWidth, canvas.offsetHeight);
});

// ── FRIGO 3D (statique, vue 3/4) ─────────────────────
const fridgeCanvas = document.getElementById('fridge-canvas');
const fridgeLoad   = document.getElementById('fridge-loading');

const fridgeRend = new THREE.WebGLRenderer({ canvas: fridgeCanvas, antialias: true, alpha: true });
fridgeRend.setPixelRatio(Math.min(window.devicePixelRatio, 2));
fridgeRend.setSize(fridgeCanvas.offsetWidth, fridgeCanvas.offsetHeight);
fridgeRend.outputColorSpace    = THREE.SRGBColorSpace;
fridgeRend.toneMapping         = THREE.ACESFilmicToneMapping;
fridgeRend.toneMappingExposure = 1.3;

const fridgeScene = new THREE.Scene();
const fridgeCam   = new THREE.PerspectiveCamera(
  38,
  fridgeCanvas.offsetWidth / fridgeCanvas.offsetHeight,
  0.1, 100
);
fridgeCam.position.set(0, 0.3, 5.5);
fridgeCam.lookAt(0, 0, 0);

// Lumières — tons froids pour l'ambiance frigo
fridgeScene.add(new THREE.AmbientLight(0xffffff, 0.6));
const fKey  = new THREE.DirectionalLight(0xd0f4ff, 3.5); fKey.position.set(3, 4, 3);   fridgeScene.add(fKey);
const fFill = new THREE.DirectionalLight(0x818cf8, 1.5); fFill.position.set(-3, 1, 2); fridgeScene.add(fFill);
const fRim  = new THREE.DirectionalLight(0xffffff, 1.2); fRim.position.set(-2, -2, -2); fridgeScene.add(fRim);
const fPt   = new THREE.PointLight(0x22d3ee, 1, 12);     fPt.position.set(0, 2, 2);    fridgeScene.add(fPt);

const fridgeLoader = new GLTFLoader();
fridgeLoader.setDRACOLoader(draco);

let fridgeMixer    = null;
let fridgeActions  = [];     // références aux actions pour scrubbing
let fridgeAnimDur  = 0;
let fridgeDoorMesh = null;

function loadFridge() {
  fridgeLoader.load('<?= BASE_URL ?>/models/refrigerator.glb', gltf => {
    fridgeLoad.style.display = 'none';
    const fridge = gltf.scene;

    // Centrage et mise à l'échelle
    const fBox    = new THREE.Box3().setFromObject(fridge);
    const fSize   = fBox.getSize(new THREE.Vector3());
    const fCenter = fBox.getCenter(new THREE.Vector3());
    const fScale  = 3.0 / Math.max(fSize.x, fSize.y, fSize.z);
    fridge.scale.setScalar(fScale);
    fridge.position.sub(fCenter.multiplyScalar(fScale));

    // Vue 3/4 face
    fridge.rotation.y = 37 * Math.PI / 180;
    fridge.rotation.x = 0.04;
    fridge.position.x -= 1.0;

    // ── Cas 1 : le GLB contient des animations ──────────────
    if (gltf.animations && gltf.animations.length > 0) {
      fridgeMixer = new THREE.AnimationMixer(fridge);
      gltf.animations.forEach(clip => {
        const action = fridgeMixer.clipAction(clip);
        action.play();
        action.paused           = true;  // on scrube via scroll, pas auto-play
        action.clampWhenFinished = true;
        fridgeActions.push(action);
        fridgeAnimDur = Math.max(fridgeAnimDur, clip.duration);
      });

    // ── Cas 2 : pas d'anim → on cherche un mesh "porte" par son nom ──
    } else {
      fridge.traverse(child => {
        if (!fridgeDoorMesh && child.isMesh) {
          const n = child.name.toLowerCase();
          if (n.includes('door') || n.includes('porte') || n.includes('hinge') || n.includes('porta')) {
            fridgeDoorMesh = child;
          }
        }
      });
    }

    fridgeScene.add(fridge);
    fridgeRend.render(fridgeScene, fridgeCam);

    // Log pour debug (ouvre la console pour voir les noms des meshes)
    console.log('Frigo chargé — animations GLB :', gltf.animations.length);
    fridge.traverse(c => { if (c.isMesh) console.log(' mesh:', c.name); });

  }, undefined, err => {
    console.error('Erreur chargement frigo:', err);
    fridgeLoad.innerHTML = '<span style="color:#ef4444;font-size:12px;">Modèle non disponible</span>';
  });
}

// ── Scrubbing scroll → ouverture porte ───────────────
function onFridgeScroll() {
  const card = fridgeCanvas.closest('.feat-fridge');
  if (!card) return;

  const rect = card.getBoundingClientRect();
  const vh   = window.innerHeight;

  // progress : 0 = card en bas de l'écran  → 1 = card centrée
  const progress = Math.max(0, Math.min(1, (vh - rect.top) / vh));

  if (fridgeMixer && fridgeAnimDur > 0) {
    // Scrubbing : on fixe le temps sur chaque action puis mixer.update(0)
    // (setTime() désactive les actions — à ne pas utiliser ici)
    const t = (1 - progress) * fridgeAnimDur;
    fridgeActions.forEach(a => { a.time = t; });
    fridgeMixer.update(0);
  } else if (fridgeDoorMesh) {
    // Fallback : rotation manuelle de la porte (max 90°)
    fridgeDoorMesh.rotation.y = progress * (Math.PI / 2);
  }

  fridgeRend.render(fridgeScene, fridgeCam);
}

window.addEventListener('scroll', onFridgeScroll, { passive: true });

// Chargement déclenché quand la card entre dans le viewport
const fridgeObserver = new IntersectionObserver(entries => {
  if (entries[0].isIntersecting) {
    loadFridge();
    fridgeObserver.disconnect();
  }
}, { threshold: 0.1 });
fridgeObserver.observe(fridgeCanvas);

window.addEventListener('resize', () => {
  const w = fridgeCanvas.offsetWidth, h = fridgeCanvas.offsetHeight;
  fridgeCam.aspect = w / h;
  fridgeCam.updateProjectionMatrix();
  fridgeRend.setSize(w, h);
  fridgeRend.render(fridgeScene, fridgeCam);
});

// ── CADDIE 3D ─────────────────────────────────────────
const cartCanvas = document.getElementById('cart-canvas');
const cartLoad   = document.getElementById('cart-loading');

const cartRend = new THREE.WebGLRenderer({ canvas: cartCanvas, antialias: true, alpha: true });
cartRend.setPixelRatio(Math.min(window.devicePixelRatio, 2));
cartRend.setSize(cartCanvas.offsetWidth, cartCanvas.offsetHeight);
cartRend.outputColorSpace    = THREE.SRGBColorSpace;
cartRend.toneMapping         = THREE.ACESFilmicToneMapping;
cartRend.toneMappingExposure = 1.4;

const cartScene = new THREE.Scene();
const cartCam   = new THREE.PerspectiveCamera(
  38,
  cartCanvas.offsetWidth / cartCanvas.offsetHeight,
  0.1, 100
);
cartCam.position.set(0, 0.2, 5.5);
cartCam.lookAt(0, 0, 0);

// Lumières caddie — tons chauds / indigo
cartScene.add(new THREE.AmbientLight(0xffffff, 0.5));
const cKey  = new THREE.DirectionalLight(0xffffff, 3.5); cKey.position.set(3, 4, 3);   cartScene.add(cKey);
const cFill = new THREE.DirectionalLight(0x818cf8, 2);   cFill.position.set(-3, 1, 2); cartScene.add(cFill);
const cRim  = new THREE.DirectionalLight(0x22d3ee, 1.5); cRim.position.set(-1, -3, -2); cartScene.add(cRim);
const cPt   = new THREE.PointLight(0x6366f1, 1.2, 12);   cPt.position.set(0, 2, 2);    cartScene.add(cPt);

const cartLoader = new GLTFLoader();
cartLoader.setDRACOLoader(draco);

let cartMixer    = null;
let cartActions  = [];
let cartAnimDur  = 0;
let cartMesh     = null;

function loadCart() {
  cartLoader.load('<?= BASE_URL ?>/models/shopping_cart.glb', gltf => {
    cartLoad.style.display = 'none';
    const cart = gltf.scene;

    const cBox    = new THREE.Box3().setFromObject(cart);
    const cSize   = cBox.getSize(new THREE.Vector3());
    const cCenter = cBox.getCenter(new THREE.Vector3());
    const cScale  = 2.8 / Math.max(cSize.x, cSize.y, cSize.z);
    cart.scale.setScalar(cScale);
    cart.position.sub(cCenter.multiplyScalar(cScale));

    // Vue 3/4 face
    cart.rotation.y = -37 * Math.PI / 180;
    cart.rotation.x = 0.04;

    // Animations built-in si disponibles
    if (gltf.animations && gltf.animations.length > 0) {
      cartMixer = new THREE.AnimationMixer(cart);
      gltf.animations.forEach(clip => {
        const action = cartMixer.clipAction(clip);
        action.play();
        action.paused = true;
        action.clampWhenFinished = true;
        cartActions.push(action);
        cartAnimDur = Math.max(cartAnimDur, clip.duration);
      });
    }

    cartScene.add(cart);
    cartMesh = cart;

    // Rotation auto douce si pas d'animation
    if (!cartMixer) startCartLoop();
    else cartRend.render(cartScene, cartCam);

  }, undefined, err => {
    console.error('Erreur chargement caddie:', err);
    cartLoad.innerHTML = '<span style="color:#ef4444;font-size:12px;">Modèle non disponible</span>';
  });
}

function startCartLoop() {
  // Pas de boucle auto — la rotation est pilotée par le scroll
  cartRend.render(cartScene, cartCam);
}

// Scroll → scrub animation ou simple re-render
function onCartScroll() {
  if (!cartMesh) return;
  // Rotation latérale Y basée sur le scroll global
  cartMesh.rotation.y = -37 * Math.PI / 180 + window.scrollY * 0.003;
  cartRend.render(cartScene, cartCam);
}
window.addEventListener('scroll', onCartScroll, { passive: true });

// Chargement au scroll
const cartObserver = new IntersectionObserver(entries => {
  if (entries[0].isIntersecting) {
    loadCart();
    cartObserver.disconnect();
  }
}, { threshold: 0.1 });
cartObserver.observe(cartCanvas);

window.addEventListener('resize', () => {
  const w = cartCanvas.offsetWidth, h = cartCanvas.offsetHeight;
  cartCam.aspect = w / h;
  cartCam.updateProjectionMatrix();
  cartRend.setSize(w, h);
  cartRend.render(cartScene, cartCam);
});

// ── MAISON 3D (Coloc) ────────────────────────────────
const colocCanvas = document.getElementById('coloc-canvas');
const colocLoad   = document.getElementById('coloc-loading');

const colocRend = new THREE.WebGLRenderer({ canvas: colocCanvas, antialias: true, alpha: true });
colocRend.setPixelRatio(Math.min(window.devicePixelRatio, 2));
colocRend.setSize(colocCanvas.offsetWidth, colocCanvas.offsetHeight);
colocRend.outputColorSpace    = THREE.SRGBColorSpace;
colocRend.toneMapping         = THREE.ACESFilmicToneMapping;
colocRend.toneMappingExposure = 1.3;

const colocScene = new THREE.Scene();
const colocCam   = new THREE.PerspectiveCamera(
  38,
  colocCanvas.offsetWidth / colocCanvas.offsetHeight,
  0.1, 100
);
colocCam.position.set(0, 0.8, 5.5);
colocCam.lookAt(0, 0, 0);

// Lumières maison — tons chauds / verts émeral
colocScene.add(new THREE.AmbientLight(0xffffff, 0.6));
const hKey  = new THREE.DirectionalLight(0xfff4d0, 4);   hKey.position.set(4, 5, 3);   colocScene.add(hKey);
const hFill = new THREE.DirectionalLight(0x34d399, 1.5); hFill.position.set(-3, 2, 2); colocScene.add(hFill);
const hRim  = new THREE.DirectionalLight(0x818cf8, 1.2); hRim.position.set(-2, -2, -2); colocScene.add(hRim);
const hPt   = new THREE.PointLight(0xffd580, 1.5, 12);   hPt.position.set(0, 3, 2);    colocScene.add(hPt);

const colocLoader = new GLTFLoader();
colocLoader.setDRACOLoader(draco);
let colocMesh = null;

function loadColoc() {
  colocLoader.load('<?= BASE_URL ?>/models/modern_stylized_bedroom.glb', gltf => {
    colocLoad.style.display = 'none';
    const house = gltf.scene;

    const hBox    = new THREE.Box3().setFromObject(house);
    const hSize   = hBox.getSize(new THREE.Vector3());
    const hCenter = hBox.getCenter(new THREE.Vector3());
    const hScale  = 2.6 / Math.max(hSize.x, hSize.y, hSize.z);
    house.scale.setScalar(hScale);
    house.position.sub(hCenter.multiplyScalar(hScale));

    // Vue isométrique légèrement de dessus
    house.rotation.y = 37 * Math.PI / 180;
    house.rotation.x = 0.15;
    house.position.x += 0.3;

    colocScene.add(house);
    colocMesh = house;
    colocRend.render(colocScene, colocCam);

  }, undefined, err => {
    console.error('Erreur chargement maison:', err);
    colocLoad.innerHTML = '<span style="color:#ef4444;font-size:12px;">Modèle non disponible</span>';
  });
}

// Rotation latérale au scroll
function onColocScroll() {
  if (!colocMesh) return;
  const card = colocCanvas.closest('.feat-coloc');
  if (!card) return;
  const rect     = card.getBoundingClientRect();
  const progress = Math.max(0, Math.min(1, (window.innerHeight - rect.top) / window.innerHeight));
  // progress=0 (entre dans le viewport) → +45° à droite
  // progress=1 (scrollé) → position de base 37°
  colocMesh.rotation.y = (37 + 45 * (1 - progress)) * Math.PI / 180;
  colocRend.render(colocScene, colocCam);
}
window.addEventListener('scroll', onColocScroll, { passive: true });

// Chargement au scroll
loadColoc();

window.addEventListener('resize', () => {
  const w = colocCanvas.offsetWidth, h = colocCanvas.offsetHeight;
  colocCam.aspect = w / h;
  colocCam.updateProjectionMatrix();
  colocRend.setSize(w, h);
  colocRend.render(colocScene, colocCam);
});
</script>

</body>
</html>
