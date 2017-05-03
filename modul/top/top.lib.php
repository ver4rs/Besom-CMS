<?php

# TOP najnovsie naj koment, naj citane, 

class toparticle {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang/*, $widget_id**/) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
		//$this->widget_id = $widget_id;
	}


	public function najnovsieArticles ($pocet = '10', $casTrvania = 'all') {

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
*/
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

	public function najcitanejsieArticles ($pocet = '10', $casTrvania = 'all') {

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

	public function najkomentArticles ($pocet = '10', $casTrvania = 'all') {

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

	public function topnews ($pocet = '10', $casTrvania = 'all') {

		?>
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['widnadpistopnews']); ?></h5></div>

					<?php echo $this->najnovsieArticles($pocet, $casTrvania); ?>
		</div>
		<?php
	}

	public function topvisit ($pocet = '10', $casTrvania = 'all') {

		?>
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['widnadpistopvidene']); ?></h5></div>

					<?php echo $this->najcitanejsieArticles($pocet, $casTrvania); ?>
		</div>
		<?php
	}

	public function topcomment ($pocet = '10', $casTrvania = 'all') {

		?>
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['widnadpistopkoment']); ?></h5></div>

					<?php echo $this->najkomentArticles($pocet, $casTrvania); ?>
		</div>
		<?php
	}

	public function typzobrazenia ($cislo) {

		if ($cislo == '1') {
			$this->topnews('10', $casTrvania = 'all');
		}
		elseif ($cislo == '2') {
			$this->topvisit('10', $casTrvania = 'all');
		}
		elseif ($cislo == '3') {
			$this->topcomment('10', $casTrvania = 'all');
		}
		elseif ($cislo == '4') {
			
?>
<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul class="tabs-control ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
		<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="true"><a href="#tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">News</a></li>
		
		<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false"><a href="#tabs-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Popular</a></li>
	</ul>
							
	<ul class="tabs-content">
		<li id="tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false">
			<ul>
			<?php
			$this->najnovsieArticles('5','all');
			?>
</ul>
		</li>
								
								

								<li id="tabs-2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
									<ul>
			<?php
			$this->najcitanejsieArticles('5', 'all');
?>
</ul>	
								</li>
							
						</ul>
						</div>
				</div>
<?php
			//$this->najkomentArticles($pocet, $casTrvania);

		}
		else {
			?>
			<div class="widget-bound newsletter-widget">
				<div class="heading-light"><h5><?php echo htmlspecialchars('spolu kombinovane. Pracujem natom'); ?></h5></div>
			</div>
			<?php
		}

	}




}



#$toparticle = new toparticle($this->db, $this->prefix, $this->lang);
#$toparticle->typzobrazenia();


# $toparticle->topnews('5', 'all') // zobrazi najnovsie clanky      // pocet clankov, all-cas zobrazenia v hodinach
# $toparticle->topvisit('5', 'all') // zobrazi nacitanejsie 		// pocet clankov, all-cas zobrazenia v hodinach
# $toparticle->topcomment('5', 'all') // zobrazi najomentovanejsie		// pocet clankov, all-cas zobrazenia v hodinach









?>