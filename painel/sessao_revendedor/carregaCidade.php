<?php include("../sistema_mod_include.php"); ?>
<?php
$codigo_estado = $_POST['codigo_estado'];

$sql_cidade = "SELECT codigo_cidade, nome_cidade FROM cidade WHERE codigo_estado = '".$codigo_estado."' AND status = 'L' ORDER BY nome_cidade ASC";
$query_cidades = $pdo->query($sql_cidade);
$resultado_cidade = $query_cidades->fetchAll( PDO::FETCH_ASSOC );
    echo '<option value="" selected>Selecione uma cidade</option>';
foreach($resultado_cidade AS $cidade):
    echo '<option value="'.$cidade["codigo_cidade"].'">'.$cidade["nome_cidade"].'</option>';
endforeach;
?>