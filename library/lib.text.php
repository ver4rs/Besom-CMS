<?php

# CONTENT

/**
* 
*/
class text extends komentar {

	protected $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function sirka ($menu) {
		$over = $this->db->query('SELECT menu_url, menu_nazov, menu_rodic, menu_typ, t.menu_id, m.menu_id, t.text_sirka 
									FROM be_menu' . $this->prefix . ' m 
										JOIN be_text' . $this->prefix . ' t ON m.menu_id = t.menu_id 
									WHERE menu_url ="' . $this->db->real_escape_string($menu) . '"
									LIMIT 1 ');
		if ($over->num_rows != FALSE) {
			$tlac = $over->fetch_assoc();

			$sirka = $tlac['text_sirka'];
		}
		else {
			$sirka = 'half';
		}
		return $sirka;
	}


	public function textzobraz ($url_menu = 'start', $pozicia = 'hore', $url_zacni) {

		# $_GET['menu'] = menu=sluzby
		# 1 Ci existuje v tabulke menu taky nazov
		# 2 ci v tabulke text existuje take id menu_id
		# 3 ci je to text alebo clanky + startovacia stranka
		# 4 text -> tak zobraz & clanok -> ci sekcia alebo kategoria -> ak sekcia tak vsetko -> tlacenie vsetkych
		# 5 ok- - end

		# POVOLENIE KOMENTAROV SYSTEM
		$kom = $this->db->query('SELECT system_komentar, system_id FROM be_system' . $this->prefix . ' 
											ORDER BY system_id ASC LIMIT 1');
		if ($kom->num_rows != FALSE) {
			$komentar = $kom->fetch_assoc();
		}

		/////////////////////////////////////////////////////////////////////////////////////
		////////////////////////  								////////////////////////////
		///////////////////       				STARTOVACIA STRANKA           //////////////////////
		//////////////////////////								////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////

		if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php' OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK ) {
			# START
			//echo 'start' . $this->lang['test'];

			$pozstart = 'start';
			$zisti = $this->db->query('SELECT menu_id, menu_typ, menu_rodic, menu_pozicia, menu_zobraz, menu_modul_id, menu_album FROM be_menu' . $this->prefix . ' WHERE menu_zobraz ="' . $this->db->real_escape_string($pozstart) . '" ');
			if ($zisti->num_rows != FALSE) {
				# OK
				$existuje_menu = $zisti->fetch_assoc();


				if ($existuje_menu['menu_album'] != '0') {
					# FUNCTION ALBUM
					$this->viewAlbum($existuje_menu['menu_album']);
					//echo 'album';
				}
					# ROZDELENIE CI JE TO TEXT PAGES ALEBO MODUL
				elseif ($existuje_menu['menu_modul_id'] == '0') { //nieje modul tak bude text 



					if ($existuje_menu['menu_typ'] == 'text') { // text
						# TEXT

						# CI SA NACHADZA DANY TEXT 
						$publik = $this->db->query('SELECT text_public, menu_id FROM be_text' . $this->prefix . ' 
										WHERE menu_id ="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" ');
						if ($publik->num_rows != FALSE) {
							# OK JE 
							$publik_t = $publik->fetch_assoc();

							# PUBLKOVANY ----  ZABLOKOVANY
							if ($publik_t['text_public'] == '1') {
								# OK
								
								# NACITAJ TEXT
								$text_nacitaj = $this->db->query('SELECT * FROM be_text' . $this->prefix . ' 
													WHERE menu_id ="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" ');
								if ($text_nacitaj->num_rows != FALSE) {
									
									$article = $text_nacitaj->fetch_assoc();

									////////////////////////////////////////////////////////////////////////////////////
									/////////#    VISITS  +1 FUNCTION   /////////////////////////////////////////////////
									$this->visitarticle($article['text_id'], '1'); // 6 expiration time hours //////////
									////////////////////////////////////////////////////////////////////////////////////


									# NACITAJ HTML CONTENT
									include TEMPLATE_DESIGN . 'html_content.php';

									# GALERIA
									if ($article['album_id'] != '0') {
										$photo = $this->db->query('SELECT * FROM be_photos' . $this->prefix . ' 
																	WHERE album_id ="' . $this->db->real_escape_string(intval($article['album_id'])) . '" ');
										if ($photo->num_rows != FALSE) {
											
											while($photos = $photo->fetch_assoc()) {
											
												include TEMPLATE_DESIGN . 'html_view_album_text.php';
											}
										}
									}


									# KOMENTARE 
									// ci su povolene
									if ($article['text_comment_ano'] == '1') {  // povolenie v clannku 

										if ($komentar['system_komentar'] == '1') {  // povolene v SYSTEM
												# POVOLENE

												# EXTERNY .LIB CLASS KOMENTAR
												parent::ukazkomentar($article['text_id']);
												parent::napiskomentar($article['text_id']);

										}
										else {
											// zakazane v systeme, ale je prihlaseny
											if (isset($_SESSION['user_id']) AND isset($_SESSION['user']) AND isset($_SESSION['name']) AND isset($_SESSION['avatar']) AND isset($_SESSION['email']) AND isset($_SESSION['last_action'])) {
													
													# EXTERNY .LIB CLASS KOMENTAR
													parent::ukazkomentar($article['text_id']);
													parent::napiskomentar($article['text_id']);
											}
											else {
											 	# NACITAJ HTML SPRAVA NA PRIHLASENIE/ REGISTER
											 	include TEMPLATE_DESIGN . 'html_sprava.php';
											}	
										}
									}
									# END  *** 2 je zakazane


								}// else nieje naco 

							}
							else {
								# ZABLOKOVANY
								# Stranka bola zablokovana
								//echo $this->lang['blok'];
								?>
								<div class="wrap-main-content">
									<div class="container fullwidth">
										<div class="sixteen columns">
											<h4><?php echo $this->lang['blok']; ?></h4>
										</div>
									</div>
								</div>
								<?php
							}

						}
						else {
							# NIEJE V TAB TEXT
							# VYSTAVBA
							//echo $this->lang['vystavba'];
							?>
							<div class="wrap-main-content">
								<div class="container fullwidth">
									<div class="sixteen columns">
										<h4><?php echo $this->lang['vystavba']; ?></h4>
									</div>
								</div>
							</div>
							<?php
						}

					}
					elseif ($existuje_menu['menu_typ'] == 'clanok') { # CLANOK
						# CLANOK

						//////////////////////////////////////////////////////////////////////////////////////////////////////
						//////////   VYBER START ZOBRAZENIE NAJNOVSICH CLANOK ALEBO ZOBRAZENIE NORMAL SEKCIA-KATEGORIA    ////
						/////////////////////////////////////////////////////////////////////////////////////////////////////

						#   top -> zobrazenie najnovsich clankov z kazdej sekcie 
						#   blog -> nomrmalne zobrazenie podla sekcie
						$star_c = $this->db->query('SELECT system_id, system_start_clanok FROM be_system' . $this->prefix . '  
													ORDER BY system_id ASC LIMIT 1');
						if ($star_c->num_rows != FALSE) {

							$startovacia_t = $star_c->fetch_assoc();

							$startClanok = $startovacia_t['system_start_clanok'];
						}
						else {
							$startClanok = 'top';
						}



						# GET STRANA
						if (isset($_GET['page']) AND $_GET['page'] != '0') {
							$page = $_GET['page'];
						}
						else {
							$page =1;
						}

						$limit = 10;
						$range = 5;

						$start = ($page - 1)* $limit;


						//////////////////////////////////////////////////////////////////
						//				            	ROZDELENIE					    //
						//////////////////////////////////////////////////////////////////

						if ($startClanok == 'top') {
							# TOP / najnovsie zobrazene clanky

							$public =1;


							# TLACENIE CLANKOV
							$hlad_clanok = $this->db->query('SELECT SQL_CALC_FOUND_ROWS  *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
														FROM be_text' . $this->prefix . ' t 
														JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
														JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
										WHERE menu_pozicia="' . $this->db->real_escape_string($pozicia) . '" AND 
											menu_typ="' . $this->db->real_escape_string($existuje_menu['menu_typ']) . '" AND 
											text_public ="' . $this->db->real_escape_string($public) . '" 
										ORDER BY text_datum ASC LIMIT ' . $start . ', ' . $limit . ' ');
							if ($hlad_clanok->num_rows != FALSE) {
								# OK SU

								while ($article = $hlad_clanok->fetch_assoc()) {

									include TEMPLATE_DESIGN . 'html_article.php';

								}

								$this->strankovanie($limit, $range, $url_zacni, $url_menu);

							}
							else {
								# ERROR
								# ZIADNY CLANOK
								//echo $this->lang['nosekcia'];
								?>
								<div class="wrap-main-content">
									<div class="container fullwidth">
										<div class="sixteen columns">
											<h4><?php echo $this->lang['nosekcia']; ?></h4>
										</div>
									</div>
								</div>
								<?php
							}


						}
						else {
							# blog -> podla sekcie a kategorie zobrazene


							/////////////////////////////
							/// CI KAT ALEBO SEKCIA  ///
							///////////////////////////




							/////////////////
							// SEKCIA  /////
							///////////////
							if ($existuje_menu['menu_rodic'] == '0' AND $existuje_menu['menu_pozicia'] == $pozicia) {

								$public =1;


								# TLACENIE CLANKOV
								$hlad_clanok = $this->db->query('SELECT SQL_CALC_FOUND_ROWS  *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
															FROM be_text' . $this->prefix . ' t 
															JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
															JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
											WHERE menu_rodic="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" AND 
												menu_pozicia="' . $this->db->real_escape_string($pozicia) . '" AND 
												menu_typ="' . $this->db->real_escape_string($existuje_menu['menu_typ']) . '" AND 
												text_public ="' . $this->db->real_escape_string($public) . '" 
											ORDER BY text_datum ASC LIMIT ' . $start . ', ' . $limit . ' ');
								if ($hlad_clanok->num_rows != FALSE) {
									# OK SU

									while ($article = $hlad_clanok->fetch_assoc()) {

										include TEMPLATE_DESIGN . 'html_article.php';

									}

									$this->strankovanie($limit, $range, $url_zacni, $url_menu);

								}
								else {
									# ERROR
									# ZIADNY CLANOK
									//echo $this->lang['nosekcia'];
									?>
									<div class="wrap-main-content">
										<div class="container fullwidth">
											<div class="sixteen columns">
												<h4><?php echo $this->lang['nosekcia']; ?></h4>
											</div>
										</div>
									</div>
									<?php
								}

							}
							elseif ($existuje_menu['menu_rodic'] != '0' AND $existuje_menu['menu_pozicia'] == $pozicia) {
								/////////////////
								// KATEGORIA  //
								///////////////

								$public =1;

								# TLACENIE CLANKOV
								$hlad_clanok = $this->db->query('SELECT SQL_CALC_FOUND_ROWS *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
															FROM be_text' . $this->prefix . ' t 
															JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
															JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
											WHERE t.menu_id="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" AND 
												menu_pozicia="' . $this->db->real_escape_string($pozicia) . '" AND 
												menu_typ="' . $this->db->real_escape_string($existuje_menu['menu_typ']) . '" AND 
												text_public ="' . $this->db->real_escape_string($public) . '" 
											ORDER BY text_datum ASC LIMIT ' . $start . ', ' . $limit . ' ');
								if ($hlad_clanok->num_rows != FALSE) {
									# OK SU
									
									# DATUM A CAS

									while ($article = $hlad_clanok->fetch_assoc()) {

										include TEMPLATE_DESIGN . 'html_article.php';

									}
									
									$this->strankovanie($limit, $range, $url_zacni, $url_menu);

								}
								else {
									# ERROR
									# ZIADNY CLANOK
									echo $this->lang['nokategoria'];
									?>
									<div class="wrap-main-content">
										<div class="container fullwidth">
											<div class="sixteen columns">
												<h4><?php echo $this->lang['nokategoria']; ?></h4>
											</div>
										</div>
									</div>
									<?php
								}


							}
							else {
								# ERROR
								# chyba zdrojova chyba, kontaktujte spravcu
								//echo $this->lang['zdrojchyba'];
								?>
								<div class="wrap-main-content">
									<div class="container fullwidth">
										<div class="sixteen columns">
											<h4><?php echo $this->lang['zdrojchyba']; ?></h4>
										</div>
									</div>
								</div>
								<?php
							}

							////////////////////////////////////////////////////
							///////      END ARTICLE SEKCIA A KATEGORIA   //////
							////////////////////////////////////////////////////



						}
						

						
					}
					else {
						//////////////////////
						//////   URL   //////
						////////////////////

						$nacitaj = $this->db->query('SELECT menu_id, menu_nazov, menu_url, menu_target, menu_typ FROM be_menu' . $this->prefix . ' 
										WHERE menu_typ ="' . $this->db->real_escape_string($existuje_menu['menu_typ']) . '" AND 
										menu_id ="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" ');
						if ($nacitaj->num_rows != FALSE) {
							# OK JE

							$nacitaj_url = $nacitaj->fetch_assoc();

							$this->redirect($nacitaj_url['menu_url']);
							//header('Location: ' . $nacitaj_url['menu_url']);
							//exit();

						}
						else {
							# ERROR
							# Nepodarilo sa nacitat externu adresu
							//echo $this->lang['noexteradresa'];
							?>
							<div class="wrap-main-content">
								<div class="container fullwidth">
									<div class="sixteen columns">
										<h4><?php echo $this->lang['noexteradresa']; ?></h4>
									</div>
								</div>
							</div>
							<?php
						}

					}


				}
				else {
					//echo 'modul';
					?>
							<!-- <div class="wrap-main-content">
								<div class="container fullwidth">
									<div class="sixteen columns">  -->
										<?php
										// $existuje_menu['menu_modul_id']
										$nacM = $this->db->query('SELECT * FROM be_modul' . $this->prefix . ' 
													WHERE modul_stav =1 AND 
													modul_id ="' . $this->db->real_escape_string($existuje_menu['menu_modul_id']) . '" LIMIT 1 ');
										if ($nacM->num_rows != FALSE) {
											$tlacModul = $nacM->fetch_assoc();

											# NACITANIE CLASS
											if (file_exists('modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.lib.php')) {

												if (class_exists($tlacModul['modul_subor'])) {
													include 'modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.start.php';
												}
												else {
													include 'modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.lib.php';

													# NACITAME START
													if (file_exists('modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.start.php')) {
														include 'modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.start.php';
													}
												}

												
												
												//unset($tlacModul['modul_subor']);
											}

										}
										else {
											# NENI POVOLENY
											?><h4><?php echo htmlspecialchars($this->lang['conmodulno']); ?></h4><?php
										}


										?>
									<!-- </div>
								</div>
							</div>  -->
					<?php
				}

			}
			else {
				# ERROR
				# FATAL
				//echo $this->lang['fatal'];
				?>
				<div class="wrap-main-content">
					<div class="container fullwidth">
						<div class="sixteen columns">
							<h4><?php echo $this->lang['fatal']; ?></h4>
						</div>
					</div>
				</div>
				<?php
			}

		}
		else {

			$exist_menu = $this->db->query('SELECT menu_id, menu_typ, menu_rodic, menu_pozicia, menu_modul_id,menu_album FROM be_menu' . $this->prefix . ' WHERE menu_url ="' . $this->db->real_escape_string($url_menu) . '" ');
			if ($exist_menu->num_rows != FALSE) {

				$existuje_menu = $exist_menu->fetch_assoc();

					
				if ($existuje_menu['menu_album'] != '0') {
					$this->viewAlbum($existuje_menu['menu_album']);
					//echo 'album';
				}
					# ROZDELENIE CI JE TO TEXT PAGES ALEBO MODUL
				elseif ($existuje_menu['menu_modul_id'] == '0') { //nieje modul tak bude text 
					

					# TEXT
					if ($existuje_menu['menu_typ'] == 'text') {
						# TEXT

						# CI SA NACHADZA DANY TEXT 
						$publik = $this->db->query('SELECT text_public, menu_id FROM be_text' . $this->prefix . ' 
										WHERE menu_id ="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" ');
						if ($publik->num_rows != FALSE) {
							# OK JE 
							$publik_t = $publik->fetch_assoc();

							# PUBLKOVANY ----  ZABLOKOVANY
							if ($publik_t['text_public'] == '1') {
								# OK
								
								# NACITAJ TEXT
								$text_nacitaj = $this->db->query('SELECT * FROM be_text' . $this->prefix . ' 
													WHERE menu_id ="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" ');
								if ($text_nacitaj->num_rows != FALSE) {
									
									$article = $text_nacitaj->fetch_assoc();


									////////////////////////////////////////////////////////////////////////////////////
									/////////#    VISITS +1 FUNCTION   /////////////////////////////////////////////////
									$this->visitarticle($article['text_id'], '1'); // 6 expiration time        //////////
									////////////////////////////////////////////////////////////////////////////////////


									# NACITAJ HTML CONTENT
									include TEMPLATE_DESIGN . 'html_content.php';

									# GALERIA
									if ($article['album_id'] != '0') {
										$photo = $this->db->query('SELECT * FROM be_photos' . $this->prefix . ' 
																	WHERE album_id ="' . $this->db->real_escape_string(intval($fullArticle['album_id'])) . '" ');
										if ($photo->num_rows != FALSE) {
											
											while($photos = $photo->fetch_assoc()) {
											
												include TEMPLATE_DESIGN . 'html_view_album_text.php';
											}
										}
									}


									# KOMENTARE 
									// ci su povolene
									if ($article['text_comment_ano'] == '1') {  // povolenie v clannku 

										if ($komentar['system_komentar'] == '1') {  // povolene v SYSTEM
												# POVOLENE

												# EXTERNY .LIB CLASS KOMENTAR
												parent::ukazkomentar($article['text_id']);
												parent::napiskomentar($article['text_id']);

										}
										else {
											// zakazane v systeme, ale je prihlaseny
											if (isset($_SESSION['user_id']) AND isset($_SESSION['user']) AND isset($_SESSION['name']) AND isset($_SESSION['avatar']) AND isset($_SESSION['email']) AND isset($_SESSION['last_action'])) {
													
													# EXTERNY .LIB CLASS KOMENTAR
													parent::ukazkomentar($article['text_id']);
													parent::napiskomentar($article['text_id']);
											}
											else {
											 	# NACITAJ HTML SPRAVA NA PRIHLASENIE/ REGISTER
											 	include TEMPLATE_DESIGN . 'html_sprava.php';
											}	
										}
									}
									# END  *** 2 je zakazane


								}// else nieje naco 

							}
							else {
								# ZABLOKOVANY
								# Stranka bola zablokovana
								//echo $this->lang['blok'];
								?>
								<div class="wrap-main-content">
									<div class="container fullwidth">
										<div class="sixteen columns">
											<h4><?php echo $this->lang['blok']; ?></h4>
										</div>
									</div>
								</div>
								<?php
							}

						}
						else {
							# NIEJE V TAB TEXT
							# VYSTAVBA
							//echo $this->lang['vystavba'];
							?>
							<div class="wrap-main-content">
								<div class="container fullwidth">
									<div class="sixteen columns">
										<h4><?php echo $this->lang['vystavba']; ?></h4>
									</div>
								</div>
							</div>
							<?php
						}


					}
					elseif ($existuje_menu['menu_typ'] == 'clanok') {
						# CLANOK
						
						/////////////////////////////
						/// CI KAT ALEBO SEKCIA  ///
						///////////////////////////


						# GET STRANA
						if (isset($_GET['page']) AND $_GET['page'] != '0') {
							$page = $_GET['page'];
						}
						else {
							$page =1;
						}

						$limit = 2;
						$range = 5;

						$start = ($page - 1)* $limit;

						/////////////////
						// SEKCIA  /////
						///////////////
						if ($existuje_menu['menu_rodic'] == '0' AND $existuje_menu['menu_pozicia'] == $pozicia) {

							$public =1;


							# TLACENIE CLANKOV
							$hlad_clanok = $this->db->query('SELECT SQL_CALC_FOUND_ROWS  *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
														FROM be_text' . $this->prefix . ' t 
														JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
														JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
										WHERE menu_rodic="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" AND 
											menu_pozicia="' . $this->db->real_escape_string($pozicia) . '" AND 
											menu_typ="' . $this->db->real_escape_string($existuje_menu['menu_typ']) . '" AND 
											text_public ="' . $this->db->real_escape_string($public) . '" 
										ORDER BY text_datum ASC LIMIT ' . $start . ', ' . $limit . ' ');
							if ($hlad_clanok->num_rows != FALSE) {
								# OK SU

								while ($article = $hlad_clanok->fetch_assoc()) {
									
									include TEMPLATE_DESIGN . 'html_article.php';

								}

								$this->strankovanie($limit, $range, $url_zacni, $url_menu);

							}
							else {
								# ERROR
								# ZIADNY CLANOK
								//echo $this->lang['nosekcia'];
								?>
								<div class="wrap-main-content">
									<div class="container fullwidth">
										<div class="sixteen columns">
											<h4><?php echo $this->lang['nosekcia']; ?></h4>
										</div>
									</div>
								</div>
								<?php
							}

						}
						elseif ($existuje_menu['menu_rodic'] != '0' AND $existuje_menu['menu_pozicia'] == $pozicia) {
							/////////////////
							// KATEGORIA  //
							///////////////

							$public =1;

							# TLACENIE CLANKOV
							$hlad_clanok = $this->db->query('SELECT SQL_CALC_FOUND_ROWS *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
														FROM be_text' . $this->prefix . ' t 
														JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
														JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
										WHERE t.menu_id="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" AND 
											menu_pozicia="' . $this->db->real_escape_string($pozicia) . '" AND 
											menu_typ="' . $this->db->real_escape_string($existuje_menu['menu_typ']) . '" AND 
											text_public ="' . $this->db->real_escape_string($public) . '" 
										ORDER BY text_datum ASC LIMIT ' . $start . ', ' . $limit . ' ');
							if ($hlad_clanok->num_rows != FALSE) {
								# OK SU

								# DATUM A CAS

								while ($article = $hlad_clanok->fetch_assoc()) {

									include TEMPLATE_DESIGN . 'html_article.php';

								}
								
								$this->strankovanie($limit, $range, $url_zacni, $url_menu);

							}
							else {
								# ERROR
								# ZIADNY CLANOK
								//echo $this->lang['nokategoria'];
								?>
								<div class="wrap-main-content">
									<div class="container fullwidth">
										<div class="sixteen columns">
											<h4><?php echo $this->lang['nokategoria']; ?></h4>
										</div>
									</div>
								</div>
								<?php
							}


						}
						else {
							# ERROR
							# chyba zdrojova chyba, kontaktujte spravcu
							//echo $this->lang['zdrojchyba'];
							?>
							<div class="wrap-main-content">
								<div class="container fullwidth">
									<div class="sixteen columns">
										<h4><?php echo $this->lang['zdrojchyba']; ?></h4>
									</div>
								</div>
							</div>
							<?php
						}



					}
					else {
						//////////////////////
						//////   URL   //////
						////////////////////

						$nacitaj = $this->db->query('SELECT menu_id, menu_nazov, menu_url, menu_target, menu_typ FROM be_menu' . $this->prefix . ' 
										WHERE menu_typ ="' . $this->db->real_escape_string($existuje_menu['menu_typ']) . '" AND 
										menu_id ="' . $this->db->real_escape_string($existuje_menu['menu_id']) . '" ');
						if ($nacitaj->num_rows != FALSE) {
							# OK JE

							$nacitaj_url = $nacitaj->fetch_assoc();

							$this->redirect($nacitaj_url['menu_url']);
							//header('Location: ' . $nacitaj_url['menu_url']);
							//exit();

						}
						else {
							# ERROR
							# Nepodarilo sa nacitat externu adresu
							//echo $this->lang['noexteradresa'];
							?>
							<div class="wrap-main-content">
								<div class="container fullwidth">
									<div class="sixteen columns">
										<h4><?php echo $this->lang['noexteradresa']; ?></h4>
									</div>
								</div>
							</div>
							<?php
						}

					}

				}
				else { // modul
					# ZISTIME CI EXISTUJE AK HEJ TAK HO NACITAME
					# A CI JE POVOLENE
					# AK HEJ ZOBRAZIME
					//echo 'modul';
					?>
							<!-- <div class="wrap-main-content">
								<div class="container fullwidth">
									<div class="sixteen columns">  -->
										<?php
										// $existuje_menu['menu_modul_id']
										$nacM = $this->db->query('SELECT * FROM be_modul' . $this->prefix . ' 
													WHERE modul_stav =1 AND 
													modul_id ="' . $this->db->real_escape_string($existuje_menu['menu_modul_id']) . '" LIMIT 1 ');
										if ($nacM->num_rows != FALSE) {
											$tlacModul = $nacM->fetch_assoc();

											# NACITANIE CLASS
											if (file_exists('modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.lib.php')) {

												if (class_exists($tlacModul['modul_subor'])) {
													include 'modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.start.php';
												}
												else {
													include 'modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.lib.php';

													# NACITAME START
													if (file_exists('modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.start.php')) {
														include 'modul/' . $tlacModul['modul_subor'] . '/' . $tlacModul['modul_subor'] . '.start.php';
													}
												}

												
												
												//unset($tlacModul['modul_subor']);
											}

										}
										else {
											# NENI POVOLENY
											?><h4><?php echo htmlspecialchars($this->lang['conmodulno']); ?></h4><?php
										}


										?>
									<!-- </div>
								</div>
							</div>  -->
					<?php
				}

			}
			else {
				# TAKE MENU NENI, NEEXISTUJE
				# error chyba fatalna
				//echo $this->lang['fatal'];
				?>
				<div class="wrap-main-content">
					<div class="container fullwidth">
						<div class="sixteen columns">
							<h4><?php echo $this->lang['fatal']; ?></h4>
						</div>
					</div>
				</div>
				<?php
			}
		}

	}

	public function viewAlbum ($id) {
		$query = $this->db->query('SELECT * FROM be_album' . $this->prefix . ' 
									WHERE album_id ="' . $this->db->real_escape_string(intval($id)) . '" AND 
										album_stav =1 ');
		if ($query->num_rows != FALSE) {
			$album = $query->fetch_assoc(); // print album

			# NACITANIE photos
			$photo = $this->db->query('SELECT * FROM be_photos' . $this->prefix . ' 
										WHERE album_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
			if ($photo->num_rows != FALSE) {
				
				// template -> zaciatok
				
				//DEFAULT 
				?>
				<section id="examples" class="section examples-section">
					<h3 style="clear: both;"><?php echo htmlspecialchars($album['album_nazov']); ?></h3>

					<div class="image-row">
					<div class="image-set">
				<?php
				
				while ($photos = $photo->fetch_assoc()) {
					
					# INCLUDE HTML TEMPLATE
					include TEMPLATE_DESIGN . 'html_viewalbum.php';
				}
				
				?>
					</div>
					</div>
				</section>
				<?php
				//END DEFA
				
				// template -> koniec

			}

		}
	}


	public function viewAlbumText ($id) {
		$query = $this->db->query('SELECT * FROM be_album' . $this->prefix . ' 
									WHERE album_id ="' . $this->db->real_escape_string(intval($id)) . '" AND 
										album_stav =1 ');
		if ($query->num_rows != FALSE) {
			$row = $query->fetch_assoc(); // print album

			# NACITANIE photos
			$photo = $this->db->query('SELECT * FROM be_photos' . $this->prefix . ' 
										WHERE album_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
			if ($photo->num_rows != FALSE) {
				
				// template -> zaciatok

				while ($photos = $photo->fetch_assoc()) {
					
					# INCLUDE HTML TEMPLATE
					include TEMPLATE_DESIGN . 'html_view_album_text.php';
				}
				
				// template -> koniec

			}

		}
	}



	public function datum ($datum, $typ) {

		if ($typ == '1') {
			# 15. jula 2013 21:35

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);

			$tlac = $datum_den . '. ' . $datum_mesiac . ' ' . $datum_rok = date('Y', strtotime($datum)) . ' ' . $cas_hodina = date('H', strtotime($datum)) . ':' . $cas_minuta = date('i', strtotime($datum));
			
			return $tlac;
		}

		if ($typ == '2') {
			# 15. jula 2013

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);

			$tlac = $datum_den . '. ' . $datum_mesiac . ' ' . $datum_rok = date('Y', strtotime($datum));
			
			return $tlac;
		}

		if ($typ == '3') {
			# jul 15, 2014

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac1 = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);
			$datum_mesiac = strtoupper($datum_mesiac1);

			$datum_rok = date('Y', strtotime($datum));

			//$tlac = $datum_den . '. ' . $datum_mesiac . ' ' . $datum_rok = date('Y', strtotime($datum));
			$tlac = $datum_mesiac . ' ' . $datum_den . ', ' . $datum_rok;
			
			return $tlac;
		}

		if ($typ == '4') {
			# 07:34

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac1 = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);
			$datum_mesiac = strtoupper($datum_mesiac1);

			$datum_rok = date('Y', strtotime($datum));

			$tlac = $cas_hodina = date('H', strtotime($datum)) . ':' . $cas_minuta = date('i', strtotime($datum));
			
			return $tlac;
		}

	}


	public function strankovanie ($limit = '10', $range = '5', $url_zacni, $url_menu) { // limit pocet clankov na stranu,    range pocet stran na stranu
				// url_menu    menu_url 

		# POCET vYSLEDKOV ,  POCET CLANKOV
		$poc = $this->db->query('SELECT FOUND_ROWS() ');
		$pocet = $poc->fetch_array();
		$numrows = $pocet[0]; // pocet celkovy clankou

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
		<div id="pagination" class="clearfix">
			<?php
			if ($page != '1') {
				$pagePrev = $page -1;
				?>
				<div class="page-prev">
					<?php
					if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php' OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK) {
							# DOMOV    HOME
						?><a class="button" href="?page=<?php echo $pagePrev; ?>"><?php echo $this->lang['pageprev']; ?></a><?php
					}
					else {
						?><a class="button" href="<?php echo htmlspecialchars($url_zacni . $url_menu . '&page=' . $pagePrev); ?>"><?php echo $this->lang['pageprev']; ?></a><?php
					}
					?>
				</div>
				<?php
			}
			else {
				?><div class="page-prev"><a href="#" style="color: transparent; border: 1px solid transparent; background: transparent" class="button"><?php echo $this->lang['pageprev']; ?></a></div><?php
			}
			?>
			
			<div class="page-list">
				<?php
				//for ($i=1; $i <= $numofpages ; $i++) {
				for ($i= $maxrange; $i <= $minrange ; $i++) {

					//if ($zaciatok > 0 AND $zaciatok <= $numofpages) { // range

						if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php' OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK) {
								# DOMOV    HOME
							?><a <?php if($i == $page) { echo ' style=" border: 1px solid #60a2a8; " '; } ?> href="?page=<?php echo $i; ?>"><?php echo $i; ?></a><?php
						}
						else {
							?><a <?php if($i == $page) { echo ' style=" border: 1px solid #60a2a8; " '; } ?> href="<?php echo htmlspecialchars($url_zacni . $url_menu . '&page=' . $i); ?>"><?php echo $i; ?></a><?php
						}

					//}

				}
				?>
			</div>
			<?php
			if ($page != $numofpages) {
				?>
				<div class="page-next">
					<?php
					if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php' OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK) {
							# DOMOV    HOME
						?><a class="button" href="?page=<?php echo $page; ?>"><?php echo $this->lang['pagenext']; ?></a><?php
					}
					else {
						?><a class="button" href="<?php echo htmlspecialchars($url_zacni . $url_menu . '&page=' . $page); ?>"><?php echo $this->lang['pagenext']; ?></a><?php
					}
					?>
				</div>
				<?php
			}
			?>			

		</div>
		<?php


	}



	public function getarticle ($url_article) {

		$exist = $this->db->query('SELECT text_id, text_url, text_public FROM be_text' . $this->prefix . ' 
				WHERE text_url ="' . $this->db->real_escape_string($url_article) . '" ');
		if ($exist->num_rows != FALSE) {
			# OK

			$public = $exist->fetch_assoc();
			# JE PUBLIKOVANY
			if ($public['text_public'] == '1') { // PUBLIKOVANY
				# PUBLIKOVANY

				# VISITS +1 FUNCTION
				$this->visitarticle($public['text_id'], '1'); // 6 expiration time
				
				
				# POVOLENIE KOMENTAROV SYSTEM
				$kom = $this->db->query('SELECT system_komentar, system_id FROM be_system' . $this->prefix . ' 
											ORDER BY system_id ASC LIMIT 1');
				if ($kom->num_rows != FALSE) {
					$komentar = $kom->fetch_assoc();
				}

				# NACITAJ
				$nacitaj = $this->db->query('SELECT *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id, u.user_avatar  
									FROM be_text' . $this->prefix . ' t 
										JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
										JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									WHERE text_id ="' . $this->db->real_escape_string($public['text_id']) . '" ');
				if ($nacitaj->num_rows != FALSE) {
					# JE NACITANY

					$fullArticle = $nacitaj->fetch_assoc();

					# HTML
					include TEMPLATE_DESIGN . 'html_fullArticle.php';

					# GALERIA
					if ($fullArticle['album_id'] != '0') {

						$photo = $this->db->query('SELECT * FROM be_photos' . $this->prefix . ' 
													WHERE album_id ="' . $this->db->real_escape_string(intval($fullArticle['album_id'])) . '" ');
						if ($photo->num_rows != FALSE) {
							$photos = $photo->fetch_assoc();
							
							include TEMPLATE_DESIGN . 'html_view_album_text.php';
						}

					}

					# KOMENTARE 
					// ci su povolene
					if ($fullArticle['text_comment_ano'] == '1') {  // povolenie v clannku 

						if ($komentar['system_komentar'] == '1') {  // povolene v SYSTEM
								# POVOLENE

								# EXTERNY .LIB CLASS KOMENTAR
								parent::ukazkomentar($fullArticle['text_id']);
								parent::napiskomentar($fullArticle['text_id']);

						}
						else {
							// zakazane v systeme, ale je prihlaseny
							if (isset($_SESSION['user_id']) AND isset($_SESSION['user']) AND isset($_SESSION['name']) AND isset($_SESSION['avatar']) AND isset($_SESSION['email']) AND isset($_SESSION['last_action'])) {
												
									# EXTERNY .LIB CLASS KOMENTAR
									parent::ukazkomentar($fullArticle['text_id']);
									parent::napiskomentar($fullArticle['text_id']);
							}
							else {
							 	# NACITAJ HTML SPRAVA NA PRIHLASENIE/ REGISTER
							 	include TEMPLATE_DESIGN . 'html_sprava.php';
							}	
						}
					}
					# END  *** 2 je zakazane


				}	

				else {
					# NO ERROR
					# FATAL
					//echo $this->lang['fatal'];
					?>
					<div class="wrap-main-content">
						<div class="container fullwidth">
							<div class="sixteen columns">
								<h4><?php echo $this->lang['fatal']; ?></h4>
							</div>
						</div>
					</div>
					<?php
				}


			}
			else {
				# ZABLOKOVANY
				//echo $this->lang['blok'];
				?>
				<div class="wrap-main-content">
					<div class="container fullwidth">
						<div class="sixteen columns">
							<h4><?php echo $this->lang['blok']; ?></h4>
						</div>
					</div>
				</div>
				<?php
			}



		}
		else {
			# ERROR
			# NEEXISTUJE TAKY CLANOK
			//echo $this->lang['fatal'];
			?>
			<div class="wrap-main-content">
				<div class="container fullwidth">
					<div class="sixteen columns">
						<h4><?php echo $this->lang['fatal']; ?></h4>
					</div>
				</div>
			</div>
			<?php
		}



	}


	public function textgethladaj ($vyraz, $url_zacni) {

		# GET STRANA
		if (isset($_GET['page']) AND $_GET['page'] != '0') {
			$page = $_GET['page'];
		}
		else {
			$page =1;
		}

		$limit = 10;
		$range = 5;

		$start = ($page - 1)* $limit;

		$public =1;

		$hladaj_text = $this->db->query('SELECT SQL_CALC_FOUND_ROWS *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
											FROM be_text' . $this->prefix . ' t 
											JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
											JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
						WHERE MATCH (text_tags, text_nazov, text_popis, text_cely) AGAINST ("' . mysql_real_escape_string($vyraz) . '" IN BOOLEAN MODE) AND
										text_public ="' . $this->db->real_escape_string($public) . '" 
							ORDER BY text_datum ASC LIMIT ' . $start . ', ' . $limit . ' ');
		if ($hladaj_text->num_rows != FALSE) {
			# OK JE
			
			# VYSLEDKY HLADANIA

					?>
					<div class="wrap-main-content">
						<div class="container fullwidth">
							<div class="sixteen columns">
								<h4><?php echo $this->lang['vysledkyhlad'] . '" ' . $vyraz . ' "'; ?></h4>
							</div>
						</div>
					</div>
					<?php

			//echo $this->lang['vysledkyhlad'] . $vyraz;

			while ($hladaj = $hladaj_text->fetch_assoc()) {
				
				include TEMPLATE_DESIGN . 'html_hladat.php';

			}

			$this->strankovanie($limit, $range, $url_zacni, $vyraz);

		}
		else {
			# NIEJE NENASLO SA NIC
			//echo $this->lang['hladattext'] . ' ' . $vyraz . '</br>' . $this->lang['nohladat'];
					?>
					<div class="wrap-main-content">
						<div class="container fullwidth">
							<div class="sixteen columns">
								<h4><?php echo $this->lang['hladattext'] . ' ' . $vyraz . '</br>' . $this->lang['nohladat']; ?></h4>
							</div>
						</div>
					</div>
					<?php
		}


	}


	private function visitarticle ($idarticle, $interval = '1') {

		# SYSTEM VISIT INTERVAL
		$s = $this->db->query('SELECT system_id, system_visit FROM be_system' . $this->prefix . ' 
									ORDER BY system_id DESC LIMIT 1');
		if ($s->num_rows != FALSE) {

			$tlac = $s->fetch_assoc();
			$interval = $tlac['system_visit'];
		}
		else {
			$interval = '1';
		}

		$expire = time() + ((60*60)* $interval ); //  hodin

		if (isset($_COOKIE['visitarticle' . $idarticle]) AND $_COOKIE['visitarticle' . $idarticle] == '1') {
			# OK JE

		}
		else {

			# ULOZ COOKIE
			setcookie('visitarticle' . $idarticle, '1', $expire);
			
			# VISITS +1
			$visit = $this->db->query('UPDATE be_text' . $this->prefix . ' SET text_visit= text_visit + 1 
									WHERE text_id ="' . $this->db->real_escape_string($idarticle) .  '" ');

		}


	}



	public function getarchiv ($cas, $typ = 'clanok', $pozicia = 'hore') {

		$rok = substr($cas,0, -(strlen($cas) - strrpos($cas, '/')));
		$mesiac = substr($cas, strpos($cas, '/')+1);

		if (strrpos($cas, '/') != FALSE) {
			$oboje = 1; // rok aj mesiac
		}
		else {
			$rok = $cas;
			$oboje = 2; // len rok
		}

		//$typ = '';
		//$pozicia = '';

		//echo 'rok ' . $rok . '</br>' . 'mesiac ' . $mesiac;

		# GET STRANA
		if (isset($_GET['page']) AND $_GET['page'] != '0') {
			$page = $_GET['page'];
		}
		else {
			$page =1;
		}

		$limit = 10;
		$range = 5;

		$start = ($page - 1)* $limit;

		///////////////////////////
		//////   ARCHIV   ////////
		/////////////////////////

		$public =1;



		# TLACENIE CLANKOV
		$hlad_clanok1 = '';
		$hlad_clanok1 .= 'SELECT SQL_CALC_FOUND_ROWS  *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
										FROM be_text' . $this->prefix . ' t 
										JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
										JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									WHERE EXTRACT(YEAR FROM text_datum)="' . $this->db->real_escape_string($rok) . '"  ';

		if($oboje == '1') {
			$hlad_clanok1 .= ' AND EXTRACT(MONTH FROM text_datum)="' . $this->db->real_escape_string($mesiac) . '" ';
		}
										
		$hlad_clanok1 .= ' AND text_public ="' . $this->db->real_escape_string($public) . '" AND 
								menu_typ ="' . $this->db->real_escape_string($typ) . '" AND 
								menu_pozicia ="' . $this->db->real_escape_string($pozicia) . '" 
									ORDER BY text_datum ASC LIMIT ' . $start . ', ' . $limit . ' ';

		$hlad_clanok = $this->db->query($hlad_clanok1);

		if ($hlad_clanok->num_rows != FALSE) {
			# OK SU

			while ($article = $hlad_clanok->fetch_assoc()) {
								
				include TEMPLATE_DESIGN . 'html_article.php';

			}

			$url_zacni = '?archiv';
			$url_menu = $cas;

			$this->strankovanie($limit, $range, $url_zacni, $url_menu);

		}
		else {
			# ERROR
			# ZIADNY CLANOK
			//echo $this->lang['noarchivclanok'];
			?>
			<div class="wrap-main-content">
				<div class="container fullwidth">
					<div class="sixteen columns">
						<h4><?php echo $this->lang['noarchivclanok']; ?></h4>
					</div>
				</div>
			</div>
			<?php
		}

	}

	public function redirect($url, $cas= '10') {

		if (isset($url) AND filter_var($url, FILTER_VALIDATE_URL)) {
			
			include TEMPLATE_DESIGN . 'html_redirect.php';
			
			header('Refresh: ' . $cas . '; url=' . $url);
			exit();
		}
		else {
			//echo 'Externa stranka nema spravny format.';
			?>
			<div class="wrap-main-content">
				<div class="container fullwidth">
					<div class="sixteen columns">
						<h4><?php echo 'Externa stranka nema spravny format.'; ?></h4>
					</div>
				</div>
			</div>
			<?php

		}


	}


	public function status ($typ, $hash = '') {

		if ($typ == 'activeNews') {
			# POTVRDENIE ODBERU NEWSLETTER
			$over = $this->db->query('SELECT newsletter_token, newsletter_stav, newsletter_id, newsletter_email  
										FROM be_newsletter' . $this->prefix . ' 
										WHERE newsletter_token ="' . $this->db->real_escape_string($hash) . '" ');
			if ($over->num_rows != FALSE) {
				# OK JE, aktivujeme

				$overenie = $over->fetch_assoc();

				if ($overenie['newsletter_stav'] == '2') {
					# treba aktivovat
					$prepis = $this->db->query('UPDATE be_newsletter' . $this->prefix . ' SET 
													newsletter_stav =1 
												WHERE newsletter_id ="' . $this->db->real_escape_string(intval($overenie['newsletter_id'])) . '" ');
					$stav = 'active';

					include TEMPLATE_DESIGN . 'html_activeNews.php';

				}
				elseif ($overenie['newsletter_stav'] == '1') {
					# je aktivne uz, co robis reeat
					$stav = 'activerepeat';
					include TEMPLATE_DESIGN . 'html_activeNews.php';

				}
				elseif ($overenie['newsletter_stav'] == '0') {
					# ucet je zablkovany, nieje mozne skusat take veci
					$stav = 'block';
					include TEMPLATE_DESIGN . 'html_activeNews.php';

				}
				else {
					# nwm taka moznost nieje
					$stav = 'error';
					include TEMPLATE_DESIGN . 'html_activeNews.php';

				}

			}
			else {
				# ZLE NENI, ALEBO UY ZJE  AKTIVOVANE
				$stav = 'error';
				
				include TEMPLATE_DESIGN . 'html_activeNews.php';

			}
		}
		elseif ($typ == 'dss') {
			//echo 'ddddd';
			?>
			<div class="wrap-main-content">
				<div class="container fullwidth">
					<div class="sixteen columns">
						<h4><?php echo 'dddddd'; ?></h4>
					</div>
				</div>
			</div>
			<?php
		}
		else {
			//echo 'noioooo';
			?>
			<div class="wrap-main-content">
				<div class="container fullwidth">
					<div class="sixteen columns">
						<h4><?php echo 'nooooooooo.'; ?></h4>
					</div>
				</div>
			</div>
			<?php
		}



	}

}



?>