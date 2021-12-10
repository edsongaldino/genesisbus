<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega os dados
$sql_htaccess = "SELECT htaccess FROM site_informacoes WHERE id_site_informacao = '1'";
$query_htaccess = mysql_query($sql_htaccess);
$executa_htaccess = mysql_fetch_assoc($query_htaccess);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$metatags_description = trim($_POST['htaccess']);

	// REALIZA A ALTERAÇÃO
	$sql_altera = "UPDATE site_informacoes SET htaccess = '".$htaccess."' WHERE id_site_informacao = '1'";
	$query_altera = mysql_query($sql_altera); // executa o sql acima

	// VERIFICA SE DEU ERRO
	if($query_altera) {
		// Gera o novo htaccess
		htaccess();
		
		salvar_log($_SESSION["id_usuario"],"alterar htaccess",$sql_altera);
		redireciona($_SERVER['SCRIPT_NAME']."?aviso=ok&msg=Informações do htaccess salvas com sucesso.","_self");
	} else {
		salvar_log($_SESSION["id_usuario"],"erro",$sql_altera);
		redireciona($_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível salvar as informações do htaccess.","_self");
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../../sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<body onload="document.form1.endereco_ftp.focus();">
<div class="titulo-sessao-azul">Informa&ccedil;&otilde;es do htaccess</div>
<?php include("../../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar">
  <tr>
    <td>
      <div id="tabFormulario" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do htaccess</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" class="campo-form">Htaccess:</td>
                <td><textarea name="htaccess" cols="70" rows="20" id="htaccess"><?php echo $executa_htaccess['htaccess']; ?></textarea></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar htaccess" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var tabFormulario = new Spry.Widget.TabbedPanels("tabFormulario");
</script>
</body>
</html>
<?php
mysql_free_result($query_htaccess);
?>