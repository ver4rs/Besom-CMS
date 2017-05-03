<?php

# |-------------------------------- ------|
# |----------     BESOM CMS      ---------|
# |----------   AUTHOR vers4rs   ---------|
# |----------  LICENCE GNU/GNP   ---------|
# |----------    VERSION 1.0     ---------|
# |----------                    ---------|
# |---------------------------------------|



require 'config.php';

##########################################################
####                   POCITADLO                       ###
##########################################################
# pocitadlo stranku
$poc_ip = $_SERVER['REMOTE_ADDR'];
$poc_user_id = $_SESSION['user_id'];
$poc_datum = date('Y-m-d H:i:s');
$poc_host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
$poc_referer = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER['HTTP_REFERER'] : '';
$poc_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$poc_useragent = $_SERVER["HTTP_USER_AGENT"];
$poc_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$pocitadlo_pristupov = $pocitadlo->pocitajpristupy($poc_ip, $poc_user_id, $poc_datum, $poc_host, $poc_referer, $poc_url, $poc_useragent, $poc_lang);
###########################################################


#############################################################################
########                 ONLINE 									#########
#############################################################################
$poc_ip = $_SERVER['REMOTE_ADDR'];
$poc_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$poc_host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
$poc_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$poc_useragent = $_SERVER["HTTP_USER_AGENT"];
$onlineTeraz = $pocitadlo->pocitadloOnlineAlgoritmus('50', 's', $poc_ip, $poc_url, $poc_host, $poc_lang, $poc_useragent);
#############################################################################


###################################################
#####              NASTAVENIA                   ###
###################################################
# system stranky nastavenia,.... pre vsetko     ###
$system = $systems->systemstranky();             ###
###################################################

######################################################
# HEAD SETTING AUTOMATIC GENERATION
$head = $systems->systemHead();

include TEMPLATE_DESIGN . 'html_head.php';
######################################################


?>

	<div class="nav-header" style=" ">
		<div class="container">
			<div id="menu" class="eleven menus" style="">
			<?php $menu->menuTop('vrch'); ?>
			</div>

			<div class="one-third column alpha lang" style="float: right; padding-left: 10px; ">
			<?php
					$nac_lang = $conn->query('SELECT * FROM be_jazyk' . DB_PREFIX . ' ORDER BY jazyk_id ASC ');
					if ($nac_lang->num_rows != 0) {
						# OK je
						while ($lang_t = $nac_lang->fetch_assoc()) {

							?><p><a href="<?php echo URL_ADRESA . htmlspecialchars('?lang=' . $lang_t['jazyk_short']); ?>"><img src="<?php echo htmlspecialchars(URL_ADRESA . $lang_t['jazyk_ikona']); ?>" alt="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>" title="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>" width="20" height="20" class="thumbnail"><!-- <span><?php //echo htmlspecialchars($lang_t['jazyk_skratka']) ?></span>--></a></p><?php
						}
					}
					else {
						# NONE
					}
			?>
			</div>
		</div>	
	</div>


<div class="wrap-header"><!--  head - logo and social icon  -->
	<div class="container">
		<div class="eleven columns omega main-header header-bar left-only">
			<a href="<?php echo URL_ADRESA; ?>" class="alogo"><img src="<?php echo URL_ADRESA . htmlspecialchars('upload/system/' . $system['system_img']); ?>" alt="" title="" id="imlogo"></a>
		</div>
		
		<div class="one-third column alpha">
			<div class="social clearfix">
				<ul class="social-links">
					<?php
					$nac_soc = $conn->query('SELECT * FROM be_social' . DB_PREFIX . '
												WHERE social_typ = 1 AND social_stav = 1 
												 ORDER BY social_zorad ASC ');
					if ($nac_soc->num_rows != 0) {
						# OK je
						while ($lang_t = $nac_soc->fetch_assoc()) {

							$t = array('Facebook' => 'icon-facebook',
										'Twitter' => 'icon-twitter',
										'Google +' => 'icon-google-plus',
										'Linkedin' => 'icon-linkedin',
										'RSS' => 'icon-rss');
							if (empty($t[$lang_t['social_nazov']])) {
								?><li><a href="<?php echo htmlspecialchars($lang_t['social_url']); ?>"><!-- <img src="<?php //echo htmlspecialchars(URL_ADRESA . IMAGE_SOCIAL . $lang_t['social_img']); ?>" title="<?php //echo htmlspecialchars($lang_t['social_nazov']); ?>" alt="<?php //echo htmlspecialchars($lang_t['social_nazov']); ?>" style="width: 30px; margin-top: 20px;">--><i class="icon-none"></i></a></li><?php
							}
							else {
								?><li><a href="<?php echo htmlspecialchars($lang_t['social_url']); ?>"><i class="<?php echo $t[$lang_t['social_nazov']]; ?>"></i></a></li><?php
							}
							
							/*?><p><a href="<?php echo URL_ADRESA . htmlspecialchars('?lang=' . $lang_t['jazyk_short']); ?>"><img src="<?php echo htmlspecialchars(URL_ADRESA . $lang_t['jazyk_ikona']); ?>" alt="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>" title="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>" width="20" height="20" class="thumbnail"><!-- <span><?php //echo htmlspecialchars($lang_t['jazyk_skratka']) ?></span>--></a></p><?php*/
						}
					}
					else {
						# NONE
					}
					?>
				</ul>
				<?php /*<b><h3 style="margin-bottom: -10px; ">Kontakt:</h3></b> </br>
					<b>Tel.</b> <span  style="color: #0A6CAD;"><?php echo htmlspecialchars($system['system_admin_tel']); ?></span></br>
					<b>Email:</b> <a href="mailto:<?php echo htmlspecialchars_decode($system['system_admin_email']); ?>" style="color: #0A6CAD; "><?php echo htmlspecialchars_decode($system['system_admin_email']); ?></a></br>
					<b>Najdes ma na:</b></br>
					 <a target="_blank" href="<?php echo htmlspecialchars_decode($system['system_admin_facebook']); ?>" title=""><img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars('facebook.png'); ?>" style="height: 20px; vertical-align: middle;" alt="Facebook"></a>
					 <a target="_blank" href="<?php echo htmlspecialchars_decode($system['system_admin_twitter']); ?>" title=""><img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars('twitter.png'); ?>" style="height: 20px; vertical-align: middle;" alt="Twitter"></a>
					 <a href="#" title=""><img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars('rss.png'); ?>" style="height: 20px; vertical-align: middle;" alt="RSS"></a></br>  */ ?>
			</div>
		</div>
		
	</div>
</div>

<div class="nav-container clearfix"><!--  MENU   -->
	<div class="container">
	<nav class="primary eleven columns omega">
		<a href="" id="pull"></a> 	
			<?php $menu->menuTop('hore'); ?>
	</nav>
	</div>
</div>

<?php
if ($system['system_slider_ano'] == '1' AND ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index1.php') ) {	?>
<div class="wrap-slides"><!--  SLIDER  -->
	<div class="container">
		<div class="slides eleven columns omega slider-bar">
			<div class="rs-wrap" style="width: 680px;">
				<div class="rs-slide-bg" style="-webkit-transform: translateZ(0); -webkit-perspective: 1000px;">
					<?php $slider->sliderzobraz($system['system_slider_pocet']); // pocet snimkov  ?>
			
				</div>
			</div>
		</div><!-- end slides -->
			
			
		<div class="one-third column alpha">
			<div class="about-intro clearfix">
				<h2><?php echo $system['system_nadpis']; ?></h2>
				<p><?php echo $system['system_description']; ?></p>
				<a class="button"><?php echo $lg['buttviac']; ?></a>
			</div>	
		</div>
	</div>
</div>
<?php } else { ?>
<div class="sub-header">
	<div class="container">
		<div class="eleven columns omega search-bar left-only">
			<div id="search">
					<button name="search" type="button" value="val"></button>
					<input type="text" name="q" value="<?php if (isset($_GET['q'])) { echo htmlspecialchars($_GET['q']); } ?>">
			</div>
		</div>
		<div class="one-third column alpha">
			<div class="breadcrumbs">
				<h3>
					<?php
					$ces = $systems->systemCesta(URL_ADRESA);
					if ($ces[2]['url'] != '' AND $ces[2]['nazov'] != '') {
						
						if (strrpos($ces[2]['nazov'], '→') != '0') {
							$ces[2]['nazov'] = substr($ces[2]['nazov'], 0,-(strlen($ces[2]['nazov']) - strrpos($ces[2]['nazov'], '→') ));
						}
						else {
							$ces[2]['nazov'] = $ces[2]['nazov'];
						}
						echo $ces[2]['nazov'];
					}
					elseif ($ces[1]['url'] != '' AND $ces[1]['nazov'] != '') {
						if (strrpos($ces[1]['nazov'], '→') != '0') {
							$ces[1]['nazov'] = substr($ces[1]['nazov'], 0,-(strlen($ces[1]['nazov']) - strrpos($ces[1]['nazov'], '→') ));
						}
						else {
							$ces[1]['nazov'] = $ces[1]['nazov'];
						}
						echo $ces[1]['nazov'];
					}
					else {
						?><a href="<?php echo URL_ADRESA; ?>"><?php echo $lg['HOME']; // lang DOMOV ?></a><?php
					}
					?>
				</h3>
			</div>
		</div>
		
	</div>
</div>
<?php } ?>

<?php /*
<div class="container" style=" ">
	<div class="eleven columns omega fullwidth-bar left-only"  style="">
		<div class="main-content clearfix" style="">
			<div class="heading" style="">
				<h5>
					<a href="<?php echo URL_ADRESA; ?>">Home</a>
					<?php

					$cesta = $systems->systemCesta(URL_ADRESA);

					if ($cesta[1]['url'] != '' AND $cesta[1]['nazov'] != '') {
						?><span> &nbsp; → &nbsp; </span><a href="<?php echo $cesta[1]['url']; ?>"><?php echo $cesta[1]['nazov']; ?></a><?php
					}
					if ($cesta[2]['url'] != '' AND $cesta[2]['nazov'] != '') {
						?><span> &nbsp; → &nbsp; </span><a href="<?php echo $cesta[2]['url']; ?>"><?php echo $cesta[2]['nazov']; ?></a><?php
					}
					?>
				</h5>
			</div>
		</div>
	</div>

</div>
*/?>	

<?php

#SIRKA
$sir = (isset($_GET['menu'])) ? $_GET['menu'] : '';
if ($sir == '' AND ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php') ) {
	$sir = 'uvod';  //len pre toto>>>>>> domov   --->>>   je to start a nictam z db start
}
$sirka = $text->sirka($sir);
//echo $sirka;

#FULL
if ($sirka == 'full') {
	#MAM ->> v kode
	?>
<div class="wrap-main-content">
	<div class="container fullwidth">
		<div class="sixteen columns">
	<?php
}
elseif ($sirka == 'half') {
	?>
<div class="container">
	<div class="eleven columns omega fullwidth-bar left-only">
		<div class="main-content clearfix">

			<div class="heading" style="">
				<h5>
					<a href="<?php echo URL_ADRESA; ?>">Home</a>
					<?php

					$cesta = $systems->systemCesta(URL_ADRESA);

					if ($cesta[1]['url'] != '' AND $cesta[1]['nazov'] != '') {
						?><span> &nbsp; → &nbsp; </span><a href="<?php echo $cesta[1]['url']; ?>"><?php echo $cesta[1]['nazov']; ?></a><?php
					}
					if ($cesta[2]['url'] != '' AND $cesta[2]['nazov'] != '') {
						?><span> &nbsp; → &nbsp; </span><a href="<?php echo $cesta[2]['url']; ?>"><?php echo $cesta[2]['nazov']; ?></a><?php
					}
					?>
				</h5>
			</div>
	<?php
}
elseif ($sirka == 'left-') {
	# 
}
elseif ($sirka == 'right-') {
	# 
}
else {
?>
<div class="container">
	<div class="eleven columns omega fullwidth-bar left-only">
		<div class="main-content clearfix">

			<div class="heading" style="">
				<h5>
					<a href="<?php echo URL_ADRESA; ?>">Home</a>
					<?php

					$cesta = $systems->systemCesta(URL_ADRESA);

					if ($cesta[1]['url'] != '' AND $cesta[1]['nazov'] != '') {
						?><span> &nbsp; → &nbsp; </span><a href="<?php echo $cesta[1]['url']; ?>"><?php echo $cesta[1]['nazov']; ?></a><?php
					}
					if ($cesta[2]['url'] != '' AND $cesta[2]['nazov'] != '') {
						?><span> &nbsp; → &nbsp; </span><a href="<?php echo $cesta[2]['url']; ?>"><?php echo $cesta[2]['nazov']; ?></a><?php
					}
					?>
				</h5>
			</div>
	<?php
}
?>




	
					<?php

					if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php') {
						# START  ===  DOMOV
						$text->textzobraz('', 'hore', '?menu='); // url adresa, pozicia menu
					}
					elseif (isset($_GET['menu'])) {
						# MENU
						$text->textzobraz($_GET['menu'], 'hore', '?menu='); // url adresa, pozicia menu
					}
					elseif (isset($_GET['q'])) {
						# HLADAJ
						$text->textgethladaj(htmlspecialchars($_GET['q']), '?q=');
					}
					elseif (isset($_GET['article'])) {
						# CLANOK
						$text->getarticle($_GET['article']);
					}
					elseif (isset($_GET['archiv'])) {
						# ARCHIV
						$text->getarchiv($_GET['archiv']); // get, typ=clanok, content, url,     pozicia= hore,...
					}
					elseif (isset($_GET['redirect'])) {
						$text->redirect($_GET['redirect']);
					}
					elseif (isset($_GET['status']) AND isset($_GET['hash'])) {
						$text->status($_GET['status'], $_GET['hash']);
					}
					else {
						# DOMOV
						//$text->textzobrazstart($getmenu);
						//$text->textzobraz('', 'hore'); // url adresa, pozicia menu

					}


####################################################
# ZOBRAZENIE AKO???
#####################################################
#FULL
if ($sirka == 'full') {
	#MAM
	?>
		</div>	
	
	</div>
</div>
	<?php
}
elseif ($sirka == 'half') {
	?>
		</div>
	</div>

	<aside class="one-third column alpha">
	<?php
	# ZOBRAZENIE WIDGETOV
	$widget->widgetzobraz();
	?>
	</aside>
</div>	
	<?php
}
elseif ($sirka == 'left-') {
	# 
}
elseif ($sirka == 'right-') {
	# 
}
else {
?>
		</div>
	</div>

	<aside class="one-third column alpha">
	<?php
	# ZOBRAZENIE WIDGETOV
	$widget->widgetzobraz();
	?>
	</aside>
</div>
<?php	
}
?>



<?php
if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php') {
?>
<div class="wrap-main-content">
	<div class="container fullwidth">
		<section class="services clearfix">
				<div class="heading"><h3>Vyberte si to, čo potrebujete</h3></div>

				<div class="one-third column">
					<div class="icon-heading"><i class="icon-cog"></i><h5>Systémy</h5></div>
					<div class="info">
						<p>Vytvorené CMS systémy.</br>
						<a style="font-weight: bold; color: crimson;" href="<?php echo URL_ADRESA . '?menu=besom-cms'; ?>" title="viac">Besom CMS</a></br>
						<a style="font-weight: bold; color: crimson;" href="<?php echo URL_ADRESA . '?menu=besom-gallery'; ?>" title="viac">Besom gallery</a></br>
						<a style="font-weight: bold; color: crimson;" href="<?php echo URL_ADRESA . '?menu=besom-blog'; ?>" title="viac">Besom glog</a></p>
						<a class="read-more" href="<?php echo URL_ADRESA . '?menu=systemy'; ?>"><i class="icon-plus"></i> Čítaj viac</a>
					</div>				
				</div>
				<div class="one-third column">
					<div class="icon-heading"><i class="icon-desktop"></i><h5>Služby</h5></div>
					<div class="info">
						<p>Pre vlastné návrhy, predlohy, ponúkam tieto služby.</br>
						<a style="font-weight: bold; color: crimson;" href="<?php echo URL_ADRESA . '?menu=tvorba-systemu-na-mieru'; ?>" title="viac">Tvorba systému na mieru</a></br>
						<a style="font-weight: bold; color: crimson;" href="<?php echo URL_ADRESA . '?menu=nakodovanie-diyajnu'; ?>" title="viac">Nákodovanie dizajnu</a></br>
						<a style="font-weight: bold; color: crimson;" href="<?php echo URL_ADRESA . '?menu=administracia-systemu'; ?>" title="viac">Administrácia systému</a></br>
						<a style="font-weight: bold; color: crimson;" href="<?php echo URL_ADRESA . '?menu=skripty'; ?>" title="viac">Skripty</a></br>
						</p>
						<a class="read-more" href="<?php echo URL_ADRESA . '?menu=sluzby'; ?>"><i class="icon-plus"></i> Čítaj viac</a>
					</div>				
				</div>
				<div class="one-third column">
					<div class="icon-heading"><i class="icon-print"></i> <h5>Grafika</h5></div>
					<div class="info">
						<p>Tvorba a návrh loga, nálepiek a letákov.
						</p>
						<a class="read-more" href="<?php echo URL_ADRESA . '?menu=grafika'; ?>"><i class="icon-plus"></i> Čítaj viac</a>
					</div>					
				</div>
		</section><!-- end services -->

		<section class="projects clearfix">
				<div class="heading"><h3>Projekty</h3></div>
				<div class="one-third column">
					<h5>Heading Title Here</h5>
					<p>Vestibulum in sem ac tellus facilisis commodo blandit quis justo. Proin non dui neque, eget auctor velit. Etiam ullamcorper dolor neque, a fringilla lacus. </p>
					<a class="button">View Our Portfolio</a>	
									
				</div>
				<div class="one-third column">
					<img src="images/assets/preview/460x300-printdesign-2.jpg" class="scale-with-grid" alt="">
					<h5 class="title"><a href="#">Project Title Goes Here</a></h5>
					<a href="#" class="button round category">Print Design</a>						
				</div>
				<div class="one-third column">
					<img src="images/assets/preview/460x300-printdesign-1.jpg" class="scale-with-grid" alt="">
					<h5 class="title"><a href="#">Project Title Goes Here</a></h5>
					<a href="#" class="button round category">Print Design</a>					
				</div>
		</section>

<?php /*	
		<section class="news clearfix">
				<div class="heading"><h3>Blog</h3></div>

				<div class="one-third column">
					<h5 class="title"><a href="http://themes.burnstudioph.com/demo/arvie/index.html#">Some Awesome Long Title Goes in This Line</a></h5>
					<div class="meta"><span class="author">by <a href="http://themes.burnstudioph.com/demo/arvie/index.html#">Admin</a></span>  <span class="noComments"><a href="http://themes.burnstudioph.com/demo/arvie/index.html#">0 Comments</a></span></div>
					<p>Sed in nisi in nunc suscipit euismod. Donec vitae nisi ut est pellentesque pharetra a et tortor. </p>		
				</div>
				<div class="one-third column">
					<h5 class="title"><a href="http://themes.burnstudioph.com/demo/arvie/index.html#">Some Awesome Long Title Goes in This Line</a></h5>
					<div class="meta"><span class="author">by <a href="http://themes.burnstudioph.com/demo/arvie/index.html#">Admin</a></span>  <span class="noComments"><a href="http://themes.burnstudioph.com/demo/arvie/index.html#">0 Comments</a></span></div>
					<p>Sed in nisi in nunc suscipit euismod. Donec vitae nisi ut est pellentesque pharetra a et tortor. </p>		
				</div>
				<div class="one-third column">
					<h5 class="title"><a href="http://themes.burnstudioph.com/demo/arvie/index.html#">Some Awesome Long Title Goes in This Line</a></h5>
					<div class="meta"><span class="author">by <a href="http://themes.burnstudioph.com/demo/arvie/index.html#">Admin</a></span>  <span class="noComments"><a href="http://themes.burnstudioph.com/demo/arvie/index.html#">0 Comments</a></span></div>
					<p>Sed in nisi in nunc suscipit euismod. Donec vitae nisi ut est pellentesque pharetra a et tortor. </p>			
				</div>
		</section><!-- end news -->
		
		<div class="info-widget clearfix">
			<div>
				<h5>information box</h5>
				<p>Sed in nisi in nunc suscipit euismod. Donec vitae nisi ut est pellentesque pharetra a et tortor Sed in nisi in nunc suscipit euismod donec vitae.</p>
			</div>
			<a href="http://themes.burnstudioph.com/demo/arvie/index.html#" class="button">Purchase Theme</a>
		</div>
*/ ?>
</div><!-- end container -->
</div><!-- edn wrap-main-content -->
<?php
}
?>




<div class="wrap-footer">
<footer class="container">

	<div class="eleven columns omega footer-bar left-only">
		
		<div class="widgets clearfix">

			<div class="one-third column alpha">
				<div class="widget-bound">
					<div class="heading-dark"><h5><?php echo strtoupper($lg['KONTAKT']); // lang kontakt ?></h5></div>


					<p>
					<?php echo $lg['nick']; // lang nick ?> <?php echo htmlspecialchars($system['system_admin_meno'] . ' | besom | Horacio'); ?></br>
					<?php echo $lg['meno']; // lang meno ?> <?php echo htmlspecialchars($system['system_admin_firstmeno'] . ' ' . $system['system_admin_lastmeno']); ?></br>
					<?php echo $lg['tel']; // lang tel ?> &nbsp; <?php echo htmlspecialchars($system['system_admin_tel']); ?> </br>
					
					<?php echo $lg['email']; // lang email ?> <a href="mailto:<?php echo htmlspecialchars_decode($system['system_admin_email']); ?>"><?php echo htmlspecialchars_decode($system['system_admin_email']); ?></a> <small><?php echo $lg['emailCompany']; // email popis company ?></small></br>
					<?php echo $lg['email']; // lang email ?> <a href="mailto:ver4rs@gmail.com">ver4rs@gmail.com</a> <small><?php echo $lg['emailPersonal']; //lang email personal ?></small>

					<ul class="social-links-dark">
						<li><a target="_blank" href="<?php echo htmlspecialchars_decode($system['system_admin_facebook']); ?>" title=""><i class="icon-facebook"></i></a></li>
						<li><a target="_blank" href="<?php echo htmlspecialchars_decode($system['system_admin_twitter']); ?>" title=""><i class="icon-twitter"></i></a></li>
					</ul>
					</p>
				</div><!-- end widget-bound -->
			</div>
			
			<div class="one-third column">
				<div class="widget-bound recent-post-widget">
					<div class="heading-dark"><h5><?php echo strtoupper($lg['ENGINEER']); // lang engneer ?></h5></div>
						<p style="text-align: justify; ">Copyright c 2013 &nbsp; Powered by <a href="http://www.besom.sk">Besom CMS</a> &nbsp; Design by <a href="http://www.besom.sk">Besom CMS</a> &nbsp; Developed by <a href="http://www.besom.sk">Besom CMS</a> |
						Enginer <a href="mailto:ver4rs@gmail.com">ver4rs@gmail.com</a> or <a href="mailto:<?php echo htmlspecialchars_decode($system['system_admin_email']); ?>"><?php echo htmlspecialchars_decode($system['system_admin_email']); ?></a></p>
				</div>

				<div class="heading-dark"><h5><?php echo strtoupper($lg['NAJDES_NAS']); // lang najdes nas ?></h5></div>
				<ul class="social-links-dark">
				<?php
					$nac_soc = $conn->query('SELECT * FROM be_social' . DB_PREFIX . '
												WHERE social_typ = 1 AND social_stav = 1 
												 ORDER BY social_zorad ASC ');
					if ($nac_soc->num_rows != 0) {
						# OK je
						while ($lang_t = $nac_soc->fetch_assoc()) {

							$t = array('Facebook' => 'icon-facebook',
										'Twitter' => 'icon-twitter',
										'Google +' => 'icon-google-plus',
										'Linkedin' => 'icon-linkedin',
										'RSS' => 'icon-rss');
							if (empty($t[$lang_t['social_nazov']])) {
								?><li><a href="<?php echo htmlspecialchars($lang_t['social_url']); ?>"><!-- <img src="<?php //echo htmlspecialchars(URL_ADRESA . IMAGE_SOCIAL . $lang_t['social_img']); ?>" title="<?php //echo htmlspecialchars($lang_t['social_nazov']); ?>" alt="<?php //echo htmlspecialchars($lang_t['social_nazov']); ?>" style="width: 30px; margin-top: 20px;">--><i class="icon-none"></i></a></li><?php
							}
							else {
								?><li><a href="<?php echo htmlspecialchars($lang_t['social_url']); ?>"><i class="<?php echo $t[$lang_t['social_nazov']]; ?>"></i></a></li><?php
							}
							
						}
					}
					else {
						# NONE
					}
					?>
				</ul>
			</div>
		
		</div>
	</div>
	
	<div class="one-third column alpha">
		<div class="copyright clearfix">

			<div class="counter" style="margin: 0px; padding: 0px;">
				<span><?php echo $lg['totalvisit']; // lang total visitors ?></span>
				<h1><?php echo $pocitadlo->pocitadloCelkovoPocet(); // celkovo len pocet nic a ip je 2 ?></h1>
			</div>

			<small style="text-align: justify; ">
				<?php echo htmlspecialchars_decode($system['system_spod']);  ?>
				 | Online <?php echo $pocitadlo->pocitadloOnlineStav(); // online stav prihlasenych ?>
			</small>
			
		
		</div>
	</div>

</footer>

</div>


</body>
</html>