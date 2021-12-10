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
		$condicao_consulta .= "nome LIKE '%".$nomes[$i]."%'";
		
		if(($i+1) != $total_nomes) {
			$condicao_consulta .= " OR ";
		}
	}
	
	$condicao_consulta .= ")";
	
	unset($i);
}

// Coleta os dados
$sql_videos = "SELECT id_video, nome, status FROM videos WHERE status <> 'E'".$condicao_consulta." ORDER BY id_video DESC";
$query_videos = mysql_query($sql_videos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body>
<div class="titulo-sessao-azul">V&iacute;deos</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=consultar" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td><input type="button" name="button" id="button" onclick="javascript: window.open('incluir_video.php','_self');" value="Incluir novo v&iacute;deo" /></td>
  </tr>
  <tr>
    <td>
      <div id="gerenciar_video" class="TabbedPanels">
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
                <td><input type="submit" name="button" id="button" value="Filtrar v&iacute;deos" /></td>
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
var gerenciar_video = new Spry.Widget.TabbedPanels("gerenciar_video");
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
            <td>Nome</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
          </tr>
          <?php while($executa_videos = mysql_fetch_assoc($query_videos)) { ?>
          <tr bgcolor="<?php if(is_int($i/2) != 1) { echo "#EDEFF3"; } else { echo "#FFFFFF"; }; ?>">
            <td>
            <div align="center">
			<?php
				if($executa_videos['status'] == "L") {
					echo '<img src="../imagens/icone/icone_ok.png" title="V&iacute;deo liberado" width="16" height="16" border="0" />';
				}
				if($executa_videos['status'] == "B") {
					echo '<img src="../imagens/icone/icone_erro.png" title="V&iacute;deo bloqueado" width="16" height="16" border="0" />';
				}
			?>
            </div>
            </td>
            <td><div align="center"><?php echo $executa_videos['id_video']; ?></div></td>
            <td><?php echo $executa_videos['nome']; ?></td>
            <td><div align="center"><a href="alterar_video.php?id_video=<?php echo base64_encode($executa_videos['id_video']); ?>" target="_self"><img src="../imagens/icone/icone_alterar.png" title="Alterar o v&iacute;deo" width="16" height="16" border="0" /></a></div></td>
            <td><div align="center"><a href="javascript: confirma_acao('Tem certeza que deseja excluir &quot;<?php echo $executa_videos['nome']; ?>&quot;?','excluir_video.php?id_video=<?php echo base64_encode($executa_videos['id_video']); ?>');" target="_self"><img src="../imagens/icone/icone_excluir.gif" title="Excluir o v&iacute;deo" width="16" height="16" border="0" /></a></div></td>
          </tr>
          <?php $i += 1; } ?>
        </table>
    </td>
  </tr>
</table>
</body>
</html>