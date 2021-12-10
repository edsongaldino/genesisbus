<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_slideshow = (int) base64_decode(addslashes(trim($_GET['id_slideshow'])));
		
try{

	// REALIZA A ALTERAÇÃO
	$sql_exclui = "UPDATE slideshows SET status = 'E' WHERE id_slideshow = '".$id_slideshow."'";
	$exclui = $pdo->prepare($sql_exclui);
	$exclui->execute();

	$excluir = true;

}catch (PDOException $e){
	echo 'Error: '. $e->getMessage();
	$excluir = false;
} 

// Verifica se deu erro
if($excluir) {
	// Log
	salvar_log($_SESSION["id_usuario"],"excluir slideshow",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_slideshows.php?aviso=ok&msg=Slideshow excluÍdo com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui);
		
	echo "<script>";
	echo "open('gerenciar_slideshows.php?aviso=erro&msg=Não foi possível exclui o slideshow.','_self');";
	echo "</script>";
}
?>