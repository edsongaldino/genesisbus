<?php
// Função para remover as tags html que não podem ser utilizada
function fonte_editor($txt) {
	$txt = str_replace("</div>","<br>",$txt);
	$txt = strip_tags($txt,"<a><b><strong><i><em><br>");
	
	return $txt;
}

// Salvar log - salvar_log('1','walbernir','DELETE FROM LOG WHERE CODIGO=BENI')
function salvar_log($id_usuario_log,$operacao_log,$comando_log, $pdo) {
	// Armazena o log
	$sql_log = 'INSERT INTO sistema_logs (id_usuario,ip,data,hora,operacao,comando) VALUES ("'.$id_usuario_log.'","'.$_SERVER["REMOTE_ADDR"].'","'.date("Ymd",time()).'","'.date("His",time()).'","'.$operacao_log.'","'.addslashes($comando_log).'")';

	// Atualizando o ultimo acesso			
	$atualiza_log = $pdo->prepare($sql_log);
	$atualiza_log->execute();
	
	// Apaga as variáveis
	unset($id_usuario_log,$operacao_log,$comando_log,$sql_log,$atualiza_log);
}

// Função para converte valores decimais para o padrão MySQL
function conv_valor_iso($valor) {
	$valor = str_replace(",",".",str_replace(".","",$valor));
	return $valor;
}

// Função para converte a data de portugues para o padrao Iso
function converte_data_mysql($data) {
	if($data) {
		$data = explode("/",$data);
		return $data[2]."-".$data[1]."-".$data[0];
	} else {
		return "0000-00-00";
	}
}

// Função para converte a data de Iso para o padrao port
function converte_data_port($data) {
	if($data) {
		$data = explode("-",$data);
		return $data[2]."/".$data[1]."/".$data[0];
	} else {
		return "00/00/0000";
	}
}

// Função para verificar se o campo ta vazio, se tiver, coloca N�o informado
function verifica_vazio($valor, $retorna) {
	$valor = trim($valor);

	if($valor) {
		return $valor;	
	} else {
		if($retorna == "") {
			return "-";
		} else {
			return $retorna;
		}
	}
}

// Verifica a sessao;
function verifica_sessao() {
	session_start();
	
	if($_SESSION['usuario_acesso'] != "765q3498b5t9erw87tsd48f6ds4f9849tre87t9r87ter") {
		echo "<script>";
		echo "open('/painel/sistema_login.php','_parent');";
		echo "</script>";
	}
}

// Remove todos os acentos e caracteres espeicias
function remove_acentos($texto) {
	$texto = strtolower(strtr($texto, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ -", "aaaaeeiooouucAAAAEEIOOOUUC__"));
	$texto = preg_match("[^a-zA-Z0-9_]", "", $texto);
	
	return $texto;
}

// Redimensiona a imagem
function redimensiona_imagem($width_img,$height_img,$nome_arquivo,$novo_nome,$diretorio,$diretorio_destino,$forca_proporcao) {
	// Pega tamanho da foto
	$tamanho = getimagesize($diretorio.$nome_arquivo);
	
	// Fator imagem nova
	$fator1 = $width_img / $height_img;
	
	// Fator imagem original
	$fator2 = $tamanho[0] / $tamanho[1];

	// Faz o redimensionamento
	if($fator2 <= $fator1) {
		$width = $width_img;
		$width_ajuste = $tamanho[0]/$width;
		$height = $tamanho[1]/$width_ajuste;		
	} else {
		$height = $height_img;
		$height_ajuste = $tamanho[1]/$height;
		$width = $tamanho[0]/$height_ajuste;		
	}

	// Verifica se � pra for�a a propor��o (ou seja, a imagem ficar toda dentro do tamanho definido)
	if($forca_proporcao == "S") {
		// Verifica novamente pra ver se esta tudo ok
		if($width > $width_img) {
			$width = $width_img;
			$width_ajuste = $tamanho[0]/$width;
			$height = $tamanho[1]/$width_ajuste;
		}
		if($height > $height_img) {
			$height = $height_img;
			$height_ajuste = $tamanho[1]/$height;
			$width = $tamanho[0]/$height_ajuste;
		}
	}

	// Ajusta a margem para centralizar a imagem
	$pos_x = ($width_img-$width)/2;
	$pos_y = ($height_img-$height)/2;
	
	// Grava a imagem
	$tamanho_red = imagecreatetruecolor($width_img, $height_img); // definir tamanho fixo aqui
	imagefill($tamanho_red, 0, 0, imagecolorallocate($tamanho_red, 255, 255, 255)); // aplica o fundo branco
	imagecopyresampled($tamanho_red, imagecreatefromjpeg($diretorio.$nome_arquivo), $pos_x, $pos_y, 0, 0, $width, $height, $tamanho[0], $tamanho[1]);

	// Gera a nova imagem e salva em uma variavel
	ob_start();	
	imagejpeg($tamanho_red, NULL, 100);
	$imagem_final = ob_get_contents();
	ob_end_clean();

	// Cria um arquivo temporario e salva a imagem
	$arquivo_temp = tempnam($_SERVER['DOCUMENT_ROOT']."/painel/ferramentas/tmp", "TMP");
	$arquivo_temp_handle = fopen($arquivo_temp, "w");
	fwrite($arquivo_temp_handle, $imagem_final);
	fclose($arquivo_temp_handle);
	
	// Coletando informa��es sobre o FTP
	$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
	$query_sistema_informacao = mysql_query($sql_sistema_informacao);
	$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
	
	// Abre uma conex�o FTP e salva o arquivo
	$ftp_conexao = @ftp_connect($executa_sistema_informacao['endereco_ftp']); // abre conexao
	$ftp_login = @ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp'])); // conecta
	$ftp_upload_mini = @ftp_put($ftp_conexao,$diretorio_destino.$novo_nome,$arquivo_temp,FTP_BINARY);
	$ftp_fechando_conexao = @ftp_quit($ftp_conexao);
	
	// Apaga arquivo temporario
	@unlink($arquivo_temp);
}

// Redimensiona a imagem
function redimensiona_imagem2($width_img,$height_img,$nome_arquivo,$novo_nome,$diretorio,$diretorio_destino,$forca_proporcao) {
	// Pega tamanho da foto
	$tamanho = getimagesize($diretorio.$nome_arquivo);
	
	// Fator imagem nova
	$fator1 = $width_img / $height_img;
	
	// Fator imagem original
	$fator2 = $tamanho[0] / $tamanho[1];

	// Faz o redimensionamento
	if($fator2 <= $fator1) {
		$width = $width_img;
		$width_ajuste = $tamanho[0]/$width;
		$height = $tamanho[1]/$width_ajuste;		
	} else {
		$height = $height_img;
		$height_ajuste = $tamanho[1]/$height;
		$width = $tamanho[0]/$height_ajuste;		
	}

	// Verifica se � pra for�a a propor��o (ou seja, a imagem ficar toda dentro do tamanho definido)
	if($forca_proporcao == "S") {
		// Verifica novamente pra ver se esta tudo ok
		if($width > $width_img) {
			$width = $width_img;
			$width_ajuste = $tamanho[0]/$width;
			$height = $tamanho[1]/$width_ajuste;
		}
		if($height > $height_img) {
			$height = $height_img;
			$height_ajuste = $tamanho[1]/$height;
			$width = $tamanho[0]/$height_ajuste;
		}
	}

	// Ajusta a margem para centralizar a imagem
	$pos_x = ($width_img-$width)/2;
	$pos_y = ($height_img-$height)/2;
	
	// Grava a imagem
	$tamanho_red = imagecreatetruecolor($width_img, $height_img); // definir tamanho fixo aqui
	imagefill($tamanho_red, 0, 0, imagecolorallocate($tamanho_red, 255, 255, 255)); // aplica o fundo branco
	imagecopyresampled($tamanho_red, imagecreatefromjpeg($diretorio.$nome_arquivo), $pos_x, $pos_y, 0, 0, $width, $height, $tamanho[0], $tamanho[1]);

	// Gera a nova imagem e salva em uma variavel
	ob_start();	
	imagejpeg($tamanho_red, NULL, 100);
	$imagem_final = ob_get_contents();
	ob_end_clean();

	// Cria um arquivo temporario e salva a imagem
	$arquivo_temp = tempnam($_SERVER['DOCUMENT_ROOT']."/painel/ferramentas/tmp", "TMP");
	$arquivo_temp_handle = fopen($arquivo_temp, "w");
	fwrite($arquivo_temp_handle, $imagem_final);
	fclose($arquivo_temp_handle);
	
	// Apaga arquivo temporario
	//unlink($arquivo_temp);
}

// Lista todos os arquivos de um diretorio
function lista_arquivo_diretorio($diretorio) {
	$conteudo_dir = array();

	if(is_dir($diretorio)) { // verifica se � diretorio 
		$abre_diretorio = @opendir($diretorio); // abre o diretorio
	
		if($abre_diretorio) { // verifica se abriu
			while(($arquivo = readdir($abre_diretorio)) != false) { // pega o conteudo
				if(filetype($diretorio.$arquivo) == "file") { // pega somente os arquivos, retirando os subdiretorios
					$conteudo_dir[] = $arquivo;
				}
			}
		
			closedir($abre_diretorio); // fecha o diretorio
			
			return $conteudo_dir; // retorna os arquivos
		}
	}
}


function texto($id_texto, $tipo, $pdo){

	// Coleta os dados
	$sql_texto = "SELECT id_texto, titulo, subtitulo, resumo, texto, arquivo FROM textos WHERE id_texto = '".$id_texto."'";
	$query_texto = $pdo->query($sql_texto);
	$texto = $query_texto->fetch( PDO::FETCH_ASSOC );


	switch($tipo):

		case ($tipo == 'titulo'):
			$resultado = $texto["titulo"];
		break;

		case ($tipo == 'subtitulo'):
			$resultado = $texto["subtitulo"];
		break;

		case ($tipo == 'texto'):
			$resultado = $texto["texto"];
		break;

		case ($tipo == 'imagem'):
			$resultado = $texto["arquivo"];
		break;

		case ($tipo == 'resumo'):
			$resultado = $texto["resumo"];
		break;


	endswitch;

	return ($resultado);
}

function redireciona($url,$target = "_self") {
	echo "<script>";
	echo "window.open('".$url."','".$target."');";
	echo "</script>";
	
	die("Aguarde...");
}

function url_amigavel($string)
{
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z',
        'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
        'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );
    // Traduz os caracteres em $string, baseado no vetor $table
    $string = strtr($string, $table);
    // converte para minúsculo
    $string = strtolower($string);
    // remove caracteres indesejáveis (que não estão no padrão)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    // Remove múltiplas ocorrências de hífens ou espaços
    $string = preg_replace("/[\s-]+/", " ", $string);
    // Transforma espaços e underscores em hífens
    $string = preg_replace("/[\s_]/", "-", $string);
    // retorna a string
    return $string;
}

function protege($valor,$int = false) {
	if($int) {
		return (int) addslashes(trim($valor));
	} else {
		return addslashes(trim($valor));
	}
}

// função para salvar contato
function envia_contato($nome,$email,$telefone,$assunto,$mensagem) {
	

	$corpo_mensagem = "
			
			<p>Ola,<br/> ".utf8_decode($nome)." esteve visitando seu site e lhe enviou uma mensagem.</p>
			<p>
			<b>Nome</b>: ".utf8_decode($nome)."<br>
			<b>E-mail</b>: ".$email."<br>
			<b>Telefone</b>: ".$telefone."<br>
			<b>Assunto</b>: ".utf8_decode($assunto)."<br>
			<b>Mensagem</b>: ".utf8_decode($mensagem)."<br>
			</p>
			<p>Este e um e-mail enviado pelo site www.genesisbus.com.br.</p>
			<p>Genesis Bus</p>
						
			";
	
	// Inicia a classe PHPMailer
	$mail = new PHPMailer(true);

	// Define os dados do servidor e tipo de conexão
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); // Define que a mensagem será SMTP
	
		try {
		$mail->SMTPSecure = "ssl"; // tbm já tentei tls
		$mail->Host = "mail.genesisbus.com.br"; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
		$mail->SMTPAuth   = false;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
		$mail->Port       = 465; //  Usar 587 porta SMTP
		$mail->Username = 'formulario@genesisbus.com.br'; // Usuário do servidor SMTP (endereço de email)
		$mail->Password = 'web@259864'; // Senha do servidor SMTP (senha do email usado)
	
		//Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
		$mail->SetFrom('contato@genesisbus.com.br', 'Site'); //Seu e-mail
		$mail->AddReplyTo($email, $nome); //Seu e-mail
		$mail->Subject = utf8_decode($assunto);//Assunto do e-mail
	
	
		//Define os destinatário(s)
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->AddAddress('contato@genesisbus.com.br', 'Genesis Bus');
		$mail->AddCC('edsongaldino@datapix.com.br', 'Datapix Tecnologia'); // Copia
		//Campos abaixo são opcionais 
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
		//$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
		//$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo
	
	
		//Define o corpo do email
		$mail->MsgHTML($corpo_mensagem); 
	
		////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
		//$mail->MsgHTML(file_get_contents('arquivo.html'));
	
		$mail->Send();
		//echo "Mensagem enviada com sucesso</p>\n";
		return true;
	
		//caso apresente algum erro é apresentado abaixo com essa exceção.
		}catch (phpmailerException $e) {
			echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
		return false;
	}

}

function js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function js_array($array)
{
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}

function codifica($valor,$int = false) {
	$valor = addslashes(trim($valor));
	
	if($int) {
		$valor = (int) $valor;
	}

	return base64_encode(strrev(base64_encode($valor)));
}

function decodifica($valor,$int = false) {
	$valor = base64_decode(strrev(base64_decode($valor)));
	$valor = addslashes(trim($valor));

	if($int) {
		$valor = (int) $valor;
	}
		
	return $valor;
}
?>