<?php

// Função para calcular diferença entre datas
function calcula_diferenca_datas($data1, $data2){
	
	// Criacao das datas
	$data_inicial = \DateTime::createFromFormat('Y-m-d', $data1);
	$data_final   = \DateTime::createFromFormat('Y-m-d', $data2);

	// Calculo da diferenca entre as datas
	$diferenca = $data_final->diff($data_inicial);

	// Exibicao da diferenca em dias
	return $diferenca->format("%a");

}	


// Função para reduzir dias em data atual
function diminui_dias_data($dias, $data){
	
	$data_inicial = date('Y-m-d', strtotime('-'.$dias.' days', strtotime($data)));

	// Exibicao da data 
	return $data_inicial;

}


?>