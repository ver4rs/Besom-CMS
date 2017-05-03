<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$language->languageEdit($_GET['edit']);

		die();
	}
}	

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		echo 'delete ' . $_GET['delete'];

		$language->languageDelete($_GET['delete']);

		die();
	}
}	


if ($stav['function_view'] == '1') {
?>
<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #232323; margin-bottom: 5px;">Jazyk obsadenia</h3>

<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title">Jazyk obsadenia</h3>
	</div>
	<div class="panel-body">
	<?php


	#TABULKA
	$jazyk = $language->language();

?><table class="table table-hover">
	<tr style="background: #ffffff; ">
	<th>#</th>
	<th>Nazov</th>
	<th>Skratka</th>
	<th>Ikona</th>
	<th>Defaultne</th>
	<th>Short</th>
	<th><a href="#"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</a></th>
	</tr>

<?php
$poradie = 0;
foreach ($jazyk as $pozId => $poz1) {
	foreach ($poz1 as $pozNazov => $poz2) {
		foreach ($poz2 as $pozskratka => $poz3) {
			foreach ($poz3 as $pozIkona => $poz4) {
				foreach ($poz4 as $pozDefa => $poz5) {
					foreach ($poz5 as $pozshort => $poz6) {
						$poradie += 1;
						?>
						<tr>
							<td><?php echo htmlspecialchars($poradie); ?></td>
							<td><?php echo htmlspecialchars($pozNazov); ?></td>
							<td><?php echo htmlspecialchars($pozskratka); ?></td>
							<td><img src="<?php echo ADRESA . PRIECINOK . htmlspecialchars($pozIkona); ?>" alt="<?php echo htmlspecialchars($pozNazov); ?>" title="<?php echo htmlspecialchars($pozNazov); ?>" class="img-rounded" width="30px"></td>
							<td><?php echo htmlspecialchars($pozDefa); ?></td>
							<td><?php echo htmlspecialchars($pozshort); ?></td>
							<td><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&edit=' . $pozId); ?>" title="Zmenit"><i class="glyphicon glyphicon-edit"></i></a> &nbsp; <a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&delete=' . $pozId); ?>" title="delete"><i class="glyphicon glyphicon-trash"></i></a></td>
						</tr>
						<?php
					}
				}
			}		
		}
	}
}
?></table>

</div>
</div><?php
}

if ($stav['function_write'] == '1') {
	##########################
	# NEW LANG ADD
	$language->languageAdd();
	##########################
}


?>