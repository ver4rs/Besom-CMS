<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class album extends url {

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
		echo $this->lg[$this->flag]['nadpisText'];
	}


	public function albumTab () {

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

						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&action1=new'; ?>"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridat</a>

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
								<input type="submit" name="submit" value="Hladaj" class="btn-sm">

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
										FROM be_album' . $this->prefix . ' ';
		if (isset($_GET['search']) AND $_GET['search'] != FALSE AND $_GET['search'] != '') {
			
			#NAZOV
			$quer .= ' WHERE album_nazov LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR album_url LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR album_popis LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR album_rodic LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR album_visit LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR album_stav LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

		}

		#FILTERS
		// VIEWS
		if (isset($_GET['nazov'])AND $_GET['nazov'] != FALSE AND ($_GET['nazov'] == 'ASC' OR $_GET['nazov'] == 'DESC')) {
			$quer .= ' ORDER BY album_nazov ' . $_GET['nazov'] . ' ';
		}
		elseif (isset($_GET['url'])AND $_GET['url'] != FALSE AND ($_GET['url'] == 'ASC' OR $_GET['url'] == 'DESC')) {
			$quer .= ' ORDER BY album_url ' . $_GET['url'] . ' ';
		}
		elseif (isset($_GET['popis'])AND $_GET['popis'] != FALSE AND ($_GET['popis'] == 'ASC' OR $_GET['popis'] == 'DESC')) {
			$quer .= ' ORDER BY album_popis ' . $_GET['popis'] . ' ';
		}
		elseif (isset($_GET['rodic'])AND $_GET['rodic'] != FALSE AND ($_GET['rodic'] == 'ASC' OR $_GET['rodic'] == 'DESC')) {
			$quer .= ' ORDER BY album_rodic ' . $_GET['rodic'] . ' ';
		}
		elseif (isset($_GET['visit'])AND $_GET['visit'] != FALSE AND ($_GET['visit'] == 'ASC' OR $_GET['visit'] == 'DESC')) {
			$quer .= ' ORDER BY album_visit ' . $_GET['visit'] . ' ';
		}
		elseif (isset($_GET['stav'])AND $_GET['stav'] != FALSE AND ($_GET['stav'] == 'ASC' OR $_GET['stav'] == 'DESC')) {
			$quer .= ' ORDER BY album_stav ' . $_GET['stav'] . ' ';
		}
		else {
			$quer .= ' ORDER BY album_nazov DESC  ';
		}

		$quer .= ' LIMIT ' . $start . ', ' . $limit . ' ';

		$query = $this->db->query($quer);
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$por = $por + $start;

			$publikA = array('1' => 'Aktivny',
							 '2' => 'Zablokovany' );
			$colorA = array('1' => 'success',
							 '2' => 'danger',
							 '3' => 'warning');

			$ikonA = array('1' => 'glyphicon glyphicon-ok-circle',
							'2' => 'glyphicon glyphicon-remove-circle');


			$colA = array('1' => 'green',
							'2' => 'red');
			?>
			<table class="table table-hover">
				<tr>
					<th>#</th>
					<th>Nazov 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&nazov=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&nazov=ASC'; ?>">
							<
						</a>
					</th>
					<th>Url 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&url=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&url=ASC'; ?>">
							<
						</a>
					</th>
					<th>Popis 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&popis=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&popis=ASC'; ?>">
							<
						</a>
					</th>
					<th>Rodic 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&rodic=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&rodic=ASC'; ?>">
							<
						</a>
					</th>
					<th>Pocet 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&visit=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&visit=ASC'; ?>">
							<
						</a>
					</th>
					<th>Stav 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=ASC'; ?>">
							<
						</a> 
					</th>
					
					<th><i class="glyphicon glyphicon-star"> &nbsp; </i> &nbsp;........ </th>
				</tr>
			<?php
			while ($album = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php  /*if(!isset($menuP[$album['menu_id']]['menu_nazov'])) { echo htmlspecialchars($colorA['3']); } else {*/ echo htmlspecialchars($colorA[$album['album_stav']]); /*}*/ ?>">
					<td><?php echo htmlspecialchars($por); ?></td>
					
					<td><?php echo htmlspecialchars($album['album_nazov']); ?></td>
					<td><?php echo htmlspecialchars($album['album_url']); ?></td>
					<td><?php echo htmlspecialchars($album['album_popis']); ?></td>
					<td><?php echo htmlspecialchars($album['album_rodic']); ?></td>
					<td><?php echo htmlspecialchars($album['album_visit']); ?></td>
					<!-- <td>
						<?php //echo htmlspecialchars($album['menu_nazov']);

						//echo $menuP[$album['menu_id']]['menu_nazov'];
						/*if (isset($menuP[$album['menu_id']]['menu_nazov'])) {
							echo htmlspecialchars($menuP[$album['menu_id']]['menu_nazov']);
						}
						else {
							echo htmlspecialchars_decode('<b class="text-danger">Uncategory</b>');
						}*/

						?>
					</td>  -->
					<td><i class="<?php echo $ikonA[$album['album_stav']]; ?>" style="color: <?php echo $colA[$album['album_stav']]; ?>"></i>&nbsp;<?php echo htmlspecialchars($publikA[$album['album_stav']]); ?></td>
					<td>
							<?php
								if ($album['album_stav'] == '1') {
									# active
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&deactive=' . $album['album_id']);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
								}
								else {
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&active=' . $album['album_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

							?>
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($album['album_id']); ?>"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($album['album_id']); ?>"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a>
					</td>
				</tr>
				<?php

			}
			?></table><?php 

			# STRANKAOVANIE
			//$this->strankovanie($limit, $range, $url_zacni, $url_menu);
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


	public function albumActive ($id) {
		$query = $this->db->query('SELECT album_stav FROM be_album' . $this->prefix . ' 
							WHERE album_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_album' . $this->prefix . ' SET 
										album_stav =1 WHERE album_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function albumDeactive ($id) {
		$query = $this->db->query('SELECT album_stav FROM be_album' . $this->prefix . ' 
							WHERE album_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_album' . $this->prefix . ' SET 
										album_stav =2 WHERE album_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function albumNew () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['popis']) /*AND isset($_POST['rodic'])*/ AND isset($_POST['stav']) /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$rodic = (isset($_POST['rodic'])) ? $_POST['rodic'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';

			$sirka = (isset($_POST['sirka'])) ? $_POST['sirka'] : '';

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

			if (!empty($nazov) AND !empty($popis) /*AND !empty($rodic)*/ AND !empty($stav) /*AND !empty($publik)*/ /*AND !empty($zobrazPopis) AND !empty($zobrazNazov) AND !empty($slider) AND !empty($popisSlider) AND !empty($koment)*/ /*AND !empty($_FILES["image"])*/ ) {
				


				#-----------------------------------------------------------------------------------------------
				/*error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_SLIDER', '../upload/articles/slider/'); //horny panel mini
				define('UPLOAD_DIR_MINI', '../upload/articles/mini/'); //horny panel mini
				define('UPLOAD_DIR_NORMAL', '../upload/articles/normal/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/articles/original/'); // original
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
						$slider = UPLOAD_DIR_SLIDER . $last_id . '.jpg';   //mini 
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


						  //===-=-=-=-=-=

						  $resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  $resize -> resizeImage(300, 210, 'crop');

						  // *** 3) Save image
						  $resize -> saveImage($slider , 20);
						//----------------------------------------------------------------------------------

						imagedestroy($src);  //original
						imagedestroy($normal);
						imagedestroy($mini);
						imagedestroy($slider);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}*/

				# URL
				$url = parent::seo_url($nazov);

				#VISIT
				if (!isset($visit)) {
					$visit = 0;
				}

				# RODIC ZATIAL NETREBA
				$rodic =0;

				$popis = str_replace('\"', '', $popis);
							
				# ULOZ
				$uloz = $this->db->query('INSERT IGNORE INTO be_album' . $this->prefix . ' 
												(album_id, album_nazov, album_url, album_popis, album_rodic, album_visit, album_stav)  
												VALUES (NULL,
														"' . $this->db->real_escape_string($nazov) . '",
														"' . $this->db->real_escape_string($url) . '",
														"' . $this->db->real_escape_string($popis) . '",
														"' . $this->db->real_escape_string($rodic) . '",
														"' . $this->db->real_escape_string($visit) . '",
														"' . $this->db->real_escape_string($stav) . '") ');

				//print_r($uloz);
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
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&action1=' . $_GET['action1']);
				exit();
			}

		}	

		##########################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##########################################################
		##########################################################
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
				    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Nazov" name="nazov">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
				    <div class="col-sm-8">
				    <!-- <textarea class="form-control" name="text"></textarea> -->
					    <?php   
			                // Create Editor instance and use Text property to load content into the RTE.  
			                $rte=new RichTextEditor();   
			                $rte->Text= $zobraz['text_cely']; 
			                // Set a unique ID to Editor   
			                $rte->ID="popis";    
			                $rte->MvcInit();   
			                // Render Editor 
			                echo $rte->GetString(); 
			            ?>  
					    
				    </div>
				</div>


				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="stav" id="optionsRadios1" value="1" checked="checked">
						    Aktivne
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="stav" id="optionsRadios2" value="2">
						    Zablokovane
						  </label>
						</div>
					</div>	
			  	</div>

 				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success btn-sm" name="submit"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</button>
					</div>
				</div>	
			</form>
		</div>
	</div>
		<?php
	}




	public function albumEdit ($id) {

		# POST
		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['popis']) AND isset($_POST['visit']) /*AND isset($_POST['rodic'])*/ AND isset($_POST['stav']) /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$rodic = (isset($_POST['rodic'])) ? $_POST['rodic'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			$visit = (isset($_POST['visit'])) ? $_POST['visit'] : '';

			$sirka = (isset($_POST['sirka'])) ? $_POST['sirka'] : '';

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

			if (!empty($nazov) AND !empty($popis) AND !empty($visit) /*AND !empty($rodic)*/ AND !empty($stav) /*AND !empty($publik)*/ /*AND !empty($zobrazPopis) AND !empty($zobrazNazov) AND !empty($slider) AND !empty($popisSlider) AND !empty($koment)*/ /*AND !empty($_FILES["image"])*/ ) {
				


				#-----------------------------------------------------------------------------------------------
				/*error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_SLIDER', '../upload/articles/slider/'); //horny panel mini
				define('UPLOAD_DIR_MINI', '../upload/articles/mini/'); //horny panel mini
				define('UPLOAD_DIR_NORMAL', '../upload/articles/normal/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/articles/original/'); // original
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
						$slider = UPLOAD_DIR_SLIDER . $last_id . '.jpg';   //mini 
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


						  //===-=-=-=-=-=

						  $resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  $resize -> resizeImage(300, 210, 'crop');

						  // *** 3) Save image
						  $resize -> saveImage($slider , 20);
						//----------------------------------------------------------------------------------

						imagedestroy($src);  //original
						imagedestroy($normal);
						imagedestroy($mini);
						imagedestroy($slider);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}*/

				# URL
				$url = parent::seo_url($nazov);

				#VISIT
				if (!isset($visit)) {
					$visit = 0;
				}

				# RODIC ZATIAL NETREBA
				$rodic =0;

				$popis = str_replace('\"', '', $popis);
							
				# ULOZ
				$uloz = $this->db->query('UPDATE be_album' . $this->prefix . ' SET 
												album_nazov ="' . $this->db->real_escape_string($nazov) . '",
												album_url ="' . $this->db->real_escape_string($url) . '",
												album_popis ="' . $this->db->real_escape_string($popis) . '",
												album_rodic ="' . $this->db->real_escape_string($rodic) . '",
												album_visit ="' . $this->db->real_escape_string($visit) . '",
												album_stav ="' . $this->db->real_escape_string($stav) . '"
											WHERE album_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

				//print_r($uloz);
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
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&action1=' . $_GET['action1']);
				exit();
			}

		}	

		##########################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##########################################################
		##########################################################
		#FORM


		$over = $this->db->query('SELECT * FROM be_album' . $this->prefix . ' 
									WHERE album_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; Zmenit <?php echo htmlspecialchars($this->lg[$this->flag]['nadpis']); ?></h3>
				</div>
				<div class="panel-body">
						
				</div>
				
				<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-4">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['album_nazov']); ?>" name="nazov">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-8">
					    <!-- <textarea class="form-control" name="text"></textarea> -->
						    <?php   
				                // Create Editor instance and use Text property to load content into the RTE.  
				                $rte=new RichTextEditor();   
				                $rte->Text= $zobraz['album_popis']; 
				                // Set a unique ID to Editor   
				                $rte->ID="popis";    
				                $rte->MvcInit();   
				                // Render Editor 
				                echo $rte->GetString(); 
				            ?>  
						    
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Pocet prezreti</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['album_visit']); ?>" name="visit">
					    </div>
					</div>


					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" <?php if($zobraz['album_stav'] == '1') { echo 'checked="checked"'; } ?> value="1" >
							    Aktivne
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" <?php if($zobraz['album_stav'] == '2') { echo 'checked="checked"'; } ?> value="2">
							    Zablokovane
							  </label>
							</div>
						</div>	
				  	</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-info btn-sm" name="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
						</div>
					</div>
				</form>
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

	public function albumDelete ($id) {

		# POST
		if (isset($_POST['submit']) /*AND isset($_POST['ikona'])*/ AND isset($id)) {


			//unlink('../' . IMAGE_ARTICLES_SLIDER . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_album' . $this->prefix . ' 
										WHERE album_id ="' . $this->db->real_escape_string($id) . '" ');


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST

		##########################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##########################################################


		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_album' . $this->prefix . ' 
									WHERE album_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
			<div class="panel panel-danger">
				<div class="panel-heading">
					<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; Vymazat <?php echo htmlspecialchars($this->lg[$this->flag]['nadpis']); ?></h3>
				</div>
				<div class="panel-body">
						
				</div>

				<form class="form-horizontal" role="form" method="post" action="">
				<fieldset disabled>
				
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-4">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['album_nazov']); ?>" name="nazov">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars_decode($zobraz['album_popis']); ?></p> 
					    <?php   
			                // Create Editor instance and use Text property to load content into the RTE.  
			                /*$rte=new RichTextEditor();   
			                $rte->Text= $zobraz['text_cely']; 
			                // Set a unique ID to Editor   
			                $rte->ID="text";    
			                $rte->MvcInit();   
			                // Render Editor 
			                echo $rte->GetString();*/  
			            ?>   
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Pocet prezreti</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['album_visit']); ?>" name="visit">
					    </div>
					</div>



					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="1" <?php if ($zobraz['album_stav'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="2" <?php if ($zobraz['album_stav'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

				</fieldset>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>

				</form>
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


}


//$language = new language($this->db, $this->prefix, $this->lang);
$album = new album($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>