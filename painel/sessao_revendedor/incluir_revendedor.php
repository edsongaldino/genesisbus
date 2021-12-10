<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

if($_GET['acao'] == 'incluir') {
	// PEGA OS DADOS DO FORMULÁRIO
  $nome_revendedor = addslashes(trim($_POST['nome_revendedor']));
  $bairro = addslashes(trim($_POST['bairro']));
  $endereco_revendedor = addslashes(trim($_POST['endereco_revendedor']));
  $telefone_revendedor = addslashes(trim($_POST['telefone_revendedor']));
	$status = addslashes(trim($_POST['status']));
		
	// Inclui o produto
	$sql_inclui = "INSERT INTO revendedor (nome_revendedor,endereco_revendedor,telefone_revendedor, codigo_bairro,status) VALUES ('".$nome_revendedor."','".$endereco_revendedor."','".$telefone_revendedor."','".$bairro."','".$status."')";
  $query_inclui = $pdo->prepare($sql_inclui);
  $query_inclui->execute();

	// VERIFICA SE DEU ERRO
	if($query_inclui) {
		// Log
		salvar_log($_SESSION["id_usuario"],"incluir revendedor",$sql_inclui,$pdo);

		echo "<script>";
		echo "open('gerenciar_revendedores.php?aviso=ok&msg=revendedor incluído com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui,$pdo);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível incluir o revendedor.','_self');";
		echo "</script>";
	}
}

// Busca estados
$sql_estados = "SELECT codigo_estado, nome_estado FROM estado WHERE status = 'L' ORDER BY nome_estado ASC";
$query_estados = $pdo->query($sql_estados);
$resultado_estado = $query_estados->fetchAll( PDO::FETCH_ASSOC );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body onload="document.form1.nome_revendedor.focus();">
<div class="titulo-sessao-azul">Incluir novo revendedor</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_cliente" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr>
                <td class="campo-form">Nome do Revendedor:</td>
                <td><input name="nome_revendedor" class="campo-nome" type="text" id="nome_revendedor" size="50" maxlength="150" /></td>
              </tr>
              
              <tr>
                <td class="campo-form">Estado:</td>
                <td>
                <select name="estado" id="estado" class="select-tipo" onchange="carregaCidade(this.value);">
                <option value="">Selecione o Estado</option>
                <?php foreach($resultado_estado AS $estado):?>
                  <option value="<?php echo $estado["codigo_estado"];?>"><?php echo $estado["nome_estado"];?></option>
                <?php endforeach;?>
                </select>
                </td>
              </tr>

              <tr>
                <td class="campo-form">Cidade:</td>
                <td>
                <select name="cidade" id="cidade" class="select-tipo" onchange="carregaBairro(this.value);">
                  <option value="">Selecione o Estado</option>
                </select>
                </td>
              </tr>

              <tr>
                <td class="campo-form">Bairro:</td>
                <td>
                <select name="bairro" id="bairro" class="select-tipo">
                  <option value="">Selecione a Cidade</option>
                </select>
                </td>
              </tr>

              <tr>
                <td class="campo-form">Endereço:</td>
                <td><input name="endereco_revendedor" class="campo-nome" type="text" id="endereco_revendedor" size="50" maxlength="150" placeholder="Endereço Ex: (Rua, número, complemento, CEP, ponto de referência" /></td>
              </tr>

              <tr>
                <td class="campo-form">Telefone:</td>
                <td><input name="telefone_revendedor" class="campo-nome" type="text" id="telefone_revendedor" size="20" maxlength="20" placeholder="Telefone" /></td>
              </tr>

              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status" class="select-tipo">
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
  	<td><div class="info-observacao"></div></td>
    <td><div align="right"><input type="submit" name="button" id="button" onclick="" value="Salvar novo revendedor" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_cliente = new Spry.Widget.TabbedPanels("incluir_cliente");

function carregaCidade(codigo_estado){
    
    var parametros = 'codigo_estado='+codigo_estado;
    action = 'carregaCidade.php';
    $.ajax({
        type: "POST",
        url: action,
        data: parametros,
        beforeSend: function() {
            $('#cidade').empty().append('<option value="0">Aguarde...</option>');
        },        
        success: function(txt) {  
            $('#cidade').empty().append(txt);
        },
        error: function(txt) {
            $('#cidade').empty().append('<option value="0">Erro!</option>');
        }
    });
}

function carregaBairro(codigo_cidade){
    
    var parametros = 'codigo_cidade='+codigo_cidade;
    action = 'carregaBairro.php';
    $.ajax({
        type: "POST",
        url: action,
        data: parametros,
        beforeSend: function() {
            $('#bairro').empty().append('<option value="0">Aguarde...</option>');
        },        
        success: function(txt) {  
            $('#bairro').empty().append(txt);
        },
        error: function(txt) {
            $('#bairro').empty().append('<option value="0">Erro!</option>');
        }
    });
}

</script>
</body>
</html>