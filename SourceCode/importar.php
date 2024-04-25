<?php
// Autor: Ademir Luiz do Prado

// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();  

if (!isset($_SESSION['UsuarioNivel']))
{
  session_destroy();
  header('Location: index.php?erro=<h2>Fill in User and Password to access the system.');
  exit;
}
if (@$_SESSION['UsuarioNivel'] != 0)
{
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
		<link rel="stylesheet" type="text/css" href="style.css"> 
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	    <meta name="author" content="Ademir Luiz do Prado">
	    <title>Import Tests</title>
	  </head>
	  <body>
		<?php
			$logado=$_SESSION['UsuarioNome'];
			$_SESSION['UsuarioNome'] = '';
			include "menu.php";
			$_SESSION['UsuarioNome'] = $logado;
		?> 
		<div class="site-content">
		<div class="center" style="width:185mm">
		<?php
			if(isset($mensagem))
				echo '<H2><div style="color:red">'.$mensagem.'</div></H2><b>';
		?>
		<h1>Import Tests</h1>
		<form method="POST" action="uploadCSV.php" enctype="multipart/form-data">
			<b>Guidelines:<b><br>
			1. CSV file separated by semicolons<br>
			2. File with 19 columns<br>
			3. Contact ademirlp@ufpr.br for model file <br>
			<br>
			<label>Select File:</label><br>
			<input type="file" required name="arquivo"><br><br>	 
			<input type="submit" value="Import">
		</form>
		</div>
		</div>
	  </body>
	</html>
<?php
}
else
{
	// Perfil é diferente de 1=Acesso Total
	header("location:menu.php?erro=<h2>Access Denied to Import Tests!");
}  
?>