<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class right extends url {

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
		echo $this->lg[$this->flag]['nadpisRight'];
	}


	public function rightTab () {

				?>
				<div class="panel panel-default">
					<div class="panel-heading">
					<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->lg[$this->flag]['nadpisRight']); ?></h3>
					</div>
					<div class="panel-body">
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new'; ?>" title="Pridat">Pridat <i class="glyphicon glyphicon-plus"></i></a>
					</div>
				<?php

		$query = $this->db->query('SELECT * FROM be_right' . $this->prefix . ' 
									ORDER BY right_folder ASC ');
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$last = 0;
			//$por = $por + $start;

			?>
			<table class="table table-hover">
				<tr>
					<th></th>
					<th>#</th>
					<th>Nazov</th>
					<th>Url</th>
					<th>Priecinok</th>

					<th><i class="glyphicon glyphicon-star">  </i>Akcia</th>
				</tr>
			<?php
			while ($right = $query->fetch_assoc()) {
				$por = + $por +1;

				##############
				if ($por == '1' OR $last != $right['right_folder']/* AND $last == $right['right_url'])*/) {
					$por = 1;
					?><tr class="text-info"><th><?php echo htmlspecialchars($right['right_folder']); ?></th><th></th><th></th><th></th><th></th><th></th></tr><?php
				}
				##############
				
				?>
				<tr class="<?php if ($right['right_url'] == $right['right_folder']) { echo 'text-info'; } else { echo 'text-warning'; } ?>">
					<td></td>
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php echo htmlspecialchars($right['right_nazov']); ?></td>
					<td><?php echo htmlspecialchars($right['right_url']); ?></td>
					<td><?php echo htmlspecialchars($right['right_folder']); ?></td>

					<td>
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($right['right_id']); ?>"  alt="Zmenit" title="Zmenit">
							<i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i>
						</a>
						 &nbsp; 
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($right['right_id']); ?>"  alt="Vymazat" title="Vymazat">
							<i class="glyphicon glyphicon-trash" style="color: red; "></i>
						</a>
					</td>
				</tr>
				<?php
				#########
				$last = $right['right_folder'];
				$lastU = $right['right_url'];
				#########

			}
			?></table><?php 
			 
		}
		?></div><?php
	}


	public function rightNew () {


		#POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['url']) AND isset($_POST['folder'])) {
			
			$nazov = (isset($_POST['nazov'])) ? trim($_POST['nazov']) : '';
			$url = (isset($_POST['url'])) ? trim($_POST['url']) : '';
			$folder = (isset($_POST['folder'])) ? trim($_POST['folder']) : '';

			if (!empty($nazov) AND !empty($url) AND !empty($folder)) {
				
				$uloz = $this->db->query('INSERT IGNORE INTO be_right' . $this->prefix . ' 
								(right_id, right_nazov, right_url, right_folder) VALUES 
												(NULL,
												 "' . $this->db->real_escape_string($nazov) . '",
												 "' . $this->db->real_escape_string($url) . '",
												 "' . $this->db->real_escape_string($folder) . '") ');


				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();

			}
			else {
				#ERROR

				setcookie('odpoved','1', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new');
				exit();
			}
		}

		?>
<!-- <h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat <?php //echo $this->lg[$this->flag]['nadpisGroup']; ?></h3> -->
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">Pridat <?php echo /*lcfirst(*/$this->lg[$this->flag]['nadpisRight']/*)*/; ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
				    <div class="col-sm-6">
						<input type="text" class="form-control" id="inputText3" placeholder="Nazov" name="nazov">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Url</label>
				    <div class="col-sm-6">
						<input type="text" class="form-control" id="inputText3" placeholder="url" name="url">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Priecinok</label>
				    <div class="col-sm-6">
						<input type="text" class="form-control" id="inputText3" placeholder="Priecinok" name="folder">
				    </div>
				</div>

				<div class="form-group">
				      	<div class="col-sm-offset-2 col-sm-10">
				      		<button type="submit" class="btn btn-success btn-sm" name="submit"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</button>
				      		<button class="btn btn-danger btn-sm" type="reset" onclick="location.href(window.history.back());"><i class="glyphicon glyphicon-remove"></i>&nbsp;Reset</button>
				      	</div>
				</div>	
			</form>
		</div>
	</div>
		<?php
	}


	public function rightEdit ($id) {


		#POST
		if (isset($_POST['submit']) AND isset($_POST['nazov'])) {
			
			$nazov = (isset($_POST['nazov'])) ? trim($_POST['nazov']) : '';
			$url = (isset($_POST['url'])) ? trim($_POST['url']) : '';
			$folder = (isset($_POST['folder'])) ? trim($_POST['folder']) : '';

			if (!empty($nazov) AND !empty($url) AND !empty($folder)) {
				
				$uloz = $this->db->query('UPDATE be_right' . $this->prefix . ' SET  
												right_nazov ="' . $this->db->real_escape_string($nazov) . '",
												right_url ="' . $this->db->real_escape_string($url) . '",
												right_folder ="' . $this->db->real_escape_string($folder) . '"
											WHERE right_id ="' . $this->db->real_escape_string(intval($id)) . '" ');


				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();

			}
			else {
				#ERROR

				setcookie('odpoved','1', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . $_GET['edit'] . '&id=' . $_GET['id']);
				exit();
			}
		}


		$over = $this->db->query('SELECT * FROM be_right' . $this->prefix . ' 
									WHERE right_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();
		?>
<!-- <h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat <?php //echo $this->lg[$this->flag]['nadpisGroup']; ?></h3> -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Zmenit <?php echo /*lcfirst(*/$this->lg[$this->flag]['nadpisRight']/*)*/; ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
				    <div class="col-sm-6">
						<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['right_nazov']); ?>" name="nazov">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Url</label>
				    <div class="col-sm-6">
						<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['right_url']); ?>" name="url">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Priecinok</label>
				    <div class="col-sm-6">
						<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['right_folder']); ?>" name="folder">
				    </div>
				</div>


				<div class="form-group">
				      	<div class="col-sm-offset-2 col-sm-10">
				      		<button type="submit" class="btn btn-primary btn-sm" name="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
				      		<button class="btn btn-danger btn-sm" type="reset" onclick="location.href(window.history.back());"><i class="glyphicon glyphicon-remove"></i>&nbsp;Reset</button>
				      	</div>
				</div>	
			</form>
		</div>
	</div>
		<?php
		}

	}


	public function rightDelete ($id) {


		#POST
		if (isset($_POST['submit']) AND isset($_POST['nazov'])) {
			
			$nazov = (isset($_POST['nazov'])) ? trim($_POST['nazov']) : '';
			//$url = (isset($_POST['url'])) ? trim($_POST['url']) : '';
			//$folder = (isset($_POST['folder'])) ? trim($_POST['folder']) : '';

			if (!empty($nazov)) {
				
				$uloz = $this->db->query('DELETE FROM be_right' . $this->prefix . '  
											WHERE right_nazov ="' . $this->db->real_escape_string($nazov) . '" AND 
													right_id ="' . $this->db->real_escape_string(intval($id)) . '" ');


				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();

			}
			else {
				#ERROR

				setcookie('odpoved','1', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . $_GET['delete']);
				exit();
			}
		}


		$over = $this->db->query('SELECT * FROM be_right' . $this->prefix . ' 
									WHERE right_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();
		?>
<!-- <h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat <?php //echo $this->lg[$this->flag]['nadpisGroup']; ?></h3> -->
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">Zmenit <?php echo /*lcfirst(*/$this->lg[$this->flag]['nadpisRight']/*)*/; ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
				    <div class="col-sm-6">
						<p class="form-control-static"><?php echo htmlspecialchars($zobraz['right_nazov']); ?></p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Url</label>
				    <div class="col-sm-6">
						<p class="form-control-static"><?php echo htmlspecialchars($zobraz['right_url']); ?></p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Priecinok</label>
				    <div class="col-sm-6">
						<p class="form-control-static"><?php echo htmlspecialchars($zobraz['right_folder']); ?></p>
				    </div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						<button class="btn btn-danger btn-sm" type="reset" onclick="location.href(window.history.back());"><i class="glyphicon glyphicon-remove"></i>&nbsp;Reset</button>
					</div>
				</div>
				<input type="hidden" name="nazov" value="<?php echo htmlspecialchars($zobraz['right_nazov']); ?>">
			</form>
		</div>
	</div>
		<?php
		}

	}













/*
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
*/

/*	
  	public function menuSelect ($id = '0') {

		# $typ = 1; // new  
		# $typ = 2; // edit

			?><select class="form-control" name="menu"><?php

			#####################################################################################
			# TEXT

			# POZICIA 
			$poz = $this->db->query('SELECT * FROM be_pozicia' . $this->prefix . ' 
										WHERE pozicia_aka ="menu"  ORDER BY pozicia_nazov ASC ');
			if ($poz->num_rows != FALSE) {
				while ($tla = $poz->fetch_assoc()) {

					?><optgroup label="<?php echo htmlspecialchars($tla['pozicia_nazov']); ?>"><?php

					#####################################################################################################
					# TEXT
					?><optgroup label="-----> TEXT"><?php
					$nav = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
												WHERE menu_typ ="text" AND menu_rodic =0 AND menu_pozicia ="' . $this->db->real_escape_string($tla['pozicia_nazov']) . '" 
												ORDER BY menu_nazov ASC ');
					if ($nav->num_rows != FALSE) {
						# TEXT-> SECTION
						while ($tlac1 = $nav->fetch_assoc()) {

							?><option style="font-weight: bold; color: #212121;" value="<?php echo htmlspecialchars($tlac1['menu_id']); ?>" name="<?php echo htmlspecialchars($tlac1['menu_id']); ?>" <?php if ($tlac1['menu_id'] == $id) { echo 'selected="selected"'; } ?> > &nbsp; <?php echo htmlspecialchars($tlac1['menu_nazov']); ?></option><?php

							# TEXT - SECTION _> CATEGORIES
							$nav1 = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
														WHERE menu_typ ="text" AND menu_rodic ="' . $this->db->real_escape_string($tlac1['menu_id']) . '" AND menu_pozicia ="' . $this->db->real_escape_string($tla['pozicia_nazov']) . '" 
														ORDER BY menu_nazov ASC ');
							if ($nav1->num_rows != FALSE) {
								while ($tlac2 = $nav1->fetch_assoc()) {
									?><option style="color: #363636; " value="<?php echo htmlspecialchars($tlac2['menu_id']); ?>" name="<?php echo htmlspecialchars($tlac2['menu_id']); ?>" <?php if ($tlac2['menu_id'] == $id) { echo 'selected="selected"'; } ?> > &nbsp; -----><?php echo htmlspecialchars($tlac2['menu_nazov']); ?></option><?php
								}
							}
						}
					}
					?></optgroup><?php // END TEXT
					######################################################################################################

					######################################################################################################
					# CLANOK
					?><optgroup label="-----> CLANOK"><?php
					$nav = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
												WHERE menu_typ ="clanok" AND menu_rodic =0 AND menu_pozicia ="' . $this->db->real_escape_string($tla['pozicia_nazov']) . '" 
												ORDER BY menu_nazov ASC ');
					if ($nav->num_rows != FALSE) {
						# CLANOK-> SECTION
						while ($tlac1 = $nav->fetch_assoc()) {

							?><option style="font-weight: bold; color: #212121;" value="<?php echo htmlspecialchars($tlac1['menu_id']); ?>" name="<?php echo htmlspecialchars($tlac1['menu_id']); ?>"  <?php if ($tlac1['menu_id'] == $id) { echo 'selected="selected"'; } ?> > &nbsp; <?php echo htmlspecialchars($tlac1['menu_nazov']); ?></option><?php

							# CLANOK - SECTION _> CATEGORIES
							$nav1 = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
														WHERE menu_typ ="clanok" AND menu_rodic ="' . $this->db->real_escape_string($tlac1['menu_id']) . '" AND menu_pozicia ="' . $this->db->real_escape_string($tla['pozicia_nazov']) . '" 
														ORDER BY menu_nazov ASC ');
							if ($nav1->num_rows != FALSE) {
								while ($tlac2 = $nav1->fetch_assoc()) {
									?><option style="color: #363636; " value="<?php echo htmlspecialchars($tlac2['menu_id']); ?>" name="<?php echo htmlspecialchars($tlac2['menu_id']); ?>"  <?php if ($tlac2['menu_id'] == $id) { echo 'selected="selected"'; } ?> > &nbsp; -----><?php echo htmlspecialchars($tlac2['menu_nazov']); ?></option><?php
								}
							}
						}
					}
					?></optgroup><?php // END CLANOK
					#######################################################################################################

					#######################################################################################################
					# URL
					?><optgroup label="-----> URL"><?php
					$nav = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
												WHERE menu_typ ="URL" AND menu_rodic =0 AND menu_pozicia ="' . $this->db->real_escape_string($tla['pozicia_nazov']) . '" 
												ORDER BY menu_nazov ASC ');
					if ($nav->num_rows != FALSE) {
						# TEXT-> SECTION
						while ($tlac1 = $nav->fetch_assoc()) {

							?><option disabled="disabled" style="font-weight: bold; color: #212121;" value="<?php echo htmlspecialchars($tlac1['menu_id']); ?>" name="<?php echo htmlspecialchars($tlac1['menu_id']); ?>"> &nbsp; <?php echo htmlspecialchars($tlac1['menu_nazov']); ?></option><?php

							# TEXT - SECTION _> CATEGORIES
							$nav1 = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
														WHERE menu_typ ="URL" AND menu_rodic ="' . $this->db->real_escape_string($tlac1['menu_id']) . '" AND menu_pozicia ="' . $this->db->real_escape_string($tla['pozicia_nazov']) . '" 
														ORDER BY menu_nazov ASC ');
							if ($nav1->num_rows != FALSE) {
								while ($tlac2 = $nav1->fetch_assoc()) {
									?><option disabled="disabled" style="color: #363636; " value="<?php echo htmlspecialchars($tlac2['menu_id']); ?>" name="<?php echo htmlspecialchars($tlac2['menu_id']); ?>"> &nbsp; -----><?php echo htmlspecialchars($tlac2['menu_nazov']); ?></option><?php
								}
							}
						}
					}
					?></optgroup><?php // END URL
					######################################################################################################


					?></optgroup><?php // END POSITION
				}
			}
			# NO POSITION END

			?></select><?php
			
	}
*/




}


//$language = new language($this->db, $this->prefix, $this->lang);
$right = new right($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>