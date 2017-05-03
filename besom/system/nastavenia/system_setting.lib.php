<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class system1 {

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


	public function systemForm () {
		$query = $this->db->query('SELECT * FROM be_system' . $this->prefix . ' ORDER BY system_id ASC LIMIT 1');
		if ($query->num_rows != FALSE) {
			# OK
			$tlac = $query->fetch_assoc();
			
			return $tlac;
		}
	}

	public function systemUpdateSetting ($id1) {

		# POST
		if (isset($_POST['submit1']) AND $_POST['submit1'] == 'submit11') {
			
			if (isset($_POST['title']) AND isset($_POST['keywords']) AND isset($_POST['description']) AND isset($_POST['nadpis']) AND isset($_POST['urlAdresa']) AND isset($_POST['analitic']) AND isset($_POST['spod']) AND isset($_POST['id']) /* AND isset($_FILES["image"])*/) {


				$title = (isset($_POST['title'])) ? $_POST['title'] : '';
				$keywords = (isset($_POST['keywords'])) ? $_POST['keywords'] : '';
				$description = (isset($_POST['description'])) ? $_POST['description'] : '';
				$nadpis = (isset($_POST['nadpis'])) ? $_POST['nadpis'] : '';
				$urlAdresa = (isset($_POST['urlAdresa'])) ? $_POST['urlAdresa'] : '';
				$analitic = (isset($_POST['analitic'])) ? $_POST['analitic'] : '';
				$spod = (isset($_POST['spod'])) ? $_POST['spod'] : '';
				$id = (isset($_POST['id'])) ? $_POST['id'] : '';

				/*echo $title . '<br>';
				echo $keywords . '<br>';
				echo $description . '<br>';
				echo $nadpis . '<br>';
				echo $urlAdresa . '<br>';
				echo $analitic . '<br>';
				echo $spod . '<br>';
				echo $id . '<br>';
				echo $id1 . '<br>';
				echo $_FILES["image"]['name'] . '<br>';
				die('END');*/
			

				if (!empty($title) AND !empty($keywords) AND !empty($description) AND !empty($nadpis) AND !empty($urlAdresa) AND !empty($analitic) AND !empty($spod) AND !empty($id) AND $id == $id1) {
					


					#-----------------------------------------------------------------------------------------------
					error_reporting(0);

					$last_id = md5(date('YmdHis'));

					$change="";
					$abc="";

					//define('UPLOAD_DIR_MICRO', '../../galeria/micro/'); //horny panel mini
					//define('UPLOAD_DIR_MINI', '../../galeria/mini/'); //horny panel mini
					//define('UPLOAD_DIR_NORMAL', '../../galeria/male/'); // na komentare stredny
					define('UPLOAD_DIR_ORIGINAL', '../upload/system/'); // original
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



							imagejpeg($src,$filenam,90);

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

						/*$title = (isset($_POST['title'])) ? $_POST['title'] : '';
						$keywords = (isset($_POST['keywords'])) ? $_POST['keywords'] : '';
						$description = (isset($_POST['description'])) ? $_POST['description'] : '';
						$nadpis = (isset($_POST['nadpis'])) ? $_POST['nadpis'] : '';
						$urlAdresa = (isset($_POST['urlAdresa'])) ? $_POST['urlAdresa'] : '';
						$analitic = (isset($_POST['analitic'])) ? $_POST['analitic'] : '';
						$spod = (isset($_POST['spod'])) ? $_POST['spod'] : '';
						$id = (isset($_POST['id'])) ? $_POST['id'] : '';	*/

						$spod = str_replace('\"', '', $spod);						

						# ULOZ
						if ($image) {
							# S IMAGE
							$uloz = $this->db->query('UPDATE be_system' . $this->prefix . ' SET 
														system_title ="' . $this->db->real_escape_string($title) . '",
														system_keywords ="' . $this->db->real_escape_string($keywords) . '",
														system_description ="' . $this->db->real_escape_string($description) . '",
														system_nadpis ="' . $this->db->real_escape_string($nadpis) . '",
														system_url ="' . $this->db->real_escape_string($urlAdresa) . '",
														system_analitic ="' . $this->db->real_escape_string($analitic) . '",
														system_spod ="' . $this->db->real_escape_string($spod) . '",
														system_img ="' . $this->db->real_escape_string($last_id . '.jpg') . '"
													WHERE system_id ="' . $this->db->real_escape_string($id) . '" ');

							
							setcookie('odpoved','2', time()+1);
							//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
							//header("Location: " . $_SERVER['HTTP_REFERER']);
							//header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
							header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
							exit();
						}
						else {
							$uloz = $this->db->query('UPDATE be_system' . $this->prefix . ' SET 
														system_title ="' . $this->db->real_escape_string($title) . '",
														system_keywords ="' . $this->db->real_escape_string($keywords) . '",
														system_description ="' . $this->db->real_escape_string($description) . '",
														system_nadpis ="' . $this->db->real_escape_string($nadpis) . '",
														system_url ="' . $this->db->real_escape_string($urlAdresa) . '",
														system_analitic ="' . $this->db->real_escape_string($analitic) . '",
														system_spod ="' . $this->db->real_escape_string($spod) . '"
													WHERE system_id ="' . $this->db->real_escape_string($id) . '" ');

							
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

	}


public function systemUpdateAdmin ($id1) {

		# POST
		if (isset($_POST['submit2']) AND $_POST['submit2'] == 'submit22') {
			
			if (isset($_POST['avatar']) AND isset($_POST['meno']) AND isset($_POST['priezvisko']) AND isset($_POST['facebook']) AND isset($_POST['twitter']) AND isset($_POST['email']) AND isset($_POST['adresa']) AND isset($_POST['mesto']) AND isset($_POST['region']) AND isset($_POST['stat']) AND isset($_POST['psc']) AND isset($_POST['telefon']) AND isset($_POST['ico']) AND isset($_POST['dic']) AND isset($_POST['id']) /* AND isset($_FILES["image"])*/) {


				$avatar = (isset($_POST['avatar'])) ? $_POST['avatar'] : '';
				$meno = (isset($_POST['meno'])) ? $_POST['meno'] : '';
				$priezvisko = (isset($_POST['priezvisko'])) ? $_POST['priezvisko'] : '';
				$facebook = (isset($_POST['facebook'])) ? $_POST['facebook'] : '';
				$twitter = (isset($_POST['twitter'])) ? $_POST['twitter'] : '';
				$email = (isset($_POST['email'])) ? $_POST['email'] : '';
				$adresa = (isset($_POST['adresa'])) ? $_POST['adresa'] : '';
				$mesto = (isset($_POST['mesto'])) ? $_POST['mesto'] : '';
				$region = (isset($_POST['region'])) ? $_POST['region'] : '';
				$stat = (isset($_POST['stat'])) ? $_POST['stat'] : '';
				$psc = (isset($_POST['psc'])) ? $_POST['psc'] : '';
				$telefon = (isset($_POST['telefon'])) ? $_POST['telefon'] : '';
				$ico = (isset($_POST['ico'])) ? $_POST['ico'] : '';
				$dic = (isset($_POST['dic'])) ? $_POST['dic'] : '';
				$id = (isset($_POST['id'])) ? $_POST['id'] : '';

				/*echo $title . '<br>';
				echo $keywords . '<br>';
				echo $description . '<br>';
				echo $nadpis . '<br>';
				echo $urlAdresa . '<br>';
				echo $analitic . '<br>';
				echo $spod . '<br>';
				echo $id . '<br>';
				echo $id1 . '<br>';
				echo $_FILES["image"]['name'] . '<br>';
				die('END');*/
			

				if (!empty($avatar) AND !empty($meno) AND !empty($priezvisko) AND !empty($facebook) AND !empty($twitter) AND !empty($email) AND !empty($adresa) AND !empty($mesto) AND !empty($region) AND !empty($stat) AND !empty($psc) AND !empty($telefon) AND !empty($ico) AND !empty($dic) AND !empty($id) AND $id == $id1) {
										

						# ULOZ
					$uloz = $this->db->query('UPDATE be_system' . $this->prefix . ' SET 
												system_admin_meno ="' . $this->db->real_escape_string($avatar) . '",
												system_admin_firstmeno ="' . $this->db->real_escape_string($meno) . '",
												system_admin_lastmeno ="' . $this->db->real_escape_string($priezvisko) . '",
												system_admin_facebook ="' . $this->db->real_escape_string($facebook) . '",
												system_admin_twitter ="' . $this->db->real_escape_string($twitter) . '",
												system_admin_email ="' . $this->db->real_escape_string($email) . '",
												system_admin_adresa ="' . $this->db->real_escape_string($adresa) . '",
												system_admin_mesto ="' . $this->db->real_escape_string($mesto) . '",
												system_admin_region ="' . $this->db->real_escape_string($region) . '",
												system_admin_stat ="' . $this->db->real_escape_string($stat) . '",
												system_admin_psc ="' . $this->db->real_escape_string($psc) . '",
												system_admin_tel ="' . $this->db->real_escape_string($telefon) . '",
												system_admin_ico ="' . $this->db->real_escape_string($ico) . '",
												system_admin_dic ="' . $this->db->real_escape_string($dic) . '"
											WHERE system_id ="' . $this->db->real_escape_string($id) . '" ');

							
					setcookie('odpoved','2', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					//header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();

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

	}


	public function systemUpdateOthers ($id1) {

		# POST
		if (isset($_POST['submit3']) AND $_POST['submit3'] == 'submit33') {
			
			if (isset($_POST['systemKomentar']) AND isset($_POST['systemStartClanok']) AND isset($_POST['systemSlider']) AND isset($_POST['systemSliderPocet']) AND isset($_POST['systemCaptchaLogin']) AND isset($_POST['systemCaptchaRegister']) AND isset($_POST['id']) AND isset($_POST['systemSliderAno']) AND isset($_POST['systemCaptchaTyp']) AND isset($_POST['systemVisit']) AND isset($_POST['systemNewsletter']) /* AND isset($_FILES["image"])*/) {


				$systemKomentar = (isset($_POST['systemKomentar'])) ? $_POST['systemKomentar'] : '';
				$systemStartClanok = (isset($_POST['systemStartClanok'])) ? $_POST['systemStartClanok'] : '';
				$systemSliderAno = (isset($_POST['systemSliderAno'])) ? $_POST['systemSliderAno'] : '';
				$systemSlider = (isset($_POST['systemSlider'])) ? $_POST['systemSlider'] : '';
				$systemSliderPocet = (isset($_POST['systemSliderPocet'])) ? $_POST['systemSliderPocet'] : '';
				$systemCaptchaLogin = (isset($_POST['systemCaptchaLogin'])) ? $_POST['systemCaptchaLogin'] : '';
				$systemCaptchaRegister = (isset($_POST['systemCaptchaRegister'])) ? $_POST['systemCaptchaRegister'] : '';
				$systemCaptchaTyp = (isset($_POST['systemCaptchaTyp'])) ? $_POST['systemCaptchaTyp'] : '';
				$systemVisit = (isset($_POST['systemVisit'])) ? $_POST['systemVisit'] : '';
				$systemNewsletter = (isset($_POST['systemNewsletter'])) ? $_POST['systemNewsletter'] : '';

				$id = (isset($_POST['id'])) ? $_POST['id'] : '';


				/*echo $systemKomentar . '<br>';
				echo $systemStartClanok . '<br>';
				echo $systemSliderAno . '<br>';
				echo $systemSlider . '<br>';
				echo $systemSliderPocet . '<br>';
				echo $systemCaptchaLogin . '<br>';
				echo $systemCaptchaRegister . '<br>';
				echo $systemCaptchaTyp . '<br>';
				echo $systemVisit . '<br>';
				echo $id . '<br>';
				echo $id1 . '<br>';
				//echo $_FILES["image"]['name'] . '<br>';
				die('END');*/
			

				if (!empty($systemKomentar) /*AND ($systemKomentar == '1' OR $systemKomentar == '2')*/ AND !empty($systemStartClanok) AND !empty($systemSlider) AND !empty($systemSliderPocet) AND !empty($systemCaptchaLogin) AND !empty($systemCaptchaRegister) AND !empty($id) AND $systemSliderAno != '' AND $systemNewsletter != '' AND !empty($systemCaptchaTyp) AND !empty($systemVisit) AND $id == $id1) {
										

					# ULOZ
					$uloz = $this->db->query('UPDATE be_system' . $this->prefix . ' SET 
												system_komentar ="' . $this->db->real_escape_string($systemKomentar) . '",
												system_start_clanok ="' . $this->db->real_escape_string($systemStartClanok) . '",
												system_slider_ano ="' . $this->db->real_escape_string($systemSliderAno) . '",
												system_slider ="' . $this->db->real_escape_string($systemSlider) . '",
												system_slider_pocet ="' . $this->db->real_escape_string($systemSliderPocet) . '",
												system_captcha_login ="' . $this->db->real_escape_string($systemCaptchaLogin) . '",
												system_captcha_register ="' . $this->db->real_escape_string($systemCaptchaRegister) . '",
												system_captcha_typ ="' . $this->db->real_escape_string($systemCaptchaTyp) . '",
												system_visit ="' . $this->db->real_escape_string($systemVisit) . '",
												system_newsletter ="' . $this->db->real_escape_string($systemNewsletter) . '"
											WHERE system_id ="' . $this->db->real_escape_string($id) . '" ');

							
					setcookie('odpoved','2', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					//header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
					exit();

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
$system = new system1($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>