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
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Painel Administrativo - Datapix Tecnologia</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100">
				<form class="login100-form validate-form" name="form1" method="post" action="sistema_login.php?acao=entrar">
					<span class="login100-form-logo">
						<img src="images/logo_painel.png" alt="">
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Painel Administrativo
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Entre com seu login">
						<input class="input100" type="text" name="login" placeholder="Login">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Digite sua senha">
						<input class="input100" type="password" name="senha" placeholder="Senha">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="contact100-form-checkbox">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
						<label class="label-checkbox100" for="ckb1">
							Lembrar-me
						</label>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit" name="button" id="button">
							Login
						</button>
					</div>

					
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>