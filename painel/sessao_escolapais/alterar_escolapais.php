<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_escola_pais = (int) base64_decode(addslashes(trim($_GET['id_escola_pais'])));

// Pega os dados
$sql_escola_pais = "SELECT titulo, data, arquivo, texto, status FROM escola_pais WHERE id_escola_pais = ".$id_escola_pais."";
$query_escola_pais = mysql_query($sql_escola_pais);
$executa_escola_pais = mysql_fetch_assoc($query_escola_pais);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$titulo = addslashes(trim($_POST['titulo']));
	$data = converte_data_mysql($_POST['data']);
	$arquivo = $_FILES['arquivo'];
	$texto = addslashes(trim(fonte_editor($_POST['texto'])));
	$status = addslashes(trim($_POST['status']));
		
	// Verifica se existe algum arquivo
	if(($arquivo["type"] == "image/jpeg") || ($arquivo["type"] == "image/pjpeg")) {
		// Gera nome unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".remove_acentos($titulo);
		$nome_arquivo_antigo = $executa_escola_pais["arquivo"];
	
		// Gera o novo nome
		$extensao_arquivo = strtolower(substr($arquivo["name"],(strlen($arquivo["name"])-3),strlen($arquivo["name"])));
		$nome_arquivo = $nome_unico.".".$extensao_arquivo;
	
		// Coletando informações sobre o FTP
		$sql_sistema_informacao = "SELECT endereco_ftp, login_ftp, senha_ftp FROM site_informacoes LIMIT 1";
		$query_sistema_informacao = mysql_query($sql_sistema_informacao);
		$executa_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
		
		$ftp_conexao = ftp_connect($executa_sistema_informacao['endereco_ftp']);
		$ftp_login = ftp_login($ftp_conexao, $executa_sistema_informacao['login_ftp'], base64_decode($executa_sistema_informacao['senha_ftp']));
		$ftp_upload_arquivo = ftp_put($ftp_conexao,"/www/conteudos/escola_pais/".$nome_arquivo,$arquivo["tmp_name"],FTP_BINARY);
		
		// Deleta o arquivo antigo
		if(($ftp_upload_arquivo) && ($nome_arquivo_antigo)) {
			// Deleta o arquivo antigo
			$ftp_deleta_arquivo_antigo1 = ftp_delete($ftp_conexao,"/www/conteudos/escola_pais/".$nome_arquivo_antigo);
			$ftp_deleta_arquivo_antigo2 = ftp_delete($ftp_conexao,"/www/conteudos/escola_pais/mini/".$nome_arquivo_antigo);
			
			// Verifica se deu erro
			if(($ftp_deleta_arquivo_antigo1) && ($ftp_deleta_arquivo_antigo2)) {
				redimensiona_imagem(160,110,$nome_arquivo,$nome_arquivo,"../../conteudos/escola_pais/","/www/conteudos/escola_pais/mini/","N");
				
				$tamanho = getimagesize("../../conteudos/escola_pais/".$nome_arquivo);
				
				if($tamanho[0] > 350) {
					$novo_width = 350;
					$novo_height = (350/$tamanho[0])*$tamanho[1];
					
					redimensiona_imagem($novo_width,$novo_height,$nome_arquivo,$nome_arquivo,"../../conteudos/escola_pais/","/www/conteudos/escola_pais/","N");
				}
			} else {
				// Log
				salvar_log($_SESSION["id_usuario"],"erro","não foi possível deletar o arquivo ".$nome_arquivo_antigo);
			}
		}
		
		// Fecha conexão
		$ftp_fechando_conexao = ftp_quit($ftp_conexao);
	} else {
		$nome_arquivo = $executa_escola_pais["arquivo"];
	}

	// REALIZA A ALTERAÇÃO
	$sql_alterar = "UPDATE escola_pais SET titulo = '".$titulo."', data = '".$data."', arquivo = '".$nome_arquivo."', texto = '".$texto."', status = '".$status."' WHERE id_escola_pais = ".$id_escola_pais."";
	$query_alterar = mysql_query($sql_alterar);

	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar notícia",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_escolaspais.php?aviso=ok&msg=Informações da notícia alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações da notícia.&id_escola_pais=".base64_encode($id_escola_pais)."','_self');";
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
<div class="titulo-sessao-azul">Alterar escola de pais</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_escola_pais=<?php echo base64_encode($id_escola_pais); ?>"  enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_escola_pais" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da escola de pais</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Título:</td>
                <td><input name="titulo" type="text" id="titulo" value="<?php echo $executa_escola_pais['titulo']; ?>" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td class="campo-form">Data:</td>
                <td><input name="data" type="text" id="data" value="<?php echo converte_data_port($executa_escola_pais['data']); ?>" size="10" maxlength="10" /></td>
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
                    <textarea name="texto" cols="60" rows="10" id="texto"><?php echo $executa_escola_pais['texto']; ?></textarea>
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
                  <option value="L" <?php if($executa_escola_pais['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_escola_pais['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
var alterar_escola_pais = new Spry.Widget.TabbedPanels("alterar_escola_pais");
</script>
</body>
</html>