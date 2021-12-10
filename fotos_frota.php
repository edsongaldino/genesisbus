<?php include "site_mod_include.php";?>	
<?php

$id_produto = protege($_GET["id_produto"]);
// Coleta os dados
$sql_produtos = "SELECT 
produto.nome_produto,
produto.id_produto,
produto.texto_produto,
subtipo_produto.nome_subtipo_produto,
tipo_produto.nome_tipo_produto
FROM produto 
JOIN subtipo_produto ON produto.id_subtipo_produto = subtipo_produto.id_subtipo_produto
JOIN tipo_produto ON subtipo_produto.id_tipo_produto = tipo_produto.id_tipo_produto
WHERE produto.`id_produto` = '".$id_produto."'";
$query_produtos = $pdo->query($sql_produtos);
$produto = $query_produtos->fetch( PDO::FETCH_ASSOC );


// Coleta os dados
$sql_fotos = "SELECT 
produto_fotos.id_produto_foto,
produto_fotos.arquivo
FROM produto_fotos
WHERE produto_fotos.`id_produto` = '".$id_produto."'";
$query_fotos = $pdo->query($sql_fotos);
$fotos_frota = $query_fotos->fetchAll( PDO::FETCH_ASSOC );

?>
<!DOCTYPE html>
<html lang="en">
	<head>
    <?php include "site_mod_head.php";?>
	<link href="ferramenta/lightGallery-master/dist/css/lightgallery.css" rel="stylesheet">
        <style type="text/css">
            .demo-gallery > ul {
              margin-bottom: 0;
			  list-style: none;
            }
            .demo-gallery > ul > li {
                float: left;
                margin-bottom: 60px;
                margin-right: 20px;
                width: 370px;
				height: 250px;
            }
            .demo-gallery > ul > li a {
              border: 3px solid #FFF;
              border-radius: 3px;
              display: block;
              overflow: hidden;
              position: relative;
              float: left;
            }
            .demo-gallery > ul > li a > img {
              -webkit-transition: -webkit-transform 0.15s ease 0s;
              -moz-transition: -moz-transform 0.15s ease 0s;
              -o-transition: -o-transform 0.15s ease 0s;
              transition: transform 0.15s ease 0s;
              -webkit-transform: scale3d(1, 1, 1);
              transform: scale3d(1, 1, 1);
              height: 250px;
              width: 100%;
			  margin-bottom:20px;
            }
            .demo-gallery > ul > li a:hover > img {
              -webkit-transform: scale3d(1.1, 1.1, 1.1);
              transform: scale3d(1.1, 1.1, 1.1);
            }
            .demo-gallery > ul > li a:hover .demo-gallery-poster > img {
              opacity: 1;
            }
            .demo-gallery > ul > li a .demo-gallery-poster {
              background-color: rgba(0, 0, 0, 0.1);
              bottom: 0;
              left: 0;
              position: absolute;
              right: 0;
              top: 0;
              -webkit-transition: background-color 0.15s ease 0s;
              -o-transition: background-color 0.15s ease 0s;
              transition: background-color 0.15s ease 0s;
            }
            .demo-gallery > ul > li a .demo-gallery-poster > img {
              left: 50%;
              margin-left: -10px;
              margin-top: -10px;
              opacity: 0;
              position: absolute;
              top: 50%;
              -webkit-transition: opacity 0.3s ease 0s;
              -o-transition: opacity 0.3s ease 0s;
              transition: opacity 0.3s ease 0s;
            }
            .demo-gallery > ul > li a:hover .demo-gallery-poster {
              background-color: rgba(0, 0, 0, 0.5);
            }
            .demo-gallery .justified-gallery > a > img {
              -webkit-transition: -webkit-transform 0.15s ease 0s;
              -moz-transition: -moz-transform 0.15s ease 0s;
              -o-transition: -o-transform 0.15s ease 0s;
              transition: transform 0.15s ease 0s;
              -webkit-transform: scale3d(1, 1, 1);
              transform: scale3d(1, 1, 1);
              height: 100%;
              width: 100%;
            }
            .demo-gallery .justified-gallery > a:hover > img {
              -webkit-transform: scale3d(1.1, 1.1, 1.1);
              transform: scale3d(1.1, 1.1, 1.1);
            }
            .demo-gallery .justified-gallery > a:hover .demo-gallery-poster > img {
              opacity: 1;
            }
            .demo-gallery .justified-gallery > a .demo-gallery-poster {
              background-color: rgba(0, 0, 0, 0.1);
              bottom: 0;
              left: 0;
              position: absolute;
              right: 0;
              top: 0;
              -webkit-transition: background-color 0.15s ease 0s;
              -o-transition: background-color 0.15s ease 0s;
              transition: background-color 0.15s ease 0s;
            }
            .demo-gallery .justified-gallery > a .demo-gallery-poster > img {
              left: 50%;
              margin-left: -10px;
              margin-top: -10px;
              opacity: 0;
              position: absolute;
              top: 50%;
              -webkit-transition: opacity 0.3s ease 0s;
              -o-transition: opacity 0.3s ease 0s;
              transition: opacity 0.3s ease 0s;
            }
            .demo-gallery .justified-gallery > a:hover .demo-gallery-poster {
              background-color: rgba(0, 0, 0, 0.5);
            }
            .demo-gallery .video .demo-gallery-poster img {
              height: 48px;
              margin-left: -24px;
              margin-top: -24px;
              opacity: 0.8;
              width: 48px;
            }
            .demo-gallery.dark > ul > li a {
              border: 3px solid #04070a;
            }
            .home .demo-gallery {
              padding-bottom: 80px;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	</head>
	<body>
		
		<div id="site-content">
			<div class="site-header" id="site-header">
            <?php include "site_mod_header.php";?>	
			</div> <!-- .site-header -->

			<main class="main-content">
				
				<div class="page">
					<div class="container">
						<h2 class="entry-title"><?php echo $produto["nome_produto"];?></h2>
						<p><?php echo $produto["texto_produto"];?></p>

						<div class="demo-gallery">
							<ul id="lightgallery" class="list-unstyled row">
								<?php foreach($fotos_frota AS $foto):?>
								<li class="col-xs-3 col-sm-4 col-md-4" data-src="conteudos/produto/<?php echo $id_produto;?>/<?php echo $foto["arquivo"];?>" data-sub-html="<h4><?php echo $produto["nome_produto"];?></h4>">
									<a href="">
										<img class="img-responsive" src="conteudos/produto/<?php echo $id_produto;?>/<?php echo $foto["arquivo"];?>">
									</a>
								</li>
								<?php endforeach;?>
							</ul>
						</div>
						<script type="text/javascript">
						$(document).ready(function(){
							$('#lightgallery').lightGallery();
						});
						</script>
						
						<script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js"></script>
						<script src="ferramenta/lightGallery-master/dist/js/lightgallery-all.min.js"></script>
						<script src="ferramenta/lightGallery-master/lib/jquery.mousewheel.min.js"></script>

						
					</div>
				</div> <!-- .page -->

			</main> <!-- .main-content -->

			<footer class="site-footer">
				<?php include "site_mod_footer.php";?>
			</footer> <!-- .site-footer -->
		</div>

        <?php //include "site_mod_js.php";?>
		
		
	</body>

</html>