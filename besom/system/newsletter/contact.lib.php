<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

include_once('mailer/class.phpmailer.php');

class newsletter {

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
		echo $this->lg[$this->flag]['nadpis'];
	}


	public function newsletterTab () {

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
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new'; ?>"><span class="text-default">Pridat kontakt</span>&nbsp;<i class="glyphicon glyphicon-plus"></i></a>

					</div>
				<?php

		/*$query = $this->db->query('SELECT SQL_CALC_FOUND_ROWS  *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
										FROM be_text' . $this->prefix . ' t 
											JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
											JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									ORDER BY text_datum DESC LIMIT ' . $start . ', ' . $limit . ' ');*/
		
		$quer = 'SELECT SQL_CALC_FOUND_ROWS  *  
										FROM be_newsletter' . $this->prefix . ' ';

		if (isset($_GET['search']) AND $_GET['search'] != FALSE AND $_GET['search'] != '') {
			
			#NAZOV
			$quer .= ' WHERE newsletter_email LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR newsletter_privacy LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR newsletter_token LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR newsletter_datum LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR newsletter_stav LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

		}

		#FILTERS
		// VIEWS
		if (isset($_GET['email'])AND $_GET['email'] != FALSE AND ($_GET['email'] == 'ASC' OR $_GET['email'] == 'DESC')) {
			$quer .= ' ORDER BY newsletter_email ' . $_GET['email'] . ' ';
		}
		elseif (isset($_GET['licence'])AND $_GET['licence'] != FALSE AND ($_GET['licence'] == 'ASC' OR $_GET['licence'] == 'DESC')) {
			$quer .= ' ORDER BY newsletter_privacy ' . $_GET['licence'] . ' ';
		}
		elseif (isset($_GET['token'])AND $_GET['token'] != FALSE AND ($_GET['token'] == 'ASC' OR $_GET['token'] == 'DESC')) {
			$quer .= ' ORDER BY newsletter_token ' . $_GET['token'] . ' ';
		}
		elseif (isset($_GET['date'])AND $_GET['date'] != FALSE AND ($_GET['date'] == 'ASC' OR $_GET['date'] == 'DESC')) {
			$quer .= ' ORDER BY newsletter_datum ' . $_GET['date'] . ' ';
		}
		elseif (isset($_GET['stav'])AND $_GET['stav'] != FALSE AND ($_GET['stav'] == 'ASC' OR $_GET['stav'] == 'DESC')) {
			$quer .= ' ORDER BY newsletter_stav ' . $_GET['stav'] . ' ';
		}
		else {
			$quer .= ' ORDER BY newsletter_datum DESC  ';
		}

		$quer .= ' LIMIT ' . $start . ', ' . $limit . ' ';

		$query = $this->db->query($quer);
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$por = $por + $start;

			$publikA = array('1' => 'Aktivny',
							 '0' => 'Zablokovany',
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
					<th>Email 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&email=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&email=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<!-- <th>Popis</th>  -->
					<th>Datum 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Token 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&token=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&token=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Licencia 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&licence=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&licence=ASC'; ?>">
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
			while ($news = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php echo htmlspecialchars($colorA[$news['newsletter_stav']]); ?>">
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php echo htmlspecialchars($news['newsletter_email']); ?></td>
					<td><?php echo htmlspecialchars($news['newsletter_datum']); ?></td>
					<td><?php echo htmlspecialchars($news['newsletter_token']); ?>&nbsp; <a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&newToken=' . htmlspecialchars($news['newsletter_id']); ?>"><i style="color: red;" class="glyphicon glyphicon-repeat"></i></a></td>
					<td><?php echo htmlspecialchars($news['newsletter_privacy'] . ' ' . $licA[$news['newsletter_privacy']]); ?></td>
					<td><?php echo htmlspecialchars($publikA[$news['newsletter_stav']]); ?>&nbsp;<i style="color: <?php echo $colA[$news['newsletter_stav']]; ?>;" class="<?php echo $ikonA[$news['newsletter_stav']]; ?>"></i></td>
					<td>
							<?php
								if ($news['newsletter_stav'] == '1') {
									# active
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&deactive=' . $news['newsletter_id']);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
								}
								elseif ($news['newsletter_stav'] == '2') {
									# AKTIVOVAT TREBA
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&acemail=' . $news['newsletter_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green"></i></a>&nbsp;<?php
								}
								else { // 0 zablokovane
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&active=' . $news['newsletter_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

								?>
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($news['newsletter_id']); ?>"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($news['newsletter_id']); ?>"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a></td>
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


	/*public function newsletterActive ($id) {
		$query = $this->db->query('SELECT newsletter_stav FROM be_newsletter' . $this->prefix . ' 
							WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_newsletter' . $this->prefix . ' SET 
										newsletter_stav =1 WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

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

	public function newsletterDeactive ($id) {
		$query = $this->db->query('SELECT newsletter_stav FROM be_newsletter' . $this->prefix . ' 
							WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_newsletter' . $this->prefix . ' SET 
										newsletter_stav =0 WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

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


	public function newsletterAcemail ($id) {
		$query = $this->db->query('SELECT newsletter_stav, newsletter_email, newsletter_token FROM be_newsletter' . $this->prefix . ' 
							WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$email = $query->fetch_assoc();  // len kvoli emailu prijmatel

			$uloz = $this->db->query('UPDATE be_newsletter' . $this->prefix . ' SET 
										newsletter_stav =1 WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

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
			$text .= "<b>Pekny den praje " . $adminMeno . " z adresy " . $adminUrl . ".</b> <br>";
			$text .= "Prave Vam bol aktivovany odber noviniek. Odber aktivoval admin stranky. ";
			$text .= "<br>Pre viac informacii o odbere noviniek sa dozviete na tejto adrese. " . '<a href="' . $adminUrl . '?status=adminactive&hash=' . $email['newsletter_token'] . '">Odkaz pre viac info o aktivaciiadminom</a>.</br>';
			$text .= "<br> Budeme radi ak si precitate podmienky pouzivania a licenciu prav."; 	
			$text .= "<br> Dakujeme pekne za vasu priazen, budeme spokojny ak nas odporucite vasim znamim."; 
			$text .= "<br>Prajeme vam pekny zvysok dna. "; 
			$text .= "</body></html> "; 
			
			$predmet = 'Odoberanie newslettera zo stanky ' . $adminUrl;


			$this->sendEmail($adminEmail, $adminMeno, $email['newsletter_email'], $text, $predmet); // function send email


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

	public function newsletterToken ($id) {

		$query = $this->db->query('SELECT newsletter_id, newsletter_email FROM be_newsletter' . $this->prefix . ' 
							WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$email = $query->fetch_assoc();  // len kvoli prijimatelovi, email

				# TOKEN
				$a = array("A","B","C","D","E","F","G","H","J","K","L","M",
                            "N","P","Q","R","S","T","U","V","W","X","Y","Z",
                            "1","2","3","4","5","6","7","8","9",
                            "a","b","c","d","e","f","g","h","j","k","l","m",
                            "n","p","q","r","s","t","u","v","w","x","y","z");
				$pocet = '8';
				$keys = array();
				for ($i=0; $i <= $pocet ; $i++) { 
					$x = mt_rand('1', count($a)-1);

					if(!in_array($x, $keys)) {
               			$keys[] = $x;
            		}
				}

				$token = '';
		        foreach($keys as $key){
		           $token .= $a[$key];
		        }
		        // $token


			$uloz = $this->db->query('UPDATE be_newsletter' . $this->prefix . ' SET 
										newsletter_token ="' . $this->db->real_escape_string($token) . '" 
									WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');


			#EMAIL POSLAT
			# EMAIL   -- stav    newToken
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
			$text .= "<b>Pekny den praje " . $adminMeno . " z adresy " . $adminUrl . ".</b> <br>";
			$text .= "Prave Vam bol opetovne zaslany link na aktivaciu odberu noviniek. ";
			$text .= "<br>Aktivovanie odberu noviniek tejto adrese. " . '<a href="' . $adminUrl . '?status=newToken&hash=' . $token . '">Aktivacia odberu noviniek</a>.</br>';
			$text .= "<br> Ak ste tento email nemali dostat, prosim ignorujte ho."; 	
			$text .= "<br> Budeme radi ak si precitate podmienky pouzivania a licenciu prav."; 	
			$text .= "<br> Dakujeme pekne za vasu priazen, budeme spokojny ak nas odporucite vasim znamim."; 
			$text .= "<br>Prajeme vam pekny zvysok dna. "; 
			$text .= '</body></html>';
			
			$predmet = 'Odoberanie newslettera zo stanky ' . $adminUrl;


			$this->sendEmail($adminEmail, $adminMeno, $email['newsletter_email'], $text, $predmet); // function send email


			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&newToken=' . $_GET['newToken']);
			exit();
		}

		$over = $this->db->query('SELECT * FROM be_text' . $this->prefix . ' 
									WHERE text_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
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
					    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['newsletter_email']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['newsletter_datum']); ?></p>
					    </div>
					</div>
				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-warning btn-sm" name="submit"><i class="glyphicon glyphicon-warning-sign"></i>&nbsp;Poslat Token</button>
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

	public function newsletterNew () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['email']) AND isset($_POST['stav']) AND isset($_POST['date'])) {

			// token nemusi byt
			$email = (isset($_POST['email'])) ? $_POST['email'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			$date = (isset($_POST['date'])) ? $_POST['date'] : '';
			//$licence = (isset($_POST['licence'])) ? $_POST['licence'] : '';


			//$image = (isset($_POST['image'])) ? $_POST['image'] : '';



			if (!empty($email) AND !empty($stav) AND !empty($date) AND isset($_POST['licence']) AND $_POST['licence'] == '1') {

				$licence = $_POST['licence'];

				# TOKEN
				$a = array("A","B","C","D","E","F","G","H","J","K","L","M",
                            "N","P","Q","R","S","T","U","V","W","X","Y","Z",
                            "1","2","3","4","5","6","7","8","9",
                            "a","b","c","d","e","f","g","h","j","k","l","m",
                            "n","p","q","r","s","t","u","v","w","x","y","z");
				$pocet = '8';
				$keys = array();
				for ($i=0; $i <= $pocet ; $i++) { 
					$x = mt_rand('1', count($a)-1);

					if(!in_array($x, $keys)) {
               			$keys[] = $x;
            		}
				}

				$token = '';
		        foreach($keys as $key){
		           $token .= $a[$key];
		        }
		        // $token


				#EMAIL POSLAT
				# EMAIL   -- pridany v systemee ako novy new
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
				$text .= "<b>Pekny den praje " . $adminMeno . " z adresy " . $adminUrl . ".</b> <br>";
				$text .= "Prave ste boli pridany adminom k odberu noviniek. ";

				if ($stav == '2') {
					# treba aktivovat emailom
					$text .= "<br>Aktivaciu uskutocnite odberom noviniek tejto adrese. " . '<a href="' . $adminUrl . '?status=adminactive&hash=' . $token . '">Aktivacia odberu noviniek</a>.</br>';
				}
				elseif ($stav == '1') {
					# uz je aktivy
					$text .= "<br>Viac informacii sa dozviete na adrese. " . '<a href="' . $adminUrl . '?status=activerepeat&hash=' . $token . '">viac info</a>.</br>';
				}
				else {
					# none
				}

				$text .= "<br>Aktivaciu uskutocnite odberom noviniek tejto adrese. " . '<a href="' . $adminUrl . '?status=newEmail&hash=' . $token . '">Aktivacia odberu noviniek</a>.</br>';
				$text .= "<br> Ak ste tento email nemali dostat, prosim ignorujte ho."; 	
				$text .= "<br> Budeme radi ak si precitate podmienky pouzivania a licenciu prav."; 	
				$text .= "<br> Dakujeme pekne za vasu priazen, budeme spokojny ak nas odporucite vasim znamim."; 
				$text .= "<br>Prajeme vam pekny zvysok dna. "; 
				$text .= '</body></html>';
				
				$predmet = 'Odoberanie newslettera zo stanky ' . $adminUrl;

				$this->sendEmail($adminEmail, $adminMeno, $email, $text, $predmet); // function send email
				#END SEND MAIL

				# ULOZ
				$uloz = $this->db->query('INSERT IGNORE INTO be_newsletter' . $this->prefix . ' 
												(newsletter_id, newsletter_email, newsletter_datum, newsletter_token, newsletter_stav, newsletter_privacy)   
												VALUES (NULL,
														"' . $this->db->real_escape_string($email) . '",
														"' . $this->db->real_escape_string($date) . '",
														"' . $this->db->real_escape_string($token) . '",
														"' . $this->db->real_escape_string($stav) . '",
														"' . $this->db->real_escape_string($licence) . '") ');

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


		#FORM
		?>
	<h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat</h3>
	<div class="panel panel-success">
		<div class="panel-heading">
		   	<h3 class="panel-title">Pridat kontakt</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Nazov" name="email">
				    	<span class="text-danger">Musi byt validny!!!</span>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
				    <div class="col-sm-6">
				    	<input type="datetime" class="form-control" id="inputText3" value="<?php echo date('Y-m-d H:i:s'); ?>" name="date">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Token</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" disabled="disabled" placeholder="Automaticky generovany.." name="token">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Podmienky Licencia</br><small>Podmienky pouzivania</small></label>

				    <div class="col-sm-6">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="licence" value="1"> Pravidla a podmienky pouzivania
							</label>
						</div>
						<small class="text-info">Kliknutim/oynacenim suhlasite.</small>&nbsp;
						<span class="text-danger">Musite suhlasit, inak to nepojde</span>
					</div>
				</div>

			  	<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="stav" id="optionsRadios1" value="1" checked="checked">
						    Aktivny
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="stav" id="optionsRadios2" value="0">
						    Zablokovany
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="stav" id="optionsRadios2" value="2">
						    Neaktivny <small class="text-danger">Treba aktivovat emailom, alebo rucne.</small>
						  </label>
						</div>
					</div>	
			  	</div>


 				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success btn-sm" name="submit"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</button>
					</div>
				</div>	
			</form>
		</div>
	</div>	
		<?php
	}


	public function newsletterEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['email']) AND isset($_POST['stav']) AND isset($_POST['date']) AND isset($_POST['token'])) {

			$token = (isset($_POST['token'])) ? $_POST['token'] : '';
			$email = (isset($_POST['email'])) ? $_POST['email'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			$date = (isset($_POST['date'])) ? $_POST['date'] : '';
			//$licence = (isset($_POST['licence'])) ? $_POST['licence'] : '';


			//$image = (isset($_POST['image'])) ? $_POST['image'] : '';



			if (!empty($email) AND !empty($stav) AND !empty($token) AND !empty($date) AND isset($_POST['licence']) AND $_POST['licence'] == '1') {

				$licence = $_POST['licence'];

				# TOKEN
				/*$a = array("A","B","C","D","E","F","G","H","J","K","L","M",
                            "N","P","Q","R","S","T","U","V","W","X","Y","Z",
                            "1","2","3","4","5","6","7","8","9",
                            "a","b","c","d","e","f","g","h","j","k","l","m",
                            "n","p","q","r","s","t","u","v","w","x","y","z");
				$pocet = '8';
				$keys = array();
				for ($i=0; $i <= $pocet ; $i++) { 
					$x = mt_rand('1', count($a)-1);

					if(!in_array($x, $keys)) {
               			$keys[] = $x;
            		}
				}

				$token = '';
		        foreach($keys as $key){
		           $token .= $a[$key];
		        }*/
		        // $token

				# ULOZ
				$uloz = $this->db->query('UPDATE be_newsletter' . $this->prefix . ' SET 												
												newsletter_email ="' . $this->db->real_escape_string($email) . '",
												newsletter_datum ="' . $this->db->real_escape_string($date) . '",
												newsletter_token ="' . $this->db->real_escape_string($token) . '",
												newsletter_stav ="' . $this->db->real_escape_string($stav) . '",
												newsletter_privacy ="' . $this->db->real_escape_string($licence) . '" 
											WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

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


		$over = $this->db->query('SELECT * FROM be_newsletter' . $this->prefix . ' 
									WHERE newsletter_id ="' . $this->db->real_escape_string($id) . '" ');
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
					    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['newsletter_email']); ?>" name="email">
					    	<span class="text-danger">Musi byt validny!!!</span>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<input type="datetime" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['newsletter_datum']); ?>" name="date">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Token</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" disabled="disabled" value="<?php echo htmlspecialchars($zobraz['newsletter_token']); ?>" name="token">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Podmienky Licencia</br><small>Podmienky pouzivania</small></label>

					    <div class="col-sm-6">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="licence" <?php if($zobraz['newsletter_privacy'] == '1') { echo 'checked="checked"'; } ?>  value="1" > Pravidla a podmienky pouzivania
								</label>
							</div>
							<small class="text-info">Kliknutim/oynacenim suhlasite.</small>&nbsp;
							<span class="text-danger">Musite suhlasit, inak to nepojde</span>
						</div>
					</div>

				  	<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" <?php if($zobraz['newsletter_stav'] == '1') { echo 'checked="checked"'; } ?> value="1">
							    Aktivny
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" <?php if($zobraz['newsletter_stav'] == '0') { echo 'checked="checked"'; } ?> value="0">
							    Zablokovany
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" <?php if($zobraz['newsletter_stav'] == '2') { echo 'checked="checked"'; } ?> value="2">
							    Neaktivny <small class="text-danger">Treba aktivovat emailom, alebo rucne.</small>
							  </label>
							</div>
						</div>	
				  	</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-info btn-sm" name="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
						</div>
					</div>
					<input type="hidden" name="token" value="<?php echo htmlspecialchars($zobraz['newsletter_token']); ?>">	
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

	public function newsletterDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['token']) AND isset($_POST['email']) AND isset($id)) {


			$del = $this->db->query('DELETE FROM be_newsletter' . $this->prefix . ' 
										WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" AND 
												newsletter_token ="' . $this->db->real_escape_string($_POST['token']) . '" AND 
												newsletter_email ="' . $this->db->real_escape_string($_POST['email']) . '" ');

			#EMAIL POSLAT
			# EMAIL   -- pridany v systemee ako novy new
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
			$text .= "<b>Pekny den praje " . $adminMeno . " z adresy " . $adminUrl . ".</b> <br>";
			$text .= "Prave vam bol zruseny odber noviniek ";
			$text .= "<br>Viac informacii sa dozviete na tejto adrese. " . '<a href="' . $adminUrl . '?status=del&hash=' . $_POST['token'] . '">Viac inofmracii</a>.</br>';
			$text .= "<br> Ak ste tento email nemali dostat, prosim ignorujte ho."; 	
			$text .= "<br> Budeme radi ak si precitate podmienky pouzivania a licenciu prav."; 	
			$text .= "<br> Dakujeme pekne za vasu priazen, budeme spokojny ak nas odporucite vasim znamim."; 
			$text .= "<br>Prajeme vam pekny zvysok dna. "; 
			$text .= '</body></html>';
			
			$predmet = 'Odoberanie newslettera zo stanky ' . $adminUrl;

			$this->sendEmail($adminEmail, $adminMeno, $_POST['email'], $text, $predmet); // function send email
			#END SEND MAIL


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST


		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_newsletter' . $this->prefix . ' 
									WHERE newsletter_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
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
					    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['newsletter_email']); ?></p>
					    	<span class="text-danger">Musi byt validny!!!</span>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['newsletter_datum']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Token</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['newsletter_token']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Podmienky Licencia</br><small>Podmienky pouzivania</small></label>

					    <div class="col-sm-6">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="licence" <?php if($zobraz['newsletter_privacy'] == '1') { echo 'checked="checked"'; } ?>  value="1" > Pravidla a podmienky pouzivania
								</label>
							</div>
							<small class="text-info">Kliknutim/oynacenim suhlasite.</small>&nbsp;
							<span class="text-danger">Musite suhlasit, inak to nepojde</span>
						</div>
					</div>

				  	<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" <?php if($zobraz['newsletter_stav'] == '1') { echo 'checked="checked"'; } ?> value="1">
							    Aktivny
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" <?php if($zobraz['newsletter_stav'] == '0') { echo 'checked="checked"'; } ?> value="0">
							    Zablokovany
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" <?php if($zobraz['newsletter_stav'] == '2') { echo 'checked="checked"'; } ?> value="2">
							    Neaktivny <small class="text-danger">Treba aktivovat emailom, alebo rucne.</small>
							  </label>
							</div>
						</div>	
				  	</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>
					<input type="hidden" name="token" value="<?php echo $zobraz['newsletter_token']; ?>">
					<input type="hidden" name="email" value="<?php echo $zobraz['newsletter_email']; ?>">
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
$newsletter = new newsletter($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>