<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_frase = (int) base64_decode(addslashes(trim($_GET['id_frase'])));
		
$sql_exclui = "UPDATE frases SET status = 'E' WHERE id_frase = ".$id_frase."";
$query_exclui = mysql_query($sql_exclui);

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir frase",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_frases.php?aviso=ok&msg=Frase excluída com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_frases.php?aviso=erro&msg=Não foi possível exclui a frase.','_self');";
	echo "</script>";
}
?>