<?php include "site_mod_include.php";?>
<?php
$id_galeria = $_GET["id_galeria"];

// Coleta os dados
$sql_novidades = "SELECT galerias.`data`, galerias.descricao AS resumo, galerias.nome, galerias_fotos.arquivo, galerias.id_galeria FROM galerias
					JOIN galerias_fotos ON galerias_fotos.id_galeria = galerias.id_galeria
					WHERE galerias_fotos.destaque = 'S' AND galerias.id_galeria = '".$id_galeria."'";
$query_novidades = $pdo->query($sql_novidades);
$novidades = $query_novidades->fetch( PDO::FETCH_ASSOC );
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
						<h2 class="entry-title"><?php echo $novidades["nome"];?></h2>
										
						<figure class="align-left"><img src="conteudos/galeria/<?php echo $novidades["id_galeria"];?>/<?php echo $novidades["arquivo"];?>" alt="<?php echo $novidades["nome"];?>" width="400"></figure>
						<p><?php echo $novidades["resumo"];?></p>
										
						
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