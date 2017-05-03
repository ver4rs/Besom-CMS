<?php

# WIDGET SKRIPTY

class widget extends modul {

	protected $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang, $lang_short) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
		$this->lang_short = $lang_short;
	}


	public function widgetzobraz ($pozicia = '') { # nacitanie celeho praveho panela widget, sidebar

		$povol =1;

		$query = $this->db->query('SELECT * FROM be_widget' . $this->prefix . ' 
									WHERE widget_stav ="' . $this->db->real_escape_string($povol) . '"
									ORDER BY widget_zorad ASC ');
		if ($query->num_rows != FALSE) {
			# OK SU WIGETY

			while ($wid = $query->fetch_assoc()) {
				# TLACIME

				#ROZDELENIE
				if ($wid['modul_id'] != '0') {
					# MODUL

					# NACITAME MODUL INFORMACIE, OVERIME CI EXISTUJE
					$this->existujemodulstav($wid['modul_id'], $wid['widget_id']); 


					//parent::testmodul(); //test

				}
				elseif ($wid['widget_typ'] == 'script') { // script js,.... banner,....
					# SCRIPT JS 
					?>
					<div class="widget-bound newsletter-widget">
						<div class="heading-light"><h5><?php echo htmlspecialchars($wid['widget_nazov']); ?></h5></div>
						<p class="script"><?php echo htmlspecialchars_decode($wid['widget_text']);   ?></p>
					</div>
					<?php
					 
				}
				elseif ($wid['widget_typ'] == 'image') { // script js,.... banner,....
					# IMAGE
					?>
					<div class="widget-bound newsletter-widget">
						<div class="heading-light"><h5><?php echo htmlspecialchars($wid['widget_nazov']); ?></h5></div>
						
						<a class="wida" href="<?php if ($wid['widget_url'] != FALSE) { echo URL_ADRESA . htmlspecialchars('?redirect=') . urldecode($wid['widget_url']); } else { echo '#'; } ?>" target="_blank">
							<img class="widimage" src="<?php echo htmlspecialchars_decode($wid['widget_text']); ?>" alt="<?php echo htmlspecialchars($wid['widget_nazov']); ?>" title="<?php echo htmlspecialchars($wid['widget_nazov']); ?>">
						</a>
					</div>
					<?php
					 
				}
				elseif ($wid['text_id'] != '0') {
					# TEXT ID Z DATABAZY

					#  Z tabulky clankou

					$povol =1;

					$nac_cl = $this->db->query('SELECT * FROM be_text' . $this->prefix . ' 
													WHERE text_id ="' . $this->db->real_escape_string(int($wid['text_id'])) . '" AND 
															 text_public ="' . $this->db->real_escape_string(int($povol)) . '" ');
					if ($nac_cl->num_rows != FALSE) {
						# OK
						$tlac = $nac_cl->fetch_assoc();

						?>
						<div class="widget-bound newsletter-widget">
							<div class="heading-light"><h5><?php echo htmlspecialchars($tlac['text_nazov']); ?></h5></div>
							<p class="widtext"><?php echo htmlspecialchars_decode($tlac['text_cely']); ?></p>
						</div>	
						<?php
						
					}
					else {
						?>
						<div class="widget-bound newsletter-widget">
							<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['notext']); ?></h5></div>
						</div>
						<?php	

					}

				}
				else {
					# WIDGET TEXT
					# VLASTNY

					# PREMENNE
					$nadpis = '';
					$popis = '';

					# LANG
					// $this->lang;
					$def = $this->db->query('SELECT jazyk_short, jazyk_default FROM be_jazyk' . $this->prefix . ' 
												WHERE jazyk_short ="' . $this->db->real_escape_string($this->lang_short) . '" AND 
													jazyk_default =1 LIMIT 1 ');
					if ($def->num_rows != FALSE) {
						# je, default lang
						$nadpis = $wid['widget_nazov'];
						$popis = $wid['widget_text'];
					}
					else {
						# none default lang
						# prerobime

						# zisyime ci v translate su take
						# NADPIS TRANSLATE
						$trans = $this->db->query('SELECT * FROM be_translate' . $this->prefix . ' 
											WHERE translate_id ="' . $this->db->real_escape_string($wid['widget_translate_id']) . '" ');
						if ($trans->num_rows != FALSE) {
							# je preklad nazov
							$translate = $trans->fetch_assoc();

							$nadpis = $translate['translate_' . $this->lang];

						}
						else {
							# none nadpis TRANSLATE
							$nadpis = $wid['widget_nazov'];	
						}

						# POPIS TRANSLATE
						$trans1 = $this->db->query('SELECT * FROM be_translate' . $this->prefix . ' 
										WHERE translate_id ="' . $this->db->real_escape_string($wid['widget_popis_translate_id']) . '" ');
						if ($trans1->num_rows != FALSE) {
							# je preklad nazov
							$translate = $trans1->fetch_assoc();

							$popis = $translate['translate_' . $this->lang];	

						}
						else {
							# none popis translate
							$popis = $wid['widget_text'];
						}
					}

					# DESIGN SIDEBAR, WIDGET
					# VLASTNY
					// $nadpis
					// $popis
					//echo htmlspecialchars_decode($nadpis);
					//echo htmlspecialchars_decode($popis);
					?>
					<div class="widget-bound newsletter-widget">
							<div class="heading-light"><h5><?php echo htmlspecialchars($nadpis); ?></h5></div>
						<p class="widtext"><?php echo htmlspecialchars_decode($popis);   ?></p>
						
					</div>
					<?php


				}



			}

		}
		else {
			# ZIADNY WIDGET
			echo 'ziadny widget';
		}


	}



	public function existujemodulstav ($module_id, $widget_id) { # zistenie ci existuje dany modul

		$ex_mod = $this->db->query('SELECT * FROM be_modul' . $this->prefix . ' 
										WHERE modul_id ="' . $this->db->real_escape_string($module_id) . '" ');
		if ($ex_mod->num_rows != FALSE) {
			# code...
			
			$row = $ex_mod->fetch_assoc();

			if ($row['modul_stav'] == '1') {
				# POVOLENY

				parent::nacitajmodulsubor(MODUL_CESTA, $row['modul_subor'], $widget_id);

			}
			else {
				# ZAKAZANY
				// nebudeme vypisovat, je to skarede
				//echo 'zakazany modul.';
			}
		}
		else {
			echo 'nastala chyba modul neexistuje';
		}


	}





}







?>