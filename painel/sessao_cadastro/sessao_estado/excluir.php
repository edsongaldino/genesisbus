<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id = $_GET['id'];
		
$sql_exclui = "UPDATE estado SET status = 'E' WHERE codigo_estado = '".$id."'";
$query_exclui = $pdo->prepare($sql_exclui);
$query_exclui->execute();

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir estado",$sql_exclui,$pdo);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=ok&msg=Estado excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui,$pdo);
		
	echo "<script>";
	echo "open('gerenciar.php?aviso=erro&msg=Não foi possível excluir o estado.','_self');";
	echo "</script>";
}
?>