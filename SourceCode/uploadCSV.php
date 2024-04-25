<?php
// Autor: Ademir Luiz do Prado

// Evitar o timeout caso o arquivo seja grande
ini_set('max_execution_time', 0);

// Conexão com o Banco de Dados
include 'conexao.php';

$uploaddir = getcwd() . '\upload\\'; 							// Diretório atual
$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);// Diretório com arquivo selecionado
move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);// Move arquivo para diretório do sistema

// Variáveis para testar tipo de arquivo CSV
$nome 		= $_FILES["arquivo"]["name"];						// Nome do arquivo
$ext 		= explode(".", $nome);								// Extensão do arquivo
$extensao 	= strtolower(end($ext));							// Extensão em letras minúsculas
		
if($extensao != "csv")
{
	header("location:menu.php?erro=<h2>File type is not CSV!");
}else{
	if(file_exists($uploadfile)){
		$uploadfile = "C:/xampp/htdocs/COVID+/upload/$nome";
		if(mysqli_query($conn, "LOAD DATA INFILE '$uploadfile' INTO TABLE exames FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 ROWS"))
		{ 
			if(file_exists($uploadfile)){
				unlink($uploadfile);
			}
			header("location:menu.php?erro=<h2>File imported successfully!");
		}else{
		  header("location:menu.php?erro=<h2>Could not import file!");
		}
	}else{
		header("location:menu.php?erro=<h2>File does not exist!");
	}
}
?>
