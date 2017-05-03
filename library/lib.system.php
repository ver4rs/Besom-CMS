<?php

# CLASS SYSTEM

class system {

	private $db, $prefix, $conn;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function systemstranky () {

		$tlac = $this->db->query('SELECT * FROM be_system' . $this->prefix . ' ORDER BY system_id ASC LIMIT 1 ');
		
		if ($tlac->num_rows != FALSE) {
			$row = $tlac->fetch_assoc();
		}
		else {
			$row = FALSE;
		}
		
		return $row;

	}

	public function systemCesta ($adresa = '') {

		$posli = array();
		
		$posli[1]['url'] = '';
		$posli[1]['nazov'] = '';

		$posli[2]['url'] = '';
		$posli[2]['nazov'] = '';


		if (isset($_GET['menu'])) { // menu
			
			$nac = $this->db->query('SELECT menu_nazov, menu_url, menu_rodic FROM be_menu' . $this->prefix . ' 
										WHERE menu_url ="' . $this->db->real_escape_string($_GET['menu']) . '" ');
			if ($nac->num_rows != FALSE) {
				$cesta = $nac->fetch_assoc();

				# CI JE SECTION/CATEGORY
				if ($cesta['menu_rodic'] == '0') {
					# SECTION

					$posli[1]['url'] = $adresa . '?menu=' . $cesta['menu_url'];
					$posli[1]['nazov'] = $cesta['menu_nazov'];
				}
				else {
					# CATEGORY
					$cat = $this->db->query('SELECT menu_nazov, menu_url, menu_rodic FROM be_menu' . $this->prefix . ' 
												WHERE menu_id ="' . $this->db->real_escape_string(intval($cesta['menu_rodic'])) . '" ');
					if ($cat->num_rows != FALSE) {
						$category = $cat->fetch_assoc();

						# DLZKA
						$dlzka = strlen($cesta['menu_nazov'] . $category['menu_nazov'])+5;
						if ($dlzka > 59) {
							
							# SECTION
							$posli[2]['url'] = $adresa . '?menu=' . $cesta['menu_url'];
							$posli[2]['nazov'] = $cesta['menu_nazov'];

							# CATEGORY
							$posli[1]['url'] = $adresa . '?menu=' . $category['menu_url'];
							$posli[1]['nazov'] = $category['menu_nazov'];
						}
						else {

							# SECTION
							$posli[2]['url'] = $adresa . '?menu=' . $cesta['menu_url'];
							$posli[2]['nazov'] = $cesta['menu_nazov'];

							# CATEGORY
							$posli[1]['url'] = $adresa . '?menu=' . $category['menu_url'];
							$posli[1]['nazov'] = $category['menu_nazov'];
						}

					}

				}


			}
		}
		elseif (isset($_GET['article'])) {  // clanok
			
			$nac = $this->db->query('SELECT text_nazov, text_url FROM be_text' . $this->prefix . ' 
										WHERE text_url ="' . $this->db->real_escape_string($_GET['article']) . '" ');
			if ($nac->num_rows != FALSE) {
				$cesta = $nac->fetch_assoc();

				$posli[1]['url'] = $adresa . '?article=' . $cesta['text_url'];

				if (strlen($cesta['text_nazov']) > 59) { 
					$posli[1]['nazov'] = substr($cesta['text_nazov'],0, 60) . '...'; 
				} 
				else { 
					$posli[1]['nazov'] = $cesta['text_nazov']; 
				}
			}
		}
		elseif (isset($_GET['rss'])) { // rss
			# code...
		}
		elseif (isset($_GET['q'])) {
			# SEARCH
			$posli[1]['url'] = $adresa . '?q=' . htmlspecialchars($_GET['q']);
			$posli[1]['nazov'] = 'Hladam → ' . htmlspecialchars($_GET['q']);
		}
		elseif (isset($_GET['archiv'])) {
			# ARCHIVE
			$posli[1]['url'] = $adresa . '?archiv=' . $_GET['archiv'];
			$posli[1]['nazov'] = 'Archiv → ' . $_GET['archiv'];
		}
		elseif (isset($_GET['redirect'])) {
			# STATUS ACTIVE NEWSLETTER
			$posli[1]['url'] = $adresa . '?redirect=' . $_GET['redirect'];
			$posli[1]['nazov'] = 'Presmeruj ma na ' . urldecode($_GET['redirect']);
		}
		elseif (isset($_GET['status']) AND isset($_GET['hash'])) {
			# STATUS ACTIVE NEWSLETTER
			$posli[1]['url'] = $adresa . '?status=' . $_GET['status'] . '&hash=' . $_GET['hash'];
			$posli[1]['nazov'] = 'Odber newslettera';
		}
		else {
			# nic
		}

		return $posli;

	}



	public function systemHead ($adresa = '') {

		$posli = array();

		
		$hlav = $this->db->query('SELECT system_title, system_keywords, system_description, system_img, system_url
										FROM be_system' . $this->prefix . ' ORDER BY system_id DESC LIMIT 1 ');
		if ($hlav->num_rows != FALSE) {
			$hlava = $hlav->fetch_assoc();

			$posli['url'] = $hlava['system_url'];

			$posli['title'] = $hlava['system_title'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
			$posli['keywords'] = $hlava['system_keywords'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
			$posli['description'] = $hlava['system_description'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
			$posli['url'] = $hlava['system_url'];
			$posli['image'] = URL_ADRESA . IMAGE_SYSTEM . $hlava['system_img'];
		}
		else {
			$posli['title'] = '';
			$posli['keywords'] = '';
			$posli['description'] = '';
			$posli['url'] = '';
			$posli['image'] = '';
		}


		if (isset($_GET['menu'])) { // menu
			
			$nac = $this->db->query('SELECT menu_id, menu_nazov, menu_url, menu_rodic, menu_typ, menu_popis, menu_modul_id, menu_album FROM be_menu' . $this->prefix . ' 
										WHERE menu_url ="' . $this->db->real_escape_string($_GET['menu']) . '" ');
			if ($nac->num_rows != FALSE) {
				$cesta = $nac->fetch_assoc();

				if ($cesta['menu_typ'] == 'text') {
					# content, text

					$tex = $this->db->query('SELECT text_nazov, text_popis, text_tags, text_obrazok, menu_id FROM be_text' . $this->prefix . ' 
												WHERE menu_id ="' . $this->db->real_escape_string(intval($cesta['menu_id'])) . '" ');
					if ($tex->num_rows != FALSE) {
						# OK JE
						$text = $tex->fetch_assoc();

						#IMAGE
						if (file_exists(URL_ADRESA . IMAGE_ARTICLES_NORMAL . htmlspecialchars($text['text_obrazok']))) {
							$image = URL_ADRESA . IMAGE_ARTICLES_NORMAL . htmlspecialchars($text['text_obrazok']);
						}
						else {
							//$image = // mame uz hore def. a vyplnene
							$image = $posli['image'];
						}

						$posli['title'] = $text['text_nazov']/* . ' - ' . $cesta['menu_nazov']*/ . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
						$posli['keywords'] = $text['text_tags'] . ', ' . $cesta['menu_nazov'] . $cesta['menu_popis'] . ' | ' . $posli['keywords'];
						$posli['description'] = $text['text_popis'] . ', - ' . $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
						$posli['url'] = URL_ADRESA . htmlspecialchars('?menu=' . $_GET['menu']);
						$posli['image'] = $image;
					}

				}
				elseif ($cesta['menu_typ'] == 'clanok') {
					# clanok

					$posli['title'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
					$posli['keywords'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['keywords'];
					$posli['description'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['description'];
					$posli['url'] = URL_ADRESA . htmlspecialchars('?menu=' . $_GET['menu']);
					//$posli['image'] = '';  //uz mame
				}
				elseif ($cesta['menu_modul_id'] != '0') {
					# modul

					/*$posli['title'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
					$posli['keywords'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['keywords'];
					$posli['description'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['description'];
					$posli['url'] = URL_ADRESA . htmlspecialchars('?menu=' . $_GET['menu']);*/
					//$posli['image'] = '';  //uz mame
				}
				elseif ($cesta['menu_album'] != '0') {
					# GALERIA

					/*$posli['title'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
					$posli['keywords'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['keywords'];
					$posli['description'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['description'];
					$posli['url'] = URL_ADRESA . htmlspecialchars('?menu=' . $_GET['menu']);*/
					//$posli['image'] = '';  //uz mame
				}
				else {
					# URL

					$posli['title'] = $cesta['menu_nazov'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
					$posli['keywords'] = $cesta['menu_nazov'] . ', ' . $posli['keywords'];
					$posli['description'] = $cesta['menu_nazov'] . ', ' . $posli['description'];
					$posli['url'] = $cesta['menu_url'];
					//$posli['image'] = ''; // mame def. v hlava
				}

			}
		}
		elseif (isset($_GET['redirect'])) {

			$nac = $this->db->query('SELECT menu_id, menu_nazov, menu_url, menu_rodic, menu_typ, menu_popis FROM be_menu' . $this->prefix . ' 
										WHERE menu_url ="' . $this->db->real_escape_string($_GET['redirect']) . '" ');
			if ($nac->num_rows != FALSE) {
				$cesta = $nac->fetch_assoc();

				$posli['title'] = $cesta['menu_nazov'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
				$posli['keywords'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['keywords'];
				$posli['description'] = $cesta['menu_nazov'] . ' - ' . $cesta['menu_popis'] . ' | ' . $posli['description'];
				$posli['url'] = $cesta['menu_url'];
				//$posli['image'] = ''; // mame def. v hlava
			}
		}
		elseif (isset($_GET['article'])) {  // clanok
			
			$nac = $this->db->query('SELECT text_nazov, text_url, text_popis, text_tags, text_obrazok FROM be_text' . $this->prefix . ' 
										WHERE text_url ="' . $this->db->real_escape_string($_GET['article']) . '" ');
			if ($nac->num_rows != FALSE) {
				$cesta = $nac->fetch_assoc();

				#IMAGE
				if (file_exists(IMAGE_ARTICLES_NORMAL . htmlspecialchars($cesta['text_obrazok']))) {
					$image = URL_ADRESA . IMAGE_ARTICLES_NORMAL . htmlspecialchars($cesta['text_obrazok']);
				}
				else {
					//$image = // mame uz hore def. a vyplnene
					$image = $posli['image'];
				}

				$posli['title'] = $cesta['text_nazov'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
				$posli['keywords'] = $cesta['text_tags'] . ', - ' . $posli['keywords'];
				$posli['description'] = $cesta['text_popis'] . ' | ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
				$posli['url'] = URL_ADRESA . htmlspecialchars('?article=' . $cesta['text_url']);
				$posli['image'] = $image;
			}
		}
		elseif (isset($_GET['rss'])) { // rss
			# RSS --- este nemam
			// vlastne generovanie

		}
		elseif (isset($_GET['q'])) {
			# SEARCH

			$posli['title'] = 'Vyhladavanie na ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/'))));
			$posli['keywords'] = 'Hladam ' . htmlspecialchars($_GET['q']) . ' | ' . $posli['keywords'];
			$posli['description'] = 'Hladam ' . htmlspecialchars($_GET['q']) . ' | ' . $posli['description'];
			$posli['url'] = URL_ADRESA . htmlspecialchars('?q=' . $_GET['q']);
			//$posli['image'] = ;  // mame def.
		}
		elseif (isset($_GET['archiv'])) {
			# ARCHIVE


			$posli['title'] = 'Archiv ' . $_GET['archiv'] . ' na ' . ucfirst(substr($posli['url'], strpos($posli['url'], '.')+1, -(strlen($posli['url']) - strrpos($posli['url'], '/')))) . ' ' . $_GET['archiv'];
			$posli['keywords'] = 'Archiv, ' . $_GET['archiv'] . ', ' . $posli['keywords'];
			$posli['description'] = 'Archiv ' . $_GET['archiv'] . ' - ' . $posli['description'];
			$posli['url'] = URL_ADRESA . htmlspecialchars('?archiv=' . $_GET['archiv']);
			//$posli['image'] = ''; // mame def
		}
		else {
			# nic
		}

		return $posli;

	}

	
}





?>