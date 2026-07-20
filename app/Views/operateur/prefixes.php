<!DOCTYPE html>
<html>

<head>

<title>Préfixes opérateur</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body>


<div class="container mt-5">


<h1>
Gestion des préfixes
</h1>


<form method="post" action="/operateur/prefixes/add" class="mt-4">


<div class="input-group">


<input 
type="text"
name="prefixe"
class="form-control"
placeholder="Ex: 033">


<button class="btn btn-primary">
Ajouter
</button>


</div>


</form>



<table class="table table-bordered mt-4">


<tr>

<th>
ID
</th>

<th>
Préfixe
</th>

</tr>



<?php foreach($prefixes as $p): ?>

<tr>

<td>
<?= $p['id'] ?>
</td>


<td>
<?= $p['prefixe'] ?>
</td>


</tr>


<?php endforeach; ?>


</table>


</div>


</body>

</html>