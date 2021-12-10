<?php include("sistema_mod_include.php"); // Faz um include de tudo ?>
<?php
verifica_sessao(); // verifica a sessao
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<frameset rows="48,*" cols="*" frameborder="no" border="0" framespacing="0">
  <frame src="sistema_topo.php" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" />
  <frameset cols="202,*" frameborder="no" border="0" framespacing="0" bordercolor="#0066CC">
    <frame src="sistema_lateral.php" name="leftFrame" scrolling="auto" noresize="noresize" id="leftFrame" style="z-index:-10" />
    <frame src="sistema_principal.php" name="principal" id="principal" />
  </frameset>
</frameset>
<noframes></noframes>
</html>