<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

if(($_GET['acao'] == 'incluir') || ($_GET['acao'] == 'alterar')) {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = verifica_vazio($_POST['nome'], "Não informado");
	$status = $_POST['status'];
}

if($_GET['acao'] == 'incluir') {
	// REALIZA A INCLUSAO
	$sql_inclui = "INSERT INTO site_infraestruturas_opcoes (nome,status) VALUES ('".$nome."','".$status."')";
	$query_inclui = @mysql_query($sql_inclui);
	
	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		salvar_log($_SESSION["id_usuario"],"incluir opção da infraestrutura",$sql_inclui);
		redireciona("gerenciar.php?aviso=ok&msg=Informações da opção da infraestrutura salvas com sucesso.","_self");
	} else {
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
		redireciona($_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível salvar as informações.","_self");
	}
}

if($_GET['acao'] == 'alterar') {
	// Pega o id da proximidade
	$id = $_GET['id'];

	if($_GET['tipo'] == 'alterar') {
		// Coletando dados
		$sql_dados_alterar = "SELECT site_infraestruturas_opcoes.nome, site_infraestruturas_opcoes.status FROM site_infraestruturas_opcoes WHERE site_infraestruturas_opcoes.id_infraestrutura_opcao = '".$id."'";
		$query_dados_alterar = @mysql_query($sql_dados_alterar);
		$executa_dados_alterar = @mysql_fetch_assoc($query_dados_alterar);
	} else {
		// REALIZA A ALTERACAO
		$sql_altera = "UPDATE site_infraestruturas_opcoes SET nome = '".$nome."', status = '".$status."' WHERE id_infraestrutura_opcao = '".$id."'";
		$query_altera = @mysql_query($sql_altera);
		
		// VERIFICA SE DEU ERRO
		if($query_altera) {
			salvar_log($_SESSION["id_usuario"],"alterar infraestrutura",$sql_altera);
			redireciona("gerenciar.php?aviso=ok&msg=Informações da opção da infraestrutura salvas com sucesso.","_self");
		} else {
			salvar_log($_SESSION["id_usuario"],"erro",$sql_altera);
			redireciona($_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível salvar as informações.","_self");
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../../sistema_mod_include_head.php"); // Faz um include do head ?>
<script language="javascript" type="text/javascript">
window.onload = function() {
	// focar no primeiro campo
	document.form1.nome.focus();
}
</script>
</head>
<body>
<div class="titulo-sessao-azul">Opção da infraestrutura</div>
<?php include("../../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
if($_GET['acao'] == "alterar") {
	$action = $_SERVER['PHP_SELF']."?acao=alterar&tipo=salvar&id=".$id;
} else {
	$action = $_SERVER['PHP_SELF']."?acao=incluir";
}
?>
<form id="form1" name="form1" method="post" action="<?php echo $action; ?>">
  <tr>
    <td colspan="2">
      <div id="tabFormulario" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da opção da infraestrutura</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="campo-form">Nome</td>
                <td><input name="nome" type="text" id="nome" value="<?php echo $executa_dados_alterar['nome']; ?>" size="50" maxlength="100" ></td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                    <select name="status" id="status">
                      <option value="L" <?php if($executa_dados_alterar['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                      <option value="B" <?php if($executa_dados_alterar['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar informações" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var tabFormulario = new Spry.Widget.TabbedPanels("tabFormulario");
</script>
</body>
</html>