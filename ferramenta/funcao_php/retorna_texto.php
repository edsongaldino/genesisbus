<?php

function retorna_texto($id_texto){
	//Abre a conexão
	$pdo = Database::conexao();
	//consulta dados construtora
	$sql_consulta_texto = "SELECT titulo, subtitulo, texto FROM textos WHERE status = 'L' AND id_texto = '".$id_texto."' LIMIT 1";
	$result = $pdo->query( $sql_consulta_texto );
	$resultado_texto = $result->fetch( PDO::FETCH_ASSOC );

	return ($resultado_texto["texto"]);
}

?>