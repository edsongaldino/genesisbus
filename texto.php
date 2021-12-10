<?php include "site_mod_include.php";?>
<?php
	$id_texto = protege($_GET["id_texto"]);

	// Coleta os dados
	$sql_texto = "SELECT id_texto, titulo, subtitulo, resumo, texto, arquivo FROM textos WHERE id_texto = '".$id_texto."'";
	$query_texto = $pdo->query($sql_texto);
	$texto = $query_texto->fetch( PDO::FETCH_ASSOC );
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    <?php include "site_mod_head.php";?>
	</head>
	<body>
		
		<div id="site-content">
			<div class="site-header" id="site-header">
            <?php include "site_mod_header.php";?>	
			</div> <!-- .site-header -->

			<main class="main-content">
				
				<div class="page">
					<div class="container">
						<h2 class="entry-title"><?php echo $texto["titulo"];?></h2>
						
						<p class="depoimento"><?php echo $texto["resumo"];?></p>
						
						<?php if($texto["arquivo"]):?><figure class="align-left"><img src="conteudos/textos/<?php echo $texto["arquivo"];?>" alt="<?php echo $texto["titulo"];?>" width="350"></figure><?php endif;?>
						<p><?php echo $texto["texto"];?></p>			
					
						
					</div>
				</div> <!-- .page -->

			</main> <!-- .main-content -->

			<footer class="site-footer">
				<?php include "site_mod_footer.php";?>
			</footer> <!-- .site-footer -->
		</div>

        <?php include "site_mod_js.php";?>
		
		
	</body>

</html>