<?php include("../../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao

if(($_GET['acao'] == 'incluir') || ($_GET['acao'] == 'alterar')) {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome = verifica_vazio($_POST['nome'], "Não informado");
	$icone = $_FILES['icone'];
	$status = $_POST['status'];
}

if($_GET['acao'] == 'incluir') {
	// VERIFICA SE EXISTE UM ARQUIVO LOGOMARCA
	if($icone["type"]) {
		// Gera unidade unico para salvar
		$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".remove_acentos($nome);
	
		// Gera o novo unidade
		$extensao_icone = strtolower(substr($icone["name"],(strlen($icone["name"])-3),strlen($icone["name"])));
		$nome_icone = $nome_unico.".".$extensao_icone;
	}
	
	// REALIZA A INCLUSAO
	$sql_inclui = "INSERT INTO site_proximidades (nome,icone,status) VALUES ('".$nome."','".$nome_icone."','".$status."')";
	$query_inclui = @mysql_query($sql_inclui);
	
	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		if($icone["type"]) {
			// Conectando com o FTP para criar a pasta das fotos
			$ftp_conexao = @ftp_connect($resultado_info['endereco_ftp']);
			$ftp_login = @ftp_login($ftp_conexao, $resultado_info['login_ftp'], base64_decode($resultado_info['senha_ftp']));
			$ftp_upload_icone = @ftp_put($ftp_conexao,"/www/imagens/proximidade/".$nome_icone,$icone["tmp_name"],FTP_BINARY);
			$ftp_fechando_conexao = @ftp_quit($ftp_conexao); // fecha conexão
			
			// Verifica se deu erro no upload da icone
			if($ftp_upload_icone) {
				salvar_log($_SESSION["id_usuario"],"erro","não foi possivel incluir o icone ".$nome_icone);
			}		
		}
		
		salvar_log($_SESSION["id_usuario"],"incluir proximidade",$sql_inclui);
		redireciona("gerenciar.php?aviso=ok&msg=Informações da proximidade salvas com sucesso.","_self");
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
		$sql_dados_alterar = "SELECT site_proximidades.nome, site_proximidades.icone, site_proximidades.status FROM site_proximidades WHERE site_proximidades.id_proximidade = '".$id."'";
		$query_dados_alterar = @mysql_query($sql_dados_alterar);
		$executa_dados_alterar = @mysql_fetch_assoc($query_dados_alterar);
	} else {
		// VERIFICA SE EXISTE UM ARQUIVO LOGOMARCA
		if($icone["type"]) {
			// Gera unidade unico para salvar
			$nome_unico = substr(str_shuffle('0123456789abcdefghijlkmnopqrstuvwxyz'), 0, 5)."_".remove_acentos($nome);
		
			// Gera o novo unidade
			$extensao_icone = strtolower(substr($icone["name"],(strlen($icone["name"])-3),strlen($icone["name"])));
			$nome_icone = $nome_unico.".".$extensao_icone;
			
			// Conectando com o FTP para criar a pasta das fotos
			$ftp_conexao = @ftp_connect($resultado_info['endereco_ftp']);
			$ftp_login = @ftp_login($ftp_conexao, $resultado_info['login_ftp'], base64_decode($resultado_info['senha_ftp']));
			$ftp_upload_icone = @ftp_put($ftp_conexao,"/www/imagens/proximidade/".$nome_icone,$icone["tmp_name"],FTP_BINARY);
			$ftp_fechando_conexao = @ftp_quit($ftp_conexao); // fecha conexão
			
			// Verifica se deu erro no upload da icone
			if($ftp_upload_icone) {
				salvar_log($_SESSION["id_usuario"],"erro","não foi possivel incluir o icone ".$nome_icone);
			}
			
			// REALIZA A ALTERACAO
			$sql_altera = "UPDATE site_proximidades SET nome = '".$nome."', icone = '".$nome_icone."', status = '".$status."' WHERE id_proximidade = '".$id."'";
			$query_altera = @mysql_query($sql_altera);
		} else {
			// REALIZA A ALTERACAO
			$sql_altera = "UPDATE site_proximidades SET nome = '".$nome."', status = '".$status."' WHERE id_proximidade = '".$id."'";
			$query_altera = @mysql_query($sql_altera);
		}
		
		// VERIFICA SE DEU ERRO
		if($query_altera) {
			salvar_log($_SESSION["id_usuario"],"alterar proximidade",$sql_altera);
			redireciona("gerenciar.php?aviso=ok&msg=Informações da proximidade salvas com sucesso.","_self");
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
<div class="titulo-sessao-azul">Proximidade</div>
<?php include("../../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
if($_GET['acao'] == "alterar") {
	$action = $_SERVER['PHP_SELF']."?acao=alterar&tipo=salvar&id=".$id;
} else {
	$action = $_SERVER['PHP_SELF']."?acao=incluir";
}
?>
<form id="form1" name="form1" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="tabFormulario" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es da proximidade</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="campo-form">Nome</td>
                <td><input name="nome" type="text" id="nome" value="<?php echo $executa_dados_alterar['nome']; ?>" size="50" maxlength="100" ></td>
              </tr>
              <tr>
                <td class="campo-form">Ícone:</td>
                <td><input name="icone" type="file" id="icone" size="50" /> (16 x 16px)</td>
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
    <td><div class="info-observacao"><span class="negrito">*</span> Recomendações da extensão e / ou tamanho do arquivo.</div></td>
    <td><div align="right"><input type="submit" name="button" id="button" value="Salvar informações" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var tabFormulario = new Spry.Widget.TabbedPanels("tabFormulario");
</script>
</body>
</html>