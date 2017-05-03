<?php

#CLASS statistika

class pocitadlo {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function pocitajpristupy ($ip, $user_id, $datum, $host, $referer, $url, $useragent, $lang) {

		# ulozime data
		$query = $this->db->query('INSERT INTO be_pocitadlo' . $this->prefix . ' 
			(pocitadlo_id, pocitadlo_ip, pocitadlo_datum, pocitadlo_host, pocitadlo_referer, pocitadlo_url, pocitadlo_useragent, pocitadlo_lang)
					VALUES (NULL,
							"' . $this->db->real_escape_string($ip) . '",
							"' . $this->db->real_escape_string($datum) . '",
							"' . $this->db->real_escape_string($host) . '",
							"' . $this->db->real_escape_string($referer) . '",
							"' . $this->db->real_escape_string($url) . '",
							"' . $this->db->real_escape_string($useragent) . '",
							"' . $this->db->real_escape_string($lang) . '") ');
		# OK
		return;

	}

	public function pocitadloCelkovoPocet () {  // visitors
		$query = $this->db->query('SELECT pocitadlo_id FROM be_pocitadlo' . $this->prefix . ' ');
		if ($query->num_rows != FALSE) {
			$tlac = $query->num_rows;
			$tlac = number_format($tlac, '0', '', ' ');
		}
		else {
			$tlac = '0';
		}
		return $tlac;
	}

	public function pocitadloCelkovoPocet2 () {  // count ip
		$query = $this->db->query('SELECT COUNT(pocitadlo_ip) AS pocet FROM be_pocitadlo' . $this->prefix . ' ');
		if ($query->num_rows != FALSE) {
			$tlac = $query->num_rows;
			$tlac = number_format($tlac, '0', '', ' ');
		}
		else {
			$tlac = '0';
		}
		return $tlac;
	}

	public function pocitadloOnlineAlgoritmus($cas = '5', $typ = 's', $ip, $url, $host, $lang, $useragent) {
		// $cas = 5; //min. cas vymazania 

		#PRIPOCITAME NOVYCH --->>>> INSERT
		#VYMAZEME STARYCH    ---->>>> DELETE
		#    TU NIEEEE ------------>>>>   # NACITAME POCET     ---->>>>>  SELECT

		// $cas   ///// TYP   second/minute
		if ($typ == 's') {  // s   second
			$date = date('Y-m-d H:i:s', time() + $cas);
		}
		else { // m minute
			//$currentDate = date('Y-m-d H:i:s');
			//$prevod = strtotime('s', $currentDate) + (60* $cas);
			//$date = strtotime('Y-m-d H:i:s', $prevod);
			$date = date('Y-m-d H:i:s', time() + ($cas * 60)); 
		}

		# CI UZ JE TAKA IP V ONLINE
		$je  =$this->db->query('SELECT online_ip, online_id FROM be_online' . $this->prefix . ' 
									WHERE online_ip ="' . $this->db->real_escape_string($ip) . '" ');
		if ($je->num_rows != FALSE) {
			# JE
			#UPDATE
			$zmen = $this->db->query('UPDATE be_online' . $this->prefix . ' SET 
											online_date ="' . $this->db->real_escape_string($date) . '",
											online_url ="' . $this->db->real_escape_string($url) . '" 
										WHERE online_ip ="' . $this->db->real_escape_string($ip) . '" ');
		}
		else {

			#1   -->>> INSERT NOVE  --->> ZISTIME CI JE UZ TAKE   -->>> UNIQUE
			$pripocitaj = $this->db->query('INSERT INTO be_online' . $this->prefix . ' (
											online_id, online_ip, online_date, online_url, online_host, online_lang, online_useragent) 
										VALUES (NULL,
												"' . $this->db->real_escape_string($ip) . '",
												"' . $this->db->real_escape_string($date) . '",
												"' . $this->db->real_escape_string($url) . '",
												"' . $this->db->real_escape_string($host) . '",
												"' . $this->db->real_escape_string($lang) . '",
												"' . $this->db->real_escape_string($useragent) . '") ');
		}
		#2   --> DELETE STARE

		# DELETE
		$del = $this->db->query('DELETE FROM be_online' . $this->prefix . ' 
									WHERE online_date < "' . $this->db->real_escape_string(date('Y-m-d H:i:s')) . '"  ');


	}

	public function pocitadloOnlineStav () {  // online 
		$query = $this->db->query('SELECT COUNT(online_ip) AS pocet FROM be_online' . $this->prefix . ' ');
		if ($query->num_rows != FALSE) {
			$tlac = $query->num_rows;
			$tlac = number_format($tlac, '0', '', ' ');
		}
		else {
			$tlac = '0';
		}
		return $tlac;
	}


/*
	public function zobrazpristupy ($kedy = 'all') {

		# $kedy = 'all' // dnes vcera tyzden, celkovo - cas 
		# dnes = 
		# vcera = - 1 day  alebo time()-(3600*24) = 1 den
		# tyzden = - 7 day
		
		# CELKOVO
		$query = $this->db->query('SELECT pocitadlo_datum, pocitadlo_ip FROM be_pocitadlo' . $this->prefix . ' 
									ORDER BY pocitadlo_datum DESC ');

		echo 'Celkovo ip' . $query->num_rows . '</br>';


		$query1 = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS poc_ip, pocitadlo_datum  FROM be_pocitadlo' . $this->prefix . ' 
									ORDER BY pocitadlo_datum DESC ');
		$quer2 = $query1->fetch_assoc();
		echo 'Unikatne i' . $quer2['poc_ip'] . '</br>';

		$query2 = $this->db->query('SELECT pocitadlo_datum  FROM be_pocitadlo' . $this->prefix . ' 
									ORDER BY pocitadlo_datum ASC LIMIT 1');
		$quer = $query2->fetch_assoc();
		echo 'Zaciatok  pocitania' . date('h:i:s d:m:Y', strtotime($quer['pocitadlo_datum'])) . '</br>';
		echo 'teraz ' . date('h:i:s d:m:Y') . '</br>';
	



	}

*/





}





?>