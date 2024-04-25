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
//echo $_SESSION['UsuarioNivel'];
$nivel_necessario = $_SESSION['UsuarioNivel'];

  // Verifica se não há a variável da sessão que identifica o usuário
  if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel']<$nivel_necessario)) {
	  // Destrói a sessão por segurança
	  session_destroy();
	  // Redireciona o visitante de volta pro login
	  header("Location: index.php"); exit;
  }
}
?>
<html>
	<head>
		<title>Credits</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta charset="utf-8"/>	    
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<meta name="author" content="Ademir Luiz do Prado">
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
			<h1>CREDITS</h1>
			<ul>
				<b>
				<li><a href="http://lattes.cnpq.br/6998361386483022" target="_blank">Ademir Luiz do Prado</a></li>
				<li><a href="http://lattes.cnpq.br/8472361065562513" target="_blank">Waldemar Volanski</a></li>
				<li><a href="http://lattes.cnpq.br/7026587564783077" target="_blank">Liana Signorini</a></li>
				<li><a href="http://lattes.cnpq.br/9897630183923653" target="_blank">Glaucio Valdameri</a></li>
				<li><a href="http://lattes.cnpq.br/2041345461058971" target="_blank">Fabiane Gomes de Moraes Rego</a></li>
				<li><a href="http://lattes.cnpq.br/8988774918002313" target="_blank">Geraldo Picheth</a></li>
			</ul>
			<br>
			For information click or touch the corresponding name.</b>
	    </div>
	    </div>
	</body>
</html>