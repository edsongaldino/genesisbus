<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();
$id = $_POST['id'];

$sql_subtipo_produto = "SELECT id_subtipo_produto, nome_subtipo_produto FROM subtipo_produto WHERE id_tipo_produto = '".$id."' ORDER BY nome_subtipo_produto ASC";
$query_subtipos = $pdo->query($sql_subtipo_produto);
$resultado_subtipo_produto = $query_subtipos->fetchAll( PDO::FETCH_ASSOC );

foreach($resultado_subtipo_produto AS $subtipo):
    echo '<option value="'.$subtipo["id_subtipo_produto"].'">'.$subtipo["nome_subtipo_produto"].'</option>';
endforeach;
?>