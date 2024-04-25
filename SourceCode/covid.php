<?php
// Autor: Ademir Luiz do Prado

//Importar grandes tabelas no XAMPP via Control Panel, Botão Shell:
//# mysql -u root -p BANCO < "C:/xampp/mysql/bin/TABELA.sql"

include_once("conexao.php"); // incluindo a conexao com banco de dados

// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();  
if (!isset($_SESSION['UsuarioNivel']))
{
  session_destroy();
  header('Location: index.php?erro=<h2>Fill in User and Password to access Geolocation.');
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
	  header("Location: index.php?erro=<h2>Fill in User and Password to access Geolocation."); 
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
		  var  map = L.map('map').setView([-25.44498, -49.24027], 15);
		  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
			minZoom: 1,
			maxZoom: 19
		  }).addTo(map);

		  //const url = "https://seusiteoudominio.com/coordenadas.php" (caso nao esteja no mesmo servidor)
		  const url = 'coordenadas.php'; // api responsavel por buscar os dados que estao salvos no banco.

		  fetch(url)
			.then(response => response.json())
			.then(result => {
			  const dados = JSON.stringify(result);
			  result.forEach(function(retorno) {
				if (retorno.severity == 1)
				{
					retorno.severity = 'Severe';
					var myIcon = L.icon({
					iconUrl: 'imagens/my-icon.png',
					iconSize: [24, 41],
					iconAnchor: [24, 41],
					popupAnchor: [-10, -38],
					//shadowUrl: 'my-icon-shadow.png',
					//shadowSize: [68, 95],
					//shadowAnchor: [22, 94]
					});
				}else{
					retorno.severity = 'Mild to Moderate';
					var myIcon = L.icon({
					iconUrl: 'imagens/marker-icon.png',
					iconSize: [24, 41],
					iconAnchor: [24, 41],
					popupAnchor: [-10, -38],
					//shadowUrl: 'my-icon-shadow.png',
					//shadowSize: [68, 95],
					//shadowAnchor: [22, 94]
					});
				}			  
				var location = new L.LatLng(retorno.lat, retorno.lng);
				console.log("RETORNO ",result);
				var markerGroup = L.featureGroup([]).addTo(map);
				var latLng = L.latLng([retorno.lat, retorno.lng]);
				L.marker(latLng, {icon: myIcon}).bindPopup('<b><u>HEALTH INSTITUTION: ' + retorno.type + 
				  '</u></b><br>* ' + retorno.severity + ' in ' + retorno.data +
				  "<br>* Patient's Sex: " + retorno.name +
				  '<br>* City: ' + retorno.city +
				  '<br>* District: ' + retorno.district).addTo(markerGroup).addTo(map);
			  });
			})
			.catch(function(err) {
			  console.error(err);
			})
		</script>
		<?php
		//}else{
			// Perfil é diferente de 1=Acesso Total
		//	header("location:menu.php?erro=<h2>Acesso Negado para Consultar Mapa de Geolocalização de Pacientes com COVID!");
		//}
		?>	
	</div>  
  </body>
</html>