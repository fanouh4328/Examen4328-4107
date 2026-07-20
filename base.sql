-- ============================================================================
-- FICHIER : base.sql (À la racine du projet)
-- SYSTEME  : Simulateur Mobile Money (Version 1)
-- ============================================================================

-- Activation des clés étrangères pour SQLite
PRAGMA foreign_keys = ON;

-- Note : Suppression des instructions CREATE DATABASE et USE (Incompatibles avec SQLite)

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
CREATE TABLE baremes_frais (
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
-- 6. VUES REQUISES PAR LE SUJET
-- ============================================================================

-- Vue pour l'opérateur : Situation des gains via les différents frais[cite: 1]
CREATE VIEW IF NOT EXISTS v_situation_gains AS
SELECT 
    t.id AS type_operation_id,
    t.nom AS type_operation,
    COUNT(tr.id) AS nombre_operations,
    IFNULL(SUM(tr.frais_appliques), 0) AS total_gains,
    IFNULL(SUM(tr.part_frais_normaux), 0) AS gains_normaux,
    IFNULL(SUM(tr.part_commission_externe), 0) AS gains_commission_externe
FROM types_operations t
LEFT JOIN transactions tr ON t.id = tr.type_operation_id
WHERE t.id IN (2, 3) -- 2 = retrait, 3 = transfert[cite: 1]
GROUP BY t.id, t.nom;

-- Vue pour l'opérateur : Situation globale des comptes clients[cite: 1]
CREATE VIEW IF NOT EXISTS v_situation_comptes_clients AS
SELECT 
    id AS client_id,
    num_tel,
    solde
FROM clients
ORDER BY solde DESC;


-- ============================================================================
-- INSERTIONS INITIALES (DONNÉES DE CONFIGURATION)
-- ============================================================================

-- Insertion des préfixes initiaux exemples (033, 037)
INSERT INTO prefixe_operateurs (prefixe) VALUES ('033'), ('037');

-- NOUVEAU V2 : Insertion des préfixes externes (Autres réseaux)
INSERT INTO prefixes_externes (nom_operateur, prefixe) VALUES 
('Telma', '034'),
('Orange', '032');

-- NOUVEAU V2 : Initialisation du taux de commission externe (ex: 5% supplémentaires)
INSERT INTO config_commission_externe (pourcentage) VALUES (5.0);

-- Insertion des types d'opérations obligatoires
INSERT INTO types_operations (id, nom) VALUES 
(1, 'dépôt'),
(2, 'retrait'),
(3, 'transfert');

-- Insertion du barème par tranche donné en exemple[cite: 1]
-- Correction apportée : 50010 corrigé en 50001 pour la cohérence des tranches
INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
(2, 100, 1000, 50),
(2, 1001, 5000, 50),
(2, 5001, 10000, 100),
(2, 10001, 25000, 200),
(2, 25001, 50000, 400),
(2, 50001, 100000, 800), -- Corrigé ici (50001)
(2, 100001, 250000, 1500),
(2, 250001, 500000, 1500),
(2, 500001, 1000000, 2500),
(2, 1000001, 2000000, 3000),

(3, 100, 1000, 50),
(3, 1001, 5000, 50),
(3, 5001, 10000, 100),
(3, 10001, 25000, 200),
(3, 25001, 50000, 400),
(3, 50001, 100000, 800), -- Corrigé ici (50001)
(3, 100001, 250000, 1500),
(3, 250001, 500000, 1500),
(3, 500001, 1000000, 2500),
(3, 1000001, 2000000, 3000);