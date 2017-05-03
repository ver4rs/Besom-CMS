<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);


/*
#DEACTIVE
if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$social->socialDeactive($_GET['deactive']);

	die();
}*/





# NACITAME DO FORMULARA
$zobraz = $system->systemForm();


########################################################
#ISSET POST 1 FORMULARA 							####
$system->systemUpdateSetting($zobraz['system_id']); ####
########   END POST FORMULAR 1    ######################


########################################################
#ISSET POST 2 FORMULARA 							####
$system->systemUpdateAdmin($zobraz['system_id']); 	####
########   END POST FORMULAR 2    ######################


########################################################
#ISSET POST 3 FORMULARA 							####
$system->systemUpdateOthers($zobraz['system_id']); 	####
########   END POST FORMULAR 3    ######################



##########################################################
##### RICHTEXT EDITOR
include_once "richtexteditor/richtexteditor/include_rte.php"; 
##########################################################

?>
<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #232323; margin-bottom: 5px;">Systemove nastavenia</h3>

<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title">Systemove nastavenia</h3>
	</div>
	<div class="panel-body">

		<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Titul</label>
			    <div class="col-sm-6">
			    	<input type="text" class="validate[required] form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_title']); ?>" name="title">
			    </div>
			</div>


			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Keywords</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_keywords']); ?>" name="keywords">
			    </div>
			</div>


			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_description']); ?>" name="description">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Nadpis</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_nadpis']); ?>" name="nadpis">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Url adresa</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_url']); ?>" name="urlAdresa">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Google Analitics</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_analitic']); ?>" name="analitic">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Obrazok</label>
			    <div class="col-sm-6">
				    <input type="file" id="exampleInputFile" name="image">
		    		<p class="help-block">Pozor ak vyberiete obrazok, tak sa zmeni predosli!!</p>
		    		<img src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('upload/system/' . $zobraz['system_img']); ?>" alt="" title="">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Spodok</label>
			    <div class="col-sm-7">
			    	<!-- <textarea class="form-control" rows="4" cols="10" name="spod"><?php //echo htmlspecialchars($zobraz['system_spod']); ?></textarea>  -->
			    <?php   
	                // Create Editor instance and use Text property to load content into the RTE.  
	                $rte=new RichTextEditor();   
	                $rte->Text= $zobraz['system_spod']; 
	                // Set a unique ID to Editor   
	                $rte->ID="spod";    
	                $rte->MvcInit();   
	                // Render Editor 
	                echo $rte->GetString();  
	            ?>   
			    </div>
			</div>	
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($zobraz['system_id']); ?>">

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit1" class="btn btn-success btn-sm" name="submit1" value="submit11"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
				</div>
			</div>
		</form>	
	</div>
</div>


<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title">Admin</h3>
	</div>
	<div class="panel-body">

		<form class="form-horizontal" role="form" method="post" action="">

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Prezivka (Avatar)</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_meno']); ?>" name="avatar">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Meno</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_firstmeno']); ?>" name="meno">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Priezvisko</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_lastmeno']); ?>" name="priezvisko">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_facebook']); ?>" name="facebook">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_twitter']); ?>" name="twitter">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_email']); ?>" name="email">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Adresa</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_adresa']); ?>" name="adresa">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Mesto</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_mesto']); ?>" name="mesto">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Region</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_region']); ?>" name="region">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Stat</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_stat']); ?>" name="stat">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">PSC</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_psc']); ?>" name="psc">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Telefon</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_tel']); ?>" name="telefon">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">ICO</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_ico']); ?>" name="ico">
			    </div>
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">DIC</label>
			    <div class="col-sm-6">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_admin_dic']); ?>" name="dic">
			    </div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit2" class="btn btn-success btn-sm" name="submit2" value="submit22"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
				</div>
			</div>
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($zobraz['system_id']); ?>">
		</form>	
	</div>
</div>


<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title">Dalsie nastavenia</h3>
	</div>
	<div class="panel-body">

		<form class="form-horizontal" role="form" method="post" action="">


			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Komentare</label>
				    <div class="col-sm-6">
				    <div class="radio">
						<label>
					    	<input type="radio" name="systemKomentar" id="optionsRadios1" value="1"  <?php if ($zobraz['system_komentar'] == '1') { echo 'checked="checked"'; } ?> >
					    Povolene
					  	</label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="systemKomentar" id="optionsRadios2" value="2" <?php if ($zobraz['system_komentar'] == '2') { echo 'checked="checked"'; } ?> >
					    Zakazane
					  </label>
					</div>
				</div>	
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Aktivacia emailom Newsletter</label>
				    <div class="col-sm-6">
				    <div class="radio">
						<label>
					    	<input type="radio" name="systemNewsletter" id="optionsRadios1" value="1"  <?php if ($zobraz['system_newsletter'] == '1') { echo 'checked="checked"'; } ?> >
					    Aktivovat emailom
					  	</label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="systemNewsletter" id="optionsRadios2" value="0" <?php if ($zobraz['system_newsletter'] == '0') { echo 'checked="checked"'; } ?> >
					    Netreba aktivovat
					  </label>
					</div>
				</div>	
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Startovaci slanok</label>
				    <div class="col-sm-6">
				    <div class="radio">
						<label>
					    	<input type="radio" name="systemStartClanok" id="optionsRadios1" value="blog"  <?php if ($zobraz['system_start_clanok'] == 'blog') { echo 'checked="checked"'; } ?> >
					    blog
					  	</label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="systemStartClanok" id="optionsRadios2" value="top" <?php if ($zobraz['system_start_clanok'] == 'top') { echo 'checked="checked"'; } ?> >
					    top
					  </label>
					</div>
				</div>	
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Slider</label>
				    <div class="col-sm-6">
				    <div class="radio">
				      		<label>
				      		<input type="radio" name="systemSliderAno" id="optionsRadios1" value="1"  <?php if ($zobraz['system_slider_ano'] == '1') { echo 'checked="checked"'; } ?> >
				      	    Ano
				      		</label>
				      	</div>
				      	<div class="radio">
				      	  <label>
				      	    <input type="radio" name="systemSliderAno" id="optionsRadios2" value="0" <?php if ($zobraz['system_slider_ano'] == '0') { echo 'checked="checked"'; } ?> >
				      	    Nie
				      	  </label>
				      	</div>
				</div>	
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Slider zobrazy</label>
				    <div class="col-sm-6">
				    <div class="radio">
						<label>
					    	<input type="radio" name="systemSlider" id="optionsRadios1" value="1"  <?php if ($zobraz['system_slider'] == '1') { echo 'checked="checked"'; } ?> >
					    Clanky
					  	</label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="systemSlider" id="optionsRadios2" value="2" <?php if ($zobraz['system_slider'] == '2') { echo 'checked="checked"'; } ?> >
					    Vybrane
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="systemSlider" id="optionsRadios2" value="3" <?php if ($zobraz['system_slider'] == '3') { echo 'checked="checked"'; } ?> >
					    Kombinovane
					  </label>
					</div>
				</div>	
			</div>
	 				
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Pocet Slidov</label>
			    <div class="col-sm-1">
			    	<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_slider_pocet']); ?>" name="systemSliderPocet">
			    </div>
			</div>		

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Captcha login</label>
				    <div class="col-sm-6">
				    <div class="radio">
						<label>
					    	<input type="radio" name="systemCaptchaLogin" id="optionsRadios1" value="1"  <?php if ($zobraz['system_captcha_login'] == '1') { echo 'checked="checked"'; } ?> >
					    Povolene
					  	</label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="systemCaptchaLogin" id="optionsRadios2" value="2" <?php if ($zobraz['system_captcha_login'] == '2') { echo 'checked="checked"'; } ?> >
					    Zakazane
					  </label>
					</div>
				</div>	
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Captcha register</label>
				    <div class="col-sm-6">
				    <div class="radio">
						<label>
					    	<input type="radio" name="systemCaptchaRegister" id="optionsRadios1" value="1"  <?php if ($zobraz['system_captcha_register'] == '1') { echo 'checked="checked"'; } ?> >
					    Povolene
					  	</label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="systemCaptchaRegister" id="optionsRadios2" value="2" <?php if ($zobraz['system_captcha_register'] == '2') { echo 'checked="checked"'; } ?> >
					    Zakazane
					  </label>
					</div>
				</div>	
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Captcha typ</label>
				    <div class="col-sm-6">
				    <div class="radio">
				      		<label>
				      		<input type="radio" name="systemCaptchaTyp" id="optionsRadios1" value="1"  <?php if ($zobraz['system_captcha_typ'] == '1') { echo 'checked="checked"'; } ?> >
				      	    Kod <small>D5f4A</small>
				      		</label>
				      	</div>
				      	<div class="radio">
				      	  <label>
				      	    <input type="radio" name="systemCaptchaTyp" id="optionsRadios2" value="2" <?php if ($zobraz['system_captcha_typ'] == '2') { echo 'checked="checked"'; } ?> >
				      	    Priklad <small>5+4=</small>
				      	  </label>
				      	</div>
				      	<div class="radio">
				      	  <label>
				      	    <input type="radio" name="systemCaptchaTyp" id="optionsRadios2" value="3" <?php if ($zobraz['system_captcha_typ'] == '3') { echo 'checked="checked"'; } ?> >
				      	    Priklad farebne<small>5+4=</small>
				      	  </label>
				      	</div>
				</div>	
			</div>

			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Pocet prezreti, interval pripocitania <small>Po hodinach</small></label>
			    <div class="col-sm-1">
				<input type="text" class="form-control" id="inputText3" value="<?php echo htmlspecialchars($zobraz['system_visit']); ?>" name="systemVisit">
			    </div>
			</div>

		    <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit3" class="btn btn-success btn-sm" name="submit3" value="submit33"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
				</div>
			</div>
		    <input type="hidden" name="id" value="<?php echo htmlspecialchars($zobraz['system_id']); ?>">
		</form>	
	</div>
</div>
	<?php

/*
$setting = $system->systemForm();

echo '<pre>';
print_r($setting);
echo '</pre>';
*/





##########################
# NEW LANG ADD
//$social->socialAdd();
##########################



?>