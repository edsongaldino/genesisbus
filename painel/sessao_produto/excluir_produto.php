<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_produto = (int) base64_decode(addslashes(trim($_GET['id_produto'])));
		
$sql_exclui = "UPDATE produto SET status = 'E' WHERE id_produto = ".$id_produto."";
$exclui = $pdo->query($sql_exclui);
$exclui->execute();

// Verifica se deu erro
if($exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir produto",$sql_exclui, $pdo);
		
	echo "<script>";
	echo "open('gerenciar_produtos.php?aviso=ok&msg=produto excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui, $pdo);
		
	echo "<script>";
	echo "open('gerenciar_produtos.php?aviso=erro&msg=Não foi possível excluir o produto.','_self');";
	echo "</script>";
}
?>