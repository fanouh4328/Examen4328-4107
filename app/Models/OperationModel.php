<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table = 'transactions';

    protected $primaryKey = 'id';


    // Gains de l'opérateur principal
    public function getGainsOperateur()
    {
        return $this->selectSum('frais_appliques')
                    ->whereIn('type_operation_id', [2,3])
                    ->where('autres_operateurs_id', null)
                    ->first();
    }



    // Gains provenant des autres opérateurs
    public function getGainsAutresOperateurs()
    {
        return $this->select(
                'autres_operateurs.prefixe,
                 SUM(transactions.frais_appliques) as total_gain'
            )
            ->join(
                'autres_operateurs',
                'autres_operateurs.id = transactions.autres_operateurs_id'
            )
            ->whereIn('type_operation_id', [2,3])
            ->groupBy('autres_operateurs.id')
            ->findAll();
    }



    // Montants à envoyer à chaque opérateur
    public function getMontantsParOperateur()
    {
        return $this->select(
                'autres_operateurs.prefixe,
                 SUM(transactions.montant) as montant_total'
            )
            ->join(
                'autres_operateurs',
                'autres_operateurs.id = transactions.autres_operateurs_id'
            )
            ->groupBy('autres_operateurs.id')
            ->findAll();
    }

}