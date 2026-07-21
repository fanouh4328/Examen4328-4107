-- ============================================================================
-- FICHIER : base.sql (À la racine du projet)
-- SYSTEME  : Simulateur Mobile Money (Version 1 & 2)
-- ============================================================================

PRAGMA foreign_keys = ON;

-- 1. Table des préfixes valides de l'opérateur
CREATE TABLE IF NOT EXISTS prefixe_operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(10) NOT NULL UNIQUE
);

-- 2. Table des préfixes externes (Nouveau V2)
CREATE TABLE IF NOT EXISTS prefixes_externes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_operateur VARCHAR(50) NOT NULL,
    prefixe VARCHAR(10) NOT NULL UNIQUE
);

-- 3. Table de configuration commission externe (Nouveau V2)
CREATE TABLE IF NOT EXISTS config_commission_externe (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pourcentage REAL NOT NULL DEFAULT 5.0
);

-- 4. Table des clients
CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    num_tel VARCHAR(20) NOT NULL UNIQUE,
    solde REAL DEFAULT 0.0
);

-- 5. Table des types d'opérations
CREATE TABLE IF NOT EXISTS types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- 6. Table des barèmes de frais par tranche
CREATE TABLE IF NOT EXISTS baremes_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id) ON DELETE CASCADE
);

-- 7. Table des transactions
CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    destinataire_id INTEGER DEFAULT NULL,
    type_operation_id INTEGER NOT NULL,
    montant REAL NOT NULL,
    frais_appliques REAL NOT NULL,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (destinataire_id) REFERENCES clients(id),
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

-- ============================================================================
-- VUES REQUISES
-- ============================================================================

CREATE VIEW IF NOT EXISTS v_situation_gains AS
SELECT 
    t.id AS type_operation_id,
    t.nom AS type_operation,
    COUNT(tr.id) AS nombre_operations,
    IFNULL(SUM(tr.frais_appliques), 0) AS total_gains
FROM types_operations t
LEFT JOIN transactions tr ON t.id = tr.type_operation_id
WHERE t.id IN (2, 3)
GROUP BY t.id, t.nom;

CREATE VIEW IF NOT EXISTS v_situation_comptes_clients AS
SELECT 
    id AS client_id,
    num_tel,
    solde
FROM clients
ORDER BY solde DESC;

-- ============================================================================
-- INSERTIONS INITIALES
-- ============================================================================

INSERT INTO prefixe_operateurs (prefixe) VALUES ('033'), ('037');

INSERT INTO prefixes_externes (nom_operateur, prefixe) VALUES 
('Telma', '034'),
('Orange', '032');

INSERT INTO config_commission_externe (pourcentage) VALUES (5.0);

INSERT INTO types_operations (id, nom) VALUES 
(1, 'dépôt'),
(2, 'retrait'),
(3, 'transfert');

INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
(2, 100, 1000, 50),
(2, 1001, 5000, 50),
(2, 5001, 10000, 100),
(2, 10001, 25000, 200),
(2, 25001, 50000, 400),
(2, 50001, 100000, 800),
(2, 100001, 250000, 1500),
(2, 250001, 500000, 1500),
(2, 500001, 1000000, 2500),
(2, 1000001, 2000000, 3000),

(3, 100, 1000, 50),
(3, 1001, 5000, 50),
(3, 5001, 10000, 100),
(3, 10001, 25000, 200),
(3, 25001, 50000, 400),
(3, 50001, 100000, 800),
(3, 100001, 250000, 1500),
(3, 250001, 500000, 1500),
(3, 500001, 1000000, 2500),
(3, 1000001, 2000000, 3000);