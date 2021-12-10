<?php

// Função para calcular total
function calcula_total_atendimento($situacao) {
	
	if($situacao == 0){
		
		if(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 1) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento FROM atendimento";
		
		// gestor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 2) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
							AND publico_construtoras.id_construtora = ".protege($_SESSION["codigo_construtora_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
							
							

		// consultor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 3) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";

											
		//Gestor da Imobiliária
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 4) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN parceiro_usuario ON (atendimento.codigo_usuario = parceiro_usuario.codigo_usuario)
								INNER JOIN usuario ON (usuario.codigo_usuario = atendimento.codigo_usuario)
							AND parceiro_usuario.codigo_parceiro = ".protege($_SESSION["codigo_parceiro_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
							
		//Consultor da Imobiliária
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 5) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
		}
		
	}else{
		
		if(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 1) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento FROM atendimento WHERE atendimento.codigo_situacao = '".$situacao."'";
		
		// gestor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 2) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND publico_construtoras.id_construtora = ".protege($_SESSION["codigo_construtora_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
							
							

		// consultor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 3) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";

											
		//Gestor da Imobiliária
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 4) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN parceiro_usuario ON (atendimento.codigo_usuario = parceiro_usuario.codigo_usuario)
								INNER JOIN usuario ON (usuario.codigo_usuario = atendimento.codigo_usuario)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND parceiro_usuario.codigo_parceiro = ".protege($_SESSION["codigo_parceiro_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
							
		//Consultor da Imobiliária
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 5) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
		}
	}
	
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$total_consulta = mysql_num_rows($query_consulta);
	
	return $total_consulta;

}


// Função para calcular total
function calcula_total_unidades($empreendimento) {
	$total_unidades = 0;
	$sql_consulta = "SELECT 
						(publico_torres.total_andar * publico_torres.unidade_andar) + qtd_apartamento_terreo + qtd_cobertura AS total_unidades
						FROM publico_torres
					WHERE publico_torres.id_empreendimento = ".$empreendimento."
					";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	while($resultado = mysql_fetch_assoc($query_consulta)){
		$total_unidades = $total_unidades + $resultado["total_unidades"];
	}
	
	return $total_unidades;

}


// Função para calcular total de unidades geral
function total_unidades() {
	$total_unidades = 0;
	$sql_consulta = "SELECT COUNT(*) AS total_unidades FROM publico_unidades WHERE publico_unidades.situacao = 'D'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	while($resultado = mysql_fetch_assoc($query_consulta)){
		$total_unidades = $total_unidades + $resultado["total_unidades"];
	}
	
	return $total_unidades;

}

// Função para calcular total de unidades geral
function total_empreendimentos() {
	$sql_consulta = "SELECT COUNT(*) AS total_empreendimentos FROM publico_empreendimentos WHERE publico_empreendimentos.`status` = 'L'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);

	return $resultado["total_empreendimentos"];

}

// Função para calcular total de unidades geral
function total_construtoras() {
	$sql_consulta = "SELECT COUNT(*) AS total_construtoras FROM publico_construtoras WHERE publico_construtoras.`status` = 'L'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);

	return $resultado["total_construtoras"];

}


// Função para calcular total de cliques do empreendimento
function estatisticas_empreendimentos($tipo_relatorio,$local,$id_empreendimento,$id_construtora,$modalidade,$data_inicial) {

	/*
	1 - Cliques por Empreendimento 
	2 - Cliques por Modalidade
	3 - Contatos por Empreendimento
	4 - Contatos por Modalidade
	5 - Contatos por Construtora
	6 - Cliques por Construtora
	7 - Views por Empreendimento
	8 - Views por Construtora
	9 - Views por Modalidade
	10 -
	11 -
	12 - Views por Plataforma
	*/

	$pdo = Database::conexao();

	if($data_inicial == 0){
		$data_inicial = "2016-05-10";
	}else{
		$data_inicial = $data_inicial;
	}

	switch($tipo_relatorio){

		case 1:
			$sql_consulta = "SELECT COUNT(empreendimento_clique.codigo_empreendimento_clique) AS total_estatistica 
								FROM empreendimento_clique 
								WHERE empreendimento_clique.id_empreendimento = '".$id_empreendimento."' AND empreendimento_clique.data_empreendimento_clique > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

		case 2:
			$sql_consulta = "SELECT COUNT(empreendimento_clique.codigo_empreendimento_clique) AS total_estatistica 
								FROM empreendimento_clique
								JOIN view_empreendimentos ON (empreendimento_clique.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE view_empreendimentos.modalidade = '".$modalidade."' AND empreendimento_clique.data_empreendimento_clique > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

		case 3:
			$sql_consulta = "SELECT COUNT(empreendimento_contato.codigo_empreendimento_contato) AS total_estatistica FROM empreendimento_contato
								WHERE empreendimento_contato.id_empreendimento = '".$id_empreendimento."' AND empreendimento_contato.data_empreendimento_contato > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

		case 4:
			$sql_consulta = "SELECT COUNT(empreendimento_contato.codigo_empreendimento_contato) AS total_estatistica 
								FROM empreendimento_contato
								JOIN view_empreendimentos ON (empreendimento_contato.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE view_empreendimentos.modalidade = '".$modalidade."' AND empreendimento_contato.data_empreendimento_contato > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

		case 5:
			$sql_consulta = "SELECT COUNT(empreendimento_contato.codigo_empreendimento_contato) AS total_estatistica 
								FROM empreendimento_contato
								JOIN view_empreendimentos ON (empreendimento_contato.id_empreendimento = view_empreendimentos.id_empreendimento)
								JOIN view_construtoras ON (view_empreendimentos.id_construtora = view_construtoras.id_construtora)
								WHERE view_construtoras.id_construtora = '".$id_construtora."' AND empreendimento_contato.data_empreendimento_contato > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

		case 6:
			$sql_consulta = "SELECT COUNT(empreendimento_clique.codigo_empreendimento_clique) AS total_estatistica 
								FROM empreendimento_clique 
								JOIN view_empreendimentos ON (empreendimento_clique.id_empreendimento = view_empreendimentos.id_empreendimento)
								JOIN view_construtoras ON (view_empreendimentos.id_construtora = view_construtoras.id_construtora)
								WHERE view_construtoras.id_construtora = '".$id_construtora."' AND empreendimento_clique.data_empreendimento_clique > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

		case 7:

			$sql_consulta_view_dia = "SELECT SUM(empreendimento_visualizacao_dia.total_empreendimento_visualizacao_dia) AS total_view_dia
								FROM empreendimento_visualizacao_dia
								WHERE empreendimento_visualizacao_dia.id_empreendimento = '".$id_empreendimento."' AND empreendimento_visualizacao_dia.data_empreendimento_visualizacao > '".$data_inicial."'
								GROUP BY empreendimento_visualizacao_dia.id_empreendimento";
			$result = $pdo->query( $sql_consulta_view_dia );
			$resultado_view_dia = $result->fetch( PDO::FETCH_ASSOC );

			$sql_consulta = "SELECT COUNT(empreendimento_visualizacao.codigo_empreendimento_visualizacao) AS total_estatistica FROM empreendimento_visualizacao
								JOIN view_empreendimentos ON (empreendimento_visualizacao.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE view_empreendimentos.id_empreendimento = '".$id_empreendimento."' AND empreendimento_visualizacao.data_empreendimento_visualizacao > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );

			$resultado["total_estatistica"] = $resultado["total_estatistica"] + $resultado_view_dia["total_view_dia"];


		break;

		case 8:

			$sql_consulta_view_dia = "SELECT SUM(empreendimento_visualizacao_dia.total_empreendimento_visualizacao_dia) AS total_view_dia
								FROM empreendimento_visualizacao_dia
								JOIN view_empreendimentos ON (empreendimento_visualizacao_dia.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE view_empreendimentos.id_construtora = '".$id_construtora."' AND empreendimento_visualizacao_dia.data_empreendimento_visualizacao > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta_view_dia );
			$resultado_view_dia = $result->fetch( PDO::FETCH_ASSOC );

			$sql_consulta = "SELECT COUNT(empreendimento_visualizacao.codigo_empreendimento_visualizacao) AS total_estatistica FROM empreendimento_visualizacao
								JOIN view_empreendimentos ON (empreendimento_visualizacao.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE view_empreendimentos.id_construtora = '".$id_construtora."' AND empreendimento_visualizacao.data_empreendimento_visualizacao > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );

			$resultado["total_estatistica"] = $resultado["total_estatistica"] + $resultado_view_dia["total_view_dia"];

		break;

		case 9:

			$sql_consulta_view_dia = "SELECT SUM(empreendimento_visualizacao_dia.total_empreendimento_visualizacao_dia) AS total_view_dia
								FROM empreendimento_visualizacao_dia
								JOIN view_empreendimentos ON (empreendimento_visualizacao_dia.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE view_empreendimentos.modalidade = '".$modalidade."' AND empreendimento_visualizacao_dia.data_empreendimento_visualizacao > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta_view_dia );
			$resultado_view_dia = $result->fetch( PDO::FETCH_ASSOC );

			$sql_consulta = "SELECT COUNT(empreendimento_visualizacao.codigo_empreendimento_visualizacao) AS total_estatistica FROM empreendimento_visualizacao
								JOIN view_empreendimentos ON (empreendimento_visualizacao.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE view_empreendimentos.modalidade = '".$modalidade."' AND empreendimento_visualizacao.data_empreendimento_visualizacao > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );

			$resultado["total_estatistica"] = $resultado["total_estatistica"] + $resultado_view_dia["total_view_dia"];

		break;

		case 10:

			$sql_consulta_view_dia = "SELECT SUM(empreendimento_visualizacao_dia.total_empreendimento_visualizacao_dia) AS total_view_dia
								FROM empreendimento_visualizacao_dia
								JOIN view_empreendimentos ON (empreendimento_visualizacao_dia.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE empreendimento_visualizacao_dia.data_empreendimento_visualizacao > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta_view_dia );
			$resultado_view_dia = $result->fetch( PDO::FETCH_ASSOC );

			$sql_consulta = "SELECT COUNT(empreendimento_visualizacao.codigo_empreendimento_visualizacao) AS total_estatistica FROM empreendimento_visualizacao
								JOIN view_empreendimentos ON (empreendimento_visualizacao.id_empreendimento = view_empreendimentos.id_empreendimento)
								WHERE empreendimento_visualizacao.data_empreendimento_visualizacao > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );

			$resultado["total_estatistica"] = $resultado["total_estatistica"] + $resultado_view_dia["total_view_dia"];

		break;

		case 11:
			$sql_consulta = "SELECT COUNT(empreendimento_clique.codigo_empreendimento_clique) AS total_estatistica 
								FROM empreendimento_clique 
								WHERE empreendimento_clique.data_empreendimento_clique > '".$data_inicial."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

		case 12:
			$sql_consulta = "SELECT COUNT(empreendimento_clique.codigo_empreendimento_clique) AS total_estatistica 
								FROM empreendimento_clique 
								WHERE empreendimento_clique.local_empreendimento_clique = '".$local."'";
			$result = $pdo->query( $sql_consulta );
			$resultado = $result->fetch( PDO::FETCH_ASSOC );
		break;

	}
	
	return $resultado["total_estatistica"];

}

?>