<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_produto = base64_decode(addslashes(trim($_GET['id_produto'])));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<body>
<div class="titulo-sessao-azul">Incluir fotos do produto</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="incluir_produto_fotos_destaque.php?id_produto=<?php echo base64_encode($id_produto); ?>">
  <tr>
    <td colspan="2">
      <div id="incluir_produto_foto" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Fotos do produto</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input id="fileInput2" name="fileInput2" type="file" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>
				<script type="text/javascript">
                $(document).ready(function() {
                    $("#fileInput2").uploadify({
                        'uploader'       : '../ferramentas/upload/uploadify.swf',
						            'buttonText'	 : 'Adicionar fotos',
                        'script'         : '../ferramentas/upload/uploadify.php',
                        'cancelImg'      : '../imagens/icone/icone_excluir.gif',
                        'folder'         : '../../conteudos/produto/<?php echo $id_produto; ?>/temp/',
						            'fileDesc'		 : 'Apenas arquivos JPG/JPEG',
						            'fileExt'		 : '*.jpg;*.jpeg;',
                        'multi'          : true
                    });
                });
                </script>                
                </td>
              </tr>
              <tr>
                <td><a href="javascript:$('#fileInput2').uploadifyUpload();"><img src="../imagens/painel/botao_enviarfotos.gif" title="Enviar fotos" width="120" height="30" border="0" /></a> <a href="javascript:$('#fileInput2').uploadifyClearQueue();"><img src="../imagens/painel/botao_cancelarenvio.gif" title="Cancelar envio" width="142" height="30" border="0" /></a></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td>
    	<div class="info-observacao">
        	Ap&oacute;s adicionar todas as fotos e realizar o envio, clique em <span class="negrito">Finalizar postagem de fotos</span> para selecionar as fotos em destaque do produto.<br />
            Tamanho recomendado proporcional &agrave; <span class="negrito">640x480</span> px.
		</div>
	</td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Finalizar postagem de fotos" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_produto_foto = new Spry.Widget.TabbedPanels("incluir_produto_foto");
</script>
</body>
</html>