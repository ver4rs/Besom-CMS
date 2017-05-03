<?php

# class slider

class slider {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function sliderzobraz ($pocet_snimkov = '5') {

		# slider_typ {1,2,..... 3}  1 - top naj clanky, blog    2 - vybrane obrazky    3 - kombinovane
		$public = 1;
		$povol = 1;

		$slider_nastavenia = $this->db->query('SELECT system_slider, system_slider_pocet FROM be_system' . $this->prefix . ' ');
		if ($slider_nastavenia->num_rows != FALSE) {
			# OK

			$slider_nastavenia_tlac = $slider_nastavenia->fetch_assoc();

			$slider_typ = $slider_nastavenia_tlac['system_slider'];
			$pocet_snimkov = $slider_nastavenia_tlac['system_slider_pocet'];

			if ($slider_nastavenia_tlac['system_slider'] == '1') {
				# TOP CLANKY NAJNOVSIE

				$slider1 = $this->db->query('SELECT *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
													FROM be_text' . $this->prefix . ' t 
													JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
													JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									WHERE text_public ="' . $this->db->real_escape_string($public) . '" AND 
											text_slider ="' . $this->db->real_escape_string($povol) . '" 
									ORDER BY text_datum ASC LIMIT 0, ' . $this->db->real_escape_string($pocet_snimkov) . ' ');
				if ($slider1->num_rows != FALSE) {
					# OK, JE
					$poc = -1;
					$poc1 = 1;
					
					
					?><ul class="rs-slider"><?php

					while ($slider = $slider1->fetch_assoc()) {
						# TLAC
						$poc +=1;
						if ($poc == '0') {
							$nap1 = ' z-index: 2; opacity: 1; ';
						}
						else {
							$nap1 = ' opacity: 0; ';
						}
						?>
						<li class="rs-slide-<?php echo $poc; ?>" style="<?php echo $nap1; ?>">
							<a href="<?php if ($slider['menu_typ'] == 'text') { echo URL_ADRESA . htmlspecialchars('?menu=' . $slider['text_url']); } else {/* clanok   */ echo URL_ADRESA . htmlspecialchars('?article=' . $slider['text_url']); } ?>" target="_blank" />
								<img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_NORMAL . htmlspecialchars($slider['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($slider['text_nazov']); ?>" style="width: 700px; height: 300px;" class="rs-slide-image"/>
			                <?php

							# STAV ZOBRAZENIA POPISU ANO NIE
							if ($slider['text_slider_popis'] == '1') {
				                /*?>
				                <span>
				                    <b style="font-size: 16px; color: #DEDEDE; "><?php echo htmlspecialchars($slider['text_nazov']); ?></b><br />
				                    <?php echo htmlspecialchars($slider['text_popis']); ?>
				                </span>
				                <?php*/
				            }
				            ?>    
							</a>
						</li>
						<?php

					}
					?></ul><?php

				}
				else {
					# NENI
					echo $this->lang['slidernophoto'];
				}

			}
			elseif ($slider_nastavenia_tlac['system_slider'] == '2') {
				# VYBRANE CLANKY

				$stav =1;

				$slider1 = $this->db->query('SELECT * FROM be_slider' . $this->prefix . ' 
												WHERE slider_stav ="' . $this->db->real_escape_string($stav) . '" 
												ORDER BY slider_poradie ASC ');
				if ($slider1->num_rows != FALSE) {
					# OK
					$poc = -1;
					$poc1 = 1;
					
					
					?><ul class="rs-slider"><?php

					while ($slider = $slider1->fetch_assoc()) {
						# TLAC
						$poc +=1;
						if ($poc == '0') {
							$nap1 = ' z-index: 2; opacity: 1; ';
						}
						else {
							$nap1 = ' opacity: 0; ';
						}
						?>
						<li class="rs-slide-<?php echo $poc; ?>" style="<?php echo $nap1; ?>">
							<a href="#" target="_blank">
							<img src="<?php echo URL_ADRESA . IMAGE_SLIDER . htmlspecialchars($slider['slider_img']); ?>" alt="<?php echo htmlspecialchars($slider['slider_nazov']); ?>" style="width: 800px; height: 350px;" class="rs-slide-image" />
			                
							<?php
							# STAV ZOBRAZENIA POPISU ANO NIE
							if ($slider['slider_popis_stav'] == '1') {
				                /*?>
				                <span>
				                    <b style="font-size: 16px; color: #DEDEDE; "><?php echo htmlspecialchars($slider['slider_nazov']); ?></b><br />
				                    <?php echo htmlspecialchars($slider['slider_popis']); ?>
				                </span>
				                <?php*/
							}

							?>

							</a>
						</li>
						<?php

					}

					?></ul>
				    <?php    

				}
				else {
					# NENI ZIADNE FOTO
					echo $this->lang['slidernophoto'];
				}

			}
			elseif ($slider_nastavenia_tlac['system_slider'] == '3') {
				# KOMBINOVANE


				######################################################
				# ------> NEXT  NESKORSIE DOPLNENIE   <--------------
				#####################################################

			}
			else {
				# NEEXISTUJE MOZNOST
				# NIC NEZOBRAZIME
				echo $this->lang['sliderno'];
			}

		}
		else {
			$slider_typ = 1; 
		}

	}







}






?>