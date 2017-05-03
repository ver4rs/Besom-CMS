<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

# NADPIS
?><h2><?php echo htmlspecialchars($users->nadpis()); ?></h2><?php

#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['new']) /*AND $_GET['new'] == ''*/) {
		//echo 'edit ' . $_GET['edit'];

		$users->usersNew();

		//die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$users->usersEdit($_GET['edit']);

		//die();
	}
}

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$users->usersDelete($_GET['delete']);

		//die();
	}
}	


#ACTIVE
if (isset($_GET['active']) AND $_GET['active'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$users->usersActive($_GET['active']);

	//die();
}

#DEACTIVE
if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$users->usersDeactive($_GET['deactive']);

	//die();
}

#ACTIVE EMAIL
if (isset($_GET['email']) AND $_GET['email'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$users->usersActiveEmail($_GET['email']);

	//die();
}

#View EMAIL
if ($stav['function_read'] == '1') {
	if (isset($_GET['view']) AND $_GET['view'] != FALSE) {
		//echo 'order ' . $_GET['order'];

		$users->usersView($_GET['view']);

		//die();
	}
}




# NEW TEXT LINK
?>
<a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&new'; ?>"><h3>Pridat uzivatela <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php

if ($stav['function_view'] == '1') {
	# PAGES TEXT TABLUKA
	$users->usersTab();
}



##########################
# NEW LANG ADD
//$social->socialAdd();
##########################



?>