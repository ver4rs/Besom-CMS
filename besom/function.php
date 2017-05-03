<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

# CLASS SYSTEM

class funkcia {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function pre_r ($exception) {

		echo '<pre>';
		print_r($exception);
		echo '</pre>';

		//return;

	}

	public function redirect($site = '') {

		$url = URL_ADRESA;

		/*if (empty($site) {
			header("location: " . $_SERVER['HTTP_REFERER']);
			exit();	
		}
		else {*/
			header("Location: " . $url . $site);
			exit();
		//}

	}

	public function errorMessage () {

		#ERROR MESSAGE


	}


}	