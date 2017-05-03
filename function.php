<?php


# FUNCTION


class funkcia {

	private $db, $prefix;
	public $lang;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function datum ($datum, $jednotka, $typ) {

		# H   i   s  -> casova jednotka
		# d   m   Y  -> datumova jednotka

		if ($jednotka == 'H') {
			
			$cas_hodina = date('H', strtotime($datum));
		}
		elseif ($jednotka == 'i') {
			
			$cas_minuta = date('i', strtotime($datum));
		}
		elseif ($jednotka == 's') {

			$cas_sekunda = date('s', strtotime($datum));
		}
		elseif ($jednotka == 'd') {
			
			$datum_den = date('d', strtotime($datum));

			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}
		}
		elseif ($jednotka == 'm') {
			
			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);
		}
		elseif ($jednotka == 'Y') {
			$datum_rok = date('Y', strtotime($datum));
		}
		else {

		}

		if ($typ == '1') {
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

			$tlac = $cas_hodina = date('H', strtotime($datum)) . ':' . $cas_minuta = date('i', strtotime($datum)) . ' ' . $datum_den . '. ' . $datum_mesiac . ' ' . $datum_rok = date('Y', strtotime($datum));
			
			return $tlac;
		}


		//datum
		//$datum_den = substr($datum, strrpos($datum, '-')+1); //den
		//$datum_mesiac = substr($datum, strpos($datum, '-')+1, -(strlen($datum) - strrpos($datum, '-')));  //mesiac
		//$datum_rok = substr($datum, 0, -(strlen($datum) - strpos($datum, '-')));  //rok
	


	}





}



?>