<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class users extends url {

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
		echo $this->lg[$this->flag]['nadpisUsers'];
	}


	public function usersTab () {

		# GET STRANA
		if (isset($_GET['page']) AND $_GET['page'] != '0') {
			$page = $_GET['page'];
		}
		else {
			$page =1;
		}

		$limit = 10;
		$range = 10;

		$start = ($page - 1)* $limit;

				?>
				<div class="panel panel-default">
					<div class="panel-heading">
				    	<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->nadpis()); ?></h3>
					</div>
					<div class="panel-body">
					</div>
				<?php

		$query = $this->db->query('SELECT SQL_CALC_FOUND_ROWS  *, u.group_id, g.group_id   
										FROM be_user' . $this->prefix . ' u 
										JOIN be_group' . $this->prefix . ' g ON u.group_id = g.group_id 
									ORDER BY user_reg DESC LIMIT ' . $start . ', ' . $limit . ' ');
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$por = $por + $start;

			$publikA = array('1' => 'aktivny',
							 '2' => 'Zablokovany',
							 '3' => 'Neaktivovany' );
			$colorA = array('1' => 'success',
							 '2' => 'danger',
							 '3' => 'warning');
			$ikonA = array('1' => 'glyphicon glyphicon-ok-circle',
							'2' => 'glyphicon glyphicon-remove-circle',
							'3' => 'glyphicon glyphicon-warning-sign');

			$colA = array('1' => 'green',
							'2' => 'red',
							'3' => '#c4be16');
			?>
			<table class="table table-hover">
				<tr>
					<th>#</th>
					<th>Avatar</th>
					<th>Meno</th>
					<th>Nick</th>
					<th>Email</th>
					<th>Telefon</th>
					<th>Datum reg.</th>
					<th>Stav</th>
					<th>Skupina</th>
			   <!-- <th>heslo</th>
					<th>Salt</th>
					<th>Key</th> -->
					<th><i class="glyphicon glyphicon-star">  </i>Akcia</th>
				</tr>
			<?php
			while ($user = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php echo htmlspecialchars($colorA[$user['user_typ']]); ?>">
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php if ($user['user_avatar'] != FALSE) { ?><img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . IMAGE_USER_MINI . htmlspecialchars($user['user_avatar']); ?>"><?php }else { ?><img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>"><?php } ?></td>
					<td><?php echo htmlspecialchars($user['user_meno']); ?></td>
					<td><?php echo htmlspecialchars($user['user_nick']); ?></td>
					<td><?php echo htmlspecialchars($user['user_email']); ?></td>
					<td><?php echo htmlspecialchars($user['user_telefon']); ?></td>
					<td><?php echo htmlspecialchars($user['user_reg']); ?></td>
					<td><?php echo htmlspecialchars($publikA[$user['user_typ']]); ?>&nbsp;<i class="<?php echo $ikonA[$user['user_typ']]; ?>" style="color: <?php echo $colA[$user['user_typ']]; ?>" title="<?php echo $publikA[$user['user_typ']] ?>"></i></td>
					<td><?php echo htmlspecialchars($user['group_id'] . ' ' . $user['group_nazov']); ?></td>
			<?php /*<td><?php echo htmlspecialchars($user['user_heslo']); ?></td>
					<td><?php echo htmlspecialchars($user['user_salt']); ?></td>
					<td><?php echo htmlspecialchars($user['user_key']); ?></td> */?>
					<td>
							<?php
								if ($user['user_typ'] == '1') {
									# active
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&deactive=' . $user['user_id']);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
								}
								elseif ($user['user_typ'] == '3') {
									# active email
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&email=' . $user['user_id']);  ?>" alt="Aktivacia emailom" title="Aktivacia emailom"><i class="glyphicon glyphicon-warning-sign" style="color: #bdbd04"></i></a>&nbsp;<?php
								
								}
								else { // 2 zablokovany
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&active=' . $user['user_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

								?>

						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&view=' . htmlspecialchars($user['user_id']); ?>"  alt="Zobrazit" title="Zobrazit">
							<i class="glyphicon glyphicon-eye-open" style="color: grau;"></i>
						</a>
						&nbsp;
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($user['user_id']); ?>"  alt="Zmenit" title="Zmenit">
							<i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i>
						</a>
						&nbsp;
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($user['user_id']); ?>"  alt="Vymazat" title="Vymazat">
							<i class="glyphicon glyphicon-trash" style="color: red; "></i>
						</a>
					</td>
				</tr>
				<?php

			}
			?></table><?php 

			# STRANKAOVANIE
			//$this->strankovanie($limit, $range, $url_zacni, $url_menu);
			$this->strankovanie($limit, $range, URL_ADRESA, '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			 
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


	public function usersActive ($id) {
		$query = $this->db->query('SELECT user_id FROM be_user' . $this->prefix . ' 
							WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_user' . $this->prefix . ' SET 
										user_typ =1 WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');

			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
	}

	public function usersDeactive ($id) {
		$query = $this->db->query('SELECT user_id FROM be_user' . $this->prefix . ' 
							WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_user' . $this->prefix . ' SET 
										user_typ =2 WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');

			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
	}

	public function usersActiveEmail ($id) {
		$query = $this->db->query('SELECT user_id FROM be_user' . $this->prefix . ' 
							WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_user' . $this->prefix . ' SET 
										user_typ =1 WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');

			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}
	}

	public function usersNew () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['nick']) AND isset($_POST['meno']) AND isset($_POST['email']) AND isset($_POST['tel']) AND isset($_POST['heslo']) AND isset($_POST['datum']) AND isset($_POST['group']) AND isset($_POST['typ']) /*AND isset($_POST['image'])*/ ) {


			$nick = (isset($_POST['nick'])) ? $_POST['nick'] : '';
			$meno = (isset($_POST['meno'])) ? $_POST['meno'] : '';
			$email = (isset($_POST['email'])) ? $_POST['email'] : '';

			$tel = (isset($_POST['tel'])) ? $_POST['tel'] : '';
			$heslo = (isset($_POST['heslo'])) ? $_POST['heslo'] : '';
			$datum = (isset($_POST['datum'])) ? $_POST['datum'] : '';

			$group = (isset($_POST['group'])) ? $_POST['group'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			/*
			echo $_FILES["image"]['name'] . '<br>';
			*//*
			echo $nazov . ' 1</br>';
			echo $popis . ' 2</br>';
			echo $text . ' 3</br>';
			echo $menu . ' 4</br>';
			echo $tags . ' 5</br>';
			die();*/

			if (!empty($nick) AND !empty($meno) AND !empty($email) AND !empty($tel) AND !empty($heslo) AND !empty($datum) AND !empty($group) AND !empty($typ) /*AND !empty($_FILES["image"])*/ ) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_MINI', '../upload/user/mini/'); //horny panel mini
				define('UPLOAD_DIR_NORMAL', '../upload/user/normal/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/user/original/'); // original
				//velkost
				define ("MAX_SIZE","1000");
				//funkcia na premenu nazvu name obrazku
				function getExtension($str) {
				         $i = strrpos($str,".");
				         if (!$i) { return ""; }
				         $l = strlen($str) - $i;
				         $ext = substr($str,$i+1,$l);
				         return $ext;
				}

				$errors=0;



				$image =$_FILES["image"]["name"];
				$uploadedfile = $_FILES['image']['tmp_name'];


				if ($image) {

				    $filename = stripslashes($_FILES['image']['name']);

				    $extension = getExtension($filename);
				    $extension = strtolower($extension);


					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {

				    	$change='<div class="msgdiv">Obrazok ma zly format.</div> ';
				    	$errors=1;
				    }
				    else {

						$size = filesize($_FILES['image']['tmp_name'])/ 1000;

						if ($size > MAX_SIZE*1024) {
							$change='<div class="msgdiv">Maximalna velkost je ' . MAX_SIZE . '</div>';
							$errors=1;
						}
						$size = $size /1000;

						if($extension=="jpg" || $extension=="jpeg" ) {

							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);
						}
						else if($extension=="png") {

							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}
						else {
							$src = imagecreatefromgif($uploadedfile);
						}

						//$src = imagecreatefromjpg, gif, png, jpeg($uploadedfile); ----- original suboru

						//echo $scr;

						list($height,$width)=getimagesize($uploadedfile);

						//$filenam = UPLOAD_DIR_ORIGINAL . $_FILES['obrazokUloz']['name'];  //original
						//$filenam = UPLOAD_DIR_ORIGINAL . $user_id . '.' . $extension;  //original
						$filenam = UPLOAD_DIR_ORIGINAL . $last_id . '.jpg';  //original



						imagejpeg($src,$filenam,80);

						$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
						$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
						//--------------------------------------------------------------------------------------
				
						//------------------------ nove ------------------
						include 'component/resize-class.php';

						// *** 1) Initialise / load image
						  $resizeObj = new resize($filenam);

						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
						  $resizeObj -> resizeImage(200, 280, 'crop');

						  // *** 3) Save image
						  $resizeObj -> saveImage($normal , 80);


						//===-=-=-=-=-=

						 $resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  $resize -> resizeImage(50, 70, 'crop');

						  // *** 3) Save image
						  $resize -> saveImage($mini , 80);
						//----------------------------------------------------------------------------------


						//----------------------------------------------------------------------------------

						imagedestroy($src);  //original
						imagedestroy($normal);
						imagedestroy($mini);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}


				if ($errors == '0') {

					$nick = str_replace(' ', '', $nick); // meno bez medzier
					//$salt1 = parrent::salt('10');  // ulozime do salt
					//$hh = $heslo . $salt1;
					//$newHeslo = parrent::cryptPassword($hh);  //ulozime do hesla

					$salt1 = $this->salt('10');  // ulozime do salt
					$newHeslo = $this->cryptPassword($heslo . $salt1);  //ulozime do hesla

			/*echo $nick . ' 1</br>';
			echo $meno . ' 1</br>';
			echo $email . ' 1</br>';
			echo $tel . ' 1</br>';
			echo $newHeslo . ' 1</br>';
			echo $salt1 . ' 1</br>';
			//echo $last_id . ' 1</br>';
			echo $datum . ' 1</br>';
			echo $group . ' 1</br>';
			echo $typ . ' 1</br>';

			die();*/

					if ($image) {
						
						# ULOZ
						$uloz = $this->db->query('INSERT IGNORE INTO be_user' . $this->prefix . ' 
												(user_id, user_nick, user_meno, user_email, user_telefon, user_heslo, user_salt, user_avatar, user_reg, group_id, user_typ)  
												VALUES (NULL,
														"' . $this->db->real_escape_string($nick) . '",
														"' . $this->db->real_escape_string($meno) . '",
														"' . $this->db->real_escape_string($email) . '",
														"' . $this->db->real_escape_string($tel) . '",
														"' . $this->db->real_escape_string($newHeslo) . '",
														"' . $this->db->real_escape_string($salt1) . '",
														"' . $this->db->real_escape_string($last_id . '.jpg') . '",
														"' . $this->db->real_escape_string($datum) . '",
														"' . $this->db->real_escape_string($group) . '",
														"' . $this->db->real_escape_string($typ) . '") ');

					}
					else {

						$nula =0;

						# ULOZ
						$uloz = $this->db->query('INSERT IGNORE INTO be_user' . $this->prefix . ' 
												(user_id, user_nick, user_meno, user_email, user_telefon, user_heslo, user_salt, user_avatar, user_reg, group_id, user_typ)  
												VALUES (NULL,
														"' . $this->db->real_escape_string($nick) . '",
														"' . $this->db->real_escape_string($meno) . '",
														"' . $this->db->real_escape_string($email) . '",
														"' . $this->db->real_escape_string($tel) . '",
														"' . $this->db->real_escape_string($newHeslo) . '",
														"' . $this->db->real_escape_string($salt1) . '",
														"' . $this->db->real_escape_string($nula) . '",
														"' . $this->db->real_escape_string($datum) . '",
														"' . $this->db->real_escape_string($group) . '",
														"' . $this->db->real_escape_string($typ) . '") ');

					}

					#ulozime key
					$id = mysql_insert_id();

					//$key = parent::cryptRememberId($id, $nick, $datum);
					$key = $this->cryptRememberId($id, $nick, $datum);

					$uloz1 = $this->db->query('UPDATE be_user' . $this->prefix . ' SET 
													user_key ="' . $this->db->real_escape_string($key) . '" 
												WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');

					//print_r($uloz);
					setcookie('odpoved','2', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();
				}
				else {
					setcookie('odpoved','1', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();
				}
				

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();
			}

		}

		#FORM
		?>

	<h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #278c38; margin-bottom: 5px;">Pridat</h3>
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">Pridat</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nickname</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="prezivka" name="nick">
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Meno a Priezvisko</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Janko Hraška" name="meno">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="hrasko@besom.sk" name="email">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Telefon</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="0902123456" name="tel">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Heslo</label>
				    <div class="col-sm-6">
				    	<input type="password" class="form-control" id="inputText3" placeholder="******" name="heslo">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Avatar</label>
				    <div class="col-sm-6">
					    <input type="file" id="exampleInputFile" name="image">
	    				<p class="help-block">Vyber obrazok</p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Datum</br><small>Podla prav</small></label>
				    <div class="col-sm-6">
				    	<input type="datepicker" class="form-control" id="inputText3" value="<?php echo date('Y-m-d H:i:s'); ?>" name="datum">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Skupina</br><small>Podla prav</small></label>
				    <div class="col-sm-6">
						<select class="form-control" name="group">
				    	<?php
				    	$selA = $this->groupSelect();
				    	foreach ($selA as $id => $posun1) {
				    		foreach ($posun1 as $nazov => $posun2) {
				    			?><option name="<?php echo $id; ?>" value="<?php echo $id; ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
				    		}
				    	}
				    	?>
						</select>
					</div>	
				</div>


				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Typ uctu</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="typ" id="optionsRadios1" value="1">
						    Aktivny
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="typ" id="optionsRadios2" value="2">
						    Zablokovany
						  </label>
						</div>
						<div class="radio">
							<label>
						    	<input type="radio" name="typ" id="optionsRadios1" value="3" checked="checked">
						    Neaktivny&nbsp;<small class="text-danger">Treba aktivovat, emailom</small>
						  	</label>
						</div>

					</div>	
			  	</div>
 				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success btn-sm" name="submit"><i class="glyphicon glyphicon-plus"></i>&nbsp;Vytvorit</button>
					</div>
				</div>	
			</form>
		</div>
	</div>	
		<?php
	}


	public function groupSelect () {

		$query = $this->db->query('SELECT group_id, group_nazov FROM be_group' . $this->prefix . ' ORDER BY group_nazov DESC');
		if ($query->num_rows != FALSE) {
			$pole = array();
			while ($q = $query->fetch_assoc()) {
				$pole[$q['group_id']][$q['group_nazov']][] = $q;
			}
			return $pole;
		}
		else {
			return 0;
		}

	}

	private function getGroup ($cislo) {

		$query = $this->db->query('SELECT group_nazov, group_id FROM be_group' . $this->prefix . ' 
										WHERE group_id ="' . $this->db->real_escape_string(intval($cislo)) . '" ');

		return $query->fetch_array();
	}

	
	public function usersEdit ($id) {

		# POST
		# POST
		if (isset($_POST['submit']) AND isset($_POST['nick']) AND isset($_POST['meno']) AND isset($_POST['email']) AND isset($_POST['tel']) AND isset($_POST['oldPass']) AND isset($_POST['datum']) AND isset($_POST['group']) AND isset($_POST['typ']) /*AND isset($_POST['image'])*/ ) {


			$nick = (isset($_POST['nick'])) ? $_POST['nick'] : '';
			$meno = (isset($_POST['meno'])) ? $_POST['meno'] : '';
			$email = (isset($_POST['email'])) ? $_POST['email'] : '';

			$tel = (isset($_POST['tel'])) ? $_POST['tel'] : '';
			$heslo = (isset($_POST['heslo'])) ? $_POST['heslo'] : '';
			$datum = (isset($_POST['datum'])) ? $_POST['datum'] : '';

			$group = (isset($_POST['group'])) ? $_POST['group'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			$img = (isset($_POST['img'])) ? $_POST['img'] : '';
			$oldPass = (isset($_POST['oldPass'])) ? $_POST['oldPass'] : '';
			$oldSalt = (isset($_POST['oldSalt'])) ? $_POST['oldSalt'] : '';

			/*
			echo $_FILES["image"]['name'] . '<br>';
			*/

			if (!empty($nick) AND !empty($meno) AND !empty($email) AND !empty($tel) AND !empty($oldPass) AND !empty($datum) AND !empty($group) AND !empty($typ) /*AND !empty($_FILES["image"])*/ ) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_MINI', '../upload/user/mini/'); //horny panel mini
				define('UPLOAD_DIR_NORMAL', '../upload/user/normal/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/user/original/'); // original
				//velkost
				define ("MAX_SIZE","1000");
				//funkcia na premenu nazvu name obrazku
				function getExtension($str) {
				         $i = strrpos($str,".");
				         if (!$i) { return ""; }
				         $l = strlen($str) - $i;
				         $ext = substr($str,$i+1,$l);
				         return $ext;
				}

				$errors=0;



				$image =$_FILES["image"]["name"];
				$uploadedfile = $_FILES['image']['tmp_name'];


				if ($image) {

				    $filename = stripslashes($_FILES['image']['name']);

				    $extension = getExtension($filename);
				    $extension = strtolower($extension);


					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {

					$change='<div class="msgdiv">Obrazok ma zly format.</div> ';
					$errors=1;
				    }
				    else {

						$size = filesize($_FILES['image']['tmp_name'])/ 1000;

						if ($size > MAX_SIZE*1024) {
							$change='<div class="msgdiv">Maximalna velkost je ' . MAX_SIZE . '</div>';
							$errors=1;
						}
						$size = $size /1000;

						if($extension=="jpg" || $extension=="jpeg" ) {

							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);
						}
						else if($extension=="png") {

							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}
						else {
							$src = imagecreatefromgif($uploadedfile);
						}

						//$src = imagecreatefromjpg, gif, png, jpeg($uploadedfile); ----- original suboru

						//echo $scr;

						list($height,$width)=getimagesize($uploadedfile);

						//$filenam = UPLOAD_DIR_ORIGINAL . $_FILES['obrazokUloz']['name'];  //original
						//$filenam = UPLOAD_DIR_ORIGINAL . $user_id . '.' . $extension;  //original
						$filenam = UPLOAD_DIR_ORIGINAL . $last_id . '.jpg';  //original



						imagejpeg($src,$filenam,80);

						$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
						$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
						//--------------------------------------------------------------------------------------
				
						//------------------------ nove ------------------
						include 'component/resize-class.php';

						// *** 1) Initialise / load image
						  $resizeObj = new resize($filenam);

						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
						  $resizeObj -> resizeImage(200, 280, 'crop');

						  // *** 3) Save image
						  $resizeObj -> saveImage($normal , 80);


						//===-=-=-=-=-=

						 $resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  $resize -> resizeImage(50, 70, 'crop');

						  // *** 3) Save image
						  $resize -> saveImage($mini , 80);
						//----------------------------------------------------------------------------------


						//----------------------------------------------------------------------------------

						imagedestroy($src);  //original
						imagedestroy($normal);
						imagedestroy($mini);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}


				if ($errors == '0') {

					$nick = str_replace(' ', '', $nick); // meno bez medzier
					//$salt1 = parrent::salt('10');  // ulozime do salt
					//$hh = $heslo . $salt1;
					//$newHeslo = parrent::cryptPassword($hh);  //ulozime do hesla

					//$salt1 = $this->salt('10');  // ulozime do salt
					//$newHeslo = $this->cryptPassword($heslo . $salt1);  //ulozime do hesla

					if (empty($heslo)) {
						# bude stare
						$newHeslo = $oldPass;
						$salt1 = $oldSalt;
					}
					else {
						$salt1 = $this->salt('10');  // ulozime do salt
						$newHeslo = $this->cryptPassword($heslo . $salt1);  //ulozime do hesla
					}

					if ($image) {

						# VYMAZEME OBRAZKY
						unlink('../' . IMAGE_USER_MINI . $img);
						unlink('../' . IMAGE_USER_NORMAL . $img);
						unlink('../' . IMAGE_USER_ORIGINAL . $img);
						
						# ULOZ
						$uloz = $this->db->query('UPDATE be_user' . $this->prefix . ' SET 
														user_nick ="' . $this->db->real_escape_string($nick) . '",
														user_meno ="' . $this->db->real_escape_string($meno) . '",
														user_email ="' . $this->db->real_escape_string($email) . '",
														user_telefon ="' . $this->db->real_escape_string($tel) . '",
														user_heslo ="' . $this->db->real_escape_string($newHeslo) . '",
														user_salt ="' . $this->db->real_escape_string($salt1) . '",
														user_avatar ="' . $this->db->real_escape_string($last_id . '.jpg') . '",
														user_reg ="' . $this->db->real_escape_string($datum) . '",
														group_id ="' . $this->db->real_escape_string($group) . '",
														user_typ ="' . $this->db->real_escape_string($typ) . '"
												WHERE user_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

					}
					else {

						$nula =0;
						if (!empty($img)) { // ak je obrazok nejaky, tak ho nechame
							$nula = $img;
						}

						# ULOZ
						$uloz = $this->db->query('UPDATE be_user' . $this->prefix . ' SET 
														user_nick ="' . $this->db->real_escape_string($nick) . '",
														user_meno ="' . $this->db->real_escape_string($meno) . '",
														user_email ="' . $this->db->real_escape_string($email) . '",
														user_telefon ="' . $this->db->real_escape_string($tel) . '",
														user_heslo ="' . $this->db->real_escape_string($newHeslo) . '",
														user_salt ="' . $this->db->real_escape_string($salt1) . '",
														user_avatar ="' . $this->db->real_escape_string($nula) . '",
														user_reg ="' . $this->db->real_escape_string($datum) . '",
														group_id ="' . $this->db->real_escape_string($group) . '",
														user_typ ="' . $this->db->real_escape_string($typ) . '"
												WHERE user_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

					}

					#ulozime key

					//$key = parent::cryptRememberId($id, $nick, $datum);
					$key = $this->cryptRememberId($id, $nick, $datum);

					$uloz1 = $this->db->query('UPDATE be_user' . $this->prefix . ' SET 
													user_key ="' . $this->db->real_escape_string($key) . '" 
												WHERE user_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

					//print_r($uloz);
					setcookie('odpoved','2', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();
				}
				else {
					setcookie('odpoved','1', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();
				}
				

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();
			}

		}
		# END POST DATA

		$over = $this->db->query('SELECT * FROM be_user' . $this->prefix . ' 
									WHERE user_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
<h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #0256c4; margin-bottom: 5px;">Zmenit</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Zmenit</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nickname</label>
				    <div class="col-sm-6">
					<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['user_nick']); ?>" name="nick">
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Meno a Priezvisko</label>
				    <div class="col-sm-6">
					<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['user_meno']); ?>" name="meno">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
				    <div class="col-sm-6">
					<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['user_email']); ?>" name="email">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Telefon</label>
				    <div class="col-sm-6">
					<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['user_telefon']); ?>" name="tel">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Zmenit heslo</label>
				    <div class="col-sm-6">
						<input type="password" class="form-control" id="inputText3" placeholder="******" name="heslo">
						<span class="text-danger">Ak nebude zadane zostane predosle heslo!!!</span>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Avatar</label>
				    <div class="col-sm-6">
					    <input type="file" id="exampleInputFile" name="image">
					    <?php
					    if (file_exists(ADRESA . PRIECINOK . IMAGE_USER_MINI . htmlspecialchars($zobraz['user_avatar']))) {
							?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_USER_MINI . htmlspecialchars($zobraz['user_avatar']); ?>" alt="<?php echo htmlspecialchars($zobraz['user_nick']); ?>" title="<?php echo htmlspecialchars($zobraz['user_nick']); ?>" class="img-rounded" width="70"><?php
					    }
					    ?>
						<p class="help-block">Ak vyberiete tak sa predosli zmeni</p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Datum</br><small>Podla prav</small></label>
				    <div class="col-sm-6">
					<input type="datepicker" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['user_reg']); ?>" name="datum">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Skupina</br><small>Podla prav</small></label>
				    <div class="col-sm-6">
						<select class="form-control" name="group">
					<?php
					$selA = $this->groupSelect();
					foreach ($selA as $id => $posun1) {
						foreach ($posun1 as $nazov => $posun2) {
							
							if ($id == $zobraz['group_id']) {
								?><option name="<?php echo $id; ?>" selected="selected" value="<?php echo $id; ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
							}
							else {
							 	?><option name="<?php echo $id; ?>" value="<?php echo $id; ?>"><?php echo htmlspecialchars($nazov); ?></option><?php
							}	
						}
					}
					?>
					      	</select>
					</div>	
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Typ uctu</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
							<input type="radio" name="typ" id="optionsRadios1" <?php if ($zobraz['user_typ'] == '1') { echo 'checked="checked"'; } else {  } ?> value="1" >
						    Aktivny
							</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="typ" id="optionsRadios2" <?php if ($zobraz['user_typ'] == '2') { echo 'checked="checked"'; } else {  } ?> value="2" >
						    Zablokovany
						  </label>
						</div>
						<div class="radio">
							<label>
							<input type="radio" name="typ" id="optionsRadios3" <?php if ($zobraz['user_typ'] == '3') { echo 'checked="checked"'; } else {  } ?> value="3">
						    Neaktivny&nbsp;<small class="text-danger">Treba aktivovat, emailom</small>
							</label>
						</div>

					</div>	
				</div>
				<input type="hidden" name="img" value="<?php echo htmlspecialchars($zobraz['user_avatar']); ?>">
				<input type="hidden" name="oldPass" value="<?php echo htmlspecialchars($zobraz['user_heslo']); ?>">
				<input type="hidden" name="oldSalt" value="<?php echo htmlspecialchars($zobraz['user_salt']); ?>">
				<div class="form-group">
				      	<div class="col-sm-offset-2 col-sm-10">
				      		<button type="submit" class="btn btn-primary btn-sm" name="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
				      	</div>
				</div>	
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

	public function usersView ($id) {
		# ADMIN ZOBRAZENIE PROFILU

		$prof = $this->db->query('SELECT * FROM be_user' . $this->prefix . ' 
									WHERE user_id ="' . $this->db->real_escape_string(intval($id)) . '" LIMIT 1');
		if ($prof->num_rows != FALSE) {
			#OK JE
			$profil = $prof->fetch_assoc();

			/*echo '<pre>';
			print_r($profil);
			echo '</pre>';*/
			
			?>
<h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #a37e05; margin-bottom: 5px;">Profil</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Profil</h3>
		</div>

		<div class="panel-body">
		<table>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Nickname</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_nick']); ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Meno a Priezvisko</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_meno']); ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_email']); ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Telefon</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_telefon']); ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Datum Registracie</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_reg']); ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Avatar</label>
				
				<p class="form-control-static">
					
					<?php

					if (file_exists(ADRESA . PRIECINOK . IMAGE_USER_MINI . htmlspecialchars($profil['user_avatar']))) {
						?>
						<img src="<?php echo ADRESA . PRIECINOK . IMAGE_USER_MINI . htmlspecialchars($profil['user_avatar']); ?>" alt="<?php echo htmlspecialchars($profil['user_nick']); ?>" title="<?php echo htmlspecialchars($profil['user_nick']); ?>" class="img-rounded" width="100">
						<?php
					}
					else {
						# NENI IMG AVATAR
						?>
						<img src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($profil['user_nick']); ?>" title="<?php echo htmlspecialchars($profil['user_nick']); ?>" class="img-rounded" width="100">
						<?php
					}
				    ?>
				</p>	
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Stav uctu</label>
				
				<p class="form-control-static">
						<?php 
						$publikA = array('1' => 'aktivny',
								 '2' => 'Zablokovany',
								 '3' => 'Neaktivovany' );
						$colorA = array('1' => 'success',
										 '2' => 'danger',
										 '3' => 'warning');
						$ikonA = array('1' => 'glyphicon glyphicon-ok-circle',
										'2' => 'glyphicon glyphicon-remove-circle',
										'3' => 'glyphicon glyphicon-warning-sign');

						$colA = array('1' => 'green',
										'2' => 'red',
										'3' => '#c4be16');

						echo htmlspecialchars($publikA[$profil['user_typ']]);

						?>
				</p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Skupina</label>
				
				<p class="form-control-static"><?php $aa = $this->getGroup($profil['group_id']); echo $aa['group_nazov']; ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Datum Registracie</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_reg']); ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Heslo</label>
				
				<p class="form-control-static"><?php if (strlen($profil['user_heslo']) > 80) { echo substr($profil['user_heslo'], 0, 80) . '' . substr($profil['user_heslo'], 80); } else { echo htmlspecialchars($profil['user_heslo']); } ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Salt</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_salt']); ?></p>
			</tr>

			<tr>
			    <label for="inputEmail3" class="col-sm-3 control-label">Key</label>
				
				<p class="form-control-static"><?php echo htmlspecialchars($profil['user_key']); ?></p>
			</tr>


	    </table>
	</div>	
	</div>
	      		<?php
	      	}
	      	else {
	      		# NENI UZOVATEL
	      		echo $this->lg[$this->flag]['userViewNo'];
	      	}



	}

	public function setImage ($cesta) {

		if (file_exists($cesta)) {
			unlink($cesta);
		}

	} 

	public function usersDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['ikona']) AND isset($id)) {


			$this->setImage('../' . IMAGE_USER_MINI . $_POST['ikona']);
			$this->setImage('../' . IMAGE_USER_NORMAL . $_POST['ikona']);
			$this->setImage('../' . IMAGE_USER_ORIGINAL . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_user' . $this->prefix . ' 
										WHERE user_id ="' . $this->db->real_escape_string(intval($id)) . '" ');


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST



		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT *, u.group_id, g.group_id FROM be_user' . $this->prefix . ' u 
											JOIN be_group' . $this->prefix . ' g ON u.group_id = g.group_id 
									WHERE user_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

						$publikA = array('1' => 'aktivny',
								 '2' => 'Zablokovany',
								 '3' => 'Neaktivovany' );

						$colorA = array('1' => 'success',
										 '2' => 'danger',
										 '3' => 'warning');

						$ikonA = array('1' => 'glyphicon glyphicon-ok-circle',
										'2' => 'glyphicon glyphicon-remove-circle',
										'3' => 'glyphicon glyphicon-warning-sign');

						$colA = array('1' => 'green',
										'2' => 'red',
										'3' => '#c4be16');


			?>
<h3 style="margin: 0px; padding: 10px 0px 2px 5px;  color: #454545; margin-bottom: 5px;">Profil</h3>
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">Profil</h3>
		</div>

		<div class="panel-body">
				
			<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nickname</label>
				    <div class="col-sm-6">
					<p class="form-control-static"><?php echo htmlspecialchars($zobraz['user_nick']); ?></p>
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Meno a Priezvisko</label>
				    <div class="col-sm-6">
					<p class="form-control-static"><?php echo htmlspecialchars($zobraz['user_meno']); ?></p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
				    <div class="col-sm-6">
					<p class="form-control-static"><?php echo htmlspecialchars($zobraz['user_email']); ?></p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Telefon</label>
				    <div class="col-sm-6">
					<p class="form-control-static"><?php echo htmlspecialchars($zobraz['user_telefon']); ?></p>
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Avatar</label>
				    <div class="col-sm-6">
						<p class="form-control-static">
					    <?php
					    if (file_exists(ADRESA . PRIECINOK . IMAGE_USER_MINI . htmlspecialchars($zobraz['user_avatar']))) {
							?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_USER_MINI . htmlspecialchars($zobraz['user_avatar']); ?>" alt="<?php echo htmlspecialchars($zobraz['user_nick']); ?>" title="<?php echo htmlspecialchars($zobraz['user_nick']); ?>" class="img-rounded" width="70"><?php
					    }
					    ?>
					    </p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
				    <div class="col-sm-6">
						<p class="form-control-static"><?php echo htmlspecialchars($zobraz['user_reg']); ?></p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Skupina</label>
				    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars($zobraz['group_nazov']); ?></p>
					</div>	
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Typ uctu</label>

				    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars($publikA[$zobraz['user_typ']]); ?></p>
					</div>	
				</div>

				</fieldset>

				<input type="hidden" name="ikona" value="<?php echo htmlspecialchars($zobraz['user_avatar']); ?>">

				<div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
				    </div>
				</div>	

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


	/*
			********
			**********************
									PREVZATE Z lib folder    lib.user.php
			**********************
			********
			*/
				private function salt ($chars = 10) {

				        $characters = array("A","B","C","D","E","F","G","H","J","K","L","M",
				                            "N","P","Q","R","S","T","U","V","W","X","Y","Z",
				                            "1","2","3","4","5","6","7","8","9",
				                            "a","b","c","d","e","f","g","h","j","k","l","m",
				                            "n","p","q","r","s","t","u","v","w","x","y","z"
				                      );
				        $keys = array();

				        while(count($keys) < $chars) {

				            $x = mt_rand(0, count($characters)-1);
				            if(!in_array($x, $keys)) {
				               $keys[] = $x;
				            }
				        }
				        $salt = '';
				        foreach($keys as $key){
				           $salt .= $characters[$key];
				        }
				        return $salt;

					}

					private function cryptPassword ($pass) {

						$password = hash("SHA512", $pass);

						return $password;

					}

					private function cryptRememberId ($id, $user, $date) {
						# LOGIN ON COOKIES

						$RemDate = hash("SHA512", $date);
						$RemId = hash("SHA512", $id);
						$RemUser = hash("SHA512", $user);
						$key = $this->cryptPassword($RemId . $RemUser . $RemDate);

						return $key;
					}
					/*
					END prevzate
					*/


}


//$language = new language($this->db, $this->prefix, $this->lang);
$users = new users($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>