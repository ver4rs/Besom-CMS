<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

# NADPIS
?><h2><?php echo htmlspecialchars($function->nadpis()); ?></h2><?php

# STAV KRATKY POPIS
?>
<div class="container" style="background: #EFEFEF; border: 1px solid #CDCDCD; border-radius: 3px; margin-bottom: 20px; ">
	<h3><?php echo htmlspecialchars($function->nadpis()); ?> pomocnik</h3>
	<div class="col-md-4">
		<b class="text-danger">Popis <i>zobrazenia</i></b>
		<ol>
			<li>Skupina <small class="text-warning">nazov skupiny, skupina uzivatelov</small></li>
			<li>Prava <small class="text-warning">jednotlive polozky menu</small></li>
		</ol>
	</div>

	<div class="col-md-5">
	      	<b class="text-danger">Moznosti <i>prav</i></b>
	      	<ol>
	      		<li>Citat <small class="text-warning">read - pravo citania</small></li>
	      		<li>Pisat <small class="text-warning">write - pravo pisania</small></li>
	      		<li>Editovat <small class="text-warning">edit - pravo zmeny, editacie</small></li>
	      		<li>Zobrazovat <small class="text-warning">view - pravo zobrazenia</small></li>
	      		<li>Vlastne <small class="text-warning">me/y - pravo vlastnych, mojich napisanych</small></li>
	      	</ol>
	</div>	
</div>
<?php

/*    
	FUNCTION
*/
#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['new'])) {
		//echo 'edit ' . $_GET['edit'];

		$function->functionNew();

		//die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$function->functionEdit($_GET['edit']);

		//die();
	}
}

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$function->functionDelete($_GET['delete']);

		//die();
	}
}


# VIEW
if ($stav['function_view'] == '1') {
	if (isset($_GET['action']) AND $_GET['action'] == 'function' /*AND isset($_GET['group'])*//* AND $_GET['group'] == 'view'*/) {
		//echo 'edit ' . $_GET['edit'];

		$function->functionTab();

		//die();
	}
}




# NEW TEXT LINK
/*?>
<a href="<?php echo URL_ADRESA . '?menu=uzivatel&action=new'; ?>"><h3>Pridat skupinu <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php
*/

# PAGES TEXT TABLUKA
//$function->groupTab();




##########################
# NEW LANG ADD
//$social->socialAdd();
##########################



?>