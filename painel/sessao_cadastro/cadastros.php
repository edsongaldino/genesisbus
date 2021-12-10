<?php include("../sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<body>
<div class="titulo-sessao-azul">Cadastros</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">
      <div id="cadastros" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Escolha um cadastro</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table border="0" cellpadding="10" cellspacing="10">
              <tr>
                <td><div align="center"><a href="sessao_estado/gerenciar.php" target="_self"><img src="../imagens/painel/cadastro_estado.jpg" alt="Gerenciar estados" title="Gerenciar estados" width="40" height="40" border="0" /></a></div><div align="center">Estados</div></td>
                <td><div align="center"><a href="sessao_cidade/gerenciar.php" target="_self"><img src="../imagens/painel/cadastro_cidade.jpg" alt="Gerenciar cidades" title="Gerenciar cidades" width="40" height="40" border="0" /></a></div><div align="center">Cidades</div></td>
                <td><div align="center"><a href="sessao_bairro/gerenciar.php" target="_self"><img src="../imagens/painel/cadastro_bairro.jpg" alt="Gerenciar bairros" title="Gerenciar bairros" width="40" height="40" border="0" /></a></div><div align="center">Bairros</div></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
</table>
<script type="text/javascript">
var cadastros = new Spry.Widget.TabbedPanels("cadastros");
</script>
</body>
</html>