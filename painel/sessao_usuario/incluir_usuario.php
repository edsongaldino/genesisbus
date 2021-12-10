<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMUL�RIO
	$nome = addslashes(trim($_POST['nome']));
	$login = remove_acentos(addslashes(trim($_POST['login'])));
	$senha = md5($_POST['senha']);
	$status = addslashes(trim($_POST['status']));
	
	$sql_inclui = "INSERT INTO sistema_usuarios (nome,login,senha,status) VALUES ('".$nome."','".$login."','".$senha."','".$status."')";
  $query_inclui = $pdo->prepare($sql_inclui);
  $query_inclui->execute();

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir usuario",$sql_inclui,$pdo);

		echo "<script>";
		echo "window.open('gerenciar_usuarios.php?aviso=ok&msg=Usuário incluído com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui,$pdo);

		echo "<script>";
		echo "window.open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir o usuário','_self');";
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
<body>
<div class="titulo-sessao-azul">Incluir novo usuário</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td>
      <div id="incluir_usuario" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do usuário</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome do usuário:</td>
                <td><input name="nome" type="text" id="nome" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Login:</td>
                <td><input name="login" type="text" id="login" size="14" maxlength="14" /></td>
              </tr>
              <tr>
                <td class="campo-form">Senha:</td>
                <td><input name="senha" type="password" id="senha" size="14" maxlength="14" /></td>
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
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar novo usuário" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_usuario = new Spry.Widget.TabbedPanels("incluir_usuario");
</script>
</body>
</html>