<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Coletando dados para consulta
$nome = addslashes(trim($_GET['nome']));

if($nome) {
	$nomes = explode(" ",$nome);
	$total_nomes = sizeof($nomes);
	
	$condicao_consulta .= " AND (";
	
	for($i=0;$i<$total_nomes;$i++) {
		$condicao_consulta .= "produto.nome_produto LIKE '%".$nomes[$i]."%'";
		
		if(($i+1) != $total_nomes) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";	
	
	unset($i);
}

// Coleta os dados
$sql_produtos = "SELECT produto.id_produto, produto.nome_produto, produto.texto_produto, produto.status, subtipo_produto.nome_subtipo_produto, tipo_produto.nome_tipo_produto  FROM produto
                  JOIN subtipo_produto ON produto.id_subtipo_produto = subtipo_produto.id_subtipo_produto
                  JOIN tipo_produto ON subtipo_produto.id_tipo_produto = tipo_produto.id_tipo_produto
                  WHERE produto.status <> 'E'".$condicao_consulta." ORDER BY produto.id_produto DESC";
$query_produtos = $pdo->query($sql_produtos);
$resultado_produtos = $query_produtos->fetchAll( PDO::FETCH_ASSOC );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body>
<div class="titulo-sessao-azul">produtos</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td><input type="button" name="button" id="button" onclick="javascript: window.open('incluir_produto.php','_self');" value="Incluir novo produto" /></td>
  </tr>
  <tr>
    <td>
      <div id="gerenciar_produto" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Filtros</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="nome" type="text" id="nome" size="50" maxlength="150" value="<?php echo $nome; ?>" /></td>
              </tr>
              <tr>
                <td class="campo-form">&nbsp;</td>
                <td><input type="submit" name="button" id="button" value="Filtrar produtos" /></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</form>
</table>
<script type="text/javascript">
var gerenciar_produto = new Spry.Widget.TabbedPanels("gerenciar_produto");
</script>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EDEFF3">
  <tr>
    <td><div class="barra-endereco"></div></td>
  </tr>
  <tr>
    <td>
        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
          <tr>
            <td width="20">&nbsp;</td>
            <td width="20">Id</td>
            <td>Produto</td>
            <td>Tipo</td>
            <td>Subtipo</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php foreach($resultado_produtos as $executa_produtos) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td>
            <div align="center">
			<?php
				if($executa_produtos['status'] == "L") {
					echo '<img src="../imagens/icone/icone_ok.png" title="Produto liberado" width="16" height="16" border="0" />';
				}
				if($executa_produtos['status'] == "B") {
					echo '<img src="../imagens/icone/icone_erro.png" title="Produto bloqueado" width="16" height="16" border="0" />';
				}
			?>
            </div>
            </td>
            <td><div align="center"><?php echo $executa_produtos['id_produto']; ?></div></td>
            <td><?php echo $executa_produtos['nome_produto']; ?></td>
            <td><?php echo $executa_produtos['nome_tipo_produto']; ?></td>
            <td><?php echo $executa_produtos['nome_subtipo_produto']; ?></td>
            <td><div align="center"><a href="incluir_produto_fotos_destaque.php?id_produto=<?php echo base64_encode($executa_produtos['id_produto']); ?>" target="_self"><img src="../imagens/icone/icone_foto.png" title="Fotos da produto" width="16" height="16" border="0" /></a></div></td>
            <td><div align="center"><a href="alterar_produto.php?id_produto=<?php echo base64_encode($executa_produtos['id_produto']); ?>" target="_self"><img src="../imagens/icone/icone_alterar.png" title="Alterar a produto" width="16" height="16" border="0" /></a></div></td>
            <td><div align="center"><a href="javascript: confirma_acao('Tem certeza que deseja excluir &quot;<?php echo $executa_produtos['nome_produto']; ?>&quot;?','excluir_produto.php?id_produto=<?php echo base64_encode($executa_produtos['id_produto']); ?>');" target="_self"><img src="../imagens/icone/icone_excluir.gif" title="Excluir a produto" width="16" height="16" border="0" /></a></div></td>
          </tr>
          <?php $i += 1; } ?>
        </table>
    </td>
  </tr>
</table>
</body>
</html>