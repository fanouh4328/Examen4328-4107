<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Auth extends BaseController
{
    public function login()
    {
        $session = session();
        $clientModel = new ClientModel();

        // 1. Récupérer le numéro tapé dans le formulaire
        $numero = $this->request->getPost('numero_telephone');

        if (empty($numero)) {
            return redirect()->back()->with('error', 'Veuillez entrer un numéro de téléphone.');
        }

        // 2. Vérification des préfixes valides (ex: 033 ou 037)
        $prefixeValide = false;
        $prefixesAutorises = ['033', '037']; // Tu peux aussi les récupérer depuis ta BDD si tu as le temps

        foreach ($prefixesAutorises as $pref) {
            if (strpos($numero, $pref) === 0) {
                $prefixeValide = true;
                break;
            }
        }

        if (!$prefixeValide) {
            return redirect()->back()->with('error', 'Le numéro doit commencer par un préfixe valide (033 ou 037).');
        }

        // 3. Chercher si le client existe déjà en BDD
        $client = $clientModel->where('numero_telephone', $numero)->first();

        // 4. S'il n'existe pas, on le crée automatiquement avec un solde à 0
        if (!$client) {
            $dataClient = [
                'numero_telephone' => $numero,
                'solde'            => 0 // Solde initial à 0
            ];
            
            $clientId = $clientModel->insert($dataClient);
            
            // Récupérer les infos du client fraîchement créé
            $client = $clientModel->find($clientId);
        }

        // 5. Connecter le client en Session
        $session->set([
            'client_id' => $client['id'],
            'numero_telephone' => $client['numero_telephone'],
            'isLoggedIn' => true
        ]);

        // 6. Rediriger vers l'espace client (Dashboard)
        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
