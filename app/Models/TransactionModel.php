<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['client_id', 'destinataire_id', 'type_operation_id', 'montant', 'frais_appliques', 'date_transaction'];

    /**
     * NB : Calcul des frais (Coeur du système)
     * Fait un SELECT frais FROM baremes_frais WHERE type_operation_id = X AND montant BETWEEN ...
     */
    public function calculerFrais($type_operation_id, $montant)
    {
        if ($type_operation_id == 1) {
            return 0;
        }

        $db = \Config\Database::connect();
        $builder = $db->table('baremes_frais');

        $result = $builder->where('type_operation_id', $type_operation_id)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->get()
            ->getRowArray();

        return $result ? (float)$result['frais'] : 0;
    }

    /**
     * Récupère l'historique complet d'un client (envoi et réception)
     */
    public function getHistoriqueClient($client_id)
    {
        return $this->select('transactions.*, t.nom as type_nom, c.num_tel as expéditeur, d.num_tel as destinataire')
            ->join('types_operations t', 't.id = transactions.type_operation_id')
            ->join('clients c', 'c.id = transactions.client_id')
            ->join('clients d', 'd.id = transactions.destinataire_id', 'left')
            ->groupStart()
            ->where('transactions.client_id', $client_id)
            ->orWhere('transactions.frais_operateur')
            ->orWhere('transactions.destinataire_id', $client_id)
            ->groupEnd()
            ->orderBy('transactions.date_transaction', 'DESC')
            ->findAll();
    }
}
