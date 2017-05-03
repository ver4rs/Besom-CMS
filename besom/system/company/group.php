<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

# NADPIS
?><h2><?php echo htmlspecialchars($group->nadpis()); ?></h2><?php


/*    
	GROUP
*/
#NEW
if ($stav['function_write'] == '1') {	
	if (isset($_GET['new'])) {
		//echo 'edit ' . $_GET['edit'];

		$group->groupNew();

		//die();
	}
}	

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$group->groupEdit($_GET['edit']);

		//die();
	}
}

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$group->groupDelete($_GET['delete']);

		//die();
	}
}

# VIEW
if ($stav['function_view'] == '1') {
	if (isset($_GET['action']) AND $_GET['action'] == 'group' /*AND isset($_GET['group'])*//* AND $_GET['group'] == 'view'*/) {
		//echo 'edit ' . $_GET['edit'];

		$group->groupTab();

		//die();
	}
}
/*
	END GROUP
*/
###############################################################################################################################




# NEW TEXT LINK
/*?>
<a href="<?php echo URL_ADRESA . '?menu=uzivatel&action=new'; ?>"><h3>Pridat skupinu <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php
*/

# PAGES TEXT TABLUKA
//$group->groupTab();




##########################
# NEW LANG ADD
//$social->socialAdd();
##########################



?>