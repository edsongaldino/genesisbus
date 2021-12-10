<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome_produto = addslashes(trim($_POST['nome']));
	$id_subtipo_produto = addslashes(trim($_POST['subtipo']));
  $status = addslashes(trim($_POST['status']));
  $texto_produto = addslashes(trim(fonte_editor($_POST['texto'])));
	
	// Inclui o produto
	$sql_inclui = "INSERT INTO produto (nome_produto,id_subtipo_produto,texto_produto,status) VALUES ('".$nome_produto."','".$id_subtipo_produto."','".$texto_produto."','".$status."')";
  $query_inclui = $pdo->prepare($sql_inclui);
  $query_inclui->execute();

	$id_produto = $pdo->lastInsertId();

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		
		$ftp_cria_pasta_raiz = mkdir("../../conteudos/produto/".$id_produto);
		$ftp_cria_pasta_mini = mkdir("../../conteudos/produto/".$id_produto."/mini");
		$ftp_cria_pasta_destaque = mkdir("../../conteudos/produto/".$id_produto."/destaque");
    $ftp_cria_pasta_temp = mkdir("../../conteudos/produto/".$id_produto."/temp");
    $ftp_chmod_pasta_temp = chmod("../../conteudos/produto/".$id_produto."/temp", 0777);

		// Verifica se deu
		if(($ftp_cria_pasta_raiz) && ($ftp_cria_pasta_mini) && ($ftp_cria_pasta_destaque) && ($ftp_cria_pasta_temp) && ($ftp_chmod_pasta_temp)) {
			// Log
			salvar_log($_SESSION["id_usuario"],"incluir produto",$sql_inclui, $pdo);

			echo "<script>";
			echo "open('incluir_produto_fotos_destaque.php?id_produto=".base64_encode($id_produto)."&aviso=ok&msg=produto incluído com sucesso.','_self');";
			echo "</script>";
		} else {
			// Log
			salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui,$pdo);
	
			echo "<script>";
			echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Ocorreu alguns erros durante as conexões com o FTP.','_self');";
			echo "</script>";
		}
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui,$pdo);

		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir a produto.','_self');";
		echo "</script>";
	}		
}

// Coleta os dados
$sql_tipo_produto = "SELECT id_tipo_produto, nome_tipo_produto FROM tipo_produto ORDER BY nome_tipo_produto ASC";
$query_tipos = $pdo->query($sql_tipo_produto);
$resultado_tipo_produto = $query_tipos->fetchAll( PDO::FETCH_ASSOC );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body onload="document.form1.nome.focus();">
<div class="titulo-sessao-azul">Incluir Produto</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_produto" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da Produto</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" size="25" maxlength="50" class="campo-nome" /></td>
              </tr>
              <tr>
                <td class="campo-form">Tipo</td>
                <td>
                  <select name="tipo" id="tipo" class="select-tipo" onchange="carregaSubtipo(this.value);">
                    <option value="">Selecione</option>
                    <?php foreach($resultado_tipo_produto AS $tipo):?>
                    <option value="<?php echo $tipo["id_tipo_produto"];?>"><?php echo $tipo["nome_tipo_produto"];?></option>
                    <?php endforeach;?>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="campo-form">Subtipo</td>
                <td>
                  <select name="subtipo" id="subtipo" class="select-tipo">
                    <option value="">Selecione</option>
                  </select>
                </td>
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
  	<td><div class="info-observacao">&nbsp;</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar nova produto" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_produto = new Spry.Widget.TabbedPanels("incluir_produto");

function carregaSubtipo(id){
    
    var parametros = 'id='+id;
    action = 'carregaSubtipos.php';
    $.ajax({
        type: "POST",
        url: action,
        data: parametros,
        beforeSend: function() {
            $('#subtipo').empty().append('<option value="0">Aguarde...</option>');
        },        
        success: function(txt) {  
            $('#subtipo').empty().append(txt);
        },
        error: function(txt) {
            $('#subtipo').empty().append('<option value="0">Erro!</option>');
        }
    });
}


</script>
</body>
</html>