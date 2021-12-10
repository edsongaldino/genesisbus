<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_escola_pais = (int) base64_decode(addslashes(trim($_GET['id_escola_pais'])));
		
$sql_exclui = "UPDATE escola_pais SET status = 'E' WHERE id_escola_pais = ".$id_escola_pais."";
$query_exclui = mysql_query($sql_exclui);

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir escola_pais",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_escolaspais.php?aviso=ok&msg=Escola de pais excluída com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_escolaspais.php?aviso=erro&msg=Não foi possível exclui a escola de pais.','_self');";
	echo "</script>";
}
?>