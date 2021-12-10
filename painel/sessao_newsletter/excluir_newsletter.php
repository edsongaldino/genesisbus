<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$codigo_newsletter = (int) base64_decode(addslashes(trim($_GET['codigo'])));
		
$sql_exclui = "UPDATE newsletter SET status = 'E' WHERE codigo = ".$codigo_newsletter."";
$query_exclui = mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir email",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_newsletter.php?aviso=ok&msg=Email excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_newsletter.php?aviso=erro&msg=Não foi possível excluir o Email.','_self');";
	echo "</script>";
}
?>