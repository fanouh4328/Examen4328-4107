<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table = 'baremes_fairs';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'type_operation_id',
        'montant_min',
        'montant_max',
        'frais'
    ];


    public function getBaremes()
    {
        return $this->findAll();
    }


    public function modifierFrais($id, $frais)
    {
        return $this->update($id, [
            'frais' => $frais
        ]);
    }
}