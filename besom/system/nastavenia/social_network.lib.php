<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class social {

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

	public function socialTitul ($stav = '4') {

		if ($stav == '1') {
			echo $this->lg[$this->flag]['socialTitul'] . ' -> ' . $this->lg[$this->flag]['socialFan'];
		}
		elseif ($stav == '2') {
			echo $this->lg[$this->flag]['socialTitul'] . ' -> ' . $this->lg[$this->flag]['socialShare'];
		}
		elseif ($stav == '3') {
			echo $this->lg[$this->flag]['socialTitul'] . ' -> ' . $this->lg[$this->flag]['socialLike'];
		}
		else {
			echo $this->lg[$this->flag]['socialTitul'];
		}

	}


	public function socialTab ($typ) {
		$query = $this->db->query('SELECT * FROM be_social' . $this->prefix . ' 
										WHERE social_typ ="' . $this->db->real_escape_string(intval($typ)) . '" 
									ORDER BY social_zorad ASC');
		if ($query->num_rows != FALSE) {
			# OK

			$soc = array();
			while ($tlac = $query->fetch_assoc()) {

				$id = $tlac['social_id'];
				$naz = $tlac['social_nazov'];
				$img = $tlac['social_img'];
				$url = $tlac['social_url'];
				$target = $tlac['social_target'];
				$typ = $tlac['social_typ'];
				$stav = $tlac['social_stav'];
				$zorad = $tlac['social_zorad'];
				$app = $tlac['social_app'];

				$soc[$id][$naz][$img][$url][$target][$typ][$stav][$zorad][$app][] = $tlac;
			}
			 
		}
		else {
			$soc =0;
		}
		return $soc;
	}

	public function order ($order, $typ = '1') {
		
		# $order = 1;  //toto +1
		# $id = 1;     // id cislo

		#POCET ROVNAKYCH $order
		$pocet1 = $this->db->query('SELECT social_id, social_zorad FROM be_social' . $this->prefix . ' 
										WHERE social_zorad ="' . $this->db->real_escape_string($order) . '" AND 
											social_typ ="' . $this->db->real_escape_string(intval($typ)) . '"  ');
		if ($pocet1->num_rows == '1') {
			
			$cislo1 = $pocet1->fetch_assoc();

			#pocet2  order+1
			$pocet2 = $this->db->query('SELECT social_id, social_zorad FROM be_social' . $this->prefix . ' 
											WHERE social_zorad ="' . $this->db->real_escape_string($order+1) . '"  AND 
												social_typ ="' . $this->db->real_escape_string(intval($typ)) . '"  ');
			if ($pocet2->num_rows == '1') {
				
				$cislo2 = $pocet2->fetch_assoc();

				//echo '1 ---- ' . $cislo1['social_zorad'] . ' a ' . $cislo2['social_zorad'];
				$cis1 = $cislo1['social_zorad'];
				$cis2 = $cislo2['social_zorad'];

				#PRIPOCITANIE
				$query1 = $this->db->query('UPDATE be_social' . $this->prefix . ' SET 
												social_zorad ="' . $this->db->real_escape_string($cis2) . '" 
											WHERE social_id ="' . $this->db->real_escape_string($cislo1['social_id']) . '" AND 
													social_typ ="' . $this->db->real_escape_string(intval($typ)) . '" ');
				# ODCITANIE
				$query2 = $this->db->query('UPDATE be_social' . $this->prefix . ' SET 
												social_zorad ="' . $this->db->real_escape_string($cis1) . '" 
											WHERE social_id ="' . $this->db->real_escape_string($cislo2['social_id']) . '" AND 
													social_typ ="' . $this->db->real_escape_string(intval($typ)) . '" ');

				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();

			}
			else {
				//die('chyba2');
				setcookie('odpoved','1', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				exit();
			}

		}
		else {
			//die('chyba1');
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			exit();
		}

	}

	public function socialActive ($id) {
		$query = $this->db->query('SELECT social_id FROM be_social' . $this->prefix . ' 
							WHERE social_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_social' . $this->prefix . ' SET 
										social_stav =1 WHERE social_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function socialDeactive ($id) {
		$query = $this->db->query('SELECT social_id FROM be_social' . $this->prefix . ' 
							WHERE social_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_social' . $this->prefix . ' SET 
										social_stav =2 WHERE social_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function socialAdd () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['urlAdresa']) AND isset($_POST['stav']) AND isset($_POST['typ']) AND isset($_POST['target']) AND isset($_FILES["image"])) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$urlAdresa = (isset($_POST['urlAdresa'])) ? $_POST['urlAdresa'] : '';
			$idApp = (isset($_POST['idApp'])) ? $_POST['idApp'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';
			$target = (isset($_POST['target'])) ? $_POST['target'] : '';

			/*
			echo $nazov . '<br>';
			echo $urlAdresa . '<br>';
			echo $idApp . '<br>';
			echo $stav . '<br>';
			echo $typ . '<br>';
			echo $target . '<br>';
			echo $_FILES["image"]['name'] . '<br>';
			*/

			if (!empty($nazov) AND !empty($urlAdresa) AND !empty($stav) AND !empty($typ) AND !empty($target) AND !empty($_FILES["image"])) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				//define('UPLOAD_DIR_MICRO', '../../galeria/micro/'); //horny panel mini
				//define('UPLOAD_DIR_MINI', '../../galeria/mini/'); //horny panel mini
				//define('UPLOAD_DIR_NORMAL', '../../galeria/male/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/social/'); // original
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


				 if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
				    {

				      $change='<div class="msgdiv">Obrazok ma zly format.</div> ';
				      $errors=1;
				    }
				    else
				    {

						$size = filesize($_FILES['image']['tmp_name'])/ 1000;

						list($width,$height)=getimagesize($uploadedfile);
						$files = UPLOAD_DIR_ORIGINAL . $last_id . '.png';


						if ($size > MAX_SIZE*1024) {
							$change='<div class="msgdiv">Maximalna velkost je ' . MAX_SIZE . '</div>';
							$errors=1;
						}

						$size = $size /1000;

						if ($extension=="jpg" || $extension=="jpeg" ) {

							$files = UPLOAD_DIR_ORIGINAL . $last_id . '.jpg';

							$uploadedfile = $_FILES['image']['tmp_name'];

							$tmp = imagecreatetruecolor($width,$height);
			                $src = imagecreatefromjpeg($uploadedfile); 
			                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			                $con = imagejpeg($tmp, $files);

			                $kk =1;

						}
						elseif ($extension=="png") {
							
							$files = UPLOAD_DIR_ORIGINAL . $last_id . '.png';

							$uploadedfile = $_FILES['image']['tmp_name'];

							$tmp = imagecreatetruecolor($width,$height);
			                $src = imagecreatefrompng($uploadedfile);
			                imagealphablending($tmp, false);
			                imagesavealpha($tmp,true);
			                $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			                imagefilledrectangle($tmp, 0, 0, $width, $height, $transparent); 
			                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			                $con = imagepng($tmp, $files);

			                $kk =2;

						}
						elseif ($extension == "gif") {

							$files = UPLOAD_DIR_ORIGINAL . $last_id . '.gif';
							
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromgif($uploadedfile);

							$tmp = imagecreatetruecolor($width,$height);
							imagealphablending($tmp, false);
			                imagesavealpha($tmp,true);
			                $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			                imagefilledrectangle($tmp, 0, 0, $width, $height, $transparent); 
			                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			                $src = imagecreatefromgif($uploadedfile);
			                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			                $con = imagegif($tmp, $files);

			                $kk =3;
						}
						else {
							
							//$src = imagecreatefromgif($uploadedfile);
							$errors =1;
						}

						$ass = array('1' => 'jpg',
									 '2' => 'png',
									 '3' => 'gif');
						$filenam = $last_id . '.' . $ass[$kk];

				//$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
				//$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
				//$micro = UPLOAD_DIR_MICRO . $last_id . '.jpg';   //mini 
				//--------------------------------------------------------------------------------------
				//------------------------ nove ------------------
				//include 'component/resize-class.php';

				// *** 1) Initialise / load image
				  //$resizeObj = new resize($filenam);

				  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
				  //$resizeObj -> resizeImage(420, 500, 'crop');

				  // *** 3) Save image
				  //$resizeObj -> saveImage($normal , 70);


				//===-=-=-=-=-=

				 //$resize = new resize($filenam);
				  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
				  //$resize -> resizeImage(200, 280, 'crop');

				  // *** 3) Save image
				  //$resize -> saveImage($mini , 70);
				//----------------------------------------------------------------------------------


				  //===-=-=-=-=-=

				  //$resize = new resize($filenam);
				  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
				  //$resize -> resizeImage(150, 210, 'crop');

				  // *** 3) Save image
				  //$resize -> saveImage($micro , 20);
				//----------------------------------------------------------------------------------

				imagedestroy($src);  //original
				imagedestroy($tmp);  //dd
				//imagedestroy($normal);
				//imagedestroy($mini);
				//imagedestroy($micro);
				imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################

			}

				}
				else {
					$errors =1;
				}
				if ($errors == '0') {

					$por = $this->db->query('SELECT social_zorad FROM be_social' . $this->prefix . '
												WHERE social_typ ="' . $this->db->real_escape_string(intval($typ)) . '" 
												ORDER BY social_zorad DESC LIMIT 1');
					if ($por->num_rows != FALSE) {
						
						$pora = $por->fetch_assoc();

						$zorad = $pora['social_zorad']+1;

					}
					else {
						$zorad = '2';
					}

					# ULOZ
					$uloz = $this->db->query('INSERT INTO be_social' . $this->prefix . ' 
											(social_id, social_nazov, social_img, social_url, social_target, social_typ, social_stav, social_app, social_zorad)  
											VALUES (NULL,
													"' . $this->db->real_escape_string($nazov) . '",
													"' . $this->db->real_escape_string($filenam) . '",
													"' . $this->db->real_escape_string($urlAdresa) . '",
													"' . $this->db->real_escape_string($target) . '",
													"' . $this->db->real_escape_string($typ) . '",
													"' . $this->db->real_escape_string($stav) . '",
													"' . $this->db->real_escape_string($idApp) . '",
													"' . $this->db->real_escape_string($zorad) . '") ');

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
			<form class="form-horizontal" role="form" method="post" action="#" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Nazov" name="nazov">
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Url Adresa</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="http://facebook.com/besom" name="urlAdresa">
				    	<span class="text-info">Pre automaticky generovanu adresu pouzite #</span>
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Id aplikacie</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="101230014" name="idApp">
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
				    <label for="inputEmail3" class="col-sm-2 control-label">Typ</label>
				    <div class="col-sm-6">
						<select class="form-control" name="typ">
							<option name="1" value="1" selected="selected">Fan page</option>
							<option name="2" value="2">Share</option>
							<option name="3" value="3">like</option>
						</select>
					</div>	
				</div>

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Zobraz</label>
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
					<label for="inputEmail3" class="col-sm-2 control-label">Zobraz</label>
					    <div class="col-sm-6">
					    <div class="radio">
							<label>
						    	<input type="radio" name="stav" id="optionsRadios1" value="1" checked="checked">
						    Aktivna
						  	</label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="stav" id="optionsRadios2" value="2">
						    Neaktivna
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


	public function socialEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['urlAdresa']) AND isset($_POST['stav']) AND isset($_POST['typ']) AND isset($_POST['target']) AND isset($_FILES["image"])) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$urlAdresa = (isset($_POST['urlAdresa'])) ? $_POST['urlAdresa'] : '';
			$idApp = (isset($_POST['idApp'])) ? $_POST['idApp'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';
			$target = (isset($_POST['target'])) ? $_POST['target'] : '';

			/*
			echo $nazov . '<br>';
			echo $urlAdresa . '<br>';
			echo $idApp . '<br>';
			echo $stav . '<br>';
			echo $typ . '<br>';
			echo $target . '<br>';
			echo $_FILES["image"]['name'] . '<br>';
			*/

			if (!empty($nazov) AND !empty($urlAdresa) AND !empty($stav) AND !empty($typ) AND !empty($target) ) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				//define('UPLOAD_DIR_MICRO', '../../galeria/micro/'); //horny panel mini
				//define('UPLOAD_DIR_MINI', '../../galeria/mini/'); //horny panel mini
				//define('UPLOAD_DIR_NORMAL', '../../galeria/male/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/social/'); // original
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

						list($width,$height)=getimagesize($uploadedfile);
						$files = UPLOAD_DIR_ORIGINAL . $last_id . '.png';



						if ($size > MAX_SIZE*1024) {
							$change='<div class="msgdiv">Maximalna velkost je ' . MAX_SIZE . '</div>';
							$errors=1;
						}

						$size = $size /1000;

						if ($extension=="jpg" || $extension=="jpeg" ) {

							$files = UPLOAD_DIR_ORIGINAL . $last_id . '.jpg';

							$uploadedfile = $_FILES['image']['tmp_name'];

							$tmp = imagecreatetruecolor($width,$height);
			                $src = imagecreatefromjpeg($uploadedfile); 
			                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			                $con = imagejpeg($tmp, $files);

			                $kk =1;

						}
						elseif ($extension=="png") {
							
							$files = UPLOAD_DIR_ORIGINAL . $last_id . '.png';

							$uploadedfile = $_FILES['image']['tmp_name'];

							$tmp = imagecreatetruecolor($width,$height);
			                $src = imagecreatefrompng($uploadedfile);
			                imagealphablending($tmp, false);
			                imagesavealpha($tmp,true);
			                $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			                imagefilledrectangle($tmp, 0, 0, $width, $height, $transparent); 
			                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			                $con = imagepng($tmp, $files);

			                $kk =2;

						}
						elseif ($extension == "gif") {

							$files = UPLOAD_DIR_ORIGINAL . $last_id . '.gif';
							
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromgif($uploadedfile);

							$tmp = imagecreatetruecolor($width,$height);
			                $src = imagecreatefromgif($uploadedfile);
							imagealphablending($tmp, false);
			                imagesavealpha($tmp,true);
			                $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			                imagefilledrectangle($tmp, 0, 0, $width, $height, $transparent); 
			                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			                $con = imagegif($tmp, $files);

			                $kk =3;
						}
						else {
							
							//$src = imagecreatefromgif($uploadedfile);
							$errors =1;
						}

						$ass = array('1' => 'jpg',
									 '2' => 'png',
									 '3' => 'gif');
						$filenam = $last_id . '.' . $ass[$kk];

						//$src = imagecreatefromjpg, gif, png, jpeg($uploadedfile); ----- original suboru

						//echo $scr;

						//list($width,$height)=getimagesize($uploadedfile);

	


						//$filenam = UPLOAD_DIR_ORIGINAL . $_FILES['obrazokUloz']['name'];  //original
						//$filenam = UPLOAD_DIR_ORIGINAL . $user_id . '.' . $extension;  //original
						//$filenam = UPLOAD_DIR_ORIGINAL . $last_id . '.jpg';  //original



						//imagejpeg($src,$filenam,90);
						

						/*$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
						//$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
						//$micro = UPLOAD_DIR_MICRO . $last_id . '.jpg';   //mini 
						//--------------------------------------------------------------------------------------
						//------------------------ nove ------------------
						//include 'component/resize-class.php';

						// *** 1) Initialise / load image
						  $resizeObj = new resize($files);

						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
						  $resizeObj -> resizeImage(20, 20, 'crop');

						  // *** 3) Save image
						  $resizeObj -> saveImage('../upload/social/test.png' , 70);*/


						//===-=-=-=-=-=

						 //$resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  //$resize -> resizeImage(200, 280, 'crop');

						  // *** 3) Save image
						  //$resize -> saveImage($mini , 70);
						//----------------------------------------------------------------------------------


						  //===-=-=-=-=-=

						  //$resize = new resize($filenam);
						  // *** 2) Resize image (options: exact, portrait, landscape, auto, crop) 800*540
						  //$resize -> resizeImage(150, 210, 'crop');

						  // *** 3) Save image
						  //$resize -> saveImage($micro , 20);
						//----------------------------------------------------------------------------------

						imagedestroy($src);  //original
						imagedestroy($tmp);  //original
						//imagedestroy($normal);
						//imagedestroy($mini);
						//imagedestroy($micro);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################

					} 
				}
				else {
					$errors = 1;
				}
		
				if ($errors == '0') {
							

					# ULOZ
					if ($image) {
						# S IMAGE
						$uloz = $this->db->query('UPDATE be_social' . $this->prefix . ' SET 
													social_nazov ="' . $this->db->real_escape_string($nazov) . '",
													social_img ="' . $this->db->real_escape_string($filenam) . '",
													social_url ="' . $this->db->real_escape_string($urlAdresa) . '",
													social_target ="' . $this->db->real_escape_string($target) . '",
													social_typ ="' . $this->db->real_escape_string($typ) . '",
													social_stav ="' . $this->db->real_escape_string($stav) . '",
													social_app ="' . $this->db->real_escape_string($idApp) . '"
												WHERE social_id ="' . $this->db->real_escape_string($_GET['edit']) . '" ');

						
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						//header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						exit();
					}
					else {
						$uloz = $this->db->query('UPDATE be_social' . $this->prefix . ' SET 
													social_nazov ="' . $this->db->real_escape_string($nazov) . '",
													social_url ="' . $this->db->real_escape_string($urlAdresa) . '",
													social_target ="' . $this->db->real_escape_string($target) . '",
													social_typ ="' . $this->db->real_escape_string($typ) . '",
													social_stav ="' . $this->db->real_escape_string($stav) . '",
													social_app ="' . $this->db->real_escape_string($idApp) . '"
												WHERE social_id ="' . $this->db->real_escape_string($_GET['edit']) . '" ');

						
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
						exit();
					}
				}
				else {
					setcookie('odpoved','1', time()+1);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					exit();
				}

			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				exit();
			}
			
		}
		# END POST DATA

		$over = $this->db->query('SELECT * FROM be_social' . $this->prefix . ' 
									WHERE social_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
	<div class="panel panel-default">
		<div class="panel-heading">
		   	<h3 class="panel-title">Zmenit</h3>
		</div>
		<div class="panel-body">
				
				<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['social_nazov']); ?>" name="nazov">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Url Adresa</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['social_url']); ?>" name="urlAdresa">
					    	<span class="text-info">Pre automaticky generovanu adresu pouzite #</span>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Id aplikacie</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['social_app']); ?>" name="idApp">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
						    <input type="file" id="exampleInputFile" name="image">
		    				<p class="help-block">Pozor ak vyberiete obrazok, tak sa zmeni predosli!!</p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Typ</label>
					    <div class="col-sm-6">
							<select class="form-control" name="typ">
								<option name="1" value="1" <?php if ($zobraz['social_typ'] == '1') { echo 'selected="selected"'; } ?> >Fan page</option>
								<option name="2" value="2" <?php if ($zobraz['social_typ'] == '2') { echo 'selected="selected"'; } ?> >Share</option>
								<option name="3" value="3" <?php if ($zobraz['social_typ'] == '3') { echo 'selected="selected"'; } ?> >like</option>
							</select>
						</div>	
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Zobraz</label>
					    <div class="col-sm-6">
							<select class="form-control" name="target">
								<option name="_blank" <?php if ($zobraz['social_target'] == '_blank') { echo 'selectde="selected"'; } ?> >_blank</option>
								<option name="_self" <?php if ($zobraz['social_target'] == '_self') { echo 'selectde="selected"'; } ?> >_self</option>
								<option name="_parent" <?php if ($zobraz['social_target'] == '_parent') { echo 'selectde="selected"'; } ?> >_parent</option>
								<option name="_top" <?php if ($zobraz['social_target'] == '_top') { echo 'selectde="selected"'; } ?> >_top</option>
							</select>
						</div>	
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Zobraz</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="1"  <?php if ($zobraz['social_stav'] == '1') { echo 'checked="checked"'; } ?> >
							    Aktivna
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="2" <?php if ($zobraz['social_stav'] == '2') { echo 'checked="checked"'; } ?> >
							    Neaktivna
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

	public function socialDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['ikona']) AND isset($id)) {


			unlink('../' . IMAGE_SOCIAL . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_social' . $this->prefix . ' 
										WHERE social_id ="' . $this->db->real_escape_string($id) . '" ');


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST



		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_social' . $this->prefix . ' 
									WHERE social_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
			<div class="bs-example" style="background: #FFFFFF; padding: 0px 0px 5px 0px; border: 1px solid #CDCDCD; margin-bottom: 20px;">
				<h3 style="margin: 0px; padding: 10px 0px 2px 5px; background: #E5E5E5; color: #e60e0e; margin-bottom: 5px;">Vymazat</h3>

				<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">
				<fieldset disabled>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['social_nazov']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Url Adresa</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['social_url']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Id aplikacie</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['social_app']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
						    <img src="<?php echo ADRESA . PRIECINOK . IMAGE_SOCIAL . htmlspecialchars($zobraz['social_img']); ?>" alt="<?php echo htmlspecialchars($zobraz['social_nazov']); ?>" class="img-thumbnail" width="70px">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Typ</label>
					    <div class="col-sm-6">
							<select class="form-control" name="typ">
								<option name="1" value="1" <?php if ($zobraz['social_typ'] == '1') { echo 'selected="selected"'; } ?> >Fan page</option>
								<option name="2" value="2" <?php if ($zobraz['social_typ'] == '2') { echo 'selected="selected"'; } ?> >Share</option>
								<option name="3" value="3" <?php if ($zobraz['social_typ'] == '3') { echo 'selected="selected"'; } ?> >like</option>
							</select>
						</div>	
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Zobraz</label>
					    <div class="col-sm-6">
							<select class="form-control" name="target">
								<option name="_blank" <?php if ($zobraz['social_target'] == '_blank') { echo 'selectde="selected"'; } ?> >_blank</option>
								<option name="_self" <?php if ($zobraz['social_target'] == '_self') { echo 'selectde="selected"'; } ?> >_self</option>
								<option name="_parent" <?php if ($zobraz['social_target'] == '_parent') { echo 'selectde="selected"'; } ?> >_parent</option>
								<option name="_top" <?php if ($zobraz['social_target'] == '_top') { echo 'selectde="selected"'; } ?> >_top</option>
							</select>
						</div>	
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Zobraz</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="1"  <?php if ($zobraz['social_stav'] == '1') { echo 'checked="checked"'; } ?> >
							    Aktivna
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="2" <?php if ($zobraz['social_stav'] == '2') { echo 'checked="checked"'; } ?> >
							    Neaktivna
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

					<input type="hidden" name="ikona" value="<?php echo htmlspecialchars($zobraz['social_img']); ?>">		
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
$social = new social($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>