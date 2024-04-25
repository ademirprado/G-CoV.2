<?php
  // Autor: Ademir Luiz do Prado
  
  // Verifica se houve POST e se o usuário ou a senha é(estão) vazio(s)
  if (empty($_POST) AND (empty($_POST['usuario']) OR empty($_POST['senha']))) {
      header("Location: index.php"); exit;
  }
	//Conecta com o Banco de Dados
	include ("conexao.php");
	
	// Pegar os campos do formulário
	$login = $_POST["txtLogin"];
	$senha = $_POST["txtSenha"];
	$sql = "SELECT id, login, nome, senha, perfil FROM USUARIO WHERE login = '$login' LIMIT 1";
	$sql_exec = $conn->query($sql) or die($conn->error);
	$resultado = $sql_exec->fetch_assoc();
	if(password_verify($senha, $resultado['senha']))
	{
	  // Se a sessão não existir, inicia uma
      if (!isset($_SESSION)) session_start();
      // Salva os dados encontrados na sessão
      $_SESSION['UsuarioCodigo'] = $resultado['id'];
	  $_SESSION['UsuarioID'] = $resultado['login'];
      $_SESSION['UsuarioNome'] = $resultado['nome'];
      $_SESSION['UsuarioNivel'] = $resultado['perfil'];
	  header("Location:menu.php");
	}
	else
	{
		// Login e senha NÃO conferem
		header("location:index.php?erro=<h2>Invalid User/Password(s).");
	}
?>