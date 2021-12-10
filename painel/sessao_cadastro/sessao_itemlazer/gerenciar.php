<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Coletando dados para consulta
$palavra_chave = $_GET['palavra_chave'];

if($palavra_chave) {
	$palavra_chaves = explode(" ",$palavra_chave);
	$total_palavra_chaves = sizeof($palavra_chaves);
	
	$condicao_consulta .= " AND (";
	
	for($i=0;$i<$total_palavra_chaves;$i++) {
		$condicao_consulta .= "site_itemlazer.nome LIKE '%".$palavra_chaves[$i]."%'";
		
		if(($i+1) != $total_palavra_chaves) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";
	
	unset($i);
}

// Coleta os dados
$sql_gerenciar = "SELECT site_itemlazer.id_itemlazer, site_itemlazer.nome, site_itemlazer.status FROM site_itemlazer WHERE site_itemlazer.status <> 'E'".$condicao_consulta." ORDER BY site_itemlazer.nome";
$query_gerenciar = @mysql_query($sql_gerenciar);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../../sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<body>
<div class="titulo-sessao-azul">Itens de lazer</div>
<?php include("../../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar">
  <tr>
    <td><input type="button" name="button" id="button" onclick="javascript: window.open('form.php','_self');" value="Incluir novo item de lazer" /></td>
  </tr>
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
            <td width="20">&nbsp;</td>
            <td width="20">Id</td>
            <td>Nome</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php while($executa_gerenciar = mysql_fetch_assoc($query_gerenciar)) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td><div align="center"><?php echo exibi_icone($executa_gerenciar['status']); ?></div></td>
            <td><div align="center"><?php echo $executa_gerenciar['id_itemlazer']; ?></div></td>
            <td><?php echo $executa_gerenciar['nome']; ?></td>
            <td><div align="center"><a href="form.php?acao=alterar&tipo=alterar&id=<?php echo $executa_gerenciar['id_itemlazer']; ?>" target="_self"><img src="/paineladm/imagens/icone/icone_alterar.png" title="Alterar" width="16" height="16" border="0" /></a></div></td>
            <td><div align="center"><a href="javascript: confirma_acao('Tem certeza que deseja excluir <?php echo $executa_gerenciar['nome']; ?>?','excluir.php?id=<?php echo $executa_gerenciar['id_itemlazer']; ?>');" target="_self"><img src="/paineladm/imagens/icone/icone_excluir.gif" title="Excluir" width="16" height="16" border="0" /></a></div></td>
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