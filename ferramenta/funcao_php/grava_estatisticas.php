<?php

// Função para salvar os cliques nos empreendimentos
function empreendimento_clique($id_empreendimento,$codigo_empreendimento_estatistica_local, $tipo_empreendimento_clique) {
	
	//mysql_close();
	
	// Estatísticas
	//$conexao_domus = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl03", "bit3530bit") or die("Erro na conexao - domus");
	//$conecta_mysql_domus = mysql_select_db("lancamentosonl03", $conexao_domus) or die("Nao foi possivel abrir o banco de dados - domus");

	
	$sql_insere_est = "INSERT INTO empreendimento_clique (id_empreendimento,local_empreendimento_clique,data_empreendimento_clique,hora_empreendimento_clique,ip_usuario_empreendimento_clique, tipo_empreendimento_clique) 
						VALUES ('".$id_empreendimento."','".$codigo_empreendimento_estatistica_local."','".date("Ymd", time())."','".date("His", time())."','".$_SERVER['REMOTE_ADDR']."', '".$tipo_empreendimento_clique."')";
	$query_insere_est = mysql_query($sql_insere_est);
	
	//mysql_close();
	
	// site
	//$conexao = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl01", "a54d6as87d") or die("Erro na conexao");
	//$conecta_mysql = mysql_select_db("lancamentosonl01", $conexao) or die("Nao foi possivel abrir o banco de dados");

}

// Função para salvar os contatos dos empreendimentos
function empreendimento_contato($id_empreendimento,$codigo_empreendimento_estatistica_local) {
	
	//mysql_close();
	
	// Estatísticas
	//$conexao_domus = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl03", "bit3530bit") or die("Erro na conexao - domus");
	//$conecta_mysql_domus = mysql_select_db("lancamentosonl03", $conexao_domus) or die("Nao foi possivel abrir o banco de dados - domus");

	
	$sql_insere_est = "INSERT INTO empreendimento_contato (id_empreendimento,local_empreendimento_contato,data_empreendimento_contato,hora_empreendimento_contato,ip_usuario_empreendimento_contato) 
						VALUES (".$id_empreendimento.",".$codigo_empreendimento_estatistica_local.",'".date("Ymd", time())."','".date("His", time())."','".$_SERVER['REMOTE_ADDR']."')";
	$query_insere_est = mysql_query($sql_insere_est);
	
	//mysql_close();
	
	// site
	//$conexao = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl01", "a54d6as87d") or die("Erro na conexao");
	//$conecta_mysql = mysql_select_db("lancamentosonl01", $conexao) or die("Nao foi possivel abrir o banco de dados");

}
?>