<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class functio extends url {

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
		echo $this->lg[$this->flag]['nadpisFunction'];
	}


	public function functionTab () {

				?>
				<div class="panel panel-default">
					<div class="panel-heading">
					<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->lg[$this->flag]['nadpisFunction']); ?></h3>
					</div>
					<div class="panel-body">
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new'; ?>" title="Pridat">Pridat <i class="glyphicon glyphicon-plus"></i></a>
					</div>
				<?php

		$query = $this->db->query('SELECT *, f.right_id, f.group_id, r.right_id, g.group_id 
									FROM be_function' . $this->prefix . ' f 
										JOIN be_right' . $this->prefix . ' r ON f.right_id = r.right_id 
										JOIN be_group' . $this->prefix . ' g ON f.group_id = g.group_id 
									ORDER BY f.group_id ASC ');
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$last = 0;
			//$por = $por + $start;

			$colorA = array('1' => 'text-success',
							'0' => 'text-danger' );
			$ikonaA = array('1' => 'glyphicon glyphicon-ok',
							'0' => 'glyphicon glyphicon-remove');

			?>
			<table class="table table-hover">
				<tr>
					<th></th>
					<th>#</th>
					<th>Skupina</th>
					<th>Pravo</th>
					<th>Citat</th>
					<th>Pisat</th>
					<th>Editovat</th>
					<th>Zmazat</th>
					<th>Zobrazovat</th>
					<th>Vlastne</th>

					<th><i class="glyphicon glyphicon-star">  </i>Akcia</th>
				</tr>
			<?php
			while ($func = $query->fetch_assoc()) {
				$por = + $por +1;
				

				###########
				if ($last != $func['group_nazov'] OR $por == '1') {
					$por = 1;
					?><tr class="text-primary" style="background: trasparent;"><th><?php echo htmlspecialchars($func['group_nazov']); ?></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr><?php
				}
				###########
				?>
				<tr class="<?php //echo htmlspecialchars($colorA[$user['user_typ']]); ?>">
					<td></td>
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php echo htmlspecialchars($func['group_nazov']); ?></td>
					<td><?php echo htmlspecialchars($func['right_nazov']); ?></td>
					<td><i class="<?php echo $ikonaA[htmlspecialchars($func['function_read'])] . ' ' . $colorA[$func['function_read']]; ?>"></i></td>
					<td><i class="<?php echo $ikonaA[htmlspecialchars($func['function_write'])] . ' ' . $colorA[$func['function_write']]; ?>"></i></td>
					<td><i class="<?php echo $ikonaA[htmlspecialchars($func['function_edit'])] . ' ' . $colorA[$func['function_edit']]; ?>"></i></td>
					<td><i class="<?php echo $ikonaA[htmlspecialchars($func['function_delete'])] . ' ' . $colorA[$func['function_delete']]; ?>"></i></td>
					<td><i class="<?php echo $ikonaA[htmlspecialchars($func['function_view'])] . ' ' . $colorA[$func['function_view']]; ?>"></i></td>
					<td><i class="<?php echo $ikonaA[htmlspecialchars($func['function_my'])] . ' ' . $colorA[$func['function_my']]; ?>"></i></td>

					<td>
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($func['function_id']); ?>"  alt="Zmenit" title="Zmenit">
							<i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i>
						</a>
						&nbsp;
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($func['function_id']); ?>"  alt="Vymazat" title="Vymazat">
							<i class="glyphicon glyphicon-trash" style="color: red; "></i>
						</a>
					</td>
				</tr>
				<?php
				#####
				$last = $func['group_nazov']; 

			}
			?></table><?php 
			 
		}
		?></div><?php
	}


	public function functionNew () {


		#POST
		if (isset($_POST['submit']) AND isset($_POST['group']) AND isset($_POST['right']) AND isset($_POST['read']) AND isset($_POST['write']) AND isset($_POST['edit']) AND isset($_POST['del']) AND isset($_POST['view']) AND isset($_POST['my'])) {
			
			$group = (isset($_POST['group'])) ? trim($_POST['group']) : '';
			$right = (isset($_POST['right'])) ? trim($_POST['right']) : '';
			
			$read = (isset($_POST['read'])) ? trim($_POST['read']) : '';
			$write = (isset($_POST['write'])) ? trim($_POST['write']) : '';
			$edit = (isset($_POST['edit'])) ? trim($_POST['edit']) : '';
			$view = (isset($_POST['view'])) ? trim($_POST['view']) : '';
			$del = (isset($_POST['del'])) ? trim($_POST['del']) : '';
			$my = (isset($_POST['my'])) ? trim($_POST['my']) : '';

			if (!empty($group) AND !empty($right) AND $read != '' AND $write != '' AND $edit != '' AND $del != '' AND $view != '' AND $my != '') {
				
				$uloz = $this->db->query('INSERT IGNORE INTO be_function' . $this->prefix . ' 
								(function_id, group_id, right_id, function_read, function_write, function_edit, function_delete, function_view, function_my) VALUES 
												(NULL,
												 "' . $this->db->real_escape_string($group) . '",
												 "' . $this->db->real_escape_string($right) . '",
												 "' . $this->db->real_escape_string($read) . '",
												 "' . $this->db->real_escape_string($write) . '",
												 "' . $this->db->real_escape_string($edit) . '",
												 "' . $this->db->real_escape_string($del) . '",
												 "' . $this->db->real_escape_string($view) . '",
												 "' . $this->db->real_escape_string($my) . '") ');
				/*
				#// ci tam je folder ak nieje pridame
				$over = $this->db->query('SELECT right_id, right_folder 
											FROM be_right' . $this->prefix . ' 
											WHERE right_id ="' . $this->db->real_escape_string(intval($right)) . '" ');
				if ($over->num_rows != FALSE) {
					# OK JE
					$over1 = $over->fetch_assoc();

					// folder 
					$hladaj = $this->db->query('SELECT right_id, right_url, right_folder 
													FROM be_right' . $this->prefix . ' 
													WHERE right_folder ="' . $this->db->real_escape_string($over1['right_folder']) . '" AND 
														right_folder = right_url ');
					if ($hladaj->num_rows != FALSE) {
						# OK

						// mame id SEKCIE
						$hladaj1 = $hladaj->fetch_assoc();

						$zisti = $this->db->query('SELECT right_id FROM be_function' . $this->prefix . ' 
														WHERE right_id ="' . $this->db->real_escape_string($hladaj1['right_id']) . '" ');
						if ($zisti->num_rows == FALSE) {
							# NENI, tak vlozime zaznam, sekciu
							$ulozS = $this->db->query('INSERT IGNORE INTO be_function' . $this->prefix . ' 
								(function_id, group_id, right_id, function_read, function_write, function_edit, function_view, function_my) VALUES 
												(NULL,
												 "' . $this->db->real_escape_string($group) . '",
												 "' . $this->db->real_escape_string($right) . '",
												 "' . $this->db->real_escape_string($read) . '",
												 "' . $this->db->real_escape_string($write) . '",
												 "' . $this->db->real_escape_string($edit) . '",
												 "' . $this->db->real_escape_string($view) . '",
												 "' . $this->db->real_escape_string($my) . '") ')
						}
					}
				}
				else {
					# NENEI
				}
				*/

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
			<h3 class="panel-title">Pridat <?php echo ucfirst($this->lg[$this->flag]['nadpisFunction']); ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Skupina</label>
				    <div class="col-sm-6">
						<?php 
							$sel = $this->groupSelect();
						?>
						<select class="form-control" name="group">
							<?php
							foreach ($sel as $id => $id1) {
								foreach ($id1 as $nazov => $nazov1) {
									?><option value="<?php echo htmlspecialchars($id); ?>" name="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
								}
							}
							?>
						</select>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Pravo</label>
				    <div class="col-sm-6">
						<?php 
							$sel = $this->rightSelect();
						?>
						<select class="form-control" name="right">
							<?php
							foreach ($sel as $option => $sel1) {
								foreach ($sel1 as $id => $id1) {
									foreach ($id1 as $nazov => $nazov1) {
										foreach ($nazov1 as $folder => $folder1) {

											if ($option == '1') {
												?><optgroup label="<?php echo htmlspecialchars($nazov); ?>"><?php
											}
											?><option value="<?php echo htmlspecialchars($id); ?>" name="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
											if ($option == '1') {
												?></optgroup><?php
											}
										}
									}
								}
							}	
							?>
						</select>
				    </div>
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Citat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="read" id="optionsRadios1" value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="read" id="optionsRadios2" value="0" checked="checked">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Pisat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="write" id="optionsRadios1" value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="write" id="optionsRadios2" value="0" checked="checked">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Editovat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="edit" id="optionsRadios1" value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="edit" id="optionsRadios2" value="0" checked="checked">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Zmazanie</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="del" id="optionsRadios1" value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="del" id="optionsRadios2" value="0" checked="checked">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Zobrazovat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="view" id="optionsRadios1" value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="view" id="optionsRadios2" value="0" checked="checked">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Vlastne</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="my" id="optionsRadios1" value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="my" id="optionsRadios2" value="0" checked="checked">
							    Nie
							</label>
					    </div>
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


	public function functionEdit ($id) {


		#POST
		if (isset($_POST['submit']) AND isset($_POST['group']) AND isset($_POST['right']) AND isset($_POST['read']) AND isset($_POST['write']) AND isset($_POST['edit']) AND isset($_POST['del']) AND isset($_POST['view']) AND isset($_POST['my'])) {
			
			$group = (isset($_POST['group'])) ? trim($_POST['group']) : '';
			$right = (isset($_POST['right'])) ? trim($_POST['right']) : '';
			
			$read = (isset($_POST['read'])) ? trim($_POST['read']) : '';
			$write = (isset($_POST['write'])) ? trim($_POST['write']) : '';
			$edit = (isset($_POST['edit'])) ? trim($_POST['edit']) : '';
			$del = (isset($_POST['del'])) ? trim($_POST['del']) : '';
			$view = (isset($_POST['view'])) ? trim($_POST['view']) : '';
			$my = (isset($_POST['my'])) ? trim($_POST['my']) : '';

			if (!empty($group) AND !empty($right) AND $read != '' AND $write != '' AND $edit != '' AND $del != '' AND $view != '' AND $my != '') {
				
				$uloz = $this->db->query('UPDATE be_function' . $this->prefix . ' SET 
												group_id ="' . $this->db->real_escape_string($group) . '",
												right_id ="' . $this->db->real_escape_string($right) . '",
												function_read ="' . $this->db->real_escape_string($read) . '",
												function_write ="' . $this->db->real_escape_string($write) . '",
												function_edit ="' . $this->db->real_escape_string($edit) . '",
												function_delete ="' . $this->db->real_escape_string($del) . '",
												function_view ="' . $this->db->real_escape_string($view) . '",
												function_my ="' . $this->db->real_escape_string($my) . '"
											WHERE function_id ="' . $this->db->real_escape_string(intval($id)) . '"	 ');


				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();

			}
			else {
				#ERROR

				setcookie('odpoved','1', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . $_GET['edit']);
				exit();
			}
		}

		$query = $this->db->query('SELECT * FROM be_function' . $this->prefix . ' 
										WHERE function_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FALSE) {
			
			$zobraz = $query->fetch_assoc();

		?>
<!-- <h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat <?php //echo $this->lg[$this->flag]['nadpisGroup']; ?></h3> -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Pridat <?php echo ucfirst($this->lg[$this->flag]['nadpisFunction']); ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Skupina</label>
				    <div class="col-sm-6">
						<?php 
							$sel = $this->groupSelect($zobraz['group_id']);
						?>
						<select class="form-control" name="group">
							<?php
							foreach ($sel as $id => $id1) {
								foreach ($id1 as $nazov => $nazov1) {
									?><option <?php if($zobraz['group_id'] == $id) { echo ' selected="selected" '; } ?> value="<?php echo htmlspecialchars($id); ?>" name="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
								}
							}
							?>
						</select>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Pravo</label>
				    <div class="col-sm-6">
						<?php 
							$sel = $this->rightSelect($zobraz['right_id']);
						?>
						<select class="form-control" name="right">
							<?php
							foreach ($sel as $option => $sel1) {
								foreach ($sel1 as $id => $id1) {
									foreach ($id1 as $nazov => $nazov1) {
										foreach ($nazov1 as $folder => $folder1) {

											if ($option == '1') {
												?><optgroup label="<?php echo htmlspecialchars($nazov); ?>"><?php
											}
											?><option <?php if($zobraz['right_id'] == $id) { echo ' selected="selected" '; } ?> value="<?php echo htmlspecialchars($id); ?>" name="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
											if ($option == '1') {
												?></optgroup><?php
											}
										}
									}
								}
							}	
							?>
						</select>
				    </div>
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Citat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="read" id="optionsRadios1" <?php if($zobraz['function_read'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="read" id="optionsRadios2" <?php if($zobraz['function_read'] == '0') { echo ' checked="checked" '; } ?> value="0" >
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Pisat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="write" id="optionsRadios1" <?php if($zobraz['function_write'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="write" id="optionsRadios2" <?php if($zobraz['function_write'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Editovat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="edit" id="optionsRadios1" <?php if($zobraz['function_edit'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="edit" id="optionsRadios2" <?php if($zobraz['function_edit'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Zmazanie</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="del" id="optionsRadios1" <?php if($zobraz['function_delete'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="del" id="optionsRadios2" <?php if($zobraz['function_delete'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Zobrazovat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="view" id="optionsRadios1" <?php if($zobraz['function_view'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="view" id="optionsRadios2" <?php if($zobraz['function_view'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Vlastne</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="my" id="optionsRadios1" <?php if($zobraz['function_my'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="my" id="optionsRadios2" <?php if($zobraz['function_my'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
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


	public function functionDelete ($id) {


		#POST
		if (isset($_POST['submit']) AND isset($_POST['idd'])) {
			
			$idd = (isset($_POST['idd'])) ? trim($_POST['idd']) : '';


			if (!empty($idd) AND $idd == $id) {
				
				$uloz = $this->db->query('DELETE FROM be_function' . $this->prefix . '  
											WHERE function_id ="' . $this->db->real_escape_string(intval($idd)) . '" ');


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


		$over = $this->db->query('SELECT * FROM be_function' . $this->prefix . ' 
										WHERE function_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();
		?>
<!-- <h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat <?php //echo $this->lg[$this->flag]['nadpisGroup']; ?></h3> -->
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">Zmenit <?php echo ucfirst($this->lg[$this->flag]['nadpisFunction']); ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="">
			<fieldset disabled>
				
				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Skupina</label>
				    <div class="col-sm-6">
						<?php 
							$sel = $this->groupSelect($zobraz['group_id']);
						?>
						<select class="form-control" name="group">
							<?php
							foreach ($sel as $id => $id1) {
								foreach ($id1 as $nazov => $nazov1) {
									?><option <?php if($zobraz['group_id'] == $id) { echo ' selected="selected" '; } ?> value="<?php echo htmlspecialchars($id); ?>" name="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
								}
							}
							?>
						</select>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Pravo</label>
				    <div class="col-sm-6">
						<?php 
							$sel = $this->rightSelect($zobraz['right_id']);
						?>
						<select class="form-control" name="right">
							<?php
							foreach ($sel as $option => $sel1) {
								foreach ($sel1 as $id => $id1) {
									foreach ($id1 as $nazov => $nazov1) {
										foreach ($nazov1 as $folder => $folder1) {

											if ($option == '1') {
												?><optgroup label="<?php echo htmlspecialchars($nazov); ?>"><?php
											}
											?><option <?php if($zobraz['right_id'] == $id) { echo ' selected="selected" '; } ?> value="<?php echo htmlspecialchars($id); ?>" name="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
											if ($option == '1') {
												?></optgroup><?php
											}
										}
									}
								}
							}	
							?>
						</select>
				    </div>
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Citat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="read" id="optionsRadios1" <?php if($zobraz['function_read'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="read" id="optionsRadios2" <?php if($zobraz['function_read'] == '0') { echo ' checked="checked" '; } ?> value="0" >
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Pisat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="write" id="optionsRadios1" <?php if($zobraz['function_write'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="write" id="optionsRadios2" <?php if($zobraz['function_write'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Editovat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="edit" id="optionsRadios1" <?php if($zobraz['function_edit'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="edit" id="optionsRadios2" <?php if($zobraz['function_edit'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Mazanie</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="del" id="optionsRadios1" <?php if($zobraz['function_delete'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="del" id="optionsRadios2" <?php if($zobraz['function_delete'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Zobrazovat</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="view" id="optionsRadios1" <?php if($zobraz['function_view'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="view" id="optionsRadios2" <?php if($zobraz['function_view'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Vlastne</label>
					<div class="col-sm-6">
					    <div class="radio">
							<label>
								<input type="radio" name="my" id="optionsRadios1" <?php if($zobraz['function_my'] == '1') { echo ' checked="checked" '; } ?> value="1">
								Ano
							</label>
					    </div>
					    <div class="radio">
							<label>
								<input type="radio" name="my" id="optionsRadios2" <?php if($zobraz['function_my'] == '0') { echo ' checked="checked" '; } ?> value="0">
							    Nie
							</label>
					    </div>
					</div>	
				</div>
</fieldset>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						<button class="btn btn-danger btn-sm" type="reset" onclick="location.href(window.history.back());"><i class="glyphicon glyphicon-remove"></i>&nbsp;Reset</button>
					</div>
				</div>
				<input type="hidden" name="idd" value="<?php echo htmlspecialchars($zobraz['function_id']); ?>">
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
	public function groupSelect ($id = '0') {

		$nac = $this->db->query('SELECT group_id, group_nazov FROM be_group' . $this->prefix . ' 
									ORDER BY group_nazov ASC ');
		if ($nac->num_rows != FALSE) {
			
			$pole[] = array();
			while ($tlac = $nac->fetch_assoc()) {
				$pole[$tlac['group_id']][$tlac['group_nazov']] = $tlac;
			}
		}
		else {
			$pole =0;
		}
		return $pole;
	}

	public function rightSelect ($id = '0') {

		$nac = $this->db->query('SELECT right_id, right_nazov, right_folder, right_url FROM be_right' . $this->prefix . ' 
									ORDER BY right_folder ASC ');
		if ($nac->num_rows != FALSE) {
			
			$pole[] = array();
			while ($tlac = $nac->fetch_assoc()) {

				if ($tlac['right_url'] == $tlac['right_folder']) {
					$option = 1;
				}
				else {
					$option = 0;
				}

				$pole[$option][$tlac['right_id']][$tlac['right_nazov']][$tlac['right_folder']] = $tlac;
			}
		}
		else {
			$pole =0;
		}
		return $pole;
	}

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
$function = new functio($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>