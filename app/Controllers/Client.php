<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;

class Client extends BaseController
{
    private const LOGIN_ROUTE = '/client/login';
    private const DASHBOARD_ROUTE = '/client/dashboard';

    protected $session;
    protected $clientModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->clientModel = new ClientModel();
        $this->transactionModel = new TransactionModel();
    }

    // --- AUTHENTIFICATION ---
    public function login()
    {
        if ($this->session->has('client_id')) {
            return redirect()->to(self::DASHBOARD_ROUTE);
        }
        return view('client/login');
    }

    public function doLogin()
    {
        $num_tel = trim((string) $this->request->getPost('num_tel'));

        if ($num_tel === '') {
            return $this->redirectToLogin('Veuillez saisir un numéro de téléphone.');
        }
        
        $prefixe = substr($num_tel, 0, 3);
        if (!in_array($prefixe, ['033', '037'], true)) {
            return $this->redirectToLogin('Numéro invalide. Préfixes acceptés : 033 ou 037.');
        }

        $client = $this->clientModel->autoLogin($num_tel);
        
        $this->session->set([
            'client_id' => $client['id'],
            'num_tel'   => $client['num_tel']
        ]);

        return redirect()->to(self::DASHBOARD_ROUTE);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(self::LOGIN_ROUTE);
    }

    // --- ESPACE CLIENT (DASHBOARD) ---
    public function dashboard()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        $client_id = $this->session->get('client_id');
        $data['client'] = $this->clientModel->find($client_id);
        $data['transactions'] = $this->transactionModel->getHistoriqueClient($client_id);

        return view('client/dashboard', $data);
    }

    // --- OPÉRATIONS ---
    public function executerOperation()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        $client_id = (int) $this->session->get('client_id');
        $type_op = (int) $this->request->getPost('type_operation');
        $montant = (float) $this->request->getPost('montant');
        $client = $this->clientModel->find($client_id);

        $validationResponse = $this->validateOperationInput($client, $montant);
        if ($validationResponse !== null) {
            return $validationResponse;
        }

        return $this->processOperation($client_id, $client, $type_op, $montant);
    }

    private function validateOperationInput($client, float $montant)
    {
        if (!$client) {
            return $this->redirectToLogin('Session expirée. Veuillez vous reconnecter.');
        }

        if ($montant <= 0) {
            return $this->redirectBackWithError('Le montant doit être supérieur à 0.');
        }

        return null;
    }

    private function processOperation(int $client_id, array $client, int $type_op, float $montant)
    {
        $frais = $this->transactionModel->calculerFrais($type_op, $montant);
        $response = $this->redirectBackWithError('Type d\'opération invalide.');

        switch ($type_op) {
            case 1:
                $this->clientModel->update($client_id, ['solde' => $client['solde'] + $montant]);
                $this->transactionModel->insert([
                    'client_id' => $client_id,
                    'type_operation_id' => 1,
                    'montant' => $montant,
                    'frais_appliques' => 0
                ]);
                $response = $this->redirectBackWithSuccess('Dépôt réussi !');
                break;

            case 2:
                if ($client['solde'] < ($montant + $frais)) {
                    $response = $this->redirectBackWithError('Solde insuffisant pour couvrir le retrait et les frais (Frais: ' . $frais . ' Ar).');
                    break;
                }

                $this->clientModel->update($client_id, ['solde' => $client['solde'] - ($montant + $frais)]);
                $this->transactionModel->insert([
                    'client_id' => $client_id,
                    'type_operation_id' => 2,
                    'montant' => $montant,
                    'frais_appliques' => $frais
                ]);
                $response = $this->redirectBackWithSuccess('Retrait effectué avec succès !');
                break;

            case 3:
                $response = $this->processTransfer($client_id, $client, $montant, $frais);
                break;

            default:
                break;
        }

        return $response;
    }

    private function processTransfer(int $client_id, array $client, float $montant, float $frais)
    {
        $num_dest = trim((string) $this->request->getPost('num_destinataire'));

        $response = $this->redirectBackWithError('Veuillez saisir le numéro du destinataire.');

        if ($num_dest !== '') {
            if ($num_dest === $client['num_tel']) {
                $response = $this->redirectBackWithError('Impossible de transférer à vous-même.');
            } else {
                $prefixe_dest = substr($num_dest, 0, 3);
                if (!in_array($prefixe_dest, ['033', '037'], true)) {
                    $response = $this->redirectBackWithError('Préfixe du destinataire invalide (033/037).');
                } else {
                    $destinataire = $this->clientModel->where('num_tel', $num_dest)->first();
                    if (!$destinataire) {
                        $response = $this->redirectBackWithError('Le numéro destinataire n\'existe pas dans le système.');
                    } elseif ($client['solde'] < ($montant + $frais)) {
                        $response = $this->redirectBackWithError('Solde insuffisant pour le transfert (Frais: ' . $frais . ' Ar).');
                    } else {
                        $this->clientModel->update($client_id, ['solde' => $client['solde'] - ($montant + $frais)]);
                        $this->clientModel->update($destinataire['id'], ['solde' => $destinataire['solde'] + $montant]);
                        $this->transactionModel->insert([
                            'client_id' => $client_id,
                            'destinataire_id' => $destinataire['id'],
                            'type_operation_id' => 3,
                            'montant' => $montant,
                            'frais_appliques' => $frais
                        ]);

                        $response = $this->redirectBackWithSuccess('Transfert effectué avec succès !');
                    }
                }
            }
        }

        return $response;
    }

    private function isAuthenticated(): bool
    {
        return $this->session->has('client_id');
    }

    private function redirectToLogin(?string $errorMessage = null)
    {
        $redirect = redirect()->to(self::LOGIN_ROUTE);

        if ($errorMessage === null) {
            return $redirect;
        }

        return $redirect->with('error', $errorMessage);
    }

    private function redirectBackWithError(string $message)
    {
        return redirect()->back()->with('error', $message);
    }

    private function redirectBackWithSuccess(string $message)
    {
        return redirect()->back()->with('success', $message);
    }
}
