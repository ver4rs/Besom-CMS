<?php

class socialS {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function socialShare () {


		?>
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['widnadpissocialshare']); ?></h5></div>
					<p>
						<?php 
							$this->socialShare1(); 
						?>
					</p>

		</div>
		<?php

	}



	public function socialShare1 () {

		$share = 2; // 2 = share
		$stav = 1; // povolene zobrazenie

		$nacitaj1 = $this->db->query('SELECT * FROM be_social' . $this->prefix . ' 
										WHERE social_typ ="' . $this->db->real_escape_string($share) . '" AND 
												social_stav ="' . $this->db->real_escape_string($stav) . '" 
										ORDER BY social_zorad ASC ');
		if ($nacitaj1->num_rows != FALSE) {
			# OK
			//$social_fan_page = array();

			/*?><div id="socialfanpage"><?php*/

			$host = $_SERVER['HTTP_HOST'];
			$self = $_SERVER['PHP_SELF'];
			$query = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
			$url = !empty($query) ? "http://$host$self?$query" : "http://$host$self";

			while ($social_share = $nacitaj1->fetch_assoc()) {

				// print,
				// email
				if ($social_share['social_url'] == 'email') {
					# send email
					# data - prijimatel, odosielatel - meno, - predmet, text 
				}
				elseif ($social_share['social_url'] == 'print') {
					# tlacit
					# href=    onClick="window.print();return false"
					# href=javascript:window.print()
				}
				else {

				}

				$host = $_SERVER['HTTP_HOST'];
				$self = $_SERVER['PHP_SELF'];
				$query = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
				$url = !empty($query) ? "http://$host$self?$query" : "http://$host$self";

				include TEMPLATE_DESIGN . 'html_social_share.php';

			}
			/*?></div><?php*/

			//return $social_fan_page;

		}
		else {
			# niesu
			echo $this->lang['nosocial'];
		}

	}


}


#$social = new social($this->db, $this->prefix, $this->lang);
#$social->socialfanpage() // zobrazi vsetko socialne siete +newsletter

# $cosial->socialfanpage1() // zobrazi len socialne siete
# $cosial->newsletter() // zobrazi  +newsletter







?>