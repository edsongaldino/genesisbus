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

			<div class="fullwidth-block image-block-topo" data-bg-image="dummy/section-img-contato.jpg"></div>

			<!-- Módulo de reservas -->
			<div class="modulo-reservas">
				<?php include "site_mod_reservas.php";?>
			</div>
			<!-- Módulo de reservas -->
			
			<main class="main-content">
				
				<div class="page">
					<div class="container">

						<?php if($_GET["envio"]):?>
						<?php if($_GET["envio"] == "sucesso"):?>
						<div class="alert alert-success sucesso">
							<strong>OK! :)</strong> <?php echo decodifica($_GET["mm"]);?>
						</div>
						<?php else:?>
						<div class="alert alert-danger erro">
							<strong>Ops! :(</strong> <?php echo decodifica($_GET["mm"]);?>
						</div>
						<?php endif;?>
						<?php endif;?>

						<div class="row">
							<div class="col-md-7">
								<div class="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3842.256024420658!2d-56.03035068458074!3d-15.631349123131368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x939daffcc09ae2c9%3A0x97e54c3d351c33d5!2sGenesis+Bus+-+Transporte+e+Turismo!5e0!3m2!1spt-BR!2sbr!4v1532978661186" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></div>

								<div class="contact-detail">
									<address>
										<div class="contact-icon">
											<img src="images/icon-marker.png" class="icon">
										</div>
										<p><strong>Genesis Bus - Transportes</strong> <br>R 15, SN, QUADRA 15, LOTE 33 - Altos do Coxipó, Cuiabá - MT, CEP: 78088-495</p>
									</address><br/><br/>
									<a href="call:6536678882" class="phone"><span class="contact-icon"><img src="images/icon-phone.png" class="icon"></span> (65) 3667-8882</a>
									<a href="mailto:contato@genesisbus.com.br" class="email"><span class="contact-icon"><img src="images/icon-envelope.png" class="icon"></span> contato@genesisbus.com.br</a>
								</div>
							</div>
							<div class="col-md-5">
								<div class="contact-form">
									<h2 class="section-title">Entre em contato</h2>
									<p>Envie-nos uma mensagem que responderemos prontamente.</p>

									<form name="contato" id="contato" action="envia_contato.php" method="POST">
										<input type="text" placeholder="Seu nome" name="nome" required>
										<input type="email" placeholder="Email" name="email" required>
										<input type="text" placeholder="Telefone" name="telefone" required>
										<select name="assunto" id="assunto" required>
											<option value="">Selecione o assunto</option>
											<option value="Orçamento">Orçamento</option>
											<option value="Sugestão">Sugestão</option>
											<option value="Elogio ou Reclamação">Elogio ou Reclamação</option>
										</select>
										<textarea placeholder="Mensagem" name="mensagem" required></textarea>
										<input type="hidden" name="acao" value="<?php echo codifica("envia-form-contato");?>">
										<input type="submit" value="Enviar Formulário" class="enviar">
									</form>
									
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