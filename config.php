<?php


# CONFIG

session_start();

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    //define("ROOT","");
    define("DBSERVER","localhost");
    define("DBUSER","root");
    define("DBPASS","");
    define("DBNAME","besom");

    define("DB_PREFIX", "_12345");

    define('ADRESA', 'http://localhost/');
    define('PRIECINOK', 'besom/');
    define('ADMINISTRATION', 'besom/');
    define('URL_ADRESA', ADRESA . PRIECINOK);
}
else {

//define("ROOT","");
define("DBSERVER","localhost");
define("DBUSER","name");
define("DBPASS","pass");
define("DBNAME","databaseName");

define("DB_PREFIX", "_12345");

define('ADRESA', 'http://besom.6f.sk/');
define('PRIECINOK', '');
define('ADMINISTRATION', 'besom/');
define('URL_ADRESA', ADRESA . PRIECINOK);
} // localhost/server

define('DESIGN', 'besom/'); // template folder
define('TEMPLATE_URL', URL_ADRESA . 'template/' . DESIGN);
define('TEMPLATE_DESIGN', 'template/' . DESIGN);

define('IMAGE_ARTICLES_MINI', 'upload/articles/mini/');
define('IMAGE_ARTICLES_NORMAL', 'upload/articles/normal/');

define('IMAGE_USER_MINI', 'upload/user/mini/');
define('IMAGE_USER_NORMAL', 'upload/user/normal/');
define('IMAGE_USER_ORIGINAL', 'upload/user/original/');

define('IMAGE_LANG', 'upload/lang/');

define('IMAGE_SYSTEM', 'upload/system/');


define('IMAGE_COMMENT', 'upload/comment/');


define('GALLERY_CESTA_MINI', 'upload/gallery/mini/');
define('GALLERY_CESTA_ORIGINAL', 'upload/gallery/original/');

define('IMAGE_SLIDER', 'upload/slider/');

define('IMAGE_SOCIAL', 'upload/social/');

define('MODUL_CESTA', 'modul/');





//}
//echo ROOT; exit;
$conn = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);

if ($conn->connect_error){
    throw new Exception($conn->connect_errno." ".$conn->connect_error,0);
    die('ERROR. No database');
}

$table = 'be_system' . DB_PREFIX;
if ($a = $conn->query("SHOW TABLES ")) {
    if ($a->num_rows == FALSE) {
        die('ERROR. No connect to database and no tables in database.');
    }
}
else {
    die('ERROR. No connect to database and no tables in database.');
}

// saved to DB format
//$conn->query("set names 'utf8'");


# Function
include 'function.php';

# function codes facebook fan page,...
include 'codes/facebook/facebook.php';

# MAIERL
//include("besom/mailer/class.phpmailer.php"); 



# LANG
$cas_lang = time() + ((60*60)*24); // 24 h

if(empty($_COOKIE["lang1"]) AND empty($_COOKIE["lang2"])) {
	$nas = $conn->query('SELECT * FROM be_jazyk' . DB_PREFIX . ' WHERE jazyk_default =1 ');
	if ($nas->num_rows != 0) {
		$nas_t = $nas->fetch_assoc();

		$lang_short = $nas_t['jazyk_short']; // sk
		$lang_nazov = $nas_t['jazyk_nazov']; // Slovak

	}
	else {

		$lang_short ="sk";
		$lang_nazov = 'Slovak';

	}

}
else { // neni v DB ulozene
    $nas = $conn->query('SELECT * FROM be_jazyk' . DB_PREFIX . ' 
                            WHERE jazyk_short ="' . $conn->real_escape_string($_COOKIE['lang1']) . '" AND 
                            jazyk_nazov ="' . $conn->real_escape_string($_COOKIE['lang2']) . '" ');
    if ($nas->num_rows != FALSE AND file_exists('lang/' . $_COOKIE['lang2'] . '.php')) {
        $nas_t = $nas->fetch_assoc();

        //$lang_short = $_COOKIE['lang1']; //$nas_t['jazyk_short']; // sk
        //$lang_nazov = $_COOKIE['lang2']; //$nas_t['jazyk_nazov']; // Slovak

        if (file_exists('lang/' . $nas_t['jazyk_nazov'] . '.php')) {

            $lang_short = $nas_t['jazyk_short']; // sk
            $lang_nazov = $nas_t['jazyk_nazov']; // Slovak
        }
        else {
            $lang_short ="sk";
            $lang_nazov = 'Slovak';
        }

    }
    else {
        // default
        $lang_short ="sk";
        $lang_nazov = 'Slovak';

    }

    setcookie('lang1', '', time()-3600);
    setcookie('lang2', '', time()-3600);
}

if (isset($_GET['lang'])) {
    $sel = $conn->query('SELECT * FROM be_jazyk' . DB_PREFIX . ' 
                    WHERE jazyk_short="' . $conn->real_escape_string($_GET['lang']) . '" ');

    if ($sel->num_rows != FALSE) {

        $sel1 = $sel->fetch_assoc();

        setcookie('lang1', htmlspecialchars($sel1['jazyk_short']), $cas_lang);
        setcookie('lang2', htmlspecialchars($sel1['jazyk_nazov']), $cas_lang);

        //$lang_short = $sel1["jazyk_nazov"];
		//$lang_nazov = $sel1['jazyk_nazov'];
        //$_SESSION["auto_detect"]=true;
        //echo "<script> document.location.reload();</script>";
        
        if (empty($_SERVER["HTTP_REFERER"])) {
            header("Location: " . URL_ADRESA);
            exit();
        }
        else {
            header("Location: ". $_SERVER["HTTP_REFERER"]);
            exit();
        }

    }
    else {
        $lang_short ="sk";
        $lang_nazov = 'Slovak';
    }
}

if (file_exists('lang/' . $lang_nazov . '.php')) {
    include 'lang/' . $lang_nazov . '.php';
}
else {
    include 'lang/Slovak.php';
}
//echo 'lang/' . $lang_nazov . '.php';
# KONIEC LANG




#library
foreach(glob("library/*.php") as $filename){
    include($filename);
}


$prefix = DB_PREFIX;

//$user = new user($conn, $prefix);

$funkcia = new funkcia($conn, $prefix, $lg);
$menu = new menu($conn, $prefix, $lg);
$text = new text($conn, $prefix, $lg);
$slider = new slider($conn, $prefix, $lg);
//$social = new social($conn, $prefix, $lg);
$captcha = new captcha($conn, $prefix, $lg);
$komentar = new komentar($conn, $prefix, $lg);
$systems = new system($conn, $prefix, $lg);
$pocitadlo = new pocitadlo($conn, $prefix, $lg);
//$toparticle = new toparticle($conn, $prefix, $lg);
$modul = new modul($conn, $prefix, $lg);
$widget = new widget($conn, $prefix, $lg, $lang_short);
$datum = new datum($conn, $prefix, $lg);



# NACITANIE MODULOV ->>>>
# CLASS TRIEDY ->>>>>>>>>
$modul->nacitajobjektymodulov(MODUL_CESTA);



if (isset($_SESSION['user_id'])) {
    # code...
}
else {
    $_SESSION['user_id'] = 0;
}


?>