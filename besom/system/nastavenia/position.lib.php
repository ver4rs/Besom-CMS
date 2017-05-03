<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');


class position {

	private $db, $prefix;
	public $lang, $lg, $lang_short;

	public function __construct ($conn, $prefix, $lang, $lg, $lang_short) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
		$this->lg = $lg;
		$this->flag = $lang_short;

		if ($lg[$this->flag]['test'] == '') {
			$this->flag = 'sk';
		}

		//echo $lg[$this->flag]['test'];
	}




	public function position () {
		$query = $this->db->query('SELECT * FROM be_pozicia' . $this->prefix . ' ORDER BY pozicia_id ASC ');

		if ($query->num_rows != FALSE) {
			# OK

			$poz = array();
			while ($tlac = $query->fetch_assoc()) {

				$id = $tlac['pozicia_id'];
				$naz = $tlac['pozicia_nazov'];
				$aka = $tlac['pozicia_aka'];

				$poz[$id][$naz][$aka][] = $tlac;
			}
			return $poz;
			 
		}

	}

	public function nazovOdId ($id) {
		$naz = $this->db->query('SELECT pozicia_nazov, pozicia_aka FROM be_pozicia' . $this->prefix . ' WHERE pozicia_id ="' . $this->db->real_escape_string($id) .'" ');
		
		if ($naz->num_rows != FALSE) {
			$naz1 = $naz->fetch_assoc();

			return $naz1;
		}
		else { 
			//$nazovDel = ''; 
			//return $nazovDel;

			$this->redirect('?menu=nastavenia&action=position');

		}
	}

	public function poziciaAction ($action) {
		# $action = 1 create, new, 2 edit,   3 delete

		if ($action == '1') {
			# NEW POZICIA
			//echo 'post ' . $_POST['poznazov'] . $_POST['pozaka'];

			$uloz = $this->db->query('INSERT INTO be_pozicia' . $this->prefix . ' (pozicia_id, pozicia_nazov, pozicia_aka) 
										VALUES (NULL,
												"' . $this->db->real_escape_string($_POST['poznazov']) . '",
												"' . $this->db->real_escape_string($_POST['pozaka']) . '") ');
			return;

		}
		elseif ($action == '2') {
			# EDIT
			$edit = $this->db->query('UPDATE be_pozicia' . $this->prefix . ' SET 
										pozicia_nazov ="' . $this->db->real_escape_string($_POST['poznazov']) . '",
										pozicia_aka ="' . $this->db->real_escape_string($_POST['pozaka']) . '"
									WHERE pozicia_id ="' . $this->db->real_escape_string($_GET['id']) . '" ');
			
			return;

		}
		elseif ($action == '3') {
			# DELETE
			$del = $this->db->query('DELETE FROM be_pozicia' . $this->prefix . ' 
										WHERE pozicia_id ="' . $this->db->real_escape_string($_GET['id']) . '" ');
			return; 
		}
		else {
			# ERROR

		}

	}





}

//$position = new position($this->db, $this->prefix, $this->lang);
$position = new position($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);


?>