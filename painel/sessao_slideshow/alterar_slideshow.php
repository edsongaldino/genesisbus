<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_slideshow = (int) base64_decode(addslashes(trim($_GET['id_slideshow'])));

// Pega os dados
$sql_slideshow = "SELECT descricao, tamanho, destino, arquivo, observacoes, data_inicial, data_final, status FROM slideshows WHERE id_slideshow = ".$id_slideshow."";
$query_slideshow = mysql_query($sql_slideshow);
$executa_slideshow = mysql_fetch_assoc($query_slideshow);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$descricao = addslashes(trim($_POST['descricao']));
	$tamanho = addslashes(trim($_POST['tamanho']));
	$destino = addslashes(trim($_POST['destino']));
	$arquivo = $_FILES['arquivo'];
	$observacoes = addslashes(trim($_POST['observacoes']));	
	$data_inicial = converte_data_mysql($_POST['data_inicial']);
	$data_final = converte_data_mysql($_POST['data_final']);	
	$status = addslashes(trim($_POST['status']));
		
	// Verifica se existe algum arquivo
	if(($arquivo["type"] == "image/jpeg") || ($arquivo["type"] == "image/pjpeg") || ($arquivo["type"] == "application/x-shockwave-flash")) {
		// Gera nome unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".remove_acentos($descricao);
		$nome_arquivo_antigo = $executa_slideshow["arquivo"];
	
		// Gera o novo nome
		$extensao_arquivo = strtolower(substr($arquivo["name"],(strlen($arquivo["name"])-3),strlen($arquivo["name"])));
		$nome_arquivo = $nome_unico.".".$extensao_arquivo;
	
		// Coletando informações sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_arquivo = ftp_put($ftp_conexao,"/www/conteudos/slideshow/".$tamanho."/".$nome_arquivo,$arquivo["tmp_name"],FTP_BINARY);
		
		// Deleta o arquivo antigo
		if(($ftp_upload_arquivo) && ($nome_arquivo_antigo)) {
			// Deleta o arquivo antigo
			$ftp_deleta_arquivo_antigo = ftp_delete($ftp_conexao,"/www/conteudos/slideshow/".$executa_slideshow['tamanho']."/".$nome_arquivo_antigo);
			
			// Verifica se deu erro
			if($ftp_deleta_arquivo_antigo) {
				redimensiona_imagem(940,330,$nome_arquivo,$nome_arquivo,"../../conteudos/slideshow/".$tamanho."/","/www/conteudos/slideshow/".$tamanho."/","N");
			} else {
				// Log
				salvar_log($_SESSION["id_usuario"],"erro","não foi possível deletar o arquivo ".$nome_arquivo_antigo);
			}
		}
		
		// Fecha conexão
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);
	} else {
		$nome_arquivo = $executa_slideshow["arquivo"];
		
		// Copia entre as pastas
		if($executa_slideshow['tamanho'] != $tamanho) {
			// Coletando informações sobre o FTP
			$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
			$query_sistema_informacao = mysql_query($sql_sistema_informacao);
			$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
			
			$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
			$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
			$ftp_upload_arquivo = ftp_rename($ftp_conexao,"/www/conteudos/slideshow/".$executa_slideshow['tamanho']."/".$nome_arquivo,"/www/conteudos/slideshow/".$tamanho."/".$nome_arquivo);
			$ftp_fechando_conexao = ftp_quit($ftp_conexao);
		}
	}

	// REALIZA A ALTERAÇÃO
	$sql_alterar = "UPDATE slideshows SET descricao = '".$descricao."', tamanho = '".$tamanho."', destino = '".$destino."', arquivo = '".$nome_arquivo."', observacoes = '".$observacoes."', data_inicial = '".$data_inicial."', data_final = '".$data_final."', status = '".$status."' WHERE id_slideshow = ".$id_slideshow."";
	$query_alterar = mysql_query($sql_alterar);

	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar slideshow",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_slideshows.php?aviso=ok&msg=Informações do slideshow alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações do slideshow.&id_slideshow=".$id_slideshow."','_self');";
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
<body onload="document.form1.descricao.focus();">
<div class="titulo-sessao-azul">Alterar slideshow</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_slideshow=<?php echo base64_encode($id_slideshow); ?>" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_slideshow" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do slideshow</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Descrição:</td>
                <td><input name="descricao" type="text" id="descricao" value="<?php echo $executa_slideshow['descricao']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Tamanho:</td>
                <td>
                <select name="tamanho" id="tamanho">
                  <option value="940x330" <?php if($executa_slideshow['tamanho'] == "940x330") { echo 'selected="selected"'; } ?>>940x330</option>
                </select>
                </td>
              </tr>              
              <tr>
                <td class="campo-form">Destino:</td>
                <td>http:// <input name="destino" type="text" id="destino" value="<?php echo $executa_slideshow['destino']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.jpg 940x330 px *)</td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">Observa&ccedil;&otilde;es:</td>
                <td><textarea name="observacoes" cols="50" rows="5" id="observacoes"><?php echo $executa_slideshow['observacoes']; ?></textarea></td>
              </tr>
              <tr>
                <td class="campo-form">Data inicial:</td>
                <td><input name="data_inicial" type="text" id="data_inicial" value="<?php echo converte_data_port($executa_slideshow['data_inicial']); ?>" size="10" maxlength="10" /></td>
              </tr>
              <tr>
                <td class="campo-form">Data final:</td>
                <td><input name="data_final" type="text" id="data_final" value="<?php echo converte_data_port($executa_slideshow['data_final']); ?>" size="10" maxlength="10" /></td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_slideshow['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_slideshow['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
var alterar_slideshow = new Spry.Widget.TabbedPanels("alterar_slideshow");
</script>
</body>
</html>