<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mobile Money - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center"><h5>Espace Client</h5></div>
                    <div class="card-body">
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>
                        <form action="<?= base_url('/client/doLogin') ?>" method="post">
                            <div class="mb-3">
                                <label for="num_tel" class="form-label">Entrez votre numéro de téléphone</label>
                                <input id="num_tel" type="text" name="num_tel" class="form-control" placeholder="Ex: 0331234567" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
