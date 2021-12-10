<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$codigo_revendedor = (int) base64_decode(addslashes(trim($_GET['codigo_revendedor'])));
		
try{

  // REALIZA A ALTERAÇÃO
  $sql_exclui = "UPDATE revendedor SET status = 'E' WHERE codigo_revendedor = '".$codigo_revendedor."'";
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
	salvar_log($_SESSION["id_usuario"],"excluir revendedor",$sql_exclui, $pdo);
	echo "<script>";
	echo "open('gerenciar_revendedores.php?aviso=ok&msg=Revendedor excluído com sucesso.','_self');";
	echo "</script>";

} else {

	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui, $pdo);
	echo "<script>";
	echo "open('gerenciar_revendedores.php?aviso=erro&msg=Não foi possível exclui a revendedor.','_self');";
	echo "</script>";

}
?>