<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_link = (int) base64_decode(addslashes(trim($_GET['id_link'])));

// Pega os dados
$sql_link = "SELECT nome, site, status FROM links WHERE id_link = ".$id_link."";
$query_link = mysql_query($sql_link);
$executa_link = mysql_fetch_assoc($query_link);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = addslashes(trim($_POST['nome']));
	$site = addslashes(trim($_POST['site']));
	$status = addslashes(trim($_POST['status']));
		
	// REALIZA A ALTERAÇÃO
	$sql_alterar = "UPDATE links SET nome = '".$nome."', site = '".$site."', status = '".$status."' WHERE id_link = ".$id_link."";
	$query_alterar = mysql_query($sql_alterar);

	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar link",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_links.php?aviso=ok&msg=Informações do link alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações do link.&id_link=".$id_link."','_self');";
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
<body onload="document.form1.nome.focus();">
<div class="titulo-sessao-azul">Alterar link</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_link=<?php echo base64_encode($id_link); ?>" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_link" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do link</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" value="<?php echo $executa_link['nome']; ?>" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td class="campo-form">Site:</td>
                <td>http:// <input name="site" type="text" id="site" value="<?php echo $executa_link['site']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_link['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_link['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
var alterar_link = new Spry.Widget.TabbedPanels("alterar_link");
</script>
</body>
</html>