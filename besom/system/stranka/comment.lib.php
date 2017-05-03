<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class comment {

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


	public function commentTab () {

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
		$uziv = $this->db->query('SELECT user_id, user_nick FROM be_user' . $this->prefix . ' ');
		if ($uziv->num_rows != FALSE) {
			$uzivA = array();
			
			$a = 1;
			$uzivA[0][1] = ' ';
			while($row = $uziv->fetch_array()) {
			    $uzivA[$a] = $row;
			    $a +=1;
			}

		}
		//echo $uzivA[1][1]; // prve sa zvisuje a druhe zostava 1

			# ARRAY MENU
		$textA = array();
		$textP = $this->db->query('SELECT text_id, text_nazov, text_obrazok, text_url FROM be_text' . $this->prefix . ' 
									ORDER BY menu_id ASC ');
		if ($textP->num_rows != FALSE) {

			while ($pol = $textP->fetch_assoc()) {
				$textA[$pol['text_id']] = $pol;

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
								<input type="submit" name="submit" value="Hladaj">

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
										FROM be_comment' . $this->prefix . ' ';
		if (isset($_GET['search']) AND $_GET['search'] != FALSE AND $_GET['search'] != '') {
			
			#NAZOV
			$quer .= ' WHERE text_id LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR comment_name LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR comment_email LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR comment_text LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR user_id LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR comment_date LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR comment_ip LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR parent_id LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';
			$quer .= ' OR comment_stav LIKE "' . $this->db->real_escape_string('%' . $_GET['search'] . '%') . '" ';

		}

		#FILTERS
		// VIEWS
		if (isset($_GET['nazov'])AND $_GET['nazov'] != FALSE AND ($_GET['nazov'] == 'ASC' OR $_GET['nazov'] == 'DESC')) {
			$quer .= ' ORDER BY text_id ' . $_GET['nazov'] . ' ';
		}
		elseif (isset($_GET['name'])AND $_GET['name'] != FALSE AND ($_GET['name'] == 'ASC' OR $_GET['name'] == 'DESC')) {
			$quer .= ' ORDER BY comment_name ' . $_GET['name'] . ' ';
		}
		elseif (isset($_GET['email'])AND $_GET['email'] != FALSE AND ($_GET['email'] == 'ASC' OR $_GET['email'] == 'DESC')) {
			$quer .= ' ORDER BY comment_email ' . $_GET['email'] . ' ';
		}
		elseif (isset($_GET['comments'])AND $_GET['comments'] != FALSE AND ($_GET['comments'] == 'ASC' OR $_GET['comments'] == 'DESC')) {
			$quer .= ' ORDER BY comment_text ' . $_GET['comments'] . ' ';
		}
		elseif (isset($_GET['user'])AND $_GET['user'] != FALSE AND ($_GET['user'] == 'ASC' OR $_GET['user'] == 'DESC')) {
			$quer .= ' ORDER BY user_id ' . $_GET['user'] . ' ';
		}
		elseif (isset($_GET['date'])AND $_GET['date'] != FALSE AND ($_GET['date'] == 'ASC' OR $_GET['date'] == 'DESC')) {
			$quer .= ' ORDER BY comment_date ' . $_GET['date'] . ' ';
		}
		elseif (isset($_GET['ip'])AND $_GET['ip'] != FALSE AND ($_GET['ip'] == 'ASC' OR $_GET['ip'] == 'DESC')) {
			$quer .= ' ORDER BY comment_ip ' . $_GET['ip'] . ' ';
		}
		elseif (isset($_GET['parent'])AND $_GET['parent'] != FALSE AND ($_GET['parent'] == 'ASC' OR $_GET['parent'] == 'DESC')) {
			$quer .= ' ORDER BY parent_id ' . $_GET['parent'] . ' ';
		}
		elseif (isset($_GET['stav'])AND $_GET['stav'] != FALSE AND ($_GET['stav'] == 'ASC' OR $_GET['stav'] == 'DESC')) {
			$quer .= ' ORDER BY comment_stav ' . $_GET['stav'] . ' ';
		}
		else {
			$quer .= ' ORDER BY comment_date DESC  ';
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
					<th>Obrazok textu</th>
					<th>Nazov textu 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&nazov=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&nazov=ASC'; ?>">
							<
						</a>
					</th>

					<th>Avatar</th>

					<th>Reg.
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&user=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&user=ASC'; ?>">
							<
						</a>
					</th>

					<th>Meno 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&name=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&name=ASC'; ?>">
							<
						</a>
					</th>

					<th>Email 
						<a title="A-Z" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&email=DESC'; ?>">
							>
						</a> 
						<a title="Z-A" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&email=ASC'; ?>">
							<
						</a>
					</th>

					<th>Text 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&comments=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&comments=ASC'; ?>">
							<
						</a> 
					</th>

					<th>Datum 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&date=ASC'; ?>">
							<
						</a>
					</th>

					<th>Ip 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&ip=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&ip=ASC'; ?>">
							<
						</a>
					</th>

					<th>Rodic 
						<a title="max" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&parent=DESC'; ?>">
							>
						</a> 
						<a title="min" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&parent=ASC'; ?>">
							<
						</a>
					</th>

					<th>Stav 
						<a title="ano" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=DESC'; ?>">
							>
						</a> 
						<a title="nie" href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&stav=ASC'; ?>">
							<
						</a>
					</th>

					<th><i class="glyphicon glyphicon-star"> &nbsp; </i> &nbsp;............... </th>
				</tr>
			<?php
			while ($text = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php if(!isset($textA[$text['text_id']]['text_nazov'])) { echo htmlspecialchars($colorA['4']); } else { echo htmlspecialchars($colorA[$text['comment_stav']]); } ?>">

					<td><?php echo htmlspecialchars($por); ?></td>
					
					<td><?php
						if (isset($textA[$text['text_id']]['text_obrazok'])) {

							if ($textA[$text['text_id']]['text_obrazok'] != '0') { ?><img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . IMAGE_ARTICLES_MINI . htmlspecialchars($textA[$text['text_id']]['text_obrazok']); ?>"><?php }else { ?><img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>"><?php }
						}
						else {
							?><img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>"><?php
						}
						?>
					</td>

					<td><?php 
							//echo htmlspecialchars($text['text_nazov']); 
						if (isset($textA[$text['text_id']]['text_nazov'])) {
							echo htmlspecialchars($textA[$text['text_id']]['text_nazov']);
						}
						else {
							echo htmlspecialchars_decode('<b class="text-danger">Uncategoryzed</b>');
						}
						?>
					</td>


					<td><?php if ($text['comment_img'] != '') { ?><img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . IMAGE_COMMENT . htmlspecialchars($text['comment_img']); ?>"><?php }else { ?><img class="img-rounded" width="50" src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/user.gif'); ?>"><?php } ?></td>

					<td><?php echo htmlspecialchars($text['user_id'] . ' ' . $uzivA[$text['user_id']][1]); ?></td>
					<td><?php echo htmlspecialchars($text['comment_name']); ?></td>
					<td><?php echo htmlspecialchars($text['comment_email']); ?></td>
					<td><?php echo htmlspecialchars($text['comment_text']); ?></td>

					<td><?php echo htmlspecialchars($text['comment_date']); ?></td>
					<td><?php echo htmlspecialchars($text['comment_ip']); ?></td>
					<td><?php echo htmlspecialchars($text['parent_id']); ?></td>
					<td><?php echo '<i class="' . $iconA[$text['comment_stav']] . '" style="color: ' . $colA[$text['comment_stav']] . '"></i>&nbsp;' . htmlspecialchars($publikA[$text['comment_stav']]); ?></td>
					<td>
							<?php
								if ($text['comment_stav'] == '1') {
									# active
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&deactive=' . $text['comment_id']);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
								}
								else {
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . htmlspecialchars('&active=' . $text['comment_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

								?>
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&edit=' . htmlspecialchars($text['comment_id']); ?>"><i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i></a> &nbsp; 
					<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&delete=' . htmlspecialchars($text['comment_id']); ?>"><i class="glyphicon glyphicon-trash" style="color: red; "></i></a></td>
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


	public function commentActive ($id) {
		$query = $this->db->query('SELECT comment_stav FROM be_comment' . $this->prefix . ' 
							WHERE comment_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_comment' . $this->prefix . ' SET 
										comment_stav =1 WHERE comment_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function commentDeactive ($id) {
		$query = $this->db->query('SELECT comment_stav FROM be_comment' . $this->prefix . ' 
							WHERE comment_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_comment' . $this->prefix . ' SET 
										comment_stav =2 WHERE comment_id ="' . $this->db->real_escape_string($id) . '" ');

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


	public function commentNew () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['sirka']) AND isset($_POST['popis']) AND isset($_POST['text']) /*AND isset($_POST['image'])*/ /*AND isset($_POST['date'])*//* AND isset($_POST['autor']) */AND isset($_POST['menu']) AND /*isset($_POST['publik']) AND*/ isset($_POST['tags']) /*AND isset($_POST['zobrazPopis']) AND isset($_POST['zobrazNazov']) AND isset($_POST['slider']) AND isset($_POST['popisSlider']) AND isset($_POST['koment'])*/ /*AND isset($_FILES["image"])*/ ) {


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

			if (!empty($nazov) AND !empty($sirka) AND !empty($popis) AND !empty($text) AND !empty($menu) /*AND !empty($publik)*/ AND !empty($tags) /*AND !empty($zobrazPopis) AND !empty($zobrazNazov) AND !empty($slider) AND !empty($popisSlider) AND !empty($koment)*/ /*AND !empty($_FILES["image"])*/ ) {
				


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
												(text_id, text_nazov, text_popis, text_url, text_cely, text_obrazok, text_datum, autor_id, text_public, menu_id, text_tags, text_popis_ano, text_nazov_ano, text_slider, text_slider_popis, text_sirka, text_comment_ano)  
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
														"' . $this->db->real_escape_string($sirka) . '",
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
												(text_id, text_nazov, text_popis, text_url, text_cely, text_obrazok, text_datum, autor_id, text_public, menu_id, text_tags, text_popis_ano, text_nazov_ano, text_slider, text_slider_popis, text_sirka, text_comment_ano)  
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
														"' . $this->db->real_escape_string($sirka) . '",
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
				    <textarea class="form-control" name="popis" cols="200" rows="5"></textarea>
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
				    <label for="inputEmail3" class="col-sm-2 control-label">Sirka</label>
				    <div class="col-sm-6">
						<select class="form-control" name="sirka">

				    		<option name="full" value="full">Cela sirka</option>
				    		<option name="half" value="half">polovica</option>
				    		<option name="right" value="right" disabled="disabled">Napravo</option>
				    		<option name="left" value="left" disabled="disabled">Nalavo<option>

						</select>
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






	public function commentEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['sirka']) AND isset($_POST['popis']) AND isset($_POST['text']) /*AND isset($_POST['image'])*/ /*AND isset($_POST['date'])*//* AND isset($_POST['autor']) */AND isset($_POST['menu']) AND /*isset($_POST['publik']) AND*/ isset($_POST['tags']) /*AND isset($_POST['zobrazPopis']) AND isset($_POST['zobrazNazov']) AND isset($_POST['slider']) AND isset($_POST['popisSlider']) AND isset($_POST['koment'])*/ /*AND isset($_FILES["image"])*/ ) {


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
			$sirka = (isset($_POST['sirka'])) ? $_POST['sirka'] : '';
			
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

			if (!empty($nazov) AND !empty($sirka) AND !empty($popis) AND !empty($text) AND !empty($menu) /*AND !empty($publik)*/ AND !empty($tags) /*AND !empty($zobrazPopis) AND !empty($zobrazNazov) AND !empty($slider) AND !empty($popisSlider) AND !empty($koment)*/ /*AND !empty($_FILES["image"])*/ ) {
				


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
														text_sirka ="' . $this->db->real_escape_string($sirka) . '",
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
														text_sirka ="' . $this->db->real_escape_string($sirka) . '",
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
					    <label for="inputEmail3" class="col-sm-2 control-label">Sirka</label>
					    <div class="col-sm-6">
							<select class="form-control" name="sirka">

					    		<option name="full" value="full" <?php if($zobraz['text_sirka'] == 'full') { echo ' selected="selected" '; } ?> >Cela sirka</option>
					    		<option name="half" value="half" <?php if($zobraz['text_sirka'] == 'full') { echo ' selected="selected" '; } ?> >polovica</option>
					    		<option name="right" value="right" disabled="disabled">Napravo</option>
					    		<option name="left" value="left" disabled="disabled">Nalavo<option>

							</select>
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

	public function commentDelete ($id) {

		# POST
		if (isset($_POST['submit'])/* AND isset($_POST['ikona'])*/ AND isset($id)) {


			unlink('../' . IMAGE_COMMENT . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_comment' . $this->prefix . ' 
										WHERE comment_id ="' . $this->db->real_escape_string($id) . '" ');


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
		$over = $this->db->query('SELECT * FROM be_comment' . $this->prefix . ' 
									WHERE comment_id ="' . $this->db->real_escape_string($id) . '" ');
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
						<p class="form-control-static"><?php echo htmlspecialchars($zobraz['comment_name']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars($zobraz['comment_email']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars($zobraz['comment_text']); ?></p>  
					    </div>
					</div>




					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['comment_date']); ?></p>
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
$comment = new comment($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>