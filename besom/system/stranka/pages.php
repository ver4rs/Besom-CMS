<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);


/*
#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['action1']) AND $_GET['action1'] == 'new') {
		//echo 'edit ' . $_GET['edit'];

		$pages->pagesNew();

		die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$pages->pagesEdit($_GET['edit']);

		die();
	}
}	

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$pages->pagesDelete($_GET['delete']);

		die();
	}
}	


#ACTIVE
if (isset($_GET['active']) AND $_GET['active'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$pages->pagesActive($_GET['active']);

	die();
}

#DEACTIVE
if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$pages->pagesDeactive($_GET['deactive']);

	die();
}

*/
# NADPIS
?><h2><?php echo htmlspecialchars($pages->nadpis()); ?></h2><?php

/*
# NEW TEXT LINK
?>
<a href="<?php echo URL_ADRESA . '?menu=pages&action1=new'; ?>"><h3>Vytvorit novy text <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php


if ($stav['function_view'] == '1') {
	# PAGES TEXT TABLUKA
	$pages->pagesTab();
}



##########################
# NEW LANG ADD
//$social->socialAdd();
##########################
*/


?>