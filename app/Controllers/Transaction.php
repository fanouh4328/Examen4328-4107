<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;

class Transaction extends BaseController
{
    public function processTransfert()
    {
        $session = session();
        $clientModel = new ClientModel();
        $transModel = new TransactionModel();
        
        $expediteurId = $session->get('client_id');
        $expediteur = $clientModel->find($expediteurId);

        if (!$expediteur) {
            return redirect()->to('/client/login')->with('error', 'Session expirée.');
        }

        // 1. Récupération des données
        $numerosRaw = $this->request->getPost('numeros');
        $montantTotal = floatval($this->request->getPost('montant_total'));
        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') == '1';

        $listeNumeros = array_filter(array_map('trim', explode(',', $numerosRaw)));
        $nombreDestinataires = count($listeNumeros);

        if ($nombreDestinataires === 0 || $montantTotal <= 0) {
            return redirect()->back()->with('error', 'Données invalides.');
        }

        // 2. Division du montant par numéro
        $montantParPersonne = $montantTotal / $nombreDestinataires;
        $transactionsAPlanifier = [];
        $debitTotalExpediteur = 0;

        foreach ($listeNumeros as $numeroDest) {
            $estExterne = $this->verifierSiOperateurExterne($numeroDest); 

            // Calcul des frais de transfert (type_operation_id = 3)
            $fraisTransfertBase = $transModel->calculerFrais(3, $montantParPersonne);

            if ($estExterne) {
                $pourcentageCommission = 0.05; // 5% de commission pour autres opérateurs
                $fraisTransfertBase += ($montantParPersonne * $pourcentageCommission);
            }

            $fraisRetraitFutur = 0;
            if ($inclureFraisRetrait) {
                // Frais de retrait (type_operation_id = 2)
                $fraisRetraitFutur = $transModel->calculerFrais(2, $montantParPersonne);
            }

            $montantFinalEnvoye = $montantParPersonne;
            if ($inclureFraisRetrait) {
                $montantFinalEnvoye = $montantParPersonne + $fraisRetraitFutur;
            }

            $coutPourCeNumero = $montantFinalEnvoye + $fraisTransfertBase;
            $debitTotalExpediteur += $coutPourCeNumero;

            $transactionsAPlanifier[] = [
                'numero' => $numeroDest,
                'montant_a_recevoir' => $montantFinalEnvoye,
                'frais_operateur' => $fraisTransfertBase,
                'est_externe' => $estExterne
            ];
        }

        // 3. Vérification du solde
        if ($expediteur['solde'] < $debitTotalExpediteur) {
            return redirect()->back()->with('error', 'Solde insuffisant. Il vous faut un total de ' . number_format($debitTotalExpediteur, 2, ',', ' ') . ' Ar.');
        }

        // 4. Exécution SQL
        $db = \Config\Database::connect();
        $db->transStart();

        $clientModel->update($expediteurId, [
            'solde' => $expediteur['solde'] - $debitTotalExpediteur
        ]);

        foreach ($transactionsAPlanifier as $tx) {
            // Utilisation correcte du champ 'num_tel'
            $destinataire = $clientModel->where('num_tel', $tx['numero'])->first();
            
            if (!$destinataire && !$tx['est_externe']) {
                $idDest = $clientModel->insert(['num_tel' => $tx['numero'], 'solde' => 0]);
                $destinataire = $clientModel->find($idDest);
            }

            if ($destinataire) {
                $clientModel->update($destinataire['id'], [
                    'solde' => $destinataire['solde'] + $tx['montant_a_recevoir']
                ]);
            }

            // Insertion avec les noms de colonnes exacts de la BDD
            $transModel->insert([
                'client_id'        => $expediteurId,
                'destinataire_id'  => $destinataire ? $destinataire['id'] : null,
                'type_operation_id'=> 3,
                'montant'          => $tx['montant_a_recevoir'],
                'frais_appliques'  => $tx['frais_operateur'],
                'date_transaction' => date('Y-m-d H:i:s')
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors du transfert.');
        }

        return redirect()->to('/client/dashboard')->with('success', 'Transfert(s) effectué(s) avec succès !');
    }

    private function verifierSiOperateurExterne($numero)
    {
        $prefixesExternes = ['032', '034', '038'];
        foreach ($prefixesExternes as $pref) {
            if (strpos($numero, $pref) === 0) return true;
        }
        return false;
    }
}