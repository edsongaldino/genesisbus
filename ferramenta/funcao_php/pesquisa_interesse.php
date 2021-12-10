<?php

// Função para salvar a pesquisa de interesse
function pesquisa_interesse($pesquisa) {
	mysql_close();
	
	// domus
	$conexao_domus = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl03", "bit3530bit") or die("Erro na conexao - domus");
	$conecta_mysql_domus = mysql_select_db("lancamentosonl03", $conexao_domus) or die("Nao foi possivel abrir o banco de dados - domus");
	
	foreach($pesquisa as $resposta) {
		$enquete = decodifica($resposta["codigo_enquete"],true);
		$opcao = decodifica($resposta["codigo_enquete_opcao"],true);
		
		if(($enquete > 0) && ($opcao > 0)) {
			// insere resposta
			$sql_insere_resposta = "INSERT INTO enquete_opcao_resposta (codigo_enquete_opcao,data_enquete_opcao_resposta,hora_enquete_opcao_resposta,ip_enquete_opcao_resposta) VALUES (".$opcao.",'".date("Ymd", time())."','".date("His", time())."','".$_SERVER['REMOTE_ADDR']."')";
			$query_insere_resposta = mysql_query($sql_insere_resposta) or mascara_erro_mysql($sql_insere_resposta);			
		}
	}
	
	mysql_close();
	
	// site
	$conexao = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl01", "a54d6as87d") or die("Erro na conexao");
	$conecta_mysql = mysql_select_db("lancamentosonl01", $conexao) or die("Nao foi possivel abrir o banco de dados");
}

?>