<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

include_once('mailer/class.phpmailer.php');

class email {

	private $db, $prefix;
	public $lang, $lg, $lang_short;

	protected static $mailer=NULL;

    protected static function getInstance() {

        if (self::$mailer === NULL) {
            
            self::$mailer = new PHPMailer();
        }
        return self::$mailer;
    }

	public function __construct ($conn, $prefix, $lang, $lg, $lang_short) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
		$this->lg = $lg;
		$this->flag = $lang_short;

		if ($lg[$this->flag]['test'] == '') {
			$this->flag = 'sk';
		}

		//echo $lg[$this->flag]['test'];
	}

	public function nadpis () {
		echo $this->lg[$this->flag]['nadpisEmail'];
	}


	public function emailTab () {

		# GET STRANA
		if (isset($_GET['page']) AND $_GET['page'] != '0') {
				$page = $_GET['page'];
		}
		else {
			$page =1;
		}

		#LIMIT STRANU
		if (isset($_GET['limit']) AND $_GET['limit'] != FALSE AND $_GET['limit'] != '') {
			$limit = $_GET['limit'];
		}
		else {
			$limit = 50;
		}
		
		$range = 10;

		$start = ($page - 1)* $limit;

				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->nadpis()); ?></h3>
					</div>
					<div class="panel-body">

						<?php
						if (isset($_POST['submit']) AND isset($_POST['search'])) {

							header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&search=' . $_POST['search']);
							exit();
						}
						?>

						<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="form-horizontal" role="form">
							
							<div class="form-group">
							    <label for="inputEmail3" class="col-sm-2 control-label">Hladaj</label>
							    <div class="col-sm-4">
									<input type="text" class="form-control" id="inputText3" value="<?php if (isset($_GET['search'])) { echo $_GET['search']; } ?>" placeholder="Hladany vyraz" name="search">
							    </div>
								<button type="submit" name="submit" value="Hladaj" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-warning-sign"></i>&nbsp;Skusit stastie</button>

							</div>
						</form>

						<?php
							if (isset($_POST['submit']) AND isset($_POST['limit']) ) {

								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&limit=' . $_POST['limit']);
								exit();
							}
						?>
						<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="form-horizontal" role="form">
							<div class="form-group">
							<div class="col-md-5 col-md-offset-1">
								<label for="sur" class="text-default">Pocet zaznamou na stranu</label>
								<input type="number" name="limit" min="10" max="150" value="<?php if (isset($_GET['limit']) AND $_GET['limit'] != '') { echo $_GET['limit']; } else { echo '50'; } ?>" >

								<button type="submit" class="btn btn-info btn-sm" name="submit" value="Skusit"><i class="glyphicon glyphicon-warning-sign"></i>&nbsp;Skusit</button>
							</div>
							</div>
						</form>
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new'; ?>"><span class="text-default">Pridat email</span>&nbsp;<i class="glyphicon glyphicon-plus"></i></a>

					</div>
				<?php

		
		$quer = 'SELECT SQL_CALC_FOUND_ROWS  *  
										FROM be_email' . $this->prefix . ' ';

		if (isset($_GET['search']) AND $_GET['search'] != FALSE AND $_GET['search'] != '') {
			
			#NAZOV
			$quer .= ' WHERE email_predmet LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR email_text LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR email_komu LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR email_stav LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR newsletter_datum LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

		}

		#FILTERS
		// VIEWS
		if (isset($_GET['subject'])AND $_GET['subject'] != FALSE AND ($_GET['subject'] == 'ASC' OR $_GET['subject'] == 'DESC')) {
			$quer .= ' ORDER BY email_predmet ' . $_GET['subject'] . ' ';
		}
		elseif (isset($_GET['text'])AND $_GET['text'] != FALSE AND ($_GET['text'] == 'ASC' OR $_GET['text'] == 'DESC')) {
			$quer .= ' ORDER BY email_text ' . $_GET['text'] . ' ';
		}
		elseif (isset($_GET['komu'])AND $_GET['komu'] != FALSE AND ($_GET['komu'] == 'ASC' OR $_GET['komu'] == 'DESC')) {
			$quer .= ' ORDER BY email_komu ' . $_GET['komu'] . ' ';
		}
		elseif (isset($_GET['stav'])AND $_GET['stav'] != FALSE AND ($_GET['stav'] == 'ASC' OR $_GET['stav'] == 'DESC')) {
			$quer .= ' ORDER BY email_stav ' . $_GET['stav'] . ' ';
		}
		elseif (isset($_GET['date'])AND $_GET['date'] != FALSE AND ($_GET['date'] == 'ASC' OR $_GET['date'] == 'DESC')) {
			$quer .= ' ORDER BY email_datum ' . $_GET['date'] . ' ';
		}
		else {
			$quer .= ' ORDER BY email_datum DESC  ';
		}

		$quer .= ' LIMIT ' . $start . ', ' . $limit . ' ';

		$query = $this->db->query($quer);
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$por = $por + $start;

			$publikA = array('1' => 'Poslany',
							 '0' => 'V editacii',
							 '2' => 'Neaktivny');
			$colorA = array('1' => 'success',
							 '0' => 'danger',
							 '2' => 'warning');

			$ikonA = array('1' => 'glyphicon glyphicon-ok-circle',
							'0' => 'glyphicon glyphicon-remove-circle',
							'2' => 'glyphicon glyphicon-warning-sign');

			$colA = array('1' => 'green',
							'0' => 'red',
							'2' => '#c4be16');
			$licA = array('1' => 'Ano',
						 '0' => 'Nie');
			?>
			<table class="table table-hover">
				<tr>
					<th>#</th>
					<th>Datum 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Predmet 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&subject=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&subject=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Text 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&text=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&text=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Komu 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&komu=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&komu=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Stav 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th><i class="glyphicon glyphicon-star"> &nbsp; </i></th>
				</tr>
			<?php
			while ($ema = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php echo htmlspecialchars($colorA[$ema['email_stav']]); ?>">
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php echo htmlspecialchars($ema['email_datum']); ?></td>
					<td><?php
						//echo htmlspecialchars($ema['email_predmet']); 
						if (strlen(htmlspecialchars($ema['email_predmet'])) > 30) {
							$premet = substr(htmlspecialchars($ema['email_predmet']), 0,27) . '...';
						}
						else {
							$premet = htmlspecialchars($ema['email_predmet']);
						}
						echo $premet;
						
						?>
					</td>
					<td><?php
						if (strlen(htmlspecialchars($ema['email_text'])) > 60) {
							$text = substr(htmlspecialchars($ema['email_text']), 0,60);
						}
						else {
							$text = htmlspecialchars($ema['email_text']);
						}
						echo $text;
						?>
					</td>
					<td><?php echo htmlspecialchars($ema['email_komu']); ?></td>
					<td><?php echo htmlspecialchars($ema['email_stav'] . ' ' . $publikA[$ema['email_stav']]); ?></td>
					<td>
							<?php
								if ($ema['email_stav'] == '1') {
									# active
									/*?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&deactive=' . $ema['email_id']);  ?>" alt="Zablokovat" title="Zablokovat">*/?>
									<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&repeat=' . htmlspecialchars($ema['email_id']); ?>" title="Znovu poslat email"><i style="color: red;" class="glyphicon glyphicon-repeat"></i></a> &nbsp; 
									<i class="glyphicon glyphicon-ok" title="Email uz bol rozoslany" style="color: green"></i>&nbsp;<?php /*</a>&nbsp;<?php*/
								}
								else { // 0 zablokovane
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&active=' . $ema['email_id']);  ?>" alt="Rozoslat email" title="Rozoslat email"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

								?>
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($ema['email_id']); ?>" title="Zmenit"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($ema['email_id']); ?>" title="Vymazat"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a></td>
				</tr>
				<?php

			}
			?></table><?php 

			# STRANKAOVANIE
			//$this->strankovanie($limit, $range, $url_zacni, $url_menu);
			$this->strankovanie($limit, $range, URL_ADRESA, '?' . $_SERVER['QUERY_STRING']);
			 
		}
		else {
			echo $this->lg[$this->flag]['nodata'];
		}
		?></div><?php
	}

	public function strankovanie ($limit = '5', $range = '2', $url_zacni, $url_menu) { // limit pocet clankov na stranu,    range pocet stran na stranu
				// url_menu    menu_url 

		# POCET vYSLEDKOV ,  POCET CLANKOV
		$poc = $this->db->query('SELECT FOUND_ROWS() ');
		$pocet = $poc->fetch_array();
		$numrows = $pocet[0];

		if ($numrows > $limit) {
			if (isset($_GET['page']) AND $_GET['page'] != '0') {
				$page = $_GET['page'];
			}
			else {
				$page = 1;
			}
		}
		else {
			$page = 1;
		}

		$numofpages = ceil($numrows / $limit);
		
		if ($range == '0' OR $range == FALSE) {
			$range = 5;
		}

		$maxrange = max(1, $page - (($range - 1) / 2));
		$minrange = min($numofpages, $page + (($range - 1) / 2));
		if (($minrange - $maxrange) < ($range - 1))  {
			if ($maxrange == 1) {
				$minrange = min($maxrange + ($range - 1), $numofpages);
			}
			else {
				$maxrange = max($minrange - ($range - 1), 0);
			}
		}
		

		# TLAC STRANY
		?>
		<ul class="pagination" style="">
		<?php
		for ($i=1; $i <= $numofpages ; $i++) {
 
			if ($i == $page) {
				?><li class="active"><a href="#"><?php echo $i; ?></a></li><?php
			}
			else {
				if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php' OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK) {
					# DOMOV    HOME
					?><li class=""><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php
				}
				else {
					?><li class=""><a href="<?php echo htmlspecialchars($url_zacni . $url_menu . '&page=' . $i); ?>"><?php echo $i; ?></a></li><?php
				}

			}

		}
		?>
		</ul>
		<?php


	}


	private function sendEmail($adminEmail, $adminMeno, $email, $body, $subject_add = ""){
        $mail=self::getInstance();

        $mail->From = $adminEmail; //moja adresa
        $mail->FromName = $adminMeno; //moje meno
        $mail->AddAddress($email); //Vas mail
        $mail->WordWrap = 50;                                 // po 50 znaku slova rozdel slovo
        $mail->IsHTML(true);
        $mail->Subject = $subject_add;
        $mail->Body    = htmlspecialchars_decode($body);
        $mail->AltBody = "Táto správa obsahuje HTML značky... Váš poštový klient ich asi nepodporuje";

        $mail->CharSet = "UTF-8";
        $mail->Send();
    }


	public function emailActive ($id) {
		$query = $this->db->query('SELECT email_stav, email_komu, email_text, email_predmet FROM be_email' . $this->prefix . ' 
							WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$co = $query->fetch_assoc();

			# SEND SEND SEND MAIL
			# POSLAT KAZDEMU
			# EMAIL   -- stav    adminactive
			$sys = $this->db->query('SELECT system_admin_email, system_title, system_url 
										FROM be_system' . $this->prefix . ' 
										ORDER BY system_id DESC ');
			if ($sys->num_rows != FALSE) {
				$system = $sys->fetch_assoc();

				$adminEmail = $system['system_admin_email'];
				$adminMeno = $system['system_title'];
				$adminUrl = $system['system_url'];
			}
			else {
				$adminEmail = 'besom@besom.sk';
				$adminMeno = 'Besom.sk CMS';
				$adminUrl = 'http://www.besom.sk';
			}

			$text = '<html><head></head><body>';
			$text .= htmlspecialchars_decode($co['email_text']);
			$text .= '</body></html>';

			$predmet = htmlspecialchars($co['email_predmet']);

			# NACIATNEI KOMu TO POJDE
			$nac = $this->db->query('SELECT newsletter_email, newsletter_id FROM be_newsletter' . $this->prefix . ' 
										WHERE newsletter_stav =1 AND newsletter_privacy =1 
										ORDER BY newsletter_id DESC ');
			if ($nac->num_rows != FALSE) {
				# all - kazdemu
				while ($all = $nac->fetch_assoc()) {
					$this->sendEmail($adminEmail, $adminMeno, $all['newsletter_email'], $text, $predmet);
				}
			}
			#END EMAIL SEND FUNCTION


			# ZMENIME STAV NA POSLANE
			$uloz = $this->db->query('UPDATE be_email' . $this->prefix . ' SET 
										email_stav =1 WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');



			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
	}


	public function emailRepeat ($id) {
		$query = $this->db->query('SELECT email_stav, email_komu, email_text, email_predmet FROM be_email' . $this->prefix . ' 
							WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$co = $query->fetch_assoc();

			# SEND SEND SEND MAIL
			# POSLAT KAZDEMU
			# EMAIL   -- stav    adminactive
			$sys = $this->db->query('SELECT system_admin_email, system_title, system_url 
										FROM be_system' . $this->prefix . ' 
										ORDER BY system_id DESC ');
			if ($sys->num_rows != FALSE) {
				$system = $sys->fetch_assoc();

				$adminEmail = $system['system_admin_email'];
				$adminMeno = $system['system_title'];
				$adminUrl = $system['system_url'];
			}
			else {
				$adminEmail = 'besom@besom.sk';
				$adminMeno = 'Besom.sk CMS';
				$adminUrl = 'http://www.besom.sk';
			}

			$text = '<html><head></head><body>';
			$text .= htmlspecialchars_decode($co['email_text']) . '<br>' . htmlspecialchars('Tento email bol poslany znovu, pravdepodobne nastala niekde chyba, vopred sa ospravedlnujeme.');
			$text .= '</body></html>';
			

			$predmet = htmlspecialchars($co['email_predmet']);

			# NACIATNEI KOMu TO POJDE
			$nac = $this->db->query('SELECT newsletter_email, newsletter_id FROM be_newsletter' . $this->prefix . ' 
										WHERE newsletter_stav =1 AND newsletter_privacy =1 
										ORDER BY newsletter_id DESC ');
			if ($nac->num_rows != FALSE) {
				# all - kazdemu
				while ($all = $nac->fetch_assoc()) {
					$this->sendEmail($adminEmail, $adminMeno, $all['newsletter_email'], $text, $predmet);
				}
			}
			#END EMAIL SEND FUNCTION

			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
	}


	/*public function emailDeactive ($id) {
		$query = $this->db->query('SELECT email_stav FROM be_email' . $this->prefix . ' 
							WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_email' . $this->prefix . ' SET 
										email_stav =0 WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
	}*/




	public function emailNew () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['predmet']) AND isset($_POST['text']) AND isset($_POST['komu']) AND isset($_POST['stav']) AND isset($_POST['date'])) {

			// token nemusi byt
			$predmet = (isset($_POST['predmet'])) ? $_POST['predmet'] : '';
			$text = (isset($_POST['text'])) ? $_POST['text'] : '';
			$komu = (isset($_POST['komu'])) ? $_POST['komu'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			$date = (isset($_POST['date'])) ? $_POST['date'] : '';


			if (!empty($predmet) AND !empty($text) AND !empty($komu) AND !empty($date) AND $stav != '') {

				$text = str_replace('\"', '', $text);

				# ULOZ
				$uloz = $this->db->query('INSERT IGNORE INTO be_email' . $this->prefix . ' 
												(email_id, email_predmet, email_text, email_datum, email_komu, email_stav)   
												VALUES (NULL,
														"' . $this->db->real_escape_string($predmet) . '",
														"' . $this->db->real_escape_string($text) . '",
														"' . $this->db->real_escape_string($date) . '",
														"' . $this->db->real_escape_string($komu) . '",
														"' . $this->db->real_escape_string($stav) . '") ');

				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();
				
			}
			else {

				setcookie('odpoved','1', time()+1);

				if (isset($_SERVER['HTTP_REFERER'])) {
					header("Location: " . $_SERVER['HTTP_REFERER']);
					exit();
				}
				else {
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new');
					exit();
				}

			}

		}


		##############################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##############################################################

		#FORM
		?>
	<h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat</h3>
	<div class="panel panel-success">
		<div class="panel-heading">
		   	<h3 class="panel-title">Pridat email</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Predmet</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Popis predmetu" name="predmet">
				    	<span class="text-danger">Musi byt!!!</span>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
				    <div class="col-sm-6">
				    	<input type="datetime" class="form-control" id="inputText3" value="<?php echo date('Y-m-d H:i:s'); ?>" name="date">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
				    <div class="col-sm-7">
				    <?php   
		                // Create Editor instance and use Text property to load content into the RTE.  
		                $rte = new RichTextEditor();   
		                $rte->Text = 'Tvoj novy Email'; 
		                // Set a unique ID to Editor   
		                $rte->ID = "text";    
		                $rte->MvcInit();   
		                // Render Editor 
		                echo $rte->GetString();  
		            ?> 
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Prijimatel</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" value="all" name="komu">
				    	<span class="text-danger">Musi byt!!! Defaultne nastavene kazdemu</span>
				    </div>
				</div>

			  	<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="stav" id="optionsRadios1" value="0" checked="checked">
						    Mozne dodatocne editovanie <small clas="text-info">Email nebude poslany, po jeho ulozeni.</small>
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="stav" id="optionsRadios2" value="1" disabled="disabled">
						    Poslat email
						  </label>
						</div>
					</div>	
			  	</div>


 				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success btn-sm" name="submit"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</button> &nbsp; 
						<button type="reset" class="btn btn-reset btn-sm" name="submit"><i class="glyphicon glyphicon-del"></i>&nbsp;Reset</button>

					</div>
				</div>	
			</form>
		</div>
	</div>	
		<?php
	}


	public function emailEdit ($id) {

				# POST
		if (isset($_POST['submit']) AND isset($_POST['predmet']) AND isset($_POST['text']) AND isset($_POST['komu']) AND isset($_POST['stav']) AND isset($_POST['date'])) {

			// token nemusi byt
			$predmet = (isset($_POST['predmet'])) ? $_POST['predmet'] : '';
			$text = (isset($_POST['text'])) ? $_POST['text'] : '';
			$komu = (isset($_POST['komu'])) ? $_POST['komu'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			$date = (isset($_POST['date'])) ? $_POST['date'] : '';


			if (!empty($predmet) AND !empty($text) AND !empty($komu) AND !empty($date) AND $stav != '') {

				$text = str_replace('\"', '', $text);

				# ULOZ
				$uloz = $this->db->query('UPDATE be_email' . $this->prefix . ' SET 
												email_predmet ="' . $this->db->real_escape_string($predmet) . '",
												email_text ="' . $this->db->real_escape_string($text) . '",
												email_datum ="' . $this->db->real_escape_string($date) . '",
												email_komu ="' . $this->db->real_escape_string($komu) . '",
												email_stav"' . $this->db->real_escape_string($stav) . '" 
											WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();
				
			}
			else {

				setcookie('odpoved','1', time()+1);

				if (isset($_SERVER['HTTP_REFERER'])) {
					header("Location: " . $_SERVER['HTTP_REFERER']);
					exit();
				}
				else {
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new');
					exit();
				}

			}

		}


		##############################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##############################################################


		$over = $this->db->query('SELECT * FROM be_email' . $this->prefix . ' 
									WHERE email_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; Zmenit <?php echo htmlspecialchars($this->lg[$this->flag]['nadpis']); ?></h3>
				</div>
				<div class="panel-body">
						
				</div>
				
				<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Predmet</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo $zobraz['email_predmet']; ?>" name="predmet">
					    	<span class="text-danger">Musi byt!!!</span>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<input type="datetime" class="form-control" id="inputText3" value="<?php echo $zobraz['email_datum']; ?>" name="date">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-7">
					    <?php   
			                // Create Editor instance and use Text property to load content into the RTE.  
			                $rte = new RichTextEditor();   
			                $rte->Text = $zobraz['email_text']; 
			                // Set a unique ID to Editor   
			                $rte->ID = "text";    
			                $rte->MvcInit();   
			                // Render Editor 
			                echo $rte->GetString();  
			            ?> 
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Prijimatel</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo $zobraz['email_komu']; ?>" name="komu">
					    	<span class="text-danger">Musi byt!!! Defaultne nastavene kazdemu</span>
					    </div>
					</div>

				  	<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="0" checked="checked">
							    Mozne dodatocne editovanie <small clas="text-info">Email nebude poslany, po jeho ulozeni.</small>
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="1" disabled="disabled">
							    Poslat email
							  </label>
							</div>
						</div>	
				  	</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-info btn-sm" name="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
						</div>
					</div>
				</form>
			</div>
			<?php

		}
		else {
			# ERROR NIEJE ID NEEXISTUJE
			setcookie('odpoved', '1', time()+1);
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit();
		}


	}

	public function emailDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($id)) {


			$del = $this->db->query('DELETE FROM be_email' . $this->prefix . ' 
										WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST


		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_email' . $this->prefix . ' 
									WHERE email_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
			<div class="panel panel-danger">
				<div class="panel-heading">
					<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; Vymazat <?php echo htmlspecialchars($this->lg[$this->flag]['nadpis']); ?></h3>
				</div>
				<div class="panel-body">
						
				</div>

				<form class="form-horizontal" role="form" method="post" action="">
				
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Predmet</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo $zobraz['email_predmet']; ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo $zobraz['email_datum']; ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-7">
					    	<p class="form-control-static"><?php echo htmlspecialchars_decode($zobraz['email_text']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Prijimatel</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo $zobraz['email_komu']; ?></p>
					    </div>
					</div>

				  	<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						<div class="col-sm-6">
					    	<p class="form-control-static"><?php echo $zobraz['email_stav']; ?></p>
						</div>	
				  	</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>

				</form>
			</div>
			<?php

		}
		else {
			# ERROR NIEJE ID NEEXISTUJE
			setcookie('odpoved', '1', time()+1);
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit();
		}

	}


}


//$language = new language($this->db, $this->prefix, $this->lang);
$email = new email($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>