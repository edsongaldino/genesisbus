<?php include "site_mod_include.php";?>
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

			<!-- Módulo de reservas -->
			<div class="modulo-reservas">
				<?php include "site_mod_reservas.php";?>
			</div>
			<!-- Módulo de reservas -->
			
			<main class="main-content">
				
				<div class="page">
					<div class="container">
						<h2 class="entry-title"><?php echo texto(1,'subtitulo', $pdo);?></h2>
						
						<p class="depoimento"><?php echo texto(1,'resumo', $pdo);?></p>
						
						<figure class="align-left"><img src="dummy/single-image.jpg" alt="Single image"></figure>
						<p><?php echo texto(1,'texto', $pdo);?></p>
						

						<div class="row margin-60">
							<div class="col-md-4">
								<div class="feature-numbered">
									<div class="num">1</div>
									<h2 class="feature-title">Fronta completa</h2>
								</div>
							</div>
							<div class="col-md-4">
								<div class="feature-numbered">
									<div class="num">2</div>
									<h2 class="feature-title">Veículos Certificados</h2>
								</div>
							</div>
							<div class="col-md-4">
								<div class="feature-numbered">
									<div class="num">3</div>
									<h2 class="feature-title">Pontualidade</h2>
								</div>
							</div>
						</div>
						
					
						
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