<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_produto = (int) base64_decode(addslashes(trim($_GET['id_produto'])));

// Pega os dados
$sql_produto = "SELECT produto.id_produto, produto.nome_produto, produto.texto_produto, produto.status, subtipo_produto.id_subtipo_produto, tipo_produto.id_tipo_produto  FROM produto
                  JOIN subtipo_produto ON produto.id_subtipo_produto = subtipo_produto.id_subtipo_produto
                  JOIN tipo_produto ON subtipo_produto.id_tipo_produto = tipo_produto.id_tipo_produto
                  WHERE produto.id_produto = ".$id_produto."";
$query_produto = $pdo->query($sql_produto);
$executa_produto = $query_produto->fetch( PDO::FETCH_ASSOC );



if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = addslashes(trim($_POST['nome']));
	$subtipo = addslashes(trim($_POST['subtipo']));
  $texto = addslashes(trim(fonte_editor($_POST['texto'])));
	$status = addslashes(trim($_POST['status']));

  try{

    // REALIZA A ALTERAÇÃO
    $sql_alterar = "UPDATE produto SET nome_produto = '".$nome."', id_subtipo_produto = '".$subtipo."', texto_produto = '".$texto."', status = '".$status."' WHERE id_produto = ".$id_produto."";
    $atualiza_produto = $pdo->prepare($sql_alterar);
    $atualiza_produto->execute();

    $altera = true;
    
  }catch (PDOException $e){
    echo 'Error: '. $e->getMessage();
    $altera = false;
  } 

	// VERIFICA SE DEU ERRO
	if($altera) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar produto",$sql_alterar, $pdo);

		echo "<script>";
		echo "open('gerenciar_produtos.php?aviso=ok&msg=Informações do produto alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui,$pdo);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?id_produto=".$id_produto."&aviso=erro&msg=Não foi possível alterar as informações do produto.','_self');";
		echo "</script>";
	}
}

// Coleta os dados
$sql_tipo_produto = "SELECT id_tipo_produto, nome_tipo_produto FROM tipo_produto ORDER BY nome_tipo_produto ASC";
$query_tipos = $pdo->query($sql_tipo_produto);
$resultado_tipo_produto = $query_tipos->fetchAll( PDO::FETCH_ASSOC );

$sql_subtipo_produto = "SELECT id_subtipo_produto, nome_subtipo_produto FROM subtipo_produto WHERE id_tipo_produto = '".$executa_produto["id_tipo_produto"]."' ORDER BY nome_subtipo_produto ASC";
$query_subtipos = $pdo->query($sql_subtipo_produto);
$resultado_subtipo_produto = $query_subtipos->fetchAll( PDO::FETCH_ASSOC );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body onload="document.form1.nome.focus();">
<div class="titulo-sessao-azul">Alterar Produto</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_produto=<?php echo base64_encode($id_produto); ?>" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_produto" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do Produto</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" size="25" maxlength="50" class="campo-nome" value="<?php echo $executa_produto['nome_produto']; ?>" /></td>
              </tr>
              <tr>
                <td class="campo-form">Tipo</td>
                <td>
                  <select name="tipo" id="tipo" class="select-tipo" onchange="carregaSubtipo(this.value);">
                    <?php foreach($resultado_tipo_produto AS $tipo):?>
                    <option value="<?php echo $tipo["id_tipo_produto"];?>" <?php if($tipo["id_tipo_produto"] == $executa_produto["id_tipo_produto"]){echo 'selected="selected"';}?>><?php echo $tipo["nome_tipo_produto"];?></option>
                    <?php endforeach;?>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="campo-form">Subtipo</td>
                <td>
                  <select name="subtipo" id="subtipo" class="select-tipo">
                    <?php foreach($resultado_subtipo_produto AS $subtipo):?>
                    <option value="<?php echo $subtipo["id_subtipo_produto"];?>" <?php if($subtipo["id_subtipo_produto"] == $executa_produto["id_subtipo_produto"]){echo 'selected="selected"';}?>><?php echo $subtipo["nome_subtipo_produto"];?></option>
                    <?php endforeach;?>
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
                    <textarea name="texto" cols="60" rows="10" id="texto"><?php echo $executa_produto['texto_produto']; ?></textarea>
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
                  <option value="L" <?php if($executa_produto['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_produto['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar altera&ccedil;&otilde;es" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var alterar_produto = new Spry.Widget.TabbedPanels("alterar_produto");

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