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

$genero = array();
$quantidade = array();
$cor = array();
$cor[0] = '#ff3300';
$cor[1] = '#0000ff';
$cor[2] = '#006600';
$cor[3] = '#ff0066';
$i = 0;
$result = $conn->query("SELECT INSTITUICAO, SEXO, COUNT(*) as 'QUANTIDADE' FROM exames WHERE PATOLOGIA=1 GROUP BY INSTITUICAO, SEXO");
while($aux_query = $result->fetch_assoc()) {
	$genero[$i] = $aux_query["SEXO"];
	$quantidade[$i] = $aux_query["QUANTIDADE"];
	$i = $i + 1;
}
?>
<html>
	<head>
	   <link rel="stylesheet" type="text/css" href="style.css"> 
	   <meta charset="utf-8">	    
	   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	   <meta name="author" content="Ademir Luiz do Prado">
	   <title>G-CoV.2: Columns Graph</title>
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
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = google.visualization.arrayToDataTable([
				["Gênero", "Quantidade", {role: "style"}],
				<?php 
				$k = $i;
				for ($i = 0; $i < $k; $i++) { ?>
					['<?php echo $genero[$i] ?>', <?php echo $quantidade[$i] ?>, '<?php echo $cor[$i] ?>'],				
				<?php } ?>		
			]);
			var view = new google.visualization.DataView(data);
			view.setColumns([0, 1,
						   { calc: "stringify",
							 sourceColumn: 1,
							 type: "string",
							 role: "annotation" },
						   2]);					   
			var options = {
				title: "COVID-19: Patients Positive for Sex and Health Institution",
				width: 800,
				height: 620,
				bar: {groupWidth: "50%"},
				hAxis: { title: "Clinics Hospital of the Federal University of Paraná and Municipal Laboratory of Curitiba. Source: G-CoV.2 (cpdm.ufpr.br/g-cov-2)"},
				vAxis: { title: "Number of Patients" },
				legend: { position: "none" },
			  };
			  
			var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
			  chart.draw(view, options);
		  }
		</script>
		<div id="columnchart_values" style="width: 900px; height: 300px;"></div>
		</div>
		</div>
	</body>
</html>