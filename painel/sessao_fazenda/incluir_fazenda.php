<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMULÁRIO
	$titulo = addslashes(trim($_POST['titulo']));
	$data = converte_data_mysql($_POST['data']);
	$arquivo = $_FILES['arquivo'];
	$texto = addslashes(trim(fonte_editor($_POST['texto'])));
	$status = addslashes(trim($_POST['status']));

	// Verifica se existe um arquivo
	if(($arquivo["type"] == "image/jpeg") || ($arquivo["type"] == "image/pjpeg")) {
		// Gera titulo unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".remove_acentos($titulo);
	
		// Gera o novo titulo
		$extensao_arquivo = strtolower(substr($arquivo["name"],(strlen($arquivo["name"])-3),strlen($arquivo["name"])));
		$nome_arquivo = $nome_unico.".".$extensao_arquivo;
		
		// Coletando informações sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_arquivo = ftp_put($ftp_conexao,"/www/conteudos/noticia/".$nome_arquivo,$arquivo["tmp_name"],FTP_BINARY);
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);

		// Verifica se deu
		if($ftp_upload_arquivo) {
			redimensiona_imagem(160,110,$nome_arquivo,$nome_arquivo,"../../conteudos/noticia/","/www/conteudos/noticia/mini/","N");
			
			$tamanho = getimagesize("../../conteudos/noticia/".$nome_arquivo);
			
			if($tamanho[0] > 350) {
				$novo_width = 350;
				$novo_height = (350/$tamanho[0])*$tamanho[1];
				
				redimensiona_imagem($novo_width,$novo_height,$nome_arquivo,$nome_arquivo,"../../conteudos/noticia/","/www/conteudos/noticia/","N");
			}
		} else {
			// Log
			salvar_log($_SESSION["id_usuario"],"erro","não foi possivel incluir o arquivo ".$nome_arquivo);
		}
	}
		
	// Inclui
	$sql_inclui = "INSERT INTO noticias (titulo,data,arquivo,texto,status) VALUES ('".$titulo."','".$data."','".$nome_arquivo."','".$texto."','".$status."')";
	$query_inclui = mysql_query($sql_inclui);

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir notícia",$sql_inclui);

		echo "<script>";
		echo "open('gerenciar_noticias.php?aviso=ok&msg=Notícia incluída com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir a notícia.','_self');";
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
<div class="titulo-sessao-azul">Incluir nova notícia</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_noticia" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da notícia</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Título:</td>
                <td><input name="titulo" type="text" id="titulo" size="50" maxlength="150" /></td>
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
                <td valign="top" class="campo-form">Texto:</td>
                <td>
					<script src="../ferramentas/editor/nicEdit.js" type="text/javascript"></script>
                    <script>
                        var id_textarea = 'texto';
                        var id_botao_submit = 'button'
                        var id_botao_ini_copiar = 'iniciar_copia';
                        var id_botao_fim_copiar = 'finalizar_copia';
                        
                        bkLib.onDomLoaded(function() {
                            new nicEditor({buttonList:['save','bold','italic','underline','link','unlink','html'],iconsPath:'../ferramentas/editor/nicEditorIcons.gif'}).panelInstance(id_textarea);
                            
                            document.getElementById(id_botao_submit).onclick = function() {
                                document.getElementById(id_textarea).value = nicEditors.findEditor(id_textarea).getContent();
                            }
                            
                            document.getElementById(id_botao_ini_copiar).onclick = function() {
                                mostrar_camada('boxColarConteudo');
								var conteudo_temp = nicEditors.findEditor(id_textarea).getContent();
                                if(conteudo_temp == "<br>") { conteudo_temp = ""; }
								conteudo_temp = conteudo_temp.replace(/(<br>)/ig,"\n");
								conteudo_temp = conteudo_temp.replace(/(<([^>]+)>)/ig,"");
								document.getElementById('conteudo_externo').value = conteudo_temp;
                                document.getElementById('conteudo_externo').focus();
                            }
                            
                            document.getElementById(id_botao_fim_copiar).onclick = function() {
                                var conteudo_temp = document.getElementById('conteudo_externo').value;
                                var regX = /\n/gi ;
                                
                                conteudo_temp = conteudo_temp.replace(/(<([^>]+)>)/ig,"");
                                s = new String(conteudo_temp);
                                s = s.replace(regX, "<br> \n");
                                conteudo_temp = s;
                                
                                nicEditors.findEditor(id_textarea).setContent(conteudo_temp);
                                ocultar_camada('boxColarConteudo');
                            }
                        });
                    </script>
                    <input type="button" name="iniciar_copia" id="iniciar_copia" onclick="" value="Copiar conteúdo externo" class="button" />
                    <textarea name="texto" cols="60" rows="10" id="texto"></textarea>
                    <div id="boxColarConteudo">
                        <div id="boxColarConteudoFormularioCentral">
                            <div class="boxColarConteudoFormularioCentralTitulo">Copiar conteúdo externo</div>
                            <div class="boxColarConteudoFormularioCentralDetalhe">Para copiar o conteúdo de uma fonte externa, cole o texto abaixo e clica em finalizar cópia.</div>
                            <div class="boxColarConteudoFormularioCentralForm"><textarea name="conteudo_externo" id="conteudo_externo"></textarea></div>
                            <div class="boxColarConteudoFormularioCentralBotoes"><input type="button" name="finalizar_copia" id="finalizar_copia" onclick="" value="Finalizar cópia" class="button" /> - <input type="button" name="cancelar_copia" id="cancelar_copia" onclick="javascript: ocultar_camada('boxColarConteudo');" value="Cancelar" class="button" /></div>
                        </div>
                    </div>
                </td>
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
    <td><div align="right"><input type="submit" name="button" id="button" onclick="" value="Salvar nova notícia" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_noticia = new Spry.Widget.TabbedPanels("incluir_noticia");
</script>
</body>
</html>