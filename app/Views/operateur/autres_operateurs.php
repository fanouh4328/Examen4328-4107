<!DOCTYPE html>
<html>
<head>
    <title>Configuration des autres opérateurs</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 40px;
        }

        .container{
            width: 80%;
            margin: auto;
        }

        h1{
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .card{
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        label{
            font-weight: bold;
        }

        input{
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        button{
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover{
            background-color: #2980b9;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        table th{
            background-color: #3498db;
            color: white;
            padding: 12px;
        }

        table td{
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #dddddd;
        }

        table tr:hover{
            background-color: #f2f2f2;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Configuration des autres opérateurs</h1>


    <div class="card">

        <h2>Ajouter un opérateur</h2>

        <form action="<?= base_url('operateur/ajouter-operateur') ?>" method="post">

            <label>Préfixe</label>
            <input type="text"
                   name="prefixe"
                   placeholder="Exemple : 031"
                   required>

            <label>Commission supplémentaire (%)</label>
            <input type="number"
                   name="commission"
                   min="0"
                   step="1"
                   placeholder="Exemple : 5"
                   required>

            <button type="submit">
                Ajouter
            </button>

        </form>

    </div>


    <div class="card">

        <h2>Liste des autres opérateurs</h2>

        <table>

            <tr>
                <th>ID</th>
                <th>Préfixe</th>
                <th>Commission (%)</th>
            </tr>

            <?php foreach ($operateurs as $operateur): ?>

                <tr>
                    <td><?= $operateur['id']; ?></td>
                    <td><?= $operateur['prefixe']; ?></td>
                    <td><?= $operateur['commission']; ?> %</td>
                </tr>

            <?php endforeach; ?>

        </table>

    </div>

</div>

</body>
</html>