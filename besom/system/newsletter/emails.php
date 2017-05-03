<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['new'])) {
		//echo 'edit ' . $_GET['edit'];

		$email->emailNew();

		die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$email->emailEdit($_GET['edit']);

		die();
	}
}	

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$email->emailDelete($_GET['delete']);

		die();
	}
}	


#ACTIVE
if (isset($_GET['active']) AND $_GET['active'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$email->emailActive($_GET['active']);

	die();
}

#REPEAT
if (isset($_GET['repeat']) AND $_GET['repeat'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$email->emailRepeat($_GET['repeat']);

	die();
}


#DEACTIVE    -- neda sa ak sa aktivuje tak bude odoslany
/*if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$email->emailDeactive($_GET['deactive']);

	die();
}*/



# NADPIS
?><h2><?php echo htmlspecialchars($email->nadpis()); ?></h2><?php


# NEW TEXT LINK
/*?>
<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']; ?>"><h3>Pridat kontakt <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php*/


if ($stav['function_view'] == '1') {
	# newsletter TEXT TABLUKA
	$email->emailTab();
}



##########################
# NEW LANG ADD
//$social->socialAdd();
##########################



?>