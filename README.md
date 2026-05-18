# StudentLife Hub

> **Ta vie étudiante, enfin maîtrisée.**  
> Application web de gestion du quotidien étudiant — budget, frigo, courses et colocation.

---

## Présentation

StudentLife Hub est une application web PHP pensée pour les étudiants qui souhaitent reprendre le contrôle de leur vie quotidienne. Elle centralise la gestion financière, l'inventaire du frigo, les listes de courses et les dépenses partagées en colocation dans une interface moderne au style **Y2K Glassmorphism Aqua**.

---

## Fonctionnalités

| Module | Description |
|---|---|
| 💰 **Budget** | Suivi des revenus et dépenses par catégorie, donut chart, solde en temps réel |
| 🧊 **Frigo** | Inventaire des aliments avec alertes de péremption |
| 🛒 **Courses** | Liste de courses collaborative avec système de cases à cocher |
| 👥 **Colocation** | Dépenses partagées, système d'invitations, calcul des remboursements |
| 🎯 **Objectifs** | Objectifs d'épargne avec barre de progression |
| 👤 **Profil** | Modification des informations personnelles et du mot de passe |
| 🌙 **Dark / Light** | Mode sombre et clair persistant via `localStorage` |
| 📊 **Dashboard** | Vue synthétique avec graphe 6 mois, prévisionnel, score mensuel et alertes budget |

---

## Stack technique

- **Back-end** : PHP 8.x — architecture MVC maison (Router, Controller, Model)
- **Base de données** : MySQL via PDO (pattern Singleton)
- **Front-end** : HTML/CSS/JS vanilla — aucun framework front
- **3D** : Three.js (GLTFLoader) — modèles `.glb` sur la landing page
- **Animations** : GSAP + ScrollTrigger, CSS animations, Intersection Observer
- **Style** : CSS custom (Y2K Glassmorphism Aqua) — pas de Tailwind, pas de Bootstrap

---

## Prérequis

- **PHP** 8.1+ (CLI ou via MAMP/XAMPP)
- **MySQL** 8.0+ (via MAMP recommandé sur macOS)
- Un navigateur moderne (Chrome, Firefox, Safari)

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/TON_USERNAME/studentlife-hub.git
cd studentlife-hub
```

### 2. Configurer la base de données

Crée une base de données MySQL et importe le schéma :

```bash
mysql -u root -p < database/studentlife.sql
```

Puis édite `config/database.php` avec tes identifiants :

```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);        // 8889 si MAMP
define('DB_NAME', 'studentlife_hub');
define('DB_USER', 'root');
define('DB_PASS', 'root');
```

### 3. Lancer le serveur

```bash
cd public
php -S localhost:8000 index.php
```

Ouvre ensuite [http://localhost:8000](http://localhost:8000) dans ton navigateur.

> **Avec MAMP** : pointe le Document Root vers le dossier `public/` et accède via `http://localhost:8888`.

---

## Comptes de démonstration

| Email | Mot de passe | Rôle |
|---|---|---|
| `alice@example.com` | `password123` | Utilisateur test |
| `bob@example.com` | `password123` | Utilisateur test |

> Les données de démonstration sont incluses dans `database/studentlife.sql`.

---

## Structure du projet

```
studentlife-hub/
├── public/                 # Point d'entrée web (index.php, .htaccess)
│   ├── css/
│   │   └── y2k.css         # Feuille de style principale (Y2K Glassmorphism)
│   ├── img/                # Logos dark/light
│   └── models/             # Modèles 3D (.glb) pour la landing page
│
├── app/
│   ├── controllers/        # Logique métier (Dashboard, Expenses, Fridge…)
│   ├── models/             # Accès base de données (PDO)
│   ├── views/              # Templates PHP
│   │   ├── layout/         # header.php, sidebar.php, footer.php
│   │   ├── home/           # Landing page (vitrine)
│   │   ├── dashboard/
│   │   ├── expenses/
│   │   ├── fridge/
│   │   ├── shopping/
│   │   ├── savings/
│   │   ├── shared/
│   │   ├── profile/
│   │   └── auth/           # Login / Register
│   └── helpers/
│       └── functions.php   # icon() SVG helper
│
├── config/
│   └── database.php        # Configuration PDO
│
├── core/
│   ├── Router.php          # Routeur URL → Contrôleur/Action
│   ├── Controller.php      # Classe de base (render, redirect, flash…)
│   └── Model.php           # Classe de base (CRUD PDO)
│
└── database/
    └── studentlife.sql     # Schéma + données de démonstration
```

---

## Architecture MVC

Le routeur (`core/Router.php`) analyse l'URL et dispatche vers le bon contrôleur :

```
GET /expenses        →  ExpensesController::index()
GET /fridge/delete/5 →  FridgeController::delete(5)
POST /savings        →  SavingsController::index() [POST]
```

Les vues reçoivent leurs données via `extract($data)` après un `ob_start()` qui bufferise le contenu avant d'injecter le layout.

---

## Variables d'environnement

Aucun fichier `.env` n'est utilisé. La configuration se fait directement dans `config/database.php`.  

> ⚠️ **Ne jamais committer ce fichier** si la base est en production. Ajoute-le à `.gitignore` :
> ```
> config/database.php
> ```

---

## Auteur

Développé par **Elliot Klein** — Étudiant en SN 3A, dans le cadre du cours de Développement Web (S6).

---

## Licence

Projet académique — usage interne uniquement.
