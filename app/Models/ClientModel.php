<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'num_tel',
        'solde'
    ];


    public function getClients()
    {
        return $this->findAll();
    }
}