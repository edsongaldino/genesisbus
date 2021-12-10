<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id_user = (int) base64_decode(addslashes(trim($_GET['id_user'])));
		
$sql_exclui = "UPDATE sistema_usuarios SET status = 'E' WHERE id_usuario = ".$id_user."";
$query_exclui = mysql_query($sql_exclui);

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir usuario",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_usuarios.php?aviso=ok&msg=Usuário excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_usuarios.php?aviso=erro&msg=Não foi possível exclui o usuário.','_self');";
	echo "</script>";
}
?>