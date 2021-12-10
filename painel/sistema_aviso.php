<?php if($_GET['aviso'] == "ok") { ?>
<div id="avisoMsg">
<p>
<table width="315" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_esquerdo_topo.gif" width="9" height="9" /></td>
    <td bgcolor="#FFFFE1" class="linha-topo-box-aviso"><img src="../imagens/painel/transparente.gif" width="1" height="1" /></td>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_direito_topo.gif" width="9" height="9" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFE1" class="linha-esquerda-box-aviso"><img src="../imagens/painel/transparente.gif" width="1" height="1" /></td>
    <td bgcolor="#FFFFE1">
      <table width="100%" border="0" cellspacing="3" cellpadding="3">
          <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="../imagens/icone/icone_ok.png" width="16" height="16" /></td>
                    <td>&nbsp;</td>
                    <td class="titulo-aviso">Ok!</td>
                  </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td><?php echo $_GET['msg']; ?></td>
          </tr>
          <tr>
            <td><a href="javascript: ocultar_camada('avisoMsg');" class="fechar-aviso-box-aviso">Fechar aviso</a></td>
          </tr>
      </table>
    </td>
    <td bgcolor="#FFFFE1" class="linha-direita-box-aviso"><img src="../imagens/painel/transparente.gif" width="1" height="1" /></td>
  </tr>
  <tr>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_esquerdo_baixo.gif" width="9" height="9" /></td>
    <td bgcolor="#FFFFE1" class="linha-baixo-box-aviso"><img src="../imagens/painel/transparente.gif" width="1" height="1" /></td>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_direito_baixo.gif" width="9" height="9" /></td>
  </tr>
</table>
</p>
</div>
<?php } ?>
<?php if($_GET['aviso'] == "erro") { ?>
<div id="avisoMsg">
<p>
<table width="315" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_esquerdo_topo.gif" width="9" height="9" /></td>
    <td bgcolor="#FFFFE1" class="linha-topo-box-aviso"><img src="../imagens/painel/transparente.gif" width="1" height="8" /></td>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_direito_topo.gif" width="9" height="9" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFE1" class="linha-esquerda-box-aviso"><img src="../imagens/painel/transparente.gif" width="8" height="1" /></td>
    <td bgcolor="#FFFFE1">
      <table width="100%" border="0" cellspacing="3" cellpadding="3">
          <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="../imagens/icone/icone_erro.png" width="16" height="16" /></td>
                    <td>&nbsp;</td>
                    <td class="titulo-aviso">Erro!</td>
                  </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td>Ocorreu um erro!</td>
        </tr>
          <tr>
            <td><?php echo $_GET['msg']; ?></td>
          </tr>
          <tr>
            <td><a href="javascript: ocultar_camada('avisoMsg');" class="fechar-aviso-box-aviso">Fechar aviso</a></td>
          </tr>
      </table>
    </td>
    <td bgcolor="#FFFFE1" class="linha-direita-box-aviso"><img src="../imagens/painel/transparente.gif" width="8" height="1" /></td>
  </tr>
  <tr>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_esquerdo_baixo.gif" width="9" height="9" /></td>
    <td bgcolor="#FFFFE1" class="linha-baixo-box-aviso"><img src="../imagens/painel/transparente.gif" width="1" height="8" /></td>
    <td width="9" height="9"><img src="../imagens/painel/box_aviso_canto_direito_baixo.gif" width="9" height="9" /></td>
  </tr>
</table>
</p>
</div>
<?php } ?>