<?php
	// Autor: Ademir Luiz do Prado
				
	// Conecta o Banco de Dados
	include ("conexao.php");

	//Incluímos um código aqui...
	$id     = -1;
	$nome   = "";
	$email  = "";
	$login = "";
	$senha     = "";
	$perfil = "3";

	//Validando a existência dos dados
	if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["login"]) && isset($_POST["senha"]))
	{
		//Agora, o $id, pode vir com o valor -1, que nos indica novo registro, ou, vir com um valor diferente de -1,
		// ou seja, o código do registro no banco, que nos indica alteração dos dados.
		$id     = $_POST["id"];		
		$nome   = $_POST["nome"];
		$email  = $_POST["email"];
		$login  = $_POST["login"];
		$senha  = password_hash($_POST["senha"], PASSWORD_DEFAULT);
		$perfil = 3;
				
		//Se o id for -1, vamos realizar o cadastro ou alteração dos dados enviados.
		if($id == -1)
		{
			$stmt = $conn->prepare("INSERT INTO USUARIO (`nome`,`email`,`login`,`senha`, `perfil`) VALUES (?,?,?,?,?)");
			$stmt->bind_param('sssss', $nome, $email, $login, $senha, $perfil);	
			try
			{
				if($stmt->execute())
				{
					header("location:index.php?erro=<h2>Successful Registration!");
					exit;
				}
			}
			catch (Exception $ex)
			{
				$erro = $stmt->error;
				header("location:cadastraAcesso.php?erro=<h2>Unable to register. Login or E-Mail already entered!");
				exit;
			}
		}
	}		
?>		
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta charset="utf-8"/>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<title>Register Users</title>
		<meta name="author" content="Ademir Luiz do Prado">
	</head>
	<body>
		<div class="site-content">
			<center>
			<img src="imagens\G-CoV2.svg">
			<?php
				//Exibir mensagem de erro caso ocorra
				if (isset($_GET["erro"]))
				{
					$erro = $_GET["erro"];
					echo "<center><h2><font color='red'>$erro</font></center>";
				}
			?>		
			<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST" class="form-signin" enctype="multipart/form-data">     
				<fieldset>									
					<legend>
					<strong>
					<h2>Register Users</h2>
					</legend>
					<br>
					<label for="nome">Name:</label>
					<br>					
					<input type="text" name="nome" size="50" maxlength = "50" placeholder="What's your name?" value="<?=$nome?>" required><br/><br/>
					<label for="email">E-Mail:</label> 
					<br>					
					<input type="email" name="email" size="30" maxlength = "30" placeholder="What's your e-mail?" value="<?=$email?>" required><br/><br/>
					<label for="login">User:</label> 
					<br>					
					<input type="text" name="login" size="15" maxlength = "15" placeholder="Enter your user." value="<?=$login?>" required><br/><br/>
					<label for="senha">Password:</label> 
					<br>					
					<input type="password" name="senha" size="15" maxlength = "15" placeholder="Enter your password." value="<?=$senha?>" required><br/><br/>
					</strong>
				</fieldset>
				<br>
				<input type="hidden" value="<?=$id?>" name="id" >
				  <!--Alteramos aqui também, para poder mostrar o texto Cadastrar, ou Salvar, de acordo com o momento. :)-->
				<button type="submit"><?=($id==-1)?"Register":"Salvar"?></button>
				<input type="reset" value="Reset">
				<input type="button" value="Come Back" onClick="history.go(-1)">
			</form>		
			</center>
		</div>
	</body>
</html>