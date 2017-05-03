<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

# DOMOV SYSTEM


class domov {

	private $db, $prefix;
	public $lang, $lg, $lang_short;

	public function __construct ($conn, $prefix, $lang, $lg, $lang_short) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
		$this->lg = $lg;
		$this->flag = $lang_short;

		if ($lg[$this->flag]['test'] == '') {
			$this->flag = 'sk';
		}

		//echo $lg[$this->flag]['test'];
	}

	public function nadpis () {
		return $this->lg[$this->flag]['nazov'];
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

	public function pocitadloMinDate () {  // count ip
		$query = $this->db->query('SELECT MIN(pocitadlo_datum) AS najmensidatum FROM be_pocitadlo' . $this->prefix . ' ');
		if ($query->num_rows != FALSE) {
			$tlac1 = $query->fetch_assoc();
			$tlac = date('d. m Y H:i:s',strtotime($tlac1['najmensidatum']));
		}
		else {
			$tlac = '0';
		}
		return $tlac;
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



	public function pocitadloDnes ($typ = '1') {

		// $typ = 1 - navstevy
		// $typ = 2 - zobrazenia

		$nac = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS pocetcelkovo_ip, COUNT(DISTINCT pocitadlo_id) AS pocetcelkovo_zob FROM be_pocitadlo' . $this->prefix . '
							WHERE DATE(pocitadlo_datum) = DATE(NOW()) ');
		if ($nac->num_rows != FALSE) {
			$riadok2 = $nac->fetch_assoc();

			//echo 'Celkom pocet ip adries je ' . $riadok2['pocetcelkovo_ip'] . '</br>';
			//echo 'Celkom pocet zobrazeni ' . $riadok2['pocetcelkovo_zob'] . '</br>';

			if ($typ == '1') {
				$tlac = $riadok2['pocetcelkovo_ip'];
			}
			else {
				$tlac = $riadok2['pocetcelkovo_zob'];
			}

			return $tlac;
			
		}
		else {
			return false;
		}


	}


	public function pocitadloVcera ($typ = '1') {

		// $typ = 1 - navstevy
		// $typ = 2 - zobrazenia

		# VSERA
		$nac = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS pocetcelkovo_ip, COUNT(DISTINCT pocitadlo_id) AS pocetcelkovo_zob FROM be_pocitadlo' . $this->prefix . '
							WHERE DATE(pocitadlo_datum) = DATE_ADD(CURDATE(), INTERVAL -1 DAY) ');
		if ($nac->num_rows != FALSE) {
			$riadok3 = $nac->fetch_assoc();

			//echo 'Celkom pocet ip adries je ' . $riadok3['pocetcelkovo_ip'] . '</br>';
			//echo 'Celkom pocet zobrazeni ' . $riadok3['pocetcelkovo_zob'] . '</br>';

			if ($typ == '1') {
				$tlac = $riadok3['pocetcelkovo_ip'];
			}
			else {
				$tlac = $riadok3['pocetcelkovo_zob'];
			}

			return $tlac;
			

		}
		else {
			return false;
		}

	}
	


	public function pocitadloTyzden ($typ = '1') {	

		// $typ = 1 - navstevy
		// $typ = 2 - zobrazenia

		# TYZDEN
		$nac = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS pocetcelkovo_ip, COUNT(DISTINCT pocitadlo_id) AS pocetcelkovo_zob FROM be_pocitadlo' . $this->prefix . '
							WHERE pocitadlo_datum >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) ');
		if ($nac->num_rows != FALSE) {
			$riadok4 = $nac->fetch_assoc();

			//echo 'Celkom pocet ip adries je ' . $riadok4['pocetcelkovo_ip'] . '</br>';
			//echo 'Celkom pocet zobrazeni ' . $riadok4['pocetcelkovo_zob'] . '</br>';

			if ($typ == '1') {
				$tlac = $riadok4['pocetcelkovo_ip'];
			}
			else {
				$tlac = $riadok4['pocetcelkovo_zob'];
			}

			return $tlac;

		}
		else {
			return false;
		}

	}







}

//$domov = new domov($this->db, $this->prefix, $this->lang);
$domov = new domov($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);



?>