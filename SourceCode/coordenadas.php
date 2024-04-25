<?php
	// Autor: Ademir Luiz do Prado
	
	include "conexao.php";

	header('Content-type: application/json');

	$busca_mapa = "SELECT concat(a.SEXO,' and Age: ',a.IDADE) as name, concat(b.municipio,' (',b.estado,')') as city, b.bairro as district, b.latitude as lat, 
	b.longitude as lng, concat(monthname(data), ' ', DATE_FORMAT(data, '%d'),', ',  DATE_FORMAT(data, '%Y')) as data, a.INSTITUICAO as type, a.CLASSIFICACAO as severity  FROM exames a, enderecos b where a.CEP=b.cep";
	$res_consulta = mysqli_query($conn, $busca_mapa);
	$data = array();

	while ($row = mysqli_fetch_assoc($res_consulta)) {
		$data[] = $row;
	}

	echo json_encode($data, JSON_PRETTY_PRINT);
	mysqli_close($conn);
?>