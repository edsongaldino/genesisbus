<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega os dados
$sql_informacoes = "SELECT empresa, endereco, bairro, cidade, uf, cep, telefone, email_faleconosco, titulo_site, metatags_keywords, metatags_description FROM site_informacoes WHERE id_site_informacao = '1'";
$query_informacoes = $pdo->query( $sql_informacoes );
$executa_informacoes = $query_informacoes->fetch( PDO::FETCH_ASSOC );


if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$empresa = verifica_vazio($_POST['empresa'], "");
	$endereco = verifica_vazio($_POST['endereco'], "");
	$bairro = verifica_vazio($_POST['bairro'], "");
	$cidade = verifica_vazio($_POST['cidade'], "");
	$uf = verifica_vazio($_POST['uf'], "");
	$cep = verifica_vazio($_POST['cep'], "");
	$telefones = trim($_POST['telefones']);
	$email_faleconosco = verifica_vazio($_POST['email_faleconosco'], "");
	$titulo_site = verifica_vazio($_POST['titulo_site'], $empresa);
	$metatags_keywords = verifica_vazio($_POST['metatags_keywords'], $empresa);
	$metatags_description = verifica_vazio($_POST['metatags_description'], $empresa);

  try{

    // REALIZA A ALTERAÇÃO
    $sql_alterar = "UPDATE site_informacoes SET empresa = '".$empresa."', endereco = '".$endereco."', bairro = '".$bairro."', cidade = '".$cidade."', uf = '".$uf."', cep = '".$cep."', telefone = '".$telefones."', email_faleconosco = '".$email_faleconosco."', titulo_site = '".$titulo_site."', metatags_keywords = '".$metatags_keywords."', metatags_description = '".$metatags_description."' WHERE id_site_informacao = '1'";
    $atualiza_informacoes = $pdo->prepare($sql_alterar);
    $atualiza_informacoes->execute();

    $altera = true;
    
  }catch (PDOException $e){
    echo 'Error: '. $e->getMessage();
    $altera = false;
  } 

	// VERIFICA SE DEU ERRO
	if($altera) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar informacoes",$sql_alterar);
	
		echo "<script>";
		echo "open('informacoes.php?aviso=ok&msg=Informações da empresa alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_alterar);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as Informações da empresa.','_self');";
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
<body onload="document.form1.endereco_ftp.focus();">
<div class="titulo-sessao-azul">Alterar informa&ccedil;&otilde;es da empresa</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar">
  <tr>
    <td>
      <div id="alterar_informacoes" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da empresa</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">

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
                <td class="campo-form">Telefones:</td>
                <td><input name="telefones" type="text" id="telefones" value="<?php echo $executa_informacoes['telefone']; ?>" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">E-mail (contato):</td>
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
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar altera&ccedil;&otilde;es" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var alterar_informacoes = new Spry.Widget.TabbedPanels("alterar_informacoes");
</script>
</body>
</html>