<?php

# CLASS MODUL ARCHIV

class archiv {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function archivskript ($pocet = '10', $typ = 'clanok', $pozicia = 'hore') {

		// $pocet = '10'; // pocet zobrazeni mesiacov, pocet riadkov
		// $typ = clanok, text, url  //typ textu vyber
		// $pozicia = hore, vrch menu kde sa zobrazuje
		$public =1;

		$nac = $this->db->query('SELECT text_id, text_datum, text_public, t.menu_id, m.menu_id, m.menu_nazov, m.menu_typ, m.menu_pozicia 
									FROM be_text' . $this->prefix . ' t 
										JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
									WHERE menu_typ ="' . $this->db->real_escape_string($typ) . '" AND 
										menu_pozicia ="' . $this->db->real_escape_string($pozicia) . '" AND 
										text_public ="' . $this->db->real_escape_string($public) . '" 
									ORDER BY text_datum DESC LIMIT 0, ' . $this->db->real_escape_string($pocet) . ' ');
		if ($nac->num_rows != FALSE) {
			# OK
			$pole = array();
			$polee = array();

			while ($nacitaj = $nac->fetch_assoc()) {
				
				$rok = date('Y', strtotime($nacitaj['text_datum']));
				$mesiac = date('m', strtotime($nacitaj['text_datum']));

				$pole[$rok][$mesiac][] = $nacitaj;

				$polee[$rok] = $nacitaj;
			}

			$pocet_clankov_rok = 0;
			$pocet_rok =0;
			foreach($pole as $rok_z => $mesiac_z) {

	    		

		    	foreach($mesiac_z as $datum_mesiac => $pocet) {

		    		$datum_mesiac_cislo = $datum_mesiac;
		        	$pocet_clankov_mesiac = count($pocet);

		        	$pocet_rok = $pocet_rok + $pocet_clankov_mesiac;
		        	$pocet_clankov_rok = count($pocet) + $pocet_clankov_rok;


		        	$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

					$sk_nazov = array("január", "ferbuár", "marec", "apríl", "máj", "jún", "júl", "august", "september", "október", "november", "december");
					$aj_nazov = array("januar", "ferbuar", "march", "april", "mai", "juni", "juli", "august", "september", "october", "november", "dezember");
					$de_nazov = array("januar", "ferbuar", "march", "april", "mai", "juni", "juli", "august", "september", "october", "november", "dezember");

					$datum_mesiac = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);

					?>
					<li style="padding-left: 20px;">
						<a href="<?php echo URL_ADRESA . htmlspecialchars('?archiv=' . $rok_z . '/' . $datum_mesiac_cislo); ?>" title="<?php echo htmlspecialchars($datum_mesiac . ' ' . $rok_z); ?>" id=""><?php echo htmlspecialchars($datum_mesiac . ' ' . $rok_z); ?></a>
						<span><?php echo htmlspecialchars('(' . $pocet_clankov_mesiac . ')'); ?></span>
					</li>
		            <?php
		            

		    	}
		    	?>
				<li>
					<h6><a href="<?php echo URL_ADRESA . htmlspecialchars('?archiv=' . $rok_z); ?>" title="<?php echo htmlspecialchars($rok_z); ?>" id=""><?php echo htmlspecialchars($rok_z); ?></a>
					<span><?php echo htmlspecialchars('(' . $pocet_clankov_rok . ')'); ?></span></h6>
				</li>
		    	<?php
		    	//echo $rok_z . ' (' . $pocet_clankov_rok . ')' . "<br>";
		    	$pocet_clankov_rok =0;

			}
			


		}
		else {
			# ERROR
			# nenasiel nic
			echo $this->lang['noarchiv'];
		}



	}

	public function archivzobraz ($pocet = '10', $typ = 'clanok', $pozicia = 'hore') {

		?>
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['widnadpisarchiv']); ?></h5></div>
					<p><ul id="archivtlac"><?php echo $this->archivskript($pocet, $typ, $pozicia); ?></ul></p>
		</div>
		<?php
	}


}

#$archiv = new archiv($this->db, $this->prefix, $this->lang);
#$archiv->archivzobraz('10', 'clanok', 'hore') // zobrazy archiv 





?>
