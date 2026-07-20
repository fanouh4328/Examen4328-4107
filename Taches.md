 # Livraison Version 1 (v1)
___________________________________________
# Binômes:
4328
4107
____________________________________________
## Base:
  -operateurs:
      -config_préfixes
      -types_operations: id, nom(depot, retrait, transfert)
      -baremes_frais: id, type_operation_id(FK), montant_min/max, frais(gestion des tranches modifiables)
      -transactions: id, client_id(/expediteur)(FK), destinataire_id(nullable si depot/retrait), type_operation_id(FK), montant, frais_appliques, date_transaction  
      -clients: id, num_tel, solde, auto_login
__________________________________________
## Dev A: cote client et authentification
 -auto_login: formulaire pour le client(num_tel:033/037), verification base, if num n'existe pas, creation auto avec solde 0 (+equipement session)
 -espace_client(dashboard): affichage du solde actuel, historique des transactions propre à ce num
 -formulaire d'opérations: 
   -depot/retrait: formulaire simple(auto)(mise à jour solde et enregistrement d'action)
   -transfert:formulaire avec num du destinataire(valider prefixe) et montant, déduire(montant+frais) du solde de l'expediteur, ajouter montant au destinataire
__________________________________________
## Développeur B : Partie opérateur/backend
 -config_prefixes: une vue/action pour lister et ajouter des prefixes
 -gestion_baremes: interface pour voir et modifier les frais par tranche pour les retraits et transferts
 -tableau de bord operateur(statistique):
   -situation des gains: requête SQL(Sum(frais_appliques)) pour voir l'argent total genere par l'operateur via les retraits et transferts
   -situation des comptes clients: liste de tous les clients avec leur num et solde actuel

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
   -git workflow















    