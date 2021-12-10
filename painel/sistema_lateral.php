<?php
include("sistema_mod_include.php");

verifica_sessao();

// Informações do usuario
$sql_info_usuario = "SELECT nome, DATE_FORMAT(ultimo_acesso,'%d/%m/%Y %H:%i:%s') AS ultimo_acesso FROM sistema_usuarios WHERE id_usuario = '".$_SESSION["id_usuario"]."'";
$query_info_usuario = $pdo->query( $sql_info_usuario );
$resultado_info_usuario = $query_info_usuario->fetch( PDO::FETCH_ASSOC );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("sistema_mod_include_head.php"); ?>
</head>
<body id="lateral-body">
<div class="lateral-titulo-sessao">&Aacute;rea de trabalho</div>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="15">
  <tr>
    <td><div align="center" class="negrito"><?php echo $resultado_info_usuario['nome']; ?></div><div align="center" title="�ltimo acesso"><?php echo $resultado_info_usuario['ultimo_acesso']; ?></div></td>
  </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_usuario/gerenciar_usuarios.php" target="principal" class="menu-link" title="Gerenciar usu&aacute;rios">Usu&aacute;rios</a></td>
  </tr>
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_sistema/informacoes.php" target="principal" class="menu-link" title="Informa&ccedil;&otilde;es">Informa&ccedil;&otilde;es</a></td>
  </tr>
  <!--
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="http://www.solucaotoaletes.com.br/stats" target="_blank" class="menu-link" title="Estat&iacute;stica do site">Estat&iacute;stica do site</a></td>
  </tr>
  -->
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <!--
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_equipe/gerenciar_equipe.php" target="principal" class="menu-link" title="Agenda">Equipe</a></td>
  </tr>
   
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_escolapais/gerenciar_escolaspais.php" target="principal" class="menu-link" title="Escola de pais">Escola de pais</a></td>
  </tr>
 
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_cadastro/cadastros.php" target="principal" class="menu-link" title="Localidades">Localidades</a></td>
  </tr>
   -->

  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_texto/gerenciar_textos.php" target="principal" class="menu-link" title="Textos">Textos</a></td>
  </tr> 
 
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_produto/gerenciar_produtos.php" target="principal" class="menu-link" title="Frota">Frota</a></td>
  </tr>

   <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_galeria/gerenciar_galerias.php" target="principal" class="menu-link" title="Viagens">Novidades / Fotos</a></td>
  </tr>

  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_diferencial/gerenciar_diferenciais.php" target="principal" class="menu-link" title="Depoimentos">Depoimentos</a></td>
  </tr> 


 <!-- 
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_revendedor/gerenciar_revendedores.php" target="principal" class="menu-link" title="Revendedores">Revendedores</a></td>
  </tr>

 
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_historia/gerenciar_historias.php" target="principal" class="menu-link" title="História">História</a></td>
  </tr>
  
  
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_link/gerenciar_links.php" target="principal" class="menu-link" title="Links">Links</a></td>
  </tr>
  
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_fazenda/gerenciar_fazendas.php" target="principal" class="menu-link" title="Fazendas">Fazendas</a></td>
  </tr>
  -->
  <!-- 
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_contatos/gerenciar_contatos.php" target="principal" class="menu-link" title="Newsletter">Contatos</a></td>
  </tr>
  
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_pergunta/gerenciar_perguntas.php" target="principal" class="menu-link" title="Perguntas e resposta">Perguntas e resp.</a></td>
  </tr>
  -->
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_slideshow/gerenciar_slideshows.php" target="principal" class="menu-link" title="Slideshow">Banners</a></td>
  </tr>
  
  <!--
  <tr>
    <td width="21"><div align="center"><img src="imagens/painel/icone_menu.gif" width="11" height="11" /></div></td>
    <td><a href="sessao_video/gerenciar_videos.php" target="principal" class="menu-link" title="V&iacute;deos">V&iacute;deos</a></td>
  </tr>
  -->
  
</table>
</body>
</html>