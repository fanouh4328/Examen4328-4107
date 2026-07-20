<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table = 'transactions';

    protected $primaryKey = 'id';


    public function getGainsOperateur()
    {
        return $this->selectSum('frais_appliques')
                    ->whereIn('type_operation_id', [2,3])
                    ->first();
    }
}