<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class counter extends url {

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
		echo $this->lg[$this->flag]['nadpisCounter'];
	}


	public function counterTab () {

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

		$range = 50;

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
							    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo $this->lg[$this->flag]['search']; ?></label>
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
		
		$quer = 'SELECT SQL_CALC_FOUND_ROWS *, pocitadlo_ip, pocitadlo_datum 
										FROM be_pocitadlo' . $this->prefix . '  ';
		if (isset($_GET['search']) AND $_GET['search'] != FALSE AND $_GET['search'] != '') {
			
			#NAZOV
			$quer .= ' WHERE pocitadlo_ip LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_id LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR pocitadlo_host LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR pocitadlo_referer LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR pocitadlo_url LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR pocitadlo_useragent LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR pocitadlo_lang LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

			$quer .= ' OR pocitadlo_datum LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

		}

		#FILTERS
		if (isset($_GET['host'])AND $_GET['host'] != FALSE AND ($_GET['host'] == 'ASC' OR $_GET['host'] == 'DESC')) {
			$quer .= ' ORDER BY pocitadlo_host ' . $_GET['host'] . ' ';
		}
		elseif (isset($_GET['date'])AND $_GET['date'] != FALSE AND ($_GET['date'] == 'ASC' OR $_GET['date'] == 'DESC')) {
			$quer .= ' ORDER BY pocitadlo_datum ' . $_GET['date'] . ' ';
		}
		elseif (isset($_GET['referer'])AND $_GET['referer'] != FALSE AND ($_GET['referer'] == 'ASC' OR $_GET['referer'] == 'DESC')) {
			$quer .= ' ORDER BY pocitadlo_referer ' . $_GET['referer'] . ' ';
		}
		elseif (isset($_GET['user'])AND $_GET['user'] != FALSE AND ($_GET['user'] == 'ASC' OR $_GET['user'] == 'DESC')) {
			$quer .= ' ORDER BY user_id ' . $_GET['user'] . ' ';
		}
		elseif (isset($_GET['url'])AND $_GET['url'] != FALSE AND ($_GET['url'] == 'ASC' OR $_GET['url'] == 'DESC')) {
			$quer .= ' ORDER BY pocitadlo_url ' . $_GET['url'] . ' ';
		}
		elseif (isset($_GET['useragent'])AND $_GET['useragent'] != FALSE AND ($_GET['useragent'] == 'ASC' OR $_GET['useragent'] == 'DESC')) {
			$quer .= ' ORDER BY pocitadlo_useragent ' . $_GET['useragent'] . ' ';
		}
		elseif (isset($_GET['lang'])AND $_GET['lang'] != FALSE AND ($_GET['lang'] == 'ASC' OR $_GET['lang'] == 'DESC')) {
			$quer .= ' ORDER BY pocitadlo_lang ' . $_GET['lang'] . ' ';
		}
		else {
			$quer .= ' ORDER BY pocitadlo_datum DESC  ';
		}

		$quer .= ' LIMIT ' . $start . ', ' . $limit . ' ';

		$query = $this->db->query($quer);
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$por = $por + $start;

			$publikA = array('1' => 'Ano',
							 '0' => 'Nie' );
			$colorA = array('1' => 'success',
							 '0' => 'danger')
			?>
			<table class="table table-hover">
				<tr>
					<th>#</th>
					<th>Ip adresa</br>
							<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&user=DESC'; ?>">
								<span class="text-danger">↑</span>
							</a> 
							<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&user=ASC'; ?>">
								<span class="text-danger">↓</span>
							</a>
					</th>
					<th>Uzivatel</br>
							<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&user=DESC'; ?>">
								<span class="text-danger">↑</span>
							</a> 
							<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&user=ASC'; ?>">
								<span class="text-danger">↓</span>
							</a>
					</th>
					<!-- <th>Popis</th>  -->
					<th>Datum</br>
							<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=DESC'; ?>">
								<span class="text-danger">↑</span>
							</a> 
							<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=ASC'; ?>">
								<span class="text-danger">↓</span>
							</a>
					</th>
					<!-- <th>Url</th>  -->
					<th>Host</br>
							<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&host=DESC'; ?>">
								<span class="text-danger">↑</span>
							</a> 
							<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&host=ASC'; ?>">
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
					<th>Url</br>
							<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&url=DESC'; ?>">
								<span class="text-danger">↑</span>
							</a> 
							<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&url=ASC'; ?>">
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
					<th>Jazyk</br>
							<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&lang=DESC'; ?>">
								<span class="text-danger">↑</span>
							</a> 
							<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&lang=ASC'; ?>">
								<span class="text-danger">↓</span>
							</a>
					</th>
					
					<!-- <th><i class="glyphicon glyphicon-star"> &nbsp; </i>  </th>  -->
				</tr>
			<?php
			while ($counter = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="">
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php echo htmlspecialchars($counter['pocitadlo_ip']); ?></td>
					<td><?php echo htmlspecialchars($counter['user_id'] . '- '); if (isset($uzivA[$counter['user_id']][1])) { echo htmlspecialchars($uzivA[$counter['user_id']][1]); } ?></td>
					<td><?php echo htmlspecialchars($counter['pocitadlo_datum']); ?></td>
					<td><?php echo htmlspecialchars($counter['pocitadlo_host']); ?></td>
					<td><?php

							if (strlen(htmlspecialchars($counter['pocitadlo_referer'])) > 20) {
								$od =0;
								$do =20;
								$dlzka = strlen($counter['pocitadlo_referer']);  // 110  25 chcem mat po 20
								$sum = ceil($dlzka / 20);
								$t = '';
								for ($i=1; $i <= $sum ; $i++) { 
									$t .= substr($counter['pocitadlo_referer'], $od, 20 ) . '</br>';
									$od = $od +20;
									$do = $do +20;
								}
								echo htmlspecialchars_decode($t);
							}
							else {
								echo htmlspecialchars($counter['pocitadlo_referer']);
							}

						?>
					</td>
					<td>
						<?php

							if (strlen(htmlspecialchars($counter['pocitadlo_url'])) > 20) {
								$od =0;
								$do =20;
								$dlzka = strlen($counter['pocitadlo_url']);  // 110  25 chcem mat po 20
								$sum = ceil($dlzka / 20);
								$t = '';
								for ($i=1; $i <= $sum ; $i++) { 
									$t .= substr($counter['pocitadlo_url'], $od, 20 ) . '</br>';
									$od = $od +20;
									$do = $do +20;
								}
								echo htmlspecialchars_decode($t);
							}
							else {
								echo htmlspecialchars($counter['pocitadlo_url']);
							}

						?>
					</td>
					<td><?php echo htmlspecialchars($counter['pocitadlo_useragent']); ?></td>
					<td><?php echo htmlspecialchars($counter['pocitadlo_lang']); ?></td>
					<!-- <td>

						<a href="<?php //echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($counter['pocitadlo_id']); ?>"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
						<a href="<?php //echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($counter['pocitadlo_id']); ?>"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a>
					</td>  -->
				</tr>
				<?php

			}
			?></table><?php 

			# STRANKAOVANIE
			//$this->strankovanie($limit, $range, $url_zacni, $url_menu);
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
		//echo $numofpages;

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


	public function pagesActive ($id) {
		$query = $this->db->query('SELECT text_public FROM be_text' . $this->prefix . ' 
							WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_text' . $this->prefix . ' SET 
										text_public =1 WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');

			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
			exit();
		}
	}

	public function pagesDeactive ($id) {
		$query = $this->db->query('SELECT text_public FROM be_text' . $this->prefix . ' 
							WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_text' . $this->prefix . ' SET 
										text_public =0 WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');

			setcookie('odpoved','2', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
			exit();
		}
		else {
			# ERROR
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
			exit();
		}
	}

	public function pagesNew () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['popis']) AND isset($_POST['text']) /*AND isset($_POST['image'])*/ /*AND isset($_POST['date'])*//* AND isset($_POST['autor']) */AND isset($_POST['menu']) AND /*isset($_POST['publik']) AND*/ isset($_POST['tags']) /*AND isset($_POST['zobrazPopis']) AND isset($_POST['zobrazNazov']) AND isset($_POST['slider']) AND isset($_POST['popisSlider']) AND isset($_POST['koment'])*/ /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$text = (isset($_POST['text'])) ? $_POST['text'] : '';
			$date = (isset($_POST['date'])) ? $_POST['date'] : '';
			$autor = (isset($_POST['autor'])) ? $_POST['autor'] : '';
			$menu = (isset($_POST['menu'])) ? $_POST['menu'] : '';
			$publik = (isset($_POST['publik'])) ? $_POST['publik'] : '';
			$tags = (isset($_POST['tags'])) ? $_POST['tags'] : '';
			$zobrazPopis = (isset($_POST['zobrazPopis'])) ? $_POST['zobrazPopis'] : '';
			$zobrazNazov = (isset($_POST['zobrazNazov'])) ? $_POST['zobrazNazov'] : '';
			$slider = (isset($_POST['slider'])) ? $_POST['slider'] : '';
			$popisSlider = (isset($_POST['popisSlider'])) ? $_POST['popisSlider'] : '';
			$koment = (isset($_POST['koment'])) ? $_POST['koment'] : '';

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

			if (!empty($nazov) AND !empty($popis) AND !empty($text) AND !empty($menu) /*AND !empty($publik)*/ AND !empty($tags) /*AND !empty($zobrazPopis) AND !empty($zobrazNazov) AND !empty($slider) AND !empty($popisSlider) AND !empty($koment)*/ /*AND !empty($_FILES["image"])*/ ) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

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

					# DATUM
					if (isset($_POST['date']) AND $_POST['date'] != FALSE) {
						# ZADAL

						$date = $_POST['date'];
					}
					else {
						$date = date('Y-m-d H:i:s');
					}

					# AUTOR
					if (isset($_POST['autor']) AND $_POST['autor'] != '0') {
						# OVERIME CI EXISTUJE
						$over = $this->db->query('SELECT user_id FROM be_user' . $this->prefix . ' 
													WHERE user_id ="' . $this->db->real_escape_string($_POST['autor']) . '" LIMIT 1');
						if ($over->num_rows != FALSE) {
							# OK JE
							$autor = $_POST['autor'];
						}
						else {
							$autor = $_SESSION['user_id'];
						}
					}
					else {
						$autor = $_SESSION['user_id'];
					}

					# URL
					$text_url = parent::seo_url($nazov);

					# INE COUNT COMMENT< VISITS, PAci sa mi to, nepavi sa mi to


					if ($image) {
						
						# ULOZ
						$uloz = $this->db->query('INSERT IGNORE INTO be_text' . $this->prefix . ' 
												(text_id, text_nazov, text_popis, text_url, text_cely, text_obrazok, text_datum, autor_id, text_public, menu_id, text_tags, text_popis_ano, text_nazov_ano, text_slider, text_slider_popis, text_comment_ano)  
												VALUES (NULL,
														"' . $this->db->real_escape_string($nazov) . '",
														"' . $this->db->real_escape_string($popis) . '",
														"' . $this->db->real_escape_string($text_url) . '",
														"' . $this->db->real_escape_string($text) . '",
														"' . $this->db->real_escape_string($last_id . '.jpg') . '",
														"' . $this->db->real_escape_string($date) . '",
														"' . $this->db->real_escape_string($autor) . '",
														"' . $this->db->real_escape_string($publik) . '",
														"' . $this->db->real_escape_string($menu) . '",
														"' . $this->db->real_escape_string($tags) . '",
														"' . $this->db->real_escape_string($zobrazPopis) . '",
														"' . $this->db->real_escape_string($zobrazNazov) . '",
														"' . $this->db->real_escape_string($slider) . '",
														"' . $this->db->real_escape_string($popisSlider) . '",
														"' . $this->db->real_escape_string($koment) . '") ');

						//print_r($uloz);
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']/* . '&action=' . $_GET['action']*/);
						exit();
					}
					else {

						$nula =0;

						# ULOZ
						$uloz = $this->db->query('INSERT IGNORE INTO be_text' . $this->prefix . ' 
												(text_id, text_nazov, text_popis, text_url, text_cely, text_obrazok, text_datum, autor_id, text_public, menu_id, text_tags, text_popis_ano, text_nazov_ano, text_slider, text_slider_popis, text_comment_ano)  
												VALUES (NULL,
														"' . $this->db->real_escape_string($nazov) . '",
														"' . $this->db->real_escape_string($popis) . '",
														"' . $this->db->real_escape_string($text_url) . '",
														"' . $this->db->real_escape_string($text) . '",
														"' . $this->db->real_escape_string($nula) . '",
														"' . $this->db->real_escape_string($date) . '",
														"' . $this->db->real_escape_string($autor) . '",
														"' . $this->db->real_escape_string($publik) . '",
														"' . $this->db->real_escape_string($menu) . '",
														"' . $this->db->real_escape_string($tags) . '",
														"' . $this->db->real_escape_string($zobrazPopis) . '",
														"' . $this->db->real_escape_string($zobrazNazov) . '",
														"' . $this->db->real_escape_string($slider) . '",
														"' . $this->db->real_escape_string($popisSlider) . '",
														"' . $this->db->real_escape_string($koment) . '") ');

						//print_r($uloz);
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']/* . '&action=' . $_GET['action']*/);
						exit();
					}	
				}
				else {
					setcookie('odpoved','1', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action1=' . $_GET['action']);
					exit();
				}
				

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action1=' . $_GET['action']);
				exit();
			}

		}

		##########################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php" 
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
				    <textarea class="form-control" name="popis" cols="200" rows="200"></textarea>
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
				    <div class="col-sm-6">
				    <!-- <textarea class="form-control" name="text"></textarea> -->
				    <?php   
                // Create Editor instance and use Text property to load content into the RTE.  
                $rte=new RichTextEditor();   
                $rte->Text=""; 
                // Set a unique ID to Editor   
                $rte->ID="text";    
                $rte->MvcInit();   
                // Render Editor 
                echo $rte->GetString();  
            ?>   
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
				    <label for="inputEmail3" class="col-sm-2 control-label">Datum</br><small>Podla prav</small></label>
				    <div class="col-sm-6">
				    	<input type="datepicker" class="form-control" id="inputText3" placeholder="" name="date">
				    </div>
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Autor</br><small>Podla prav</small></label>
				    <div class="col-sm-6">
						<select class="form-control" name="autor">
				    	<?php
				    	$selA = $this->autorSelect();
				    	foreach ($selA as $nick => $posun1) {
				    		foreach ($posun1 as $meno => $posun2) {
				    			foreach ($posun2 as $id => $posun3) {
				    				?><option name="<?php echo $id; ?>" value="<?php echo $id; ?>"><?php echo htmlspecialchars($nick . '   ' . $meno); ?></option><?php
				    			}
				    		}
				    	}
				    	?>
						</select>
					</div>	
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Menu</label>
				    <div class="col-sm-6">
				    	<?php $this->menuSelect();  ?>
				    </div>
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Publikovat</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="publik" id="optionsRadios1" value="1" checked="checked">
						    Ano
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="publik" id="optionsRadios2" value="0">
						    Nie
						  </label>
						</div>
					</div>	
			  	</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">TAGS Klucove slova</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Klucove slova..." name="tags">
				    </div>
				</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Zobrazenie popisu</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="zobrazPopis" id="optionsRadios1" value="1" checked="checked">
						    Ano
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="zobrazPopis" id="optionsRadios2" value="2">
						    Nie
						  </label>
						</div>
					</div>	
			  	</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Zobrazenie nazvu</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="zobrazNazov" id="optionsRadios1" value="1" checked="checked">
						    Ano
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="zobrazNazov" id="optionsRadios2" value="2">
						    Nie
						  </label>
						</div>
					</div>	
			  	</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Pouzit v slideri</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="slider" id="optionsRadios1" value="1" checked="checked">
						    Ano
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="slider" id="optionsRadios2" value="2">
						    Nie
						  </label>
						</div>
					</div>	
			  	</div>

			  	<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Popis v slideri</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="popisSlider" id="optionsRadios1" value="1" checked="checked">
						    Ano
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="popisSlider" id="optionsRadios2" value="2">
						    Nie
						  </label>
						</div>
					</div>	
			  	</div>

				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Komentare</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="koment" id="optionsRadios1" value="1" checked="checked">
						    Povolene
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="koment" id="optionsRadios2" value="0">
						    Zakazane
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


	public function autorSelect () {

		$query = $this->db->query('SELECT user_nick, user_meno, user_id FROM be_user' . $this->prefix . ' ORDER BY user_nick DESC');
		if ($query->num_rows != FALSE) {
			$pole = array();
			while ($q = $query->fetch_assoc()) {
				$pole[$q['user_nick']][$q['user_meno']][$q['user_id']][] = $q;
			}
			return $pole;
		}
		else {
			return 0;
		}

	}

	public function menuSelect ($id = '0') {

		# $typ = 1; // new  
		# $typ = 2; // edit

			?><select class="form-control" name="menu"><?php
			

			if ($id == '0') {
				?><option style="font-weight: bold; color: #212121;" value="0" name="0"><?php echo htmlspecialchars('None'); ?></option><?php
			}

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

							# TEXT - SECTION _> CATEGORIES   /*menu_typ ="text" AND*/
							$nav1 = $this->db->query('SELECT * FROM be_menu' . $this->prefix . ' 
														WHERE menu_rodic ="' . $this->db->real_escape_string($tlac1['menu_id']) . '" AND menu_pozicia ="' . $this->db->real_escape_string($tla['pozicia_nazov']) . '" 
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

							# CLANOK - SECTION _> CATEGORIES     nwm menu_typ ="clanok"
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






	public function pagesEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['popis']) AND isset($_POST['text']) /*AND isset($_POST['image'])*/ /*AND isset($_POST['date'])*//* AND isset($_POST['autor']) */AND isset($_POST['menu']) AND /*isset($_POST['publik']) AND*/ isset($_POST['tags']) /*AND isset($_POST['zobrazPopis']) AND isset($_POST['zobrazNazov']) AND isset($_POST['slider']) AND isset($_POST['popisSlider']) AND isset($_POST['koment'])*/ /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$text = (isset($_POST['text'])) ? $_POST['text'] : '';
			$date = (isset($_POST['date'])) ? $_POST['date'] : '';
			$autor = (isset($_POST['autor'])) ? $_POST['autor'] : '';
			$menu = (isset($_POST['menu'])) ? $_POST['menu'] : '';
			$publik = (isset($_POST['publik'])) ? $_POST['publik'] : '';
			$tags = (isset($_POST['tags'])) ? $_POST['tags'] : '';
			$zobrazPopis = (isset($_POST['zobrazPopis'])) ? $_POST['zobrazPopis'] : '';
			$zobrazNazov = (isset($_POST['zobrazNazov'])) ? $_POST['zobrazNazov'] : '';
			$slider = (isset($_POST['slider'])) ? $_POST['slider'] : '';
			$popisSlider = (isset($_POST['popisSlider'])) ? $_POST['popisSlider'] : '';
			$koment = (isset($_POST['koment'])) ? $_POST['koment'] : '';
			
			$im = (isset($_POST['im'])) ? $_POST['im'] : '';

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

			if (!empty($nazov) AND !empty($popis) AND !empty($text) AND !empty($menu) /*AND !empty($publik)*/ AND !empty($tags) /*AND !empty($zobrazPopis) AND !empty($zobrazNazov) AND !empty($slider) AND !empty($popisSlider) AND !empty($koment)*/ /*AND !empty($_FILES["image"])*/ ) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

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

					# DATUM
					if (isset($_POST['date']) AND $_POST['date'] != FALSE) {
						# ZADAL

						$date = $_POST['date'];
					}
					else {
						$date = date('Y-m-d H:i:s');
					}

					# AUTOR
					if (isset($_POST['autor']) AND $_POST['autor'] != '0') {
						# OVERIME CI EXISTUJE
						$over = $this->db->query('SELECT user_id FROM be_user' . $this->prefix . ' 
													WHERE user_id ="' . $this->db->real_escape_string($_POST['autor']) . '" LIMIT 1');
						if ($over->num_rows != FALSE) {
							# OK JE
							$autor = $_POST['autor'];
						}
						else {
							$autor = $_SESSION['user_id'];
						}
					}
					else {
						$autor = $_SESSION['user_id'];
					}

					# URL
					$text_url = parent::seo_url($nazov);

					# INE COUNT COMMENT< VISITS, PAci sa mi to, nepavi sa mi to


					if ($image) {

						# DELETE ALTER IMAGE
						unlink('../' . IMAGE_ARTICLES_MINI . $im);
						unlink('../' . IMAGE_ARTICLES_NORMAL . $im);
						unlink('../' . IMAGE_ARTICLES_ORIGINAL . $im);
						unlink('../' . IMAGE_ARTICLES_SLIDER . $im);
						
						# ULOZ
						$uloz = $this->db->query('UPDATE be_text' . $this->prefix . ' SET 
														text_nazov ="' . $this->db->real_escape_string($nazov) . '",
														text_popis ="' . $this->db->real_escape_string($popis) . '",
														text_url ="' . $this->db->real_escape_string($text_url) . '",
														text_cely ="' . $this->db->real_escape_string($text) . '",
														text_obrazok ="' . $this->db->real_escape_string($last_id . '.jpg') . '",
														text_datum ="' . $this->db->real_escape_string($date) . '",
														autor_id ="' . $this->db->real_escape_string($autor) . '",
														text_public ="' . $this->db->real_escape_string($publik) . '",
														menu_id ="' . $this->db->real_escape_string($menu) . '",
														text_tags ="' . $this->db->real_escape_string($tags) . '",
														text_popis_ano ="' . $this->db->real_escape_string($zobrazPopis) . '",
														text_nazov_ano ="' . $this->db->real_escape_string($zobrazNazov) . '",
														text_slider ="' . $this->db->real_escape_string($slider) . '",
														text_slider_popis ="' . $this->db->real_escape_string($popisSlider) . '",
														text_comment_ano ="' . $this->db->real_escape_string($koment) . '"
													WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');

						//print_r($uloz);
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']/* . '&action=' . $_GET['action']*/);
						exit();
					}
					else {

						//$nula =0;

						# ULOZ
						$uloz = $this->db->query('UPDATE be_text' . $this->prefix . ' SET 
														text_nazov ="' . $this->db->real_escape_string($nazov) . '",
														text_popis ="' . $this->db->real_escape_string($popis) . '",
														text_url ="' . $this->db->real_escape_string($text_url) . '",
														text_cely ="' . $this->db->real_escape_string($text) . '",
														text_datum ="' . $this->db->real_escape_string($date) . '",
														autor_id ="' . $this->db->real_escape_string($autor) . '",
														text_public ="' . $this->db->real_escape_string($publik) . '",
														menu_id ="' . $this->db->real_escape_string($menu) . '",
														text_tags ="' . $this->db->real_escape_string($tags) . '",
														text_popis_ano ="' . $this->db->real_escape_string($zobrazPopis) . '",
														text_nazov_ano ="' . $this->db->real_escape_string($zobrazNazov) . '",
														text_slider ="' . $this->db->real_escape_string($slider) . '",
														text_slider_popis ="' . $this->db->real_escape_string($popisSlider) . '",
														text_comment_ano ="' . $this->db->real_escape_string($koment) . '"
													WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');

					 	//print_r($uloz);
					 	setcookie('odpoved','2', time()+1);
					 	//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					 	//header("Location: " . $_SERVER['HTTP_REFERER']);
					 	header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']/* . '&action=' . $_GET['action']*/);
					 	exit();
					}	
				}
				else {
					setcookie('odpoved','1', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . $_GET['edit']/* . '&action=' . $_GET['action']*/);
					exit();
				}
				

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']/* . '&action=' . $_GET['action']*/);
				exit();
			}

		}
		# END POST DATA

		##########################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##########################################################


		$over = $this->db->query('SELECT * FROM be_text' . $this->prefix . ' 
									WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');
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
					    <div class="col-sm-7">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['text_nazov']); ?>" name="nazov">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-7">
					    <textarea class="form-control" name="popis" rows="5"><?php echo htmlspecialchars($zobraz['text_popis']); ?></textarea>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    <!-- <textarea class="form-control" name="text"><?php //echo htmlspecialchars_decode($zobraz['text_cely']); ?></textarea> -->
					    <?php   
			                // Create Editor instance and use Text property to load content into the RTE.  
			                $rte=new RichTextEditor();   
			                $rte->Text= $zobraz['text_cely']; 
			                // Set a unique ID to Editor   
			                $rte->ID="text";    
			                $rte->MvcInit();   
			                // Render Editor 
			                echo $rte->GetString();  
			            ?>   
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
						<?php
						if ($zobraz['text_obrazok'] != '0') {
							?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_ARTICLES_MINI . htmlspecialchars($zobraz['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($zobraz['text_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['text_nazov']); ?>" style="" class="img-rounded" width="400"><?php
						}
						?>
						    <input type="file" id="exampleInputFile" name="image">
						<p class="help-block" style="color: red; ">Pokial nevyberiete, tak zostane predosli!!!</p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</br><small>Podla prav</small></label>
					    <div class="col-sm-6">
					    	<input type="datepicker" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['text_datum']); ?>" name="date">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Autor</br><small>Podla prav</small></label>
					    <div class="col-sm-6">
							<select class="form-control" name="autor">
					    	<?php
					    	$selA = $this->autorSelect();
					    	foreach ($selA as $nick => $posun1) {
					    		foreach ($posun1 as $meno => $posun2) {
					    			foreach ($posun2 as $id => $posun3) {
					    				?><option name="<?php echo $id; ?>" value="<?php echo $id; ?>"><?php echo htmlspecialchars($nick . '   ' . $meno); ?></option><?php
					    			}
					    		}
					    	}
					    	?>
							</select>
						</div>	
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Menu</label>
					    <div class="col-sm-6">
					    	<?php $this->menuSelect($zobraz['menu_id']);  ?>
					    </div>
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Publikovat</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="publik" id="optionsRadios1" value="1" <?php if ($zobraz['text_public'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="publik" id="optionsRadios2" value="0" <?php if ($zobraz['text_public'] == '0') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">TAGS Klucove slova</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['text_tags']); ?>" name="tags">
					    </div>
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Zobrazenie popisu</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="zobrazPopis" id="optionsRadios1" value="1" <?php if ($zobraz['text_popis_ano'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="zobrazPopis" id="optionsRadios2" value="2" <?php if ($zobraz['text_popis_ano'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Zobrazenie nazvu</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="zobrazNazov" id="optionsRadios1" value="1" <?php if ($zobraz['text_nazov_ano'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="zobrazNazov" id="optionsRadios2" value="2" <?php if ($zobraz['text_nazov_ano'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Pouzit v slideri</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="slider" id="optionsRadios1" value="1" <?php if ($zobraz['text_slider'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="slider" id="optionsRadios2" value="2" <?php if ($zobraz['text_slider'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

				  	<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Popis v slideri</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="popisSlider" id="optionsRadios1" value="1" <?php if ($zobraz['text_slider_popis'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="popisSlider" id="optionsRadios2" value="2" <?php if ($zobraz['text_slider_popis'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Komentare</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="koment" id="optionsRadios1" value="1" <?php if ($zobraz['text_comment_ano'] == '1') { echo 'checked="checked"'; } ?> >
							    Povolene
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="koment" id="optionsRadios2" value="2" <?php if ($zobraz['text_comment_ano'] == '2') { echo 'checked="checked"'; } ?> >
							    Zakazane
							  </label>
							</div>
						</div>	
				  	</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-info btn-sm" name="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
						</div>
					</div>
					<input type="hidden" name="im" value="<?php echo htmlspecialchars($zobraz['text_obrazok']); ?>">	
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

	public function pagesDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['ikona']) AND isset($id)) {


			unlink('../' . IMAGE_ARTICLES_MINI . $_POST['ikona']);
			unlink('../' . IMAGE_ARTICLES_NORMAL . $_POST['ikona']);
			unlink('../' . IMAGE_ARTICLES_ORIGINAL . $_POST['ikona']);
			unlink('../' . IMAGE_ARTICLES_SLIDER . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_text' . $this->prefix . ' 
										WHERE text_id ="' . $this->db->real_escape_string($id) . '" AND 
											text_obrazok ="' . $this->db->real_escape_string($_POST['ikona']) . '" ');


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
		$over = $this->db->query('SELECT * FROM be_text' . $this->prefix . ' t 
											JOIN be_user' . $this->prefix . ' u ON t.autor_id = u.user_id 
									WHERE text_id ="' . $this->db->real_escape_string($id) . '" ');
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
						<p class="form-control-static"><?php echo htmlspecialchars($zobraz['text_nazov']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars($zobraz['text_popis']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars_decode($zobraz['text_cely']); ?></p> 
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
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
					    	<?php
					    	if (file_exists(ADRESA . PRIECINOK . IMAGE_ARTICLES_MINI . htmlspecialchars($zobraz['text_obrazok']))) {
					    		?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_ARTICLES_MINI . htmlspecialchars($zobraz['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($zobraz['text_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['text_nazov']); ?>" class="img-thumbnail" width="70"><?php
					    	}
					    	else {
					    		?><img src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($zobraz['text_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['text_nazov']); ?>" class="img-thumbnail" width="70"><?php
					    	}
					    	?>
					    	
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</br><small>Podla prav</small></label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['text_datum']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Autor</br><small>Podla prav</small></label>
					    <div class="col-sm-6">
							<p class="form-control-static"><?php echo htmlspecialchars($zobraz['user_meno']); ?></p>
						</div>	
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Menu</label>
					    <div class="col-sm-6">
					    	<?php $this->menuSelect($zobraz['menu_id']);  ?>
					    </div>
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Publikovat</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="publik" id="optionsRadios1" value="1" <?php if ($zobraz['text_public'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="publik" id="optionsRadios2" value="0" <?php if ($zobraz['text_public'] == '0') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">TAGS Klucove slova</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['text_tags']); ?>" name="tags">
					    </div>
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Zobrazenie popisu</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="zobrazPopis" id="optionsRadios1" value="1" <?php if ($zobraz['text_popis_ano'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="zobrazPopis" id="optionsRadios2" value="2" <?php if ($zobraz['text_popis_ano'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Zobrazenie nazvu</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="zobrazNazov" id="optionsRadios1" value="1" <?php if ($zobraz['text_nazov_ano'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="zobrazNazov" id="optionsRadios2" value="2" <?php if ($zobraz['text_nazov_ano'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Pouzit v slideri</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="slider" id="optionsRadios1" value="1" <?php if ($zobraz['text_slider'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="slider" id="optionsRadios2" value="2" <?php if ($zobraz['text_slider'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

				  	<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Popis v slideri</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="popisSlider" id="optionsRadios1" value="1" <?php if ($zobraz['text_slider_popis'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="popisSlider" id="optionsRadios2" value="2" <?php if ($zobraz['text_slider_popis'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Komentare</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="koment" id="optionsRadios1" value="1" <?php if ($zobraz['text_comment_ano'] == '1') { echo 'checked="checked"'; } ?> >
							    Povolene
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="koment" id="optionsRadios2" value="2" <?php if ($zobraz['text_comment_ano'] == '2') { echo 'checked="checked"'; } ?> >
							    Zakazane
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

					<input type="hidden" name="ikona" value="<?php echo htmlspecialchars($zobraz['text_obrazok']); ?>">		
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
$counter = new counter($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>