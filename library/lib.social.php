<?php

# SOCIAL

/*
class social {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function socialfanpage () {

		$fan = 1; // 1 = fan page
		$stav = 1; // povolene zobrazenie

		$nacitaj = $this->db->query('SELECT * FROM be_social' . $this->prefix . ' 
										WHERE social_typ ="' . $this->db->real_escape_string($fan) . '" AND 
												social_stav ="' . $this->db->real_escape_string($stav) . '" 
										ORDER BY social_zorad DESC ');
		if ($nacitaj->num_rows != FALSE) {
			# OK
			//$social_fan_page = array();

			/*?><div id="socialfanpage"><?php*//*

			while ($social_fanpage = $nacitaj->fetch_assoc()) {

				/*
				$social_nazov = $social_fanpage['social_nazov'];
				$social_img = $social_fanpage['social_img'];
				$social_url = $social_fanpage['social_url'];
				$social_target = $social_fanpage['social_target'];

				$social_fan_page[$social_nazov][$social_img][$social_url][$social_target][];
				*/
/*
				include TEMPLATE_DESIGN . 'html_social_fanpage.php';

			}
			/*?></div><?php*/

			//return $social_fan_page;
/*
		}
		else {
			# niesu
			echo $this->lang['nosocial'];
		}

	}


}
*/

?>