<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_galeria = (int) base64_decode(addslashes(trim($_GET['id_galeria'])));
		
$sql_exclui = "UPDATE galerias SET status = 'E' WHERE id_galeria = ".$id_galeria."";
$exclui = $pdo->query($sql_exclui);
$exclui->execute();

// Verifica se deu erro
if($exclui) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir galeria",$sql_exclui, $pdo);
		
	echo "<script>";
	echo "open('gerenciar_galerias.php?aviso=ok&msg=Galeria excluída com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui, $pdo);
		
	echo "<script>";
	echo "open('gerenciar_galerias.php?aviso=erro&msg=Não foi possível excluir a galeria.','_self');";
	echo "</script>";
}
?>