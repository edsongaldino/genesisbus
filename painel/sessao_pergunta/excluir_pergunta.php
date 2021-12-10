<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega o id
$id_pergunta_resposta = (int) base64_decode(addslashes(trim($_GET['id_pergunta_resposta'])));
		
$sql_exclui = "UPDATE perguntas_resposta SET status = 'E' WHERE id_pergunta_resposta = ".$id_pergunta_resposta."";
$query_exclui = mysql_query($sql_exclui); // executa o sql acima

// Verifica se deu erro
if($query_exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir pergunta e resposta",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_perguntas.php?aviso=ok&msg=Pergunta e resposta excluída com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_perguntas.php?aviso=erro&msg=Não foi possível exclui a pergunta e resposta.','_self');";
	echo "</script>";
}
?>