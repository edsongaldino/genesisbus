<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id_usuario = (int) base64_decode(addslashes(trim($_GET['id_usuario'])));
		
$sql_exclui = "UPDATE sistema_usuarios SET status = 'E' WHERE id_usuario = ".$id_usuario."";
$query_exclui = $pdo->prepare($sql_exclui);
$query_exclui->execute();

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir usuario",$sql_exclui,$pdo);
		
	echo "<script>";
	echo "open('gerenciar_usuarios.php?aviso=ok&msg=Usuário excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui,$pdo);
		
	echo "<script>";
	echo "open('gerenciar_usuarios.php?aviso=erro&msg=Não foi possível exclui o usuário.','_self');";
	echo "</script>";
}
?>