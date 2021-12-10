<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMUL�RIO
	$numero = (int) addslashes(trim($_POST['numero']));
	$ano = (int) addslashes(trim($_POST['ano']));
	$imagem = $_FILES['imagem'];
	$arquivo = $_FILES['arquivo'];
	$status = addslashes(trim($_POST['status']));

	// Verifica se existe um imagem
	if(($imagem["type"] == "image/jpeg") || ($imagem["type"] == "image/pjpeg")) {
		// Gera nome unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".$numero."_".$ano;
	
		// Gera o novo nome
		$extensao_imagem = strtolower(substr($imagem["name"],(strlen($imagem["name"])-3),strlen($imagem["name"])));
		$nome_imagem = $nome_unico.".".$extensao_imagem;
		
		// Coletando informa��es sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_imagem = ftp_put($ftp_conexao,"/www/conteudos/folhabrasilis/".$nome_imagem,$imagem["tmp_name"],FTP_BINARY);
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);

		// Verifica se deu
		if($ftp_upload_imagem) {
			redimensiona_imagem(270,143,$nome_imagem,$nome_imagem,"../../conteudos/folhabrasilis/","/www/conteudos/folhabrasilis/","N");
			redimensiona_imagem(160,110,$nome_imagem,$nome_imagem,"../../conteudos/folhabrasilis/","/www/conteudos/folhabrasilis/mini/","N");
		} else {				
			// Log
			salvar_log($_SESSION["id_usuario"],"erro","n�o foi possivel incluir o imagem ".$nome_imagem);
		}
	}

	// Verifica se existe um arquivo
	if($arquivo["type"] == "application/pdf") {
		// Gera titulo unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".$numero."_".$ano;
	
		// Gera o novo titulo
		$extensao_arquivo = strtolower(substr($arquivo["name"],(strlen($arquivo["name"])-3),strlen($arquivo["name"])));
		$nome_arquivo = $nome_unico.".".$extensao_arquivo;
		
		// Coletando informa��es sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_arquivo = ftp_put($ftp_conexao,"/www/conteudos/folhabrasilis/".$nome_arquivo,$arquivo["tmp_name"],FTP_BINARY);
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);

		// Verifica se deu
		if(!$ftp_upload_arquivo) {
			// Log
			salvar_log($_SESSION["id_usuario"],"erro","n�o foi possivel incluir o arquivo ".$nome_arquivo);
		}
	}
		
	// Inclui
	$sql_inclui = "INSERT INTO folhasbrasilis (numero,ano,imagem,arquivo,status) VALUES (".$numero.",".$ano.",'".$nome_imagem."','".$nome_arquivo."','".$status."')";
	$query_inclui = mysql_query($sql_inclui);

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir arquivo folha brasilis",$sql_inclui);

		echo "<script>";
		echo "open('gerenciar_folhasbrasilis.php?aviso=ok&msg=Arquivo (folha brasilis) inclu�do com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=N�o foi poss�vel incluir o arquivo (folha brasilis).','_self');";
		echo "</script>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body onload="document.form1.titulo.focus();">
<div class="titulo-sessao-azul">Incluir novo arquivo (folha brasilis)</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_folhabrasilis" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do arquivo (folha brasilis)</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">N�mero:</td>
                <td><input name="numero" type="text" id="numero" size="4" maxlength="4" /></td>
              </tr>
              <tr>
                <td class="campo-form">Ano:</td>
                <td><input name="ano" type="text" id="ano" size="4" maxlength="4" /></td>
              </tr>
              <tr>
                <td class="campo-form">Imagem:</td>
                <td><input name="imagem" type="file" id="imagem" size="50" /> (.jpg *)</td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.pdf *)</td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L">Liberado</option>
                  <option value="B">Bloqueado</option>
                </select>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
  	<td><div class="info-observacao"><span class="negrito">*</span> Recomenda��es da extens�o e / ou tamanho do arquivo.</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar novo arquivo (folha brasilis)" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_folhabrasilis = new Spry.Widget.TabbedPanels("incluir_folhabrasilis");
</script>
</body>
</html>