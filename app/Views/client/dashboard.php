<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Espace Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php $client = $client ?? ['id' => null, 'num_tel' => '', 'solde' => 0]; ?>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Mobile Money</span>
            <span class="navbar-text text-white">Numéro : <strong><?= esc($client['num_tel'] ?? '') ?></strong></span>
            <a href="<?= base_url('/client/logout') ?>" class="btn btn-danger btn-sm">Déconnexion</a>
        </div>
    </nav>

    <div class="container">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php elseif(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-5">
                <div class="card text-white bg-success mb-4 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase">Solde Actuel</h6>
                        <h2 class="display-6 font-weight-bold"><?= isset($client['solde']) ? number_format($client['solde'], 2, ',', ' ') : '0,00' ?> Ar</h2>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">Effectuer une opération</div>
                    <div class="card-body">
                        <form action="<?= base_url('/client/executerOperation') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="type_op_select" class="form-label">Type d'opération</label>
                                <select name="type_operation" id="type_op_select" class="form-select" onchange="toggleDestField()" required>
                                    <option value="1">Dépôt (Auto)</option>
                                    <option value="2">Retrait (Auto)</option>
                                    <option value="3">Transfert</option>
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="destinataire_block">
                                <label for="num_destinataire" class="form-label">Numéro du Destinataire (033 / 037)</label>
                                <input type="text" name="num_destinataire" class="form-control" placeholder="Ex: 037... ">
                            </div>

                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant (Ar)</label>
                                <input type="number" name="montant" class="form-control" min="100" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Valider l'opération</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Historique de vos transactions</div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Frais</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($transactions)): ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">Aucune transaction effectuée.</td></tr>
                                <?php else: ?>
                                    <?php foreach($transactions as $t): ?>
                                        <?php
                                            $badgeClass = 'info';
                                            if ($t['type_operation_id'] == 1) {
                                                $badgeClass = 'success';
                                            } elseif ($t['type_operation_id'] == 2) {
                                                $badgeClass = 'warning';
                                            }
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($t['date_transaction'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= ucfirst(esc($t['type_nom'])) ?>
                                                </span>
                                            </td>
                                            <td><strong><?= number_format($t['montant'], 0, '', ' ') ?> Ar</strong></td>
                                            <td class="text-danger"><?= $t['frais_appliques'] > 0 ? number_format($t['frais_appliques'], 0, '', ' ').' Ar' : '-' ?></td>
                                            <td>
                                                <?php if($t['type_operation_id'] == 3): ?>
                                                    <?= isset($client['id']) && $t['client_id'] == $client['id'] ? 'Vers: '.esc($t['destinataire']) : 'De: '.esc($t['expediteur']) ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDestField() {
            var select = document.getElementById('type_op_select');
            var destBlock = document.getElementById('destinataire_block');
            if(select.value == "3") {
                destBlock.classList.remove('d-none');
            } else {
                destBlock.classList.add('d-none');
            }
        }
    </script>
</body>
</html>