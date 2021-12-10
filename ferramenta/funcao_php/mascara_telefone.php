<?php
function mascara_telefone($valor,$info_ddd=true) {
	if($info_ddd) {
		if($valor) {
			$telefone = "(".$valor[0].$valor[1].") ".$valor[2].$valor[3].$valor[4].$valor[5]."-".$valor[6].$valor[7].$valor[8].$valor[9];
			return $telefone;
		} else {
			return false;
		}
	} else {
		if($valor) {
			$telefone = $valor[0].$valor[1].$valor[2].$valor[3]."-".$valor[4].$valor[5].$valor[6].$valor[7];
			return $telefone;
		} else {
			return false;
		}
	}
}
?>