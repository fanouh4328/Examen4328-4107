<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\PrefixeModel; // Supposons que Développeur B a créé ce modèle pour les préfixes valides
use App\Models\BaremeModel;   // Pour récupérer les frais par tranche

class Transaction extends BaseController
{
    public function processTransfert()
    {
        $session = session();
        $clientModel = new ClientModel();
        $transModel = new TransactionModel();
        
        $expediteurId = $session->get('client_id');
        $expediteur = $clientModel->find($expediteurId);

        // 1. Récupération et nettoyage des inputs
        $numerosRaw = $this->request->getPost('numeros');
        $montantTotal = floatval($this->request->getPost('montant_total'));
        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') == '1';

        // Découper la chaîne par les virgules et enlever les espaces vides
        $listeNumeros = array_filter(array_map('trim', explode(',', $numerosRaw)));
        $nombreDestinataires = count($listeNumeros);

        if ($nombreDestinataires === 0 || $montantTotal <= 0) {
            return redirect()->back()->with('error', 'Données invalides.');
        }

        // 2. Division du montant par numéro
        $montantParPersonne = $montantTotal / $nombreDestinataires;

        // On va d'abord simuler le coût total pour vérifier si l'expéditeur a assez d'argent
        $transactionsAPlanifier = [];
        $debitTotalExpediteur = 0;

        foreach ($listeNumeros as $numeroDest) {
            // A. Vérifier le type d'opérateur (Interne ou Externe)
            // Code simplifié : à adapter selon les fonctions créées par ton binôme
            $estExterne = $this->verifierSiOperateurExterne($numeroDest); 

            // B. Calculer les frais de transfert de base pour ce montant
            $fraisTransfertBase = $this->calculerFrais('transfert', $montantParPersonne);

            // C. V2 : Si externe, appliquer le % de commission en plus
            if ($estExterne) {
                $pourcentageCommission = 0.05; // Exemple : 5% en plus pour les autres opérateurs. À récupérer en BDD.
                $fraisTransfertBase += ($montantParPersonne * $pourcentageCommission);
            }

            // D. V2 Optionnel : Inclure les frais de retrait
            $fraisRetraitFutur = 0;
            if ($inclureFraisRetrait) {
                // On calcule ce que le destinataire paierait s'il retirait cette somme
                $fraisRetraitFutur = $this->calculerFrais('retrait', $montantParPersonne);
            }

            // Le montant réel qui sera stocké / envoyé
            $montantFinalEnvoye = $montantParPersonne;
            
            if ($inclureFraisRetrait) {
                // Si on inclut les frais, le destinataire doit recevoir (Montant + Frais Retrait) 
                // pour qu'une fois le retrait effectué, il lui reste le montant initial net.
                $montantFinalEnvoye = $montantParPersonne + $fraisRetraitFutur;
            }

            // Calcul du coût total pour l'expéditeur pour CE numéro
            $coutPourCeNumero = $montantFinalEnvoye + $fraisTransfertBase;
            $debitTotalExpediteur += $coutPourCeNumero;

            // Enregistrer les détails pour l'exécution groupée
            $transactionsAPlanifier[] = [
                'numero' => $numeroDest,
                'montant_a_recevoir' => $montantFinalEnvoye,
                'frais_operateur' => $fraisTransfertBase,
                'est_externe' => $estExterne
            ];
        }

        // 3. Vérification du solde de l'expéditeur
        if ($expediteur['solde'] < $debitTotalExpediteur) {
            return redirect()->back()->with('error', 'Solde insuffisant. Il vous faut un total de ' . $debitTotalExpediteur . ' Ar (frais inclus).');
        }

        // 4. Exécution des transactions (Mise à jour BDD)
        $db = \Config\Database::connect();
        $db->transStart(); // Utilisation d'une transaction SQL pour éviter les bugs si un numéro échoue

        // Débiter l'expéditeur une seule fois du total global
        $clientModel->update($expediteurId, [
            'solde' => $expediteur['solde'] - $debitTotalExpediteur
        ]);

        foreach ($transactionsAPlanifier as $tx) {
            // Trouver ou créer le destinataire si c'est un numéro interne
            // Si c'est un numéro externe, on suppose qu'il n'a pas de compte local chez nous (ou selon spécifications du sujet)
            $destinataire = $clientModel->where('numero_telephone', $tx['numero'])->first();
            
            if (!$destinataire && !$tx['est_externe']) {
                // Création automatique si interne inconnu
                $idDest = $clientModel->insert(['numero_telephone' => $tx['numero'], 'solde' => 0]);
                $destinataire = $clientModel->find($idDest);
            }

            // Créditer le destinataire s'il est chez nous
            if ($destinataire) {
                $clientModel->update($destinataire['id'], [
                    'solde' => $destinataire['solde'] + $tx['montant_a_recevoir']
                ]);
            }

            // Enregistrer la transaction dans l'historique
            $transModel->insert([
                'expediteur_id'   => $expediteurId,
                'destinataire_num'=> $tx['numero'],
                'type_operation'  => 'transfert',
                'montant'         => $tx['montant_a_recevoir'],
                'frais_appliques' => $tx['frais_operateur'],
                'est_externe'     => $tx['est_externe'] ? 1 : 0,
                'date_creation'   => date('Y-m-d H:i:s')
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors du transfert.');
        }

        return redirect()->to('/dashboard')->with('success', 'Transfert(s) effectué(s) avec succès !');
    }

    // --- FONCTIONS UTILS (À relier avec les modèles du dév B) ---

    private function verifierSiOperateurExterne($numero)
    {
        // Exemple simple : si ça commence par 032 ou 031 c'est externe
        $prefixesExternes = ['032', '031'];
        foreach ($prefixesExternes as $pref) {
            if (strpos($numero, $pref) === 0) return true;
        }
        return false;
    }

    private function calculerFrais($type, $montant)
    {
        // Ici, tu fais ta requête SQL sur ton barème par tranche
        // Exemple statique en attendant la BDD pour ne pas bloquer le code :
        if ($type === 'transfert') return 500; // Remplacer par la logique réelle de tranche
        if ($type === 'retrait') return 1000;  // Remplacer par la logique réelle de tranche
        return 0;
    }
}