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

$meses = array();
$quantidades = array();
$i = 0;
$result = $conn->query("SELECT concat(year(data),'/',month(data)) as mes, count(exame) as quantidade FROM exames group by concat(year(data),'/',month(data)) ORDER BY data ASC");
while($aux_query = $result->fetch_assoc()) {
	$meses[$i] = $aux_query["mes"];
	$quantidades[$i] = $aux_query["quantidade"];
	$i = $i + 1;
}	
?>
<html>
  <head>
	   <link rel="stylesheet" type="text/css" href="style.css"> 
	   <meta charset="utf-8">	    
	   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	   <meta name="author" content="Ademir Luiz do Prado">
	   <title>G-CoV.2: Lines Graph</title>
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
		  google.charts.load('current', {packages:['corechart', 'line']});
		  google.charts.setOnLoadCallback(desenhaGrafico);
		  function desenhaGrafico() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Mês');
			data.addColumn('number', 'Quantity');
			
			data.addRows([
			<?php $k = $i;
			for($i = 0; $i < $k; $i++){ ?>		
				['<?php echo $meses[$i]; ?>', <?php echo $quantidades[$i].'],';?>				
				<?php } ?>
			]);

			var options = {
				title: 'COVID-19: Positive Patients in Curitiba and Region',
				width: 800,
				height: 620,		 
				hAxis: {
				  title: 'Period (Year/Month)'
				},
				vAxis: {
				  title: 'Patient (Quantity)'
				},
				colors: ['#a52714', '#097138']
			  };

			var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
			
			chart.draw(data, options);
		  }
		</script>
		<div id="chart_div"></div>
		<font face="arial">
		<blockquote><blockquote><blockquote><blockquote><blockquote><blockquote>
		<b>Source:</b> G-CoV.2 (cpdm.ufpr.br/g-cov-2)
	</div>
	</div>
  </body>
</html>