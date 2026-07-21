# Version 1 (v1)

## Binômes

- 4328
- 4107

## Base

- operateurs
  - config_préfixes
  - types_operations: id, nom (depot, retrait, transfert)
  - baremes_frais: id, type_operation_id(FK), montant_min/max, frais (gestion des tranches modifiables)
  - transactions: id, client_id(/expediteur)(FK), destinataire_id(nullable si depot/retrait), type_operation_id(FK), montant, frais_appliques, date_transaction
  - clients: id, num_tel, solde, auto_login

## Dev A: cote client et authentification

- [x] auto_login: formulaire pour le client(num_tel:033/037), verification base, if num n'existe pas, creation auto avec solde 0 (+equipement session)
- [x] espace_client(dashboard): affichage du solde actuel, historique des transactions propre à ce num
- [x] formulaire d'opérations
  - [x] depot/retrait: formulaire simple(auto) (mise à jour solde et enregistrement d'action)
  - [x] transfert: formulaire avec num du destinataire (valider prefixe) et montant, déduire (montant+frais) du solde de l'expediteur, ajouter montant au destinataire

## Développeur B : Partie opérateur/backend

- config_prefixes: une vue/action pour lister et ajouter des prefixes
- gestion_baremes: interface pour voir et modifier les frais par tranche pour les retraits et transferts
- tableau de bord operateur (statistique)
  - situation des gains: requête SQL (Sum(frais_appliques)) pour voir l'argent total genere par l'operateur via les retraits et transferts
  - situation des comptes clients: liste de tous les clients avec leur num et solde actuel

## Travaux réalisés

- Création du module opérateur.
- Création du contrôleur Operateur.
- Création des modèles
  - PrefixeModel
  - ClientModel
  - FraisModel
  - OperationModel
- Mise en place de la gestion des préfixes
  - affichage des préfixes opérateur
  - ajout d'un nouveau préfixe
- Mise en place de la gestion des barèmes
  - affichage des tranches de frais
  - modification des frais
- Création du tableau de bord opérateur
  - calcul des gains générés par les frais de retrait et transfert
  - affichage de la situation des comptes clients
- Création des vues Bootstrap pour l'espace opérateur.

## NB

- calcul des frais: creer une fonction d'aide(param: type_operation et montant qui fait SELECT frais FROM baremes_frais WHERE type_operation_id = X AND montant BETWEEN montant_min AND montant_max (coeur du systeme)
- design(bootstrap): tableaux + formulaire simple (classes de base de bootstrap)
- git workflow
  - git add .
  - git commit -m "Nom_du_devoir_à_pusher"
  - git tag v1
  - git push origin main --tags

## Reste à faire

- Dev B complet: configuration des préfixes, gestion des barèmes, tableau de bord opérateur.
- Vérifier si le schéma doit réellement contenir un champ auto_login dans la table clients ou si la logique d'auto-inscription suffit.
- Finaliser le workflow git demandé dans le sujet si la livraison n'est pas encore taguée/pushée.
________________________________________________________________________

v2
____________________________________
-Base: 
  -différencier les opérateurs: ajouter une colonne ou  une nouvelle table pour lister les prefixes des autres resaux
  -config de la commission externe: stockage du % supplementaire applique lors d'un transfert vers un autre opérateur
  -mise à jour des transactions: à noter le montant envoyé, la part des frais normaux(opérateur), la part de la commission externe
________________________________________________________________
-Dev A: côté client pour la logique des transferts 
  -inclure les frais de retrait: 
     -ajouter une case à cocher dans le formule de transfert
     -logique: si cochée, le systeme calcule à l'avance combien le destinataire va devoir payer en frais s'il retirait cet argent, et l'ajoute au montant total débité de l'expéditeur, puis le destinataire reçoit le montant net exact désiré
  -envoi multiple pour la division du montant:
    -permission de saisir plusieurs numéros dans le champ destinataire
    -logique: si l'utilisateur met 3 numéros et saisit 30 000 Ar, le système fait 3 transferts distincts de 10 000 Ar chacun
    -à valider si chaque numéro est interne ou externe pour appliquer les bons frais
______________________________________________________________________
2 Aléas:
tag etu
-Promotion % sur les frais de transfert vers le même operateur(-20% frais)

samihafa pour chaque client ny pourcentage epargne 
misy transfert tonga aty amiko de misy frais , 

definit pourcentage 1



