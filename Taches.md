v1
___________________________________________
Binômes:
4328
4107
____________________________________________
-Base:
  -operateurs:
      -config_préfixes
      -types_operations: id, nom(depot, retrait, transfert)
      -baremes_frais: id, type_operation_id(FK), montant_min/max, frais(gestion des tranches modifiables)
      -transactions: id, client_id(/expediteur)(FK), destinataire_id(nullable si depot/retrait), type_operation_id(FK), montant, frais_appliques, date_transaction  
      -clients: id, num_tel, solde, auto_login
__________________________________________
-Dev A: cote client et authentification
 -auto_login: formulaire pour le client(num_tel:033/037), verification base, if num n'existe pas, creation auto avec solde 0 (+equipement session)
 -espace_client(dashboard): affichage du solde actuel, historique des transactions propre à ce num
 -formulaire d'opérations: 
   -depot/retrait: formulaire simple(auto)(mise à jour solde et enregistrement d'action)
   -transfert:formulaire avec num du destinataire(valider prefixe) et montant, déduire(montant+frais) du solde de l'expediteur, ajouter montant au destinataire
__________________________________________
-Dev B:cote operateur et back-end
 -config_prefixes: une vue/action pour lister et ajouter des prefixes
 -gestion_baremes: interface pour voir et modifier les frais par tranche pour les retraits et transferts
 -tableau de bord operateur(statistique):
   -situation des gains: requête SQL(Sum(frais_appliques)) pour voir l'argent total genere par l'operateur via les retraits et transferts
   -situation des comptes clients: liste de tous les clients avec leur num et solde actuel
___________________________________________________
NB: calcul des frais: creer une fonction d'aide















    