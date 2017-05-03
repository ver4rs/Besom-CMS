<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo htmlspecialchars($head['title']); ?></title>
	<meta name="keywords" content="<?php echo htmlspecialchars($head['keywords']); ?>"/>
	<meta name="description" content="<?php echo htmlspecialchars($head['description']); ?>"/>
	<link rel="canonical" href="<?php echo htmlspecialchars($system['system_url']); ?>" />
	<meta name="robots" content="index, follow" />

	<meta property="og:title" content="<?php echo htmlspecialchars($head['title']); ?>" />
	<meta property="og:description" content="<?php echo htmlspecialchars($head['description']); ?>" />
	<meta property="og:url" content="<?php echo htmlspecialchars($head['url']); ?>" />
	<meta property="og:image" content="<?php echo htmlspecialchars($head['image']); ?>" />
	<meta property="og:site_name" content="<?php echo htmlspecialchars($system['system_url']);  ?>" />

	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?php echo htmlspecialchars($head['title']); ?>" />
	<meta name="twitter:description" content="<?php echo htmlspecialchars($head['description']); ?>" />
	<meta name="twitter:image" content="<?php echo htmlspecialchars($head['image']); ?>" />
	
	<link rel="shortcut icon" href="<?php echo htmlspecialchars($head['image']); ?>"/>
	<link rel="icon" href="<?php echo htmlspecialchars($head['image']); ?>" type="image/x-icon"/>
	<link rel="icon" type="image/jpeg" href="<?php echo htmlspecialchars($head['image']); ?>" />

	<link rel="alternate" type="application/rss+xml" title="Besom.sk - RSS" href="#">
	
	<meta name="copyright" content="&copy; 2014" />
	<meta name="application-name" content="Besom CMS" />
	<meta name="version" content="1.0">
	<meta name="author" content="ver4rs | ver4rs@gmail.com"/>
	<meta name="email" content="ver4rs@gmail.com"/>
	<meta name="website" content="Besom.sk"/>

	
	<meta http-equiv="content-script-type" content="text/javascript">
	<!-- <link rel="stylesheet" type="text/css" href="<?php //echo URL_ADRESA; ?>include/styl/styl.css" />-->

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>font.css">
	<link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>base.css">
	<link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>skelet.css">
	<link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>reslide.css">
	<link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>layot.css">

	<script src="<?php echo TEMPLATE_URL; ?>jquery-1.8.3.min.js"></script>
	<script src="<?php echo TEMPLATE_URL; ?>jquery-ui.js"></script>
	<script src="<?php echo TEMPLATE_URL; ?>jquery.refineslide.min.js"></script>
	<script src="<?php echo TEMPLATE_URL; ?>script.js"></script>


	<!--   LIGHTBOX   -->
	<script src="<?php echo URL_ADRESA ?>lightbox/js/modernizr.custom.js"></script>
	<!-- <link rel="shortcut icon" href="<?php //echo URL_ADRESA ?>lightbox/img/demopage/favicon.ico"> -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Karla:400,700">
	<!-- <link rel="stylesheet" href="<?php //echo URL_ADRESA ?>lightbox/css/screen.css" media="screen"/> -->
	<link rel="stylesheet" href="<?php echo URL_ADRESA ?>lightbox/css/lightbox.css" media="screen"/>
	<script src="<?php echo URL_ADRESA ?>lightbox/js/jquery-1.10.2.min.js"></script>
	<script src="<?php echo URL_ADRESA ?>lightbox/js/lightbox-2.6.min.js"></script>
	<!--  KONIEC  LIGHTBOX   -->


	<!-- SLIDER  -->
    <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="slider/coin-slider.js"></script>
    <link rel="stylesheet" href="slider/coin-slider-styles.css" type="text/css" />  -->
	<!-- KONIEC SLIDER  -->


	<!-- VYSKOCENIE OKNA PO ULOZENI< UPRAVE,...  -->
	<script type="text/javascript">
	setTimeout(function(){
	     $('#odpoved').hide();
	},3000)

	</script>

</head>
<body>
