<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

if($_GET['acao'] == 'alterar') {
	// Pega o id da proximidade
	$id = $_GET['id'];
	$codigo_seo = $_GET['codigo_seo'];

	// PEGA OS DADOS DO FORMULÁRIO
	$titulo_pagina_seo = verifica_vazio($_POST['titulo_pagina_seo'], "");
	$descricao_seo = verifica_vazio($_POST['descricao_seo'], "");
	$palavra_chave_seo = verifica_vazio($_POST['palavra_chave_seo'], "");
	$h1_seo = verifica_vazio($_POST['h1_seo'], "");
	$h2_seo = verifica_vazio($_POST['h2_seo'], "");

	if($_GET['tipo'] == 'alterar') {
		// Coletando dados
		$sql_dados_alterar = "SELECT site_pagina.url_pagina, seo.codigo_seo, seo.titulo_pagina_seo, seo.descricao_seo, seo.palavra_chave_seo, seo.h1_seo, seo.h2_seo FROM seo JOIN seo_pagina ON (seo.codigo_seo = seo_pagina.codigo_seo) JOIN site_pagina ON (seo_pagina.url_pagina = site_pagina.url_pagina) WHERE site_pagina.url_pagina = '".$id."'";
		$query_dados_alterar = @mysql_query($sql_dados_alterar);
		$executa_dados_alterar = @mysql_fetch_assoc($query_dados_alterar);
	} else {
		// Realiza a alteração
		$sql_altera = "UPDATE seo SET titulo_pagina_seo = '".$titulo_pagina_seo."', descricao_seo = '".$descricao_seo."', palavra_chave_seo = '".$palavra_chave_seo."', h1_seo = '".$h1_seo."', h2_seo = '".$h2_seo."' WHERE codigo_seo = ".$codigo_seo."";
		$query_altera = @mysql_query($sql_altera);
		
		// Verifica se deu erro
		if($query_altera) {
			salvar_log($_SESSION["id_usuario"],"alterar página",$sql_altera);
			redireciona("gerenciar.php?aviso=ok&msg=Informações SEO da página salvas com sucesso.","_self");
		} else {
			salvar_log($_SESSION["id_usuario"],"erro",$sql_altera);
			redireciona($_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível salvar as informações.&id=".$id,"_self");
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../../sistema_mod_include_head.php"); // Faz um include do head ?>
<script language="javascript" type="text/javascript">
window.onload = function() {
	// focar no primeiro campo
	document.form1.titulo_pagina_seo.focus();
}
</script>
</head>
<body>
<div class="titulo-sessao-azul">Página</div>
<?php include("../../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']."?acao=alterar&tipo=salvar&id=".$id."&codigo_seo=".$executa_dados_alterar['codigo_seo']; ?>">
  <tr>
    <td colspan="2">
      <div id="tabFormulario" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es SEO da página</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="campo-form">Página</td>
                <td><input name="url_pagina" type="text" id="url_pagina" value="<?php echo $id; ?>" size="50" maxlength="70" readonly="readonly" ></td>
              </tr>
              <tr>
                <td class="campo-form">Título da página</td>
                <td><input name="titulo_pagina_seo" type="text" id="titulo_pagina_seo" value="<?php echo $executa_dados_alterar['titulo_pagina_seo']; ?>" size="50" maxlength="70" ></td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">Descrição da página</td>
                <td><textarea name="descricao_seo" cols="50" rows="3" id="descricao_seo"><?php echo $executa_dados_alterar['descricao_seo']; ?></textarea></td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">Palavra-chave</td>
                <td><textarea name="palavra_chave_seo" cols="50" rows="3" id="palavra_chave_seo"><?php echo $executa_dados_alterar['palavra_chave_seo']; ?></textarea></td>
              </tr>
              <tr>
                <td class="campo-form">H1 da página</td>
                <td><input name="h1_seo" type="text" id="h1_seo" value="<?php echo $executa_dados_alterar['h1_seo']; ?>" size="50" maxlength="70" ></td>
              </tr>
              <tr>
                <td class="campo-form">H2 da página</td>
                <td><input name="h2_seo" type="text" id="h2_seo" value="<?php echo $executa_dados_alterar['h2_seo']; ?>" size="50" maxlength="70" ></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td><div class="info-observacao">&nbsp;</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar informações" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var tabFormulario = new Spry.Widget.TabbedPanels("tabFormulario");
</script>
</body>
</html>