<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$codigo_licenca = (int) base64_decode(addslashes(trim($_GET['codigo_licenca'])));
		
$sql_exclui = "UPDATE licenca SET status_licenca = 'E' WHERE codigo_licenca = ".$codigo_licenca."";
$query_exclui = mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir video",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_licencas.php?aviso=ok&msg=Liçenca excluída com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_licencas.php?aviso=erro&msg=Não foi possï¿½vel excluir a Liçenca.','_self');";
	echo "</script>";
}
?>