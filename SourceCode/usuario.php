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
@$nivel_necessario = $_SESSION['UsuarioNivel'];
if ($nivel_necessario == 1 || $nivel_necessario  == 0) 
{ // Acesso Total
  
	include "conexao.php";

	//Incluímos um código aqui...
	$id     = -1;
	$nome   = "";
	$email  = "";
	$login 	= "";
	$senha  = "";
	$perfil = "";

	//Validando a existência dos dados
	if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["login"]) && isset($_POST["senha"]) && isset($_POST["perfil"]))
	{
	if(empty($_POST["nome"]))
	{
		$mensagem = "Required name field";
	}
	else
	{
	if(empty($_POST["email"]))
	{
		$mensagem = "Required e-mail field";
	}
	else
	if(empty($_POST["login"]))
	{
		$mensagem = "Required user field";
	}
	else
	if(empty($_POST["senha"]))
	{
		$mensagem = "Required password field";
	}
	else
	if(empty($_POST["perfil"]))
	{
		$mensagem = "Required profile field";
	}
		else
	if($_POST["perfil"] != '1' && $_POST["perfil"] != '2' && $_POST["perfil"] != '3' && $_POST["perfil"] != '4' )
	{
		$mensagem = "Profile field accepts only values 1,2,3 or 4";
	}
	else
	{
		//Agora, o $id, pode vir com o valor -1, que nos indica novo registro, ou, vir com um valor diferente de -1,
		// ou seja, o código do registro no banco, que nos indica alteração dos dados.
		$id     = $_POST["id"];		
		$nome   = $_POST["nome"];
		$email  = $_POST["email"];
		$login 	= $_POST["login"];
		$senha  = password_hash($_POST["senha"], PASSWORD_DEFAULT);
		$perfil = $_POST["perfil"];
				
		//Se o id for -1, vamos realizar o cadastro ou alteração dos dados enviados.
		if($id == -1)
		{
			$stmt = $conn->prepare("INSERT INTO USUARIO (`nome`,`email`,`login`,`senha`, `perfil`) VALUES (?,?,?,?,?)");
			@$stmt->bind_param('sssss', $nome, $email, $login, $senha, $perfil);	
			try
			{
				if($stmt->execute())
				{
					header("location:usuario.php?erro=<h2>Successful Registration!");
					exit;
				}
			}
			catch (Exception $ex)
			{
				$erro = $stmt->error;
				header("location:usuario.php?erro=<h2>Unable to register. Login or E-Mail already entered!");
				exit;
			}
		}
		//se não, vamos realizar a alteraçao dos dados,
        //porém, vamos nos certificar que o valor passado no $id, seja válido para nosso caso.
		else
		if(is_numeric($id) && $id >= 1)
		{
			$stmt = $conn->prepare("UPDATE `usuario` SET `nome`=?, `email`=?, `login`=?, `senha`=?, `perfil`=? WHERE id = ? ");
			@$stmt->bind_param('sssssi', $nome, $email, $login, $senha, $perfil, $id);
			try
			{
				if($stmt->execute())
				{
					header("location:usuario.php?erro=<h2>Change Made Successfully!");
					exit;
				}
			}
			catch (Exception $ex)
			{
				$erro = $stmt->error;
				header("location:usuario.php?erro=<h2>Could not change. Existing Login or E-Mail!");
				exit;
			}
		}
		//retorna um erro.
		else
		{
			$mensagem = "Invalid number";
		}
	}
	}
	}
	else
	//Incluimos este bloco, onde vamos verificar a existência do id passado...
	if(isset($_GET["id"]) && is_numeric($_GET["id"])) //Incluimos aqui...
	{
		$id = (int)$_GET["id"];
		
		if(isset($_GET["del"]))
		{
			$stmt = $conn->prepare("DELETE FROM `usuario` WHERE id = ?");
			$stmt->bind_param('i', $id);
			$stmt->execute();			
			header("Location:usuario.php?erro=<h2>Deletion Successful!");
			exit;
		}
		else
		{
			$stmt = $conn->prepare("SELECT * FROM `usuario` WHERE id = ?");
			@$stmt->bind_param('i', $id);
			$stmt->execute();
			
			$result = $stmt->get_result();
			$aux_query = $result->fetch_assoc();
			
			$nome = $aux_query["nome"];
			$email = $aux_query["email"];
			$login = $aux_query["login"];
			$senha = $aux_query["senha"];
			$perfil = $aux_query["perfil"];
			
			$stmt->close();		
		}
	}
	?>	
	<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta charset="utf-8"/>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<meta name="author" content="Ademir Luiz do Prado">		
		<title>Manage Users</title>
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
		<?php
			if(isset($mensagem))
				echo '<H2><div style="color:red">'.$mensagem.'</div></H2><b>';
		?>
		<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST" class="form-signin" enctype="multipart/form-data"> 
			<fieldset>
			<table width="675px" cellspacing="0">    
			  <legend><strong><h2>Manage Users</h2></legend>
			  <br>
			  <label for="nome">Name:</label>
			  <br>	  
			  <input type="text" name="nome" size="50" maxlength = "50" placeholder="What's your name?" value="<?=$nome?>"><br/><br/>
			  <label for="email">E-Mail:</label> 
			  <br>	  
			  <input type="email" name="email" size="50" maxlength = "50" placeholder="What's your e-mail?" value="<?=$email?>"><br/><br/>
			  <label for="login">User:</label> 
			  <br>	  
			  <input type="text" name="login" size="15" maxlength = "15" placeholder="Enter your user." value="<?=$login?>"><br/><br/>
			  <label for="senha">Password:</label> 
			  <br>	  
			  <input type="password" name="senha" size="15" maxlength = "15" placeholder="Enter your password." value="<?=$senha?>"><br/><br/>
			  <label for="perfil">Profile:</label> 
			  <br>	  
			  <input type="text" name="perfil" size="50" maxlength = "1" placeholder="Report: 1=Total, 2=Register, 3-Query, 4-Change" value="<?=$perfil?>"><br/><br/>
			</table>
			</fieldset>
			<br>
			<input type="hidden" value="<?=$id?>" name="id" >
			<!--Alteramos aqui também, para poder mostrar o texto Cadastrar, ou Salvar, de acordo com o momento. :)-->
			<button type="submit"><?=($id==-1)?"Register":"Salvar"?></button>
			<input type="reset" value="Reset">
			<input type="button" value="Come Back" onClick="history.go(-1)">
			<input type="button" value="Advance" onCLick="history.forward()"> 
			<input type="button" value="Update" onClick="history.go(0)">
			</strong>
		</form>
		<?php
		if (@$_SESSION['UsuarioNivel'] == 1)
		?>
		<br>
		<fieldset>
		<table width="675px" cellspacing="0">
			<tr>
				<td><strong>#</strong></td>
				<td><strong>Name</strong></td>
				<td><strong>E-Mail</strong></td>
				<td><strong>User</strong></td>
				<td><strong>Profile</strong></td>
				<td><strong>#</strong></td>
			 </tr>
			<?php
			$result = $conn->query("SELECT * FROM USUARIO");
			//
			while($aux_query = $result->fetch_assoc()) 
			{
			  echo '<tr>';
			  echo '  <td>'.$aux_query["id"].'</td>';
			  echo '  <td>'.$aux_query["nome"].'</td>';
			  echo '  <td>'.$aux_query["email"].'</td>';
			  echo '  <td>'.$aux_query["login"].'</td>';
			  echo '  <td>'.$aux_query["perfil"].'</td>';
			  echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?id='.$aux_query["id"].'">Edit</a></td>';
			  echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?id='.$aux_query["id"].'&del=true">Exclude</a></td>';
			  echo '</tr>';
			}?>
		</table>
		</fieldset>
		</div>
	</div>
	</body>
	</html>
<?php			
}else{
	// Perfil é diferente de 1=Acesso Total
	header("location:menu.php?erro=<h2>Access Denied to Manage Users!");
}
?>