<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class widget extends url {

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
		echo $this->lg[$this->flag]['nadpis'];
	}

	public function widgetStav () {
		$stav = $this->db->query('');

		if ($stav->num_rows != FALSE) {
			$tlac = $stav->fetch_assoc();

			return $tlac;

		}

	}


	public function widgetTab () {

					?>
				<div class="panel panel-default">
					<div class="panel-heading">
				    	<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->nadpis()); ?></h3>
					</div>
					<div class="panel-body">
					</div>
				<?php

		/*$query = $this->db->query('SELECT *, w.modul_id, m.modul_id, m.modul_nazov 
										FROM be_widget' . $this->prefix . ' w 
										JOIN be_modul' . $this->prefix . ' m ON w.modul_id = m.modul_id
									ORDER BY widget_zorad ASC ');*/

		$query = $this->db->query('SELECT *
										FROM be_widget' . $this->prefix . ' 
									ORDER BY widget_zorad ASC ');
		if ($query->num_rows != FALSE) {
			# OK
			$por = 0;
			$por = $por;

			$publikA = array('1' => 'aktivny',
							 '0' => 'Zablokovany',
							 '2' => 'neaktivny' );
			$colorA = array('1' => 'success',
							 '2' => 'danger');
			$ikonA = array('1' => 'glyphicon glyphicon-ok-circle',
							'2' => 'glyphicon glyphicon-remove-circle');
			$napA = array('1' => 'Aktivne',
							'2' => 'Zablokovane');

			$colA = array('1' => 'green',
							'2' => 'red');
			?>
			<table class="table table-hover">
				<tr>
					<th>#</th>
					<th>Nazov</th>
					<th>Obrazok</th>
			   <!-- <th>Text</th> -->
					<th>Modul</th>
					<th>Modul typ id</th>
					<th>Text id</th>
					<th>Adresa</th>
					<th>Preklad nazov</th>
					<th>Preklad popis</th>
					<th>Typ</th>
					<th>Stav</th>
					<th>Poradie</th>
			   
					<th style="width: 100px;"><i class="glyphicon glyphicon-star"></i>Akcia</th>
				</tr>
			<?php
			while ($widget = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php echo htmlspecialchars($colorA[$widget['widget_stav']]); ?>">
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php echo htmlspecialchars($widget['widget_nazov']); ?></td>
					<td><?php if ($widget['widget_img'] != FALSE) { ?><img class="img-rounded" width="40" src="<?php echo ADRESA . PRIECINOK . IMAGE_WIDGET . htmlspecialchars($widget['widget_img']); ?>"><?php }else { ?><img class="img-rounded" width="40" src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>"><?php } ?></td>
			   <!-- <td><?php //echo htmlspecialchars($widget['widget_text']); ?></td> -->
					<td><?php echo htmlspecialchars($widget['modul_id']/* . ' ' . $widget['modul_nazov']*/); ?></td>
					<td><?php echo htmlspecialchars($widget['modul_id_typ']); ?></td>
					<td><?php echo htmlspecialchars($widget['text_id']); ?></td>
					<td><?php echo htmlspecialchars($widget['widget_url']); ?></td>
					<td><?php echo htmlspecialchars($widget['widget_translate_id']); ?></td>
					<td><?php echo htmlspecialchars($widget['widget_popis_translate_id']); ?></td>
					<td><?php echo htmlspecialchars($widget['widget_typ']); ?></td>
					<td><?php echo htmlspecialchars($napA[$widget['widget_stav']]); ?>&nbsp;<i class="<?php echo $ikonA[$widget['widget_stav']]; ?>" style="color: <?php echo $colA[$widget['widget_stav']]; ?>"></i></td>
					<td><?php echo htmlspecialchars($widget['widget_zorad']); ?> &nbsp; <a href="<?php echo htmlspecialchars('?menu=' . $_GET['menu'] . '&order=' . $widget['widget_zorad']); ?>"><i class="glyphicon glyphicon-sort-by-order"></i></a></td>
					<td>
							<?php
								if ($widget['widget_stav'] == '1') {
									# active
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . htmlspecialchars('&deactive=' . $widget['widget_id']);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
								}
								else {
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . htmlspecialchars('&active=' . $widget['widget_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

								?>


						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . htmlspecialchars($widget['widget_id']); ?>"  alt="Zmenit" title="Zmenit">
							<i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i>
						</a>
						 &nbsp; 
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&delete=' . htmlspecialchars($widget['widget_id']); ?>"  alt="Vymazat" title="Vymazat">
							<i class="glyphicon glyphicon-trash" style="color: red; "></i>
						</a>
					</td>
				</tr>
				<?php

			}
			?></table><?php 
			 
		}
		?></div><?php
	}


	public function widgetActive ($id) {
		$query = $this->db->query('SELECT widget_id FROM be_widget' . $this->prefix . ' 
							WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
										widget_stav =1 WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function widgetDeactive ($id) {
		$query = $this->db->query('SELECT widget_id FROM be_widget' . $this->prefix . ' 
							WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
										widget_stav =2 WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function widgetNew () {

# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) /*AND isset($_POST['adresar'])*/ /*AND isset($_POST['menu'])*/ /*AND isset($_POST['im'])*/ AND isset($_POST['stav']) /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			
			$im = (isset($_POST['im'])) ? $_POST['im'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';
			$txt = (isset($_POST['txt'])) ? $_POST['txt'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

		
			$error = 0;

			if (!empty($nazov) ) {
				
				if ($typ == 'modul') {
					$modulId = (isset($_POST['modulId'])) ? $_POST['modulId'] : '';
					$modulTyp = (isset($_POST['modulTyp'])) ? $_POST['modulTyp'] : '';

					if (empty($modulId) AND empty($modulTyp)) {
						$error = 1;
					}

				}
				elseif ($typ == 'textcontent') {//  text_id z DB text cize clanok alebo content
					# //  text_id z DB text cize clanok alebo content
					$text_id = (isset($_POST['text_id'])) ? $_POST['text_id'] : '';

					if (empty($text_id)) {
						$error = 1;
					}
				}
				elseif ($typ == 'image') {
					$text = (isset($_POST['text'])) ? $_POST['text'] : '';
					$url = (isset($_POST['url'])) ? $_POST['url'] : '';

					if (empty($text) AND empty($url)) {
						$error = 1;
					}
				}
				elseif ($typ == 'script') {
					$text = (isset($_POST['text'])) ? $_POST['text'] : '';

					if (empty($text)) {
						$error = 1;
					}
				}
				else {
					# text
					$text = (isset($_POST['text'])) ? $_POST['text'] : '';
					$nazovEn = (isset($_POST['nazovEn'])) ? $_POST['nazovEn'] : '';
					$nazovDe = (isset($_POST['nazovDe'])) ? $_POST['nazovDe'] : '';
					$textEn = (isset($_POST['textEn'])) ? $_POST['textEn'] : '';
					$textDe = (isset($_POST['textDe'])) ? $_POST['textDe'] : '';

					if (empty($text)) { 
						// nemusia byt preklady
						$error = 1;
					}

				}

				#-----------------------------------------------------------------------------------------------
				//error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_ORIGINAL', '../upload/widget/'); // original
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
						else if($extension=="png" || $extension == 'PNG') {

							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}
						else if($extension == 'gif') {
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromgif($uploadedfile);
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
						$last_image = $last_id . '.jpg';

						/*if ($extension == 'png'  || $extension == 'PNG' || $extension == 'gif') {
							$im = $uploadedfile;
							$black = imagecolorallocate($im, 0, 0, 0);
							imagecolortransparent($im, $black);
							$filenam = UPLOAD_DIR_ORIGINAL . $last_id . '.png';  //original
							$last_image = $last_id . '.png';

							imagepng($src,$filenam,80);	
						}
						else {*/
							imagejpeg($src,$filenam,80);
						//}

						//$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
						//$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
						//$slider = UPLOAD_DIR_SLIDER . $last_id . '.jpg';   //mini 
						//--------------------------------------------------------------------------------------
				
						//------------------------ nove ------------------
						/*include 'component/resize-class.php';

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
						*/
						imagedestroy($src);  //original
						//imagedestroy($normal);
						//imagedestroy($mini);
						//imagedestroy($slider);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}

				if ($error == '0') {
					# OK IDEME DALEJ
				


					if ($errors == '0') {


						#PORADIE
						$por = $this->db->query('SELECT widget_zorad FROM be_widget' . $this->prefix . ' 
													ORDER BY widget_zorad DESC LIMIT 1 ');
						if ($por->num_rows != FALSE) {
							$pora = $por->fetch_assoc();

							$poradie = $pora['widget_zorad'] +1;
						}
						else {
							$poradie =1;
						}

						# PUBLIK
						// podla prav bude na schvalenie

						if ($typ == 'modul') {
							# uloz

							if ($image) {

								# DELETE ALTER IMAGE
								//unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
									(widget_id, widget_nazov, modul_id, modul_id_typ, widget_stav, widget_img, widget_typ, widget_zorad) VALUES  
																(NULL,
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($modulId) . '",
																"' . $this->db->real_escape_string($modulTyp) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($last_image) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
									(widget_id, widget_nazov, modul_id, modul_id_typ, widget_stav, widget_img, widget_typ, widget_zorad) VALUES  
																(NULL,
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($modulId) . '",
																"' . $this->db->real_escape_string($modulTyp) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($nula) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}

						}
						elseif ($typ == 'textcontent') {
							# text_id
							# zleeeeleelelel
							die('Error');

						}
						elseif ($typ == 'image') {
							# uloz   -> text, url
							

							if ($image) {

								# DELETE ALTER IMAGE
								//unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
										(widget_id, widget_nazov, widget_text, widget_url, widget_stav, widget_img, widget_typ, widget_zorad) VALUES  
																(NULL,
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($text) . '",
																"' . $this->db->real_escape_string($url) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($last_image) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
										(widget_id, widget_nazov, widget_text, widget_url, widget_stav, widget_img, widget_typ, widget_zorad) VALUES  
																(NULL,
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($text) . '",
																"' . $this->db->real_escape_string($url) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($nula) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
						}
						elseif ($typ == 'script') {
							# uloz
							
							if ($image) {

								# DELETE ALTER IMAGE
								//unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
													(widget_id, widget_nazov, widget_text, widget_stav, widget_img, widget_typ, widget_zorad) VALUES 
																(NULL, 
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($text) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($last_image) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
													(widget_id, widget_nazov, widget_text, widget_stav, widget_img, widget_typ, widget_zorad) VALUES 
																(NULL, 
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($text) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($nula) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
						}
						else {
							# text

							$text = str_replace('\"', '', $text);

							if ($image) {

								# DELETE ALTER IMAGE
								//unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
													(widget_id, widget_nazov, widget_text, widget_stav, widget_img, widget_typ, widget_zorad) VALUES  
																(NULL,
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($text) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($last_image) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('INSERT INTO be_widget' . $this->prefix . ' 
													(widget_id, widget_nazov, widget_text, widget_stav, widget_img, widget_typ, widget_zorad) VALUES  
																(NULL,
																"' . $this->db->real_escape_string($nazov) . '",
																"' . $this->db->real_escape_string($text) . '",
																"' . $this->db->real_escape_string($stav) . '",
																"' . $this->db->real_escape_string($nula) . '",
																"' . $this->db->real_escape_string($typ) . '",
																"' . $this->db->real_escape_string($poradie) . '") ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}

							# translate preklad ak je
							# uloz

						}
	
					}
					else {
						setcookie('odpoved','1', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . $_GET['edit']);
						exit();
					}
				
				}
				else {
					setcookie('odpoved','1', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . $_GET['edit']);
					exit();
				}
			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . $_GET['edit']);
				exit();
			}

		}
		# END POST DATA

		##########################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
		##########################################################
			/*
			ZISTIME TYP WIDGETU
				modul
				image
				script
				text
			*/
			?>
<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #278c38; margin-bottom: 5px;">Pridat</h3>

		<div class="panel panel-default">
			<div class="panel-heading">
		    	<h3 class="panel-title">Pridat</h3>
			</div>
			<div class="panel-body">
				
				<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">
					
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" placeholder="Nazov widgetu" name="nazov">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
						    <input type="file" id="exampleInputFile" name="image">
		    				<p class="help-block" style="color: red; ">Ak nevyberiete, tak nebude ziadny!!!</p>
					    </div>
					</div>
			<?php

			if ($_GET['new'] == 'modul') {
				# modul
				?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Modul</label>
					    <div class="col-sm-6">
					    	<?php $this->selectModul('1', '', '0'); // 2-edit,id modul, 0-nebude nula   ?>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Typ Modulu</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" placeholder="typ funkcia modulu, len ak je dana." name="modulTyp">
					    </div>
					</div>
				<?php
			}
			elseif ($_GET['new'] == 'textcontent') {
				# text id clanok
				
					?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Clanok </br> <small>ID cislo textu | clanku</small></label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" placeholder="ID cislo textu" name="text_id">
					    </div>
					</div>
					<?php
			}
			elseif ($_GET['new'] == 'image') {
				# image

				?>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text </br> <small>Link adresa obrazku</small></label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="text">Obrazok adresa</textarea>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Url adresa </br> <small>Presmerovanie na adresu</small></label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" placeholder="http://besom.sk" name="url">
					    </div>
					</div>
				<?php

			}
			elseif ($_GET['new'] == 'script') {
				# script

				?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="text">Nejaky javascript, object alebo nieco ine.</textarea>
					    </div>
					</div>
				<?php
			}
			else {
				# text
				?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    	<!-- <textarea class="form-control" id="inputText3" name="text">Nejaky cisto text</textarea>  -->
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

					<!-- translate nazov, popis  -->
				<?php
				# NADPIS
				/*
				if ($zobraz['widget_translate_id'] != FALSE) {
					$preklad = $this->db->query('SELECT * FROM be_translate' . $this->prefix .' 
														WHERE translate_id ="' . $this->db->real_escape_string($zobraz['widget_translate_id']) . '"  ');
					if ($preklad->num_rows != FALSE) {
						# OK JE
						$preklad1 = $preklad->fetch_assoc();

						?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad nazov Anglictina "en"</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($preklad1['widget_translate_id']); ?>" name="nazovEn">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad nazov Nemcina "de"</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($preklad1['widget_translate_id']); ?>" name="nazovDe">
					    </div>
					</div>
						<?php
					}
				}
				else {
					#
				}

				# POPIS
				if ($zobraz['widget_popis_translate_id'] != FALSE) {
					$preklad = $this->db->query('SELECT * FROM be_translate' . $this->prefix .' 
														WHERE translate_id ="' . $this->db->real_escape_string($zobraz['widget_popis_translate_id']) . '"  ');
					if ($preklad->num_rows != FALSE) {
						# OK JE
						$preklad1 = $preklad->fetch_assoc();

						?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad textu Anglictina "en"</label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="textEn"><?php echo htmlspecialchars($preklad1['translate_en']); ?></textarea>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad textu Nemcina "de"</label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="textDe"><?php echo htmlspecialchars($preklad1['translate_de']); ?></textarea>
					    </div>
					</div>
						<?php

					}
				}
				else {
					#
				}
				*/
			}

			# stav
			?>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="1" checked="checked">
							    Aktivny
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="2">
							    Zablokovany
							  </label>
							</div>
						</div>	
				  	</div>
			<?php

			# translate dokoncenie neskor na vsetky


			?>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-success btn-sm" name="submit" value="submit"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridat</button>
						</div>
					</div>
					<input type="hidden" name="typ" value="<?php echo $_GET['new']; ?>">	
			
					<input type="hidden" name="nazovEn" value="<?php echo '0'; ?>">	
					<input type="hidden" name="nazovDe" value="<?php echo '0'; ?>">	
					<input type="hidden" name="textEn" value="<?php echo '0'; ?>">	
					<input type="hidden" name="textDe" value="<?php echo '0'; ?>">	
				</form>
			</div>
		</div>	
			<?php

	}





	public function widgetEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) /*AND isset($_POST['adresar'])*/ /*AND isset($_POST['menu'])*/ AND isset($_POST['im']) AND isset($_POST['stav']) /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			
			$im = (isset($_POST['im'])) ? $_POST['im'] : '';
			$typ = (isset($_POST['typ'])) ? $_POST['typ'] : '';
			$txt = (isset($_POST['txt'])) ? $_POST['txt'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

		
			$error = 0;

			if (!empty($nazov) ) {
				
				if ($typ == 'modul') {
					$modulId = (isset($_POST['modulId'])) ? $_POST['modulId'] : '';
					$modulTyp = (isset($_POST['modulTyp'])) ? $_POST['modulTyp'] : '';

					if (empty($modulId) AND empty($modulTyp)) {
						$error = 1;
					}

				}
				elseif ($txt == '1') {//  text_id z DB text cize clanok alebo content
					# //  text_id z DB text cize clanok alebo content
					$text_id = (isset($_POST['text_id'])) ? $_POST['text_id'] : '';

					if (empty($text_id)) {
						$error = 1;
					}
				}
				elseif ($typ == 'image') {
					$text = (isset($_POST['text'])) ? $_POST['text'] : '';
					$url = (isset($_POST['url'])) ? $_POST['url'] : '';

					if (empty($text) AND empty($url)) {
						$error = 1;
					}
				}
				elseif ($typ == 'script') {
					$text = (isset($_POST['text'])) ? $_POST['text'] : '';

					if (empty($text)) {
						$error = 1;
					}
				}
				else {
					# text
					$text = (isset($_POST['text'])) ? $_POST['text'] : '';
					$nazovEn = (isset($_POST['nazovEn'])) ? $_POST['nazovEn'] : '';
					$nazovDe = (isset($_POST['nazovDe'])) ? $_POST['nazovDe'] : '';
					$textEn = (isset($_POST['textEn'])) ? $_POST['textEn'] : '';
					$textDe = (isset($_POST['textDe'])) ? $_POST['textDe'] : '';

					if (empty($text)) { 
						// nemusia byt preklady
						$error = 1;
					}

				}

				#-----------------------------------------------------------------------------------------------
				//error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_ORIGINAL', '../upload/widget/'); // original
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
						else if($extension=="png" || $extension == 'PNG') {

							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}
						else if($extension == 'gif') {
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromgif($uploadedfile);
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
						$last_image = $last_id . '.jpg';

						/*if ($extension == 'png'  || $extension == 'PNG' || $extension == 'gif') {
							$im = $uploadedfile;
							$black = imagecolorallocate($im, 0, 0, 0);
							imagecolortransparent($im, $black);
							$filenam = UPLOAD_DIR_ORIGINAL . $last_id . '.png';  //original
							$last_image = $last_id . '.png';

							imagepng($src,$filenam,80);	
						}
						else {*/
							imagejpeg($src,$filenam,80);
						//}

						$normal = UPLOAD_DIR_NORMAL . $last_id . '.jpg';  //mensi
						$mini = UPLOAD_DIR_MINI . $last_id . '.jpg';   //mini UPLOAD_DIR_MICRO
						$slider = UPLOAD_DIR_SLIDER . $last_id . '.jpg';   //mini 
						//--------------------------------------------------------------------------------------
				
						//------------------------ nove ------------------
						/*include 'component/resize-class.php';

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
						*/
						imagedestroy($src);  //original
						//imagedestroy($normal);
						//imagedestroy($mini);
						//imagedestroy($slider);
						imagedestroy($filenam);

				############################################################################################
				#################################      ULOZENIE		 #######################################
				############################################################################################
				############################################################################################


					}
				}

				if ($error == '0') {
					# OK IDEME DALEJ
				


					if ($errors == '0') {

						# PUBLIK
						// podla prav bude na schvalenie

						if ($typ == 'modul') {
							# uloz

							if ($image) {

								# DELETE ALTER IMAGE
								unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																modul_id ="' . $this->db->real_escape_string($modulId) . '",
																modul_id_typ ="' . $this->db->real_escape_string($modulTyp) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($last_image) . '" 
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																modul_id ="' . $this->db->real_escape_string($modulId) . '",
																modul_id_typ ="' . $this->db->real_escape_string($modulTyp) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($nula) . '"
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}

						}
						elseif ($txt == '1') {
							# text_id
							# zleeeeleelelel
							die('Error');

						}
						elseif ($typ == 'image') {
							# uloz   -> text, url
							

							if ($image) {

								# DELETE ALTER IMAGE
								unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																widget_text ="' . $this->db->real_escape_string($text) . '",
																widget_url ="' . $this->db->real_escape_string($url) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($last_image) . '" 
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																widget_text ="' . $this->db->real_escape_string($text) . '",
																widget_url ="' . $this->db->real_escape_string($url) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($nula) . '"
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
						}
						elseif ($typ == 'script') {
							# uloz
							
							if ($image) {

								# DELETE ALTER IMAGE
								unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																widget_text ="' . $this->db->real_escape_string($text) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($last_image) . '" 
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																widget_text ="' . $this->db->real_escape_string($text) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($nula) . '"
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
						}
						else {
							# text

							$text = str_replace('\"', '', $text);

							if ($image) {

								# DELETE ALTER IMAGE
								unlink('../' . IMAGE_WIDGET . $im);
								
								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																widget_text ="' . $this->db->real_escape_string($text) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($last_image) . '" 
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}
							else {

								$nula = '';

								# ULOZ
								$uloz = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
																widget_nazov ="' . $this->db->real_escape_string($nazov) . '",
																widget_text ="' . $this->db->real_escape_string($text) . '",
																widget_stav ="' . $this->db->real_escape_string($stav) . '",
																widget_img ="' . $this->db->real_escape_string($nula) . '"
															WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

								//print_r($uloz);
								setcookie('odpoved','2', time()+1);
								//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
								//header("Location: " . $_SERVER['HTTP_REFERER']);
								header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
								exit();
							}

							# translate preklad ak je
							# uloz

						}
	
					}
					else {
						setcookie('odpoved','1', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . $_GET['edit']);
						exit();
					}
				
				}
				else {
					setcookie('odpoved','1', time()+1);
					//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . $_GET['edit']);
					exit();
				}
			}
			else {
				# ERROR
				# presmeruj
				setcookie('odpoved','1', time()+1);
				//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
				//header("Location: " . $_SERVER['HTTP_REFERER']);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . $_GET['edit']);
				exit();
			}

		}
		# END POST DATA

		$over = $this->db->query('SELECT * FROM be_widget' . $this->prefix . ' 
									WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			##########################################################
			##### RICHTEXT EDITOR
			include_once "richtexteditor/richtexteditor/include_rte.php"; 
			##########################################################

			/*
			ZISTIME TYP WIDGETU
				modul
				image
				script
				text
			*/
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
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['widget_nazov']); ?>" name="nazov">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
					    	<?php
					    	if ($zobraz['widget_img'] != FALSE) {
					    		?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_WIDGET . htmlspecialchars($zobraz['widget_img']); ?>" alt="<?php echo htmlspecialchars($zobraz['widget_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['widget_nazov']); ?>" width="50" class="img-rounded"><?php
					    	}
					    	?>
						    <input type="file" id="exampleInputFile" name="image">
		    				<p class="help-block" style="color: red; ">Pokial nevyberiete, tak zostane predosli!!!</p>
					    </div>
					</div>
			<?php

			if ($zobraz['widget_typ'] == 'modul') {
				# modul
				?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Modul</label>
					    <div class="col-sm-6">
					    	<?php $this->selectModul('2', $zobraz['modul_id'], '0'); // 2-edit,id modul, 0-nebude nula   ?>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Typ Modulu</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['modul_id_typ']); ?>" name="modulTyp">
					    </div>
					</div>
				<?php
			}
			elseif ($zobraz['text_id'] != FALSE) {
				# text id clanok
				$nac = $this->db->query('SELECT * FROM be_text' . $this->prefix . ' 
											WHERE text_id ="' . $this->db->real_escape_string($zobraz['text_id']) . '" ');
				if ($nac->num_rows != FALSE) {
					# OK je
					$naci = $nac->fetch_assoc();
					?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text </br> <small>Link adresa obrazku</small></label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="text_id"><?php echo htmlspecialchars($naci['text_cely']); ?></textarea>
					    </div>
					</div>
					<?php
				}
				else {
					# neni
				}
			}
			elseif ($zobraz['widget_typ'] == 'image') {
				# image

				?>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text </br> <small>Link adresa obrazku</small></label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="text"><?php echo htmlspecialchars($zobraz['widget_text']); ?></textarea>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Url adresa </br> <small>Presmerovanie na adresu</small></label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['widget_url']); ?>" name="url">
					    </div>
					</div>
				<?php

			}
			elseif ($zobraz['widget_typ'] == 'script') {
				# script

				?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="text"><?php echo htmlspecialchars($zobraz['widget_text']); ?></textarea>
					    </div>
					</div>
				<?php
			}
			else {
				# text
				?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    	<!-- <textarea class="form-control" id="inputText3" name="text"><?php //echo htmlspecialchars($zobraz['widget_text']); ?></textarea>  -->
					    <?php   
			                // Create Editor instance and use Text property to load content into the RTE.  
			                $rte=new RichTextEditor();   
			                $rte->Text= htmlspecialchars($zobraz['widget_text']); 
			                // Set a unique ID to Editor   
			                $rte->ID="text";    
			                $rte->MvcInit();   
			                // Render Editor 
			                echo $rte->GetString();  
			            ?>   
					    </div>
					</div>

					<!-- translate nazov, popis  -->
				<?php
				# NADPIS
				if ($zobraz['widget_translate_id'] != FALSE) {
					$preklad = $this->db->query('SELECT * FROM be_translate' . $this->prefix .' 
														WHERE translate_id ="' . $this->db->real_escape_string($zobraz['widget_translate_id']) . '"  ');
					if ($preklad->num_rows != FALSE) {
						# OK JE
						$preklad1 = $preklad->fetch_assoc();

						?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad nazov Anglictina "en"</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($preklad1['widget_translate_id']); ?>" name="nazovEn">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad nazov Nemcina "de"</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($preklad1['widget_translate_id']); ?>" name="nazovDe">
					    </div>
					</div>
						<?php
					}
				}
				else {
					#
				}

				# POPIS
				if ($zobraz['widget_popis_translate_id'] != FALSE) {
					$preklad = $this->db->query('SELECT * FROM be_translate' . $this->prefix .' 
														WHERE translate_id ="' . $this->db->real_escape_string($zobraz['widget_popis_translate_id']) . '"  ');
					if ($preklad->num_rows != FALSE) {
						# OK JE
						$preklad1 = $preklad->fetch_assoc();

						?>
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad textu Anglictina "en"</label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="textEn"><?php echo htmlspecialchars($preklad1['translate_en']); ?></textarea>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Preklad textu Nemcina "de"</label>
					    <div class="col-sm-6">
					    	<textarea class="form-control" id="inputText3" name="textDe"><?php echo htmlspecialchars($preklad1['translate_de']); ?></textarea>
					    </div>
					</div>
						<?php

					}
				}
				else {
					#
				}
			}

			# stav
			?>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="1" <?php if ($zobraz['widget_stav'] == '1') { echo 'checked="checked"'; } ?> >
							    Aktivny
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="2" <?php if ($zobraz['widget_stav'] == '2') { echo 'checked="checked"'; } ?> >
							    Zablokovany
							  </label>
							</div>
						</div>	
				  	</div>
			<?php

			# translate dokoncenie neskor na vsetky


			?>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-info btn-sm" name="submit" value="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
						</div>
					</div>
					<input type="hidden" name="im" value="<?php echo htmlspecialchars($zobraz['widget_img']); ?>">	
					<input type="hidden" name="typ" value="<?php echo htmlspecialchars($zobraz['widget_typ']); ?>">	
					<input type="hidden" name="txt" value="<?php if ($zobraz['text_id'] != FALSE) { echo '1'; } else { echo '2'; } ?>">	
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

	public function widgetDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['ikona']) AND isset($id) AND isset($_POST['poradie'])) {


			unlink('../' . IMAGE_WIDGET . $_POST['ikona']);


			$del = $this->db->query('DELETE FROM be_widget' . $this->prefix . ' 
										WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');

			#PORADIE POZORADOVAT
			// ci je vacsie poradie 
			$zisti = $this->db->query('SELECT widget_id, widget_zorad FROM be_widget' . $this->prefix . ' 
											WHERE widget_zorad >"' . $this->db->real_escape_string($_POST['poradie']) . '" 
											ORDER BY widget_zorad ASC ');
			if ($zisti->num_rows != FALSE) {
				
				while ($zmen = $zisti->fetch_assoc()) {
					
					$zmena = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
													widget_zorad =widget_zorad -1 
												WHERE widget_id ="' . $this->db->real_escape_string($zmen['widget_id']) . '" ');
				}
			}
			# END ZORADENIE PRED VYMAZANIM


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST



		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_widget' . $this->prefix . ' t 
									WHERE widget_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($over->num_rows != FALSE) {
			# EXISTUJE

			$zobraz = $over->fetch_assoc();

			?>
			<div class="bs-example" style="background: #FFFFFF; padding: 0px 0px 5px 0px; border: 1px solid #CDCDCD; margin-bottom: 20px;">
				<h3 style="margin: 0px; padding: 10px 0px 2px 5px; background: #E5E5E5; color: #e60e0e; margin-bottom: 5px;">Vymazat</h3>

				<form class="form-horizontal" role="form" method="post" action="">
				<fieldset disabled>
				
					
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Nazov</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['widget_nazov']); ?>" name="nazov">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Text</label>
					    <div class="col-sm-6">
					    	<input type="text" disabled="disabled" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['widget_text']); ?>" name="adresar">
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
					    	<?php
					    	if ($zobraz['widget_img'] != FALSE) {
					    		?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_WIDGET . htmlspecialchars($zobraz['widget_img']); ?>" alt="<?php echo htmlspecialchars($zobraz['widget_img']); ?>" title="<?php echo htmlspecialchars($zobraz['widget_img']); ?>" width="50" class="img-rounded"><?php
					    	}
					    	?>
						    <!-- <input type="file" id="exampleInputFile" name="image">
		    				<p class="help-block" style="color: red; ">Pokial nevyberiete, tak zostane predosli!!!</p> -->
					    </div>
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="1" <?php if ($zobraz['widget_stav'] == '1') { echo 'checked="checked"'; } ?> >
							    Aktivny
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="2" <?php if ($zobraz['widget_stav'] == '2') { echo 'checked="checked"'; } ?> >
							    Zablokovany
							  </label>
							</div>
						</div>	
				  	</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Typ</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['widget_typ']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Toto zobrazujem</label>
					    <div class="col-sm-6">
					    	<p class="form-control-static"><?php echo htmlspecialchars_decode($zobraz['widget_text']); ?></p>
					    </div>
					</div>
					
				</fieldset>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>

					<input type="hidden" name="ikona" value="<?php echo htmlspecialchars($zobraz['widget_img']); ?>">		
					<input type="hidden" name="poradie" value="<?php echo htmlspecialchars($zobraz['widget_zorad']); ?>">		
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



	public function widgetOrder ($order) {
		
		# $order = 1;  //toto +1
		# $id = 1;     // id cislo

		#POCET ROVNAKYCH $order
		$pocet1 = $this->db->query('SELECT widget_id, widget_zorad FROM be_widget' . $this->prefix . ' WHERE widget_zorad ="' . $this->db->real_escape_string($order) . '"  ');
		if ($pocet1->num_rows == '1') {
			
			$cislo1 = $pocet1->fetch_assoc();

			#pocet2  order+1
			$pocet2 = $this->db->query('SELECT widget_id, widget_zorad FROM be_widget' . $this->prefix . ' WHERE widget_zorad ="' . $this->db->real_escape_string($order+1) . '"  ');
			if ($pocet2->num_rows == '1') {
				
				$cislo2 = $pocet2->fetch_assoc();

				//echo '1 ---- ' . $cislo1['social_zorad'] . ' a ' . $cislo2['social_zorad'];
				$cis1 = $cislo1['widget_zorad'];
				$cis2 = $cislo2['widget_zorad'];

				/*echo $cislo1['widget_id'] . '  ' . $cis1 . '</br>';
				echo $cislo2['widget_id'] . '  ' . $cis2;
				die();*/

				#PRIPOCITANIE
				$query1 = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
												widget_zorad ="' . $this->db->real_escape_string($cis2) . '" 
											WHERE widget_id ="' . $this->db->real_escape_string($cislo1['widget_id']) . '" ');

				$query2 = $this->db->query('UPDATE be_widget' . $this->prefix . ' SET 
												widget_zorad ="' . $this->db->real_escape_string($cis1) . '" 
											WHERE widget_id ="' . $this->db->real_escape_string($cislo2['widget_id']) . '" ');

				setcookie('odpoved','2', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
				exit();

			}
			else {
				//die('chyba2');
				setcookie('odpoved','1', time()+1);
				header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
				exit();
			}

		}
		else {
			//die('chyba1');
			setcookie('odpoved','1', time()+1);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
			exit();
		}

	}




	/*
	*****
	
			PREVZATE FUNKCIE

			1. menu.lib -> funckia select na zobrazenie modulov    selectModul($typ = '1', $modul = '', $disabled = '')
	
	*****
	*/

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
					<!-- <option name="0" value="0">NIJAKY</option> -->
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
					<!-- <option name="0" value="0">NIJAKY</option> -->
				<?php

				while ($position = $posit->fetch_assoc()) {
					
					if ($position['modul_id'] == $modul) {
						?><option name="<?php echo htmlspecialchars($position['modul_id']); ?>" value="<?php echo htmlspecialchars($position['modul_id']); ?>" selected="selected"><?php echo htmlspecialchars($position['modul_nazov'] . ' funkcia: ' . $position['modul_funkcia']); ?></option><?php
					}
					else {
						?><option name="<?php echo htmlspecialchars($position['modul_id']); ?>" value="<?php echo htmlspecialchars($position['modul_id']); ?>"><?php echo htmlspecialchars($position['modul_nazov'] . ' funkcia: ' . $position['modul_funkcia']); ?></option><?php
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




}


//$language = new language($this->db, $this->prefix, $this->lang);
$widget = new widget($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>