-- StudentLife Hub - Schéma de base de données
-- Créé le 2026-03-24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Base de données gérée par l'hébergeur (k23008753)

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: shared_groups
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `shared_groups` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `created_by` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: group_members
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `group_members` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `group_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `joined_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`group_id`) REFERENCES `shared_groups`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_member` (`group_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: expenses
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `expenses` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `category` ENUM('alimentation','logement','transport','loisirs','sante','education','autre') NOT NULL DEFAULT 'autre',
    `description` VARCHAR(255) DEFAULT NULL,
    `date` DATE NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_date` (`user_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: income
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `income` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `source` VARCHAR(150) NOT NULL,
    `date` DATE NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_date` (`user_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: budgets
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `budgets` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `category` ENUM('alimentation','logement','transport','loisirs','sante','education','autre') NOT NULL,
    `monthly_limit` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_budget` (`user_id`, `category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: fridge_items
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `fridge_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `quantity` DECIMAL(10,2) NOT NULL DEFAULT 1,
    `unit` VARCHAR(30) DEFAULT NULL,
    `category` ENUM('fruits_legumes','produits_frais','surgeles','epicerie','boissons','autre') NOT NULL DEFAULT 'autre',
    `expiry_date` DATE DEFAULT NULL,
    `added_date` DATE NOT NULL DEFAULT (CURRENT_DATE),
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_expiry` (`user_id`, `expiry_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: shopping_list
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `shopping_list` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `quantity` DECIMAL(10,2) NOT NULL DEFAULT 1,
    `unit` VARCHAR(30) DEFAULT NULL,
    `status` ENUM('a_acheter','achete') NOT NULL DEFAULT 'a_acheter',
    `priority` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_status` (`user_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Données d'exemple
-- --------------------------------------------------------

-- Utilisateurs (mots de passe: password123)
INSERT INTO `users` (`name`, `email`, `password`, `created_at`) VALUES
('Alice Martin', 'alice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-15 10:00:00'),
('Bob Dupont', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-20 14:30:00'),
('Clara Petit', 'clara@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-02-01 09:15:00');

-- Groupes partagés
INSERT INTO `shared_groups` (`name`, `created_by`, `created_at`) VALUES
('Coloc Rue de la Paix', 1, '2026-01-15 10:30:00');

-- Membres du groupe
INSERT INTO `group_members` (`group_id`, `user_id`, `joined_at`) VALUES
(1, 1, '2026-01-15 10:30:00'),
(1, 2, '2026-01-20 15:00:00'),
(1, 3, '2026-02-01 09:30:00');

-- Budgets mensuels
INSERT INTO `budgets` (`user_id`, `category`, `monthly_limit`) VALUES
(1, 'alimentation', 300.00),
(1, 'logement', 500.00),
(1, 'transport', 100.00),
(1, 'loisirs', 150.00),
(1, 'sante', 50.00),
(1, 'education', 200.00),
(2, 'alimentation', 250.00),
(2, 'logement', 450.00),
(2, 'transport', 80.00),
(2, 'loisirs', 100.00);

-- Revenus
INSERT INTO `income` (`user_id`, `amount`, `source`, `date`) VALUES
(1, 800.00, 'Bourse CROUS', '2026-03-01'),
(1, 400.00, 'Job étudiant - Librairie', '2026-03-15'),
(1, 800.00, 'Bourse CROUS', '2026-02-01'),
(2, 750.00, 'Bourse CROUS', '2026-03-01'),
(2, 300.00, 'Job étudiant - Restaurant', '2026-03-10'),
(3, 900.00, 'Bourse CROUS', '2026-03-01');

-- Dépenses
INSERT INTO `expenses` (`user_id`, `amount`, `category`, `description`, `date`) VALUES
(1, 45.50, 'alimentation', 'Courses supermarché', '2026-03-01'),
(1, 500.00, 'logement', 'Loyer mars', '2026-03-01'),
(1, 12.80, 'transport', 'Ticket de métro (carnet)', '2026-03-03'),
(1, 28.00, 'alimentation', 'Marché bio', '2026-03-05'),
(1, 35.00, 'loisirs', 'Cinéma + resto avec amis', '2026-03-08'),
(1, 89.00, 'education', 'Manuel de mathématiques', '2026-03-10'),
(1, 15.20, 'alimentation', 'Boulangerie et épicerie', '2026-03-12'),
(1, 22.00, 'transport', 'Pass navigo complément', '2026-03-14'),
(1, 67.30, 'alimentation', 'Courses Lidl', '2026-03-18'),
(1, 18.50, 'sante', 'Pharmacie', '2026-03-20'),
(1, 42.00, 'loisirs', 'Sortie musée + expo', '2026-03-22'),
(2, 38.00, 'alimentation', 'Courses hebdomadaires', '2026-03-02'),
(2, 450.00, 'logement', 'Loyer mars', '2026-03-01'),
(2, 55.00, 'alimentation', 'Courses + snacks', '2026-03-09'),
(2, 29.90, 'loisirs', 'Abonnement Netflix + Spotify', '2026-03-05'),
(3, 320.00, 'logement', 'Résidence étudiante mars', '2026-03-01'),
(3, 72.00, 'alimentation', 'Courses Carrefour', '2026-03-04'),
(3, 15.00, 'transport', 'Vélib abonnement', '2026-03-01');

-- Articles dans le frigo
INSERT INTO `fridge_items` (`user_id`, `name`, `quantity`, `unit`, `category`, `expiry_date`, `added_date`) VALUES
(1, 'Yaourts nature', 6, 'pièces', 'produits_frais', '2026-03-26', '2026-03-20'),
(1, 'Lait demi-écrémé', 1, 'litre', 'produits_frais', '2026-03-28', '2026-03-22'),
(1, 'Pommes', 5, 'pièces', 'fruits_legumes', '2026-03-30', '2026-03-22'),
(1, 'Carottes', 500, 'g', 'fruits_legumes', '2026-04-02', '2026-03-20'),
(1, 'Fromage râpé', 200, 'g', 'produits_frais', '2026-03-25', '2026-03-18'),
(1, 'Poulet rôti', 1, 'pièce', 'produits_frais', '2026-03-24', '2026-03-22'),
(1, 'Jus d\'orange', 1, 'litre', 'boissons', '2026-04-15', '2026-03-20'),
(1, 'Pâtes', 500, 'g', 'epicerie', NULL, '2026-03-15'),
(1, 'Riz basmati', 1, 'kg', 'epicerie', NULL, '2026-03-10'),
(1, 'Glace vanille', 1, 'litre', 'surgeles', '2026-09-01', '2026-03-01'),
(2, 'Beurre', 250, 'g', 'produits_frais', '2026-04-10', '2026-03-15'),
(2, 'Oeufs', 12, 'pièces', 'produits_frais', '2026-04-05', '2026-03-20'),
(2, 'Tomates', 400, 'g', 'fruits_legumes', '2026-03-26', '2026-03-22');

-- Liste de courses
INSERT INTO `shopping_list` (`user_id`, `name`, `quantity`, `unit`, `status`, `priority`) VALUES
(1, 'Pain complet', 1, 'miche', 'a_acheter', 1),
(1, 'Bananes', 6, 'pièces', 'a_acheter', 2),
(1, 'Café moulu', 250, 'g', 'a_acheter', 1),
(1, 'Shampoing', 1, 'flacon', 'a_acheter', 0),
(1, 'Savon liquide', 2, 'flacons', 'a_acheter', 0),
(1, 'Lessive', 1, 'boîte', 'achete', 0),
(1, 'Eau minérale', 6, 'bouteilles', 'achete', 1),
(1, 'Fromage râpé', 200, 'g', 'a_acheter', 1),
(2, 'Tomates pelées', 2, 'boîtes', 'a_acheter', 1),
(2, 'Huile d\'olive', 1, 'bouteille', 'a_acheter', 0),
(2, 'Pâtes fusilli', 500, 'g', 'achete', 1);
