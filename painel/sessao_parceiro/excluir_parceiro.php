<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_cliente = (int) base64_decode(addslashes(trim($_GET['id_cliente'])));
		
$sql_exclui = "UPDATE clientes SET status = 'E' WHERE id_cliente = ".$id_cliente."";
$query_exclui = mysql_query($sql_exclui);

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir cliente",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_clientes.php?aviso=ok&msg=Cliente excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_clientes.php?aviso=erro&msg=Não foi possível exclui a cliente.','_self');";
	echo "</script>";
}
?>