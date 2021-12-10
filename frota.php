<?php include "site_mod_include.php";?>	
<?php
// Coleta os dados
$sql_produtos = "SELECT 
produto.nome_produto,
produto.id_produto,
produto.texto_produto,
produto_fotos.arquivo,
produto_fotos.destaque,
subtipo_produto.nome_subtipo_produto,
tipo_produto.nome_tipo_produto
FROM produto 
JOIN produto_fotos ON produto_fotos.id_produto = produto.id_produto
JOIN subtipo_produto ON produto.id_subtipo_produto = subtipo_produto.id_subtipo_produto
JOIN tipo_produto ON subtipo_produto.id_tipo_produto = tipo_produto.id_tipo_produto
WHERE produto.`status` = 'L' AND produto_fotos.destaque = 'S' GROUP BY produto.id_produto ";
$query_produtos = $pdo->query($sql_produtos);
$produtos = $query_produtos->fetchAll( PDO::FETCH_ASSOC );
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

			<!-- Módulo de reservas -->
			<div class="modulo-reservas">
				<?php include "site_mod_reservas.php";?>
			</div>
			<!-- Módulo de reservas -->

			<main class="main-content">
				
				<div class="page">
					<div class="container">
						<h2 class="entry-title">Conheça nossa frota</h2>
						<p>A Genesis está sempre renovando a sua frota, efetuamos um acompanhamento rigoroso de manutenção preventiva em nossas garagens e oficinas, garantindo tranquilidade em todos os deslocamentos realizados, garantimos a qualidade do serviço prestados, pois a nossa frota é própria e possuímos todos os certificados exigidos pelas autoridades.</p>

						<!--
						<div class="filter-links filterable-nav">
							<select class="mobile-filter">
								<option value="*">Show all</option>
								<option value=".skyscraper">skyscraper</option>
								<option value=".shopping-center">shopping-center</option>
								<option value=".apartment">apartment</option>
							</select>
							<a href="#" class="current wow fadeInRight" data-filter="*">Show all</a>
							<a href="#" class="wow fadeInRight" data-wow-delay=".2s" data-filter=".skyscraper">skyscraper</a>
							<a href="#" class="wow fadeInRight" data-wow-delay=".4s" data-filter=".shopping-center">shopping-center</a>
							<a href="#" class="wow fadeInRight" data-wow-delay=".6s" data-filter=".apartment">apartment</a>
						</div>
						-->

						<div class="filterable-items">

							<?php foreach($produtos AS $frota):?>
							<div class="project-item filterable-item shopping-center">
								<figure class="featured-image">
									<a href="fotos_frota.php?id_produto=<?php echo $frota["id_produto"];?>"><img src="conteudos/produto/<?php echo $frota["id_produto"];?>/<?php echo $frota["arquivo"];?>" alt="#"></a>
									<figcaption>
										<h2 class="project-title"><a href="fotos_frota.php?id_produto=<?php echo $frota["id_produto"];?>"><?php echo $frota["nome_produto"];?></a></h2>
										<p class="project-subtotle"><?php echo $frota["nome_tipo_produto"];?> - <?php echo $frota["nome_subtipo_produto"];?></p>
										<a href="fotos_frota.php?id_produto=<?php echo $frota["id_produto"];?>" class="more-link"><img src="images/fotoscarro.png" alt=""></a>
									</figcaption>
								</figure>
							</div>
							<?php endforeach;?>

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