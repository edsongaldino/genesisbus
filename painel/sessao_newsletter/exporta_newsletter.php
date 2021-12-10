<?php 

	include("../ferramentas/conexao_mysql.php"); // Faz um include de tudo 
	
	$nome = "emails.txt";
	$arquivo = fopen("$nome","w+");
	$sql = mysql_query("SELECT email FROM newsletter WHERE status = 'L'");
	while ($aux = mysql_fetch_array($sql)) {
		$email = $aux["email"] . "\n";
		$gravar = $email;
		fputs($arquivo, "$gravar");
	}
	fclose($arquivo);
	
	echo "<script>";
	echo "open('gerenciar_newsletter.php?msg=Emails exportados com sucesso.','_self');";
	echo "</script>";
	
?>