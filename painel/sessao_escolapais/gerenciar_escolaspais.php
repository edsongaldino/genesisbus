<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Coletando dados para consulta
$titulo = addslashes(trim($_GET['titulo']));

if($titulo) {
	$titulos = explode(" ",$titulo);
	$total_titulos = sizeof($titulos);
	
	$condicao_consulta .= " AND (";
	
	for($i=0;$i<$total_titulos;$i++) {
		$condicao_consulta .= "titulo LIKE '%".$titulos[$i]."%'";
		
		if(($i+1) != $total_titulos) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";
	
	unset($i);
}

// Coleta os dados
$sql_escola_pais = "SELECT id_escola_pais, titulo, data, status FROM escola_pais WHERE status <> 'E'".$condicao_consulta." ORDER BY id_escola_pais DESC";
$query_escola_pais = mysql_query($sql_escola_pais);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body>
<div class="titulo-sessao-azul">Escola de pais</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td><input type="button" name="button" id="button" onclick="javascript: window.open('incluir_escolapais.php','_self');" value="Incluir nova escola de pais" /></td>
  </tr>
  <tr>
    <td>
      <div id="gerenciar_escola_pais" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Filtros</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="campo-form">Título:</td>
                <td><input name="titulo" type="text" id="titulo" size="50" maxlength="150" value="<?php echo $titulo; ?>" /></td>
              </tr>
              <tr>
                <td class="campo-form">&nbsp;</td>
                <td><input type="submit" name="button" id="button" value="Filtrar escola de pais" /></td>
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
var gerenciar_escola_pais = new Spry.Widget.TabbedPanels("gerenciar_escola_pais");
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
            <td width="80">Data</td>
            <td>Título</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php while($executa_escola_pais = mysql_fetch_assoc($query_escola_pais)) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td>
            <div align="center">
			<?php
				if($executa_escola_pais['status'] == "L") {
					echo '<img src="../imagens/icone/icone_ok.png" title="Escola de pais liberada" width="16" height="16" border="0" />';
				}
				if($executa_escola_pais['status'] == "B") {
					echo '<img src="../imagens/icone/icone_erro.png" title="Escola de pais bloqueada" width="16" height="16" border="0" />';
				}
			?>
            </div>
            </td>
            <td><div align="center"><?php echo $executa_escola_pais['id_escola_pais']; ?></div></td>
            <td><?php echo converte_data_port($executa_escola_pais['data']); ?></td>
            <td><?php echo $executa_escola_pais['titulo']; ?></td>
            <td><div align="center"><a href="alterar_escolapais.php?id_escola_pais=<?php echo base64_encode($executa_escola_pais['id_escola_pais']); ?>" target="_self"><img src="../imagens/icone/icone_alterar.png" title="Alterar a escola de pais" width="16" height="16" border="0" /></a></div></td>
            <td><div align="center"><a href="javascript: confirma_acao('Tem certeza que deseja excluir &quot;<?php echo $executa_escola_pais['titulo']; ?>&quot;?','excluir_escolapais.php?id_escola_pais=<?php echo base64_encode($executa_escola_pais['id_escola_pais']); ?>');" target="_self"><img src="../imagens/icone/icone_excluir.gif" title="Excluir a escola de pais" width="16" height="16" border="0" /></a></div></td>
          </tr>
          <?php $i += 1; } ?>
        </table>
    </td>
  </tr>
</table>
</body>
</html>