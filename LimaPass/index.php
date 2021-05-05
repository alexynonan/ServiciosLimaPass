<?php
	include("cn/conexion.php");
	$usuario = "SELECT * FROM usuario";
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <h1>Hello, world!</h1>

    <table class="table table-hover">
		<thead>
		<tr>
		  <th scope="col">id</th>
		  <th scope="col">Nombre</th>
		  <th scope="col">Apellido</th>
		  <th scope="col">Correo</th>
		  <th scope="col">Numero</th>
		  <th scope="col">Dni</th>
		</tr>
		</thead>
		<?php $resultado = mysqli_query($conexion, $usuario);
			while ($row = mysqli_fetch_assoc($resultado)) { ?>
		<tbody>
		<tr>
		  <th scope="row"><?php echo $row["id"];?></th>
		  <td><?php echo $row["nombre"];?></td>
		  <td><?php echo $row["apellido"];?></td>
		  <td><?php echo $row["correo"];?></td>
		  <td><?php echo $row["numero"];?></td>
		  <td><?php echo $row["dni"];?></td>	  
		</tr>
		</tbody>
		<?php } mysqli_free_result($resultado);?>
	</table>

	

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  </body>
</html>
