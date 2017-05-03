<?php



# CONFIG

/* set the cache limiter to 'private' */

//session_cache_limiter('private');
//$cache_limiter = session_cache_limiter();

/* set the cache expire to 30 minutes */
//session_cache_expire("nocache");
//$cache_expire = session_cache_expire();
//echo dirname($_SERVER['DOCUMENT_ROOT']);
//ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/www/besom/besom/tmp'));
//session_start();
//error_reporting(-1);
//error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
	//define("ROOT","");
	define("DBSERVER","localhost");
	define("DBUSER","root");
	define("DBPASS","");
	define("DBNAME","besom");

    define("AA", '0');
    define("DBSOCKET", "");

	define("DB_PREFIX", "_12345");

	define('ADRESA', 'http://localhost/');
	define('PRIECINOK', 'besom/');
	define('ADMINISTRATION', 'besom/');
	define('URL_ADRESA', ADRESA . PRIECINOK . ADMINISTRATION);

    ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/www/besom/besom/tmp'));
    session_start();
    //session_id();
    //session_regenerate_id();
}
else {
	//define("ROOT","");
	define("DBSERVER","localhost");
    define("DBUSER","name");
    define("DBPASS","password");
    define("DBNAME","databaseName");

    define("AA", '0');
    define("DBSOCKET", "/tmp/mysql51.sock");

	define("DB_PREFIX", "_12345");

	define('ADRESA', 'http://www.besom.6f.sk/');
	define('PRIECINOK', '');
	define('ADMINISTRATION', 'besom/');
	define('URL_ADRESA', ADRESA . PRIECINOK . ADMINISTRATION);


    // ini_set('session.save_path',realpath('/data/web/besom.sk/web/besom/tmp'));
    session_start();
    //session_id();
    //session_regenerate_id();
    //     /data/web/besom.sk               /data/b/e/besom.sk/web/besom/
    //ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/web/' . ADMINISTRATION . 'tmp/'));
    //session_start();
    //session_save_path('/data/web/besom.sk/web/besom/tmp');

}


define('DESIGN', 'default/');
define('TEMPLATE_URL', URL_ADRESA . 'template/' . DESIGN);
define('TEMPLATE_DESIGN', 'template/' . DESIGN);

define('IMAGE_ARTICLES_MINI', 'upload/articles/mini/');
define('IMAGE_ARTICLES_NORMAL', 'upload/articles/normal/');
define('IMAGE_ARTICLES_ORIGINAL', 'upload/articles/original/');
define('IMAGE_ARTICLES_SLIDER', 'upload/articles/slider/');

define('IMAGE_USER_MINI', 'upload/user/mini/');
define('IMAGE_USER_NORMAL', 'upload/user/normal/');
define('IMAGE_USER_ORIGINAL', 'upload/user/original/');

define('IMAGE_SLIDER', 'upload/slider/');

define('IMAGE_LANG', 'upload/lang/');

define('IMAGE_SOCIAL', 'upload/social/');

define('IMAGE_MODUL', 'upload/modul/');

define('IMAGE_SYSTEM', 'upload/system/');

define('IMAGE_WIDGET', 'upload/widget/');

define('IMAGE_COMMENT', 'upload/comment/');

define('MODUL_CESTA', 'modul/');

define('MENU_CESTA', 'content/');

define('GALLERY_CESTA_MINI', 'upload/gallery/mini/');
define('GALLERY_CESTA_ORIGINAL', 'upload/gallery/original/');

define('CRYPT_REMEMBER', '3Cr4w6w67e6');

define('SYSTEM_CESTA', 'system/');





//}
//echo ROOT; exit;
$conn= new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);

if ($conn->connect_error){
    throw new Exception($conn->connect_errno." ".$conn->connect_error,0);
    die('Error database');
}

$table = 'be_system' . DB_PREFIX;
if ($a = $conn->query("SHOW TABLES ")) {
    if ($a->num_rows == FALSE) {
        die('ERROR. No connect to database and no tables in database.');
    }
    else {
        /*$tables = array();
        while ($tlac = $a->fetch_row()) {
            echo $tlac['0'] . '</br>';
            //$tables[] = $tlac['0'];
        }*/

    }
}
else {
    die('ERROR. No connect to database and no tables in database.');
}

//$conn->query("set names 'utf16'");

/*
        $p=array();
        $sel= $conn->query('SELECT * FROM be_user' . DB_PREFIX . ' ');
        if ($sel->num_rows != FALSE) {
            

            while($row= $sel->fetch_assoc()){
            array_push($p, array("user_id"=>$row["user_id"],"user"=>$row["username"],"name"=>$row["user_meno"],"avatar"=>$row["user_avatar"],"email"=>$row["user_email"], "password"=>$row['user_password']));
        }
        //$p=array(array("key"=>"dddd", "value"=>"hodnota";),array("key"=>"dddd", "value"=>"hodnota"));
        file_put_contents('tmp/temp.dat', json_encode($p));
        }
        else {
            echo 'nieee';
        }*/



# LANG
$cas_lang = time() + ((60*60)*24); // 24 h

if (empty($_COOKIE["lang1"]) AND empty($_COOKIE["lang2"])) {

    $nas = $conn->query('SELECT * FROM be_jazyk' . DB_PREFIX . ' WHERE jazyk_default =1 ');

    if ($nas->num_rows != FALSE) {
        $nas_t = $nas->fetch_assoc();

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

        $lang_short ="sk";
        $lang_nazov = 'Slovak';

    }

}
else {

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
    //echo 'je lang ' . $_GET['lang'] . '  ';
    $sel = $conn->query('SELECT * FROM be_jazyk' . DB_PREFIX . ' 
                            WHERE jazyk_short ="' . $conn->real_escape_string($_GET['lang']) . '" ');

    if ($sel->num_rows != 0 AND file_exists('lang/' . $lang_nazov . '.php')) {

        $sel1 = $sel->fetch_assoc();

        setcookie('lang1', htmlspecialchars($sel1['jazyk_short']), $cas_lang);
        setcookie('lang2', htmlspecialchars($sel1['jazyk_nazov']), $cas_lang);


        //$lang_short = $sel1["jazyk_nazov"];
        //$lang_nazov = $sel1['jazyk_nazov'];
        //$_SESSION["auto_detect"]=true;
        //echo "<script> document.location.reload();</script>";

        if(empty($_SERVER["HTTP_REFERER"])){
            header("Location: /");
            exit();
        }
        else {
            header("Location: ". $_SERVER["HTTP_REFERER"]);
            exit();
        }

    }
    else {
        //echo 'smola nenaslo ta v db';
    }
}
include 'lang/' . $lang_nazov . '.php';
//echo 'lang/' . $lang_nazov . '.php';
//echo 'lang/' . $lang_nazov . '.php';
# KONIEC LANG



$prefix = DB_PREFIX;

#fucntion
require 'function.php';
$funkcia = new funkcia($conn, $prefix, $lg);



#library
foreach(glob("lib/*.php") as $filename){
    include($filename);
}



$user = new user1($conn, $prefix, $lg);
$nastavenia = new system($conn, $prefix, $lg);
$url = new url();



/*
if (isset($_SESSION['user_id'])) {
    # code...
}
else {
    $_SESSION['user_id'] = 0;
}
*/

?>