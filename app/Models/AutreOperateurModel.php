<?php

namespace App\Models;

use CodeIgniter\Model;

class AutreOperateurModel extends Model
{
    protected $table = 'autres_operateurs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'prefixe',
        'commission'
    ];

    public function getOperateurs()
    {
        return $this->findAll();
    }
}