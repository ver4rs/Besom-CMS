<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

# CLASS NASTAVENIA 

class nastavenia extends funkcia {
	
	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function test () {
		echo '<h1>NASTAVENIA</h1>';
		//echo $this->lang['nastavenia'];
	}



	



	/*
	*******
			JAZYK
	*******
	/*/

	/*
	public function jazykTab () {
		$query = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' ORDER BY jazyk_id DESC');
		if ($query->num_rows != FALSE) {
			# OK

			$poz = array();
			while ($tlac = $query->fetch_assoc()) {

				$id = $tlac['jazyk_id'];
				$naz = $tlac['jazyk_nazov'];
				$skratka = $tlac['jazyk_skratka'];
				$ikona = $tlac['jazyk_ikona'];
				$default = $tlac['jazyk_default'];
				$short = $tlac['jazyk_short'];

				$poz[$id][$naz][$skratka][$ikona][$default][$short][] = $tlac;
			}
			return $poz;
			 
		}
	}


*/
}


$nastavenia = new nastavenia($this->db, $this->prefix, $this->lang);










?>