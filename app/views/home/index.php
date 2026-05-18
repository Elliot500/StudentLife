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

    /* ── Page Loader ─────────────────────────────────── */
    #page-loader {
      position: fixed; inset: 0; z-index: 99999;
      background: #0a0a18;
      display: flex;
      align-items: center; justify-content: center;
      transition: opacity .8s ease, visibility .8s ease;
    }
    #page-loader.out { opacity: 0; visibility: hidden; pointer-events: none; }

    /* ── 4-cube assembly loader ──────────────────────── */
    .loader {
      position: relative;
      width: 64px; height: 64px;
    }

    /* Chaque .lc = wrapper de translation 2D */
    .lc {
      position: absolute;
      width: 32px; height: 32px;
    }
    /* Positions finales (cube 2x2 assemblé) */
    .lc0 { top:  0;   left:  0;   animation: lc0-anim 4s ease-in-out infinite; }
    .lc1 { top:  0;   left: 32px; animation: lc1-anim 4s ease-in-out infinite; }
    .lc2 { top: 32px; left:  0;   animation: lc2-anim 4s ease-in-out infinite; }
    .lc3 { top: 32px; left: 32px; animation: lc3-anim 4s ease-in-out infinite; }

    /* .lc div = le cube 3D */
    .lc div {
      position: relative;
      width: 32px; height: 32px;
      background: #6366f1;
      transform-style: preserve-3d;
      animation: lc-rotate 4s ease-in-out infinite;
    }
    /* Face du dessus */
    .lc div::before {
      content: '';
      position: absolute;
      width: 32px; height: 32px;
      top: 0; left: 0;
      background: #818cf8;
      transform-origin: 50% 0;
      transform: rotateX(-90deg);
    }
    /* Face latérale droite */
    .lc div::after {
      content: '';
      position: absolute;
      width: 32px; height: 32px;
      top: 0; left: 32px;
      background: #312e81;
      transform-origin: 0 50%;
      transform: rotateY(90deg);
    }

    /* Ombre au sol */
    .loader-shadow {
      position: absolute;
      width: 100px; height: 24px;
      bottom: -20px; left: 50%;
      transform: translateX(-50%);
      background: radial-gradient(ellipse, rgba(99,102,241,.55), transparent 70%);
      border-radius: 50%;
      animation: shadow-anim 4s ease-in-out infinite;
    }

    /* ── Keyframes ────────────────────────────────────── */

    /* Rotation isométrique constante pour tous les cubes */
    @keyframes lc-rotate {
      0%, 100% { transform: rotateY(-47deg) rotateX(-15deg) rotateZ(15deg); }
    }

    /* lc0 (haut-gauche) : arrive depuis haut-gauche */
    @keyframes lc0-anim {
      0%, 5%    { transform: translate(-90px, -60px); opacity: 0; }
      8%        { opacity: 1; }
      35%       { transform: translate(0, 0); }
      58%       { transform: translate(0, 0); }
      78%       { transform: translate(0, 130px); opacity: 0; }
      100%      { transform: translate(0, 130px); opacity: 0; }
    }
    /* lc1 (haut-droite) : arrive depuis haut-droite */
    @keyframes lc1-anim {
      0%, 10%   { transform: translate(90px, -60px); opacity: 0; }
      13%       { opacity: 1; }
      38%       { transform: translate(0, 0); }
      58%       { transform: translate(0, 0); }
      80%       { transform: translate(0, 130px); opacity: 0; }
      100%      { transform: translate(0, 130px); opacity: 0; }
    }
    /* lc2 (bas-gauche) : arrive depuis bas-gauche */
    @keyframes lc2-anim {
      0%, 15%   { transform: translate(-90px, 60px); opacity: 0; }
      18%       { opacity: 1; }
      40%       { transform: translate(0, 0); }
      58%       { transform: translate(0, 0); }
      76%       { transform: translate(0, 130px); opacity: 0; }
      100%      { transform: translate(0, 130px); opacity: 0; }
    }
    /* lc3 (bas-droite) : arrive en dernier, puis sort par le bas */
    @keyframes lc3-anim {
      0%, 20%   { transform: translate(90px, 60px); opacity: 0; }
      23%       { opacity: 1; }
      43%       { transform: translate(0, 0); }    /* arrive */
      56%       { transform: translate(0, 0); }    /* pause */
      66%       { transform: translate(0, 70px); opacity: 1; } /* glisse vers le bas */
      71%       { transform: translate(0, 70px); opacity: 0; } /* disparait */
      100%      { transform: translate(0, 70px); opacity: 0; }
    }

    /* Ombre qui apparait quand le cube est assemblé */
    @keyframes shadow-anim {
      0%, 40%   { opacity: 0; transform: translateX(-50%) scaleX(0); }
      50%, 55%  { opacity: 1; transform: translateX(-50%) scaleX(1); }
      68%, 100% { opacity: 0; transform: translateX(-50%) scaleX(0); }
    }

    /* ── Cube 3D Loader ───────────────────────────────── */

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

    /* Background paths — fixed sur toute la page, visible au scroll */
    #bg-paths {
      position: fixed; inset: 0;
      pointer-events: none; z-index: 1;
    }
    #bg-paths svg {
      position: absolute; inset: 0;
      width: 100%; height: 100%;
    }
    @keyframes path-flow {
      from { stroke-dashoffset:  2000; }
      to   { stroke-dashoffset: -2000; }
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

    /* ── Arc circular stats ───────────────────────────── */
    .arc-hw {
      cursor: pointer;
      will-change: transform;
      transform-style: preserve-3d;
      transition: transform 0.12s ease;
    }

    /* C — comète qui court sur l'arc */
    @keyframes arc-comet-spin {
      from { stroke-dashoffset:  565; }
      to   { stroke-dashoffset: -565; }
    }
    .arc-comet {
      opacity: 0;
      stroke-dasharray: 60 505;
      stroke-linecap: round;
      stroke-dashoffset: 565;
      transition: opacity 0.35s ease;
      pointer-events: none;
    }
    .arc-hw:hover .arc-comet {
      opacity: 1;
      animation: arc-comet-spin 1.6s linear infinite;
    }

    /* D — glow qui pulse et s'élargit */
    @keyframes arc-glow-pulse {
      0%,100% { transform: scale(1.2); opacity: .65; }
      50%     { transform: scale(1.7); opacity: 1;   }
    }
    .arc-hw > div:first-child {
      transition: transform 0.4s ease, opacity 0.4s ease;
    }
    .arc-hw:hover > div:first-child {
      animation: arc-glow-pulse 1.8s ease-in-out infinite;
    }
    .arc-card  { display:flex; flex-direction:column; align-items:center; text-align:center; gap:16px; }
    .arc-wrap  { position:relative; width:240px; height:240px; }
    .arc-glow  { position:absolute; inset:-20px; border-radius:50%; filter:blur(40px); pointer-events:none; z-index:0; }
    .arc-wrap svg { position:relative; z-index:1; }
    .arc-center {
      position:absolute; top:50%; left:50%;
      transform:translate(-50%,-50%);
      display:flex; flex-direction:column; align-items:center;
      z-index:2; pointer-events:none;
    }
    .arc-prefix  { font-size:22px; font-weight:800; line-height:1; margin-bottom:2px; }
    .arc-num     { font-size:48px; font-weight:900; letter-spacing:-.04em; line-height:1; }
    .arc-context { font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; margin-top:6px; color:#8b8fb0; }
    .arc-label   { font-size:16px; font-weight:700; color:#f5f7ff; }
    .arc-desc    { font-size:13px; color:#8b8fb0; line-height:1.6; max-width:200px; }

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

    /* ── Cinematic Footer (remplace CTA FINAL) ──────── */
    @keyframes cf-breathe   { 0%{transform:translate(-50%,-50%) scale(1);opacity:.5} 100%{transform:translate(-50%,-50%) scale(1.12);opacity:.85} }
    @keyframes cf-marquee   { from{transform:translateX(0)} to{transform:translateX(-50%)} }
    @keyframes cf-heartbeat { 0%,100%{transform:scale(1)} 15%,45%{transform:scale(1.3)} 30%{transform:scale(1)} }

    .cf-wrapper { position:relative; height:100vh; width:100%; clip-path:polygon(0% 0,100% 0%,100% 100%,0 100%); }
    .cf-footer  { position:fixed; bottom:0; left:0; width:100%; height:100vh; display:flex; flex-direction:column; justify-content:space-between; overflow:hidden; background:#050510; }
    .cf-aurora  { position:absolute; width:80vw; height:60vh; left:50%; top:50%; border-radius:50%; background:radial-gradient(circle at 50% 50%,rgba(99,102,241,.18) 0%,rgba(34,211,238,.12) 40%,transparent 70%); filter:blur(80px); animation:cf-breathe 8s ease-in-out infinite alternate; pointer-events:none; z-index:0; }
    .cf-grid    { position:absolute; inset:0; z-index:0; pointer-events:none; background-size:60px 60px; background-image:linear-gradient(to right,rgba(99,102,241,.06) 1px,transparent 1px),linear-gradient(to bottom,rgba(99,102,241,.06) 1px,transparent 1px); mask-image:linear-gradient(to bottom,transparent,black 30%,black 70%,transparent); -webkit-mask-image:linear-gradient(to bottom,transparent,black 30%,black 70%,transparent); }
    .cf-giant   { position:absolute; bottom:-4vh; left:50%; transform:translateX(-50%); font-size:24vw; line-height:.75; font-weight:900; letter-spacing:-.05em; white-space:nowrap; pointer-events:none; user-select:none; color:transparent; -webkit-text-stroke:1px rgba(129,140,248,.08); background:linear-gradient(180deg,rgba(129,140,248,.12) 0%,transparent 60%); -webkit-background-clip:text; background-clip:text; z-index:0; }

    .cf-marquee-wrap  { position:absolute; top:52px; left:0; width:100%; overflow:hidden; border-top:1px solid rgba(255,255,255,.06); border-bottom:1px solid rgba(255,255,255,.06); background:rgba(5,5,16,.75); backdrop-filter:blur(12px); padding:12px 0; transform:rotate(-1.2deg) scaleX(1.06); z-index:10; }
    .cf-marquee-track { display:flex; width:max-content; animation:cf-marquee 38s linear infinite; font-size:11px; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:#6b6f8e; }
    .cf-marquee-item  { display:flex; align-items:center; gap:28px; padding:0 24px; white-space:nowrap; }
    .cf-dot           { color:#6366f1; }

    .cf-main  { position:relative; z-index:10; flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:140px 24px 0; }
    .cf-title { font-size:clamp(36px,6vw,72px); font-weight:900; letter-spacing:-.04em; line-height:1.05; color:transparent; background:linear-gradient(180deg,#f5f7ff 0%,rgba(245,247,255,.4) 100%); -webkit-background-clip:text; background-clip:text; filter:drop-shadow(0 0 30px rgba(129,140,248,.2)); margin:0 0 20px; }
    .cf-sub   { font-size:18px; color:#8b8fb0; margin:0 0 44px; line-height:1.6; }

    .cf-pills-row  { display:flex; flex-wrap:wrap; justify-content:center; gap:10px; margin-top:28px; }
    .cf-glass-pill { display:inline-flex; align-items:center; gap:8px; padding:10px 22px; border-radius:99px; background:rgba(20,22,38,.55); border:1px solid rgba(255,255,255,.08); backdrop-filter:blur(16px); color:#8b8fb0; font-size:12.5px; font-weight:600; text-decoration:none; transition:all .35s cubic-bezier(.16,1,.3,1); cursor:pointer; font-family:inherit; }
    .cf-glass-pill:hover { background:rgba(99,102,241,.15); border-color:rgba(129,140,248,.3); color:#e0e7ff; transform:translateY(-2px); }

    .cf-bottom     { position:relative; z-index:20; padding:20px 40px 28px; display:flex; align-items:center; justify-content:space-between; gap:16px; border-top:1px solid rgba(255,255,255,.05); }
    .cf-copyright  { font-size:10.5px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#6b6f8e; }
    .cf-made-with  { display:flex; align-items:center; gap:8px; padding:8px 20px; border-radius:99px; background:rgba(20,22,38,.55); border:1px solid rgba(255,255,255,.07); backdrop-filter:blur(12px); font-size:10.5px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#6b6f8e; }
    .cf-heart      { display:inline-block; color:#6366f1; animation:cf-heartbeat 2s cubic-bezier(.25,1,.5,1) infinite; }
    .cf-backtop    { width:44px; height:44px; border-radius:50%; background:rgba(20,22,38,.55); border:1px solid rgba(255,255,255,.08); backdrop-filter:blur(16px); display:grid; place-items:center; color:#8b8fb0; cursor:pointer; transition:all .3s; }
    .cf-backtop:hover { background:rgba(99,102,241,.2); border-color:rgba(129,140,248,.3); color:#e0e7ff; transform:translateY(-3px); }

    /* conserver les variables de l'ancien CTA (bouton inchangé) */
    .lp-cta,.cta-glow { display:none; }
    .cta-inner { max-width:680px; position:relative; z-index:2; }
    .cta-title { font-size:clamp(36px,6vw,72px); font-weight:900; letter-spacing:-.04em; color:#f5f7ff; margin:0 0 20px; line-height:1.05; }
    .cta-sub   { font-size:18px; color:#8b8fb0; margin:0 0 48px; line-height:1.6; }
    /* Motion button */
    .cta-btn {
      position: relative;
      display: inline-flex;
      align-items: center;
      height: 68px;
      width: 320px;
      border-radius: 99px;
      background: rgba(14, 14, 30, 0.65);
      border: 1px solid rgba(99,102,241,.35);
      cursor: pointer;
      outline: none;
      overflow: hidden;
      text-decoration: none;
      backdrop-filter: blur(20px);
      box-shadow: 0 0 40px -10px rgba(99,102,241,.3);
      transition: border-color .4s, box-shadow .4s;
    }
    .cta-btn:hover {
      border-color: rgba(129,140,248,.6);
      box-shadow: 0 0 60px -10px rgba(99,102,241,.5);
    }
    .cta-circle {
      display: block;
      /* margin au lieu de padding sur le parent → calcul correct */
      margin: 6px;
      width: 56px; height: 56px;
      border-radius: 99px;
      background: linear-gradient(135deg, #6366f1, #22d3ee);
      flex-shrink: 0;
      box-shadow: inset 0 1px 1px rgba(255,255,255,.35), 0 6px 20px -6px rgba(99,102,241,.7);
      transition: width .55s cubic-bezier(.22,1,.36,1);
    }
    /* 100% de la largeur du btn (320px) - 12px de marge = 308px → remplissage parfait */
    .cta-btn:hover .cta-circle { width: calc(100% - 12px); }
    .cta-arrow {
      position: absolute;
      left: 18px; top: 50%;
      transform: translateY(-50%);
      color: #fff;
      display: flex; align-items: center;
      z-index: 2;
      transition: transform .55s cubic-bezier(.22,1,.36,1);
    }
    .cta-btn:hover .cta-arrow { transform: translateY(-50%) translateX(6px); }
    .cta-label {
      position: absolute;
      left: 50%; top: 50%;
      transform: translate(-50%, -50%);
      white-space: nowrap;
      font-size: 18px; font-weight: 800;
      letter-spacing: -0.02em;
      color: #e8ebf5;
      z-index: 2;
      margin-left: 14px;
      transition: color .4s;
      font-family: "Geist", system-ui, sans-serif;
    }
    .cta-btn:hover .cta-label { color: #fff; }
    .cta-login   { margin-top:20px; font-size:13.5px; color:#6b6f8e; }
    .cta-login a { color:#818cf8; text-decoration:none; }
    .cta-login a:hover { text-decoration:underline; }

    /* ── Responsive landing page ──────────────────────── */
    @media (max-width:900px) {
      /* Hero */
      .lp-hero { padding:80px 18px 100px; }
      .hero-title { font-size:clamp(38px,10vw,72px); }
      .hero-logo  { top:20px; left:20px; }
      .hero-sub   { font-size:16px; }
      .hero-actions { flex-direction:column; align-items:center; }

      /* Features */
      .feat-budget { grid-template-columns:1fr; }
      .feat-budget-3d { min-height:300px; border-left:none; border-top:1px solid rgba(255,255,255,.06); }
      #coin-canvas { width:240px; height:240px; }
      .coin-halo { width:190px; height:190px; }
      .coin-halo-outer { width:260px; height:260px; }

      .feat-fridge, .feat-cart { grid-template-columns:1fr; }
      .feat-fridge-3d, .feat-cart-3d { min-height:280px; border:none; border-top:1px solid rgba(255,255,255,.06); }
      #fridge-canvas, #cart-canvas { width:240px; height:240px; }

      .feat-coloc { grid-template-columns:1fr; }
      .feat-coloc-3d { min-height:280px; border:none; border-top:1px solid rgba(255,255,255,.06); }
      #coloc-canvas { width:240px; height:240px; }

      .feat-row  { grid-template-columns:60px 1fr; }
      .feat-tag  { display:none; }

      /* Arc cards → vertical */
      #arcs-row  { flex-direction:column; align-items:center; gap:40px !important; }
      #arcs-row > div[style*="margin-top:40px"] { margin-top:0 !important; }

      /* How it works */
      .cs-outer  { padding:20px 18px 80px; }
      .cs-card-tilt { padding:28px 20px; }
      .cs-steps-grid { grid-template-columns:1fr; gap:24px; }
      .cs-col-div    { display:none; }

      /* Section padding */
      .lp-section { padding:80px 18px; }

      /* Cinematic footer */
      .cf-bottom { flex-direction:column; gap:14px; padding:16px 20px 24px; }
      .cf-made-with { display:none; }
      .cf-title  { font-size:clamp(28px,8vw,56px); }
      .cf-giant  { font-size:35vw; }
      .cf-pills-row { gap:8px; }
      .cta-btn   { width:90vw; max-width:360px; }
    }

    @media (max-width:480px) {
      .hero-title   { font-size:clamp(32px,11vw,56px); }
      .hero-badge   { font-size:11px; padding:6px 14px; }
      .section-title { font-size:clamp(26px,7vw,42px); }
      .feat-row-icon { width:52px; height:52px; font-size:22px; border-radius:14px; }
      .feat-row     { gap:14px; padding:24px 18px; }
      .feat-budget-text, .feat-fridge-text, .feat-cart-text, .feat-coloc-text { padding:28px 20px; }
      #coin-canvas, #fridge-canvas, #cart-canvas, #coloc-canvas { width:200px !important; height:200px !important; }
      .arc-hw       { transform:none !important; } /* désactive tilt 3D sur mobile */
    }
  </style>
</head>
<body>

<!-- ══ Loading screen ══ -->
<div id="page-loader">
  <div class="loader">
    <div class="lc lc0"><div></div></div>
    <div class="lc lc1"><div></div></div>
    <div class="lc lc2"><div></div></div>
    <div class="lc lc3"><div></div></div>
    <div class="loader-shadow"></div>
  </div>
</div>

<div id="bg-paths"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div id="lp">

  <!-- ══════════════ HERO centré ══════════════ -->
  <section class="lp-hero">

    <div class="hero-logo">
      <img src="<?= BASE_URL ?>/img/logo-dark.png"  class="logo-img-dark"  alt="StudentLife Hub" style="width:36px;height:36px;border-radius:10px;object-fit:contain;">
      <img src="<?= BASE_URL ?>/img/logo-light.png" class="logo-img-light" alt="StudentLife Hub" style="width:36px;height:36px;border-radius:10px;object-fit:contain;">
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
  <section class="lp-section" style="padding-bottom:80px;">
    <div data-sr style="text-align:center;margin-bottom:72px;">
      <div class="section-label" style="justify-content:center;">Impact réel</div>
      <h2 class="section-title" style="text-align:center;">Des résultats qui parlent d'eux-mêmes.</h2>
    </div>

    <!-- Arc circulaires -->
    <div id="arcs-row" style="display:flex;justify-content:center;align-items:flex-start;gap:56px;flex-wrap:wrap;margin-bottom:80px;opacity:0;transform:translateY(40px);transition:opacity .8s ease,transform .8s ease;">

      <!-- Arc 1 — +5 000 étudiants -->
      <div style="display:flex;flex-direction:column;align-items:center;gap:18px;text-align:center;">
        <div class="arc-hw" style="position:relative;width:240px;height:240px;">
          <div style="position:absolute;inset:-24px;border-radius:50%;background:radial-gradient(circle,rgba(34,211,238,.18),transparent 70%);filter:blur(40px);z-index:0;pointer-events:none;"></div>
          <svg viewBox="0 0 240 240" width="240" height="240" style="position:relative;z-index:1;display:block;">
            <defs>
              <linearGradient id="ag1" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#22d3ee"/><stop offset="100%" stop-color="#818cf8"/>
              </linearGradient>
              <filter id="af1"><feGaussianBlur in="SourceGraphic" stdDeviation="4" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
            </defs>
            <circle cx="120" cy="120" r="108" fill="none" stroke="rgba(34,211,238,.08)" stroke-width="1.5" stroke-dasharray="5 9"/>
            <circle cx="120" cy="120" r="90"  fill="none" stroke="rgba(255,255,255,.07)" stroke-width="12"/>
            <circle id="arc1" cx="120" cy="120" r="90" fill="none" stroke="url(#ag1)" stroke-width="12" stroke-linecap="round"
              stroke-dasharray="565.49" stroke-dashoffset="565.49" transform="rotate(-90 120 120)" filter="url(#af1)" data-target="124"/>
            <circle class="arc-comet" cx="120" cy="120" r="90" fill="none"
              stroke="rgba(200,240,255,0.9)" stroke-width="4" transform="rotate(-90 120 120)"/>
          </svg>
          <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:2;display:flex;flex-direction:column;align-items:center;pointer-events:none;">
            <div style="display:flex;align-items:baseline;gap:2px;">
              <span style="font-size:20px;font-weight:800;color:#22d3ee;line-height:1;">+</span>
              <span id="arc1-num" style="display:inline-block;font-size:46px;font-weight:900;letter-spacing:-.04em;line-height:1;background:linear-gradient(120deg,#22d3ee,#818cf8);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;">0</span>
            </div>
            <span style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#8b8fb0;margin-top:6px;">étudiants</span>
          </div>
        </div>
        <div style="font-size:16px;font-weight:700;color:#f5f7ff;">Étudiants organisés</div>
        <div style="font-size:13px;color:#8b8fb0;line-height:1.6;max-width:200px;">qui ont repris le contrôle<br>de leur budget</div>
      </div>

      <!-- Arc 2 — -340 € (décalé vers le bas pour le rythme) -->
      <div style="display:flex;flex-direction:column;align-items:center;gap:18px;text-align:center;margin-top:40px;">
        <div class="arc-hw" style="position:relative;width:240px;height:240px;">
          <div style="position:absolute;inset:-24px;border-radius:50%;background:radial-gradient(circle,rgba(52,211,153,.18),transparent 70%);filter:blur(40px);z-index:0;pointer-events:none;"></div>
          <svg viewBox="0 0 240 240" width="240" height="240" style="position:relative;z-index:1;display:block;">
            <defs>
              <linearGradient id="ag2" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#34d399"/><stop offset="100%" stop-color="#22d3ee"/>
              </linearGradient>
              <filter id="af2"><feGaussianBlur in="SourceGraphic" stdDeviation="4" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
            </defs>
            <circle cx="120" cy="120" r="108" fill="none" stroke="rgba(52,211,153,.08)" stroke-width="1.5" stroke-dasharray="5 9"/>
            <circle cx="120" cy="120" r="90"  fill="none" stroke="rgba(255,255,255,.07)" stroke-width="12"/>
            <circle id="arc2" cx="120" cy="120" r="90" fill="none" stroke="url(#ag2)" stroke-width="12" stroke-linecap="round"
              stroke-dasharray="565.49" stroke-dashoffset="565.49" transform="rotate(-90 120 120)" filter="url(#af2)" data-target="198"/>
            <circle class="arc-comet" cx="120" cy="120" r="90" fill="none"
              stroke="rgba(200,255,235,0.9)" stroke-width="4" transform="rotate(-90 120 120)"/>
          </svg>
          <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:2;display:flex;flex-direction:column;align-items:center;pointer-events:none;">
            <div style="display:flex;align-items:baseline;gap:2px;">
              <span style="font-size:20px;font-weight:800;color:#34d399;line-height:1;">-</span>
              <span id="arc2-num" style="display:inline-block;font-size:46px;font-weight:900;letter-spacing:-.04em;line-height:1;background:linear-gradient(120deg,#34d399,#22d3ee);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;">0</span>
              <span style="font-size:20px;font-weight:800;color:#34d399;line-height:1;margin-left:2px;">€</span>
            </div>
            <span style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#34d399;margin-top:6px;">par mois</span>
          </div>
        </div>
        <div style="font-size:16px;font-weight:700;color:#f5f7ff;">Dépenses évitées</div>
        <div style="font-size:13px;color:#8b8fb0;line-height:1.6;max-width:200px;">en moyenne grâce au suivi<br>en temps réel</div>
      </div>

      <!-- Arc 3 — 2 min -->
      <div style="display:flex;flex-direction:column;align-items:center;gap:18px;text-align:center;">
        <div class="arc-hw" style="position:relative;width:240px;height:240px;">
          <div style="position:absolute;inset:-24px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.22),transparent 70%);filter:blur(40px);z-index:0;pointer-events:none;"></div>
          <svg viewBox="0 0 240 240" width="240" height="240" style="position:relative;z-index:1;display:block;">
            <defs>
              <linearGradient id="ag3" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#818cf8"/><stop offset="100%" stop-color="#6366f1"/>
              </linearGradient>
              <filter id="af3"><feGaussianBlur in="SourceGraphic" stdDeviation="4" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
            </defs>
            <circle cx="120" cy="120" r="108" fill="none" stroke="rgba(99,102,241,.09)" stroke-width="1.5" stroke-dasharray="5 9"/>
            <circle cx="120" cy="120" r="90"  fill="none" stroke="rgba(255,255,255,.07)" stroke-width="12"/>
            <circle id="arc3" cx="120" cy="120" r="90" fill="none" stroke="url(#ag3)" stroke-width="12" stroke-linecap="round"
              stroke-dasharray="565.49" stroke-dashoffset="565.49" transform="rotate(-90 120 120)" filter="url(#af3)" data-target="68"/>
            <circle class="arc-comet" cx="120" cy="120" r="90" fill="none"
              stroke="rgba(210,200,255,0.9)" stroke-width="4" transform="rotate(-90 120 120)"/>
          </svg>
          <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:2;display:flex;flex-direction:column;align-items:center;pointer-events:none;">
            <div style="display:flex;align-items:baseline;gap:6px;">
              <span id="arc3-num" style="display:inline-block;font-size:56px;font-weight:900;letter-spacing:-.04em;line-height:1;background:linear-gradient(120deg,#818cf8,#6366f1);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;">0</span>
              <span style="font-size:20px;font-weight:800;color:#818cf8;line-height:1;">min</span>
            </div>
            <span style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#818cf8;margin-top:6px;">par jour</span>
          </div>
        </div>
        <div style="font-size:16px;font-weight:700;color:#f5f7ff;">Par jour suffisent</div>
        <div style="font-size:13px;color:#8b8fb0;line-height:1.6;max-width:200px;">pour une vue complète<br>de ta vie étudiante</div>
      </div>

    </div>
  </section>

  <!-- ══════════════ CINEMATIC FOOTER ══════════════ -->
  <div class="lp-sep" style="height:80px;"></div>

  <div class="cf-wrapper" id="cf-wrapper">
    <footer class="cf-footer">

      <!-- Ambient glow -->
      <div class="cf-aurora"></div>
      <!-- Grid -->
      <div class="cf-grid"></div>
      <!-- Giant background text -->
      <div class="cf-giant" id="cf-giant">STUDENT</div>

      <!-- Marquee band -->
      <div class="cf-marquee-wrap">
        <div class="cf-marquee-track">
          <?php for($i=0;$i<2;$i++): ?>
          <div class="cf-marquee-item">
            Budget maîtrisé <span class="cf-dot">✦</span>
            Frigo intelligent <span class="cf-dot">✦</span>
            Courses simplifiées <span class="cf-dot">✦</span>
            Vie en coloc <span class="cf-dot">✦</span>
            100% gratuit <span class="cf-dot">✦</span>
            Objectifs d'épargne <span class="cf-dot">✦</span>
          </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- Centre : titre + bouton existant -->
      <div class="cf-main" id="cf-heading">
        <h2 class="cf-title">Prêt à reprendre<br>le contrôle ?</h2>
        <p class="cf-sub">Rejoins StudentLife Hub et arrête de subir ta vie étudiante.<br>C'est gratuit, c'est maintenant.</p>

        <!-- Bouton inchangé -->
        <a href="<?= BASE_URL ?>/auth/register" class="cta-btn cf-mag">
          <span class="cta-circle"></span>
          <span class="cta-arrow">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </span>
          <span class="cta-label">Commencer dès maintenant</span>
        </a>

        <p style="margin-top:18px;font-size:13.5px;color:#6b6f8e;">
          Déjà un compte ? <a href="<?= BASE_URL ?>/auth/login" style="color:#818cf8;text-decoration:none;">Se connecter</a>
        </p>

        <!-- Liens secondaires (glass pills) -->
        <div class="cf-pills-row" id="cf-links">
          <a href="#" class="cf-glass-pill cf-mag">Mentions légales</a>
          <a href="#" class="cf-glass-pill cf-mag">Confidentialité</a>
          <a href="mailto:contact@studentlifehub.fr" class="cf-glass-pill cf-mag">Contact</a>
        </div>
      </div>

      <!-- Barre du bas -->
      <div class="cf-bottom">
        <span class="cf-copyright">© 2026 StudentLife Hub — Tous droits réservés</span>

        <div class="cf-made-with">
          Fait avec <span class="cf-heart">♥</span> pour les étudiants
        </div>

        <button class="cf-backtop cf-mag" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Retour en haut">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 10l7-7 7 7M12 3v18"/></svg>
        </button>
      </div>

    </footer>
  </div>

</div>

<!-- Scroll reveal + Counters -->
<script>
// Masque le loader après chargement (min 1.2s pour voir l'animation)
const loaderEl = document.getElementById('page-loader');
const loaderStart = Date.now();
window.addEventListener('load', () => {
  const elapsed = Date.now() - loaderStart;
  const delay   = Math.max(0, 1200 - elapsed);
  setTimeout(() => loaderEl?.classList.add('out'), delay);
});

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

// ── Background paths (hero) ────────────────────────────
(function() {
  const ns  = 'http://www.w3.org/2000/svg';
  const box = document.getElementById('bg-paths');
  if (!box) return;

  [1, -1].forEach(pos => {
    const svg = document.createElementNS(ns, 'svg');
    svg.setAttribute('viewBox', '0 0 696 316');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('preserveAspectRatio', 'xMidYMid slice');

    for (let i = 0; i < 36; i++) {
      const x1 = -(380 - i * 5 * pos);
      const y1 = -(189 + i * 6);
      const cx2 = -(312 - i * 5 * pos);
      const cy2 =  216 - i * 6;
      const ex  =  152 - i * 5 * pos;
      const ey  =  343 - i * 6;
      const cx3 =  616 - i * 5 * pos;
      const cy3 =  470 - i * 6;
      const ex2 =  684 - i * 5 * pos;
      const ey2 =  875 - i * 6;

      const d = `M${x1} ${y1}C${x1} ${y1} ${cx2} ${cy2} ${ex} ${ey}C${cx3} ${cy3} ${ex2} ${ey2} ${ex2} ${ey2}`;

      const p = document.createElementNS(ns, 'path');
      p.setAttribute('d', d);
      p.setAttribute('fill', 'none');
      const opacity = (0.08 + i * 0.022).toFixed(3);
      p.setAttribute('stroke', pos === 1
        ? `rgba(99,102,241,${opacity})`
        : `rgba(34,211,238,${opacity})`);
      p.setAttribute('stroke-width', (0.4 + i * 0.025).toFixed(2));

      const dur   = (18 + Math.random() * 12).toFixed(1);
      const delay = -(Math.random() * 20).toFixed(1);
      p.style.strokeDasharray  = '400 1600';
      p.style.strokeDashoffset = '0';
      p.style.animation = `path-flow ${dur}s ${delay}s linear infinite`;

      svg.appendChild(p);
    }
    box.appendChild(svg);
  });
})();

// ── Arc hover — tilt 3D magnétique ──────────────────────
document.querySelectorAll('.arc-hw').forEach(function(el) {
  el.addEventListener('mousemove', function(e) {
    var r   = el.getBoundingClientRect();
    var x   = (e.clientX - r.left   - r.width  / 2) / (r.width  / 2); // -1 → +1
    var y   = (e.clientY - r.top    - r.height / 2) / (r.height / 2);
    el.style.transition = 'transform 0.1s ease';
    el.style.transform  =
      'perspective(700px) rotateY(' + (x * 16) + 'deg) rotateX(' + (-y * 16) + 'deg) scale3d(1.06,1.06,1.06)';
  });

  el.addEventListener('mouseleave', function() {
    el.style.transition = 'transform 0.7s cubic-bezier(0.34,1.56,0.64,1)';
    el.style.transform  = '';
  });
});

// ── Arc circulaires — animation au scroll ───────────────
(function() {
  var arcsRow = document.getElementById('arcs-row');
  if (!arcsRow) return;

  var arcs = [
    { path: document.getElementById('arc1'), num: document.getElementById('arc1-num'), target: 124, count: 5000, delay: 0 },
    { path: document.getElementById('arc2'), num: document.getElementById('arc2-num'), target: 198, count: 340, delay: 200 },
    { path: document.getElementById('arc3'), num: document.getElementById('arc3-num'), target: 68,  count: 2,   delay: 400 },
  ];

  function animateCounter(el, target, delay) {
    setTimeout(function() {
      var d = 1800, s = performance.now();
      (function tick(now) {
        var p = Math.min((now - s) / d, 1), e = 1 - Math.pow(1 - p, 3);
        var v = Math.round(e * target);
        el.textContent = target >= 100 ? v.toLocaleString('fr-FR') : v;
        if (p < 1) requestAnimationFrame(tick);
      })(performance.now());
    }, delay);
  }

  function animateArc(path, target, delay) {
    setTimeout(function() {
      // Trick reflow : forcer le navigateur à lire le layout actuel
      // avant d'appliquer la transition
      path.style.transition = 'none';
      path.style.strokeDashoffset = '565.49';
      void path.getBoundingClientRect(); // force reflow
      path.style.transition = 'stroke-dashoffset 2s cubic-bezier(0.4,0,0.2,1)';
      path.style.strokeDashoffset = String(target);
    }, delay);
  }

  var triggered = false;
  var obs = new IntersectionObserver(function(entries) {
    if (!entries[0].isIntersecting || triggered) return;
    triggered = true;

    // Révèle la ligne entière
    arcsRow.style.opacity = '1';
    arcsRow.style.transform = 'translateY(0)';

    // Lance chaque arc avec son délai
    arcs.forEach(function(a) {
      animateArc(a.path, a.target, a.delay);
      animateCounter(a.num, a.count, a.delay);
    });

    obs.disconnect();
  }, { threshold: 0.2 });

  obs.observe(arcsRow);
})();

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

<!-- GSAP pour le cinematic footer -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script>
gsap.registerPlugin(ScrollTrigger);

// ── Giant text parallax ──────────────────────────────
gsap.fromTo('#cf-giant',
  { y: '10vh', scale: 0.85, opacity: 0 },
  { y: '0vh', scale: 1, opacity: 1, ease: 'power1.out',
    scrollTrigger: { trigger: '#cf-wrapper', start: 'top 80%', end: 'bottom bottom', scrub: 1 }
  }
);

// ── Heading + links stagger reveal ──────────────────
gsap.fromTo('#cf-heading',
  { y: 60, opacity: 0 },
  { y: 0, opacity: 1, ease: 'power3.out',
    scrollTrigger: { trigger: '#cf-wrapper', start: 'top 50%', end: 'center bottom', scrub: 1 }
  }
);

// ── Magnetic buttons ────────────────────────────────
document.querySelectorAll('.cf-mag').forEach(el => {
  el.style.transition = 'transform 0.4s cubic-bezier(.16,1,.3,1)';
  el.addEventListener('mousemove', e => {
    const r = el.getBoundingClientRect();
    const x = (e.clientX - r.left - r.width  / 2) * 0.35;
    const y = (e.clientY - r.top  - r.height / 2) * 0.35;
    el.style.transform = `translate(${x}px,${y}px)`;
  });
  el.addEventListener('mouseleave', () => {
    el.style.transition = 'transform 0.8s cubic-bezier(.34,1.56,.64,1)';
    el.style.transform = '';
  });
});
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
