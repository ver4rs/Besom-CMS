<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

class slider extends url {

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

	public function sliderStav () {
		$stav = $this->db->query('SELECT system_slider_ano, system_slider_pocet, system_slider 
									FROM be_system' . $this->prefix . ' ORDER BY system_id DESC LIMIT 1 ');
		if ($stav->num_rows != FALSE) {
			$tlac = $stav->fetch_assoc();

			return $tlac;

		}

	}


	public function sliderTab () {

					?>
				<div class="panel panel-default">
					<div class="panel-heading">
				    	<h3 class="panel-title" style="color: #121212; "><i class="glyphicon glyphicon-tags" style="color: #424242;"></i> &nbsp; <?php echo htmlspecialchars($this->nadpis()); ?></h3>
					</div>
					<div class="panel-body">
					</div>
				<?php

		$query = $this->db->query('SELECT *  
										FROM be_slider' . $this->prefix . ' 
									ORDER BY slider_poradie ASC ');
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
			$napA = array('1' => 'Ano',
							'2' => 'Nie');

			$colA = array('1' => 'green',
							'2' => 'red');
			?>
			<table class="table table-hover">
				<tr>
					<th>#</th>
					<th>Obrazok</th>
					<th>Nazov</th>
					<th>Popis</th>
					<th>Url</th>
					<th>Datum</th>
					<th>Zobrazit popis</th>
					<th>Stav</th>
					<th>Poradie</th>
			   
					<th style="width: 100px;"><i class="glyphicon glyphicon-star"></i>Akcia</th>
				</tr>
			<?php
			while ($slider = $query->fetch_assoc()) {
				$por = + $por +1;
				
				?>
				<tr class="<?php echo htmlspecialchars($colorA[$slider['slider_stav']]); ?>">
					<td><?php echo htmlspecialchars($por); ?></td>
					<td><?php if ($slider['slider_img'] != FALSE) { ?><img class="img-rounded" width="150" src="<?php echo ADRESA . PRIECINOK . IMAGE_SLIDER . htmlspecialchars($slider['slider_img']); ?>"><?php }else { ?><img class="img-rounded" width="150" src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>"><?php } ?></td>
					<td><?php echo htmlspecialchars($slider['slider_nazov']); ?></td>
					<td><?php echo htmlspecialchars($slider['slider_popis']); ?></td>
					<td><?php echo htmlspecialchars($slider['slider_url']); ?></td>
					<td><?php echo htmlspecialchars($slider['slider_datum']); ?></td>
					<td><?php echo htmlspecialchars($napA[$slider['slider_popis_stav']]); ?>&nbsp;<i class="<?php echo $ikonA[$slider['slider_popis_stav']]; ?>" style="color: <?php echo $colA[$slider['slider_popis_stav']]; ?>" ></i></td>
					<td><?php echo htmlspecialchars($napA[$slider['slider_stav']]); ?>&nbsp;<i class="<?php echo $ikonA[$slider['slider_stav']]; ?>" style="color: <?php echo $colA[$slider['slider_stav']]; ?>"></i></td>
					<td><?php echo htmlspecialchars($slider['slider_poradie']); ?> &nbsp; <a href="<?php echo htmlspecialchars('?menu=' . $_GET['menu'] . '&order=' . $slider['slider_poradie']); ?>"><i class="glyphicon glyphicon-sort-by-order"></i></a></td>
					<td>
							<?php
								if ($slider['slider_stav'] == '1') {
									# active
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . htmlspecialchars('&deactive=' . $slider['slider_id']);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
								}
								else {
									?><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . htmlspecialchars('&active=' . $slider['slider_id']);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
								}

								?>


						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&edit=' . htmlspecialchars($slider['slider_id']); ?>"  alt="Zmenit" title="Zmenit">
							<i class="glyphicon glyphicon-edit" style="color: #3d51b3; "></i>
						</a>
						 &nbsp; 
						<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&delete=' . htmlspecialchars($slider['slider_id']); ?>"  alt="Vymazat" title="Vymazat">
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


	public function sliderActive ($id) {
		$query = $this->db->query('SELECT slider_id FROM be_slider' . $this->prefix . ' 
							WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_slider' . $this->prefix . ' SET 
										slider_stav =1 WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function sliderDeactive ($id) {
		$query = $this->db->query('SELECT slider_id FROM be_slider' . $this->prefix . ' 
							WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');
		if ($query->num_rows != FASLE) {
			# existuje

			$uloz = $this->db->query('UPDATE be_slider' . $this->prefix . ' SET 
										slider_stav =2 WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');

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

	public function sliderNew () {

# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['popis']) AND isset($_POST['zobrazPopis']) AND isset($_FILES["image"]) ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$date = (isset($_POST['datum'])) ? $_POST['datum'] : '';
			$popisSlider = (isset($_POST['zobrazPopis'])) ? $_POST['zobrazPopis'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			
			$im = (isset($_POST['im'])) ? $_POST['im'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			/*
			echo $_FILES["image"]['name'] . '<br>';
			*/
			/*echo $nazov . ' 1 </br>';
			echo $popis . ' 2 </br>';
			echo $date . ' 3 </br>';
			echo $popisSlider . ' 4 </br>';
			echo $stav . ' 5 </br>';
			echo $im . ' 6 </br>';
			die();*/
		

			if (!empty($nazov) AND !empty($popis) AND !empty($popisSlider) AND !empty($stav) AND !empty($_FILES["image"]) ) {
				


				#-----------------------------------------------------------------------------------------------
				//error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_ORIGINAL', '../upload/slider/'); // original
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


				if ($errors == '0') {

					# PUBLIK
					// podla prav bude na schvalenie

					# DATUM
					if (isset($_POST['datum']) AND $_POST['datum'] != FALSE) {
						# ZADAL

						$date = $_POST['datum'];
					}
					else {
						$date = date('Y-m-d H:i:s');
					}


					# URL
					$slider_url = parent::seo_url($nazov);

					# INE COUNT COMMENT< VISITS, PAci sa mi to, nepavi sa mi to


					if ($image) {

						$por = $this->db->query('SELECT slider_poradie FROM be_slider' . $this->prefix . ' 
													ORDER BY slider_poradie DESC LIMIT 1');
						if ($por->num_rows != FALSE) {
							$pora = $por->fetch_assoc();
							$poradie = $pora['slider_poradie'] +1;
						}
						else {
							$poradie = 1;
						}
						
						# ULOZ
						$uloz = $this->db->query('INSERT IGNORE INTO be_slider' . $this->prefix . ' (slider_id, slider_nazov, slider_popis, slider_url, slider_img, slider_popis_stav, slider_stav, slider_datum, slider_poradie) VALUES (
											NULL,
											"' . $this->db->real_escape_string($nazov) . '",
											"' . $this->db->real_escape_string($popis) . '",
											"' . $this->db->real_escape_string($slider_url) . '",
											"' . $this->db->real_escape_string($last_id . '.jpg') . '",
											"' . $this->db->real_escape_string($popisSlider) . '",
											"' . $this->db->real_escape_string($stav) . '",
											"' . $this->db->real_escape_string($date) . '",
											"' . $this->db->real_escape_string($poradie) . '") ');

						//print_r($uloz);
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']/* . '&action=' . $_GET['action']*/);
						exit();
					}
					else {

						// neni obrazok

						setcookie('odpoved','1', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action1=' . $_GET['action']);
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
		# END POST DATA

		##########################################################
		##### RICHTEXT EDITOR
		include_once "richtexteditor/richtexteditor/include_rte.php"; 
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
					    <div class="col-sm-6">
					    <!-- <textarea class="form-control" name="popis"></textarea>  -->
					    <?php   
			                // Create Editor instance and use Text property to load content into the RTE.  
			                $rte=new RichTextEditor();   
			                $rte->Text=""; 
			                // Set a unique ID to Editor   
			                $rte->ID="popis";    
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
		    				<p class="help-block" style="color: red; ">Obrazok musite vybrat!!!</p>
					    </div>
					</div>

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
							    <input type="radio" name="stav" id="optionsRadios2" value="0">
							    Blokovany
							  </label>
							</div>
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
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo date('Y-m-d H:i:s'); ?>" name="datum">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Poradie</label>
					    <div class="col-sm-6">
					    	<input disabled="disabled" type="text" class="form-control" id="inputText3" placeholder="Cislo... 2" name="poradie">
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

	public function sliderEdit ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['nazov']) AND isset($_POST['popis']) AND isset($_POST['zobrazPopis']) AND isset($_POST['im']) /*AND isset($_FILES["image"])*/ ) {


			$nazov = (isset($_POST['nazov'])) ? $_POST['nazov'] : '';
			$popis = (isset($_POST['popis'])) ? $_POST['popis'] : '';
			$date = (isset($_POST['datum'])) ? $_POST['datum'] : '';
			$popisSlider = (isset($_POST['zobrazPopis'])) ? $_POST['zobrazPopis'] : '';
			$stav = (isset($_POST['stav'])) ? $_POST['stav'] : '';
			
			$im = (isset($_POST['im'])) ? $_POST['im'] : '';

			$image = (isset($_POST['image'])) ? $_POST['image'] : '';

			/*
			echo $_FILES["image"]['name'] . '<br>';
			*//*
			echo $nazov . ' 1 </br>';
			echo $popis . ' 2 </br>';
			echo $date . ' 3 </br>';
			echo $popisSlider . ' 4 </br>';
			echo $stav . ' 5 </br>';
			echo $im . ' 6 </br>';
			die();*/
		

			if (!empty($nazov) AND !empty($popis) AND !empty($popisSlider) AND !empty($stav) /*AND !empty($im)*/ /*AND !empty($_FILES["image"])*/ ) {
				


				#-----------------------------------------------------------------------------------------------
				//error_reporting(0);

				$last_id = md5(date('YmdHis'));

				$change="";
				$abc="";

				define('UPLOAD_DIR_ORIGINAL', '../upload/slider/'); // original
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


				if ($errors == '0') {

					# PUBLIK
					// podla prav bude na schvalenie

					# DATUM
					if (isset($_POST['datum']) AND $_POST['datum'] != FALSE) {
						# ZADAL

						$date = $_POST['datum'];
					}
					else {
						$date = date('Y-m-d H:i:s');
					}


					# URL
					$slider_url = parent::seo_url($nazov);

					# INE COUNT COMMENT< VISITS, PAci sa mi to, nepavi sa mi to


					if ($image) {

						# DELETE ALTER IMAGE
						unlink('../' . IMAGE_SLIDER . $im);
						
						# ULOZ
						$uloz = $this->db->query('UPDATE be_slider' . $this->prefix . ' SET 
														slider_nazov ="' . $this->db->real_escape_string($nazov) . '",
														slider_popis ="' . $this->db->real_escape_string($popis) . '",
														slider_url ="' . $this->db->real_escape_string($slider_url) . '",
														slider_img ="' . $this->db->real_escape_string($last_id . '.jpg') . '",
														slider_popis_stav ="' . $this->db->real_escape_string($popisSlider) . '",
														slider_stav ="' . $this->db->real_escape_string($stav) . '",
														slider_datum ="' . $this->db->real_escape_string($date) . '" 
													WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');

						//print_r($uloz);
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
						exit();
					}
					else {

						//$nula =0;

						# ULOZ
						$uloz = $this->db->query('UPDATE be_slider' . $this->prefix . ' SET 
														slider_nazov ="' . $this->db->real_escape_string($nazov) . '",
														slider_popis ="' . $this->db->real_escape_string($popis) . '",
														slider_url ="' . $this->db->real_escape_string($slider_url) . '",
														slider_popis_stav ="' . $this->db->real_escape_string($popisSlider) . '",
														slider_stav ="' . $this->db->real_escape_string($stav) . '",
														slider_datum ="' . $this->db->real_escape_string($date) . '"
													WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');

						//print_r($uloz);
						setcookie('odpoved','2', time()+1);
						//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
						//header("Location: " . $_SERVER['HTTP_REFERER']);
						header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu']);
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


		$over = $this->db->query('SELECT * FROM be_slider' . $this->prefix . ' 
									WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');
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
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['slider_nazov']); ?>" name="nazov">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    <textarea class="form-control" name="popis"><?php echo htmlspecialchars($zobraz['slider_popis']); ?></textarea>
					    <?php   
			                // Create Editor instance and use Text property to load content into the RTE.  
			                $rte=new RichTextEditor();   
			                $rte->Text= htmlspecialchars($zobraz['slider_popis']); 
			                // Set a unique ID to Editor   
			                $rte->ID="popis";    
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
					    	if ($zobraz['slider_img'] != FALSE) {
					    		?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_SLIDER . htmlspecialchars($zobraz['slider_img']); ?>" alt="<?php echo htmlspecialchars($zobraz['slider_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['slider_nazov']); ?>" width="500" class="img-rounded"><?php
					    	}
					    	?>
						    <input type="file" id="exampleInputFile" name="image">
		    				<p class="help-block" style="color: red; ">Pokial nevyberiete, tak zostane predosli!!!</p>
					    </div>
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Stav</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="stav" id="optionsRadios1" value="1" <?php if ($zobraz['slider_stav'] == '1') { echo 'checked="checked"'; } ?> >
							    Aktivny
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="stav" id="optionsRadios2" value="0" <?php if ($zobraz['slider_stav'] == '2') { echo 'checked="checked"'; } ?> >
							    Blokovany
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Zobrazenie popisu</label>
						    <div class="col-sm-6">
						    <div class="radio">
								<label>
							    	<input type="radio" name="zobrazPopis" id="optionsRadios1" value="1" <?php if ($zobraz['slider_popis_stav'] == '1') { echo 'checked="checked"'; } ?> >
							    Ano
							  	</label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="zobrazPopis" id="optionsRadios2" value="2" <?php if ($zobraz['slider_popis_stav'] == '2') { echo 'checked="checked"'; } ?> >
							    Nie
							  </label>
							</div>
						</div>	
				  	</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Datum</label>
					    <div class="col-sm-6">
					    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['slider_datum']); ?>" name="datum">
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Poradie</label>
					    <div class="col-sm-6">
					    	<input disabled="disabled" type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['slider_poradie']); ?>" name="poradie">
					    </div>
					</div>


					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-info btn-sm" name="submit" value="submit"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
						</div>
					</div>
					<input type="hidden" name="im" value="<?php echo htmlspecialchars($zobraz['slider_img']); ?>">	
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

	public function sliderDelete ($id) {

		# POST
		if (isset($_POST['submit']) AND isset($_POST['ikona']) AND isset($id)) {


			unlink('../' . IMAGE_SLIDER . $_POST['ikona']);

			$del = $this->db->query('DELETE FROM be_slider' . $this->prefix . ' 
										WHERE slider_id ="' . $this->db->real_escape_string($id) . '" AND 
											slider_img ="' . $this->db->real_escape_string($_POST['ikona']) . '" ');


			setcookie('odpoved','2', time()+1);
			//header("location: " . URL_ADRESA . htmlspecialchars('?menu=' . $content['menu_url'] . '&action=' . $act['menu_url']));
			//header("Location: " . $_SERVER['HTTP_REFERER']);
			header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
			//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia&'))
			exit();

		}
		# END POST



		#OVERENIE EXISTUJUCEHO ID
		$over = $this->db->query('SELECT * FROM be_slider' . $this->prefix . ' t 
									WHERE slider_id ="' . $this->db->real_escape_string($id) . '" ');
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
					    	<p class="form-control-static"><?php echo htmlspecialchars($zobraz['slider_nazov']); ?></p>
					    </div>
					</div>


					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Popis</label>
					    <div class="col-sm-6">
					    <p class="form-control-static"><?php echo htmlspecialchars_decode($zobraz['slider_popis']); ?></p>
					    </div>
					</div>

					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
					    <div class="col-sm-6">
					    	<?php
					    		if ($zobraz['slider_img'] != FALSE) {
					    			
							    	?>
							    	<img src="<?php echo ADRESA . PRIECINOK . IMAGE_SLIDER . htmlspecialchars($zobraz['slider_img']); ?>" alt="<?php echo htmlspecialchars($zobraz['slider_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['slider_nazov']); ?>" class="img-thumbnail" width="150">
							    	<?php
					    		}
					    		else {
									?>
							    	<img src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($zobraz['slider_nazov']); ?>" title="<?php echo htmlspecialchars($zobraz['slider_nazov']); ?>" class="img-thumbnail" width="150">
							    	<?php
					    		}
					    		?>
					    </div>
					</div>

					
				</fieldset>
	 				
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-danger btn-sm" name="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
						</div>
					</div>

					<input type="hidden" name="ikona" value="<?php echo htmlspecialchars($zobraz['slider_img']); ?>">		
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



	public function sliderOrder ($order) {
		
		# $order = 1;  //toto +1
		# $id = 1;     // id cislo

		#POCET ROVNAKYCH $order
		$pocet1 = $this->db->query('SELECT slider_id, slider_poradie FROM be_slider' . $this->prefix . ' WHERE slider_poradie ="' . $this->db->real_escape_string($order) . '"  ');
		if ($pocet1->num_rows == '1') {
			
			$cislo1 = $pocet1->fetch_assoc();

			#pocet2  order+1
			$pocet2 = $this->db->query('SELECT slider_id, slider_poradie FROM be_slider' . $this->prefix . ' WHERE slider_poradie ="' . $this->db->real_escape_string($order+1) . '"  ');
			if ($pocet2->num_rows == '1') {
				
				$cislo2 = $pocet2->fetch_assoc();

				//echo '1 ---- ' . $cislo1['social_zorad'] . ' a ' . $cislo2['social_zorad'];
				$cis1 = $cislo1['slider_poradie'];
				$cis2 = $cislo2['slider_poradie'];

				/*echo $cislo1['slider_id'] . '  ' . $cis1 . '</br>';
				echo $cislo2['slider_id'] . '  ' . $cis2;
				die();*/

				#PRIPOCITANIE
				$query1 = $this->db->query('UPDATE be_slider' . $this->prefix . ' SET 
												slider_poradie ="' . $this->db->real_escape_string($cis2) . '" 
											WHERE slider_id ="' . $this->db->real_escape_string($cislo1['slider_id']) . '" ');

				$query2 = $this->db->query('UPDATE be_slider' . $this->prefix . ' SET 
												slider_poradie ="' . $this->db->real_escape_string($cis1) . '" 
											WHERE slider_id ="' . $this->db->real_escape_string($cislo2['slider_id']) . '" ');

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



}


//$language = new language($this->db, $this->prefix, $this->lang);
$slider = new slider($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);

//if (class_exists() {
	//$language = new language($this->db, $this->prefix, $this->lang, $lg, $lang_active['jazyk_short']);
//}
//else {
	//$language = new language($conn, $prefix, $lg, $lg, $lang_active['jazyk_short']);
//}


?>