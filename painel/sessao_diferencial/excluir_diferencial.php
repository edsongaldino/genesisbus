<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_diferencial = (int) base64_decode(addslashes(trim($_GET['id_diferencial'])));
		
try{

    // REALIZA A ALTERAÇÃO
    $sql_exclui = "UPDATE diferencial SET status = 'E' WHERE id_diferencial = '".$id_diferencial."'";
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
	salvar_log($_SESSION["id_usuario"],"excluir diferencial",$sql_exclui, $pdo);
		
	echo "<script>";
	echo "open('gerenciar_diferenciais.php?aviso=ok&msg=diferencial excluído com sucesso.','_self');";
	echo "</script>";
} else {
	// Log
	salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui, $pdo);
		
	echo "<script>";
	echo "open('gerenciar_diferenciais.php?aviso=erro&msg=Não foi possível exclui a diferencial.','_self');";
	echo "</script>";
}
?>