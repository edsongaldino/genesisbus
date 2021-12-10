<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMULÁRIO
	$descricao = addslashes(trim($_POST['descricao']));
	$tamanho = addslashes(trim($_POST['tamanho']));
	$destino = addslashes(trim($_POST['destino']));
	$arquivo = $_FILES['arquivo'];
	$observacoes = addslashes(trim($_POST['observacoes']));	
	$data_inicial = converte_data_mysql($_POST['data_inicial']);
	$data_final = converte_data_mysql($_POST['data_final']);	
	$status = addslashes(trim($_POST['status']));

	// Verifica se existe um arquivo
	if($arquivo) {
		
		$uploaddir = '../../conteudos/banner/';
		$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);

		if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
			$nome_arquivo = basename($_FILES['arquivo']['name']);
		}
		else{
				// Log
			salvar_log($_SESSION["id_usuario"],"erro","não foi possivel incluir o arquivo ".$nome_arquivo,$pdo);
		}
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro","não foi possivel incluir o arquivo ".$nome_arquivo,$pdo);
	}
		
	// Inclui
	$sql_inclui = "INSERT INTO banners (descricao,tamanho,destino,arquivo,observacoes,data_inicial,data_final,status) VALUES ('".$descricao."','".$tamanho."','".$destino."','".$nome_arquivo."','".$observacoes."','".$data_inicial."','".$data_final."','".$status."')";
  $query_inclui = $pdo->prepare($sql_inclui);
  $query_inclui->execute();

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir banner",$sql_inclui);

		echo "<script>";
		echo "open('gerenciar_banners.php?aviso=ok&msg=banner incluído com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir o banner.','_self');";
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
<body onload="document.form1.descricao.focus();">
<div class="titulo-sessao-azul">Incluir novo banner</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_banner" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es do banner</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Descrição:</td>
                <td><input name="descricao" type="text" id="descricao" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Tamanho:</td>
                <td>
                <select name="tamanho" id="tamanho">
                  <option value="940x330">940x330</option>
                </select>
                </td>
              </tr>
              <tr>
                <td class="campo-form">Destino:</td>
                <td>http:// <input name="destino" type="text" id="destino" size="50" maxlength="100" /></td>
              </tr>
              <tr>
                <td class="campo-form">Arquivo:</td>
                <td><input name="arquivo" type="file" id="arquivo" size="50" /> (.jpg 940x330 px *)</td>
              </tr>
              <tr>
                <td valign="top" class="campo-form">Observa&ccedil;&otilde;es:</td>
                <td><textarea name="observacoes" cols="50" rows="5" id="observacoes"></textarea></td>
              </tr>              
              <tr>
                <td class="campo-form">Data inicial:</td>
                <td><input name="data_inicial" type="text" id="data_inicial" size="10" maxlength="10" /></td>
              </tr>
              <tr>
                <td class="campo-form">Data final:</td>
                <td><input name="data_final" type="text" id="data_final" size="10" maxlength="10" /></td>
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
  	<td><div class="info-observacao"><span class="negrito">*</span> Recomendações da extensão e / ou tamanho do arquivo.</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar novo banner" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_banner = new Spry.Widget.TabbedPanels("incluir_banner");
</script>
</body>
</html>