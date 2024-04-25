<?php
 // Autor: Ademir Luiz do Prado

 //Importar grandes tabelas no XAMPP via Control Panel, Botão Shell ou CMD:
 //# cd c:\xampp\mysql\bin
 //# mysql -u root -p BANCO < "C:/xampp/mysql/bin/TABELA.sql"
  
 // A sessão precisa ser iniciada em cada página diferente
 if (!isset($_SESSION)) session_start();  
 if (!isset($_SESSION['UsuarioNivel'])){

	  session_destroy();
	  header('Location: index.php?erro=<h2>Fill in User and Password to access the system.');
	  exit;
  }  
  if (@$_SESSION['UsuarioNivel'] != 0)
  { 
	//echo $_SESSION['UsuarioNivel'];
	$nivel_necessario = $_SESSION['UsuarioNivel'];
  
	  // Verifica se não há a variável da sessão que identifica o usuário
	  if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel']<$nivel_necessario)) {

		  // Destrói a sessão por segurança
		  session_destroy();
		  // Redireciona o visitante de volta pro login
		  header("Location: index.php?erro=<h2>Fill in User and Password to access the system."); 
		  exit;
	  }
  }
  @$nivel_necessario = $_SESSION['UsuarioNivel'];
  //if ($nivel_necessario == 1 || $nivel_necessario  == 0) {
?>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="style.css"> 
	<meta charset="utf-8">	    
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta name="author" content="Ademir Luiz do Prado">
	<title>G-CoV.2 com OpenStreetMap</title>
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
	<form method="post" name="formLogin" action="cepLocalizar.php">
		<h2 align="center">
		<br>ENTER ONLY NUMBERS FOR THE ZIP CODE:
		<input type="number" id="txtCEP" name="txtCEP" size="8" maxlength = "8" required>
		<input type="submit" value="Check">
		<input type="reset" value="Reset">
		</h2>
	</form>
    </div>
    </div>
  </body>
</html>