//*** INICIO FUNCAO PARA MOSTRAR E OCULTAR CAMPOS ***//
	// Mostra a camada
	function mostrar_camada(camadas) {
		var camada = camadas.split(",");
		for(i=0;i<camada.length;i++) {
			document.getElementById(camada[i]).style.display = "block";
		}
	}

	// Oculta a camada
	function ocultar_camada(camadas) {
		var camada = camadas.split(",");

		for(i=0;i<camada.length;i++) {
			document.getElementById(camada[i]).style.display = "none";
		}
	}
//*** FIM FUNCAO PARA MOSTRAR E OCULTAR CAMPOS ***//

//*** INICIO VARIAS FUNCOES ***//
	// Confirma exclusao
	function confirma_acao(msg,url){
		if(confirm(msg)) {
			open(url,"_self");
		}
	}
//*** FIM VARIAS FUNCOES ***//

//*** INICIO MASCARAS DO FORMULARIO ***//
jQuery(function($) {
	$.mask.definitions['~']='[+-]';
	$('#data').mask('99/99/9999');
	$('#data_inicial').mask('99/99/9999');
	$('#data_final').mask('99/99/9999');
	$('#cep').mask('99999-999');
	$('#telefone').mask('(99) 9999-9999');
});
//*** FIM MASCARAS DO FORMULARIO ***//