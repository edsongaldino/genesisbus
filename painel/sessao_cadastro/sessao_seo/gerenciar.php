<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Coletando dados para consulta
$palavra_chave = $_GET['palavra_chave'];

if($palavra_chave) {
	$palavra_chaves = explode(" ",$palavra_chave);
	$total_palavra_chaves = sizeof($palavra_chaves);
	
	$condicao_consulta = " WHERE (";
	
	for($i=0;$i<$total_palavra_chaves;$i++) {
		$condicao_consulta .= "site_pagina.url_pagina LIKE '%".$palavra_chaves[$i]."%'";
		
		if(($i+1) != $total_palavra_chaves) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";
	
	unset($i);
}

// Coleta os dados
$sql_gerenciar = "SELECT site_pagina.url_pagina, seo.titulo_pagina_seo FROM seo JOIN seo_pagina ON (seo.codigo_seo = seo_pagina.codigo_seo) JOIN site_pagina ON (seo_pagina.url_pagina = site_pagina.url_pagina)".$condicao_consulta." ORDER BY site_pagina.url_pagina";
$query_gerenciar = @mysql_query($sql_gerenciar);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../../sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<body>
<div class="titulo-sessao-azul">Páginas</div>
<?php include("../../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar">
  <tr>
    <td>
      <div id="gerenciar" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Filtros</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="campo-form">Palavra-chave:</td>
                <td><input name="palavra_chave" type="text" id="palavra_chave" size="50" maxlength="150" value="<?php echo $palavra_chave; ?>" /></td>
              </tr>
              <tr>
                <td class="campo-form">&nbsp;</td>
                <td><input type="submit" name="button" id="button" value="Filtrar" /></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</form>
</table>
<script type="text/javascript">
var gerenciar = new Spry.Widget.TabbedPanels("gerenciar");
</script>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="barra-endereco"></td>
  </tr>
  <tr>
    <td>
        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
          <tr>
            <td width="250">Página</td>
            <td>Título da página</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php while($executa_gerenciar = mysql_fetch_assoc($query_gerenciar)) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td><?php echo $executa_gerenciar['url_pagina']; ?></td>
            <td><?php echo $executa_gerenciar['titulo_pagina_seo']; ?></td>
            <td><div align="center"><a href="form.php?acao=alterar&tipo=alterar&id=<?php echo $executa_gerenciar['url_pagina']; ?>" target="_self"><img src="/paineladm/imagens/icone/icone_alterar.png" title="Alterar" width="16" height="16" border="0" /></a></div></td>
          </tr>
          <?php $i += 1; } ?>
        </table>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
@mysql_free_result($query_gerenciar);
?>