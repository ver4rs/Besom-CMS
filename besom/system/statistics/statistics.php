<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

#NEW
if ($stav['function_write'] == '1') {
	if (isset($_GET['action1']) AND $_GET['action1'] == 'new') {
		//echo 'edit ' . $_GET['edit'];

		$slider->sliderNew();

		die();
	}
}

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$slider->sliderEdit($_GET['edit']);

		die();
	}
}

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$slider->sliderDelete($_GET['delete']);

		die();
	}
}


#ACTIVE
if (isset($_GET['active']) AND $_GET['active'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$slider->sliderActive($_GET['active']);

	die();
}

#DEACTIVE
if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$slider->sliderDeactive($_GET['deactive']);

	die();
}

#ORDER
if (isset($_GET['order']) AND $_GET['order'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$slider->sliderOrder($_GET['order']);

	die();
}


# NADPIS
?><h2><?php echo htmlspecialchars($slider->nadpis()); ?></h2><?php


# STAV KRATKY POPIS
$stav1 = $slider->sliderStav();

			$turnA = array('1' => 'Zapnuty',
							'0' => 'Vypnuty' );
			$typ = array('1' => 'Clanky',
							'2' => 'Vybrane',
							'3' => 'Kombinovane' );
			$colA = array('1' => 'green',
							'0' => 'red' );
?>
<div class="container" style="background: #EFEFEF; border: 1px solid #CDCDCD; border-radius: 3px; ">
	<h3>Slider nastavenia</h3>
	<p class="text-muted">Zmenit mozete v nastaveniach</p>
	<dl class="dl-horizontal">
		<dt>Slider </dt>
		<dd><span class="col col-danger" style="color: <?php echo $colA[$stav1['system_slider_ano']]; ?>; "><?php echo htmlspecialchars($turnA[$stav1['system_slider_ano']]); ?></span></dd>

		<dt>Co zobrazim</dt>
		<dd><?php echo htmlspecialchars($typ[$stav1['system_slider']]); ?></dd>

		<dt>Pocet slidov</dt>
		<dd><?php echo htmlspecialchars($stav1['system_slider_pocet']); ?></dd>
	</dl>
</div>

<?php




# NEW TEXT LINK
?>
<a href="<?php echo URL_ADRESA . '?menu=slider&action1=new'; ?>"><h3>Pridat slide <i class="glyphicon glyphicon-plus"></i></h3></a>
<?php

if ($stav['function_view'] == '1') {
	# PAGES TEXT TABLUKA
	$slider->sliderTab();
}




?>