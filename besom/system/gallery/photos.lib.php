<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class photos extends url {

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
		echo $this->lg[$this->flag]['nadpisComment'];
	}


	public function photosTab () {

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

		# POLE UZIVATELOV
		/*$uziv = $this->db->query('SELECT user_id, user_nick FROM be_user' . $this->prefix . ' ');
		if ($uziv->num_rows != FALSE) {
			$uzivA = array();
			
			$a = 1;
			$uzivA[0][1] = ' ';
			while($row = $uziv->fetch_array()) {
			    $uzivA[$a] = $row;
			    $a +=1;
			}

		}*/
		//echo $uzivA[1][1]; // prve sa zvisuje a druhe zostava 1

		# ARRAY MENU
		$albumA = array();
		$albumP = $this->db->query('SELECT album_id, album_nazov FROM be_album' . $this->prefix . ' 
									ORDER BY album_id ASC ');
		if ($albumP->num_rows != FALSE) {

			while ($pol = $albumP->fetch_assoc()) {
				$albumA[$pol['album_id']] = $pol;

			}

		}
		/*foreach ($menuP as $key => $value) {
			$menuTlac = array_push($menuTlac2, )
		}*/
		/*echo '<pre>';
		print_r($menuP);
		echo '</pre>';*/

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
										FROM be_photos' . $this->prefix . ' ';
		if (isset($_GET['search']) AND $_GET['search'] != FALSE AND $_GET['search'] != '') {
			
			#NAZOV
			$quer .= ' WHERE photos_id LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR photos_nazov LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR photos_url LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR photos_popis LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR album_id LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR photos_stav LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR photos_visit LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

		}

		#FILTERS
		// VIEWS
		if (isset($_GET['nazov'])AND $_GET['nazov'] != FALSE AND ($_GET['nazov'] == 'ASC' OR $_GET['nazov'] == 'DESC')) {
			$quer .= ' ORDER BY photos_nazov ' . $_GET['nazov'] . ' ';
		}
		elseif (isset($_GET['url'])AND $_GET['url'] != FALSE AND ($_GET['url'] == 'ASC' OR $_GET['url'] == 'DESC')) {
			$quer .= ' ORDER BY photos_url ' . $_GET['url'] . ' ';
		}
		elseif (isset($_GET['popis'])AND $_GET['popis'] != FALSE AND ($_GET['popis'] == 'ASC' OR $_GET['popis'] == 'DESC')) {
			$quer .= ' ORDER BY photos_popis ' . $_GET['popis'] . ' ';
		}
		elseif (isset($_GET['album'])AND $_GET['album'] != FALSE AND ($_GET['album'] == 'ASC' OR $_GET['album'] == 'DESC')) {
			$quer .= ' ORDER BY album_id ' . $_GET['album'] . ' ';
		}
		elseif (isset($_GET['stav'])AND $_GET['stav'] != FALSE AND ($_GET['stav'] == 'ASC' OR $_GET['stav'] == 'DESC')) {
			$quer .= ' ORDER BY photos_stav ' . $_GET['stav'] . ' ';
		}
		elseif (isset($_GET['visit'])AND $_GET['visit'] != FALSE AND ($_GET['visit'] == 'ASC' OR $_GET['visit'] == 'DESC')) {
			$quer .= ' ORDER BY photos_visit ' . $_GET['visit'] . ' ';
		}
		else {
			$quer .= ' ORDER BY photos_id DESC  ';
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
							 '4' => 'warning');
			$colA = array('1' => 'green',
							 '2' => 'red');
			$iconA = array('1' => 'glyphicon glyphicon-ok-circle',
							 '2' => 'glyphicon glyphicon-remove-circle');
			?>
			<table class="table table-hover">
				<tr>
					<th>#</th>
					<th>Obrazok</th>
					<th>Nadpis
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
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&popis=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&popis=ASC'; ?>">
							<
						</a>
					</th>

					<th>Album 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&album=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&album=ASC'; ?>">
							<
						</a>
					</th>

					<th>Pocet 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&visit=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&visit=ASC'; ?>">
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

					<th><i class="glyphicon glyphicon-star"> &nbsp; </i> &nbsp;......... </th>
				</tr>
			<?php
			while ($photos = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php if(!isset($albumA[$photos['album_id']])) { echo htmlspecialchars($colorA['4']); } else { echo htmlspecialchars($colorA[$photos['photos_stav']]); } ?>">

					<td><?php echo htmlspecialchars($por); ?></td>
					
					<td>
						<img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . GALLERY_CESTA_MINI . htmlspecialchars($photos['photos_img']); ?>" alt="<?php echo htmlspecialchars($photos['photos_nazov']); ?>" title="<?php echo htmlspecialchars($photos['photos_nazov']); ?>">
					</td>

					<td><?php echo htmlspecialchars($photos['photos_nazov']); ?></td>
					<td><?php echo htmlspecialchars($photos['photos_url']); ?></td>
					<td><?php echo htmlspecialchars($photos['photos_popis']); ?></td>

					<td><?php 
							//echo htmlspecialchars($photos['text_nazov']); 
						if (isset($albumA[$photos['album_id']]['album_nazov'])) {
							echo htmlspecialchars($albumA[$photos['album_id']]['album_nazov']);
						}
						else {
							echo htmlspecialchars_decode('<b class="text-danger">Uncategoryzed</b>');
						}
						?>
					</td>

					<td><?php echo htmlspecialchars($photos['photos_visit']); ?></td>
					<td><?php echo '<i class="' . $iconA[$photos['photos_stav']] . '" style="color: ' . $colA[$photos['photos_stav']] . '"></i>&nbsp;' . htmlspecialchars($publikA[$photos['photos_stav']]); ?></td>
					<td>
							<?php
								if ($photos['photos_stav'] == '1') {
									# active
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&deactive=' . $photos['photos_id']);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
								}
								else {
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&active=' . $photos['photos_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

								?>
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($photos['photos_id']); ?>"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($photos['photos_id']); ?>"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a></td>
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


	public function photosActive ($id) {
		$query = $this->db->query('SELECT photos_stav FROM be_photos' . $this->prefix . ' 
							WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_photos' . $this->prefix . ' SET 
										photos_stav =1 WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

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

	public function photosDeactive ($id) {
		$query = $this->db->query('SELECT photos_stav FROM be_photos' . $this->prefix . ' 
							WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_photos' . $this->prefix . ' SET 
										photos_stav =2 WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');

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


	public function photosNew () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov'])/* AND isset($_POST['popis']) AND isset($_POST['stav']) AND isset($_POST['album']) AND isset($_POST['stav'])*/ /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$album = (isset($_POST['album'])) ? $_POST['album'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			
			/*echo $_FILES["image"]['name'] . '<br>';
			
			echo $nazov . ' 1</br>';
			echo $popis . ' 2</br>';
			echo $album . ' 3</br>';
			echo $stav . ' 4</br>';
			die();*/

			if (!empty($nazov) AND !empty($stav) AND !empty($popis) AND !empty($album) AND !empty($_FILES["image"]) ) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_MINI', '../upload/gallery/mini/'); //horny panel mini
				define('UPLOAD_DIR_ORIGINAL', '../upload/gallery/original/'); // original
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

						//$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
						$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
						//$slider = UPLOAD_DIR_SLIDER . $last_id . '.jpg';   //mini 
						//--------------------------------------------------------------------------------------
				
						//------------------------ nove ------------------
						include 'component/resize-class.php';

						// *** 1) Initialise / load image
						  //$resizeObj = new resize($filenam);

						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
						  //$resizeObj -> resizeImage(200, 280, 'crop');

						  // *** 3) Save image
						  //$resizeObj -> saveImage($normal , 80);


						//===-=-=-=-=-=

						 $resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  $resize -> resizeImage(250, 250, 'crop');

						  // *** 3) Save image
						  $resize -> saveImage($mini , 70);
						//----------------------------------------------------------------------------------


						  //===-=-=-=-=-=

						  //$resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  //$resize -> resizeImage(300, 210, 'crop');

						  // *** 3) Save image
						  //$resize -> saveImage($slider , 20);
						//----------------------------------------------------------------------------------

						imagedestroy($src);  //original
						imagedestroy($normal);
						imagedestroy($mini);
						//imagedestroy($slider);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}


				if ($errors == '0') {

					# PRAVA
						/*echo $nazov . ' 1</br>';
			echo $popis . ' 2</br>';
			echo $text . ' 3</br>';
			echo $menu . ' 4</br>';
			echo $tags . ' 5</br>';
			echo parent::seo_url($nazov) . ' 6</br>';
			echo $last_id . ' 7</br>';
			echo $_POST['date'] . ' 8</br>';
			echo $autor . ' 9</br>';
			echo $publik . ' 10</br>';
			echo $zobrazPopis . ' 11</br>';
			echo $zobrazNazov . ' 12</br>';
			echo $slider . ' 13</br>';
			echo $popisSlider . ' 14</br>';
			echo $koment . ' 15</br>';
			die();*/

					# PUBLIK
					// podla prav bude na schvalenie


					# URL
					$url = parent::seo_url($nazov);

					# INE COUNT COMMENT< VISITS, PAci sa mi to, nepavi sa mi to

						
					# ULOZ
					$uloz = $this->db->query('INSERT IGNORE INTO be_photos' . $this->prefix . ' 
												(photos_id, photos_nazov, photos_url, photos_popis, photos_stav, photos_visit, album_id, photos_img)  
												VALUES (NULL,
														"' . $this->db->real_escape_string($nazov) . '",
														"' . $this->db->real_escape_string($url) . '",
														"' . $this->db->real_escape_string($popis) . '",
														"' . $this->db->real_escape_string($stav) . '",
														"' . $this->db->real_escape_string($visit) . '",
														"' . $this->db->real_escape_string($album) . '",
														"' . $this->db->real_escape_string($last_id . '.jpg') . '") ');

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
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&action1=' . $_GET['action1']);
					exit();
				}
				

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
		//include_once "richtexteditor/richtexteditor/include_rte.php" 
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
				    <div class="col-sm-7">
				    <textarea class="form-control" name="popis" cols="200" rows="5"></textarea>
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
				    <div class="col-sm-6">
					    <input type="file" id="exampleInputFile" name="image">
	    				<p class="help-block">Vyber obrazok</p>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Album</label>
				    <div class="col-sm-6">
						<select class="form-control" name="album">
				    	<?php
				    	$selA = $this->albumSelect();
				    	foreach ($selA as $albumNazov => $posun1) {
				    		foreach ($posun1 as $albumId => $posun2) {
				    			?><option name="<?php echo htmlspecialchars($albumId); ?>" value="<?php echo htmlspecialchars($albumId); ?>"><?php echo htmlspecialchars($albumNazov); ?></option><?php
				    		}
				    	}
				    	?>
						</select>
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


	public function albumSelect ($typ = '1') {

		$query = $this->db->query('SELECT album_id, album_nazov FROM be_album' . $this->prefix . ' ORDER BY album_id DESC');
		if ($query->num_rows != FALSE) {
			$pole = array();
			while ($q = $query->fetch_assoc()) {
				$pole[$q['album_nazov']][$q['album_id']][] = $q;
			}
			return $pole;
		}
		else {
			return 0;
		}

	}

	


	public function photosEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov'])/* AND isset($_POST['popis']) AND isset($_POST['stav']) AND isset($_POST['album']) AND isset($_POST['stav'])*/ /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$album = (isset($_POST['album'])) ? $_POST['album'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			# hidden imf for edit image
			$im = (isset($_POST['im'])) ? $_POST['im'] : '';


			
			/*echo $_FILES["image"]['name'] . '<br>';
			
			echo $nazov . ' 1</br>';
			echo $popis . ' 2</br>';
			echo $album . ' 3</br>';
			echo $stav . ' 4</br>';
			die();*/

			if (!empty($nazov) AND !empty($stav) AND !empty($popis) AND !empty($album) AND !empty($im) AND !empty($_FILES["image"]) ) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_MINI', '../upload/gallery/mini/'); //horny panel mini
				define('UPLOAD_DIR_ORIGINAL', '../upload/gallery/original/'); // original
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

						//$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
						$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
						//$slider = UPLOAD_DIR_SLIDER . $last_id . '.jpg';   //mini 
						//--------------------------------------------------------------------------------------
				
						//------------------------ nove ------------------
						include 'component/resize-class.php';

						// *** 1) Initialise / load image
						  //$resizeObj = new resize($filenam);

						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
						  //$resizeObj -> resizeImage(200, 280, 'crop');

						  // *** 3) Save image
						  //$resizeObj -> saveImage($normal , 80);


						//===-=-=-=-=-=

						 $resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  $resize -> resizeImage(250, 250, 'crop');

						  // *** 3) Save image
						  $resize -> saveImage($mini , 70);
						//----------------------------------------------------------------------------------


						  //===-=-=-=-=-=

						  //$resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  //$resize -> resizeImage(300, 210, 'crop');

						  // *** 3) Save image
						  //$resize -> saveImage($slider , 20);
						//----------------------------------------------------------------------------------

						imagedestroy($src);  //original
						imagedestroy($normal);
						imagedestroy($mini);
						//imagedestroy($slider);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}


				if ($errors == '0') {

					# PRAVA
						/*echo $nazov . ' 1</br>';
			echo $popis . ' 2</br>';
			echo $text . ' 3</br>';
			echo $menu . ' 4</br>';
			echo $tags . ' 5</br>';
			echo parent::seo_url($nazov) . ' 6</br>';
			echo $last_id . ' 7</br>';
			echo $_POST['date'] . ' 8</br>';
			echo $autor . ' 9</br>';
			echo $publik . ' 10</br>';
			echo $zobrazPopis . ' 11</br>';
			echo $zobrazNazov . ' 12</br>';
			echo $slider . ' 13</br>';
			echo $popisSlider . ' 14</br>';
			echo $koment . ' 15</br>';
			die();*/

					# PUBLIK
					// podla prav bude na schvalenie


					# URL
					$url = parent::seo_url($nazov);

					# INE COUNT COMMENT< VISITS, PAci sa mi to, nepavi sa mi to

					$visit =0;

					if ($image) {
					
						# ULOZ
						$uloz = $this->db->query('UPDATE be_photos' . $this->prefix . ' SET 
														photos_nazov ="' . $this->db->real_escape_string($nazov) . '",
														photos_url ="' . $this->db->real_escape_string($url) . '",
														photos_popis ="' . $this->db->real_escape_string($popis) . '",
														photos_stav ="' . $this->db->real_escape_string($stav) . '",
														photos_visit ="' . $this->db->real_escape_string($visit) . '",
														album_id ="' . $this->db->real_escape_string($album) . '",
														photos_img ="' . $this->db->real_escape_string($last_id . '.jpg') . '" 
													WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
					}
					else {
					
						# ULOZ
						$uloz = $this->db->query('UPDATE be_photos' . $this->prefix . ' SET 
														photos_nazov ="' . $this->db->real_escape_string($nazov) . '",
														photos_url ="' . $this->db->real_escape_string($url) . '",
														photos_popis ="' . $this->db->real_escape_string($popis) . '",
														photos_stav ="' . $this->db->real_escape_string($stav) . '",
														photos_visit ="' . $this->db->real_escape_string($visit) . '",
														album_id ="' . $this->db->real_escape_string($album) . '" 
													WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
					}


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
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . $_GET['edit']);
					exit();
				}
				

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . $_GET['edit']);
				exit();
			}

		}

		##########################################################
		##### RICHTEXT EDITOR
		//include_once "richtexteditor/richtexteditor/include_rte.php" 
		##########################################################

		$over = $this->db->query('SELECT * FROM be_photos' . $this->prefix . ' 
									WHERE photos_id ="' . $this->db->real_escape_string($id) . '" ');
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
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['photos_nazov']); ?>" name="nazov">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-7">
					    <textarea class="form-control" name="popis" cols="200" rows="5"><?php echo htmlspecialchars($zobraz['photos_popis']); ?></textarea>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
					    	<img src="<?php echo ADRESA . PRIECINOK . GALLERY_CESTA_MINI . htmlspecialchars($zobraz['photos_img']);  ?>" alt="<?php echo htmlspecialchars($zobraz['photos_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['photos_nazov']); ?>" class="img-rounded" width="200">
						    <input type="file" id="exampleInputFile" name="image">
		    				<p class="help-danger">Pokial nevyberiete zostane predosli</p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Album</label>
					    <div class="col-sm-6">
							<select class="form-control" name="album">
					    	<?php
					    	$selA = $this->albumSelect();
					    	foreach ($selA as $albumNazov => $posun1) {
					    		foreach ($posun1 as $albumId => $posun2) {
					    			if ($albumId == $id) {
					    				?><option name="<?php echo htmlspecialchars($albumId); ?>" selected="selected" value="<?php echo htmlspecialchars($albumId); ?>"><?php echo htmlspecialchars($albumNazov); ?></option><?php
					    			}
					    			else {
					    				?><option name="<?php echo htmlspecialchars($albumId); ?>" value="<?php echo htmlspecialchars($albumId); ?>"><?php echo htmlspecialchars($albumNazov); ?></option><?php
					    			}
					    		}
					    	}
					    	?>
							</select>
						</div>	
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" <?php if($zobraz['photos_stav'] == '1') { echo 'checked="checked"'; } ?> value="1">
							    	Aktivne
							  	</label>
							</div>
							<div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios2" <?php if($zobraz['photos_stav'] == '2') { echo 'checked="checked"'; } ?> value="2">
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
					<input type="hidden" name="im" value="<?php echo htmlspecialchars($zobraz['photos_img']); ?>">	
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

	public function photosDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['ikona']) AND isset($id)) {


			unlink('../' . GALLERY_CESTA_MINI . $_POST['ikona']);
			unlink('../' . GALLERY_CESTA_ORIGINAL . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_photos' . $this->prefix . ' 
										WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');


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
		//include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##########################################################


		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_photos' . $this->prefix . ' 
									WHERE photos_id ="' . $this->db->real_escape_string(intval($id)) . '" ');
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
					    <div class="col-sm-6">
						<p class="form-control-static"><?php echo htmlspecialchars($zobraz['photos_nazov']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars($zobraz['photos_popis']); ?></p>
					    </div>
					</div>



					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
					    	<img src="<?php echo ADRESA . PRIECINOK . GALLERY_CESTA_MINI . htmlspecialchars($zobraz['photos_img']); ?>" class="img-rounded" alt="<?php echo htmlspecialchars($zobraz['photos_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['photos_nazov']); ?>" width="200">
					    </div>
					</div>


				</fieldset>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>

					<input type="hidden" name="ikona" value="<?php echo htmlspecialchars($zobraz['photos_img']); ?>">		
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
$photos = new photos($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>