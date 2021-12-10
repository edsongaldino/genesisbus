<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_folhabrasilis = (int) base64_decode(addslashes(trim($_GET['id_folhabrasilis'])));

// Pega os dados
$sql_folhabrasilis = "SELECT numero, ano, imagem, arquivo, status FROM folhasbrasilis WHERE id_folhabrasilis = ".$id_folhabrasilis."";
$query_folhabrasilis = mysql_query($sql_folhabrasilis);
$executa_folhabrasilis = mysql_fetch_assoc($query_folhabrasilis);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$numero = (int) addslashes(trim($_POST['numero']));
	$ano = (int) addslashes(trim($_POST['ano']));
	$imagem = $_FILES['imagem'];
	$arquivo = $_FILES['arquivo'];
	$status = addslashes(trim($_POST['status']));
	
	// Verifica se existe algum imagem
	if(($imagem["type"] == "image/jpeg") || ($imagem["type"] == "image/pjpeg")) {
		// Gera nome unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".$numero."_".$ano;
		$nome_imagem_antigo = $executa_folhabrasilis["imagem"];
	
		// Gera o novo nome
		$extensao_imagem = strtolower(substr($imagem["name"],(strlen($imagem["name"])-3),strlen($imagem["name"])));
		$nome_imagem = $nome_unico.".".$extensao_imagem;
	
		// Coletando informações sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_imagem = ftp_put($ftp_conexao,"/www/conteudos/folhabrasilis/".$nome_imagem,$imagem["tmp_name"],FTP_BINARY);
		
		// Deleta o imagem antigo
		if(($ftp_upload_imagem) && ($nome_imagem_antigo)) {
			redimensiona_imagem(270,143,$nome_imagem,$nome_imagem,"../../conteudos/folhabrasilis/","/www/conteudos/folhabrasilis/","N");
			redimensiona_imagem(160,110,$nome_imagem,$nome_imagem,"../../conteudos/folhabrasilis/","/www/conteudos/folhabrasilis/mini/","N");
					
			// Deleta o imagem antigo
			$ftp_deleta_imagem_antigo1 = ftp_delete($ftp_conexao,"/www/conteudos/folhabrasilis/".$nome_imagem_antigo);
			$ftp_deleta_imagem_antigo2 = ftp_delete($ftp_conexao,"/www/conteudos/folhabrasilis/mini/".$nome_imagem_antigo);
			
			// Verifica se deu erro
			if((!$ftp_deleta_imagem_antigo1) && (!$ftp_deleta_imagem_antigo2)) {
				// Log
				salvar_log($_SESSION["id_usuario"],"erro","não foi possível deletar o imagem ".$nome_imagem_antigo);
			}
		}
		
		// Fecha conexão
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);
	} else {
		$nome_imagem = $executa_folhabrasilis["imagem"];
	}
		
	// Verifica se existe algum arquivo
	if($arquivo["type"] == "application/pdf") {
		// Gera nome unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".$numero."_".$ano;
		$nome_arquivo_antigo = $executa_folhabrasilis["arquivo"];
	
		// Gera o novo nome
		$extensao_arquivo = strtolower(substr($arquivo["name"],(strlen($arquivo["name"])-3),strlen($arquivo["name"])));
		$nome_arquivo = $nome_unico.".".$extensao_arquivo;
	
		// Coletando informações sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_arquivo = ftp_put($ftp_conexao,"/www/conteudos/folhabrasilis/".$nome_arquivo,$arquivo["tmp_name"],FTP_BINARY);
		
		// Deleta o arquivo antigo
		if(($ftp_upload_arquivo) && ($nome_arquivo_antigo)) {
			// Deleta o arquivo antigo
			$ftp_deleta_arquivo_antigo = ftp_delete($ftp_conexao,"/www/conteudos/folhabrasilis/".$nome_arquivo_antigo);
			
			// Verifica se deu erro
			if(!$ftp_deleta_arquivo_antigo) {
				// Log
				salvar_log($_SESSION["id_usuario"],"erro","não foi possível deletar o arquivo ".$nome_arquivo_antigo);
			}
		}
		
		// Fecha conexão
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);
	} else {
		$nome_arquivo = $executa_folhabrasilis["arquivo"];
	}

	// REALIZA A ALTERAÇÃO
	$sql_alterar = "UPDATE folhasbrasilis SET numero = ".$numero.", ano = ".$ano.", imagem = '".$nome_imagem."', arquivo = '".$nome_arquivo."', status = '".$status."' WHERE id_folhabrasilis = ".$id_folhabrasilis."";
	$query_alterar = mysql_query($sql_alterar);

	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar arquivo (folha brasilis)",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_folhasbrasilis.php?aviso=ok&msg=Informações do arquivo (folha brasilis) alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações do arquivo (folha brasilis).&id_folhabrasilis=".base64_encode($id_folhabrasilis)."','_self');";
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
<div class="titulo-sessao-azul">Alterar arquivo (folha brasilis)</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_folhabrasilis=<?php echo base64_encode($id_folhabrasilis); ?>"  enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_folhabrasilis" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do arquivo (folha brasilis)</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Número:</td>
                <td><input name="numero" type="text" id="numero" value="<?php echo $executa_folhabrasilis['numero']; ?>" size="4" maxlength="4" /></td>
              </tr>
              <tr>
                <td class="campo-form">Ano:</td>
                <td><input name="ano" type="text" id="ano" value="<?php echo $executa_folhabrasilis['ano']; ?>" size="4" maxlength="4" /></td>
              </tr>
              <tr>
                <td class="campo-form">Imagem:</td>
                <td><input name="imagem" type="file" id="imagem" size="50" /> (.jpg *)</td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.pdf  *)</td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_folhabrasilis['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_folhabrasilis['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
  	<td><div class="info-observacao"><span class="negrito">*</span> Recomendações da extensão e / ou tamanho do arquivo.</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar altera&ccedil;&otilde;es" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var alterar_folhabrasilis = new Spry.Widget.TabbedPanels("alterar_folhabrasilis");
</script>
</body>
</html>