<?php

# CLASS MODULE

class modul {

	protected $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function nacitajmodulsubor ($podadresar = 'modul/', $adresar, $widget_id) {

		# $podadresar = // cesta ku suborom modulom
		# $adresar //nazov suboru modulu + aj nazvy skriptov

		$povol =1;

		# ci je vybrany typ
		$query6 = $this->db->query('SELECT * FROM be_widget' . $this->prefix . ' 
									WHERE widget_id ="' . $this->db->real_escape_string($widget_id) . '" AND 
											widget_stav ="' . $this->db->real_escape_string($povol) . '" ');
		if ($query6->num_rows != FALSE) {
			# OK, JE TYP
			$tlac = $query6->fetch_assoc();

			$widget_typ = $tlac['modul_id_typ'];
		}
		else {
			# NIEJE TYP, NENACITALO< CHYBA|||
			$widget_typ =0;
			//echo 'chyba';
		}

		if (file_exists($podadresar . $adresar)) {
			# OK je

			# NACITAME VSETKY SUBORY
			foreach(glob($podadresar . $adresar . "/*.start.php") as $filename){
    			include($filename);
			}

			# PUSTIME

			# OK
			//echo 'ok';

		}
		else {
			# NENAJDENY MODUL
			echo 'Modul nebol najdeny.';
		}


	}



	public function testmodul () {

		echo 'test modulu funguje';
	}


	public function nacitajobjektymodulov ($podadresar = 'modul/') {

		# $podadresar = // cesta ku suborom modulom
		# $adresar //nazov suboru modulu + aj nazvy skriptov

		$povol =1;


		$nacitaj = $this->db->query('SELECT DISTINCT w.modul_id, widget_stav, modul_stav, m.modul_id, m.modul_subor   
										FROM be_widget' . $this->prefix . ' w 
										JOIN be_modul' . $this->prefix . ' m ON w.modul_id = m.modul_id 
										WHERE widget_stav ="' . $this->db->real_escape_string($povol) . '" AND 
												modul_stav ="' . $this->db->real_escape_string($povol) . '" 
										ORDER BY widget_zorad DESC ');
		if ($nacitaj->num_rows != FALSE) {

			while ($row = $nacitaj->fetch_assoc()) {
				
				if (file_exists($podadresar . $row['modul_subor'])) {

					# NACITAME
					foreach (glob($podadresar . $row['modul_subor'] . "/*.lib.php") as $file) {
						//echo $file;
						include ($file);

					}
					
				}


			}
			

		}


	}




}




















?>