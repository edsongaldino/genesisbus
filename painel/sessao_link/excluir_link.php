<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id_link = (int) base64_decode(addslashes(trim($_GET['id_link'])));
		
$sql_exclui = "UPDATE links SET status = 'E' WHERE id_link = ".$id_link."";
$query_exclui = mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir link",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_links.php?aviso=ok&msg=Link excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_links.php?aviso=erro&msg=Não foi possível exclui o link.','_self');";
	echo "</script>";
}
?>