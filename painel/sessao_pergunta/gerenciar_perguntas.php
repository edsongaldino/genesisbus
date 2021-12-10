<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

// Coletando dados para consulta
$pergunta = addslashes(trim($_GET['pergunta']));

if($pergunta) {
	$perguntas = explode(" ",$pergunta);
	$total_perguntas = sizeof($perguntas);
	
	$condicao_consulta .= " AND (";
	
	for($i=0;$i<$total_perguntas;$i++) {
		$condicao_consulta .= "pergunta LIKE '%".$perguntas[$i]."%'";
		
		if(($i+1) != $total_perguntas) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";
	
	unset($i);
}

// Coleta os dados
$sql_perguntas_resposta = "SELECT id_pergunta_resposta, pergunta, status FROM perguntas_resposta WHERE status <> 'E'".$condicao_consulta." ORDER BY id_pergunta_resposta DESC";
$query_perguntas_resposta = mysql_query($sql_perguntas_resposta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body>
<div class="titulo-sessao-azul">Perguntas e resposta</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td><input type="button" name="button" id="button" onclick="javascript: window.open('incluir_pergunta.php','_self');" value="Incluir nova pergunta e resposta" /></td>
  </tr>
  <tr>
    <td>
      <div id="gerenciar_pergunta_resposta" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Filtros</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="campo-form">Nome:</td>
                <td><input name="pergunta" type="text" id="pergunta" size="50" maxlength="150" value="<?php echo $pergunta; ?>" /></td>
              </tr>
              <tr>
                <td class="campo-form">&nbsp;</td>
                <td><input type="submit" name="button" id="button" value="Filtrar perguntas e resposta" /></td>
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
var gerenciar_pergunta_resposta = new Spry.Widget.TabbedPanels("gerenciar_pergunta_resposta");
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
            <td>Pergunta</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php while($executa_perguntas_resposta = mysql_fetch_assoc($query_perguntas_resposta)) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td>
            <div align="center">
			<?php
				if($executa_perguntas_resposta['status'] == "L") {
					echo '<img src="../imagens/icone/icone_ok.png" title="Pergunta e resposta liberada" width="16" height="16" border="0" />';
				}
				if($executa_perguntas_resposta['status'] == "B") {
					echo '<img src="../imagens/icone/icone_erro.png" title="Pergunta e resposta bloqueada" width="16" height="16" border="0" />';
				}
			?>
            </div>
            </td>
            <td><div align="center"><?php echo $executa_perguntas_resposta['id_pergunta_resposta']; ?></div></td>
            <td><?php echo $executa_perguntas_resposta['pergunta']; ?></td>
            <td><div align="center"><a href="alterar_pergunta.php?id_pergunta_resposta=<?php echo base64_encode($executa_perguntas_resposta['id_pergunta_resposta']); ?>" target="_self"><img src="../imagens/icone/icone_alterar.png" title="Alterar a pergunta e resposta" width="16" height="16" border="0" /></a></div></td>
            <td><div align="center"><a href="javascript: confirma_acao('Tem certeza que deseja excluir &quot;<?php echo $executa_perguntas_resposta['pergunta']; ?>&quot;?','excluir_pergunta.php?id_pergunta_resposta=<?php echo base64_encode($executa_perguntas_resposta['id_pergunta_resposta']); ?>');" target="_self"><img src="../imagens/icone/icone_excluir.gif" title="Excluir a pergunta e resposta" width="16" height="16" border="0" /></a></div></td>
          </tr>
          <?php $i += 1; } ?>
        </table>
    </td>
  </tr>
</table>
</body>
</html>