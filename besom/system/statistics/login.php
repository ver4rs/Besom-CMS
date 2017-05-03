<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['action1']) AND $_GET['action1'] == 'new') {
		//echo 'edit ' . $_GET['edit'];


	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];


	}
}	

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

	}
}	





# NADPIS
?><h2><?php echo htmlspecialchars($login->nadpis()); ?></h2><?php



if ($stav['function_view'] == '1') {
	# PAGES TEXT TABLUKA
	$login->loginTab();
}



?>