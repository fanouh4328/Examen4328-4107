<!DOCTYPE html>
<html lang="fr">


<head>

<title>Gestion des frais</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>



<body>

<?php $baremes = $baremes ?? []; ?>


<div class="container mt-5">


<h1>
Gestion des barèmes
</h1>



<table class="table table-bordered">


<tr>

<th>
Type opération
</th>

<th>
Montant min
</th>

<th>
Montant max
</th>

<th>
Frais
</th>

<th>
Action
</th>


</tr>




<?php if (!isset($baremes) || !is_array($baremes)) {
    $baremes = [];
}

foreach($baremes as $b): ?>


<tr>


<form method="post" action="/operateur/baremes/update/<?= $b['id'] ?>">


<td>
<?= $b['type_operation_id'] ?>
</td>


<td>
<?= $b['montant_min'] ?>
</td>


<td>
<?= $b['montant_max'] ?>
</td>


<td>

<input
class="form-control"
name="frais"
value="<?= $b['frais'] ?>">


</td>


<td>

<button class="btn btn-success">
Modifier
</button>


</td>


</form>


</tr>



<?php endforeach; ?>



</table>



</div>


</body>


</html>
