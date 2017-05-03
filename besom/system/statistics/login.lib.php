<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class login extends url {

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

	public function nadpis () {
		echo $this->lg[$this->flag]['nadpisLogin'];
	}


	public function loginTab () {

		# GET STRANA
		if (isset($_GET['page']) AND $_GET['page'] != '0') {
			$page = $_GET['page'];
		}
		else {
			$page =1;
		}

		#LIMIT STRANU
		if (isset($_GET['limit']) AND $_GET['limit'] != FALSE AND $_GET['limit'] != '') {
			$limit = $_GET['limit'];
		}
		else {
			$limit = 50;
		}

		$range = 10;

		$start = ($page - 1)* $limit;

		

		# POLE UZIVATELOV
		$uziv = $this->db->query('SELECT user_id, user_nick FROM be_user' . $this->prefix . ' ');
		if ($uziv->num_rows != FALSE) {
			$uzivA = array();
			
			$a = 1;
			while($row = $uziv->fetch_array()) {
			    $uzivA[$a] = $row;
			    $a +=1;
			}

		}
		//echo $uzivA[1][1]; // prve sa zvisuje a druhe zostava 1

				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->nadpis()); ?></h3>
					</div>
					<div class="panel-body">

						<?php
						if (isset($_POST['submit']) AND isset($_POST['search'])) {

							header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&search=' . $_POST['search']);
							exit();
						}
						?>

						<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="form-horizontal" role="form">
							
							<div class="form-group">
							    <label for="inputEmail3" class="col-sm-2 control-label">Hladaj</label>
							    <div class="col-sm-4">
									<input type="text" class="form-control" id="inputText3" value="<?php if (isset($_GET['search'])) { echo $_GET['search']; } ?>" placeholder="Hladany vyraz" name="search">
							    </div>
								<button type="submit" class="btn btn-info btn-sm" name="submit" value="Hladaj"><i class="glyphicon glyphicon-search"></i>&nbsp;Hladaj</button>

							</div>
						</form>
						
						<?php
							if (isset($_POST['submit']) AND isset($_POST['limit']) ) {

								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&limit=' . $_POST['limit']);
								exit();
							}
						?>
						<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="form-horizontal" role="form">
							<div class="form-group">
							<div class="col-md-5 col-md-offset-1">
								<label for="sur" class="text-danger">Super tajne hesla</label>
								<input type="checkbox" name="sure" value="0"></br>
								<label for="sur" class="text-default">Pocet zaznamou na stranu</label>
								<input type="number" name="limit" min="10" max="150" value="<?php if (isset($_GET['limit']) AND $_GET['limit'] != '') { echo $_GET['limit']; } else { echo '50'; } ?>" >

								<button type="submit" class="btn btn-warning btn-sm" name="submit" value="Skusit"><i class="glyphicon glyphicon-warning-sign"></i>&nbsp;Skusit</button>
							</div>
							</div>
						</form>
					</div>
				<?php

		/*$query = $this->db->query('SELECT SQL_CALC_FOUND_ROWS  *, m.menu_id, t.menu_id, m.menu_rodic, u.user_id  
										FROM be_text' . $this->prefix . ' t 
											JOIN be_menu' . $this->prefix . ' m ON t.menu_id = m.menu_id 
											JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									ORDER BY text_datum DESC LIMIT ' . $start . ', ' . $limit . ' ');*/
		
		$quer = 'SELECT SQL_CALC_FOUND_ROWS  * 
										FROM be_user_log' . $this->prefix . '  ';
		if (isset($_GET['search']) AND $_GET['search'] != FALSE AND $_GET['search'] != '') {
			
			#NAZOV
			$quer .= ' WHERE user_log_login LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_log_password LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_log_date LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_log_logout LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_log_ip LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_log_host LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_log_referer LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_log_useragent LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_id LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

		}

		#FILTERS
		// VIEWS
		if (isset($_GET['datel'])AND $_GET['datel'] != FALSE AND ($_GET['datel'] == 'ASC' OR $_GET['datel'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_logout ' . $_GET['datel'] . ' ';
		}
		elseif (isset($_GET['datep'])AND $_GET['datep'] != FALSE AND ($_GET['datep'] == 'ASC' OR $_GET['datep'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_date ' . $_GET['datep'] . ' ';
		}
		elseif (isset($_GET['ip'])AND $_GET['ip'] != FALSE AND ($_GET['ip'] == 'ASC' OR $_GET['ip'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_ip ' . $_GET['ip'] . ' ';
		}
		elseif (isset($_GET['stav'])AND $_GET['stav'] != FALSE AND ($_GET['stav'] == 'ASC' OR $_GET['stav'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_ano ' . $_GET['stav'] . ' ';
		}
		elseif (isset($_GET['name'])AND $_GET['name'] != FALSE AND ($_GET['name'] == 'ASC' OR $_GET['name'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_login ' . $_GET['name'] . ' ';
		}
		elseif (isset($_GET['pass'])AND $_GET['pass'] != FALSE AND ($_GET['pass'] == 'ASC' OR $_GET['pass'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_password ' . $_GET['pass'] . ' ';
		}
		elseif (isset($_GET['host'])AND $_GET['host'] != FALSE AND ($_GET['host'] == 'ASC' OR $_GET['host'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_host ' . $_GET['host'] . ' ';
		}
		elseif (isset($_GET['referer'])AND $_GET['referer'] != FALSE AND ($_GET['referer'] == 'ASC' OR $_GET['referer'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_referer ' . $_GET['referer'] . ' ';
		}
		elseif (isset($_GET['useragent'])AND $_GET['useragent'] != FALSE AND ($_GET['useragent'] == 'ASC' OR $_GET['useragent'] == 'DESC')) {
			$quer .= ' ORDER BY user_log_useragent ' . $_GET['useragent'] . ' ';
		}
		elseif (isset($_GET['public'])AND $_GET['public'] != FALSE AND ($_GET['public'] == 'ASC' OR $_GET['public'] == 'DESC')) {
			$quer .= ' ORDER BY text_publik ' . $_GET['public'] . ' ';
		}
		else {
			$quer .= ' ORDER BY user_log_date DESC  ';
		}

		$quer .= ' LIMIT ' . $start . ', ' . $limit . ' ';

		$query = $this->db->query($quer);
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$por = $por + $start;

			$publikA = array('1' => 'Spravne',
							 '0' => 'Nespravne' );
			$colorA = array('1' => 'success',
							 '0' => 'danger');
			$imageA = array('1' => 'glyphicon glyphicon-ok',
							'0' => 'glyphicon glyphicon-remove');
			$colA = array('1' => 'green', 
						  '0' => 'red');
			?>
			<table class="table">
				<tr>
					<th>#</th>
					<th title="Uspesnost prihlasenia">Stav</br>
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<!-- <th>Popis</th>  -->
					<th>Meno</br>
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&name=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&name=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<!-- <th>Url</th>  -->
					<th>Heslo</br>
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&pass=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&pass=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Datum prihlasenia</br>
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&datep=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&datep=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Datum odhlasenia</br>
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&datel=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&datel=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Ip adresa</br>
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&ip=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&ip=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Host</br>
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&host=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&host=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Referer</br>
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&referer=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&referer=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<th>Useragent</br>
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&useragent=DESC'; ?>">
							<span class="text-danger">↑</span>
						</a>
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&useragent=ASC'; ?>">
							<span class="text-danger">↓</span>
						</a>
					</th>
					<!-- <th><i class="glyphicon glyphicon-star"> &nbsp; </i> &nbsp;................. </th>  -->
				</tr>
			<?php
			while ($text = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php echo htmlspecialchars($colorA[$text['user_log_ano']]); ?>">
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php /*echo htmlspecialchars($text['user_log_ano']);*/ ?><i class="<?php echo $imageA[$text['user_log_ano']]; ?>" style="color: <?php echo $colA[$text['user_log_ano']]; ?>"></i><?php if (isset($uzivA[$text['user_id']][1])) { echo htmlspecialchars($uzivA[$text['user_id']][1]); } ?></td>
					<td><?php echo htmlspecialchars($text['user_log_login']); ?></td>
					<td><?php if ($text['user_log_ano'] == '1') { echo htmlspecialchars('******'); } else { echo htmlspecialchars($text['user_log_password']); } ?></td>
					<td><?php echo htmlspecialchars($text['user_log_date']); ?></td>
					<td><?php echo htmlspecialchars($text['user_log_logout']); ?></td>
					<td><?php echo htmlspecialchars($text['user_log_ip']); ?></td>
					<td><?php echo htmlspecialchars($text['user_log_host']); ?></td>
					<td><?php echo htmlspecialchars($text['user_log_referer']); ?></td>
					<td><?php echo htmlspecialchars($text['user_log_useragent']); ?></td>
					<!-- <td>

						<a href="<?php //echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . htmlspecialchars($text['user_log_id']); ?>"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
						<a href="<?php //echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&delete=' . htmlspecialchars($text['user_log_id']); ?>"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a>
					</td>  -->
				</tr>
				<?php

			}
			?></table><?php 

			# STRANKAOVANIE
			//$this->strankovanie($limit, $range, $url_zacni, $url_menu);
			# AK sa v $_SERVER['QUERY_STRING'] nachazda adresa &page=? odstraneime
			if (strrpos($_SERVER['QUERY_STRING'], '&page')) {
				$_SERVER['QUERY_STRING'] = substr($_SERVER['QUERY_STRING'], 0, -(strlen($_SERVER['QUERY_STRING']) - strrpos($_SERVER['QUERY_STRING'], '&page')));
			}
			$this->strankovanie($limit, $range, URL_ADRESA, '?' . $_SERVER['QUERY_STRING']);
			 
		}
		?></div><?php
	}

	public function strankovanie ($limit = '5', $range = '2', $url_zacni, $url_menu) { // limit pocet clankov na stranu,    range pocet stran na stranu
				// url_menu    menu_url 

		# POCET vYSLEDKOV ,  POCET CLANKOV
		$poc = $this->db->query('SELECT FOUND_ROWS() ');
		$pocet = $poc->fetch_array();
		$numrows = $pocet[0];

		if ($numrows > $limit) {
			if (isset($_GET['page']) AND $_GET['page'] != '0') {
				$page = $_GET['page'];
			}
			else {
				$page = 1;
			}
		}
		else {
			$page = 1;
		}

		$numofpages = ceil($numrows / $limit);
		
		if ($range == '0' OR $range == FALSE) {
			$range = 5;
		}

		$maxrange = max(1, $page - (($range - 1) / 2));
		$minrange = min($numofpages, $page + (($range - 1) / 2));
		if (($minrange - $maxrange) < ($range - 1))  {
			if ($maxrange == 1) {
				$minrange = min($maxrange + ($range - 1), $numofpages);
			}
			else {
				$maxrange = max($minrange - ($range - 1), 0);
			}
		}
		

		# TLAC STRANY
		?>
		<ul class="pagination" style="">
		<?php
		for ($i=1; $i <= $numofpages ; $i++) {
 
			if ($i == $page) {
				?><li class="active"><a href="#"><?php echo $i; ?></a></li><?php
			}
			else {
				if ($_SERVER['REQUEST_URI'] == '/' . PRIECINOK . 'index.php' OR $_SERVER['REQUEST_URI'] == '/' . PRIECINOK) {
					# DOMOV    HOME
					?><li class=""><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php
				}
				else {
					?><li class=""><a href="<?php echo htmlspecialchars($url_zacni . $url_menu . '&page=' . $i); ?>"><?php echo $i; ?></a></li><?php
				}

			}

		}
		?>
		</ul>
		<?php


	}


	

}


//$language = new language($this->db, $this->prefix, $this->lang);
$login = new login($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>