<?php



require 'config.php';



/*
backup_tables('localhost','root','', 'besom','*');


/* backup the db OR just a table */
/*/function backup_tables($host,$user,$pass,$name,$tables = '*')
{

  /*$link = mysql_connect($host,$user,$pass);
  mysql_select_db($name,$link);*//*
  $conn= new mysqli($host,$user,$pass,$name, '0','');

  //get all of the tables
  //if($tables == '*')
  //{
    $tables = array();
    $result = $conn->query('SHOW TABLES ');

    while($row = $result->fetch_row())
    {
      $tables[] = $row[0];

    }
  //}
 /* else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }*/

  //cycle through/*
 /* foreach($tables as $table)
  {
    $return="";
    $result = $conn->query('SELECT * FROM '.$table);
    $num_fields = $result->field_count;
    //print_r($num_fields);exit;
    $return.= 'DROP TABLE '.$table.';';
    $pomoc = $conn->query('SHOW CREATE TABLE '.$table);
    $row2 = $pomoc->fetch_row();
    $return.= "\n\n".$row2[1].";\n\n";

    for ($i = 0; $i < $num_fields; $i++) 
    {
      while($row = $result->fetch_row())
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) 
        {
          $row[$j] = htmlspecialchars($row[$j]);
         // $row[$j] = preg_replace("\n","\\n",$row[$j]);

          $row[$j] = preg_replace("/(\n){2,}/", "\\n", $row[$j]); 

          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";

  }

  //save file
  $handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
 // print_r($handle);exit;
  fwrite($handle,$return);
  fclose($handle);
//print_r($tables);
}
*/




/*  BRUTAL FORCE DOWNLOAD
$file_url = 'http://vybertvar.6f.sk/stahuj.php';
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url); // do the double-download-dance (dirty but worky)
*/
/*
$file = "http://vybertvar.6f.sk/stahuj.php"; 

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"$file\""); 

readfile ($file); 
*/



# ODHLAS
if (isset($_GET['odhlas'])) {
	$user->odhlas();
}


# SEDENIE UPLINULO LOGOUNT UNACTIVITY
$user->logoutUnactive('30'); // 1 minute

# DUAL LOGIN
if (isset($_SESSION['user_id'])) {
    $user->userDualLogin($_SESSION['user_id'],'Neplatne prihlasenie');
}


# AUTOMATICKE PRIHLASENIE CEY COOKIES
$user->loginCookies();

#############


###################################################
#####              NASTAVENIA                   ###
###################################################
# system stranky nastavenia,.... pre vsetko     ###
$system = $nastavenia->systemstranky();         ###
###################################################


?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo htmlspecialchars($system['system_title']); ?></title>
	<meta name="description" content="<?php echo htmlspecialchars($system['system_description']); ?>">
	<meta name="keywords" content="<?php echo htmlspecialchars($system['system_keywords']); ?>">
	<meta name="admin" content="<?php echo htmlspecialchars($system['system_admin_meno'] . ' | ' . $system['system_admin_email']); ?>">



	<meta name="copyright" content="&copy; 2013" />
	<meta name="application-name" content="Besom CMS" />
	<meta name="version" content="1.0">
	<meta name="author" content="ver4rs | ver4rs@gmail.com | besom@besom.sk"/>
	<meta name="email" content="besom@besom.sk"/>
	<meta name="website" content="Besom.sk"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo URL_ADRESA; ?>include/styl/styl.css" rel="stylesheet">

	<!-- Bootstrap core CSS -->
    <link href="<?php echo URL_ADRESA; ?>bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <!--  Sign in  -->
    <link href="<?php echo URL_ADRESA; ?>bootstrap/examples/signin/signin.css" rel="stylesheet">
    <!--  Justbotron  ***** fixed menu top v sign in  -->
    <link href="<?php echo URL_ADRESA; ?>bootstrap/examples/jumbotron/jumbotron.css" rel="stylesheet">
    <!--  Justbotron pokracovanie js na dropdown menu -->
    <script src="<?php echo URL_ADRESA; ?>bootstrap/assets/js/jquery.js"></script>
    <script src="<?php echo URL_ADRESA; ?>bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="<?php //echo URL_ADRESA; ?>bootstrap/examples/sticky-footer/sticky-footer.css" rel="stylesheet">

    <link href="<?php echo URL_ADRESA; ?>bootstrap/assets/css/pygments-manni.css" rel="stylesheet">



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>




	<!--  <link rel="stylesheet" type="text/css" href="<?php //echo URL_ADRESA; ?>include/styl/styl.css" /> -->

	<!-- FAVICON -->
	<link rel="shortcut icon" href="<?php echo URL_ADRESA . htmlspecialchars('images/' . $system['system_img']); ?>">
	<link rel="icon" href="images/<?php echo htmlspecialchars($system['system_img']); ?>" type="image/x-icon">

	<!-- <link rel="icon" type="image/gif" href=""> -->
	<!-- KONIEC FAVICON -->

	<meta property="og:title" content="<?php echo htmlspecialchars($system['system_title']); ?>">
	<meta property="og:url" content="<?php echo URL_ADRESA . htmlspecialchars('images/' . $system['system_img']); ?>">
	<meta property="og:image" content="<?php echo URL_ADRESA . htmlspecialchars('images/' . $system['system_img']); ?>">
	<meta property="og:site_name" content="<?php echo URL_ADRESA;  ?>">


	<!-- SLIDER  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="slider/coin-slider.js"></script>
    <link rel="stylesheet" href="slider/coin-slider-styles.css" type="text/css" />
	<!-- KONIEC SLIDER  -->


	<!-- VYSKOCENIE OKNA PO ULOZENI< UPRAVE,...  -->
	<script type="text/javascript">
	setTimeout(function(){
	     $('#odpoved').hide();
	},3000)

	</script>

	<style type="text/css" media="screen">
		/*#menu li { list-style: none; text-decoration: none; }
		#menu li:hover { background: #323232;  }
		#menu li a span:hover {  color: #FFFFFF; }
		#menu li ul { position: absolute; z-index: 9999; }
		#menu li ul li {margin-left: 100px; }
		#menu li:hover ul {  visibility: visible; display: block; position: relative;}*/
		#menu li a { color: #A1A1A1; }
		#menu li { margin: 0px; padding: 0px; border-top: 1px solid #454545; display: block; position: relative; background: #323232; }
		#menu ul li{ display:none; }
		#menu li a:hover { background: #323232; color: #EFEFEF;  }
		#menu li ul li a:hover { color: #CDCDCD;  }
		#menu li:hover ul li{ display: block; background: #323232; text-decoration: none; list-style: none;  }
		#menu li ul li { padding-bottom: 2px; }
		#menu li ul li { border-top: 1px solid transparent; }
		#menu li ul a { /*color: #ABABAB;*/ color: #656565; font-size: 14px; text-decoration: none;  }
		#menu li ul li a:hover ul { color: red; } 
	</style>

</head>
<body>
<?php

if ($user->jeprihlas() == false) {
	# NIEJE PRIHLASENY
	?>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
        	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
          	</button>
          	<a class="navbar-brand" target="_blank" href="<?php echo ADRESA . PRIECINOK; ?>"><?php echo htmlspecialchars($system['system_title']); ?></a>
        </div>
        <div class="navbar-collapse collapse">
        	<ul class="nav navbar-nav">
            	<li class="active"><a href="<?php echo URL_ADRESA; ?>">Home</a></li>
          </ul>

        	<ul class="nav navbar-nav navbar-right">
            	<li class="dropdown navbar-right">
              		<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #ffffff;"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/logo_besom.png'); ?>" class="img-thumbnail" width="100px;" style="margin: -12px 0px -12px 0px;" alt="Besom CMS"> <b class="caret"></b></a>
              		<ul class="dropdown-menu">
                		<li><a href="#" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Besom CMS</a></li>
                		<li><a href="#">About</a></li>
                		<li><a href="#" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Contact</a></li>
                		<li class="divider"></li>
                		<li class="dropdown-header">Others</li>
                		<li><a href="" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Documentation</a></li>
                		<li><a href="" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Download</a></li>
              		</ul>
            	</li>
          	</ul>
        </div>
      </div>

</div>

<div class="container form-group">

	<form action="" method="post" class="form-signin">
		<a href="#" target="_blank" onclick="javascript:location.assign('http://besom.sk');"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/logo_besom.png'); ?>" alt="Besom CMS"></a>
	    <h2 class="form-signin-heading">Please sign in</h2>
<?php
# OKNO ODPOVED
if (isset($_COOKIE['odpoved']) AND $_COOKIE['odpoved'] == '2') {
	# OK
	?>
	    <div class="alert alert-success">
			<strong>Ok</strong></br>
			logedd ? <a class="alert-link" href="#">Logged</a></br>
		</div>
	<?php
}
elseif (isset($_COOKIE['odpoved']) AND $_COOKIE['odpoved'] == '1') {
	# ERROR
	?>
	    <div class="alert alert-danger">
			<strong>Error!</strong></br>
			No account ? <a class="alert-link" href="#">Sign up</a></br>
			1. Invalid data
		</div>
	<?php
}
else {
	# NONE
	
}

?>

		<p class="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'user') { echo 'has-error'; } else { echo ' '; } } ?>">
			<label class="control-label" for="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'user') { echo 'inputError'; } else { echo ' '; } } ?>">User</label>
			<input type="text" name="username" value="" placeholder="username" class="form-control" id="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'user') { echo 'inputError'; } else { echo ' '; } } ?>">
		</p>
		<p class="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'password') { echo 'has-error'; } else { echo ' '; } } ?>">
			<label class="control-label" for="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'password') { echo 'inputError'; } else { echo ' '; } } ?>">Password</label>
			<input type="password" name="password" value="" placeholder="password" class="form-control" id="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'password') { echo 'inputError'; } else { echo ' '; } } ?>">
		</p>
		
	<?php
	# CAPTCHA
	if ($system['system_captcha_login'] == '1') {
		#POVOLENA
		?>
		<p class="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'captcha') { echo 'has-error'; } else { echo ' '; } } ?>">
			<label class="control-label" for="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'captcha') { echo 'inputError'; } else { echo ' '; } } ?>">Captcha</label>
			<input type="text" name="captcha" value="" placeholder="" class="form-control" id="<?php if(isset($_COOKIE['odpoved2'])) { if ($_COOKIE['odpoved2'] == 'captcha') { echo 'inputError'; } else { echo ' '; } } ?>">
		</p>
		<?php
	}

	?>	

		<label class="checkbox">
        	<input type="checkbox" <?php if (isset($_COOKIE['beid']) AND isset($_COOKIE['beuser']) AND isset($_COOKIE['bename']) AND isset($_COOKIE['beavatar']) AND isset($_COOKIE['bepassword']) AND isset($_COOKIE['beemail']) AND isset($_COOKIE['bekey'])) { echo 'checked="checked"'; } ?> name="remember" value="1"> Remember me
        </label>
		<p>
			<label for=""></label>
			<input type="submit" name="login" value="Sign in" placeholder="" class="btn btn-lg btn-primary btn-block">
		</p>
		<input type="hidden" name="cap" value="kod">
	</form>
</div>

<div id="footer">
    <div class="container">
        <p class="text-muted credit">Copyright © 2013 Besom.sk | Technology <a class="active" href="http://www.besom.sk"  target="_blank" oonclick="javascript:location.assign('http://www.besom.sk');">Besom.sk</a> |
        Powered by <a href="#" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Besom CMS</a> |
        Developed  <a href="https://facebook.com/ver4rs" target="_blank" oonclick="javascript:location.assign('https://facebook.com/ver4rs');">ver4rs</a>.</p>
    </div>
</div>

	<?php
}
else {

##################################################################################
######################					   #######################################
######################     PRIHLASENY      #######################################
######################					   #######################################
##################################################################################



#*************************************************************************************************************************************


########################################################################################################################################
#################################################                                   ####################################################
#################################################           HORNY RAMEC             ####################################################
#################################################                                   ####################################################
########################################################################################################################################
?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-collapse collapse">
        	<ul class="nav navbar-nav">
            	<li class="dropdown active">
              		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/logo_besom.png'); ?>" class="img-thumbnail" width="100px;" style="margin: -12px 0px -12px 0px;" alt="Besom CMS">  <b class="caret"></b></a>
              		<ul class="dropdown-menu">
                		<li><a href="#" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Besom CMS</a></li>
                		<li><a href="#">About</a></li>
                		<li><a href="#" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Contact</a></li>
                		<li class="divider"></li>
                		<li class="dropdown-header">Others</li>
                		<li><a href="#" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Documentation</a></li>
                		<li><a href="#" target="_blank" onclick="javascript:location.assign('http://www.besom.sk');">Download</a></li>
              		</ul>
            	</li>
          	</ul>

          	<ul class="nav navbar-nav navbar-right">
          	<?php 
          	#############################################################################
          	# ACTIVE lang  														  #######
          	#############################################################################
          	$lang_active = $nastavenia->systemLangActive(); // active lang        #######
          	#############################################################################
          	?>
            	<li class="dropdown navbar-right">
              		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo htmlspecialchars(ADRESA . PRIECINOK . $lang_active['jazyk_ikona']); ?>" class="img-rounded" width="25" style="padding-right: 3px;"><?php echo htmlspecialchars($lang_active['jazyk_skratka']); ?> <b class="caret"></b></a>
              		<ul class="dropdown-menu">
                		<li class="divider"></li>
                		<li class="active"><a href="<?php echo URL_ADRESA . htmlspecialchars('index.php?lang=' . $lang_active['jazyk_short']); ?>"><img src="<?php echo htmlspecialchars(ADRESA . PRIECINOK . $lang_active['jazyk_ikona']); ?>" alt="<?php echo htmlspecialchars($lang_active['jazyk_nazov']); ?>" class="img-rounded" width="25" style="padding-right: 3px; vertical-align: middle; "><?php echo htmlspecialchars($lang_active['jazyk_nazov']); ?></a></li>
                		<li class="divider"></li>
                		<?php
	$value = $nastavenia->systemLang();
	//  $bes_lang[$nazov][$ikona][$skratka][$short][$active][] = $lang_t; 
	//            Slovak 	url      SK       sk      1 or 0 	 
	foreach ($value as $lang_nazov => $valu1_z) {
		foreach ($valu1_z as $lang_ikona => $valu2_z) {
			foreach ($valu2_z as $lang_skratka => $valu3_z) {
				foreach ($valu3_z as $lang_short => $valu4_z) {
					foreach ($valu4_z as $lang_active1 => $valu5_z) {
						?>
						<li><a href="<?php echo URL_ADRESA . htmlspecialchars('index.php?lang=' . $lang_short); ?>"><img src="<?php echo htmlspecialchars(ADRESA . PRIECINOK . $lang_ikona); ?>" alt="<?php echo htmlspecialchars($lang_nazov); ?>" class="img-rounded" width="25" style="padding-right: 3px; vertical-align: middle; "><?php echo htmlspecialchars($lang_nazov); ?></a></li>
						<?php
					}
				}
			}
		}
	}


                		?>
              		</ul>
            	</li>
          	</ul>


          	<ul class="nav navbar-nav navbar-right">
            	<li class="dropdown navbar-right">
              		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<?php
					if (file_exists('../' . IMAGE_ARTICLES_MINI . htmlspecialchars($_SESSION['avatar'] . '.jpg'))) {
						?><img src="<?php echo ADRESA . PRIECINOK . IMAGE_ARTICLES_MINI . htmlspecialchars($_SESSION['avatar']); ?>" class="img-rounded" width="30px" style="margin: -2px;" alt="<?php echo htmlspecialchars($_SESSION['name']); ?>" title="<?php echo htmlspecialchars($_SESSION['name']); ?>" id="arimg"><?php
					}
					else {
						?><img src="<?php echo ADRESA . PRIECINOK . htmlspecialchars('images/user.gif'); ?>" class="img-rounded" width="30px" style="margin: -6px 0px -6px 0px;" alt="<?php echo htmlspecialchars($_SESSION['name']); ?>" title="<?php echo htmlspecialchars($_SESSION['name']); ?>" id="arimg"><?php
					}
					?>

              		<?php echo htmlspecialchars($_SESSION['user']); ?> <b class="caret"></b></a>
              		<ul class="dropdown-menu">
              			<li class="divider"></li>
                		<li class="dropdown-header"><?php echo htmlspecialchars($_SESSION['name']); ?></li>
                		<li class="dropdown-header"><?php echo htmlspecialchars($_SESSION['email']) ?></li>
              			<li class="divider"></li>
                		<li><a href="#" ><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Profil</a></li>
                		<li><a href="#"><i class="glyphicon glyphicon-cog"></i>&nbsp;Nastavenia</a></li>
              			<li class="divider"></li>
                		<li class="dropdown-header">Others</li>
                		<li><a href="?odhlas" onClick="javascript:location.href='?odhlas';"><i class="glyphicon glyphicon-off"></i>&nbsp;Odhlasit</a></li>
                		<li class="divider"></li>
              		</ul>
            	</li>
          	</ul>

          	<ul class="nav navbar-nav navbar-right">
            	<li class="navbar-brand-right">
              		<a href="<?php echo ADRESA . PRIECINOK; ?>" class="dropdown-toggle" target="_blank"><i class="glyphicon glyphicon-home"></i>&nbsp;<?php echo htmlspecialchars($system['system_title']); ?></a>
              	</li>
            </ul>
          	
        </div>
      </div>
</div>
<?php


########################################################################################################################################
#################################################                                 ######################################################
#################################################       KONIEC HORNY RAMEC        ######################################################
#################################################                                 ######################################################
########################################################################################################################################


########################################################################################################################################
#################################################                                 ######################################################
#################################################         TELO STRANKY            ######################################################
#################################################                                 ######################################################
########################################################################################################################################
?>
<div class="wrap" style="width: 95%;">

    <div class="row">

        <div class="col-xs-8 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation">

          <div class="sidebar-nav" style=" position: fixed; width: 200px; ">			
			<?php
				# NAVIGATION MENU BESOM
				$Menu = $nastavenia->menuAdmin($lang_active['jazyk_short']);				
			?>
          </div><!-- menu end  -->

        </div><!--/ end sidebar-->


        <div class="col-sm-10" style="float: right; ">

        	<ol class="breadcrumb" style="background: #eee; ">
				<li style=""><a href="#" style="font-size: 16px; color: #565656; ">Home</a></li>
				<li><a href="#">Library</a></li>
				<li class="active">Data</li>

				<i><?php echo $lg['nosekcia']; ?></i>
			</ol>
			

			<?php
				# OKNO ODPOVED
				/*if (isset($_COOKIE['odpoved']) AND $_COOKIE['odpoved'] == '1') {
					# OK
					?>
					<div class="alert alert-danger">
						<button class="close" data-dismiss="alert" type="button">X</button>
						<strong>Whoops!</strong>
						This is error. No nono.
						<a class="alert-link" href="#">This is</a>.
					</div>
					<?php
				}
				elseif (isset($_COOKIE['odpoved']) AND $_COOKIE['odpoved'] == '2') {
					# ERROR
					?>
					<div class="alert alert-success" id="odpoved">
						<button class="close" data-dismiss="alert" type="button">X</button>
						<strong>Well done!</strong>
						...
						<a class="alert-link" href="#">This is</a>.
					</div>
					<?php
				}
				else {
					# NONE
				}*/

				//(isset($_GET['menu'])) ? $men = $_GET['menu'] : $men = '';
				//(isset($_GET['action'])) ? $act = $_GET['action'] : $act = '';
				//$nastavenia->urlMenuGenerate($men, $act);

			##########################################################################################
			$me = 'domov';										######################################
			if (isset($_GET['menu'])) {							######################################
				$me = $_GET['menu'];							######################################
			}													######################################
			##########################################################################################
			$action = '';										######################################
			if (isset($_GET['action'])) {						######################################
			    $action = $_GET['action'];						######################################
			}													######################################
			##########################################################################################
			//$nastavenia->content($me, $action, $lang_active['jazyk_short']);	######################
			##########################################################################################
				

			//echo htmlspecialchars_decode($nastavenia->contentArray($_GET['menu'], $_GET['action'], $lang_active['jazyk_short']));
			
			$_GET['menu'] = (isset($_GET['menu'])) ? $_GET['menu'] : '';
			$urlA = (isset($_GET['action'])) ? $_GET['action'] : $_GET['menu'];

			$errorPage = 'errorPage.php';

			# STATIS CONTENT
			if (isset($_GET['menu'])) {

				echo '<pre>';
				/*echo "PHP_SELF : " . $_SERVER['PHP_SELF'] . "<br />"; 
				echo "GATEWAY_INTERFACE : " . $_SERVER['GATEWAY_INTERFACE'] . "<br />"; 
				echo "SERVER_ADDR : " . $_SERVER['SERVER_ADDR'] . "<br />"; 
				echo "SERVER_NAME : " . $_SERVER['SERVER_NAME'] . "<br />"; 
				echo "SERVER_SOFTWARE : " . $_SERVER['SERVER_SOFTWARE'] . "<br />"; 
				echo "SERVER_PROTOCOL : " . $_SERVER['SERVER_PROTOCOL'] . "<br />"; 
				echo "REQUEST_METHOD : " . $_SERVER['REQUEST_METHOD'] . "<br />"; 
				echo "REQUEST_TIME : " . $_SERVER['REQUEST_TIME'] . "<br />"; 
				echo "REQUEST_TIME_FLOAT : " . $_SERVER['REQUEST_TIME_FLOAT'] . "<br />"; 
				echo "QUERY_STRING : " . $_SERVER['QUERY_STRING'] . "<br />"; 
				echo "DOCUMENT_ROOT : " . $_SERVER['DOCUMENT_ROOT'] . "<br />"; 
				echo "HTTP_ACCEPT : " . $_SERVER['HTTP_ACCEPT'] . "<br />"; 
				echo "HTTP_ACCEPT_CHARSET : " . $_SERVER['HTTP_ACCEPT_CHARSET'] . "<br />"; 
				echo "HTTP_ACCEPT_ENCODING : " . $_SERVER['HTTP_ACCEPT_ENCODING'] . "<br />"; 
				echo "HTTP_ACCEPT_LANGUAGE : " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "<br />"; 
				echo "HTTP_CONNECTION : " . $_SERVER['HTTP_CONNECTION'] . "<br />"; 
				echo "HTTP_HOST : " . $_SERVER['HTTP_HOST'] . "<br />"; 
				echo "HTTP_REFERER : " . $_SERVER['HTTP_REFERER'] . "<br />"; 
				echo "HTTP_USER_AGENT : " . $_SERVER['HTTP_USER_AGENT'] . "<br />"; 
				echo "HTTPS : " . $_SERVER['HTTPS'] . "<br />"; 
				echo "REMOTE_ADDR : " . $_SERVER['REMOTE_ADDR'] . "<br />"; 
				echo "REMOTE_HOST : " . $_SERVER['REMOTE_HOST'] . "<br />"; 
				echo "REMOTE_PORT : " . $_SERVER['REMOTE_PORT'] . "<br />"; 
				echo "REMOTE_USER : " . $_SERVER['REMOTE_USER'] . "<br />"; 
				echo "REDIRECT_REMOTE_USER : " . $_SERVER['REDIRECT_REMOTE_USER'] . "<br />"; 
				echo "SCRIPT_FILENAME : " . $_SERVER['SCRIPT_FILENAME'] . "<br />"; 
				echo "SERVER_ADMIN : " . $_SERVER['SERVER_ADMIN'] . "<br />"; 
				echo "SERVER_PORT : " . $_SERVER['SERVER_PORT'] . "<br />"; 
				echo "SERVER_SIGNATURE : " . $_SERVER['SERVER_SIGNATURE'] . "<br />"; 
				echo "PATH_TRANSLATED : " . $_SERVER['PATH_TRANSLATED'] . "<br />"; 
				echo "SCRIPT_NAME : " . $_SERVER['SCRIPT_NAME'] . "<br />"; 
				echo "REQUEST_URI : " . $_SERVER['REQUEST_URI'] . "<br />"; 
				echo "PHP_AUTH_DIGEST : " . $_SERVER['PHP_AUTH_DIGEST'] . "<br />"; 
				echo "PHP_AUTH_USER : " . $_SERVER['PHP_AUTH_USER'] . "<br />"; 
				echo "PHP_AUTH_PW : " . $_SERVER['PHP_AUTH_PW'] . "<br />"; 
				echo "AUTH_TYPE : " . $_SERVER['AUTH_TYPE'] . "<br />"; 
				echo "PATH_INFO : " . $_SERVER['PATH_INFO'] . "<br />"; 
				echo "ORIG_PATH_INFO : " . $_SERVER['ORIG_PATH_INFO'] . "<br />"; */
				//echo $nastavenia->contentArray($_GET['menu'], $_GET['action'], $lang_active['jazyk_short']);
				echo '</pre>';
				//$nastavenia->tlacMenuSekcie($_GET['menu']);

				# OKNO ODPOVED
				if (isset($_COOKIE['odpoved']) AND $_COOKIE['odpoved'] == '1') {
					# OK
					?>
					<div class="alert alert-danger">
						<button class="close" data-dismiss="alert" type="button">X</button>
						<strong>Whoops!</strong>
						This is error. No nono.
						<a class="alert-link" href="#">This is</a>.
					</div>
					<?php
				}
				elseif (isset($_COOKIE['odpoved']) AND $_COOKIE['odpoved'] == '2') {
					# ERROR
					?>
					<div class="alert alert-success" id="odpoved">
						<button class="close" data-dismiss="alert" type="button">X</button>
						<strong>Well done!</strong>
						...
						<a class="alert-link" href="#">This is</a>.
					</div>
					<?php
				}
				else {
					# NONE
				}


				if ($_GET['menu'] == 'nastavenia') {

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {

						if (isset($_GET['action'])) {
						
							if ($_GET['action'] == 'position') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/nastavenia/lang.lib.php';
									include 'system/nastavenia/position.lib.php';
									include 'system/nastavenia/position.php';
								}
								else {
									include $errorPage;
								}

							}
							elseif ($_GET['action'] == 'language') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/nastavenia/lang.lib.php';
									include 'system/nastavenia/language.lib.php';
									include 'system/nastavenia/language.php';

								}
								else {
									include $errorPage;
								}

							}		
							elseif ($_GET['action'] == 'social_network') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/nastavenia/lang.lib.php';
									include 'system/nastavenia/social_network.lib.php';
									include 'system/nastavenia/social_network.php';

								}
								else {
									include $errorPage;
								}

							}		
							elseif ($_GET['action'] == 'system_setting') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/nastavenia/lang.lib.php';
									include 'system/nastavenia/system_setting.lib.php';
									include 'system/nastavenia/system_setting.php';
								}
								else {
									include $errorPage;
								}

							}		
							else {
								#
							}

						}

					}
					else {
						include $errorPage;
					}	
				}
				elseif ($_GET['menu'] == 'menu') {

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {

						if (isset($_GET['action'])) {
						
							if ($_GET['action'] == 'show') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
									
									include 'system/menu/lang.lib.php';
									include 'system/menu/menu.lib.php';
									include 'system/menu/menu.php';

								}
								else {
									include $errorPage;
								}

							}
							else {
								#
							}

						}

					}
					else {
						include $errorPage;
					}

				}
				elseif ($_GET['menu'] == 'statistics') {

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {

						if (isset($_GET['action'])) {
						
							if ($_GET['action'] == 'counter') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
									
									include 'system/statistics/lang.lib.php';
									include 'system/statistics/counter.lib.php';
									include 'system/statistics/counter.php';

								}
								else {
									include $errorPage;
								}

							}
							elseif ($_GET['action'] == 'login') {
								
								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
									
									include 'system/statistics/lang.lib.php';
									include 'system/statistics/login.lib.php';
									include 'system/statistics/login.php';

								}
								else {
									include $errorPage;
								}
							}
							else {
								#
							}

						}

					}
					else {
						include $errorPage;
					}

				}
				elseif ($_GET['menu'] == 'pages') {

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {


            if (isset($_GET['action'])) {
              
              if ($_GET['action'] == 'text') {

                #Authorization
                if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
                  include 'system/stranka/lang.lib.php';
                  include 'system/stranka/text.lib.php';
                  include 'system/stranka/text.php';
                }
                else {
                  include $errorPage;
                } 


              }
              elseif ($_GET['action'] == 'comment') {
                
                #Authorization
                if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
                  include 'system/stranka/lang.lib.php';
                  include 'system/stranka/comment.lib.php';
                  include 'system/stranka/comment.php';
                }
                else {
                  include $errorPage;
                } 
              }
              else {

              }
            } 


						//include 'system/stranka/lang.lib.php';
						//include 'system/stranka/pages.lib.php';
						//include 'system/stranka/pages.php';
					}
					else {
						include $errorPage;
					}

				}
        elseif ($_GET['menu'] == 'gallery') {

          #Authorization
          if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {


            if (isset($_GET['action'])) {
              
              if ($_GET['action'] == 'album') {

                #Authorization
                if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
                  include 'system/gallery/lang.lib.php';
                  include 'system/gallery/album.lib.php';
                  include 'system/gallery/album.php';
                }
                else {
                  include $errorPage;
                } 


              }
              elseif ($_GET['action'] == 'photos') {
                
                #Authorization
                if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
                  include 'system/gallery/lang.lib.php';
                  include 'system/gallery/photos.lib.php';
                  include 'system/gallery/photos.php';
                }
                else {
                  include $errorPage;
                } 
              }
              else {

              }
            } 


            //include 'system/stranka/lang.lib.php';
            //include 'system/stranka/pages.lib.php';
            //include 'system/stranka/pages.php';
          }
          else {
            include $errorPage;
          }

        }
				elseif ($_GET['menu'] == 'newsletter') {

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {

						if (isset($_GET['action'])) {
							
							if ($_GET['action'] == 'contact') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
									include 'system/newsletter/lang.lib.php';
									include 'system/newsletter/contact.lib.php';
									include 'system/newsletter/contact.php';
								}
								else {
									include $errorPage;
								}	


							}
							elseif ($_GET['action'] == 'emails') {
								
								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
									include 'system/newsletter/lang.lib.php';
									include 'system/newsletter/emails.lib.php';
									include 'system/newsletter/emails.php';
								}
								else {
									include $errorPage;
								}	
							}
							else {

							}
						}	

						//include 'system/newsletter/lang.lib.php';
						//include 'system/newsletter/newsletter.lib.php';
						//include 'system/newsletter/newsletter.php';
					}
					else {
						include $errorPage;
					}

				}
				elseif ($_GET['menu'] == 'company') {
					
					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
						
						if (isset($_GET['action'])) {
							
							if ($_GET['action'] == 'group') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/company/lang.lib.php';
									include 'system/company/group.lib.php';
									include 'system/company/group.php';
								}
								else {
									include $errorPage;
								}

							}
							elseif ($_GET['action'] == 'right') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/company/lang.lib.php';
									include 'system/company/right.lib.php';
									include 'system/company/right.php';
								}
								else {
									include $errorPage;
								}

							}
							elseif ($_GET['action'] == 'function') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/company/lang.lib.php';
									include 'system/company/function.lib.php';
									include 'system/company/function.php';
								}
								else {
									include $errorPage;
								}

							}
							elseif ($_GET['action'] == 'users') {

								#Authorization
								if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
								
									include 'system/company/lang.lib.php';
									include 'system/company/users.lib.php';
									include 'system/company/users.php';
								}
								else {
									include $errorPage;
								}

							}
							else {
								#
							}
						}

					}
					else {
						include $errorPage;
					}
				}
				elseif ($_GET['menu'] == 'domov') {
					# DOMOV

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
						include 'system/domov/lang.lib.php';
						include 'system/domov/domov.lib.php';
						include 'system/domov/domov.php';
					}
					else {
						include $errorPage;
					}

				}
				elseif ($_GET['menu'] == 'slider') {
					# SLIDER

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {

						include 'system/slider/lang.lib.php';
						include 'system/slider/slider.lib.php';
						include 'system/slider/slider.php';
					}
					else {
						include $errorPage;
					}
				}
				elseif ($_GET['menu'] == 'modul') {
					# MODUL

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
						include 'system/modul/lang.lib.php';
						include 'system/modul/modul.lib.php';
						include 'system/modul/modul.php';
					}
					else {
						include $errorPage;
					}						
				}
				elseif ($_GET['menu'] == 'widget') {
					# MODUL

					#Authorization
					if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
						include 'system/widget/lang.lib.php';
						include 'system/widget/widget.lib.php';
						include 'system/widget/widget.php';
					}
					else {
						include $errorPage;
					}
				}
				else {
					# DOMOV
					#Authorization
					/*if ($nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA) ) {
						include 'system/domov/lang.lib.php';
						include 'system/domov/domov.lib.php';
						include 'system/domov/domov.php';
					}
					else {
						include $errorPage;
					}*/
					header("Location: " . URL_ADRESA . htmlspecialchars('?menu=domov'));
					exit();
				}


			}
			else {
				//echo 'nie menu';
          header("Location: " . URL_ADRESA . htmlspecialchars('?menu=domov'));
          exit();
			}


			?>
        


        </div><!--/span-->


    </div><!--/row-->


</div><!--/.container-->






<?php
}
?>
<endora>
</body>
</html>