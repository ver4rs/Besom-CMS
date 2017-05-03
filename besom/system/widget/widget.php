<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['new']) AND $_GET['new'] == $_GET['new']) {
		//echo 'edit ' . $_GET['edit'];

		$widget->widgetNew();

		die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$widget->widgetEdit($_GET['edit']);

		die();
	}
}

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$widget->widgetDelete($_GET['delete']);

		die();
	}
}


#ACTIVE
if (isset($_GET['active']) AND $_GET['active'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$widget->widgetActive($_GET['active']);

	die();
}

#DEACTIVE
if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$widget->widgetDeactive($_GET['deactive']);

	die();
}

#ORDER
if (isset($_GET['order']) AND $_GET['order'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$widget->widgetOrder($_GET['order']);

	die();
}


# NADPIS
?><h2><?php echo htmlspecialchars($widget->nadpis()); ?></h2><?php


# STAV KRATKY POPIS
//$stav = $widget->widgetStav();

			$turnA = array('1' => 'Zapnuty',
							'0' => 'Vypnuty' );
			$typ = array('1' => 'Clanky',
							'2' => 'Vybrane',
							'3' => 'Kombinovane' );
			$colA = array('1' => 'green',
							'0' => 'red' );
?>
<div class="container" style="background: #EFEFEF; border: 1px solid #CDCDCD; border-radius: 3px; ">
	<h3><?php echo htmlspecialchars($widget->nadpis()); ?> pomocnik</h3>
	<div class="col-md-4">
		<b class="text-danger">Priorita <i>zobrazenia</i></b>
		<ol>
			<li>Modul <small class="text-warning">nazov modulu</small></li>
			<li>Skript <small class="text-warning">javascript</small></li>
			<li>Obrazok <small class="text-warning">link a adresu obrazka</small></li>
			<li>text <small class="text-warning">clanok, text</small></li>
			<li>Vlastny text <small class="text-warning">kratky text + moznost cudzieho jazyka</small></li>
		</ol>
	</div>

	<div class="col-md-3">
		<b class="text-danger">Typ <i>widgetu</i></b>
		<ol>
			<li>Skript <small class="text-warning">script</small></li>
			<li>Modul <small class="text-warning">v php skripte - home</small></li>
			<li>Text <small class="text-warning">text</small></li>
			<li>Obrazok <small class="text-warning">image</small></li>
		</ol>
	</div>	
</div>

<?php




# NEW TEXT LINK
?>
<!-- <a href="#"><h3>Novy <?php //echo htmlspecialchars($widget->nadpis()); ?><i class="glyphicon glyphicon-plus"></i></h3></a> -->
<div class="btn-group" style="margin: 10px 0px; ">
	<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
    	Novy <?php echo htmlspecialchars($widget->nadpis()); ?>&nbsp; <i class="glyphicon glyphicon-plus"></i> <span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li class="divider"></li>
		<li><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&new=modul'; ?>">Modul</a></li>
		<li><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&new=image'; ?>">Obrazok | banner | reklama</a></li>
		<li><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&new=script'; ?>">Skript | javascript | objekt</a></li>
		<li><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&new=textcontent'; ?>">text clanku alebo content</a></li>
		<li><a href="<?php echo URL_ADRESA . '?menu=' . $_GET['menu'] . '&new=text'; ?>">text | popis</a></li>
		<li class="divider"></li>
	</ul>
</div>
<?php

if ($stav['function_view'] == '1') {
	# PAGES TEXT TABLUKA
	$widget->widgetTab();
}



##########################
# NEW LANG ADD
//$social->socialAdd();
##########################



?>