<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table = 'prefixe_operateurs';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'prefixe'
    ];


    public function getPrefixes()
    {
        return $this->findAll();
    }


    public function ajouterPrefixe($prefixe)
    {
        return $this->insert([
            'prefixe' => $prefixe
        ]);
    }
}