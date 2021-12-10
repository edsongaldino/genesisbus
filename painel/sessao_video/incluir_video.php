<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = addslashes(trim($_POST['nome']));
	$descricao = addslashes(trim($_POST['descricao']));
	$url = addslashes(trim($_POST['url']));
	$data = converte_data_mysql(addslashes(trim($_POST['data'])));
	$arquivo = $_FILES['arquivo'];
	$status = addslashes(trim($_POST['status']));

	// Verifica se existe um arquivo
	if(($arquivo["type"] == "image/jpeg") || ($arquivo["type"] == "image/pjpeg")) {
		// Gera nome unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".remove_acentos($nome);
	
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
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);

		// Verifica se deu
		if($ftp_upload_arquivo) {
			redimensiona_imagem(270,143,$nome_arquivo,$nome_arquivo,"../../conteudos/video/","/www/conteudos/video/","N");
			redimensiona_imagem(160,110,$nome_arquivo,$nome_arquivo,"../../conteudos/video/","/www/conteudos/video/mini/","N");
		} else {				
			// Log
			salvar_log($_SESSION["id_usuario"],"erro","não foi possivel incluir o arquivo ".$nome_arquivo);
		}
	}
		
	// Inclui
	$sql_inclui = "INSERT INTO videos (nome,descricao,url,data,arquivo,status) VALUES ('".$nome."','".$descricao."','".$url."','".$data."','".$nome_arquivo."','".$status."')";
	$query_inclui = mysql_query($sql_inclui);

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir vídeo",$sql_inclui);

		echo "<script>";
		echo "open('gerenciar_videos.php?aviso=ok&msg=Vídeo incluído com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir o vídeo.','_self');";
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
<div class="titulo-sessao-azul">Incluir novo vídeo</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_video" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do vídeo</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" size="30" maxlength="30" /></td>
              </tr>
              <tr>
                <td class="campo-form">Descrição:</td>
                <td><input name="descricao" type="text" id="descricao" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">URL:</td>
                <td><input name="url" type="text" id="url" size="50" maxlength="150" /> (Youtube *)</td>
              </tr>
              <tr>
                <td class="campo-form">Data:</td>
                <td><input name="data" type="text" id="data" size="10" maxlength="10" /></td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.jpg *)</td>
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
  	<td><div class="info-observacao"><span class="negrito">*</span> Recomendações da extensão e / ou tamanho do arquivo.</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar novo vídeo" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_video = new Spry.Widget.TabbedPanels("incluir_video");
</script>
</body>
</html>