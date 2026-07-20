 # Livraison Version 1 (v1)
___________________________________________
# Binômes:
4328
4107
____________________________________________
## Base:
  -operateurs:
* config_préfixes
* types_operations: id, nom(depot, retrait, transfert)
* baremes_frais: id, type_operation_id(FK), montant_min/max, frais(gestion des tranches modifiables)
* transactions: id, client_id(/expediteur)(FK), destinataire_id(nullable si depot/retrait), type_operation_id(FK), montant, frais_appliques, date_transaction  
* clients: id, num_tel, solde, auto_login

  -operateurs:

* autres_operateurs : id, prefixe, commission
* configuration_commissions : pourcentage appliqué aux transferts vers les autres opérateurs
* situation_gains : gains de l'opérateur principal et des autres opérateurs
* montants_a_envoyer : montant total à envoyer à chaque opérateur

__________________________________________
<<<<<<< HEAD
-Dev A: cote client et authentification
 -[x] auto_login: formulaire pour le client(num_tel:033/037), verification base, if num n'existe pas, creation auto avec solde 0 (+equipement session)
 -[x] espace_client(dashboard): affichage du solde actuel, historique des transactions propre à ce num
 -[x] formulaire d'opérations: 
   -[x] depot/retrait: formulaire simple(auto)(mise à jour solde et enregistrement d'action)
   -[x] transfert:formulaire avec num du destinataire(valider prefixe) et montant, déduire(montant+frais) du solde de l'expediteur, ajouter montant au destinataire
=======
## Dev A: cote client et authentification
 -auto_login: formulaire pour le client(num_tel:033/037), verification base, if num n'existe pas, creation auto avec solde 0 (+equipement session)
 -espace_client(dashboard): affichage du solde actuel, historique des transactions propre à ce num
 -formulaire d'opérations: 
   -depot/retrait: formulaire simple(auto)(mise à jour solde et enregistrement d'action)
   -transfert:formulaire avec num du destinataire(valider prefixe) et montant, déduire(montant+frais) du solde de l'expediteur, ajouter montant au destinataire
>>>>>>> 16480bae60f26207f114d2a9d31ba64fbcacce74
__________________________________________
## Développeur B : Partie opérateur/backend
 1. config_prefixes: une vue/action pour lister et ajouter des prefixes
 2. gestion_baremes: interface pour voir et modifier les frais par tranche pour les retraits et transferts
 3. tableau de bord operateur(statistique):
 4. situation des gains: requête SQL(Sum(frais_appliques)) pour voir l'argent total genere par l'operateur via les retraits et transferts
 5. situation des comptes clients: liste de tous les clients avec leur num et solde actuel

### Travaux réalisés :

- Création du module opérateur.
- Création du contrôleur Operateur.
- Création des modèles :
  - PrefixeModel
  - ClientModel
  - FraisModel
  - OperationModel

- Mise en place de la gestion des préfixes :
  - affichage des préfixes opérateur
  - ajout d'un nouveau préfixe

- Mise en place de la gestion des barèmes :
  - affichage des tranches de frais
  - modification des frais

- Création du tableau de bord opérateur :
  - calcul des gains générés par les frais de retrait et transfert
  - affichage de la situation des comptes clients

- Création des vues Bootstrap pour l'espace opérateur.
___________________________________________________
NB: 
   -calcul des frais: creer une fonction d'aide(param: type_operation et montant qui fait SELECT frais  FROM baremes_frais WHERE type_operation_id = X AND: montant BETWEEN montant_min AND montant_max(coeur du systeme)
   -design(bootstrap): tableaux + formulaire simple(classes de base de bootstrap) 
   -git workflow: git add .


Reste à faire:
 - Dev B complet: configuration des préfixes, gestion des barèmes, tableau de bord opérateur.
 - Vérifier si le schéma doit réellement contenir un champ auto_login dans la table clients ou si la logique d'auto-inscription suffit.
 - Finaliser le workflow git demandé dans le sujet si la livraison n'est pas encore taguée/pushée.

____________________________________________

# Livraison V2 
____________________________________________
## Développeur B : Partie Opérateur/backend 
1. Gestion des préfixes des autres opérateurs
  * Ajouter et afficher les préfixes des autres opérateurs (032, 031, etc.).

2. Gestion des commissions
  * Configurer le pourcentage de commission pour les transferts vers les autres opérateurs.

3. Situation des gains
  * Séparer les gains de l'opérateur principal et ceux des autres opérateurs.

4. Situation des montants à envoyer
  * Afficher le montant total à envoyer à chaque opérateur.














    