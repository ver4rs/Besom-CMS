<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['new'])) {
		//echo 'edit ' . $_GET['edit'];

		$newsletter->newsletterNew();

		die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$newsletter->newsletterEdit($_GET['edit']);

		die();
	}
}	

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$newsletter->newsletterDelete($_GET['delete']);

		die();
	}
}	


#ACTIVE
/*if (isset($_GET['active']) AND $_GET['active'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$newsletter->newsletterActive($_GET['active']);

	die();
}*/

#DEACTIVE
if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$newsletter->newsletterDeactive($_GET['deactive']);

	die();
}

#AKTIVOVAT - nebol aktivovany
if (isset($_GET['acemail']) AND $_GET['acemail'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$newsletter->newsletterAcemail($_GET['acemail']);

	die();
}

#NEW TOKEN
if (isset($_GET['newToken']) AND $_GET['newToken'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$newsletter->newsletterToken($_GET['newToken']);

	die();
}


# NADPIS
?><h2><?php echo htmlspecialchars($newsletter->nadpis()); ?></h2><?php


# NEW TEXT LINK
/*?>
<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']; ?>"><h3>Pridat kontakt <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php*/


if ($stav['function_view'] == '1') {
	# newsletter TEXT TABLUKA
	$newsletter->newsletterTab();
}




?>