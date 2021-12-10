<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Pega o id
$codigo_revendedor = (int) base64_decode(addslashes(trim($_GET['codigo_revendedor'])));

// Pega os dados
$sql_revendedor = "SELECT 
revendedor.nome_revendedor, 
revendedor.endereco_revendedor, 
revendedor.telefone_revendedor, 
revendedor.codigo_bairro, 
revendedor.status,
estado.codigo_estado,
cidade.codigo_cidade
FROM revendedor 
JOIN bairro ON revendedor.codigo_bairro = bairro.codigo_bairro
JOIN cidade ON bairro.codigo_cidade = cidade.codigo_cidade
JOIN estado ON cidade.codigo_estado = estado.codigo_estado
WHERE revendedor.codigo_revendedor = '".$codigo_revendedor."'";
$query_revendedor = $pdo->query($sql_revendedor);
$executa_revendedor = $query_revendedor->fetch( PDO::FETCH_ASSOC );

if($_GET['acao'] == 'alterar') {
	// PEGA OS DADOS DO FORMULÁRIO
	$nome_revendedor = addslashes(trim($_POST['nome_revendedor']));
  $bairro = addslashes(trim($_POST['bairro']));
  $endereco_revendedor = addslashes(trim($_POST['endereco_revendedor']));
  $telefone_revendedor = addslashes(trim($_POST['telefone_revendedor']));
	$status = addslashes(trim($_POST['status']));
		
	try{

		// REALIZA A ALTERAÇÃO
		$sql_alterar = "UPDATE revendedor SET nome_revendedor = '".$nome_revendedor."', endereco_revendedor = '".$endereco_revendedor."', telefone_revendedor = '".$telefone_revendedor."', codigo_bairro = '".$bairro."',  status = '".$status."' WHERE codigo_revendedor = ".$codigo_revendedor."";
		$atualiza_revendedor = $pdo->prepare($sql_alterar);
		$atualiza_revendedor->execute();

		$altera = true;
		
	}catch (PDOException $e){
		echo 'Error: '. $e->getMessage();
		$altera = false;
	} 

	// VERIFICA SE DEU ERRO
	if($altera) {
		// Log
		salvar_log($_SESSION["id_usuario"],"alterar revendedor",$sql_alterar, $pdo);
	
		echo "<script>";
		echo "open('gerenciar_revendedores.php?aviso=ok&msg=Informações do revendedor alterada com sucesso.','_self');";
		echo "</script>";
	} else {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui, $pdo);
	
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=Não foi possível alterar as informações do revendedor.&codigo_revendedor=".base64_encode($codigo_revendedor)."','_self');";
		echo "</script>";
	}
}

// Busca estados
$sql_estados = "SELECT codigo_estado, nome_estado FROM estado WHERE status = 'L' ORDER BY nome_estado ASC";
$query_estados = $pdo->query($sql_estados);
$resultado_estado = $query_estados->fetchAll( PDO::FETCH_ASSOC );


// Busca cidades
$sql_cidades = "SELECT codigo_cidade, nome_cidade FROM cidade WHERE status = 'L' ORDER BY nome_cidade ASC";
$query_cidades = $pdo->query($sql_cidades);
$resultado_cidade = $query_cidades->fetchAll( PDO::FETCH_ASSOC );

// Busca bairros
$sql_bairros = "SELECT codigo_bairro, nome_bairro FROM bairro WHERE status = 'L' ORDER BY nome_bairro ASC";
$query_bairros = $pdo->query($sql_bairros);
$resultado_bairro = $query_bairros->fetchAll( PDO::FETCH_ASSOC );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body onload="document.form1.nome_revendedor.focus();">
<div class="titulo-sessao-azul">Alterar revendedor</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar&codigo_revendedor=<?php echo base64_encode($codigo_revendedor); ?>"  enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="alterar_revendedor" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Informa&ccedil;&otilde;es</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="3" cellpadding="0">
            <tr>
                <td class="campo-form">Nome do Revendedor:</td>
                <td><input name="nome_revendedor" class="campo-nome" type="text" id="nome_revendedor" size="50" maxlength="150" value="<?php echo $executa_revendedor["nome_revendedor"];?>" /></td>
              </tr>
              
              <tr>
                <td class="campo-form">Estado:</td>
                <td>
                <select name="estado" id="estado" class="select-tipo" onchange="carregaCidade(this.value);">
                <option value="">Selecione o Estado</option>
                <?php foreach($resultado_estado AS $estado):?>
                  <option value="<?php echo $estado["codigo_estado"];?>" <?php if($executa_revendedor['codigo_estado'] == $estado["codigo_estado"]) { echo 'selected="selected"'; } ?>><?php echo $estado["nome_estado"];?></option>
                <?php endforeach;?>
                </select>
                </td>
              </tr>

              <tr>
                <td class="campo-form">Cidade:</td>
                <td>
                <select name="cidade" id="cidade" class="select-tipo" onchange="carregaBairro(this.value);">
                <?php foreach($resultado_cidade AS $cidade):?>
                <option value="<?php echo $cidade["codigo_cidade"];?>" <?php if($executa_revendedor['codigo_cidade'] == $cidade["codigo_cidade"]) { echo 'selected="selected"'; } ?>><?php echo $cidade["nome_cidade"];?></option>
                <?php endforeach;?>
                </select>
                </td>
              </tr>

              <tr>
                <td class="campo-form">Bairro:</td>
                <td>
                <select name="bairro" id="bairro" class="select-tipo">
                <?php foreach($resultado_bairro AS $bairro):?>
                <option value="<?php echo $bairro["codigo_bairro"];?>" <?php if($executa_revendedor['bairro'] == $bairro["codigo_bairro"]) { echo 'selected="selected"'; } ?>><?php echo $bairro["nome_bairro"];?></option>
                <?php endforeach;?>
                </select>
                </td>
              </tr>

              <tr>
                <td class="campo-form">Endereço:</td>
                <td><input name="endereco_revendedor" class="campo-nome" type="text" id="endereco_revendedor" size="50" maxlength="150" value="<?php echo $executa_revendedor["endereco_revendedor"];?>" /></td>
              </tr>

              <tr>
                <td class="campo-form">Telefone:</td>
                <td><input name="telefone_revendedor" class="campo-nome" type="text" id="telefone_revendedor" size="20" maxlength="20" value="<?php echo $executa_revendedor["telefone_revendedor"];?>" /></td>
              </tr>

              <tr>
                <td class="campo-form">Status:</td>
                <td>
                <select name="status" id="status">
                  <option value="L" <?php if($executa_revendedor['status'] == "L") { echo 'selected="selected"'; } ?>>Liberado</option>
                  <option value="B" <?php if($executa_revendedor['status'] == "B") { echo 'selected="selected"'; } ?>>Bloqueado</option>
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
var alterar_revendedor = new Spry.Widget.TabbedPanels("alterar_revendedor");

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