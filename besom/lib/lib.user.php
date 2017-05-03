<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

#USER

class user {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {

		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;

		##########################################################################################
		# CI CAPTCHA JE POVOLENA ALEBO NIE
		$capt = $this->db->query('SELECT system_id, system_captcha_login 
							FROM be_system' . $this->prefix . ' ORDER BY system_id DESC LIMIT 1 ');
		if ($capt->num_rows != FALSE) {
			# OK JE
			$capt_log = $capt->fetch_assoc();

			if ($capt_log['system_captcha_login'] == '1') {
				# POVOLENE
				$captcha_isset = ' AND isset($_POST["captcha"]) ';
			}
			else {
				# 2 JE ZAKAZANE ..... else vsetko
				$captcha_isset = '  ';

			}
		}
		else {
			# NIC
		}
		###########################################################################################


		//$this->logoutAutomatic('20'); // cas odhlasenia v min.


		###########################################################################################

		if (isset($_POST['username']) AND isset($_POST['password'])) {

			if ($_POST['username'] != NULL OR $_POST['password'] != NULL) {

				#CAPTCHA
				if ($capt_log['system_captcha_login'] == '1' AND $_POST['captcha'] != NULL) {
					# NEBOLO VYPLNENE

					setcookie('odpoved2', 'captcha', time()+2);
					$odpoved = 'c';
					header('Location: index.php?error=' . urlencode($odpoved) . '&typ=1');
					exit();
				}
				elseif ($capt_log['system_captcha_login'] == '1' AND $_POST['captcha'] !== $_POST['cap']) {
					# ZLE PREPISANY KOD

					setcookie('odpoved2', 'captcha', time()+2);
					$odpoved = 'c';
					header('Location: index.php?error=' . urlencode($odpoved) . '&typ=1');
					exit();
				}
				else {
				
					$user1 = $this->db->query('SELECT * FROM be_user' . $this->prefix . ' 
						WHERE user_nick = "' . $this->db->real_escape_string($_POST['username']) . '" LIMIT 1');
					if ($user1->num_rows == 1) {
						# OK

						$user = $user1->fetch_assoc();

						if ($this->cryptPassword($_POST['password'] . htmlspecialchars($user['user_salt'])) == htmlspecialchars($user['user_heslo'])) {
							# HESLO DOBRE


							# OVERENIE DUALNEHO PRIHLASENIA
							# DUALNE PRIHLASENEI
							//$this->userDualLogin($user['user_id'], 'Dualny pristup');

							session_regenerate_id();
								
							$_SESSION['user_id'] = $this->db->real_escape_string($user['user_id']);
							$_SESSION['user'] = $this->db->real_escape_string($_POST['username']);
							$_SESSION['name'] = $this->db->real_escape_string($user['user_meno']);
							$_SESSION['avatar'] = $this->db->real_escape_string($user['user_avatar']);
							$_SESSION['password'] = $this->db->real_escape_string($this->cryptPassword($_POST['password'] . htmlspecialchars($user['user_salt'])));
							$_SESSION['email'] = $this->db->real_escape_string($user['user_email']);

							$_SESSION['last_action'] = time();


//mkdir('tmp/'.session_id(), 777);

							#######################################################################
							# REMEMBER PRIHLASENIE
							if (isset($_POST['remember']) AND $_POST['remember'] == '1') {
								
								$expire = time()+((3600*24)*10); // cokies ulozene na 10 dni
						
								setcookie('beid', $this->cryptPassword($_SESSION['user_id'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
								setcookie('beuser', $this->cryptPassword($_SESSION['user'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
								setcookie('bename', $this->cryptPassword($_SESSION['name'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
								setcookie('beavatar', $this->cryptPassword($_SESSION['avatar'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
								setcookie('bepassword', $this->cryptPassword($_SESSION['password'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
								setcookie('beemail', $this->cryptPassword($_SESSION['email'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
										
								setcookie('bekey', $user['user_key'], $expire);
							}
							#######################################################################



							#######################################################################################################
							# USER LOGS
							$this->userLogs('1', $_POST['username'], $_POST['password'], $_SERVER['REMOTE_ADDR'], gethostbyaddr($_SERVER["REMOTE_ADDR"]), $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"], '', $_SESSION['user_id']);
							#######################################################################################################


							# TEST COOKIES ODPOVED
							setcookie('odpoved','2', time()+2);
							$ok = 'p';
							header('Location: index.php?ok=' . urlencode($ok) . '&typ=2');
							exit();

						}
						else {

							#######################################################################################################
							# USER LOGS
							$this->userLogs('0', $_POST['username'], $_POST['password'], $_SERVER['REMOTE_ADDR'], gethostbyaddr($_SERVER["REMOTE_ADDR"]), $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"], '', '0');
							#######################################################################################################


							setcookie('odpoved2', 'password', time()+2);

							# TEST COOKIES ODPOVED
							setcookie('odpoved','1', time()+2);
							$odpoved = 'no';
							header('Location: index.php?error=' . urlencode($odpoved) . '&typ=1');
							exit();
						}	

					}
					else {
						# ZLE UDAJE MENO ALEBO HESLO

						#######################################################################################################
						# USER LOGS
						$this->userLogs('0', $_POST['username'], $_POST['password'], $_SERVER['REMOTE_ADDR'], gethostbyaddr($_SERVER["REMOTE_ADDR"]), $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"], '', '0');
						#######################################################################################################


						//////// esteb treba zistit a upravit co zle je
						setcookie('odpoved2', 'user', time()+3);
						setcookie('odpoved2', 'password', time()+3);


						# TEST COOKIES ODPOVED
						setcookie('odpoved','1', time()+2);
						$odpoved = 'z';
						header('Location: index.php?error=' . urlencode($odpoved) . '&typ=1');
						exit();
					}

				}

			}
			else {

				# POST NEBOLI INICIALIZOVANE ALE BOLI POTVRDENE
				if ($_POST['username'] == FALSE) {
					# MENO
					setcookie('odpoved2', 'user', time()+2);
				}
				elseif ($_POST['password'] == FALSE) {
					# HESLO
					setcookie('odpoved2', 'password', time()+2);
				}
				else {
					# NEVIEM
				}

				# TEST COOKIES ODPOVED
				setcookie('odpoved','1', time()+2);
				$odpoved = 'a';
				header('Location: index.php?error=' . urlencode($odpoved) . '&typ=1');
				exit();
			}
		}

	}

	public function createTempData($file) {
        $p=array();
        $sel=$this->db->query("SELECT login, email FROM users");
        while($selrow=$sel->fetch_Assoc()){
            array_push($p, array("key"=>$selrow["email"],"value"=>$selrow["login"]));
        }
        //$p=array(array("key"=>"dddd", "value"=>"hodnota";),array("key"=>"dddd", "value"=>"hodnota"));
        file_put_contents($file, json_encode($p));

    }
    private function deleteTempData($file) {
        unlink($file);

    }
	

	public function loginCookies () {

		# PRIHLASENIE POMOCOU COOKIES
		if (isset($_COOKIE['beid']) AND isset($_COOKIE['beuser']) AND isset($_COOKIE['bename']) AND isset($_COOKIE['beavatar']) AND isset($_COOKIE['bepassword']) AND isset($_COOKIE['beemail']) AND isset($_COOKIE['bekey'])) {
			# OK, DALEJ
			if (!empty($_COOKIE['beid']) AND !empty($_COOKIE['beuser']) AND !empty($_COOKIE['bename']) AND !empty($_COOKIE['beavatar']) AND !empty($_COOKIE['bepassword']) AND !empty($_COOKIE['beemail']) AND !empty($_COOKIE['bekey'])) {
				# OK, nacitame
				# $_COOKIE['bekey'] = user_key
				# dalsie doplnenie beid, beuser, bename, beavatar, bepassword, beemail -->> zatial sa to nepouziva

				$over_remember = $this->db->query('SELECT * FROM be_user' . $this->prefix . ' 
						WHERE user_key = "' . $this->db->real_escape_string($_COOKIE['bekey']) . '" LIMIT 1');
					if ($over_remember->num_rows == 1) {
						# OK

						# DUALNE PRIHLASENEI
						$this->userDualLogin($_COOKIE['beid']);

						$user = $over_remember->fetch_assoc();

						session_regenerate_id();

						$_SESSION['user_id'] = $this->db->real_escape_string($user['user_id']);
						$_SESSION['user'] = $this->db->real_escape_string($user['user_nick']);
						$_SESSION['name'] = $this->db->real_escape_string($user['user_meno']);
						$_SESSION['avatar'] = $this->db->real_escape_string($user['user_avatar']);
						$_SESSION['password'] = $this->db->real_escape_string($user['user_heslo']);
						$_SESSION['email'] = $this->db->real_escape_string($user['user_email']);
						$_SESSION['last_action'] = time();



						# PREDLZIME COOKIES
						$expire = time()+((3600*24)*10); // cokies ulozene na 10 dni
						setcookie('beid', $this->cryptPassword($_SESSION['user_id'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
						setcookie('beuser', $this->cryptPassword($_SESSION['user'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
						setcookie('bename', $this->cryptPassword($_SESSION['name'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
						setcookie('beavatar', $this->cryptPassword($_SESSION['avatar'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
						setcookie('bepassword', $this->cryptPassword($_SESSION['password'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);
						setcookie('beemail', $this->cryptPassword($_SESSION['email'] . $this->cryptPassword(CRYPT_REMEMBER)), $expire);	
						setcookie('bekey', $user['user_key'], $expire);

						if (empty($_SERVER["HTTP_REFERER"])) {
							
							# TEST COOKIES ODPOVED
							setcookie('odpoved','2', time()+2);
							$ok = 'r';
							header('Location: index.php?ok=' . urlencode($ok) . '&typ=2');
							exit();
						}
						else {
							setcookie('odpoved','2', time()+2);
							$ok = 'r';
							header("Location: ". $_SERVER["HTTP_REFERER"]);
            				exit();
						}


					}
					else {
						# TEST COOKIES ODPOVED
						setcookie('odpoved','1', time()+2);
						$odpoved = 'r';
						header('Location: index.php?error=' . urlencode($odpoved) . '&typ=1');
						exit();
					}	
			}
		}



	}

	private function salt ($chars = 10) {

        $characters = array("A","B","C","D","E","F","G","H","J","K","L","M",
                            "N","P","Q","R","S","T","U","V","W","X","Y","Z",
                            "1","2","3","4","5","6","7","8","9",
                            "a","b","c","d","e","f","g","h","j","k","l","m",
                            "n","p","q","r","s","t","u","v","w","x","y","z"
                      );
        $keys = array();

        while(count($keys) < $chars) {

            $x = mt_rand(0, count($characters)-1);
            if(!in_array($x, $keys)) {
               $keys[] = $x;
            }
        }
        $salt = '';
        foreach($keys as $key){
           $salt .= $characters[$key];
        }
        return $salt;

	}

	private function cryptPassword ($pass) {

		$password = hash("SHA512", $pass);

		return $password;

	}

	private function cryptRememberId ($id, $user, $date) {
		# LOGIN ON COOKIES

		$RemDate = hash("SHA512", $date);
		$RemId = hash("SHA512", $id);
		$RemUser = hash("SHA512", $user);
		$key = $this->cryptPassword($RemId . $RemUser . $RemDate);

		return $key;
	}

	public function jeprihlas() {

		if (isset($_SESSION['user_id']) and isset($_SESSION['user']) and isset($_SESSION['email']) and isset($_SESSION['password'])) {

			$vysl = $this->db->query('SELECT user_nick, user_heslo, user_email, user_id, user_salt    
												FROM be_user' . $this->prefix . ' 
						WHERE user_nick ="' . $this->db->real_escape_string($_SESSION['user']) . '" AND 
								user_id ="' . $this->db->real_escape_string(intval($_SESSION['user_id'])) . '" ');
            if ($vysl->num_rows == 1) {

            	$tlac = $vysl->fetch_assoc();

            	if (isset($_SESSION['dual']) AND $_SESSION['dual'] == $tlac['user_id']) {
            		$this->odhlas('Dualne prihlasenie, Sedenie uplinulo.');
            		die();
            	}

            	# OVERENIE HESLA
            	if ($_SESSION['password'] == htmlspecialchars($tlac['user_heslo'])) {

            		# LAST ACTION
					$_SESSION['last_action'] = time();

            		return true;

            	}
            	else {
                	return false;
            	}

            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

        if (file_exists('tmp/' . session_id())) {
        	echo 'existuje';
        }
        else {
        	echo 'neexistuje.';
        }

	}

	public function odhlas($error = '') {

		#USER LOGS VYPLNENIE CASU ODHLASENIA
		$e = $this->db->query('SELECT * FROM be_user_log' . $this->prefix . ' 
								WHERE user_log_logout = 0 AND user_log_ano = 1 AND user_id ="' . $this->db->real_escape_string($_SESSION['user_id']) . '" 
								ORDER BY user_log_date ASC ');
		if ($e->num_rows != FALSE) {
			$tl = $e->fetch_assoc();

			$c = $this->db->query('UPDATE be_user_log' . $this->prefix . ' SET 
									user_log_logout ="' . date('Y-m-d H:i:s') . '" 
								WHERE user_id ="' . $this->db->real_escape_string($tl['user_id']) . '" ');
								
		}


		$_SESSION['user_id'] = '';
		$_SESSION['user'] = '';
		$_SESSION['name'] = '';
		$_SESSION['avatar'] = '';
		$_SESSION['password'] = '';
		$_SESSION['email'] = '';
		$_SESSION['last_action'] = FALSE;

		# USER LOG ODHLASENIE


		# VYMAZANIE COOKIES SUBOROV ----> AUTOMATICKE PRIHLASENIE
		//$expire = time()-((3600*24)*10); // cokies ulozene na 10 dni
		$expire = time()-9999999999999; // c
		setcookie('beid', FALSE, $expire);
		setcookie('beuser', FALSE, $expire);
		setcookie('bename', FALSE, $expire);
		setcookie('beemail', FALSE, $expire);
		setcookie('beavatar', FALSE, $expire);
		setcookie('bepassword', FALSE, $expire);
		setcookie('bekey', FALSE, $expire);

		session_unset();
		session_destroy();

		header('Location: ' . URL_ADRESA . '?error=' . $error . '&typ=1');
		exit();

	}

	public function userDualLogin ($id, $hlaska = '') { 
		// dualne prihlaseni // 2x na inom pc | browsery


		$query = $this->db->query('SELECT * FROM be_user_log' . $this->prefix . ' 
										WHERE user_id ="' . $this->db->real_escape_string($id) . '" AND 
												user_log_ano =1 AND 
												user_log_logout =0 ');
		if ($query->num_rows >= '2') {

			$_SESSION['dual'] = $id;
			
			# ODHLASENIE
			$this->odhlas($hlaska);
			die();
		}

	}

	public function userLogs ($acept, $login, $password, $ip, $host, $referer, $useragent, $datum_logout, $user_id) {

		if (empty($datum_logout)) {
			//$datum_logout = date('Y-m-d H:i:s');
		}
		else {
			$datum_logout = $datum_logout;
		}

		$s = $this->db->query('INSERT INTO be_user_log' . $this->prefix . ' (user_log_id, user_log_ano, user_log_login, user_log_password, user_log_ip, user_log_host, user_log_referer, user_log_useragent, user_log_logout, user_id) 
			VALUES (NULL,
					"' . $this->db->real_escape_string($acept) . '",
					"' . $this->db->real_escape_string($login) . '",
					"' . $this->db->real_escape_string($password) . '",
					"' . $this->db->real_escape_string($ip) . '",
					"' . $this->db->real_escape_string($host) . '",
					"' . $this->db->real_escape_string($referer) . '",
					"' . $this->db->real_escape_string($useragent) . '",
					"' . $this->db->real_escape_string($datum_logout) . '",
					"' . $this->db->real_escape_string($user_id) . '") ');
		return;

	}

	public function logoutUnactive ($cas = '20') {
		# $cas = '1200';  // 20minut
		# test    15>47                 15:48+20=16>05
		# $cas v minutach 
		$cas = $cas * 60;
		if (isset($_SESSION['last_action'])) {
			
			if ((time() - $_SESSION['last_action']) >= $cas) {
			# logout
				$this->odhlas();
			}

		}
		
	}


	public function logoutAutomatic($cas = '20') {

		$cas = $cas * 60;

		$query = $this->db->query('SELECT * FROM be_user_log' . $this->prefix . ' 
										WHERE user_log_ano =1 AND 
												user_log_logout =0 ');
		if ($query->num_rows != FALSE) {

			while ($tlac = $query->fetch_assoc()) {

				if ((time() - strtotime($tlac['user_log_date'])) >= $cas) {
					
					$uloz = $this->db->query('UPDATE be_user_log' . $this->prefix . ' SET 
												user_log_logout ="' . $this->db->real_escape_string(date('Y-m-d H:i:s')) . '"  
												WHERE user_log_id ="' . $this->db->real_escape_string($tlac['user_log_id']) . '" ');


					# ODHLASNIE
					

				}
			}
			
			# ODHLASENIE
			//$this->odhlas($hlaska);
			//die();
		}

	}



	public function uzivateltabulka () {

		$uziv = $this->db->query('SELECT * FROM uzivatel' . $this->prefix . ' ORDER BY uzivatel_cas ASC ');
		if ($uziv->num_rows != FALSE) {
			# OK JE
			$por = 0;
			?>
			<div class="content1">
				<h1>Uzivatelia</h1>
				<table  cellpadding="0" cellspacing="0">
					<tr>
						<th>Poradie</th>
						<th>Avatar</th>
						<th>Meno</th>
						<th>Url</th>
						<th>Email</th>
						<th>Heslo</th>
						<th>Datum registracie</th>
						<th>Stav</th>
						<th>Level</th>
						<th>Spravca</th>
						<th>Nastav</th>
					</tr>
			<?php
			while ($uziv_t = $uziv->fetch_assoc()) {
				$por +=1;

				// 1 normal, 2 aktivovat treba, 3 zablokovany
				$farebnost = array('1' => ' ',
									'2' => ' style="color: green; " ',
									'3' => ' style="color: red; " ' );
				$napis = array('1' => 'Aktivny',
								'2' => 'Aktivacia',
								'3' => 'Blokovany' );


				?>
				<tr>
					<td><?php echo $por; ?></td>
					<td><img src="<?php echo ADRESA . 'uzivatel/mini/' . $uziv_t['uzivatel_cislo'] . '.jpg'; ?>" alt="<?php  echo $uziv_t['uzivatel_meno']; ?>" title="<?php echo $uziv_t['uzivatel_meno']; ?>" style="height: 60px;"></td>
					<td><?php echo $uziv_t['uzivatel_meno']; ?></td>
					<td><?php echo $uziv_t['uzivatel_url']; ?></td>
					<td><?php echo $uziv_t['uzivatel_email']; ?></td>
					<td><?php echo $uziv_t['uzivatel_heslo']; ?></td>
					<td><?php echo $uziv_t['uzivatel_cas']; ?></td>
					<td <?php echo $farebnost[$uziv_t['uzivatel_aktiv']]; ?> ><?php echo $napis[$uziv_t['uzivatel_aktiv']]; ?></td>
					<td><?php echo $uziv_t['uzivatel_level']; ?></td>
					<td><?php echo $uziv_t['spravca']; ?></td>
					<td><a href="?uzivatel=edit&id=<?php echo $uziv_t['uzivatel_id']; ?>"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/edit.png'); ?>" alt="zmen" title="zmen"></a></td>
				</tr>
				<?php

			}
			?>
				</table>
			</div>
			<?php	
		}
		else {
			# NEJE
			?>
			<div class="content1">
				<h1>Uzivatelia</h1>

				<h3>Ziadny uzivatel</h3>
			</div>
			<?php
		}


	}




	public function uzivateledit ($id) {


		if (isset($_POST['odoslizm'])) {
			# ULOZIME

			if (empty($_POST['zmeno']) OR empty($_POST['zemail']) OR empty($_POST['zheslo']) OR empty($_POST['zcas']) OR empty($_POST['zstav']) OR empty($_POST['zlevel']) OR empty($_POST['zspravca'])) {
				# PRAZDNE POLE
				$error = 'Pole nesmie zostat prezdne.';
				header('Location: ' . URL_ADRESA . '?uzivatel=edit&id=' . intval($id) . '&error=' . urlencode($error));
				exit();
			}
			else {
				# ULOZIME

				# URL MENO
				//include 'komponenty/url.php';
				include '../admin/komponenty/url.php';
				$meno_url10 = seo_url($_POST['zmeno']);

				$uloz = $this->db->query('UPDATE uzivatel' . $this->prefix . ' SET 
							uzivatel_meno ="' . $this->db->real_escape_string($_POST['zmeno']) . '",
							uzivatel_url ="' . $this->db->real_escape_string($meno_url10) . '",
							uzivatel_email ="' . $this->db->real_escape_string($_POST['zemail']) . '",
							uzivatel_heslo ="' . $this->db->real_escape_string(md5($_POST['zheslo'])) . '",
							uzivatel_cas ="' . $this->db->real_escape_string($_POST['zcas']) . '",
							uzivatel_aktiv ="' . $this->db->real_escape_string(intval($_POST['zstav'])) . '",
							uzivatel_level ="' . $this->db->real_escape_string(intval($_POST['zlevel'])) . '",
							spravca ="' . $this->db->real_escape_string(intval($_POST['zspravca'])) . '" 
								WHERE uzivatel_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

				$ok = 'Udaje boli ulozene.';
				header('Location: ' . URL_ADRESA . '?uzivatel&ok=' . urlencode($ok));
				exit();

			}

		}

		$existuje = $this->db->query('SELECT * FROM uzivatel' . $this->prefix . ' WHERE uzivatel_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($existuje->num_rows != FALSE) {
			# EXISTUJE
			$uz_t = $existuje->fetch_assoc();

			?>
			<div id="novemenu">
				<h3>Zmena uzivatela</h3>
				<form action="" method="post">
					<p>
						<img src="<?php echo ADRESA . htmlspecialchars('uzivatel/mini/' . $uz_t['uzivatel_cislo'] . '.jpg'); ?>" alt="<?php echo $uz_t['uzivatel_meno']; ?>" title="<?php echo $uz_t['uzivatel_meno']; ?>">
					</p>
					<p>
						<label>Meno:</label>
						<input type="text" name="zmeno" value="<?php echo $uz_t['uzivatel_meno']; ?>">
					</p>
					<p>
						<label>Email:</label>
						<input type="text" name="zemail" value="<?php echo $uz_t['uzivatel_email']; ?>">
					</p>
					<p>
						<label>Heslo:</label>
						<input type="text" name="zheslo" value="<?php echo $uz_t['uzivatel_heslo']; ?>">
					</p>
					<p>
						<label>Datum registracie:</label>
						<input type="text" name="zcas" value="<?php echo $uz_t['uzivatel_cas']; ?>">
					</p>
					<p>
						<label>Stav:</label>
						<input type="radio" name="zstav" <?php if ($uz_t['uzivatel_aktiv'] == '1') { echo 'checked="checked"'; }; ?> value="1"> Aktivny
						<input type="radio" name="zstav" <?php if ($uz_t['uzivatel_aktiv'] == '2') { echo 'checked="checked"'; }; ?> value="2"> Neaktivny
						<input type="radio" name="zstav" <?php if ($uz_t['uzivatel_aktiv'] == '3') { echo 'checked="checked"'; }; ?> value="3"> Zablokovany
					</p>
					<p>
						<label>Level:</label>
						<input type="radio" name="zlevel" <?php if ($uz_t['uzivatel_level'] == '1') { echo 'checked="checked"'; }; ?> value="1"> Normal
						<input type="radio" name="zlevel" <?php if ($uz_t['uzivatel_level'] == '2') { echo 'checked="checked"'; }; ?> value="2"> Admin
					</p>
					<p>
						<label>Spravca:</label>
						<input type="radio" name="zspravca" <?php if ($uz_t['spravca'] == '2') { echo 'checked="checked"'; }; ?> value="1"> Ano
						<input type="radio" name="zspravca" <?php if ($uz_t['spravca'] == '1') { echo 'checked="checked"'; }; ?> value="2"> Nie
					</p>
					<p>
						<input type="submit" name="odoslizm" value="zmenit">
					</p>
				</form>
			</div>
			<?php

		}
		else {
			# NENI TAKE
			$error = 'Fatalna chyba';
			header('Location: ' . URL_ADRESA . '?uzivatel&error=' . urlencode($error));
			exit();
		}		



	}







	public function createPhp ($h = 'tototoo') {
/*
		$b = $this->salt();
		$a = $this->cryptPassword('demo' . $b);

		$c = $this->cryptRememberId('1', 'ver4rs', '2013-08-13 09:28:29');

		echo $a . ' heslo</br>';
		echo $b . ' salt</br>';
		echo $c . ' key</br>';
*/
		
		/*
		$d = $this->cryptPassword($h . 'ndrtQ9J1BF');
		echo $d;*/
//echo $aa = $this->cryptPassword('tototoo' . 'ndrtQ9J1BF');

		

	}



}



?>