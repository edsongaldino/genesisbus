<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_frase = (int) base64_decode(addslashes(trim($_GET['id_frase'])));

// Pega os dados
$sql_frase = "SELECT frase, autor, status FROM frases WHERE id_frase = ".$id_frase."";
$query_frase = mysql_query($sql_frase);
$executa_frase = mysql_fetch_assoc($query_frase);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$frase = addslashes(trim($_POST['frase']));
	$autor = addslashes(trim($_POST['autor']));
	$status = addslashes(trim($_POST['status']));
		
	// REALIZA A ALTERAÇÃO
	$sql_alterar = "UPDATE frases SET frase = '".$frase."', autor = '".$autor."', status = '".$status."' WHERE id_frase = ".$id_frase."";
	$query_alterar = mysql_query($sql_alterar);

	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar frase",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_frases.php?aviso=ok&msg=Informações da frase alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações da frase.&id_frase=".$id_frase."','_self');";
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
<body onload="document.form1.frase.focus();">
<div class="titulo-sessao-azul">Alterar frase</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_frase=<?php echo base64_encode($id_frase); ?>" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_frase" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da frase</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Frase:</td>
                <td><input name="frase" type="text" id="frase" value="<?php echo $executa_frase['frase']; ?>" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td class="campo-form">Autor:</td>
                <td><input name="autor" type="text" id="autor" value="<?php echo $executa_frase['autor']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_frase['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_frase['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
var alterar_frase = new Spry.Widget.TabbedPanels("alterar_frase");
</script>
</body>
</html>