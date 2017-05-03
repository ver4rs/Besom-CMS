<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

#START
if (isset($_GET['start']) AND $_GET['start'] != FALSE) {
	//echo 'edit ' . $_GET['edit'];

	$menu->startStranka($_GET['start']);

	die();
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$menu->menuEdit($_GET['edit']);

		die();
	}
}

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$menu->menuDelete($_GET['delete']);

		die();
	}
}


#ORDER
if (isset($_GET['order']) AND $_GET['order'] != FALSE AND isset($_GET['id']) AND $_GET['id']) {
	//echo 'zorad ' . $_GET['order'];

	$menu->order($_GET['order'], $_GET['id']);

	die();
}

#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['new'])) {
		//echo 'new ' . $_GET['new'];

		$menu->menuAdd($_GET['new']);

		die();
	}
}

# NADPIS
?><h2><?php echo htmlspecialchars($menu->nadpis()); ?></h2><?php

?>
<a href="<?php echo $_SERVER['REQUEST_URI'] . '&new'; ?>"><h3>Vytvorit nove menu <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php

if ($stav['function_view'] == '1') {
	#TABULKA
	$menu->menu();
}






##########################
# NEW LANG ADD
//$menu->menu();
##########################



?>