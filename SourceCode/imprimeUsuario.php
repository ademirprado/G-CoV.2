<?php
// Autor: Ademir Luiz do Prado

if (!isset($_SESSION)) session_start();  
if (!isset($_SESSION['UsuarioNivel']))
{
  session_destroy();
  header('Location: index.php?erro=<h2>Fill in User and Password to access the system.');
  exit;
}
if (@$_SESSION['UsuarioNivel'] != 0)
{ 
//echo $_SESSION['UsuarioNivel'];
$nivel_necessario = $_SESSION['UsuarioNivel'];

  // Verifica se não há a variável da sessão que identifica o usuário
  if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel']<$nivel_necessario)) 
  {
	  // Destrói a sessão por segurança
	  session_destroy();
	  // Redireciona o visitante de volta pro login
	  header("Location: index.php"); exit;
  }
}
@$nivel_necessario = $_SESSION['UsuarioNivel'];
if ($nivel_necessario == 1 || $nivel_necessario  == 0) 
{ // Acesso Total
?>  
	<html>
	  <head>
		<link rel="stylesheet" type="text/css" href="print.css"> 
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	    <meta name="author" content="Ademir Luiz do Prado">
		<title>Users Report</title>
		<script type="text/javascript">
			window.print();
			//window.close(); Só descomente esta linha se tiver o modo kiosk habilitado
		</script>
	  </head>
	  <body>
		<div class="site-content">
		<div class="center" style="width:185mm">
		<center><a href="menu.php"> <img src="imagens\G-CoV2.svg" alt="Click in image for back"></a></center>
		<br>
		<h1><center>
		<p style="font-family:arial">Users Report</center></h1>
		<table>
		  <tr>
			<td><p style="font-family:arial"><strong>#</strong></td>
			<td><p style="font-family:arial"><strong>Name</strong></td>
			<td><p style="font-family:arial"><strong>E-Mail</strong></td>
			<td><p style="font-family:arial"><strong>User</strong></td>
			<td><p style="font-family:arial"><strong>Profile</strong></td>
			<td><p style="font-family:arial"><strong>#</strong></td>
		  </tr>
		  <br>
			<?php
			include "conexao.php";
			$result = $conn->query("SELECT * FROM USUARIO");
			while($aux_query = $result->fetch_assoc()) 
			{
			  echo '<tr>';
			  echo '  <td><p style="font-family:arial">'.$aux_query["id"].'</td>';
			  echo '  <td><p style="font-family:arial">'.$aux_query["nome"].'</td>';
			  echo '  <td><p style="font-family:arial">'.$aux_query["email"].'</td>';
			  echo '  <td><p style="font-family:arial">'.$aux_query["login"].'</td>';
			  echo '  <td><center><p style="font-family:arial">'.$aux_query["perfil"].'</td>';
			  echo '<tr><br>';
			}
			?>
		</table>
		<h5><p style="font-family:arial">Printed on <?php echo date('m.d.Y') . ' - ' . date('H') . 'h' . date('i') . 'm' . date('s') . 's'; ?></h5>
		</p>
		</div>
		</div>
	  </body>
	</html>
<?php
}else{
	// Perfil é diferente de 1=Acesso Total
	header("location:menu.php?erro=<h2>Access Denied to Print Users!");
}
?>