<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = addslashes(trim($_POST['nome']));
	$descricao = addslashes(trim($_POST['descricao']));
	$status = addslashes(trim($_POST['status']));

	$uploaddir = '../../conteudos/arquivo/';
	$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);

	if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
		$nome_arquivo = basename($_FILES['arquivo']['name']);
		// Inclui
		$sql_inclui = "INSERT INTO licenca (nome_licenca,descricao_licenca,arquivo_licenca,status_licenca) VALUES ('".$nome."','".$descricao."','".$nome_arquivo."','".$status."')";
		$query_inclui = mysql_query($sql_inclui);
	}

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir Arquivo",$sql_inclui);

		echo "<script>";
		echo "open('gerenciar_licencas.php?aviso=ok&msg=Arquivo incluído com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir o arquivo.','_self');";
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
<div class="titulo-sessao-azul">Incluir novo Arquivo</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_licenca" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do Arquivo</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" size="30" maxlength="30" /></td>
              </tr>
              <tr>
                <td class="campo-form">Descri&ccedil;&atilde;o:</td>
                <td><input name="descricao" type="text" id="descricao" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.pdf *)</td>
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
  	<td><div class="info-observacao"><span class="negrito">*</span> Recomenda&ccedil;&otilde;es da extens&atilde;o e / ou tamanho do arquivo.</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar novo Arquivo" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_licenca = new Spry.Widget.TabbedPanels("incluir_licenca");
</script>
</body>
</html>