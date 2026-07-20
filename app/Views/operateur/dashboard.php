<!DOCTYPE html>
<html>
<head>

    <title>Tableau de bord opérateur</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            background-color:#f4f6f9;
            margin:40px;
        }


        .container{
            width:90%;
            margin:auto;
        }


        h1{
            text-align:center;
            color:#2c3e50;
            margin-bottom:30px;
        }


        .cards{
            display:flex;
            gap:20px;
            flex-wrap:wrap;
        }


        .card{
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
            flex:1;
            min-width:280px;
        }


        .card h2{
            color:#3498db;
            font-size:20px;
        }


        .montant{
            font-size:28px;
            font-weight:bold;
            color:#27ae60;
        }


        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }


        th{
            background:#3498db;
            color:white;
            padding:10px;
        }


        td{
            padding:10px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }


        tr:hover{
            background:#f2f2f2;
        }


    </style>

</head>


<body>


<div class="container">


<h1>Tableau de bord opérateur</h1>


<div class="cards">


    <!-- Gain opérateur principal -->

    <div class="card">

        <h2>Gain opérateur</h2>

        <p class="montant">

            <?= $gain['frais_appliques'] ?? 0 ?> Ar

        </p>

        <p>
            Total des frais générés par les retraits et transferts.
        </p>

    </div>



    <!-- Gains autres opérateurs -->

    <div class="card">

        <h2>Gains autres opérateurs</h2>


        <table>

            <tr>
                <th>Préfixe</th>
                <th>Gain</th>
            </tr>


            <?php foreach($gains_autres_operateurs as $op): ?>

            <tr>

                <td>
                    <?= $op['prefixe'] ?>
                </td>

                <td>
                    <?= $op['total_gain'] ?> Ar
                </td>

            </tr>


            <?php endforeach; ?>


        </table>


    </div>



    <!-- Montants à envoyer -->

    <div class="card">


        <h2>Montants à envoyer</h2>


        <table>


            <tr>
                <th>Opérateur</th>
                <th>Montant</th>
            </tr>


            <?php foreach($montants_operateurs as $op): ?>


            <tr>

                <td>
                    <?= $op['prefixe'] ?>
                </td>

                <td>
                    <?= $op['montant_total'] ?> Ar
                </td>

            </tr>


            <?php endforeach; ?>


        </table>


    </div>


</div>



<br><br>



<!-- Situation clients -->


<div class="card">


<h2>Situation des comptes clients</h2>


<table>


<tr>

<th>Numéro</th>

<th>Solde</th>

</tr>



<?php foreach($clients as $client): ?>

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

        <?php foreach($clients ?? [] as $client): ?>

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
<tr>

<td>
<?= $client['num_tel'] ?>
</td>


<td>
<?= $client['solde'] ?> Ar
</td>


</tr>


<?php endforeach; ?>


</table>


</div>


</div>


</body>

</html>