<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

# NADPIS
?><h2><?php echo htmlspecialchars($right->nadpis()); ?></h2><?php


/*    
	RIGHT
*/
#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['new'])) {
		//echo 'edit ' . $_GET['edit'];

		$right->rightNew();

		//die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$right->rightEdit($_GET['edit']);

		//die();
	}
}

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$right->rightDelete($_GET['delete']);

		//die();
	}
}

# VIEW
if ($stav['function_view'] == '1') {
	if (isset($_GET['action']) AND $_GET['action'] == 'right' /*AND isset($_GET['right'])*//* AND $_GET['right'] == 'view'*/) {
		//echo 'edit ' . $_GET['edit'];

		$right->rightTab();

		//die();
	}
}
/*
	END RIGHT
*/









# NEW TEXT LINK
/*?>
<a href="<?php echo URL_ADRESA . '?menu=uzivatel&action=new'; ?>"><h3>Pridat skupinu <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php
*/

# PAGES TEXT TABLUKA
//$right->groupTab();




##########################
# NEW LANG ADD
//$social->socialAdd();
##########################



?>