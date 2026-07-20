<!DOCTYPE html>
<html>


<head>

<title>Gestion des frais</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>



<body>


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