<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

if(($_GET['acao'] == 'incluir') || ($_GET['acao'] == 'alterar')) {
	// PEGA OS DADOS DO FORMULÁRIO
	$codigo_estado = $_POST['codigo_estado'];
	$nome = verifica_vazio($_POST['nome'], "Não informado");
	$status = $_POST['status'];
}

if($_GET['acao'] == 'incluir') {
	// REALIZA A INCLUSAO
	$sql_inclui = "INSERT INTO cidade (codigo_estado,nome_cidade,status) VALUES ('".$codigo_estado."','".$nome."','".$status."')";
	$query_inclui = $pdo->prepare($sql_inclui);
  $query_inclui->execute();
	
	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		salvar_log($_SESSION["id_usuario"],"incluir cidade",$sql_inclui,$pdo);
		redireciona("gerenciar.php?aviso=ok&msg=Informações da cidade salvas com sucesso.","_self");
	} else {
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui,$pdo);
		redireciona($_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível salvar as informações.","_self");
	}
}

if($_GET['acao'] == 'alterar') {
	// Pega o id da proximidade
	$id = $_GET['id'];

	if($_GET['tipo'] == 'alterar') {
		// Coletando dados
		$sql_dados_alterar = "SELECT cidade.codigo_estado, cidade.nome_cidade, cidade.status FROM cidade WHERE cidade.codigo_cidade = '".$id."'";
		$query_dados_alterar = $pdo->query($sql_dados_alterar);
		$executa_dados_alterar = $query_dados_alterar->fetch( PDO::FETCH_ASSOC );
	} else {
		// REALIZA A ALTERACAO
		$sql_altera = "UPDATE cidade SET codigo_estado = '".$codigo_estado."', nome_cidade = '".$nome."', status = '".$status."' WHERE codigo_cidade = '".$id."'";
		$query_altera = $pdo->prepare($sql_altera);
  	$query_altera->execute();
		
		// VERIFICA SE DEU ERRO
		if($query_altera) {
			salvar_log($_SESSION["id_usuario"],"alterar cidade",$sql_altera,$pdo);
			redireciona("gerenciar.php?aviso=ok&msg=Informações da cidade salvas com sucesso.","_self");
		} else {
			salvar_log($_SESSION["id_usuario"],"erro",$sql_altera,$pdo);
			redireciona($_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível salvar as informações.","_self");
		}
	}
}

// Coletando todas os estados
$sql_estados = "SELECT codigo_estado, nome_estado FROM estado WHERE status = 'L' ORDER BY nome_estado";
$query_estados = $pdo->query($sql_estados);
$estados = $query_estados->fetchAll( PDO::FETCH_ASSOC );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../../sistema_mod_include_head.php"); // Faz um include do head ?>
<script language="javascript" type="text/javascript">
window.onload = function() {
	// focar no primeiro campo
	document.form1.codigo_estado.focus();
}
</script>
</head>
<body>
<div class="titulo-sessao-azul">Cidade</div>
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
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da cidade</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="campo-form">Estado</td>
                <td>
                    <select name="codigo_estado" id="codigo_estado">
                    <?php foreach($estados AS $executa_estados) { ?>
                      <option value="<?php echo $executa_estados['codigo_estado']; ?>" <?php if($executa_dados_alterar['codigo_estado'] == $executa_estados['codigo_estado']) { echo 'selected="selected"'; } ?>><?php echo $executa_estados['nome_estado']; ?></option>
                    <?php } ?>
                    </select>
                </td>
              </tr>
              <tr>
                <td class="campo-form">Nome</td>
                <td><input name="nome" type="text" id="nome" value="<?php echo $executa_dados_alterar['nome_cidade']; ?>" size="50" maxlength="100" ></td>
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