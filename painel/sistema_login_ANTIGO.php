<?php
// Inclui as informações
include("ferramentas/conexao_mysql.php");
include("ferramentas/sistema_informacoes.php");
include("ferramentas/funcao_php.php");

if($_GET['acao'] == 'sair') {
	// Inicializa sessão
	session_start();

	// Destroi a sessão
	session_destroy();
	
	// Salvando log
	salvar_log($_SESSION["id_usuario"],"logoff","session_destroy()");
	
	echo "<script>";
	echo "open('index.php','_parent');";
	echo "</script>";
}

if($_GET['acao'] == 'entrar') {
	if(($_POST['login'])) {
		$sql_verifica_usuario = "SELECT id_usuario FROM sistema_usuarios WHERE status = 'L' AND login = '".addslashes($_POST['login'])."'";
		$query_verifica_usuario = mysql_query($sql_verifica_usuario);
		$resultado_verifica_usuario = mysql_fetch_assoc($query_verifica_usuario);
		$total_verifica_usuario = mysql_num_rows($query_verifica_usuario);

		mysql_free_result($query_verifica_usuario);
		
		if($total_verifica_usuario) {
			if(($_POST['senha'])) {
				$sql_verifica_senha = "SELECT id_usuario FROM sistema_usuarios WHERE id_usuario = ".$resultado_verifica_usuario['id_usuario']." AND senha='".md5(addslashes($_POST['senha']))."'";
				$query_verifica_senha = mysql_query($sql_verifica_senha);
				$total_verifica_senha = mysql_num_rows($query_verifica_senha);

				mysql_free_result($query_verifica_senha);				

				if($total_verifica_senha) {
					$sql_usuario = "SELECT id_usuario FROM sistema_usuarios WHERE id_usuario = ".$resultado_verifica_usuario['id_usuario']."";
					$query_usuario = mysql_query($sql_usuario);
					$resultado_usuario = mysql_fetch_assoc($query_usuario);

					mysql_free_result($query_usuario);

					// Atualizando o ultimo acesso
					$sql_atualiza_usuario = "UPDATE sistema_usuarios SET ultimo_acesso = '".date("YmdHis",time())."' WHERE id_usuario = ".$resultado_usuario['id_usuario']."";
					$query_atualiza_usuario = mysql_query($sql_atualiza_usuario);
					
					// Verifica se a atualização do ultimo acesso deu erro
					if(!$query_atualiza_usuario) {
						// Salvando log
						salvar_log($resultado_usuario['id_usuario'],"erro > atualiza usuario",$sql_atualiza_usuario);					
					}

					session_start();
					$_SESSION["usuario_acesso"] = "765q3498b5t9erw87tsd48f6ds4f9849tre87t9r87ter";
					$_SESSION["id_usuario"] = $resultado_usuario['id_usuario'];

					// Salvando log
					salvar_log($_SESSION["id_usuario"],"login - usuario",$sql_verifica_usuario);
					salvar_log($_SESSION["id_usuario"],"login - senha",$sql_verifica_senha);
					salvar_log($_SESSION["id_usuario"],"login - autenticação",$sql_usuario);
					
					echo "<script>";
					echo "open('index.php','_parent');";
					echo "</script>";
				} else {
					echo "<script>";
					echo "open('sistema_login.php?msg=Digite novamente sua senha.','_parent');";
					echo "</script>";
				}
			}
		} else {
			echo "<script>";
			echo "open('sistema_login.php?msg=Digite novamente seu usuário.','_parent');";
			echo "</script>";
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php include("sistema_mod_include_head.php"); // Faz um include do head ?>
</head>
<body id="login-body" onLoad="javascript: document.form1.login.focus();">
<div id="logowalbernir"><a href="http://www.wcweb.com.br/externo/fmbs.com.br" target="_blank"><img src="imagens/painel/logo_bitline.jpg" width="60" height="64" border="0" title="Desenvolvido por: Bit Line Informática"></a></div>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="15" bgcolor="#14285A"></td>
  </tr>
  <tr>
    <td>
    <table width="402" border="0" align="center" cellpadding="0" cellspacing="0">
    <form name="form1" method="post" action="sistema_login.php?acao=entrar">
      <tr>
        <td width="67"><img src="imagens/painel/login_boneco1.jpg" width="67" height="228"></td>
        <td valign="bottom" background="imagens/painel/login_boneco2.jpg">
            <table border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td class="login-titulo">Digite seu login:</td>
              </tr>
              <tr>
                <td>
                  <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="imagens/painel/box_senha_canto_esquerdo.jpg" width="3" height="27"></td>
                      <td><input name="login" type="text" class="login-form-campo" id="login" size="12" maxlength="12"></td>
                      <td><img src="imagens/painel/box_senha_canto_direito.jpg" width="3" height="27"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="login-titulo">Digite sua senha:</td>
              </tr>
              <tr>
                <td>
                  <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="imagens/painel/box_senha_canto_esquerdo.jpg" width="3" height="27"></td>
                      <td><input name="senha" type="password" class="login-form-campo" id="senha" size="12" maxlength="12"></td>
                      <td><img src="imagens/painel/box_senha_canto_direito.jpg" width="3" height="27"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td><div align="right"><input type="submit" name="button" id="button" value="Entrar"></div></td>
              </tr>
              <tr>
                <td height="30">&nbsp;</td>
              </tr>
            </table>
        </td>
        <td width="67"><img src="imagens/painel/login_boneco3.jpg" width="67" height="228"></td>
      </tr>
    </form>
    </table>
    </td>
  </tr>
  <tr>
    <td height="15" bgcolor="#14285A"></td>
  </tr>
</table>
</body>
</html>