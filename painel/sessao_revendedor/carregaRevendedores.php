<?php include("../sistema_mod_include.php"); ?>
<?php
$codigo_cidade = $_POST['codigo_cidade'];

$sql_revendedor = "SELECT 
revendedor.nome_revendedor, 
revendedor.endereco_revendedor, 
revendedor.telefone_revendedor, 
revendedor.codigo_bairro, 
revendedor.status,
bairro.nome_bairro,
estado.codigo_estado,
estado.uf_estado,
cidade.codigo_cidade,
cidade.nome_cidade
FROM revendedor 
JOIN bairro ON revendedor.codigo_bairro = bairro.codigo_bairro
JOIN cidade ON bairro.codigo_cidade = cidade.codigo_cidade
JOIN estado ON cidade.codigo_estado = estado.codigo_estado
WHERE revendedor.status = 'L' AND cidade.codigo_cidade = '".$codigo_cidade."' ORDER BY RAND()";
$query_revendedor = $pdo->query($sql_revendedor);
$resultado_revendedores = $query_revendedor->fetchAll( PDO::FETCH_ASSOC );

foreach($resultado_revendedores AS $item_revendedor):
    echo '<div class="col-md-4 unidade-venda">
            <div class="titulo">'.$item_revendedor["nome_revendedor"].'</div>
            <div class="subtitulo"></div>
            <div class="endereco">'.$item_revendedor["endereco_revendedor"].', '.$item_revendedor["nome_bairro"].', '.$item_revendedor["nome_cidade"].' - '.$item_revendedor["uf_estado"].'<br/><strong>Fone: .'.$item_revendedor["telefone_revendedor"].'</strong></div>
        </div>';
endforeach;
?>