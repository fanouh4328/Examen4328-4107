<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\FraisModel;
use App\Models\ClientModel;
use App\Models\OperationModel;
use App\Models\AutreOperateurModel;

class Operateur extends BaseController
{

  // Tableau de bord opérateur
    public function dashboard()
    {
        $operationModel = new OperationModel();
        $clientModel = new ClientModel();


        $data = [
        // Gain de l'opérateur principal
        'gain' => $operationModel->getGainsOperateur(),

        // Gains liés aux autres opérateurs
        'gains_autres_operateurs' => $operationModel->getGainsAutresOperateurs(),

        // Montants à envoyer aux autres opérateurs
        'montants_operateurs' => $operationModel->getMontantsParOperateur(),

        // Liste des clients
        'clients' => $clientModel->getClients()

    ];


    return view('operateur/dashboard', $data);
}



    // Gestion des préfixes
    public function prefixes()
    {
        $prefixeModel = new PrefixeModel();


        $data = [
            'prefixes' => $prefixeModel->getPrefixes()
        ];


        return view('operateur/prefixes', $data);
    }



    // Ajouter un préfixe
    public function ajouterPrefixe()
    {
        $prefixeModel = new PrefixeModel();


        $prefixeModel->insert([
            'prefixe' => $this->request->getPost('prefixe')
        ]);


        return redirect()->to('/operateur/prefixes');
    }



    // Gestion des barèmes
    public function baremes()
    {
        $fraisModel = new FraisModel();


        $data = [
            'baremes' => $fraisModel->getBaremes()
        ];


        return view('operateur/baremes', $data);
    }



    // Modifier un frais
    public function modifierFrais($id)
    {
        $fraisModel = new FraisModel();


        $fraisModel->update($id, [
            'frais' => $this->request->getPost('frais')
        ]);


        return redirect()->to('/operateur/baremes');
    }

    // Gestion des autres opérateurs
    public function autresOperateurs()
    {
        $model = new AutreOperateurModel();

        $data = [
            'operateurs' => $model->findAll()
        ];

    return view('operateur/autres_operateurs', $data);
    }


    // Ajouter un autre opérateur
    public function ajouterOperateur()
    {
        $model = new AutreOperateurModel();

        $model->insert([
            'prefixe' => $this->request->getPost('prefixe'),
            'commission' => $this->request->getPost('commission')
        ]);

    return redirect()->to('/operateur/autres-operateurs');
    }

  

}