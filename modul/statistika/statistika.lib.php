<?php

#CLASS statistika

class statistika {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function zobrazpristupyCelkovo ($kedy = 'all') {

		# $kedy = 'all' // dnes vcera tyzden, celkovo - cas 
		# dnes = 
		# vcera = - 1 day  alebo time()-(3600*24) = 1 den
		# tyzden = - 7 day
		
		# CELKOVO
		$query = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS pocetcelkovo_ip, 
										  COUNT(DISTINCT pocitadlo_id) AS pocetcelkovo_zob, 
										  MIN(pocitadlo_datum) AS najmensidatum, 
										  MAX(pocitadlo_datum) AS poslednypristup 
									FROM be_pocitadlo' . $this->prefix . ' 
									ORDER BY pocitadlo_datum DESC ');
	
		$row = $query->fetch_assoc();

		# CASOVY ROZDIEL
		$den = ((((strtotime(date('Y-m-d H:i:s')) - strtotime($row['najmensidatum'])) /60) /60) /24);
		$hodina = ('0' . substr($den, strpos($den, '.'))) * 24;
		$minuta = substr($hodina, strpos($hodina, '.')) * 60;

		# SKRATENIE CASU 
		$den = substr($den, 0, -(strlen($den) - strpos($den, '.')));
		$hodina = substr($hodina, 0, -(strlen($hodina) - strpos($hodina, '.')));
		$minuta = substr($minuta, 0, -(strlen($minuta) - strpos($minuta, '.')));

		?>

		<h4>Celkovo</h4>
		<table>
		<tr><td style="font-weight: bold;">Pocet navstev:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_ip']); ?></td></tr>
		<tr><td style="font-weight: bold;">Pocet zobrazeni:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_zob']); ?></td></tr>

		<tr><td style="font-weight: bold;">Posledny pristup:</td><td><?php echo htmlspecialchars(date('H:i:s d.m.Y',strtotime($row['poslednypristup']))); ?></td></tr>
		<tr><td style="font-weight: bold;">Start pocitania:</td><td><?php echo htmlspecialchars(date('H:i:s d.m.Y',strtotime($row['najmensidatum']))); ?></td></tr>
		<tr><td style="font-weight: bold;">Preslo uz:</td><td><?php echo htmlspecialchars($den . ' dni ' . $hodina . ' hodin ' . $minuta . ' minut'); ?></td></tr>
		</table>
		<?php

	}

	public function zobrazpristupyDnes ($kedy = 'all') {

		# $kedy = 'all' // dnes vcera tyzden, celkovo - cas 
		# dnes = 
		# vcera = - 1 day  alebo time()-(3600*24) = 1 den
		# tyzden = - 7 day
		
		# CELKOVO
		$query = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS pocetcelkovo_ip, 
										  COUNT(DISTINCT pocitadlo_id) AS pocetcelkovo_zob,
										  MAX(pocitadlo_datum) AS poslednypristup 
									FROM be_pocitadlo' . $this->prefix . ' 
									WHERE DATE(pocitadlo_datum) = DATE(NOW())
									ORDER BY pocitadlo_datum DESC ');
	
		$row = $query->fetch_assoc();

		?>
		<h4>Dnes</h4>
		<table>
		<tr><td style="font-weight: bold;">Pocet navstev:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_ip']); ?></td></tr>
		<tr><td style="font-weight: bold;">Pocet zobrazeni:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_zob']); ?></td></tr>
		</table>
		<?php

	}

	public function zobrazpristupyVcera ($kedy = 'all') {

		# $kedy = 'all' // dnes vcera tyzden, celkovo - cas 
		# dnes = 
		# vcera = - 1 day  alebo time()-(3600*24) = 1 den
		# tyzden = - 7 day
		
		# CELKOVO
		$query = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS pocetcelkovo_ip, 
										  COUNT(DISTINCT pocitadlo_id) AS pocetcelkovo_zob,
										  MAX(pocitadlo_datum) AS poslednypristup 
									FROM be_pocitadlo' . $this->prefix . ' 
									WHERE DATE(pocitadlo_datum) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
									ORDER BY pocitadlo_datum DESC ');
	
		$row = $query->fetch_assoc();

		?>
		<h4>Vcera</h4>
		<table>
		<tr><td style="font-weight: bold;">Pocet navstev:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_ip']); ?></td></tr>
		<tr><td style="font-weight: bold;">Pocet zobrazeni:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_zob']); ?></td></tr>
		</table>
		<?php

	}

	public function zobrazpristupyTyzden ($kedy = 'all') {

		# $kedy = 'all' // dnes vcera tyzden, celkovo - cas 
		# dnes = 
		# vcera = - 1 day  alebo time()-(3600*24) = 1 den
		# tyzden = - 7 day
		
		# CELKOVO
		$query = $this->db->query('SELECT COUNT(DISTINCT pocitadlo_ip) AS pocetcelkovo_ip, 
										  COUNT(DISTINCT pocitadlo_id) AS pocetcelkovo_zob,
										  MAX(pocitadlo_datum) AS poslednypristup 
									FROM be_pocitadlo' . $this->prefix . ' 
									WHERE pocitadlo_datum >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
									ORDER BY pocitadlo_datum DESC ');
	
		$row = $query->fetch_assoc();

		?>
		<h4>Tyzden</h4>
		<table>
		<tr><td style="font-weight: bold;">Pocet navstev:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_ip']); ?></td></tr>
		<tr><td style="font-weight: bold;">Pocet zobrazeni:</td><td><?php echo htmlspecialchars($row['pocetcelkovo_zob']); ?></td></tr>
		</table>
		<?php

	}

	public function zobrazAktualnyHost () {

		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
		    $browser = 'Internet explorer';
		elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
		    $browser = 'Mozilla Firefox';
		elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
		    $browser = 'Google Chrome';
		elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
		    $browser = "Opera Mini";
		elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
		    $browser = "Opera";
		elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
		    $browser = "Safari";
		else {
		    $browser = 'None';
		}

		


		$uagent = $_SERVER['HTTP_USER_AGENT'] . "<br/>";

		function os_info($uagent)
		{
		    // the order of this array is important
		    global $uagent;
		    $oses   = array(
		        'Win311' => 'Win16',
		        'Win95' => '(Windows 95)|(Win95)|(Windows_95)',
		        'WinME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',
		        'Win98' => '(Windows 98)|(Win98)',
		        'Win2000' => '(Windows NT 5.0)|(Windows 2000)',
		        'WinXP' => '(Windows NT 5.1)|(Windows XP)',
		        'WinServer2003' => '(Windows NT 5.2)',
		        'WinVista' => '(Windows NT 6.0)',
		        'Windows 7' => '(Windows NT 6.1)',
		        'Windows 8' => '(Windows NT 6.2)',
		        'WinNT' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
		        'OpenBSD' => 'OpenBSD',
		        'SunOS' => 'SunOS',
		        'Ubuntu' => 'Ubuntu',
		        'Android' => 'Android',
		        'Linux' => '(Linux)|(X11)',
		        'iPhone' => 'iPhone',
		        'iPad' => 'iPad',
		        'MacOS' => '(Mac_PowerPC)|(Macintosh)',
		        'QNX' => 'QNX',
		        'BeOS' => 'BeOS',
		        'OS2' => 'OS/2',
		        'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
		    );
		    $uagent = strtolower($uagent ? $uagent : $_SERVER['HTTP_USER_AGENT']);
		    foreach ($oses as $os => $pattern)
		        if (preg_match('/' . $pattern . '/i', $uagent))
		            return $os;
		    return 'Unknown';
		}

		$lang1 = array(
			'af', // afrikaans.
			'ar', // arabic.
			'bg', // bulgarian.
			'ca', // catalan.
			'cs', // czech.
			'da', // danish.
			'de', // german.
			'el', // greek.
			'en', // english.
			'es', // spanish.
			'et', // estonian.
			'fi', // finnish.
			'fr', // french.
			'gl', // galician.
			'he', // hebrew.
			'hi', // hindi.
			'hr', // croatian.
			'hu', // hungarian.
			'id', // indonesian.
			'it', // italian.
			'ja', // japanese.
			'ko', // korean.
			'ka', // georgian.
			'lt', // lithuanian.
			'lv', // latvian.
			'ms', // malay.
			'nl', // dutch.
			'no', // norwegian.
			'pl', // polish.
			'pt', // portuguese.
			'ro', // romanian.
			'ru', // russian.
			'sk', // slovak.
			'sl', // slovenian.
			'sq', // albanian.
			'sr', // serbian.
			'sv', // swedish.
			'th', // thai.
			'tr', // turkish.
			'uk', // ukrainian.
			'zh' // chinese.
			);
			$lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
		   
		?>
		<table>
		<h4>Navstevnik</h4>
		<tr><td style="font-weight: bold;">IP adresa:</td><td><?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']); ?></td></tr>
		<tr><td style="font-weight: bold;">Prehliadac:</td><td><?php echo htmlspecialchars($browser); ?></td></tr>
		<tr><td style="font-weight: bold;">OS:</td><td><?php echo htmlspecialchars(os_info($uagent)); ?></td></tr>
		<tr><td style="font-weight: bold;">Jazyk:</td><td><?php echo htmlspecialchars($lang); ?></td></tr>
		<tr><td style="font-weight: bold;">Nazov PC:</td><td><?php echo htmlspecialchars(gethostbyaddr($_SERVER["REMOTE_ADDR"])); ?></td></tr>
		</table>
		<?php

	}



	public function zobrazStatistikuMala () {

		?>
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars('Statistika'); ?></h5></div>
		<?php
		# CELKOVO
		$this->zobrazpristupyCelkovo();

		# DNES
		$this->zobrazpristupyDnes();

		# VCERA
		$this->zobrazpristupyVcera();

		# TYZDEN
		$this->zobrazpristupyTyzden();

		# UZIVATEL AKTUALNY
		$this->zobrazAktualnyHost();

		?>
		</div>
		<?php
		

	}

	public function __destruct() {
    	//echo $this->name." still weighed ".$this->weight." units and died.";
	}

}





?>