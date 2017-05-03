<?php

# MENU CLASS



class menu {

	private $db, $prefix;
	public $lang;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function menuTop ($poz) {

		$rodic =0;
		if ($poz == FALSE) {
			$poz = 'hore';
		}
		$teraz = (isset($_GET['menu'])) ? $_GET['menu'] : '';

		$menu1 = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
			WHERE menu_rodic ="' . $this->db->real_escape_string($rodic) . '" AND menu_pozicia ="' . $this->db->real_escape_string($poz) . '" 
			ORDER BY menu_zorad ASC ');
		if ($menu1->num_rows != FALSE) {
			# OK , JE
			?><ul id="nav"><?php
			while ($menu1_t = $menu1->fetch_assoc()) {
				# TLAC ZAKLAD, SEKCIA

				if ($menu1_t['menu_zobraz'] == 'start' AND ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php') ) {
					$active = ' class="active" ';
				}
				elseif ($teraz == $menu1_t['menu_url']) {
					$active = ' class="active" ';
				}
				else {
					$active = ' ';
				}

				# TYP MENU URL ALEBO ODKAZ
				if ($menu1_t['menu_typ'] == 'url') {
					# URL
					?><li <?php echo $active; ?> ><a href="<?php echo URL_ADRESA . htmlspecialchars('?redirect=') . urldecode($menu1_t['menu_url']); ?>" title="<?php echo htmlspecialchars($menu1_t['menu_popis']); ?>" target="<?php echo htmlspecialchars($menu1_t['menu_target']); ?>"><?php echo htmlspecialchars($menu1_t['menu_nazov']); ?></a><?php
				}
				else {

					?><li <?php echo $active; ?> ><a href="<?php if ($menu1_t['menu_zobraz'] == 'start') { echo URL_ADRESA; } else {  echo URL_ADRESA . htmlspecialchars('?menu=' . $menu1_t['menu_url']); } ?>"><?php echo htmlspecialchars(urldecode($menu1_t['menu_nazov'])); ?></a><?php
				}

					# ZISTENIE KATEGORIE
					$menu2 = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
						WHERE menu_rodic ="' . $this->db->real_escape_string($menu1_t['menu_id']) . '" AND menu_pozicia ="' . $this->db->real_escape_string($poz) . '" 
						ORDER BY menu_zorad ASC ');
					if ($menu2->num_rows != FALSE) {
						?><ul><?php
						
						while ($menu2_t = $menu2->fetch_assoc()) {
							
							if ($teraz == $menu2_t['menu_url']) {
								$active = ' class="active" ';
							}
							else {
								$active = ' ';
							}

							# TYP MENU URL ALEBO ODKAZ
							if ($menu2_t['menu_typ'] == 'url') {
								# URL
								?><li <?php echo $active; ?> ><a href="<?php echo URL_ADRESA . htmlspecialchars('?redirect=') . urldecode($menu2_t['menu_url']); ?>" title="<?php echo htmlspecialchars($menu2_t['menu_popis']); ?>" target="<?php echo htmlspecialchars($menu2_t['menu_target']); ?>"><?php echo htmlspecialchars($menu2_t['menu_nazov']); ?></a><?php
							}
							else {
								?>
								<li <?php echo $active; ?> ><a href="<?php echo htmlspecialchars(/*$menu1_t['menu_url'] . '/' . */URL_ADRESA . '?menu=' . $menu2_t['menu_url']); ?>"><?php echo htmlspecialchars($menu2_t['menu_nazov']); ?></a></li>
								<?php
							}
								
						}
						?></ul></li><?php
					}
					else {
						?></li><?php
					}
					

			}
			?></ul><?php
		}
		else {
			echo $lang['nomenu'];

		}


	}




}






?>