<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Coletando dados para consulta
$email = addslashes(trim($_GET['email']));

if($email) {
	$emails = explode(" ",$email);
	$total_emails = sizeof($emails);
	
	$condicao_consulta .= " AND (";
	
	for($i=0;$i<$total_emails;$i++) {
		$condicao_consulta .= "email LIKE '%".$emails[$i]."%'";
		
		if(($i+1) != $total_emails) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";
	
	unset($i);
}

// Coleta os dados
$sql_newsletter = "SELECT codigo, email, status FROM newsletter WHERE status <> 'E'".$condicao_consulta." ORDER BY codigo DESC";
$query_newsletter = mysql_query($sql_newsletter);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body>
<div class="titulo-sessao-azul">Newsletter</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <div id="gerenciar_newsletter" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Filtros</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="email" type="text" id="email" size="50" maxlength="150" value="<?php echo $pergunta; ?>" /></td>
              </tr>
              <tr>
                <td class="campo-form">&nbsp;</td>
                <td><input type="submit" name="button" id="button" value="Filtrar Newsletter" /></td>
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
var gerenciar_newsletter = new Spry.Widget.TabbedPanels("gerenciar_newsletter");
</script>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EDEFF3">
  <tr>
    <td><div class="barra-endereco"><a href="exporta_newsletter.php"><img src="icone_exportar.png" width="150" height="30" border="0" align="right" /></a></div></td>
  </tr>
  <tr>
    <td>
        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
          <tr>
            <td width="46">&nbsp;</td>
            <td width="75">Id</td>
            <td width="1230">Email</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php while($executa_newsletter = mysql_fetch_assoc($query_newsletter)) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td>
            <div align="center">
			<?php
				if($executa_newsletter['status'] == "L") {
					echo '<img src="../imagens/icone/icone_ok.png" title="Email liberado" width="16" height="16" border="0" />';
				}
				if($executa_newsletter['status'] == "B") {
					echo '<img src="../imagens/icone/icone_erro.png" title="Email bloqueado" width="16" height="16" border="0" />';
				}
			?>
            </div>
            </td>
            <td><div align="center"><?php echo $executa_newsletter['codigo']; ?></div></td>
            <td><?php echo $executa_newsletter['email']; ?></td>
            <td><div align="center"><a href="javascript: confirma_acao('Tem certeza que deseja excluir &quot;<?php echo $executa_newsletter['email']; ?>&quot;?','excluir_newsletter.php?codigo=<?php echo base64_encode($executa_newsletter['codigo']); ?>');" target="_self"><img src="../imagens/icone/icone_excluir.gif" title="Excluir a Newsletter" width="16" height="16" border="0" /></a></div></td>
          </tr>
          <?php $i += 1; } ?>
        </table>
    </td>
  </tr>
</table>
</body>
</html>