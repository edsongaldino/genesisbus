<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_video = (int) base64_decode(addslashes(trim($_GET['id_video'])));

// Pega os dados
$sql_video = "SELECT nome, descricao, url, data, arquivo, status FROM videos WHERE id_video = ".$id_video."";
$query_video = mysql_query($sql_video);
$executa_video = mysql_fetch_assoc($query_video);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = addslashes(trim($_POST['nome']));
	$descricao = addslashes(trim($_POST['descricao']));
	$url = addslashes(trim($_POST['url']));
	$data = converte_data_mysql(addslashes(trim($_POST['data'])));
	$arquivo = $_FILES['arquivo'];
	$status = addslashes(trim($_POST['status']));
	
	// Verifica se existe algum arquivo
	if(($arquivo["type"] == "image/jpeg") || ($arquivo["type"] == "image/pjpeg")) {
		// Gera nome unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".remove_acentos($nome);
		$nome_arquivo_antigo = $executa_video["arquivo"];
	
		// Gera o novo nome
		$extensao_arquivo = strtolower(substr($arquivo["name"],(strlen($arquivo["name"])-3),strlen($arquivo["name"])));
		$nome_arquivo = $nome_unico.".".$extensao_arquivo;
	
		// Coletando informações sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_arquivo = ftp_put($ftp_conexao,"/www/conteudos/video/".$nome_arquivo,$arquivo["tmp_name"],FTP_BINARY);
		
		// Deleta o arquivo antigo
		if(($ftp_upload_arquivo) && ($nome_arquivo_antigo)) {
			redimensiona_imagem(270,143,$nome_arquivo,$nome_arquivo,"../../conteudos/video/","/www/conteudos/video/","N");
			redimensiona_imagem(160,110,$nome_arquivo,$nome_arquivo,"../../conteudos/video/","/www/conteudos/video/mini/","N");
					
			// Deleta o arquivo antigo
			$ftp_deleta_arquivo_antigo1 = ftp_delete($ftp_conexao,"/www/conteudos/video/".$nome_arquivo_antigo);
			$ftp_deleta_arquivo_antigo2 = ftp_delete($ftp_conexao,"/www/conteudos/video/mini/".$nome_arquivo_antigo);
			
			// Verifica se deu erro
			if((!$ftp_deleta_arquivo_antigo) && (!$ftp_deleta_arquivo_antigo2)) {
				// Log
				salvar_log($_SESSION["id_usuario"],"erro","não foi possível deletar o arquivo ".$nome_arquivo_antigo);
			}
		}
		
		// Fecha conexão
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);
	} else {
		$nome_arquivo = $executa_video["arquivo"];
	}

	// REALIZA A ALTERAÇÃO
	$sql_alterar = "UPDATE videos SET nome = '".$nome."', descricao = '".$descricao."', url = '".$url."', data = '".$data."', arquivo = '".$nome_arquivo."', status = '".$status."' WHERE id_video = ".$id_video."";
	$query_alterar = mysql_query($sql_alterar);

	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar vídeo",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_videos.php?aviso=ok&msg=Informações do vídeo alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações do vídeo.&id_video=".$id_video."','_self');";
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
<body onload="document.form1.nome.focus();">
<div class="titulo-sessao-azul">Alterar vídeo</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_video=<?php echo base64_encode($id_video); ?>"  enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_video" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do vídeo</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" value="<?php echo $executa_video['nome']; ?>" size="30" maxlength="30" /></td>
              </tr>
              <tr>
                <td class="campo-form">Descrição:</td>
                <td><input name="descricao" type="text" id="descricao" value="<?php echo $executa_video['descricao']; ?>" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">URL:</td>
                <td><input name="url" type="text" id="url" value="<?php echo $executa_video['url']; ?>" size="50" maxlength="150" /> (Youtube *)</td>
              </tr>
              <tr>
                <td class="campo-form">Data:</td>
                <td><input name="data" type="text" id="data" value="<?php echo converte_data_port($executa_video['data']); ?>" size="10" maxlength="10" /></td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.jpg *)</td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_video['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_video['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
var alterar_video = new Spry.Widget.TabbedPanels("alterar_video");
</script>
</body>
</html>