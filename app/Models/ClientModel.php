<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['num_tel', 'solde'];
    protected $returnType       = 'array';

    /**
     * Gère l'auto-login : cherche le numéro, le crée s'il n'existe pas.
     */
    public function autoLogin($client_id)
    {
        $client = $this->where('num_tel', $client_id)->first();

        if (!$client) {
            $id = $this->insert([
                'num_tel' => $client_id,
                'solde'   => 0.0
            ]);
            return $this->find($id);
        }

        return $client;
    }
}
