<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

// Pega os dados
$sql_informacoes = "SELECT endereco_ftp, login_ftp, senha_ftp, empresa, endereco, bairro, cidade, uf, cep, telefone, email_faleconosco, titulo_site, metatags_keywords, metatags_description FROM site_informacoes WHERE id_site_informacao = '1'";
$query_informacoes = mysql_query($sql_informacoes);
$executa_informacoes = mysql_fetch_assoc($query_informacoes);

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$endereco_ftp = verifica_vazio(trim($_POST['endereco_ftp']), "-");
	$login_ftp = verifica_vazio(trim($_POST['login_ftp']), "-");
	$senha_antiga = $_POST['senha_antiga'];
	$senha_ftp = base64_encode(verifica_vazio($_POST['senha_ftp'], "-"));
	$empresa = verifica_vazio(trim($_POST['empresa']), "-");
	$endereco = verifica_vazio(trim($_POST['endereco']), "-");
	$bairro = verifica_vazio(trim($_POST['bairro']), "-");
	$cidade = verifica_vazio(trim($_POST['cidade']), "-");
	$uf = verifica_vazio(trim($_POST['uf']), "-");
	$cep = verifica_vazio(trim($_POST['cep']), "78000-000");
	$telefone = trim($_POST['telefone']);
	$email_faleconosco = verifica_vazio(trim($_POST['email_faleconosco']), "-");
	$titulo_site = verifica_vazio(trim($_POST['titulo_site']), $empresa);
	$metatags_keywords = verifica_vazio(trim($_POST['metatags_keywords']), $empresa);
	$metatags_description = verifica_vazio(trim($_POST['metatags_description']), $empresa);

	// Verifica se a senha continua a mesma
	if($senha_ftp == base64_encode("-")) {
		$senha_ftp = $senha_antiga;
	}

	// REALIZA A ALTERAÇÃO
	$sql_altera = "UPDATE site_informacoes SET endereco_ftp = '".$endereco_ftp."', login_ftp = '".$login_ftp."', senha_ftp = '".$senha_ftp."', empresa = '".$empresa."', endereco = '".$endereco."', bairro = '".$bairro."', cidade = '".$cidade."', uf = '".$uf."', cep = '".$cep."', telefone = '".$telefone."', email_faleconosco = '".$email_faleconosco."', titulo_site = '".$titulo_site."', metatags_keywords = '".$metatags_keywords."', metatags_description = '".$metatags_description."' WHERE id_site_informacao = '1'";
	$query_altera = mysql_query($sql_altera); // executa o sql acima

	// VERIFICA SE DEU ERRO
	if($query_altera) {
		salvar_log($_SESSION["id_usuario"],"alterar informações da empresa",$sql_altera);
		redireciona($_SERVER['SCRIPT_NAME']."?aviso=ok&msg=Informações da empresa salvas com sucesso.","_self");
	} else {
		salvar_log($_SESSION["id_usuario"],"erro",$sql_altera);
		redireciona($_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível salvar as informações da empresa.","_self");
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
<div class="titulo-sessao-azul">Informa&ccedil;&otilde;es da empresa</div>
<?php include("../../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar">
  <tr>
    <td>
      <div id="tabFormulario" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da empresa</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="campo-form">Endere&ccedil;o FTP:</td>
                <td><input name="endereco_ftp" type="text" id="endereco_ftp" value="<?php echo $executa_informacoes['endereco_ftp']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Login FTP:</td>
                <td><input name="login_ftp" type="text" id="login_ftp" value="<?php echo $executa_informacoes['login_ftp']; ?>" size="20" maxlength="20" /></td>
              </tr>
              <tr>
                <td class="campo-form">Nova senha FTP:</td>
                <td><input type="hidden" value="<?php echo $executa_informacoes['senha_ftp']; ?>" name="senha_antiga" id="senha_antiga" /><input name="senha_ftp" type="password" id="senha_ftp" size="20" maxlength="20" /></td>
              </tr>
              <tr>
                <td class="campo-form">Empresa:</td>
                <td><input name="empresa" type="text" id="empresa" value="<?php echo $executa_informacoes['empresa']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Endere&ccedil;o:</td>
                <td><input name="endereco" type="text" id="endereco" value="<?php echo $executa_informacoes['endereco']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Bairro:</td>
                <td><input name="bairro" type="text" id="bairro" value="<?php echo $executa_informacoes['bairro']; ?>" size="25" maxlength="50" /></td>
              </tr>
              <tr>
                <td class="campo-form">Cidade:</td>
                <td><input name="cidade" type="text" id="cidade" value="<?php echo $executa_informacoes['cidade']; ?>" size="25" maxlength="50" /> <span class="campo-form">UF:</span> <input name="uf" type="text" id="uf" value="<?php echo $executa_informacoes['uf']; ?>" size="2" maxlength="2" /></td>
              </tr>
              <tr>
                <td class="campo-form">CEP:</td>
                <td><input name="cep" type="text" id="cep" value="<?php echo $executa_informacoes['cep']; ?>" size="9" maxlength="9" /></td>
              </tr>
              <tr>
                <td class="campo-form">Telefone:</td>
                <td><input name="telefone" type="text" id="telefone" value="<?php echo $executa_informacoes['telefone']; ?>" size="14" maxlength="14" /></td>
              </tr>
              <tr>
                <td class="campo-form">E-mail:</td>
                <td><input name="email_faleconosco" type="text" id="email_faleconosco" value="<?php echo $executa_informacoes['email_faleconosco']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">T&iacute;tulo do website:</td>
                <td><input name="titulo_site" type="text" id="titulo_site" value="<?php echo $executa_informacoes['titulo_site']; ?>" size="50" maxlength="150" /></td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">Metatags keywords:</td>
                <td><textarea name="metatags_keywords" cols="50" rows="5" id="metatags_keywords"><?php echo $executa_informacoes['metatags_keywords']; ?></textarea></td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">Metatags description:</td>
                <td><textarea name="metatags_description" cols="50" rows="5" id="metatags_description"><?php echo $executa_informacoes['metatags_description']; ?></textarea></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar informações" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var tabFormulario = new Spry.Widget.TabbedPanels("tabFormulario");
</script>
</body>
</html>
<?php
mysql_free_result($query_informacoes);
?>