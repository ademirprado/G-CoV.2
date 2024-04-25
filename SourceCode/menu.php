<?php
  // Autor: Ademir Luiz do Prado

  // A sessão precisa ser iniciada em cada página diferente
 if (!isset($_SESSION)) session_start();  
 if (!isset($_SESSION['UsuarioNivel'])){
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
?>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">		
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta name="author" content="Ademir Luiz do Prado">
  </head>
  <body>
  <div class="site-content">
    <div class="center" style="width:185mm">
    <center><img src="imagens\G-CoV2.svg"></center>
    <div class="menu">
    <ul class="menu-list">
    <li>
      <a href="#">Manage</a>
       <ul class="sub-menu">
		<li><a href="usuario.php">Users</a></li>
		<li><a href="imprimeUsuario.php">Print Users</a></li>
		<li><a href="csv.php">Export Data</a></li>
		<li><a href="importar.php">Import Data</a></li>
       </ul>
    </li>
    <li>
	  <a href="#">Graphics</a>
	   <ul class="sub-menu">
		<li><a href="graficoColunas.php">Columns</a></li>
		<li><a href="histograma.php">Histogram</a></li>
		<li><a href="graficoLinhas.php">Line</a></li>		
       </ul>
	</li>
	<li>
	  <a href="#">Geolocation</a>
	   <ul class="sub-menu">
		<li><a href="covid.php">G-CoV.2</a></li>
		<li><a href="estatistica.php">Statistics</a></li>
		<li><a href="cep.php">ChecK Zip Code</a></li>				
	   </ul>
	</li>
	<li>
	  <a href="#">Inform</a>
       <ul class="sub-menu">
		<li><a href="https://cpdm.ufpr.br/covid" target="_blank">COVID-19</a></li>
	    <li><a href="https://cpdm.ufpr.br/contato/" target="_blank">Contact</a></li>
		<li><a href="creditos.php">Credits</a></li>
		<li><a href="http://lattes.cnpq.br/6998361386483022" target="_blank">Developer</a></li>
	   </ul>
	</li>
	<li><a href="logout.php">Exit</a></li>
	</ul>
	</div>
	<?php
	  //Exibir mensagem de erro caso ocorra
	  if (isset($_GET["erro"]))
		{
		  if (!isset($_SESSION)) session_start();
			echo "<font color='blue'>".$_SESSION['UsuarioNome'];
			$erro = $_GET["erro"];
			echo "<h2><font color='red'>$erro</font></center><font color='black'>";
		}else{
			if ($_SESSION['UsuarioNome'] <> '')
			{?>
				<p><h2>Welcome, <?php
				if (!isset($_SESSION)) session_start(); 
				echo "<font color='blue'>".@$_SESSION['UsuarioNome']."</font>!</p>";
			}?>
	<?php }?>
	</div>
  </div>
  </body>
</html>