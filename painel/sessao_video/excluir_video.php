<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id_video = (int) base64_decode(addslashes(trim($_GET['id_video'])));
		
$sql_exclui = "UPDATE videos SET status = 'E' WHERE id_video = ".$id_video."";
$query_exclui = mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir video",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_videos.php?aviso=ok&msg=V&iacute;deo excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_videos.php?aviso=erro&msg=Não foi possível exclui o v&iacute;deo.','_self');";
	echo "</script>";
}
?>