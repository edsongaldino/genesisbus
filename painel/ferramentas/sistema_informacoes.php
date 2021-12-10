<?php
//Abre a conexão
$pdo = Database::conexao();

//consulta dados site
$sql_consulta_dados = "SELECT empresa, endereco, bairro, cidade, uf, cep, telefone, email_faleconosco, metatags_keywords, metatags_description, titulo_site FROM site_informacoes LIMIT 1";
$resultado_dados = $pdo->query( $sql_consulta_dados );
$resultado_info = $resultado_dados->fetch( PDO::FETCH_ASSOC );

?>