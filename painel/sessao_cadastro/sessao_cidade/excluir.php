<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id = $_GET['id'];
		
$sql_exclui = "UPDATE cidade SET status = 'E' WHERE codigo_cidade = '".$id."'";
$query_exclui = mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir cidade",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=ok&msg=Cidade excluída com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=erro&msg=Não foi possível excluir a cidade.','_self');";
	echo "</script>";
}
?>