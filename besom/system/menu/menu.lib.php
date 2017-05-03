<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class menu extends url {

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
		return $this->lg[$this->flag]['menu'];
	}


	public function menu () {

		# pomocne farnbz
		$typImg = array('text' => 'glyphicon glyphicon-list-alt',
					 'clanok' => 'glyphicon glyphicon-book',
					 'url' => 'glyphicon glyphicon-link' );
		$typImgTitle = array('text' => 'Text',
							 'clanok' => 'clanok',
							 'url' => 'Url Adresa');


		$p = 'menu';
		$poz = $this->db->query('SELECT pozicia_nazov FROM be_pozicia' . $this->prefix . ' 
									WHERE pozicia_aka ="' . $this->db->real_escape_string($p) . '"  ORDER BY pozicia_nazov ASC ');
		if ($poz->num_rows != FALSE) {
			# SU POZICIE

			while ($pozicia = $poz->fetch_assoc()) {
				
				//echo $pozicia['pozicia_nazov'];
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
				    	<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->nadpis()); ?> &nbsp; <i class="glyphicon glyphicon-chevron-right" style="color: #656565;"></i> &nbsp; <?php echo htmlspecialchars($pozicia['pozicia_nazov']); ?></h3>
					</div>
					<div class="panel-body">
					</div>
				<?php


				$menu1 = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
											WHERE menu_pozicia ="' . $this->db->real_escape_string($pozicia['pozicia_nazov']) . '" AND 
												menu_rodic =0  
											ORDER BY menu_zorad ASC ');
				if ($menu1->num_rows != FALSE) {
					# JE MENU PRE DANU POZICIU

					$por = 0;
					?>
					<table class="table table-hover">
						<tr>
							<th>#</th>
							<th>Nazov</th>
							<th>Url adresa</th>
							<th title="Komu patrim ?">Menu rodic</th>
							<th title="miesto zobrazenia">Pozicia</th>
							<th title="Typ sluzby : text, clanok, url">Typ</th>
							<th title="Startovacia stranka pri nacitani">Start</th>
							<th>Modul</th>
							<th>Register</th>
							<th>Presmeruj</th>
							<th>Popis (title)</th>
							<th><a href="#" title="Aktualizuj poradie" onclick="<?php echo $this->aktualizujZorad(); ?>">Poradie</a></th>
							<th><i class="glyphicon glyphicon-star"> &nbsp; </i> &nbsp;......... </th>
						</tr>
					<?php

					while ($menu = $menu1->fetch_assoc()) {
						$por +=1;
						//echo $menu['menu_nazov'] . '<br>';

						?>
						<tr style="background: #F4F4F4; color: #121212; ">
							<td><?php echo htmlspecialchars($por); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_nazov']); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_url']); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_rodic']); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_pozicia']); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_typ']); ?>&nbsp;<i class="<?php echo $typImg[$menu['menu_typ']]; ?>" title="<?php echo $typImgTitle[$menu['menu_typ']]; ?>"></i></td>
							<td><?php echo htmlspecialchars($menu['menu_zobraz']); ?><?php if ($menu['menu_zobraz'] == 'start') { ?>&nbsp;<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&start=' . $menu['menu_id']; ?>"><i class="glyphicon glyphicon-home"></i></a><?php } else { ?>&nbsp;<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&start=' . $menu['menu_id']; ?>"><i class="glyphicon glyphicon-remove-circle"></i></a><?php } ?></td>
							<td><?php echo htmlspecialchars($menu['menu_modul_id']); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_pre_register']); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_target']); ?></td>
							<td><?php echo htmlspecialchars($menu['menu_popis']); ?></td>	
							<td><?php echo htmlspecialchars($menu['menu_zorad']); ?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&order=' . $menu['menu_zorad'] . '&id=' . $menu['menu_id']; ?>"><i class="glyphicon glyphicon-sort-by-order"></i></a></td>
							<td><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . $menu['menu_id']; ?>" title="Zmenit"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
								<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . $menu['menu_id']; ?>" title="delete"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a></td>
						</tr>
						<?php

						# EXISTUJE KATEGORIA
						$kat = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' WHERE 
													menu_pozicia ="' . $this->db->real_escape_string($pozicia['pozicia_nazov']) . '" AND 
													menu_rodic ="' . $this->db->real_escape_string($menu['menu_id']) . '"  
												ORDER BY menu_zorad ASC ');
						if ($kat->num_rows != FALSE) {
							# JE
							while ($kategoria = $kat->fetch_assoc()) {
								
								?>
						<tr style=" font-size: 13px; ">
							<td><i class="glyphicon glyphicon-arrow-right"  style="opacity: 0.8; "></i></td>
							<td><?php echo htmlspecialchars($kategoria['menu_nazov']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_url']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_rodic'] . ' ' . $menu['menu_nazov']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_pozicia']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_typ']); ?>&nbsp;<i class="<?php echo $typImg[$menu['menu_typ']]; ?>" title="<?php echo $typImgTitle[$menu['menu_typ']]; ?>"></i></td>
							<td><?php echo htmlspecialchars($kategoria['menu_zobraz']); ?><?php if ($menu['menu_zobraz'] == 'start') { ?>&nbsp;<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&start=' . $kategoria['menu_id']; ?>"><i class="glyphicon glyphicon-home"></i></a><?php } else { ?>&nbsp;<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&start=' . $kategoria['menu_id']; ?>"><i class="glyphicon glyphicon-remove-circle"></i></a><?php } ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_modul_id']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_pre_register']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_target']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_popis']); ?></td>
							<td><?php echo htmlspecialchars($kategoria['menu_zorad']); ?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&order=' . $kategoria['menu_zorad'] . '&id=' . $kategoria['menu_id']; ?>"><i class="glyphicon glyphicon-sort-by-order"></i></a></td>
							<td><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . $kategoria['menu_id']; ?>" title="Zmenit"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
								<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . $kategoria['menu_id']; ?>" title="delete"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a></td>
						</tr>
								<?php

							}
						}


					}
					?>
					</table>
					<?php
				}
				else {
					echo $this->lg[$this->flag]['nomenu'];
				}

				?>
					
				</div>	
				<?php
			}

		}
		else {
			# NENI POZICIA ANI POTOM ASI MENU
			echo $this->lg[$this->flag]['nopozicia'];
		}
	}

	public function menuAdd () {


		# POST
		if (isset($_POST['submit']) AND $_POST['submit'] == 'sub' AND isset($_POST['nazov']) AND isset($_POST['menuRodic']) AND isset($_POST['positionId']) AND isset($_POST['typ']) AND isset($_POST['start']) AND isset($_POST['preRegister']) AND isset($_POST['modulId']) AND isset($_POST['target']) AND isset($_POST['popis']) /*AND isset($_POST['album'])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$menuRodic = (isset($_POST['menuRodic'])) ? $_POST['menuRodic'] : '';
			$positionId = (isset($_POST['positionId'])) ? $_POST['positionId'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';
			$preRegister = (isset($_POST['preRegister'])) ? $_POST['preRegister'] : '';
			$modulId = (isset($_POST['modulId'])) ? $_POST['modulId'] : '';
			$target = (isset($_POST['target'])) ? $_POST['target'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$start = (isset($_POST['start'])) ? $_POST['start'] : ''; // disabled

			$album = (isset($_POST['album'])) ? $_POST['album'] : ''; 

			/*echo $nazov . '<br>';
			echo $menuRodic . '<br>';
			echo $positionId . '<br>';
			echo $typ . '<br>';
			echo $preRegister . '<br>';
			echo $modulId . '<br>';
			echo $target . '<br>';
			echo $popis . '<br>';
			echo $start . '<br>';
			echo $album . '<br>';
			die();*/

			if (!empty($nazov) AND $menuRodic != '' AND !empty($positionId) AND !empty($typ) AND !empty($preRegister) AND $modulId != '' AND !empty($target) AND !empty($popis) AND !empty($start) AND $album != '' ) {

				# URL CREATE
				$menu_url = $this->seo_url($nazov);

				# ZORAD menu
				$z = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
										WHERE menu_pozicia ="' . $this->db->real_escape_string($positionId) . '" AND 
												menu_rodic ="' . $this->db->real_escape_string($menuRodic) . '" 
										ORDER BY menu_zorad DESC LIMIT 1 ');
				if ($z->num_rows != FALSE) {
					# OK
					$zz = $z->fetch_assoc();

					$menu_zorad = $zz['menu_zorad'] +1;
				}
				else {
					$menu_zorad = '1';
				}
				
				# ULOZIME
				$query = $this->db->query('INSERT INTO be_menu' . $this->prefix . ' (menu_id, menu_nazov, menu_url, menu_rodic, menu_pozicia, menu_typ, menu_zobraz, menu_modul_id, menu_pre_register, menu_target, menu_popis, menu_zorad, menu_album) 
											VALUES  (NULL,
													"' . $this->db->real_escape_string($nazov) . '",
													"' . $this->db->real_escape_string($menu_url) . '",
													"' . $this->db->real_escape_string($menuRodic) . '",
													"' . $this->db->real_escape_string($positionId) . '",
													"' . $this->db->real_escape_string($typ) . '",
													"' . $this->db->real_escape_string($start) . '",
													"' . $this->db->real_escape_string($modulId) . '",
													"' . $this->db->real_escape_string($preRegister) . '",
													"' . $this->db->real_escape_string($target) . '",
													"' . $this->db->real_escape_string($popis) . '",
													"' . $this->db->real_escape_string($menu_zorad) . '",
													"' . $this->db->real_escape_string($album) . '")');


				setcookie('odpoved','2', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new');
				exit();
			}
			
		}
		


		#FORM
		?>
	<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #278c38; margin-bottom: 5px;">Pridat</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
	    	<h3 class="panel-title">Pridat</h3>
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
				    <label for="inputEmail3" class="col-sm-2 control-label">Sekcia</label>
				    <div class="col-sm-6">
				    	<?php $this->selectRodic('1'); // menuRodic ?>
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Pozicia</label>
				    <div class="col-sm-6">
				    	<?php $this->selectPosition('1'); // positionId ?>
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Typ zobrazenia</label>
				    <div class="col-sm-6">
				    	<select class="form-control" name="typ">
							<option name="text" value="text" selected="selected">text</option>
							<option name="clanok" value="clanok">clanok</option>
							<option name="url" value="url">url</option>
						</select>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Startovacia stranka</label>
				    <div class="col-sm-6">
				    	<div class="radio">
							<label>
						    	<input type="radio" name="start" id="optionsRadios1" value="null" checked="checked">
						    Nie
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="start" id="optionsRadios2" value="start" disabled="disabled">
						    Ano
						  </label>
						</div>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Pre registrovanych</label>
				    <div class="col-sm-6">
				    	<div class="radio">
							<label>
						    	<input type="radio" name="preRegister" id="optionsRadios1" value="1" checked="checked">
						    Pre kazdeho
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="preRegister" id="optionsRadios2" value="2" disabled="disabled">
						    Len registrovany
						  </label>
						</div>
				    </div>
				</div>
				
				<h5>Len pre galeriu</h5>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Galeria</label>
				    <div class="col-sm-6">
				    	<select  class="form-control" name="album">
				    	<?php
				    	$gal = $this->albumSelect();
				    		?>
				    		<option name="0" value="0">NIJAKY</option>
				    		<?php
				    	foreach ($gal as $alId => $value) {
				    		foreach ($value as $alNazov => $value2) {
				    			?><option name="<?php echo htmlspecialchars($alId); ?>" value="<?php echo htmlspecialchars($alId); ?>"><?php echo htmlspecialchars($alNazov); ?></option><?php
				    		}
				    	}
				    	?>
				    	</select>
				    </div>
				</div>

				<h5>Len pre modul</h5>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Modul</label>
				    <div class="col-sm-6">
				    	<?php $this->selectModul('1'); // modulId ?>
				    </div>
				</div>

				<h5>Len pre URL adresu</h5>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Zobraz kam, kde</label>
				    <div class="col-sm-6">
						<select class="form-control" name="target">
							<option name="_blank" selected="selected">_blank</option>
							<option name="_self">_self</option>
							<option name="_parent">_parent</option>
							<option name="_top">_top</option>
						</select>
					</div>	
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Popis" name="popis">
				    </div>
				</div>
 				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success btn-sm" name="submit" value="sub"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</button>
					</div>
				</div>	
			</form>
		</div>
	</div>	
		<?php
	}


	public function menuEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND $_POST['submit'] == 'submit' AND isset($_POST['nazov']) AND isset($_POST['menuRodic']) AND isset($_POST['positionId']) AND isset($_POST['typ']) AND isset($_POST['start']) AND isset($_POST['preRegister']) AND isset($_POST['modulId']) AND isset($_POST['target']) AND isset($_POST['popis']) /*AND isset($_POST['zorad'])*/ AND isset($_POST['id1']) /*AND isset($_POST['album']) */) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$menuRodic = (isset($_POST['menuRodic'])) ? $_POST['menuRodic'] : '';
			$positionId = (isset($_POST['positionId'])) ? $_POST['positionId'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';
			$preRegister = (isset($_POST['preRegister'])) ? $_POST['preRegister'] : '';
			$modulId = (isset($_POST['modulId'])) ? $_POST['modulId'] : '';
			$target = (isset($_POST['target'])) ? $_POST['target'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$start = (isset($_POST['start'])) ? $_POST['start'] : ''; // disabled
			$menu_zorad = (isset($_POST['zorad'])) ? $_POST['zorad'] : ''; // disabled
			$id1 = (isset($_POST['id1'])) ? $_POST['id1'] : ''; // disabled

			$album = (isset($_POST['album'])) ? $_POST['album'] : ''; 

			/*echo $nazov . '<br>';
			echo $menuRodic . '<br>';
			echo $positionId . '<br>';
			echo $typ . '<br>';
			echo $preRegister . '<br>';
			echo $modulId . '<br>';
			echo $target . '<br>';
			echo $popis . '<br>';
			echo $start . '<br>';
			echo $menu_zorad . '</br>';
			echo $id1 . '</br>';
			echo $album . '</br>';
			die();*/

			if (!empty($nazov) AND $menuRodic != '' AND !empty($positionId) AND !empty($typ) AND !empty($preRegister) AND $modulId != '' AND !empty($target) AND !empty($popis) AND !empty($start) /*AND !empty($menu_zorad)*/ AND !empty($id) AND !empty($id1) AND $id == $id1 AND $album != '') {

				# URL CREATE
				$menu_url = $this->seo_url($nazov);

				# ZORAD menu
				/*$z = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
										WHERE menu_pozicia ="' . $this->db->real_escape_string($positionId) . '" AND 
												menu_rodic ="' . $this->db->real_escape_string($menuRodic) . '" 
										ORDER BY menu_zorad DESC LIMIT 1 ');
				if ($z->num_rows != FALSE) {
					# OK
					$zz = $z->fetch_assoc();

					$menu_zorad = $zz['menu_zorad'] +1;
				}
				else {
					$menu_zorad = '1';
				}
				*/
				
				# ULOZIME
				$query = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET 
													menu_nazov ="' . $this->db->real_escape_string($nazov) . '",
													menu_url ="' . $this->db->real_escape_string($menu_url) . '",
													menu_rodic ="' . $this->db->real_escape_string($menuRodic) . '",
													menu_pozicia ="' . $this->db->real_escape_string($positionId) . '",
													menu_typ ="' . $this->db->real_escape_string($typ) . '",
													menu_zobraz ="' . $this->db->real_escape_string($start) . '",
													menu_modul_id ="' . $this->db->real_escape_string($modulId) . '",
													menu_pre_register ="' . $this->db->real_escape_string($preRegister) . '",
													menu_target ="' . $this->db->real_escape_string($target) . '",
													menu_popis ="' . $this->db->real_escape_string($popis) . '", 
													menu_album ="' . $this->db->real_escape_string($album) . '" 
											WHERE menu_id ="' . $this->db->real_escape_string($id) . '" ');


				setcookie('odpoved','2', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit' . $id);
				exit();
			}
			
		}
		# END POST DATA

		
		# FORM
		$over = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
									WHERE menu_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
		<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #0256c4; margin-bottom: 5px;">Zmenit</h3>

		<div class="panel panel-default">
			<div class="panel-heading">
		    	<h3 class="panel-title">Zmenit</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" role="form" method="post" action="#">

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['menu_nazov']); ?>" name="nazov">
					    </div>
					</div>

				<?php
				if ($zobraz['menu_rodic'] != '0') {
					?>	
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Sekcia</label>
					    <div class="col-sm-6">
					    	<?php $this->selectRodic('2', $zobraz['menu_rodic']); //menuRodic ?>
					    </div>
					</div>
					<?php
				}
				else {
					?><input type="hidden" name="menuRodic" value="0"><?php
				}
				?>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Pozicia</label>
					    <div class="col-sm-6">
					    	<?php $this->selectPosition('2', $zobraz['menu_pozicia']); // positionId ?>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Typ zobrazenia</label>
					    <div class="col-sm-6">
					    	<select class="form-control" name="typ">
								<option name="text" <?php if ($zobraz['menu_typ'] == 'text') { echo 'selected="selected"'; } ?> value="text">text</option>
								<option name="clanok" <?php if ($zobraz['menu_typ'] == 'clanok') { echo 'selected="selected"'; } ?> value="clanok">clanok</option>
								<option name="url" <?php if ($zobraz['menu_typ'] == 'url') { echo 'selected="selected"'; } ?> value="url">url</option>
							</select>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Startovacia stranka</label>
					    <div class="col-sm-6">
					    	<div class="radio">
								<label>
							    	<input type="radio" name="start" id="optionsRadios1"  <?php if ($zobraz['menu_zobraz'] == 'null') { echo 'checked="checked"'; } else { echo 'disabled="disabled"'; } ?> value="null">
							    Nie
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="start" id="optionsRadios2"  <?php if ($zobraz['menu_zobraz'] == 'start') { echo 'checked="checked"'; } else { echo 'disabled="disabled"'; } ?> value="start">
							    Ano
							  </label>
							</div>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Pre registrovanych</label>
					    <div class="col-sm-6">
					    	<div class="radio">
								<label>
							    	<input type="radio" name="preRegister" id="optionsRadios1" <?php if ($zobraz['menu_pre_register'] == '1') { echo 'checked="checked"'; } else { echo 'disabled="disabled"'; } ?> value="1">
							    Pre kazdeho
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="preRegister" id="optionsRadios2" <?php if ($zobraz['menu_pre_register'] == '2') { echo 'checked="checked"'; } else { echo 'disabled="disabled"'; } ?> value="2">
							    Len registrovany
							  </label>
							</div>
					    </div>
					</div>
					
					<h5>Len pre galeriu</h5>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Galeria</label>
					    <div class="col-sm-6">
					    	<select  class="form-control" name="album">
					    	<?php
					    	$gal = $this->albumSelect();
					    	foreach ($gal as $alId => $value) {
					    		foreach ($value as $alNazov => $value2) {
					    			?>
						    		<option name="0" value="0">NIJAKY</option>
						    		<?php
					    			if ($alId == $id) {
					    				?><option name="<?php echo htmlspecialchars($alId); ?>" value="<?php echo htmlspecialchars($alId); ?>" selected="selected"><?php echo htmlspecialchars($alNazov); ?></option><?php
					    			}
					    			else {
										?><option name="<?php echo htmlspecialchars($alId); ?>" value="<?php echo htmlspecialchars($alId); ?>"><?php echo htmlspecialchars($alNazov); ?></option><?php
					    			}
					    		}
					    	}
					    	?>
					    	</select>
					    </div>
					</div>

					<h5>Len pre modul</h5>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Modul</label>
					    <div class="col-sm-6">
					    	<?php $this->selectModul('2', $zobraz['menu_modul_id']); // modulId ?>
					    </div>
					</div>

					<h5>Len pre URL adresu</h5>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Zobraz kam, kde</label>
					    <div class="col-sm-6">
							<select class="form-control" name="target">
								<option <?php if ($zobraz['menu_target'] == '_blank') { echo 'selected="selected"'; } ?> name="_blank">_blank</option>
								<option <?php if ($zobraz['menu_target'] == '_self') { echo 'selected="selected"'; } ?> name="_self">_self</option>
								<option <?php if ($zobraz['menu_target'] == '_parent') { echo 'selected="selected"'; } ?> name="_parent">_parent</option>
								<option <?php if ($zobraz['menu_target'] == '_top') { echo 'selected="selected"'; } ?> name="_top">_top</option>
							</select>
						</div>	
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['menu_popis']); ?>" name="popis">
					    </div>
					</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-info btn-sm" name="submit" value="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
						</div>
					</div>
					<input type="hidden" name="id1" value="<?php echo htmlspecialchars($zobraz['menu_id']); ?>">
					<input type="hidden" name="zorad" value="<?php echo htmlspecialchars($zobraz['menu_zorad']); ?>">
				</form>
			</div>
		</div>	
			<?php

		}
		else {
			# ERROR NIEJE ID NEEXISTUJE
			setcookie('odpoved', '1', time()+1);
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit();
		}


	}

	public function menuDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND $_POST['submit'] == 'submit' AND isset($id) AND isset($_POST['id1']) AND $id == $_POST['id1']) {

			$del = $this->db->query('DELETE FROM be_menu' . $this->prefix . ' 
										WHERE menu_id ="' . $this->db->real_escape_string($id) . '" ');


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST



		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
									WHERE menu_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
		<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #e60e0e; margin-bottom: 5px;">Vymazat</h3>
		
		<div class="panel panel-default">
			<div class="panel-heading">
		    	<h3 class="panel-title">Vymazat</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['menu_nazov']); ?></p>
					    </div>
					</div>

				<?php
				if ($zobraz['menu_rodic'] != '0') {
					?>	
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Sekcia</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php $this->selectRodic('2', $zobraz['menu_rodic'], '1'); //menuRodic ?></p>
					    </div>
					</div>
					<?php
				}
				else {
					?><input type="hidden" name="menuRodic" value="0"><?php
				}
				?>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Pozicia</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo $zobraz['menu_pozicia']; // positionId ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Typ zobrazenia</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['menu_typ']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Startovacia stranka</label>
					    <div class="col-sm-6">
								<p class="form-control-static">
								<?php
									if ($zobraz['menu_zobraz'] == 'null') {
										echo 'Nie';
									}
									else {
										echo 'Ano';
									}
								?>
								</p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Pre registrovanych</label>
					    <div class="col-sm-6">
								<p class="form-control-static">
								<?php
									if ($zobraz['menu_pre_register'] == 1) {
										# all
										echo 'Pre kazdeho.';
									}
									else {
										# 2// pre registrovanych
										echo 'Len pre registrovanych.';
									}
								?>
								</p>
					    </div>
					</div>

					<h5>Len pre galeriu</h5>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Galeria</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['menu_album']); ?></p>
					    </div>
					</div>

				
					<h5>Len pre modul</h5>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Modul</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php $this->selectModul('2', $zobraz['menu_modul_id'], '1'); // modulId ?></p>
					    </div>
					</div>

					<h5>Len pre URL adresu</h5>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Zobraz kam, kde</label>
					    <div class="col-sm-6">
							<p class="form-control-static"><?php echo htmlspecialchars($zobraz['menu_target']); ?></p>
						</div>	
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['menu_popis']); ?></p>
					    </div>
					</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit" value="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>
					<input type="hidden" name="id1" value="<?php echo htmlspecialchars($zobraz['menu_id']); ?>">
				</form>
			</div>
		</div>	
			<?php

		}
		else {
			# ERROR NIEJE ID NEEXISTUJE
			setcookie('odpoved', '1', time()+1);
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit();
		}

	}


	public function selectRodic ($typ = '1', $id_rodic = '0', $disabled = '0') {
		# $typ = 1; new    
		# $typ = 2; edit
		if ($typ == '1') {
			
			$poz1 = $this->db->query('SELECT pozicia_nazov, pozicia_aka, pozicia_id FROM be_pozicia' . $this->prefix . ' 
										ORDER BY pozicia_aka ASC ');
			if ($poz1->num_rows != FALSE) {

				?>
				<select class="form-control" name="menuRodic">
					<option name="0" value="0">Sekcia</option>
				<?php

				while ($po = $poz1->fetch_assoc()) {

					?><optgroup label="<?php echo htmlspecialchars($po['pozicia_aka'] . ' --> ' . $po['pozicia_nazov']); ?>"><?php
					
					$sel = $this->db->query('SELECT menu_nazov, menu_id, menu_pozicia, menu_rodic FROM be_menu' . $this->prefix . ' 
											WHERE menu_rodic =0 AND 
												menu_pozicia ="' . $this->db->real_escape_string($po['pozicia_nazov']) . '" 
											ORDER BY menu_nazov ASC ');
					if ($sel->num_rows != FALSE) {
						# OK JE

						while ($select = $sel->fetch_assoc()) {
							?><option name="<?php echo htmlspecialchars($select['menu_id']); ?>" value="<?php echo htmlspecialchars($select['menu_id']); ?>"><?php echo htmlspecialchars($select['menu_nazov']); ?></option><?php
						}

					}
					else {
						# NENI
						echo $this->lg[$this->flag]['nocat'];
					}
				}
				?>
				</select>
				<?php
			}
			else {
				echo $this->lg[$this->flag]['nopoz'];
			}

		}
		else {
			# $typ = 2;
			
			$poz1 = $this->db->query('SELECT pozicia_nazov, pozicia_aka, pozicia_id FROM be_pozicia' . $this->prefix . ' 
										ORDER BY pozicia_nazov ASC ');
			if ($poz1->num_rows != FALSE) {

				?>
				<select class="form-control" name="menuRodic" <?php if ($disabled == '1') { echo 'disabled="disabled"'; } ?> >
				<?php

				while ($po = $poz1->fetch_assoc()) {

					?><optgroup label="<?php echo htmlspecialchars($po['pozicia_aka'] . ' --> ' . $po['pozicia_nazov']); ?>"><?php

					$sel = $this->db->query('SELECT menu_nazov, menu_id FROM be_menu' . $this->prefix . ' 
												WHERE menu_rodic =0 AND 
													menu_pozicia ="' . $this->db->real_escape_string($po['pozicia_nazov']) . '" 
												ORDER BY menu_nazov ASC ');
					if ($sel->num_rows != FALSE) {
						# OK JE

						while ($select = $sel->fetch_assoc()) {

							if ($select['menu_id'] == $id_rodic) {
								?><option name="<?php echo htmlspecialchars($select['menu_id']); ?>" value="<?php echo htmlspecialchars($select['menu_id']); ?>" selected="selected"><?php echo htmlspecialchars($select['menu_nazov']); ?></option><?php
							}
							else {
								?><option name="<?php echo htmlspecialchars($select['menu_id']); ?>" value="<?php echo htmlspecialchars($select['menu_id']); ?>"><?php echo htmlspecialchars($select['menu_nazov']); ?></option><?php
							}
				
						}

					}
					else {
						# NENI
						echo $this->lg[$this->flag]['nocat'];
					}
				}
				?>
				</select>
				<?php
			}		
		}
	}

	public function selectPosition ($typ = '1', $nazov = '') {
		# $typ = 1; // new
		# $typ = 2; // edit

		#nazov = nazov position pri edit

		if ($typ == '1') {
			
			$posit = $this->db->query('SELECT * FROM be_pozicia' . $this->prefix . ' 
											ORDER BY pozicia_aka ASC ');
			if ($posit->num_rows != FALSE) {
				
				?>
				<select class="form-control" name="positionId">
				<?php

				while ($position = $posit->fetch_assoc()) {
					?><option name="<?php echo htmlspecialchars($position['pozicia_nazov']); ?>" value="<?php echo htmlspecialchars($position['pozicia_nazov']); ?>"><?php echo htmlspecialchars($position['pozicia_aka'] . ' --> ' . $position['pozicia_nazov']); ?></option><?php
				}

				?>
				</select>
				<?php

			}
			else {
				echo $this->lg[$this->flag]['nopozicia'];
			}

		}
		else {
			# $typ = 2; // edit


			$posit = $this->db->query('SELECT * FROM be_pozicia' . $this->prefix . ' 
											ORDER BY pozicia_nazov ASC ');
			if ($posit->num_rows != FALSE) {
				
				?>
				<select class="form-control" name="positionId">
				<?php

				while ($position = $posit->fetch_assoc()) {
					
					if ($position['pozicia_nazov'] == $nazov) {
						?><option name="<?php echo htmlspecialchars($position['pozicia_nazov']); ?>" value="<?php echo htmlspecialchars($position['pozicia_nazov']); ?>" selected="selected"><?php echo htmlspecialchars($position['pozicia_aka'] . ' --> ' . $position['pozicia_nazov']); ?></option><?php
					}
					else {
						?><option name="<?php echo htmlspecialchars($position['pozicia_nazov']); ?>" value="<?php echo htmlspecialchars($position['pozicia_nazov']); ?>"><?php echo htmlspecialchars($position['pozicia_aka'] . ' --> ' . $position['pozicia_nazov']); ?></option><?php
					}

				}

				?>
				</select>
				<?php
			}
			else {
				echo $this->lg[$this->flag]['nopozicia'];
			}

		}
	}

	public function albumSelect ($typ = '1') {
		$query = $this->db->query('SELECT album_id, album_nazov FROM be_album' . $this->prefix . ' 
									WHERE album_stav = 1 
										ORDER BY album_id DESC ');
		if ($query->num_rows != FALSE) {
			$pole = array();
			while ($rows = $query->fetch_assoc()) {
				$pole[$rows['album_id']][$rows['album_nazov']][] = $rows;
			}
		}
		return $pole;

	}


	public function selectModul ($typ = '1', $modul = '', $disabled = '0') {

		# $typ = 1; // new
		# $typ = 2; // edit

		#modul = modul id modulu

		if ($typ == '1') {
			
			$posit = $this->db->query('SELECT * FROM be_modul' . $this->prefix . ' 
											ORDER BY modul_nazov ASC ');
			if ($posit->num_rows != FALSE) {
				
				?>
				<select class="form-control" name="modulId">
					<option name="0" value="0">NIJAKY</option>
				<?php

				while ($position = $posit->fetch_assoc()) {
					?><option name="<?php echo htmlspecialchars($position['modul_id']); ?>" value="<?php echo htmlspecialchars($position['modul_id']); ?>"><?php echo htmlspecialchars($position['modul_nazov']); ?></option><?php
				}

				?>
				</select>
				<?php

			}
			else {
				echo $this->lg[$this->flag]['nomodul'];
			}

		}
		else {
			# $typ = 2; // edit


			$posit = $this->db->query('SELECT * FROM be_modul' . $this->prefix . ' 
											ORDER BY modul_nazov ASC ');
			if ($posit->num_rows != FALSE) {
				
				?>
				<select class="form-control" name="modulId" <?php if ($disabled == '1') { echo 'disabled="disabled"'; } ?> >
					<option name="0" value="0">NIJAKY</option>
				<?php

				while ($position = $posit->fetch_assoc()) {
					
					if ($position['modul_id'] == $modul) {
						?><option name="<?php echo htmlspecialchars($position['modul_id']); ?>" value="<?php echo htmlspecialchars($position['modul_id']); ?>" selected="selected"><?php echo htmlspecialchars($position['modul_nazov']); ?></option><?php
					}
					else {
						?><option name="<?php echo htmlspecialchars($position['modul_id']); ?>" value="<?php echo htmlspecialchars($position['modul_id']); ?>"><?php echo htmlspecialchars($position['modul_nazov']); ?></option><?php
					}

				}

				?>
				</select>
				<?php
			}
			else {
				echo $this->lg[$this->flag]['nomodul'];
			}

		}

	}

	public function startStranka ($id) {

		$null = 'null';

		$query = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
										WHERE menu_id ="' . $this->db->real_escape_string($id) . '" AND 
												menu_zobraz ="' . $this->db->real_escape_string($null) . '" ');
		if ($query->num_rows != FALSE) {
			# OK EXISUJE

			$tlac = $query->fetch_assoc();

			$null2 = 'start';

			#Zmena z start na null
			$nac = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
										WHERE menu_zobraz ="' . $this->db->real_escape_string($null2) . '" LIMIT 1 ');
			if ($nac->num_rows != FALSE) {
				# JE
				$tlac2 = $nac->fetch_assoc();

				# z null na start
				$update1 = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET
												menu_zobraz ="' . $this->db->real_escape_string($null2) . '" 
												WHERE menu_id ="' . $this->db->real_escape_string($tlac['menu_id']) . '"  ');

				# z start na null
				$update2 = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET
												menu_zobraz ="' . $this->db->real_escape_string($null) . '" 
												WHERE menu_id ="' . $this->db->real_escape_string($tlac2['menu_id']) . '"  ');
				# PRESMEROVANIE
				setcookie('odpoved', '2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();
			}
			else {
				# ERROR
				setcookie('odpoved', '1', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();
			}

		}
		else {
			setcookie('odpoved', '1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}

	}

	public function aktualizujZorad () {

		$kontrola = 1;
		$kontrola2 = 1;

		$quer = $this->db->query('SELECT * FROM be_pozicia' . $this->prefix . ' 
									WHERE pozicia_aka = "menu" 
									ORDER BY pozicia_id ASC ');
		if ($quer->num_rows != FALSE) {
			while ($tlac = $quer->fetch_assoc()) { // pozicia

				$menu = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
											WHERE menu_pozicia ="' . $this->db->real_escape_string($tlac['pozicia_nazov']) . '" AND 
													menu_rodic =0 
											ORDER BY menu_zorad ASC ');
				if ($menu->num_rows != FALSE) {
					$kontrola2 = 1;
					while ($tlac2 = $menu->fetch_assoc()) { // menu sekcia

						//echo $tlac2['menu_nazov'] . '  ' . $tlac2['menu_zorad'] . '</br>';

						$update = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET 
														menu_zorad ="' . $this->db->real_escape_string($kontrola2) . '" 
													WHERE menu_id ="' . $this->db->real_escape_string($tlac2['menu_id']) . '" ');
						$kontrola2 +=1;

						# ZISTIME CI MA RODICA 
						$rodic = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
													WHERE menu_pozicia ="' . $this->db->real_escape_string($tlac['pozicia_nazov']) . '" AND 
														menu_rodic ="' . $this->db->real_escape_string($tlac2['menu_id']) . '" 
													ORDER BY menu_zorad ASC ');
						if ($rodic->num_rows != FALSE) {
							$kontrola = 1;
							while ($tlac3 = $rodic->fetch_assoc()) { // kategoria
								
								$update = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET 
														menu_zorad ="' . $this->db->real_escape_string($kontrola) . '" 
														WHERE menu_id ="' . $this->db->real_escape_string($tlac3['menu_id']) . '" ');
								$kontrola +=1;

							}
						}
						# END KATEGORIA

					}
				}
				# END SEKCIA MENU

			}
		}
		# END POZICIA

	}


	public function order ($zorad, $id) {

		$query = $this->db->query('SELECT * FROM be_menu' . $this->prefix . '
									WHERE menu_id ="' . $this->db->real_escape_string($id) . '" AND 
										menu_zorad ="' . $this->db->real_escape_string($zorad) . '" ');
		if ($query->num_rows != FALSE) {
			# OK  JE
			$tlac = $query->fetch_assoc();


			# SEKCIA ALEBO KATEGORIA
			if ($tlac['menu_rodic'] == '0') {
				# SEKCIA

				$nac = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
										WHERE menu_pozicia ="' . $this->db->real_escape_string($tlac['menu_pozicia']) . '" AND
											menu_rodic =0 AND 
											menu_id ="' . $this->db->real_escape_string($id) . '" 
										ORDER BY menu_zorad DESC LIMIT 1 ');
				if ($nac->num_rows != FALSE) {
					# OK JE
					$nac1 = $nac->fetch_assoc();

					$order = $nac1['menu_zorad'] + 1;

					# OPAK DB NACITANIE
					$opakOrder = $this->db->query('SELECT * FROM be_menu' . $this->prefix .  ' 
													WHERE menu_pozicia ="' . $this->db->real_escape_string($tlac['menu_pozicia']) . '" AND 
														menu_rodic =0 AND 
														menu_zorad ="' . $this->db->real_escape_string($order) . '" 
													ORDER BY menu_zorad DESC LIMIT 1 ');
					if ($opakOrder->num_rows != FALSE) {
						# OK JE

						$nac2 = $opakOrder->fetch_assoc();

						# ULOZ -1
						$uloz = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET 
												menu_zorad ="' . $this->db->real_escape_string($nac1['menu_zorad']) . '" 
											WHERE menu_id ="' . $this->db->real_escape_string($nac2['menu_id']) . '" ');


						# ULOZ +1
						$uloz = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET 
												menu_zorad ="' . $this->db->real_escape_string($nac2['menu_zorad']) . '" 
											WHERE menu_id ="' . $this->db->real_escape_string($nac1['menu_id']) . '" ');

						# PRESMEROVANIE
						setcookie('odpoved', '2', time()+1);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						exit();
					}
					else {
						# ERROR
						setcookie('odpoved', '1', time()+1);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						exit();
					}
	
				}
				else {
					# ERROR
					setcookie('odpoved', '1', time()+1);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();
				}
			}
			else {
				# KATEGORIA

				$nac = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
										WHERE menu_pozicia ="' . $this->db->real_escape_string($tlac['menu_pozicia']) . '" AND 
											menu_rodic ="' . $this->db->real_escape_string($tlac['menu_rodic']) . '" AND 
											menu_id ="' . $this->db->real_escape_string($id) . '" 
										ORDER BY menu_zorad DESC LIMIT 1 ');
				if ($nac->num_rows != FALSE) {
					# OK JE
					$nac1 = $nac->fetch_assoc();

					$order = $nac1['menu_zorad'] + 1;


					# OPAK DB NACITANIE
					$opakOrder = $this->db->query('SELECT * FROM be_menu' . $this->prefix .  ' 
													WHERE menu_pozicia ="' . $this->db->real_escape_string($tlac['menu_pozicia']) . '" AND 
														menu_rodic != 0 AND 
														menu_zorad ="' . $this->db->real_escape_string($order) . '" 
													ORDER BY menu_zorad DESC LIMIT 1 ');
					if ($opakOrder->num_rows != FALSE) {
						# OK JE

						$nac2 = $opakOrder->fetch_assoc();

						# ULOZ -1
						$uloz = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET 
												menu_zorad ="' . $this->db->real_escape_string($nac1['menu_zorad']) . '" 
											WHERE menu_id ="' . $this->db->real_escape_string($nac2['menu_id']) . '" ');


						# ULOZ +1
						$uloz = $this->db->query('UPDATE be_menu' . $this->prefix . ' SET 
												menu_zorad ="' . $this->db->real_escape_string($nac2['menu_zorad']) . '" 
											WHERE menu_id ="' . $this->db->real_escape_string($nac1['menu_id']) . '" ');


						# PRESMEROVANIE
						setcookie('odpoved', '2', time()+1);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						exit();
					}
					else {
						# ERROR
						setcookie('odpoved', '1', time()+1);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						exit();
					}

				}
				else {
					# ERROR
					setcookie('odpoved', '1', time()+1);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();
				}

			}
			# SKKS
		}
		else {
			# ERROR
			setcookie('odpoved', '1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}

	}



}


//$language = new language($this->db, $this->prefix, $this->lang);
$menu = new menu($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>