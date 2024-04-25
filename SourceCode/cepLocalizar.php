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

$CEP = $_POST["txtCEP"];
$_SESSION['CEP'] = $CEP;

include "conexao.php"; // incluindo a conexao com banco de dados

$cepLocalizar = "SELECT concat(municipio,'(',estado,')') as name, bairro as address, latitude as lat, longitude as lng, DATE_FORMAT(now(), '%d/%m/%Y') as data, 'ademirlp@ufpr.br' as type FROM enderecos where cep=".$CEP." limit 1";
$result = mysqli_query($conn, $cepLocalizar);
$resultado = mysqli_fetch_assoc($result);
if(!empty($resultado)) 
{
?>
	<html>
	  <head>
	   <link rel="stylesheet" type="text/css" href="style.css"> 
	   <meta charset="utf-8">	    
	   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	   <meta name="author" content="Ademir Luiz do Prado">
		<title>G-CoV.2 with OpenStreetMap</title>
	  </head>
	  <body>
		<div class="center" style="width:185mm">
		<br>
		<center><img src="imagens\G-CoV2.svg"></center>
		<div class="menu">
		<ul class="menu-list">
		<li><a href="#">Manage</a>
		  <ul class="sub-menu">
			<li><a href="usuario.php">Users</a></li>
			<li><a href="imprimeUsuario.php">Print Users</a></li>
			<li><a href="csv.php">Export Data</a></li>
			<li><a href="importar.php">Import Data</a></li>
		  </ul>
		</li>
		<li><a href="#">Graphics</a>
		  <ul class="sub-menu">
			<li><a href="graficoColunas.php">Columns</a></li>
			<li><a href="histograma.php">Histogram</a></li>
			<li><a href="graficoLinhas.php">Line</a></li>		
		  </ul>
		</li>
		<li><a href="#">Geolocation</a>
		  <ul class="sub-menu">
			<li><a href="covid.php">G-CoV.2</a></li>
			<li><a href="estatistica.php">Statistics</a></li>
			<li><a href="cep.php">ChecK Zip Code</a></li>		
		  </ul>
		</li>
		<li><a href="#">Inform</a>
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

		<div id="map"></div> 
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" 
		integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="anonymous" />

		<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js" 
		integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin="anonymous"></script>
		<style>
		  #map {
			height: 50vh;
			width: 100hw
		  }
		</style>

		<script>
		  var  map = L.map('map').setView([<?php echo $resultado['lat'].', '.$resultado['lng'].',';?> 7], 15);
		  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
			minZoom: 1,
			maxZoom: 19
		  }).addTo(map);

		  //const url = "https://seusiteoudominio.com/coordenadas.php" (caso nao esteja no mesmo servidor)
		  const url = 'cepCoordenadas.php'; // api responsavel por buscar os dados que estao salvos no banco.

		  fetch(url)
			.then(response => response.json())
			.then(result => {
			  const dados = JSON.stringify(result);

			  result.forEach(function(retorno) {
				var location = new L.LatLng(retorno.lat, retorno.lng);
				console.log("RETORNO ",result);
				var markerGroup = L.featureGroup([]).addTo(map);
				var latLng = L.latLng([retorno.lat, retorno.lng]);
				L.marker(latLng).bindPopup('<b>APPROXIMATE GEOLOCATION:</b><br>' + retorno.name +
				  '<br>* District: ' + retorno.address +
				  '<br>* Zip Code: ' + retorno.localizacao +
				  '<br>If incorrect: <b>' + retorno.type).addTo(markerGroup).addTo(map);
			  });
			})
			.catch(function(err) {
			  console.error(err);
			})
		</script>

		</div>
	   </body>
	</html>	
<?php
}else
{		
  header("location:menu.php?erro=<h2>Zip Code not found! Contact ademirlp@ufpr.br");
}
?>