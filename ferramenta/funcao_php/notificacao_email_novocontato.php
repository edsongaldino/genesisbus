<?php

function envia_email($destino, $assunto, $mensagem)
{
        
	$mail = new PHPMailer;

	$mail->isSMTP();                                      		// Set mailer to use SMTP
	$mail->CharSet = "UTF-8";
	$mail->Host = gethostbyname("smtp.datapix.com.br");  		// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               		// Enable SMTP authentication
	$mail->Username = 'formulario@datapix.com.br';    			// SMTP username
	$mail->Password = 'web259864';                           	// SMTP password
	$mail->Port = 587;

	$mail->setFrom("contato@lancamentosonline.com.br", 'Lançamentos Online');
	$mail->addAddress($destino);// Add a recipient
	$mail->addReplyTo('contato@lancamentosonline.com.br', 'Lançamentos Online');
	$mail->isHTML(true);// Set email format to HTML

	$mail->Subject = $assunto;
	$mail->Body    = $mensagem;
				
	$result = $mail->Send();

	if($result){
	   return false;
	}else{
	   return true;
	}
  
}


//Função alterada em 11/08/2015 por Edson Galdino
function notificacao_email_novocontato_2015($codigo_atendimento,$codigo_empreendimento) {
	
	$flag_erro = false;
		
	// consulta
	$sql_consulta = "
					SELECT
						atendimento_log.data_atendimento_log, atendimento_log.hora_atendimento_log,
						cliente_email.descricao_cliente_email,
						colaborador.codigo_colaborador,
						colaborador_email.descricao_colaborador_email
					FROM atendimento
						JOIN (
							SELECT atendimento_log.codigo_atendimento, atendimento_log.data_atendimento_log, atendimento_log.hora_atendimento_log FROM atendimento_log WHERE atendimento_log.codigo_log_tipo = 1
						) AS atendimento_log ON (atendimento.codigo_atendimento = atendimento_log.codigo_atendimento)
						JOIN colaborador ON (atendimento.codigo_colaborador = colaborador.codigo_colaborador)
						LEFT JOIN colaborador_email ON (colaborador.codigo_colaborador = colaborador_email.codigo_colaborador)
						JOIN cliente ON (atendimento.codigo_cliente = cliente.codigo_cliente)
						JOIN cliente_email ON (cliente.codigo_cliente = cliente_email.codigo_cliente)
					WHERE atendimento.codigo_atendimento = ".$codigo_atendimento."
					GROUP BY atendimento.codigo_atendimento
					LIMIT 1
					";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro_mysql($sql_consulta);
	$resultado_consulta = mysql_fetch_assoc($query_consulta);

	// log
	$data_log = date("Y-m-d", time());
	$hora_log = date("H:i:s", time());
	
	// incluir (log notificacao por e-mail)
	$sql_incluir_log = "INSERT INTO atendimento_log (codigo_usuario,codigo_log_tipo,codigo_atendimento,data_atendimento_log,hora_atendimento_log,ip_atendimento_log) VALUES (".$resultado_consulta["codigo_colaborador"].",".(12).",".$codigo_atendimento.",'".$data_log."','".$hora_log."','".$_SERVER["REMOTE_ADDR"]."')";
	$query_incluir_log = mysql_query($sql_incluir_log) or mascara_erro_mysql($sql_incluir_log);
	
	if(!$query_incluir_log) {
		$flag_erro = true;
	}
	
	if(!$flag_erro) {
		
		// Envia para o usuário
		$msg_mensagem = '
						<html>
						<body>
						<div align="center"><img src="http://www.lancamentosonline.com.br/externo/notificacao-email-contato-2015-'.codifica($codigo_atendimento.$resultado_consulta["data_atendimento_log"].$resultado_consulta["hora_atendimento_log"]).'.jpg" /></div>
						<div align="center">Este e-mail foi enviado para: '.$resultado_consulta["descricao_cliente_email"].'<br />Caso não queira mais receber nossos emails, remova aqui.</div>
						</body>
						</html>
						';

        $assunto = "Seu contato foi recebido";
        $destino = $resultado_consulta["descricao_cliente_email"];
		$flag_erro = envia_email($destino, $assunto, $msg_mensagem);			
	}



	if($flag_erro) {
		return false;
	} else {
		
		mysql_close();
		
		// site
		$conexao = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl01", "a54d6as87d") or die("Erro na conexao");
		$conecta_mysql = mysql_select_db("lancamentosonl01", $conexao) or die("Nao foi possivel abrir o banco de dados");
		
		// consulta construtora
		$sql_consulta_construtora = "SELECT empreendimentos.id_construtora FROM empreendimentos WHERE empreendimentos.id_empreendimento = ".$codigo_empreendimento."";
		$query_consulta_construtora = mysql_query($sql_consulta_construtora) or mascara_erro_mysql($sql_consulta_construtora);
		$resultado_consulta_construtora = mysql_fetch_assoc($query_consulta_construtora);
		
		// consulta usuarios da construtora
		$sql_consulta_contatos_construtora = "SELECT contato_construtora.nome_contato_construtora, contato_construtora.email_contato_construtora FROM contato_construtora WHERE contato_construtora.id_construtora = '".$resultado_consulta_construtora["id_construtora"]."' AND contato_construtora.email_contato_construtora <> '' AND contato_construtora.situacao_contato_construtora = 'L' ORDER BY contato_construtora.nome_contato_construtora";
		$query_consulta_contatos_construtora = mysql_query($sql_consulta_contatos_construtora) or mascara_erro_mysql($sql_consulta_contatos_construtora);
		
		
		// envio corretor
		while($resultado_consulta_contatos_construtora = mysql_fetch_assoc($query_consulta_contatos_construtora)) {
			
			$destino = $resultado_consulta_contatos_construtora["email_contato_construtora"];
	        $assunto = "Novo contato ".str_pad($codigo_atendimento, 6, "0", STR_PAD_LEFT)." recebido";
			$flag_erro = envia_email($destino, $assunto, $msg_mensagem);

		}
		
		mysql_close();
		
		// Lançamentos Online
		$conexao_painel_corretor = mysql_connect("mysql.lancamentosonline.com.br", "lancamentosonl", "asd6844385") or die("Erro na conexao - Painel");
		$conecta_mysql_painel_corretor = mysql_select_db("lancamentosonl", $conexao_painel_corretor) or die("Nao foi possivel abrir o banco de dados - Painel");
		
		// envio administrador
        $destino = "contato@lancamentosonline.com.br";
        $assunto = "(Copia) Novo contato ".str_pad($codigo_atendimento, 6, "0", STR_PAD_LEFT)." recebido";

		$flag_erro = envia_email($destino, $assunto, $msg_mensagem);	
		
		return true;
	}
	return true;
}

?>