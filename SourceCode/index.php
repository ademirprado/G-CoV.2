<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta charset="utf-8"/>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<meta name="author" content="Ademir Luiz do Prado">
		<title>G-CoV.2</title>
	</head>
	<body>
	<div class="site-content">
		<form method="post" name="formLogin" action="login.php">
			<h1 align="center">				
			</h1>
			<center><img src="imagens\G-CoV2.svg">
			<br>			
			<?php
				//Exibir mensagem de erro caso ocorra
				if (isset($_GET["erro"]))
				{
					$erro = $_GET["erro"];
					echo "<center><h2><font color='red'>$erro</font></center>";
				}
			?>
			<table align="center">
				<tr>
					<th>
						User
					</th>
					<td>
						<input type="text" name="txtLogin" size="15" maxlength = "15">
					</td>
				</tr>
				<tr>
					<th>
						Password
					</th>
					<td>
						<input type="password" name="txtSenha" size="15" maxlength = "15">
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="submit" value="Enter">
					
						<input type="reset" value="Reset">
					</td>
				</tr>
			</table>
			<?php $_SESSION['UsuarioNivel'] = 0; ?>
			<center><a href="cadastraAcesso.php">Click Here to register</a></center>
		</form>
	</div>
	</body>
</html>