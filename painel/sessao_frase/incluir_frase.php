<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMULÁRIO
	$frase = addslashes(trim($_POST['frase']));
	$autor = addslashes(trim($_POST['autor']));
	$status = addslashes(trim($_POST['status']));

	// Inclui
	$sql_inclui = "INSERT INTO frases (frase,autor,status) VALUES ('".$frase."','".$autor."','".$status."')";
	$query_inclui = mysql_query($sql_inclui);

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir frase",$sql_inclui);

		echo "<script>";
		echo "open('gerenciar_frases.php?aviso=ok&msg=Frase incluída com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir a frase.','_self');";
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
<div class="titulo-sessao-azul">Incluir nova frase</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_frase" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da frase</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Frase:</td>
                <td><input name="frase" type="text" id="frase" size="50" maxlength="250" /></td>
              </tr>
              <tr>
                <td class="campo-form">Autor:</td>
                <td><input name="autor" type="text" id="autor" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L">Liberado</option>
                  <option value="B">Bloqueado</option>
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
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar nova frase" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_frase = new Spry.Widget.TabbedPanels("incluir_frase");
</script>
</body>
</html>