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
// Conexão com o Banco de Dados
include 'conexao.php';
?>
<html lang="pt-br">
  <head>
	   <link rel="stylesheet" type="text/css" href="style.css"> 
	   <meta charset="utf-8">	    
	   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	   <meta name="author" content="Ademir Luiz do Prado">
       <title>G-CoV.2: Statistics</title>
		<style>
			table, th, td {
			border: 1px solid white;
			border-collapse: collapse;
			border-spacing: 10px;
			padding-top: 10px;
			padding-bottom: 10px;
			padding-left: 10px;
			padding-right: 10px;
			}
			th, td {
			color: white;
			background-color: black;
		  }
		</style>
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
		<table width=700mm>
		  <tr>
			<td colspan="9"><center><h2>DESCRIPTIVE STATISTICS OF THE AGE FEATURE</h2></center></td>
		  </tr>		
		  <tr>
			<td><strong>Institution</strong></td>
			<td><strong>Sex</strong></td>
			<td><strong>Tests</strong></td>
			<td><strong>Minor</strong></td>
			<td><strong>Major</strong></td>
			<td><strong>Median</strong></td>
			<td><strong>Average</strong></td>
			<td><strong><center>Standard<br>Deviation</center></strong></td>
			<td><strong>Variance</strong></td>		
		  </tr>
		  <br>
		<?php
		$query = 'SELECT INSTITUICAO as Instituicao, SEXO as Genero, COUNT(idade) as Exames, MIN(idade) as Menor, MAX(idade) as Maior, AVG(idade) as Media, STDDEV(idade) as Desvio, VARIANCE(idade) as Variancia FROM exames GROUP BY INSTITUICAO, SEXO;';
		$result = mysqli_query($conn, $query);
		while($aux_query = mysqli_fetch_assoc($result)) 
		{
		  echo '<tr>';
		  echo '  <td>'.$aux_query['Instituicao'].'</td>';
		  echo '  <td><center>'.$aux_query["Genero"].'</td>';
		  echo '  <td><center>'.$aux_query["Exames"].'</td>';
		  echo '  <td><center>'.$aux_query["Menor"].'</td>';
		  echo '  <td><center>'.$aux_query["Maior"].'</td>';
		  // Cálculo da Mediana --------------------------------------------
		  if($aux_query["Instituicao"]=="HC" and $aux_query["Genero"] == "F")
		  {
			 $mediana = $conn->query("SET @rowindex := -1");
			 $mediana = $conn->query("SELECT AVG(e.idade) as Median FROM
				(SELECT @rowindex:=@rowindex + 1 AS rowindex, exames.idade AS idade
				FROM exames where instituicao='HC' and sexo='F'
				ORDER BY exames.idade) AS e
				WHERE e.rowindex IN (FLOOR(@rowindex / 2), CEIL(@rowindex / 2))");					   
				$medianaHCF = $mediana->fetch_assoc();
				echo '  <td><center>'.$medianaHCF["Median"].'</td>';
		  }else{
		  if($aux_query["Instituicao"]=="HC" and $aux_query["Genero"] == "M")
		  {
			 $mediana = $conn->query("SET @rowindex := -1");
			 $mediana = $conn->query("SELECT AVG(e.idade) as Median FROM
				(SELECT @rowindex:=@rowindex + 1 AS rowindex, exames.idade AS idade
				FROM exames where instituicao='HC' and sexo='M'
				ORDER BY exames.idade) AS e
				WHERE e.rowindex IN (FLOOR(@rowindex / 2), CEIL(@rowindex / 2))");					   
				$medianaHCM = $mediana->fetch_assoc();
				echo '  <td><center>'.$medianaHCM["Median"].'</td>';
		  }
		  else{
		  if($aux_query["Instituicao"]=="HC" and $aux_query["Genero"] == "F")
		  {
			 $mediana = $conn->query("SET @rowindex := -1");
			 $mediana = $conn->query("SELECT AVG(e.idade) as Median FROM
				(SELECT @rowindex:=@rowindex + 1 AS rowindex, exames.idade AS idade
				FROM exames where instituicao='LMC' and sexo='F'
				ORDER BY exames.idade) AS e
				WHERE e.rowindex IN (FLOOR(@rowindex / 2), CEIL(@rowindex / 2))");					   
				$medianaHCM = $mediana->fetch_assoc();
				echo '  <td><center>'.$medianaHCM["Median"].'</td>';
		  }else{
				$mediana = $conn->query("SET @rowindex := -1");
				$mediana = $conn->query("SELECT AVG(e.idade) as Median FROM
				(SELECT @rowindex:=@rowindex + 1 AS rowindex, exames.idade AS idade
				FROM exames where instituicao='LMC' and sexo='M'
				ORDER BY exames.idade) AS e
				WHERE e.rowindex IN (FLOOR(@rowindex / 2), CEIL(@rowindex / 2))");					   
				$medianaHCM = $mediana->fetch_assoc();
				echo '  <td><center>'.$medianaHCM["Median"].'</td>';
				}
			}
		 }
		 
		  echo '  <td><center>'.$aux_query["Media"].'</td>';
		  echo '  <td><center>'.$aux_query["Desvio"].'</td>';
		  echo '  <td><center>'.$aux_query["Variancia"].'</td>';	  	  
		  echo '<tr><br>';
		}
		?>
		</table><br>
		<center><H5>Printed on <?php echo date('m.d.Y'); ?></h5></center>
   </div>
   </div>
  </body>
</html>