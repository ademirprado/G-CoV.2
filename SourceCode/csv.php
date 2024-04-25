<?php
// Autor: Ademir Luiz do Prado

include "conexao.php";

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
	  if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel']<$nivel_necessario)) 
	  {
		  // Destrói a sessão por segurança
		  session_destroy();
		  // Redireciona o visitante de volta pro login
		  header("Location: index.php"); exit;
	  }
}
@$nivel_necessario = $_SESSION['UsuarioNivel'];
//if ($nivel_necessario == 1 || $nivel_necessario  == 0) 
{ // Acesso Total

	// Fetch records from database 
	//$query = $conn->query("SELECT * FROM exames ORDER BY data,instituicao ASC");
	//$query = $conn->query("SELECT t1.INSTITUICAO, t1.TIPO, t1.UNIDADE, t1.DATA, t1.SEXO, t1.RACA, t1.IDADE, t2.CEP, t2.ESTADO, t2.MUNICIPIO, t2.BAIRRO, t1.EXAME, t1.RESULTADO FROM exames t1, enderecos t2 where t1.cep=t2.cep ORDER BY t1.instituicao, t1.tipo, t1.unidade, T1.DATA ASC");
$query = $conn->query("SELECT t1.INSTITUICAO, t1.TIPO, t1.UNIDADE, t1.DATA, t1.SEXO, t1.RACA, t1.IDADE, t1.CEP, t1.ESTADO, t1.CIDADE, t1.BAIRRO, t1.EXAME, t1.RESULTADO FROM exames t1 ORDER BY t1.instituicao, t1.tipo, t1.unidade, T1.DATA ASC");
	 
	if($query->num_rows > 0)
	{ 
		$delimiter = ","; 
		$filename = "Tests-Data " . date('Y-m-d') . ".csv"; 
		 
		// Create a file pointer 
		$f = fopen('php://memory', 'w'); 
		 
		// Set column headers 
		$fields = array('Health_Institution', 'Type_Requester', 'Requester', 'Data_Requester', 'Gender', 'Ethnicity', 'Age', 'ZipCode', 'State', 'City', 'District', 'Test','Result'); 
		fputcsv($f, $fields, $delimiter); 
		 
		// Output each row of the data, format line as csv and write to file pointer 
		while($row = $query->fetch_assoc())
		{ 
			//$status = ($row['status'] == 1)?'Active':'Inactive'; 
			//$lineData = array($row['INSTITUICAO'], $row['TIPO'], $row['UNIDADE'], $row['SEXO'], $row['RACA'], $row['IDADE'], $row['CIDADE'], $row['ESTADO'], $row['EXAME'], $row['RESULTADO']); 
			$lineData = array($row['INSTITUICAO'], $row['TIPO'], $row['UNIDADE'], $row['DATA'], $row['SEXO'], $row['RACA'], $row['IDADE'], $row['CEP'], $row['ESTADO'], $row['CIDADE'], $row['BAIRRO'], $row['EXAME'], $row['RESULTADO']); 				
			fputcsv($f, $lineData, $delimiter); 
		} 
		 
		// Move back to beginning of file 
		fseek($f, 0); 
		 
		// Set headers to download file rather than displayed 
		header('Content-Type: text/csv'); 
		header('Content-Disposition: attachment; filename="' . $filename . '";'); 
		 
		//output all remaining data on a file pointer 
		fpassthru($f); 
	} 
}
//else
//{
	// Perfil é diferente de 1=Acesso Total
//	header("location:menu.php?erro=<h2>Access Denied to Export Tests!");
//}
exit;  
?>