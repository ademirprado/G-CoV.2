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
<html>
  <head>
	   <link rel="stylesheet" type="text/css" href="style.css"> 
	   <meta charset="utf-8">	    
	   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	   <meta name="author" content="Ademir Luiz do Prado">
	<title>G-CoV.2: Histogram</title>
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
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
		  google.charts.load("current", {packages:["corechart"]});
		  google.charts.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['idExame', 'Age'],
				<?php $result = $conn->query("SELECT idExame, idade FROM exames");
					while($aux_query = $result->fetch_assoc()) { ?>
						['<?php echo $aux_query["idExame"]; ?>', <?php echo $aux_query["idade"].'],';				
					} ?>		
			]);

			var options = {
				title: 'COVID-19: Age Distribution',
				legend: { position: 'top' },
				//colors: ['#4285F4'],

				chartArea: { width: 600 },

				bar: { gap: 0 },

				histogram: { 
				  bucketSize: 1,
				  minValue: 0,
				  maxValue: 100
				},
				
				hAxis: {
				  title: 'Age (years)'
				},
				vAxis: {
				  title: 'Patient (quantity)'
				},			
			};

			var chart = new google.visualization.Histogram(document.getElementById('chart_div'));
			chart.draw(data, options);
		  }
		</script>
		<div id="chart_div" style="width: 800px; height: 620px;"></div>
		<font face="arial">
		<blockquote><blockquote><blockquote><blockquote><blockquote>
		<b>Source:</b> G-CoV.2 (cpdm.ufpr.br/g-cov-2)
	</div>
	</div>
  </body>
</html>