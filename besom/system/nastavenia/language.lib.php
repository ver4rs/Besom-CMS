<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class language {

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


	public function language () {
		$query = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' ORDER BY jazyk_id DESC');
		if ($query->num_rows != FALSE) {
			# OK

			$poz = array();
			while ($tlac = $query->fetch_assoc()) {

				$id = $tlac['jazyk_id'];
				$naz = $tlac['jazyk_nazov'];
				$skratka = $tlac['jazyk_skratka'];
				$ikona = $tlac['jazyk_ikona'];
				$default = $tlac['jazyk_default'];
				$short = $tlac['jazyk_short'];

				$poz[$id][$naz][$skratka][$ikona][$default][$short][] = $tlac;
			}
			return $poz;
			 
		}
	}

	public function languageAdd () {


		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['skratka']) AND isset($_POST['short']) AND isset($_FILES["image"])) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$skratka = (isset($_POST['skratka'])) ? $_POST['skratka'] : '';
			$short = (isset($_POST['short'])) ? $_POST['short'] : '';
			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			if (!empty($nazov) AND !empty($skratka) AND !empty($short) AND !empty($_FILES["image"])) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				//define('UPLOAD_DIR_MICRO', '../../galeria/micro/'); //horny panel mini
				//define('UPLOAD_DIR_MINI', '../../galeria/mini/'); //horny panel mini
				//define('UPLOAD_DIR_NORMAL', '../../galeria/male/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/lang/'); // original
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


				//if ($image) {

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


				if ($size > MAX_SIZE*1024)
				{
				  $change='<div class="msgdiv">Maximalna velkost je ' . MAX_SIZE . '</div>';
				  $errors=1;
				}
				$size = $size /1000;

				if($extension=="jpg" || $extension=="jpeg" )
				{
				$uploadedfile = $_FILES['image']['tmp_name'];
				$src = imagecreatefromjpeg($uploadedfile);

				}
				else if($extension=="png")
				{
				$uploadedfile = $_FILES['image']['tmp_name'];
				$src = imagecreatefrompng($uploadedfile);

				}
				else
				{
				$src = imagecreatefromgif($uploadedfile);
				}

				//$src = imagecreatefromjpg, gif, png, jpeg($uploadedfile); ----- original suboru

				//echo $scr;

				list($height,$width)=getimagesize($uploadedfile);

				//$filenam = UPLOAD_DIR_ORIGINAL . $_FILES['obrazokUloz']['name'];  //original
				//$filenam = UPLOAD_DIR_ORIGINAL . $user_id . '.' . $extension;  //original
				$filenam = UPLOAD_DIR_ORIGINAL . $last_id . '.jpg';  //original



				imagejpeg($src,$filenam,60);

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
				//imagedestroy($normal);
				//imagedestroy($mini);
				//imagedestroy($micro);
				imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################



				}
				if ($errors == '0') {
					# ULOZ
					$uloz = $this->db->query('INSERT INTO be_jazyk' . $this->prefix . ' 
											(jazyk_id, jazyk_nazov, jazyk_skratka, jazyk_short, jazyk_ikona, jazyk_default) 
											VALUES (NULL,
													"' . $this->db->real_escape_string($nazov) . '",
													"' . $this->db->real_escape_string($skratka) . '",
													"' . $this->db->real_escape_string($short) . '",
													"' . $this->db->real_escape_string(IMAGE_LANG . $last_id . '.jpg') . '",
													"' . $this->db->real_escape_string('0') . '") ');

					
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
	<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #278c38; margin-bottom: 5px;">Pridat</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
	    	<h3 class="panel-title">Pridat</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="post" action="#" enctype="multipart/form-data">

				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="Slovak" name="nazov">
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Skratka</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="SK" name="skratka">
				    </div>
				</div>


				<div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">Short</label>
				    <div class="col-sm-6">
				    	<input type="text" class="form-control" id="inputText3" placeholder="sk" name="short">
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
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success btn-sm" name="submit"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</button>
					</div>
				</div>	
			</form>
		</div>
	</div>	
		<?php
	}


	public function languageEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['skratka']) AND isset($_POST['short']) AND isset($_FILES['image'])) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$skratka = (isset($_POST['skratka'])) ? $_POST['skratka'] : '';
			$short = (isset($_POST['short'])) ? $_POST['short'] : '';
			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			if (!empty($nazov) AND !empty($skratka) AND !empty($short) AND !empty($_FILES['image'])) {
				


				#-----------------------------------------------------------------------------------------------
				error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				//define('UPLOAD_DIR_MICRO', '../../galeria/micro/'); //horny panel mini
				//define('UPLOAD_DIR_MINI', '../../galeria/mini/'); //horny panel mini
				//define('UPLOAD_DIR_NORMAL', '../../galeria/male/'); // na komentare stredny
				define('UPLOAD_DIR_ORIGINAL', '../upload/lang/'); // original
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

						if ($extension=="jpg" || $extension=="jpeg" ) {
							
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);

						}
						elseif ($extension=="png") {
							
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);

						}
						else {
							
							$src = imagecreatefromgif($uploadedfile);
						}

						//$src = imagecreatefromjpg, gif, png, jpeg($uploadedfile); ----- original suboru

						//echo $scr;

						list($width,$height)=getimagesize($uploadedfile);

						//$filenam = UPLOAD_DIR_ORIGINAL . $_FILES['obrazokUloz']['name'];  //original
						//$filenam = UPLOAD_DIR_ORIGINAL . $user_id . '.' . $extension;  //original
						$filenam = UPLOAD_DIR_ORIGINAL . $last_id . '.jpg';  //original



						imagejpeg($src,$filenam,70);

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
		
				if ($errors == '0') {
					# ULOZ
					if ($image) {
						# S IMAGE
						$uloz = $this->db->query('UPDATE be_jazyk' . $this->prefix . ' SET 
													jazyk_nazov ="' . $this->db->real_escape_string($nazov) . '",
													jazyk_skratka ="' . $this->db->real_escape_string($skratka) . '",
													jazyk_short ="' . $this->db->real_escape_string($short) . '",
													jazyk_ikona"' . $this->db->real_escape_string(IMAGE_LANG . $last_id . '.jpg') . '"
												WHERE jazyk_id ="' . $this->db->real_escape_string($_GET['edit']) . '" ');

						
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						//header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
						exit();
					}
					else {
						$uloz = $this->db->query('UPDATE be_jazyk' . $this->prefix . ' SET 
													jazyk_nazov ="' . $this->db->real_escape_string($nazov) . '",
													jazyk_skratka ="' . $this->db->real_escape_string($skratka) . '",
													jazyk_short ="' . $this->db->real_escape_string($short) . '" 
												WHERE jazyk_id ="' . $this->db->real_escape_string($_GET['edit']) . '" ');

						
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

		$over = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' 
									WHERE jazyk_id ="' . $this->db->real_escape_string($id) . '" ');
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
				<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['jazyk_nazov']); ?>" name="nazov">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Skratka</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['jazyk_skratka']); ?>" name="skratka">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Short</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['jazyk_short']); ?>" name="short">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
						    <input type="file" id="exampleInputFile" name="image">
		    				<p class="help-block">POZOR! Ak vyberiete obrazok, nahradi sa novym.</p>
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

	public function languageDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['ikona']) AND isset($id)) {


			unlink('../' . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_jazyk' . $this->prefix . ' 
										WHERE jazyk_id ="' . $this->db->real_escape_string($id) . '" ');


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST



		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_jazyk' . $this->prefix . ' 
									WHERE jazyk_id ="' . $this->db->real_escape_string($id) . '" ');
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
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['jazyk_nazov']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Skratka</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['jazyk_skratka']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Short</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['jazyk_short']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
						    <img src="<?php echo ADRESA . PRIECINOK . htmlspecialchars($zobraz['jazyk_ikona']); ?>" alt="<?php echo htmlspecialchars($zobraz['jazyk_nazov']); ?>" class="img-thumbnail" width="70px">
					    </div>
					</div>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>
					<input type="hidden" name="ikona" value="<?php echo htmlspecialchars($zobraz['jazyk_ikona']); ?>">	
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


}


//$language = new language($this->db, $this->prefix, $this->lang);
$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>