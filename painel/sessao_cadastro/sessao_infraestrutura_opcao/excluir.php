<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id = $_GET['id'];
		
$sql_exclui = "UPDATE site_infraestruturas_opcoes SET status = 'E' WHERE id_infraestrutura_opcao = '".$id."'";
$query_exclui = @mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir op��o infraestrutura",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=ok&msg=Op��o da infraestrutura exclu�da com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=erro&msg=N�o foi poss�vel exclui a op��o da infraestrutura.','_self');";
	echo "</script>";
}
?>