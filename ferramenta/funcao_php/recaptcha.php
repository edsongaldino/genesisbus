<?php
// FUNÇÃO PARA GERAR A IMAGEM RECAPTCHA
function recaptcha($nums) {
	$tam = strlen($nums);
	
	$num_ext = array("zero","um","dois","tres","quatro","cinco","seis","sete","oito","nove");
	
	for($i=0;$i<$tam;$i++) {
		$img .= "<img src='/conteudos/recaptcha/".$num_ext[$nums[$i]].".jpg' alt='Digite o número que vocês está vendo' title='Digite o número que vocês está vendo'>";				  
	}
	
	return $img;  
}
?>