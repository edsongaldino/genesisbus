<?php
// Remove todos os caracteres especiais
function remove_caracter_especial($texto) {
	$texto = strtolower(strtr($texto, "�������������������������� -", "aaaaeeiooouucAAAAEEIOOOUUC  "));
	$texto = preg_replace("[^a-zA-Z0-9_]", "", $texto);
	
	return $texto;
}
?>