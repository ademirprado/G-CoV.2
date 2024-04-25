<?php
	// Autor: Ademir Luiz do Prado

	if (!isset($_SESSION)) session_start();
	$CEP = $_SESSION['CEP'];

	include "conexao.php";
	
	header('Content-type: application/json');

	$busca_mapa = "SELECT concat('* City: ',municipio,' (',estado,')') as name, bairro as address, latitude as lat, longitude as lng, concat(substr(cep,1,5),'-',substr(cep,6,5)) as localizacao, 'ademirlp@ufpr.br' as type FROM enderecos where cep=".$CEP." limit 1";
	$res_consulta = mysqli_query($conn, $busca_mapa);
	$data = array();

	while ($row = mysqli_fetch_assoc($res_consulta)) {
		$data[] = $row;
	}

	echo json_encode($data, JSON_PRETTY_PRINT);
	mysqli_close($conn);
?>