<?php include "site_mod_include.php";?>
<?php
// Coleta os dados
$sql_diferencial = "SELECT id_diferencial, titulo_diferencial, texto_diferencial, status FROM diferencial WHERE status = 'L' ORDER BY titulo_diferencial DESC LIMIT 6";
$query_diferencial = $pdo->query($sql_diferencial);
$resultado_diferencial = $query_diferencial->fetchAll( PDO::FETCH_ASSOC );

// Coleta os dados
$sql_novidades = "SELECT galerias.`data`, galerias.descricao AS resumo, galerias.nome, galerias_fotos.arquivo, galerias.id_galeria FROM galerias
					JOIN galerias_fotos ON galerias_fotos.id_galeria = galerias.id_galeria
					WHERE galerias_fotos.destaque = 'S' ORDER BY galerias.id_galeria DESC LIMIT 3";
$query_novidades = $pdo->query($sql_novidades);
$novidades = $query_novidades->fetchAll( PDO::FETCH_ASSOC );
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

			<div class="fullwidth-block image-block-topo" data-bg-image="dummy/section-img-servicos.jpg"></div>

			<!-- Módulo de reservas -->
			<div class="modulo-reservas">
				<?php include "site_mod_reservas.php";?>
			</div>
			<!-- Módulo de reservas -->

			<main class="main-content">
				<div class="fullwidth-block latest-projects-section">
					<div class="container">
						<h2 class="section-title">Conheça nossos Serviços</h2>
						<div class="row">
							<div class="col-sm-6 col-md-4">
								<div class="project">
									<figure class="project-thumbnail"><a href="servicos/5/fretamento-de-onibus-para-empresas"><img src="dummy/thumb-1.jpg" alt="fretamento-de-onibus-para-empresas"></a></figure>
									<h3 class="project-title"><a href="servicos/5/fretamento-de-onibus-para-empresas">FRETAMENTO CORPORATIVO</a></h3>
									<p><?php echo texto(5,'resumo', $pdo);?></p>
									<a href="servicos/5/fretamento-de-onibus-para-empresas" class="more-link"><img src="images/arrow.png" alt=""></a>
								</div>
							</div>
							<div class="col-sm-6 col-md-4">
								<div class="project">
									<figure class="project-thumbnail"><a href="servicos/6/fretamento-de-onibus-para-turismo"><img src="dummy/thumb-2.jpg" alt="fretamento-de-onibus-para-turismo"></a></figure>
									<h3 class="project-title"><a href="servicos/6/fretamento-de-onibus-para-turismo">FRETAMENTO TURÍSTICO</a></h3>
									<p><?php echo texto(6,'resumo', $pdo);?></p>
									<a href="servicos/6/fretamento-de-onibus-para-turismo" class="more-link"><img src="images/arrow.png" alt=""></a>
								</div>
							</div>
							<div class="col-sm-6 col-md-4">
								<div class="project">
									<figure class="project-thumbnail"><a href="servicos/7/fretamento-de-onibus-para-passeios-escolares"><img src="dummy/thumb-3.jpg" alt="fretamento-de-onibus-para-passeios-escolares"></a></figure>
									<h3 class="project-title"><a href="servicos/7/fretamento-de-onibus-para-passeios-escolares">VIAGENS ESCOLARES</a></h3>
									<p><?php echo texto(7,'resumo', $pdo);?></p>
									<a href="servicos/7/fretamento-de-onibus-para-passeios-escolares" class="more-link"><img src="images/arrow.png" alt=""></a>
								</div>
							</div>
						</div> <!-- .row -->
					</div> <!-- .container -->
				</div> <!-- .fullwidth-block.latest-projects-section -->

			</main> <!-- .main-content -->

			<footer class="site-footer">
				<?php include "site_mod_footer.php";?>
			</footer> <!-- .site-footer -->
		</div>

        <?php include "site_mod_js.php";?>
		
		
	</body>

</html>