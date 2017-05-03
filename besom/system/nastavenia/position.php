<?php

# POSITION

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);



#NEW
if ($stav['function_write'] == '1') {
	if (isset($_POST['pozicia1']) AND isset($_POST['poznazov']) AND isset($_POST['pozaka'])) {
		//echo 'post ' . $_POST['poznazov'] . $_POST['pozaka'];
		$position->poziciaAction('1');

		setcookie('odpoved', '2', time()+1);
		//header("Location: " . $_SERVER['HTTP_REFERER']);
		header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
		exit();
	}
}


#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_POST['pozicia3'])/* AND isset($_GET['id']) AND isset($_GET['poz'])*/) {
		//echo 'post ' . $_POST['poznazov'] . $_POST['pozaka'];
		$position->poziciaAction('3');

		setcookie('odpoved', '2', time()+1);
		//header("Location: " . $_SERVER['HTTP_REFERER']);
		//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia'));
		header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
		exit();
	}
}


#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_POST['pozicia2']) AND isset($_POST['poznazov']) AND isset($_POST['pozaka'])) {
		//echo 'post ' . $_POST['poznazov'] . $_POST['pozaka'];
		$position->poziciaAction('2');

		setcookie('odpoved', '2', time()+1);
		//header("Location: " . $_SERVER['HTTP_REFERER']);
		//header("Location: " . URL_ADRESA . htmlspecialchars('?menu=nastavenia'));
		header("Location: " . URL_ADRESA . '?menu=' . $_GET['menu'] . '&action=' . $_GET['action']);
		exit();
		//$this->redirect('?menu=nastavenia');
	}
}


if ($stav['function_view'] == '1') {
?>
<h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #232323; margin-bottom: 5px;">Pozicia obsadenia</h3>

<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title">Pozicia obsadenia</h3>
	</div>
	<div class="panel-body">
	<?php
if (isset($_GET['poz']) AND $_GET['poz'] == '3' AND isset($_GET['id'])) {
	# DELETE
	$nazov = $position->nazovOdId($_GET['id']);
	?>
	<form class="form-inline" role="form" action=""  method="post">
		<div class="form-group">
			<h4>Vymazat poziciu</h4>
		</div>
		<div class="form-group">
			<label  class="sr-only" for="exampleInputEmail2">Vymazat poziciu </label>
			<input type="text" id="disabledTextInput" class="form-control" id="exampleInputPassword2" name="pozaka2" value="<?php echo htmlspecialchars($nazov['pozicia_nazov']); ?>" disabled="disabled">
		</div>
		<div class="form-group">
			<button type="submit" name="pozicia3" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;Vymazat</button>
		</div>	
	</form>
	<?php
}
elseif (isset($_GET['poz']) AND $_GET['poz'] == '2' AND isset($_GET['id'])) {
	# EDIT
	$nazov = $position->nazovOdId($_GET['id']);
	?>
			<form class="form-inline" role="form" action="" method="post">
				<div class="form-group">
					<h4>Zmena</h4>
				</div>	
				<div class="form-group">
				    <label class="sr-only" for="exampleInputEmail2">Nazov pozicie</label>
				    <input type="text" class="form-control" id="exampleInputEmail2" name="poznazov" value="<?php echo (isset($nazov['pozicia_nazov'])) ? htmlspecialchars($nazov['pozicia_nazov']) : ''; ?>">
			  	</div>
				<div class="form-group">
				    <label class="sr-only" for="exampleInputPassword2">Kde</label>
				    <input type="text" id="disabledTextInput" class="form-control" id="exampleInputPassword2" name="pozaka" value="<?php  echo (isset($nazov['pozicia_aka'])) ?  htmlspecialchars($nazov['pozicia_aka']) : ''; ?>">
				</div>

				<button type="submit" name="pozicia2" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i>&nbsp;Zmenit</button>
			</form>
	<?php
}
else {

	#TABULKA
	$pozicia = $position->position();
	?>
			<form class="form-inline" role="form" action="" method="post">
				<div class="form-group">
					<h4>Pridaj</h4>
				</div>	
				<div class="form-group">
				    <label class="sr-only" for="exampleInputEmail2">Nazov pozicie</label>
				    <input type="text" class="form-control" id="exampleInputEmail2" name="poznazov" placeholder="Nazov pozicie">
			  	</div>
				<div class="form-group">
				    <label class="sr-only" for="exampleInputPassword2">Kde</label>
				    <input type="text" id="disabledTextInput" class="form-control" id="exampleInputPassword2" name="pozaka" value="menu">
				</div>

				<button type="submit" name="pozicia1" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</button>
			</form>
		
<?php
?><table class="table table-hover">
	<tr style="background: #ffffff; ">
	<th>#</th>
	<th>Nazov</th>
	<th>Kde</th>
	<th><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&poz=1'); ?>"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</a></th>
	</tr>

<?php
$poradie = 0;
foreach ($pozicia as $pozId => $poz1) {
	foreach ($poz1 as $pozNazov => $poz2) {
		foreach ($poz2 as $pozAka => $poz3) {
			$poradie += 1;
			?>
			<tr>
				<td><?php echo htmlspecialchars($poradie); ?></td>
				<td><?php echo htmlspecialchars($pozNazov); ?></td>
				<td><?php echo htmlspecialchars($pozAka); ?></td>
				<td><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&poz=2&id=' . $pozId); ?>" title="Zmenit"><i class="glyphicon glyphicon-edit"></i></a> &nbsp; <a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&poz=3&id=' . $pozId); ?>" title="delete"><i class="glyphicon glyphicon-trash"></i></a></td>
			</tr>
			<?php
		}
	}
}
?></table>
<?php
}
?>
</div>
</div><?php
}
else {
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit();
}






?>