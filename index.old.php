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
$poc_referer = $_SERVER["HTTP_REFERER"];
$poc_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$poc_useragent = $_SERVER["HTTP_USER_AGENT"];
$poc_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$pocitadlo_pristupov = $pocitadlo->pocitajpristupy($poc_ip, $poc_user_id, $poc_datum, $poc_host, $poc_referer, $poc_url, $poc_useragent, $poc_lang);
###########################################################



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

<div id="celok">

	<div class="telo">
	
<!--  START   -->

		<div id="vrch">
			<div class="vrsok">
				<div id="dop">
					<!-- <span>PROJEKTY: ITNEWS &nbsp; | &nbsp; MARKIZA &nbsp; | &nbsp; ADAM &nbsp; | &nbsp; EVA &nbsp; | &nbsp; SME</span>  -->
					<?php $menu->menuTop('vrch'); ?>

				</div>

				<div id="lang">
					<?php
					$nac_lang = $conn->query('SELECT * FROM be_jazyk' . DB_PREFIX . ' ORDER BY jazyk_id DESC ');
					if ($nac_lang->num_rows != 0) {
						# OK je
						while ($lang_t = $nac_lang->fetch_assoc()) {

							?><p><a href="<?php echo URL_ADRESA . htmlspecialchars('?lang=' . $lang_t['jazyk_short']); ?>"><img src="<?php echo htmlspecialchars(URL_ADRESA . $lang_t['jazyk_ikona']); ?>" alt="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>" title="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>"><span><?php echo htmlspecialchars($lang_t['jazyk_skratka']) ?></span></a></p><?php
						}
					}
					else {
						# NONE
					}
					?>
				</div>
			</div>

			<div id="hlava">

				<div class="logo">
					<a href="<?php echo URL_ADRESA; ?>"><img src="<?php echo URL_ADRESA . htmlspecialchars('upload/system/' . $system['system_img']); ?>" alt="" title=""></a>
				</div>
				
				<div class="social">
					<b><h3 style="margin-bottom: -10px; ">Kontakt:</h3></b> </br>
					<b>Tel.</b> <span  style="color: #0A6CAD;"><?php echo htmlspecialchars($system['system_admin_tel']); ?></span></br>
					<b>Email:</b> <a href="mailto:<?php echo htmlspecialchars_decode($system['system_admin_email']); ?>" style="color: #0A6CAD; "><?php echo htmlspecialchars_decode($system['system_admin_email']); ?></a></br>
					<b>Najdes ma na:</b></br>
					 <a target="_blank" href="<?php echo htmlspecialchars_decode($system['system_admin_facebook']); ?>" title=""><img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars('facebook.png'); ?>" style="height: 20px; vertical-align: middle;" alt="Facebook"></a>
					 <a target="_blank" href="<?php echo htmlspecialchars_decode($system['system_admin_twitter']); ?>" title=""><img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars('twitter.png'); ?>" style="height: 20px; vertical-align: middle;" alt="Twitter"></a>
					 <a href="#" title=""><img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars('rss.png'); ?>" style="height: 20px; vertical-align: middle;" alt="RSS"></a></br>

				</div>

			</div>

		</div>

		<div class="menu">
			<?php $menu->menuTop('hore'); ?>
		</div>



		<div id="content">

			<div class="main">
			<?php
				if ($system['system_slider_ano'] == '1') {	
			?>
				<div id="slider">
				<?php
					$slider->sliderzobraz($system['system_slider_pocet']); // pocet snimkov
				?>
				</div>
			<?php 
			  	}
			?>		

				<div id="cesta">
					<h1>
						<!-- <a href="#"><img src="<?php //echo URL_ADRESA . htmlspecialchars('images/home.png'); ?>" alt="Home"></a>  -->
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
					</h1>
				</div>

				<div id="page">
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
						$text->textgethladaj($_GET['q'], '?q=');
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

					?>
				</div>
			</div>

			<div class="sidebar">
				
				<?php

				# ZOBRAZENIE WIDGETOV
				$widget->widgetzobraz();

				?>

			</div>
 
		</div>

		<div id="foot">

			<div class="tabstlp">

                <div id="stlpec">
    # NEW MENU
<?php
/*require("besom/mailer/PHPMailerAutoload.php"); // voláme súbor

$mail = new PHPMailer(); //instancia PHPMaileru

$mail->From = "ver4rs@gmail.com"; //moja adresa
$mail->FromName = "Malak Laknfhh"; //moje meno

$mail->AddAddress("ver4rs@gmail.com"); // komu

$mail->WordWrap = 50;                                 // po 50 znaku slova rozdel slovo
$mail->IsHTML(true);
//$mail->Charset = "utf-8";

$mail->Subject = "Breza je strom zeleny!";
$mail->Body    = "Hlavný dôvod prečo priaznivci Apple výrobkou vyhľadavajú smartfóny je výborná možnosť prezerať knihy v iBook aplikácii. Vcelom systéme nájdete najme slovenské a české knihy. Potom stači prevracať";
//$mail->AltBody = "Používate chabého klienta, takže nevyhrávate ani len link na poriadneho!";

if(!$mail->Send())
{
   echo "Správa nebola zaslaná. <p>";
   echo "Nastala chyba: " . $mail->ErrorInfo;
   exit;
}

echo "Správa úspešne zaslaná";
*/


#######################
/*
require("besom/mailer/class.phpmailer.php"); // voláme súbor

$mail = new PHPMailer(); //instancia PHPMaileru

$mail->From = "besom@besom.sk"; //moja adresa
$mail->FromName = "Test testujem"; //moje meno

$mail->AddAddress("ver4rs@gmail.com"); //Vas mail komu

$mail->WordWrap = 50;                                 // po 50 znaku slova rozdel slovo
$mail->IsHTML(true);
//$mail->Charset = "utf-8";
$a = 'php koood';
$mail->Subject = "Breza je strom zeleny!";
$mail->Body    = htmlspecialchars_decode('<html><head></head><body><b>Hssslavný dôvod prečo</b> <br>' . $a . ' priaznivci Apple výrobkou vyhľadavajú smartfóny je výborná možnosť prezerať knihy v iBook aplikácii. Vcelom systéme nájdete najme slovenské a české knihy. Potom stači prevracať</body></html>');
//$mail->AltBody = "Používate chabého klienta, takže nevyhrávate ani len link na poriadneho!";

if(!$mail->Send())
{
   echo "Správa nebola zaslaná. <p>";
   echo "Nastala chyba: " . $mail->ErrorInfo;
   exit;
}

echo "Správa úspešne zaslaná";
*/

/*
$komu_email = 'ver4rs@gmail.com';  //komu
	$od_koho_email = 'martin5611@azet.sk';  //od koho


	$hlavicka_email = array();
	$hlavicka_email[] = 'MIME-Version: 1.0';
	$hlavicka_email[] = 'Content-type: text/html; charset="window-1250"';
	$hlavicka_email[] = 'Content-Transfer-Encoding: 7bit';
	$hlavicka_email[] = 'From: ' . $od_koho_email;

	$text_email = '<html><h2>Kukol stranku mobilai sponzor exohosting</h2> </br><p>Kukol stranku mobilai sponzor exo wehostinga je to a teraz ci odpovie !!!</p></html>';

	$predmet_email = 'Kukol stranku Mobilai sponzor exo';



	$odosli_email = mail($komu_email, $predmet_email, $text_email, join("\r\n", $hlavicka_email));

	if ($odosli_email) {
		echo 'Email bol odoslany ';
	}
	else {
		echo 'Nebol odoslany.';
	}*/
	
/*
	header('Location: /');
	exit();
*/


?>
				</div>

				<div id="stlpec">
					# NEW MENU
				</div>

				<div id="stlpec">
					# NEW MENU
				</div>
				
			</div>

			<p class="mefoot">
			<?php echo htmlspecialchars_decode($system['system_spod']);  ?>
			</p>
		</div>

		<div class="copyright">
			<p>Copyright c 2013 &nbsp; Powered by <a href="http://www.besom.sk">Besom CMS</a> &nbsp; Design by <a href="http://www.besom.sk">Besom CMS</a> &nbsp; Developed by <a href="http://www.besom.sk">Besom CMS</a></p>
		</div>


<?php










?>

	</div>
</div>

</body>
</html>