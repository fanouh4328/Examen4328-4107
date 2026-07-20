<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Opérateur</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h1 class="mb-4">
        Tableau de bord opérateur
    </h1>


    <div class="row">


        <!-- Gain opérateur -->
        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5 class="card-title">
                        Gains opérateur
                    </h5>

                    <p class="fs-3">
                        <?= $gain['frais_appliques'] ?? 0 ?> Ar
                    </p>

                </div>

            </div>

        </div>


    </div>



    <h2 class="mt-5">
        Situation des comptes clients
    </h2>


    <table class="table table-bordered mt-3">

        <thead class="table-dark">

            <tr>
                <th>
                    Numéro téléphone
                </th>

                <th>
                    Solde
                </th>

            </tr>

        </thead>


        <tbody>

        <?php foreach($clients as $client): ?>

            <tr>

                <td>
                    <?= $client['num_tel'] ?>
                </td>


                <td>
                    <?= $client['solde'] ?> Ar
                </td>

            </tr>


        <?php endforeach; ?>


        </tbody>


    </table>


</div>


</body>
</html>