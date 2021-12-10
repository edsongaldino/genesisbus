<?php include("../sistema_mod_include.php"); ?>
<?php
$codigo_cidade = $_POST['codigo_cidade'];

$sql_bairro = "SELECT codigo_bairro, nome_bairro FROM bairro WHERE codigo_cidade = '".$codigo_cidade."' AND status = 'L' ORDER BY nome_bairro ASC";
$query_bairros = $pdo->query($sql_bairro);
$resultado_bairro = $query_bairros->fetchAll( PDO::FETCH_ASSOC );
    echo '<option value="">Selecione o bairro</option>';
foreach($resultado_bairro AS $bairro):
    echo '<option value="'.$bairro["codigo_bairro"].'">'.$bairro["nome_bairro"].'</option>';
endforeach;
?>