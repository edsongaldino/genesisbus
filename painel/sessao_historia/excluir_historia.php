<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_noticia = (int) base64_decode(addslashes(trim($_GET['id_noticia'])));
		
$sql_exclui = "UPDATE noticias SET status = 'E' WHERE id_noticia = ".$id_noticia."";
$query_exclui = mysql_query($sql_exclui);

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir noticia",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_noticias.php?aviso=ok&msg=Not�cia exclu�da com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_noticias.php?aviso=erro&msg=N�o foi poss�vel exclui a not�cia.','_self');";
	echo "</script>";
}
?>