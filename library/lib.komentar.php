<?php

#LIB COMMENT


class komentar extends datum {

	protected $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function ukazkomentar ($id) {

		$stav =1;

		$query = $this->db->query('SELECT * 
							FROM be_comment' . $this->prefix . '
						WHERE text_id ="' . $this->db->real_escape_string(intval($id)) . '" AND 
							comment_stav ="' . $this->db->real_escape_string(intval($stav)) . '" 
						ORDER BY comment_date DESC ');
		if ($query->num_rows != FALSE) {
			# OK SU
			$pocet =0;
			?>
				
				<div class="post-fullwidth">
			<div id="comments">
				<h2><?php echo htmlspecialchars($this->lang['popkome']); ?> <span>(<?php echo htmlspecialchars($query->num_rows); ?>)</span></h2>
				
				<div id="threadComment">
					<ul class="listComment">
			<?php

			while ($komentar = $query->fetch_assoc()) {
				# TLACIME
				$pocet +=1;

				# CI JE UZIVATEL
				$uz = $this->db->query('SELECT * FROM be_user' . $this->prefix . ' 
										WHERE user_id ="' . $this->db->real_escape_string(intval($komentar['user_id'])) . '" ');
				if ($uz->num_rows != FALSE) {
					# OK JE UZIVATEL
					$uzivatel_komentar = $uz->fetch_assoc();
				}
				else {
					$uzivatel_komentar['user_id'] = '0';
				}

				# NACITAJ HTML KOMENTAR 
				include TEMPLATE_DESIGN . 'html_ukazkomentar.php';
	

			}

			?>
					</ul>
				</div>	
			</div></div><?php


		}
		else {
			# NIESU KOEMNTARE

			//echo 'ziadny komentar';
			?>
			<!--  <div class="wrap-main-content">
				<div class="container fullwidth" style="margin-top: -60px;">
					<div class="sixteen columns"> -->
						<div class="heading">&nbsp;</div>
						<div id="comments">
						
							<h2><?php echo htmlspecialchars($this->lang['popkome']); ?>  <span>(<?php echo htmlspecialchars($query->num_rows); ?>)</span></h2>
						
						</div>
					<!-- </div>
				</div>
			</div>  -->	
			<?php
		}


	}


	public function napiskomentar ($id) {

		$error = '';
		$ok = '';

		# SYSTEM TYP CAPTCHA
		$s = $this->db->query('SELECT system_id, system_captcha_typ, system_komentar FROM be_system' . $this->prefix . ' 
									ORDER BY system_id DESC LIMIT 1');
		if ($s->num_rows != FALSE) {

			$tlac = $s->fetch_assoc();
			$typ = $tlac['system_captcha_typ'];
			$kom = $tlac['system_komentar'];
		}
		else {
			$typ = '1';
			$kom = '2';
		}

		# ZISTIME CI JE HOST/ UZIVATEL REGISTROVANY
		if (isset($_POST['submit']) AND isset($_POST['captcha']) AND isset($_COOKIE['nahoda']) AND isset($id) AND isset($_POST['komeno']) AND isset($_POST['koemail']) AND isset($_POST['kotext'])) {

			$captcha = (isset($_POST['captcha'])) ? $_POST['captcha'] : '';
			$nahoda = (isset($_COOKIE['nahoda'])) ? $_COOKIE['nahoda'] : '';

			$komeno = (isset($_POST['komeno'])) ? $_POST['komeno'] : '';
			$koemail = (isset($_POST['koemail'])) ? $_POST['koemail'] : '';
			$kotext = (isset($_POST['kotext'])) ? $_POST['kotext'] : '';

			#POZOR
			$saltCaptcha = '35e4w5g464w6g46we4gwer4g6e4g';
			$nahodneCisloHash = md5(hash("SHA512", $captcha)) . md5(md5(hash("SHA512", $saltCaptcha)));
			
			if ($nahodneCisloHash == $nahoda) {

				setcookie('nahoda', '', time()-3600);
				
				if (!empty($komeno) AND !empty($koemail) AND !empty($kotext)) {

					$stav = 1; // povolene
					$parent_id = 0; // priame

					if (isset($_SESSION['user_id'])) {
						$user_id = $_SESSION['user_id'];
					}
					else {
						$user_id = '0';
					}

					# INSERT ARTICLE COMMENT +1
					$inse = $this->db->query('UPDATE be_text' . $this->prefix . ' SET 
												text_comment = text_comment +1 
											WHERE text_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
					
					#INSERT COMMENT
					$ins = $this->db->query('INSERT INTO be_comment' . $this->prefix . ' 
									(comment_id, text_id, comment_name, comment_email, comment_text, comment_date, comment_ip, comment_stav, parent_id, user_id) VALUES 
									(NULL,
									 "' . $this->db->real_escape_string($id) . '",
									 "' . $this->db->real_escape_string($komeno) . '",
									 "' . $this->db->real_escape_string($koemail) . '",
									 "' . $this->db->real_escape_string($kotext) . '",
									 "' . $this->db->real_escape_string(date('Y-m-d H:i:s')) . '",
									 "' . $this->db->real_escape_string($_SERVER['REMOTE_ADDR']) . '",
									 "' . $this->db->real_escape_string($stav) . '",
									 "' . $this->db->real_escape_string($parent_id) . '",
									 "' . $this->db->real_escape_string($user_id) . '") ');

					$ok = 'Komentar bol pridany.';
					//header("Location: " . URL_ADRESA . '?article=' . $_GET['article'] . '&ok=' . urlencode($ok));
					header("Location: " . $_SERVER['HTTP_REFERER'] . '&ok=' . urlencode($ok));
					exit();

				}
				else {
					$error = 'Musia byt vsetky polia vyplnene.';
					//header("Location: " . URL_ADRESA . '?article=' . $_GET['article'] . '&error=' . urlencode($error));
					header("Location: " . $_SERVER['HTTP_REFERER'] . '&error=' . urlencode($error));
					exit();
				}

			}
			else {
				$error = 'Zle opisany kod.';
				//header("Location: " . URL_ADRESA . '?article=' . $_GET['article'] . '&error=' . urlencode($error));
				header("Location: " . $_SERVER['HTTP_REFERER'] . '&error=' . urlencode($error));
				exit();
			}
		}

		# POVOLENIE KOMENTAROV, 
		if (/*$kom == '1' AND *//*isset($_SESSION['user_id']) AND isset($_SESSION['user']) AND isset($_SESSION['avatar']) AND isset($_SESSION['email'])*/1==1) {
			?>
			<div class="post-fullwidth">
				<div id="comments">
					<h2><?php echo htmlspecialchars($this->lang['napkom']); ?></h2>	
					
					<div id="formComment">

				<?php 
				if (isset($_GET['error'])) {
					?><p class="alert-error" ><?php echo htmlspecialchars(urldecode($_GET['error'])); ?></p><?php
				}
				if (isset($_GET['ok'])) {
					?><p class="alert-success"><?php echo htmlspecialchars(urldecode($_GET['ok'])); ?></p><?php
				}
				?>

						<form method="post" action="">

							<?php 
							# je prihlaseny
							if (isset($_SESSION['user_id']) AND isset($_SESSION['user']) AND isset($_SESSION['avatar']) AND isset($_SESSION['email'])) {

								if (file_exists(IMAGE_USER_MINI . $_SESSION['avatar'] . '.jpg')) {
									?><img id="avat" src="<?php echo URL_ADRESA . IMAGE_USER_MINI . htmlspecialchars($_SESSION['avatar'] . '.jpg'); ?>" alt="<?php echo htmlspecialchars($_SESSION['user']); ?>" title="<?php echo htmlspecialchars($_SESSION['user']); ?>"  width="60" height="60" class="thumbnail" style="margin-right: 20px;"><?php
								}
								else {
									?><img id="avat" src="<?php echo URL_ADRESA . htmlspecialchars('images/user.gif'); ?>" alt="<?php echo htmlspecialchars($_SESSION['user']); ?>" title="<?php echo htmlspecialchars($_SESSION['user']); ?>"  width="60" height="60" class="thumbnail" style="margin-right: 20px;"><?php
								}
								?>
								<p>
									<label>Meno:</label><span><?php echo htmlspecialchars($_SESSION['user']); ?></span> &nbsp; 
									<label>Email:</label><span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
								</p>
								<input type="hidden" name="komeno" value="<?php echo htmlspecialchars($_SESSION['user']); ?>">
								<input type="hidden" name="koemail" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
							<?php
							}
							else {
								# neprihlaseny, host
								?>
								<img id="avat" src="<?php echo URL_ADRESA . htmlspecialchars('images/user.gif'); ?>" alt="Host" title="Host" width="60" height="60" class="thumbnail" style="margin-right: 20px;">
								
								<input type="text" name="komeno" value="" placeholder="Meno">
								<input type="email" name="koemail" value="" placeholder="Email">
								<?php
							}
							?>

							<!-- <input type="url" name="url" value="" placeholder="Website">  -->
							<textarea name="kotext" placeholder="Napis text" rows="10" cols="40"></textarea>


							<img src="captcha.php?stav=<?php echo $typ; ?>">
							<input type="text" name="captcha" placeholder="">

							<input type="submit" name="submit" value="Pridaj">
						</form>
					</div>
						
				</div>	
			</div>	
			<?php
		}
		else {
			# MUSI SA ZAREGISTROVAT

			?>
			<!-- <div class="wrap-main-content">
				<div class="container fullwidth" style="margin-top: -60px;">
					<div class="sixteen columns">  -->
						<div class="heading">&nbsp;</div> 
						<div id="comments">
						
							<h2>Nieste prihlaseny.</h2>
							<div id="threadComment">Aby ste mohli reagovat na prispevok, je nutne sa prihlasit. <a href="<?php echo URL_ADRESA . ADMINISTRATION; ?>" target="_blank">Prihlasit sa</a>?</br>
				Ak este nemate ucet mozete sa zaregistrovat. <a href="<?php echo URL_ADRESA . ADMINISTRATION; ?>">Zaregistrovat sa</div>
						</div>
					<!-- </div>
				</div>
			</div> -->
			<?php
		}

	}











}















?>