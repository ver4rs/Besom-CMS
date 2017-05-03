<?php

# TOP najnovsie naj koment, naj citane, 

/*
class toparticle {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function najnovsieArticles ($pocet, $casTrvania = 'all') {

		$public =1;
		$typ = 'clanok';

		// $casTrvania zobrazenie aj podla hodin casu napr. 6hodin
		if ($casTrvania != 'all' AND $casTrvania != '') {
			$casZobraz = date('Y-m-d H:i:s', time()-(3600*$casTrvania)); // do SQL Klauzula WHERE datum >= $casZobraz
		}
		else {
			$casZobraz = '2013-01-01 00:00:01';
		}

/*
		$staryDatum = date('Y-m-d H:i:s', time()-(3600*12));
		echo $staryDatum . '</br>' . date('Y-m-d H:i:s'); 
*//*
		$query = $this->db->query('SELECT *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
									FROM be_text' . $this->prefix . ' t 
													JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
													JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									WHERE text_public ="' . $this->db->real_escape_string($public) . '" AND 
											menu_typ ="' . $this->db->real_escape_string($typ) . '" AND 
											text_datum >="' . $this->db->real_escape_string($casZobraz) . '"  
									ORDER BY text_datum DESC LIMIT 0, ' . $this->db->real_escape_string(intval($pocet)) . ' ');
		if ($query->num_rows != FALSE) {
			# OK SU
			$pocet = $query->num_rows;

			while ($row = $query->fetch_assoc()) {
				
				#
				include TEMPLATE_DESIGN . 'html_toparticle.php';
			}

		}
		else {
			# ERROR
			# NOT ARTICLE
			echo $this->lang['noarticle'];
		}	

	}

	public function najcitanejsieArticles ($pocet, $casTrvania = 'all') {

		$public =1;
		$typ = 'clanok';

		// $casTrvania zobrazenie aj podla hodin casu napr. 6hodin
		if ($casTrvania != 'all' AND $casTrvania != '') {
			$casZobraz = date('Y-m-d H:i:s', time()-(3600*$casTrvania)); // do SQL Klauzula WHERE datum >= $casZobraz
		}
		else {
			$casZobraz = '2013-01-01 00:00:01';
		}

		$query = $this->db->query('SELECT *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
									FROM be_text' . $this->prefix . ' t 
													JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
													JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									WHERE text_public ="' . $this->db->real_escape_string($public) . '" AND 
											menu_typ ="' . $this->db->real_escape_string($typ) . '" AND 
											text_datum >="' . $this->db->real_escape_string($casZobraz) . '"   
									ORDER BY text_visit DESC LIMIT 0, ' . $this->db->real_escape_string(intval($pocet)) . ' ');
		if ($query->num_rows != FALSE) {
			# OK SU
			$pocet = $query->num_rows;

			while ($row = $query->fetch_assoc()) {
				
				#
				include TEMPLATE_DESIGN . 'html_toparticle.php';
			}

		}
		else {
			# ERROR
			# NOT ARTICLE
			echo $this->lang['noarticle'];
		}	

	}

	public function najkomentArticles ($pocet, $casTrvania = 'all') {

		$public =1;
		$typ = 'clanok';

		// $casTrvania zobrazenie aj podla hodin casu napr. 6hodin
		if ($casTrvania != 'all' AND $casTrvania != '') {
			$casZobraz = date('Y-m-d H:i:s', time()-(3600*$casTrvania)); // do SQL Klauzula WHERE datum >= $casZobraz
		}
		else {
			$casZobraz = '2013-01-01 00:00:01';
		}

		$query = $this->db->query('SELECT *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
									FROM be_text' . $this->prefix . ' t 
													JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
													JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									WHERE text_public ="' . $this->db->real_escape_string($public) . '" AND 
											menu_typ ="' . $this->db->real_escape_string($typ) . '" AND 
											text_datum >="' . $this->db->real_escape_string($casZobraz) . '" 
									ORDER BY text_comment DESC LIMIT 0, ' . $this->db->real_escape_string(intval($pocet)) . ' ');
		if ($query->num_rows != FALSE) {
			# OK SU
			$pocet = $query->num_rows;

			while ($row = $query->fetch_assoc()) {
				
				#
				include TEMPLATE_DESIGN . 'html_toparticle.php';
			}

		}
		else {
			# ERROR
			# NOT ARTICLE
			echo $this->lang['noarticle'];
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

	}


}
*/








?>