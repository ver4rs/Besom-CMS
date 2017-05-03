<?php




class novinkovac/* extends PHPMailer*/ {

	private $db, $prefix;
	public $lang1, $odosielatel, $nick, $meno, $nadpis, $url, $titul, $stav, $privacy, $email, $post, $token, $body;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function newsletter () {


		?>
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['newsletter']); ?></h5></div>
					<?php

					$this->newsletter1();
					?>
		</div>
		<?php

	}

	public function newsletter1 () {

		?>
						<p class="text-align: justify; "><img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars('newsletter.png'); ?>" alt="<?php echo htmlspecialchars($this->lang['newsletter']); ?>" style="width: 50px; vertical-align: middle;" title="<?php echo htmlspecialchars($this->lang['newsletter']); ?>">
						<?php echo htmlspecialchars($this->lang['newspopis']); ?><p>
						<form action="" method="post">
							<input type="text" class="texnews" name="subsc" size="20" maxlength="120" placeholder="<?php echo htmlspecialchars($this->lang['newsplaceholder']); ?>">
							<input type="submit" value="<?php echo htmlspecialchars($this->lang['newssubmit']); ?>" class="subnews">
						</form>
						<?php $this->sendEmail() ?>
		<?php
	}




	public function sendEmail () {
		$this->post = 'no';

						if (isset($_POST['subsc'])) {
							if (!empty($_POST['subsc']) AND filter_var($_POST['subsc'], FILTER_VALIDATE_EMAIL)) {

								$exist = $this->db->query('SELECT newsletter_email FROM be_newsletter' . $this->prefix . ' 
													WHERE newsletter_email ="' . $this->db->real_escape_string($_POST['subsc']) . '" ');
								if ($exist->num_rows != FALSE) {
									# UZ EXISTUJE
									?><span style="color: red; text-align: justifi;">Tento email uz mame. Skuste zadat iny.</span><?php
								}
								else {

									$this->post = 'yes';

									# SYSTEM - NASTAVENIA
									$sys = $this->db->query('SELECT system_newsletter, system_admin_email, system_title, system_nadpis, system_url, system_admin_firstmeno, system_admin_lastmeno, system_admin_meno  
																FROM be_system' . $this->prefix . ' 
																WHERE system_newsletter != false AND 
																	system_admin_email != false AND 
																	system_title != false AND 
																	system_nadpis != false AND 
																	system_url != false AND 
																	system_admin_firstmeno != false AND 
																	system_admin_lastmeno != false AND 
																	system_admin_meno != false 
																ORDER BY system_id DESC LIMIT 1 ');
									if ($sys->num_rows != FALSE) {
										$system = $sys->fetch_assoc();

										$this->odosielatel = $system['system_admin_email'];
										$this->nick = $system['system_admin_meno'];
										$this->meno = $system['system_admin_firstmeno'] . ' ' . $system['system_admin_lastmeno'];
										$this->titul = $system['system_title'];
										$this->nadpis = $system['system_nadpis'];
										$this->url = $system['system_url'];
										$this->body = '';

										if ($system['system_newsletter'] == '1') {
											$this->stav = 2;
										}
										else {
											// 0 - netreba aktivovat
											$this->stav = 1;
										}
									}
									else {
										$this->stav = 2; // treba emailom aktivovat -- dam do systemu
										$this->odosielatel = 'besom@besom.sk';
										$this->nick = 'Besom.sk';
										$this->meno = 'Martin Sekerak';
										$this->titul = 'Besom.sk';
										$this->nadpis = 'Besom CMS';
										$this->url = 'http://www.besom.sk';
										$this->body = 'Tento email bol odoslany z adresy besom@besom.sk, chybamohla byt sposobena kvoli tomu ze server domeny od ktorej ste cakali email, nemohol odoslat. Email odosielatela nepovazujte za spamovz email, ale text ano.';
									} # END SUSTEM SETTINGS

									$this->email = $_POST['subsc'];
									$this->privacy =1;

									# TOKEN
									$a = array("A","B","C","D","E","F","G","H","J","K","L","M",
					                            "N","P","Q","R","S","T","U","V","W","X","Y","Z",
					                            "1","2","3","4","5","6","7","8","9",
					                            "a","b","c","d","e","f","g","h","j","k","l","m",
					                            "n","p","q","r","s","t","u","v","w","x","y","z");
									$pocet = '8';
									$keys = array();
									for ($i=0; $i <= $pocet ; $i++) { 
										$x = mt_rand('1', count($a)-1);

										if(!in_array($x, $keys)) {
					               			$keys[] = $x;
					            		}
									}

									$token = '';
							        foreach($keys as $key){
							           $token .= $a[$key];
							        }
							        // $token
							        $this->token = $token;

									$uloz = $this->db->query('INSERT IGNORE INTO be_newsletter' . $this->prefix . ' (newsletter_id, newsletter_email, newsletter_privacy, newsletter_stav, newsletter_token, newsletter_datum)
											VALUES (NULL,
													"' . $this->db->real_escape_string($this->email) . '",
													"' . $this->db->real_escape_string(intval($this->privacy)) . '",
													"' . $this->db->real_escape_string(intval($this->stav)) . '",
													"' . $this->db->real_escape_string($this->token) . '",
													"' . $this->db->real_escape_string(date('Y-m-d H:i:s')) . '") ');

									# POSLEME EMAIL, NA ADRESU

								}	

							}
							else {
								# NO ERROR, VALID EMAIL
								?><span style="color: red; text-align: justifi;">Zadajte platny email!</span><?php
							}
						}

	}


	public function meno () {
		return $this->meno;
	}

	public function nick () {
		return $this->nick;
	}

	public function odosielatel () {
		return $this->odosielatel;
	}
	public function email () {
		return $this->email;
	}
	public function titul () {
		return $this->titul;
	}
	public function nadpis () {
		return $this->nadpis;
	}
	public function url () {
		return $this->url;
	}
	public function stav () {
		return $this->stav;
	}
	public function privacy () {
		return $this->privacy;
	}
	public function hash () {
		return $this->token;
	}
	public function body () {
		return $this->body;
	}
	public function post() {
		return $this->post;
	}
}


#$newsletter = new newsletter($this->db, $this->prefix, $this->lang);
#$newsletter->socialfanpage() // zobrazi vsetko socialne siete +newsletter

# $cosial->socialfanpage1() // zobrazi len socialne siete
# $cosial->newsletter() // zobrazi  +newsletter







?>