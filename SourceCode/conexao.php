<?php
	// Autor: Ademir Luiz do Prado
	
	// Desabilita mensagens de erro
	// error_reporting(0); 

	// Variáveis para conexão
	$servidor = "localhost";
	$banco = "gcov2";
	$usuario = "root";
	$senha = "";

	//Criar a conexao
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);
	if(!$conn){
		header("location:index.php?erro=<h2>Connection failed: " . mysqli_connect_error());
	}else{
		//Conexao realizada com sucesso
		mysqli_set_charset($conn, 'utf8');
		/* Verificar a existência de tabelas no Banco de Dados
		$test_query = "SHOW TABLES FROM $banco";
		$result = mysqli_query($conn, $test_query);

		$tblCnt = 0;
		while($tbl = mysqli_fetch_array($result)) {
			$tblCnt++;
			echo $tbl[0]."<br />\n";
		}

		if (!$tblCnt) {
		  echo "There are no tables<br />\n";
		} else {
		  echo "There are $tblCnt tables<br />\n";
		} 
		*/
	}
?>
