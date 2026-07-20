-- ============================================================================
-- FICHIER : base.sql (À la racine du projet)
-- SYSTEME  : Simulateur Mobile Money (Version 1)
-- ============================================================================

-- Activation des clés étrangères pour SQLite
PRAGMA foreign_keys = ON;

CREATE DATABASE IF NOT EXISTS mobile_money;
use mobile_money;
-- 1. Table des préfixes valides de l'opérateur
CREATE TABLE prefixe_operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(10) NOT NULL UNIQUE
);

-- 2. Table des clients
CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    num_tel VARCHAR(20) NOT NULL UNIQUE,
    solde REAL DEFAULT 0.0
);

-- 3. Table des types d'opérations (depot, retrait, transfert)
CREATE TABLE types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- 4. Table des barèmes de frais par tranche
CREATE TABLE baremes_fairs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id) ON DELETE CASCADE
);

-- 5. Table des transactions/historiques
CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    destinataire_id INTEGER DEFAULT NULL, -- Nullable si dépôt ou retrait
    type_operation_id INTEGER NOT NULL,
    montant REAL NOT NULL,
    frais_appliques REAL NOT NULL,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (destinataire_id) REFERENCES clients(id),
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

-- ============================================================================
-- INSERTIONS INITIALES (DONNÉES DE CONFIGURATION)
-- ============================================================================

-- Insertion des préfixes initiaux exemples (033, 037)
INSERT INTO prefixe_operateurs (prefixe) VALUES ('033'), ('037');

-- Insertion des types d'opérations obligatoires
INSERT INTO types_operations (id, nom) VALUES 
(1, 'dépôt'),
(2, 'retrait'),
(3, 'transfert');

-- Insertion du barème par tranche donné en exemple (appliqué ici aux retraits et transferts à titre d'exemple)
-- Note : Montant max fixé à une valeur très haute (ex: 99999999) pour la dernière tranche.
INSERT INTO baremes_fairs (type_operation_id, montant_min, montant_max, frais) VALUES
(2, 100, 1000, 50),
(2, 1001, 5000, 50),
(2, 5001, 10000, 100),
(2, 10001, 25000, 200),
(2, 25001, 50000, 400),
(2, 50010, 100000, 800),
(2, 100001, 250000, 1500),
(2, 250001, 500000, 1500),
(2, 500001, 1000000, 2500),
(2, 1000001, 2000000, 3000),

(3, 100, 1000, 50),
(3, 1001, 5000, 50),
(3, 5001, 10000, 100),
(3, 10001, 25000, 200),
(3, 25001, 50000, 400),
(3, 50010, 100000, 800),
(3, 100001, 250000, 1500),
(3, 250001, 500000, 1500),
(3, 500001, 1000000, 2500),
(3, 1000001, 2000000, 3000);