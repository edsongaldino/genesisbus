<?php
	$arquivo = "emails.txt";
	
	$tamanho = filesize("$arquivo"); // pega o tamanho do arquivo em bytes
	
	$ext = explode (".",$arquivo);
	
	// enviar os cabeçalhos HTTP para o browser
	header("Content-Type: application/save"); 
	header("Content-Length: $tamanho");
	header("Content-Disposition: attachment; filename=$nome.$ext[1]"); 
	header("Content-Transfer-Encoding: binary");
	
	// abrir e enviar o arquivo
	$fp = fopen("$arquivo", "r"); 
	fpassthru($fp); 
	fclose($fp);

?>