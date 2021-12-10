<?php include("sistema_mod_include.php"); // Faz um include de tudo ?>
<?php verifica_sessao(); // verifica a sessao ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<body>
<div class="titulo-sessao-azul">&nbsp;</div>
<?php include("sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">
      <div id="atalhos" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Atalhos</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table border="0" cellpadding="0" cellspacing="15">
              <tr>
                <td><div align="center">&nbsp;</div></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
</table>
<script type="text/javascript">
var atalhos = new Spry.Widget.TabbedPanels("atalhos");
</script>
</body>
</html>