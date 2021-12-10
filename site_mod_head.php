    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
	
	<title><?php echo $resultado_info["titulo_site"];?></title>

	<!-- Loading third party fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400|" rel="stylesheet" type="text/css">
	<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

	<base href="https://www.genesisbus.com.br" />

	<!-- Loading main css file -->
	<link rel="stylesheet" href="style.css">
	
	<!--[if lt IE 9]>
	<script src="js/ie-support/html5.js"></script>
	<script src="js/ie-support/respond.js"></script>
	<![endif]-->

	<script>
		var offset = $('#site-header').offset().top;
		var $meuMenu = $('#site-header');
		$(document).on('scroll', function () {
			if (offset <= $(window).scrollTop()) {
				$meuMenu.addClass('fixar');
			} else {
				$meuMenu.removeClass('fixar');
			}
		});
	</script>