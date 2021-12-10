<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_folhabrasilis = (int) base64_decode(addslashes(trim($_GET['id_folhabrasilis'])));
		
$sql_exclui = "UPDATE folhasbrasilis SET status = 'E' WHERE id_folhabrasilis = ".$id_folhabrasilis."";
$query_exclui = mysql_query($sql_exclui);

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir arquivo (folha brasilis)",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_folhasbrasilis.php?aviso=ok&msg=Arquivo (folha brasilis) excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_folhasbrasilis.php?aviso=erro&msg=Não foi possível exclui o arquivo (folha brasilis).','_self');";
	echo "</script>";
}
?>