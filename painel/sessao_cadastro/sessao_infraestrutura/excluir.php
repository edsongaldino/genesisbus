<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id = $_GET['id'];
		
$sql_exclui = "UPDATE site_infraestruturas SET status = 'E' WHERE id_infraestrutura = '".$id."'";
$query_exclui = @mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir infraestrutura",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=ok&msg=Infraestrutura excluída com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=erro&msg=Não foi possível exclui a infraestrutura.','_self');";
	echo "</script>";
}
?>