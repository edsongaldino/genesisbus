<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Coletando dados para consulta
$titulo = addslashes(trim($_GET['titulo']));

if($titulo) {
	$titulos = explode(" ",$titulo);
	$total_titulos = sizeof($titulos);
	
	$condicao_consulta .= " AND (";
	
	for($i=0;$i<$total_titulos;$i++) {
		$condicao_consulta .= "numero LIKE '%".$titulos[$i]."%'";
		
		if(($i+1) != $total_titulos) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";
	
	unset($i);
}

// Coleta os dados
$sql_folhasbrasilis = "SELECT id_folhabrasilis, numero, ano, status FROM folhasbrasilis WHERE status <> 'E'".$condicao_consulta." ORDER BY id_folhabrasilis DESC";
$query_folhasbrasilis = mysql_query($sql_folhasbrasilis);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body>
<div class="titulo-sessao-azul">Arquivos (folha brasilis)</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td><input type="button" name="button" id="button" onclick="javascript: window.open('incluir_folhabrasilis.php','_self');" value="Incluir novo arquivo (folha brasilis)" /></td>
  </tr>
  <tr>
    <td>
      <div id="gerenciar_folhabrasilis" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Filtros</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="campo-form">Número:</td>
                <td><input name="numero" type="text" id="numero" size="50" maxlength="150" value="<?php echo $numero; ?>" /></td>
              </tr>
              <tr>
                <td class="campo-form">&nbsp;</td>
                <td><input type="submit" name="button" id="button" value="Filtrar arquivos (folha brasilis)" /></td>
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
var gerenciar_folhabrasilis = new Spry.Widget.TabbedPanels("gerenciar_folhabrasilis");
</script>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EDEFF3">
  <tr>
    <td><div class="barra-endereco"></div></td>
  </tr>
  <tr>
    <td>
        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
          <tr>
            <td width="20">&nbsp;</td>
            <td width="20">Id</td>
            <td>Número</td>
            <td width="50">Ano</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php while($executa_folhasbrasilis = mysql_fetch_assoc($query_folhasbrasilis)) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td>
            <div align="center">
			<?php
				if($executa_folhasbrasilis['status'] == "L") {
					echo '<img src="../imagens/icone/icone_ok.png" title="Arquivo (folha brasilis) liberado" width="16" height="16" border="0" />';
				}
				if($executa_folhasbrasilis['status'] == "B") {
					echo '<img src="../imagens/icone/icone_erro.png" title="Arquivo (folha brasilis) bloqueado" width="16" height="16" border="0" />';
				}
			?>
            </div>
            </td>
            <td><div align="center"><?php echo $executa_folhasbrasilis['id_folhabrasilis']; ?></div></td>
            <td><?php echo $executa_folhasbrasilis['numero']; ?></td>
            <td><?php echo $executa_folhasbrasilis['ano']; ?></td>
            <td><div align="center"><a href="alterar_folhabrasilis.php?id_folhabrasilis=<?php echo base64_encode($executa_folhasbrasilis['id_folhabrasilis']); ?>" target="_self"><img src="../imagens/icone/icone_alterar.png" title="Alterar o arquivo (folha brasilis)" width="16" height="16" border="0" /></a></div></td>
            <td><div align="center"><a href="javascript: confirma_acao('Tem certeza que deseja excluir &quot;<?php echo $executa_folhasbrasilis['titulo']; ?>&quot;?','excluir_folhabrasilis.php?id_folhabrasilis=<?php echo base64_encode($executa_folhasbrasilis['id_folhabrasilis']); ?>');" target="_self"><img src="../imagens/icone/icone_excluir.gif" title="Excluir o arquivo (folha brasilis)" width="16" height="16" border="0" /></a></div></td>
          </tr>
          <?php $i += 1; } ?>
        </table>
    </td>
  </tr>
</table>
</body>
</html>