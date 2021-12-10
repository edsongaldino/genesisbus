<?php
function formata_sql_where_filtro($id,$campo,$valor,$operador_valores,$operador_espacobranco,$operador_comparacao) {
	// separa os N valores
	$valores = ($valor);
	
	if($valores) {		
		$valores = explode("|",$valores);
		
		foreach($valores as $valor) {
			// separa os espaco em branco de cada palavra de cada valor
			$valor = trim($valor);
			
			if(strtoupper($operador_comparacao) == strtoupper("LIKE _utf8")) {
				$valor_individual = explode(" ",$valor);
				
				foreach($valor_individual as $valor_individual_palavra) {
					$valor_individual_palavra = trim($valor_individual_palavra);
					$temp = $campo." LIKE '%".$valor_individual_palavra."%'";
					$sql_temp1[] = $temp;
				}
			} else {
				$temp = $campo." ".strtoupper($operador_comparacao)." '".$valor."'";
				$sql_temp1[] = $temp;
			}
			
			$sql_where2[] = "(".implode(" ".$operador_espacobranco." ",$sql_temp1).")";
			unset($sql_temp1);
		}
		
		if(sizeof($sql_where2) > 1) {	
			$final = "(".implode(" ".$operador_valores." ",$sql_where2).")";
		} else {
			$final = implode(" ".$operador_valores." ",$sql_where2);
		}
		
		return $final;
	}
}
?>