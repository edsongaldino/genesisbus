<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$codigo_licenca = (int) base64_decode(addslashes(trim($_GET['codigo_licenca'])));

// Pega os dados
$sql_licenca = "SELECT nome_licenca, descricao_licenca, arquivo_licenca, status_licenca FROM licenca WHERE codigo_licenca = ".$codigo_licenca."";
$query_licenca = mysql_query($sql_licenca);
$executa_licenca = mysql_fetch_assoc($query_licenca);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = addslashes(trim($_POST['nome']));
	$descricao = addslashes(trim($_POST['descricao']));
	$status = addslashes(trim($_POST['status']));
	
	// Verifica se existe algum arquivo
	$uploaddir = '../../conteudos/arquivo/';
	$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);

	if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
		$nome_arquivo = basename($_FILES['arquivo']['name']);
	} else {
		$nome_arquivo = $executa_licenca["arquivo_licenca"];
	}

	// REALIZA A ALTERAÇÃO
	$sql_alterar = "UPDATE licenca SET nome_licenca = '".$nome."', descricao_licenca = '".$descricao."', arquivo_licenca = '".$nome_arquivo."', status_licenca = '".$status."' WHERE codigo_licenca = ".$codigo_licenca."";
	$query_alterar = mysql_query($sql_alterar);

	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar liçenca",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_licencas.php?aviso=ok&msg=Informações da liçenca alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações da liçenca.&codigo_licenca=".$codigo_licenca."','_self');";
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
<div class="titulo-sessao-azul">Alterar Li&ccedil;enca</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&codigo_licenca=<?php echo base64_encode($codigo_licenca); ?>"  enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_licenca" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da Li&ccedil;enca</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" value="<?php echo $executa_licenca['nome_licenca']; ?>" size="30" maxlength="30" /></td>
              </tr>
              <tr>
                <td class="campo-form">Descri&ccedil;&atilde;o:</td>
                <td><input name="descricao" type="text" id="descricao" value="<?php echo $executa_licenca['descricao_licenca']; ?>" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.pdf *)</td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_licenca['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_licenca['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar altera&ccedil;&otilde;es" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var alterar_licenca = new Spry.Widget.TabbedPanels("alterar_licenca");
</script>
</body>
</html>