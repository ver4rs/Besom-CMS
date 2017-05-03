<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

# CLASS SYSTEM

class system extends funkcia {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function systemstranky () {

		$tlac = $this->db->query('SELECT * FROM be_system' . $this->prefix . ' ORDER BY system_id ASC LIMIT 1 ');
		$row = $tlac->fetch_assoc();

		return $row;

	}

	public function systemLangActive () {

		# NENI INICIALIZOVANE 
		if (empty($_COOKIE['lang1']) AND empty($_COOKIE['lang2'])) {
			
			$nac_lang1 = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' ORDER BY jazyk_default DESC LIMIT 1 ');
			if ($nac_lang1->num_rows != 0) {
				# OK je
				$langActive = $nac_lang1->fetch_assoc();

				
				return $langActive;
			}

		}
		else {
			# JE ULOZENE V COOKIES OVERIME CI NENI FAKE - CI  EXISTUJE
			$nac_lang1 = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' 
										WHERE jazyk_nazov ="' . $this->db->real_escape_string($_COOKIE['lang2']) . '" AND 
										jazyk_short ="' . $this->db->real_escape_string($_COOKIE['lang1']) . '" LIMIT 1 ');
			if ($nac_lang1->num_rows != 0 AND file_exists('lang/' . $_COOKIE['lang2'] . '.php')) {
				# OK je
				$langActive = $nac_lang1->fetch_assoc();

				
				return $langActive;
			}
			else {
				# DEFAULTNY JAZYK NACITAM
				$nac_lang1 = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' ORDER BY jazyk_default DESC LIMIT 1 ');
				if ($nac_lang1->num_rows != 0) {
					# OK je
					$langActive = $nac_lang1->fetch_assoc();

					
					return $langActive;
				}
			}
			
		}


	}

	public function systemLang () {

		# NENI INICIALIZOVANE 
		if (empty($_COOKIE['lang1']) AND empty($_COOKIE['lang2'])) {

			
			$nac_lang = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' 
								 WHERE jazyk_default !=1 ORDER BY jazyk_nazov DESC  ');
			if ($nac_lang->num_rows != 0) {
				# OK je
				$bes_lang = array();
				while ($lang_t = $nac_lang->fetch_assoc()) {

					$short = $lang_t['jazyk_short'];
					$ikona = $lang_t['jazyk_ikona'];
					$nazov = $lang_t['jazyk_nazov'];
					$skratka = $lang_t['jazyk_skratka'];
					$active = $lang_t['jazyk_default'];

					$bes_lang[$nazov][$ikona][$skratka][$short][$active][] = $lang_t;

					/*?><p><a href="<?php echo URL_ADRESA . htmlspecialchars('?lang=' . $lang_t['jazyk_short']); ?>"><img src="<?php echo htmlspecialchars($lang_t['jazyk_ikona']); ?>" alt="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>" title="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>"><span><?php echo htmlspecialchars($lang_t['jazyk_skratka']) ?></span></a></p><?php*/
				}
				return $bes_lang;
			}
			else {
				# NONE
			}


		}
		else {
			# JE ULOZENE V COOKIES OVERIME CI NENI FAKE - CI  EXISTUJE
			
			$nac_lang = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' 
							 WHERE jazyk_short !="' . $this->db->real_escape_string($_COOKIE['lang1']) . '" ORDER BY jazyk_nazov DESC  ');
			if ($nac_lang->num_rows != 0) {
				# OK je
				$bes_lang = array();
				while ($lang_t = $nac_lang->fetch_assoc()) {

					$short = $lang_t['jazyk_short'];
					$ikona = $lang_t['jazyk_ikona'];
					$nazov = $lang_t['jazyk_nazov'];
					$skratka = $lang_t['jazyk_skratka'];
					$active = $lang_t['jazyk_default'];

					$bes_lang[$nazov][$ikona][$skratka][$short][$active][] = $lang_t;

					/*?><p><a href="<?php echo URL_ADRESA . htmlspecialchars('?lang=' . $lang_t['jazyk_short']); ?>"><img src="<?php echo htmlspecialchars($lang_t['jazyk_ikona']); ?>" alt="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>" title="<?php echo htmlspecialchars($lang_t['jazyk_nazov']); ?>"><span><?php echo htmlspecialchars($lang_t['jazyk_skratka']) ?></span></a></p><?php*/
				}
				return $bes_lang;
			}
			else {
				# NONE
			}

			
		}

	}



	public function systemMenu ($lang = 'sk') {

		#  NAVIGATION MENU 
		// sekcia hlavna a tak parrent
		$BesomMenu = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
										WHERE menu_typ = 1 AND menu_rodic = 0 ORDER BY menu_zorad DESC ');
		if ($BesomMenu->num_rows != FALSE) {
			# OK JE

			$BesMenu = array();

			while ($menu = $BesomMenu->fetch_assoc()) {
				# ULOZENIE DO POLA ARRAY

				#HODNOTY
				$id = $menu['menu_id'];
				$nazov = $menu['menu_nazov_' . $lang . ''];
				$menu_url = $menu['menu_url'];
				$menu_image = $menu['menu_img'];
				$menu_cesta = $menu['menu_cesta'];

				$BesMenu[$id][$nazov][$menu_url][$menu_image][$menu_cesta][] = $menu;

			}

			return $BesMenu;

		}
		else {

			return false;
		}
	}

	public function systemMenuParent ($parent = '0', $lang) {

		# parent navigation on to systemMenu
		$BesomMenuParent = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
											WHERE menu_typ = 1 AND menu_rodic ="' . $this->db->real_escape_string($parent) . '" 
											 ORDER BY menu_zorad DESC ');
		if ($BesomMenuParent->num_rows != FALSE) {
			# OK
			$BesMenuParent = array();

			while ($menuP = $BesomMenuParent->fetch_assoc()) {
				
				#HODNOTY
				$id1 = $menuP['menu_id'];
				$nazov1 = $menuP['menu_nazov_' . $lang . ''];
				$menu_url1 = $menuP['menu_url'];
				$menu_image1 = $menuP['menu_img'];
				$menu_cesta1 = $menuP['menu_cesta'];

				$BesMenuParent[$id1][$nazov1][$menu_url1][$menu_image1][$menu_cesta1][] = $menuP;
			}

			return $BesMenuParent;

		}
		else {
			return false;
		}

	}


	public function menuAdmin ($lang = 'sk') {


		$BesomMenu = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
										WHERE menu_typ = 1 AND menu_rodic = 0 ORDER BY menu_zorad DESC ');
		if ($BesomMenu->num_rows != FALSE) {
			# OK JE
			?><ul id="menu" class="nav nav-pills nav-stacked" style=""><?php
			//$BesMenu = array();
			//$sub = 0;
			while ($menu = $BesomMenu->fetch_assoc()) {
				# ULOZENIE DO POLA ARRAY

				#HODNOTY
				/*$id = $menu['menu_id'];
				$nazov = $menu['menu_nazov_' . $lang . ''];
				$menu_url = $menu['menu_url'];
				$menu_image = $menu['menu_img'];
				$menu_cesta = $menu['menu_cesta'];

				$BesMenu[] = $menu;*/

				?>
				<li class="submenu">
				<a href="<?php echo URL_ADRESA . htmlspecialchars('?menu=' . $menu['menu_url']); ?>"><i class="<?php echo htmlspecialchars($menu['menu_img']); ?>"></i> <span><?php echo htmlspecialchars($menu['menu_nazov_' . $lang . '']); ?></span>  <i class="glyphicon glyphicon-chevron-right"></i></a>
				<?php

				# PARENT
				$BesomMenuParent = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
											WHERE menu_typ = 1 AND menu_rodic ="' . $this->db->real_escape_string($menu['menu_id']) . '" 
											 ORDER BY menu_zorad DESC ');
				if ($BesomMenuParent->num_rows != FALSE) {
					# OK
					//$BesMenuParent = array();

					?><ul style=" "><?php

					while ($menuP = $BesomMenuParent->fetch_assoc()) {
						
						#HODNOTY
						/*$id1 = $menuP['menu_id'];
						$nazov1 = $menuP['menu_nazov_' . $lang . ''];
						$menu_url1 = $menuP['menu_url'];
						$menu_image1 = $menuP['menu_img'];
						$menu_cesta1 = $menuP['menu_cesta'];
						$sub = 1;

						$BesMenu[] = $menuP;*/

						?><li><a href="<?php echo URL_ADRESA . htmlspecialchars('?menu=' . $menu['menu_url'] . '&action=' . $menuP['menu_url']) ?>"><i class="<?php echo htmlspecialchars($menuP['menu_img']); ?>"></i> <span><?php echo htmlspecialchars($menuP['menu_nazov_' . $lang . '']) ?></span></a></li><?php

					}
					?></ul><?php
				}


				?></li><?php
			}

			//return $BesMenu;
			?></ul><?php
		}
		else {

			return false;
		}


	}



	/*
	public function content ($menu, $action = '', $lang) {

		# $menu = menu_url hlavne
		# $action = akcia menu url vedlajsie

		$over = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
									WHERE menu_url ="' . $this->db->real_escape_string($menu) . '" LIMIT 1');
		if ($over->num_rows != FALSE) {
			# OK

			$content = $over->fetch_assoc();

			if (file_exists(SYSTEM_CESTA . htmlspecialchars($content['menu_cesta']))) {

				$ces = SYSTEM_CESTA . htmlspecialchars($content['menu_cesta']);
					
				# INCLUDE LIBRARY CLASS ALL
				foreach(glob("$ces/*lib.php") as $filename){
				   include($filename);
				}

				# NACITAT CLASS new class();
				# v CLASS IN LIB 

				# NACITAT INDEX.PHP
				$subor = substr($content['menu_cesta'], 0, -(strlen($content['menu_cesta'])- strrpos($content['menu_cesta'], '/')));
				if (file_exists(SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . $subor . '.php'))) {
					include SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . $subor . '.php');
				}


			}
			else {
				# NEEXISTUJE FOLDER MENU
				# ERROR
				echo $this->lang['nofolder'];
			}


		}
		else {
			# ERROR
			# STRANKA NEBOLA NAJDENA
			echo $this->lang['nocontent'];
		}



	}
	*/
		public function content ($menu, $action = '', $lang) {

		# $menu = menu_url hlavne
		# $action = akcia menu url vedlajsie

		$over = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
									WHERE menu_url ="' . $this->db->real_escape_string($menu) . '" LIMIT 1');
		if ($over->num_rows != FALSE) {
			# OK

			$content = $over->fetch_assoc();

			if (file_exists(SYSTEM_CESTA . htmlspecialchars($content['menu_cesta']))) {


				$ac = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
											WHERE menu_url ="' . $this->db->real_escape_string($action) . '" AND
													menu_typ = 1 AND menu_rodic != 0 ');
				if ($ac->num_rows != FALSE) {
					# JE PODCATEGORY

					$act = $ac->fetch_assoc();

					$ces = SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . '/' . $act['menu_url']);

					# INCLUDE LIBRARY CLASS ALL
					/*foreach(glob("$ces/*lib.php") as $filename){
					   include($filename);
					}*/

					foreach(glob("$ces.lib.php") as $filename){
					   include($filename);
					}

					# NACITAT CLASS new class();
					# v CLASS IN LIB 

					#NACITAT LANG.lib.php
					include SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . '/lang.lib.php');

					# NACITAT INDEX.PHP
					//$subor = substr($content['menu_cesta'], 0, -(strlen($content['menu_cesta'])- strrpos($content['menu_cesta'], '/')));
					$subor = $act['menu_url'];
					if (file_exists(SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . $subor . '.php'))) {
						include SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . $subor . '.php');
					}

				}
				else {
					# NENI ACTION, LEN SEKCIA

					$ces = SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . '/' . $content['menu_url']);

					# INCLUDE LIBRARY CLASS ALL
					/*foreach(glob("$ces/*lib.php") as $filename){
					   include($filename);
					}*/

					/*foreach(glob("$ces.lib.php") as $filename){
					   include($filename);
					}*/

					# NACITAT CLASS new class();
					# v CLASS IN LIB 

					# NACITAT INDEX.PHP
					//$subor = substr($content['menu_cesta'], 0, -(strlen($content['menu_cesta'])- strrpos($content['menu_cesta'], '/')));
					//$subor = $act['menu_url'];
					/*if (file_exists(SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . $subor . '.php'))) {
						include SYSTEM_CESTA . htmlspecialchars($content['menu_cesta'] . $subor . '.php');
					}*/

					# NACITAME MENU
					$this->tlacMenuSekcie($content['menu_id']);

				}
			}
			else {
				# NEEXISTUJE FOLDER MENU
				# ERROR
				echo $this->lang['nofolder'];
			}


		}
		else {
			# ERROR
			# STRANKA NEBOLA NAJDENA
			echo $this->lang['nocontent'];
		}



	}

	public function tlacMenuSekcie ($menu_id) {
		$m = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
											WHERE menu_rodic ="' . $this->db->real_escape_string($menu_id) . '" AND
											menu_typ =1 ');
		if ($m->num_rows != FALSE) {
			# TLAC
			while ($row = $m->fetch_assoc()) {
				echo $row['menu_nazov_sk'];
			}
		}
	}

	public function urlMenuGenerate ($menu = '', $action = '') {

		if (isset($menu) AND $menu != FALSE) {
			#OK

			if (isset($action) AND $action != FALSE) {
				
				$nac = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
										WHERE menu_url ="' . $this->db->real_escape_string($action) . '" AND 
												menu_typ = 1 AND menu_rodic != 0 LIMIT 1 ');
				if ($nac->num_rows != FALSE) {
					


				}
				else {
					# ERROR

				}

			}
			else {
				# DOMOV SEKCIE
				echo 'domov sekcie';
			}
		}
		else {
			# 
			return;
		}

	}
	
	public function contentArray ($menu, $action = '', $lang) {

		# $menu = menu_url hlavne
		# $action = akcia menu url vedlajsie

		$over1 = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' WHERE menu_rodic =0 ORDER BY menu_zorad ASC ');
		if ($over1->num_rows != FALSE) {
			# OK

			$pole = array();
			$pol = ' ';
			

			$pol .= ' if (isset($_GET["menu"])) {  '; // isset $_GET['menu']

			while ($content = $over1->fetch_assoc()) {
		

				$pol .= ' if ($_GET["menu"] == "' . $content['menu_url'] . '"  ) {  ';

				# KAT ACTION
				$ove = $this->db->query('SELECT * FROM be_system_menu' . $this->prefix . ' 
										WHERE menu_rodic ="' . $this->db->real_escape_string($content['menu_id']) . '" 
										ORDER BY menu_zorad ASC ');
				if ($ove->num_rows != FALSE) {
					# JE
					$pol .= ' if (isset($_GET["action"])) {  ';

					while ($action = $ove->fetch_assoc()) {
						
						$pol .= ' if($_GET["action"] == "' . $action['menu_url'] . '") {  ';

							#include 'system/nastavenia/lang.lib.php';
							#include 'system/nastavenia/language.lib.php';
							#include 'system/nastavenia/language.php';
							$pol .= ' include "system/' . $content['menu_cesta'] . 'lang.lib.php"; ';
							$pol .= ' include "system/' . $content['menu_cesta'] . $action['menu_url'] . '.lib.php"; ';
							$pol .= ' include "system/' . $content['menu_cesta'] . $action['menu_url'] . '.php"; ';

						$pol .= ' } ';

					}

					$pol .= ' } else { /*MENU CONTENT*/ } ';
				}



				$pol .= " } ";


			}
			
			$pol .= " } else {  /*DOMOV*/ } ";  // end isset $_GET['menu']

			return $pol;
		}

	}	

	/*
	  // podla prihlaseneho uzivatela ID, zisitme ci existuje, ci existuje dana jeho skupina a v akej sa nachadza skupine
	  // TRUE - "right" v akej url sa nachadza, menu a podla toho zistime cii to moze robit

					$url = (isset($_GET['action'])) ? $_GET['action'] : $_GET['menu'];

	  // FALSE - zle, ERROR
	*/
	public function authorization ($user_id, $folder = '', $url = '') {

		//$url = (isset($_GET['action'])) ? $_GET['action'] : $_GET['menu'];


		# ZISTIME CI EXISTUJE A ID SKUPINY
		$user = $this->db->query('SELECT user_id, g.group_id, u.group_id
									FROM be_user' . $this->prefix . ' u 
										JOIN be_group' . $this->prefix . ' g ON u.group_id = g.group_id 
									WHERE user_id ="' . $this->db->real_escape_string(intval($user_id)) . '" ');

		if ($user->num_rows != FALSE) {
			# OK, 
			
			# ci je dane parametrre ---->>> 
			$users = $user->fetch_assoc();
			

			# PARAVA
			$prava = $this->db->query('SELECT *, r.right_id, f.right_id 
										FROM be_right' . $this->prefix . ' r 
											JOIN be_function' . $this->prefix . ' f ON r.right_id = f.right_id 
										WHERE group_id ="' . $this->db->real_escape_string(intval($users['group_id'])) .  '" AND 
												right_url ="' . $this->db->real_escape_string($url) . '" AND 
												right_folder ="' . $this->db->real_escape_string($folder) . '" ');
			if ($prava->num_rows != FALSE) {
				# OK

				$result = $prava->fetch_assoc();

				return $result;
			}
			else {
				# ERROR, NO AUTHORIZATION
				// no, noooo  
				return false;
			}


			//return $result;
		}
		else {
			# ERROR
			# NOT AUTHORIZING
			return false;
		}

	}





}



?>