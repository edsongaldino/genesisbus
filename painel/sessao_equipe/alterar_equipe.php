<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$id_user = (int) base64_decode(addslashes(trim($_GET['id_user'])));

// Pega os dados
$sql_usuario = "SELECT nome, login, senha, status FROM sistema_usuarios WHERE id_usuario = ".$id_user."";
$query_usuario = mysql_query($sql_usuario);
$executa_usuario = mysql_fetch_assoc($query_usuario);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = addslashes(trim($_POST['nome']));
	$login = addslashes(trim($_POST['login']));
	$senha = md5(trim($_POST['senha']));
	$status = addslashes(trim($_POST['status']));
	
	if($senha != "d41d8cd98f00b204e9800998ecf8427e") {
		// REALIZA A ALTERAÇÃO
		$sql_alterar = "UPDATE sistema_usuarios SET nome = '".$nome."', login = '".$login."', senha = '".$senha."', status = '".$status."' WHERE id_usuario = ".$id_user."";
		$query_alterar = mysql_query($sql_alterar);
	} else {
		// REALIZA A ALTERAÇÃO
		$sql_alterar = "UPDATE sistema_usuarios SET nome = '".$nome."', login = '".$login."', status = '".$status."' WHERE id_usuario = ".$id_user."";
		$query_alterar = mysql_query($sql_alterar);
	}
	
	// VERIFICA SE DEU ERRO
	if($query_alterar) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar usuario",$sql_alterar);
	
		echo "<script>";
		echo "open('gerenciar_usuarios.php?aviso=ok&msg=Informações do usuário alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_alterar);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações do usuário.&id_user=".base64_encode($id_user)."','_self');";
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
<div class="titulo-sessao-azul">Alterar usu&aacute;rio</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&id_user=<?php echo base64_encode($id_user); ?>" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td>
      <div id="alterar_usuario" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do usu&aacute;rio</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome do usu&aacute;rio:</td>
                <td><input name="nome" type="text" id="nome" value="<?php echo $executa_usuario['nome']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Login:</td>
                <td><input name="login" type="text" id="login" value="<?php echo $executa_usuario['login']; ?>" size="14" maxlength="14" /></td>
              </tr>
              <tr>
                <td class="campo-form">Nova senha:</td>
                <td><input name="senha" type="password" id="senha" size="14" maxlength="14" /></td>
              </tr>
              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_usuario['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_usuario['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar altera&ccedil;&otilde;es" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var alterar_usuario = new Spry.Widget.TabbedPanels("alterar_usuario");
</script>
</body>
</html>