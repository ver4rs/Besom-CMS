<?php if ( ! defined('URL_ADRESA')) exit('No direct script access allowed');

$stav = $nastavenia->authorization($_SESSION['user_id'], $_GET['menu'], $urlA);

?><h3 style="margin: 0px; padding: 10px 0px 2px 5px; color: #232323; margin-bottom: 5px;"><?php $social->socialTitul(); ?></h3><?php

#EDIT
if ($stav['function_edit'] == '1') {
	if (isset($_GET['edit']) AND $_GET['edit'] != FALSE) {
		//echo 'edit ' . $_GET['edit'];

		$social->socialEdit($_GET['edit']);

		die();
	}
}	

#DELETE
if ($stav['function_delete'] == '1') {
	if (isset($_GET['delete']) AND $_GET['delete'] != FALSE) {
		//echo 'delete ' . $_GET['delete'];

		$social->socialDelete($_GET['delete']);

		die();
	}
}

#ORDER
if (isset($_GET['order']) AND $_GET['order'] != FALSE AND isset($_GET['typ']) AND $_GET['typ'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$social->order($_GET['order'], $_GET['typ']);

	die();
}

#ACTIVE
if (isset($_GET['active']) AND $_GET['active'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$social->socialActive($_GET['active']);

	die();
}

#DEACTIVE
if (isset($_GET['deactive']) AND $_GET['deactive'] != FALSE) {
	//echo 'order ' . $_GET['order'];

	$social->socialDeactive($_GET['deactive']);

	die();
}


if ($stav['function_view'] == '1') {
?>


<?php  /*       TYP 1 FAN PAGE          */  ?>
<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><?php $social->socialTitul('1'); ?></h3>
	</div>
	<div class="panel-body">
	<?php


	#TABULKA
	$tabsocial = $social->socialTab('1');

	$typA = array('1' => 'Fan Page',
				  '2' => 'Share',
				  '3' => 'Like');

	$stavA = array('1' => 'Aktivne',
					'2' => 'Zakazane');
	$stavB = array('1' => 'glyphicon glyphicon-ok-circle',
					'2' => 'glyphicon glyphicon-remove-circle');

	$tabTr = array('1' => 'success',
					'2' => 'danger');

	$color = array('1' => 'green',
					'2' => 'red',
					'3' => 'black');

	//$targetA = array('' => , ); //netreba


?><table class="table table-hover">
	<tr style="background: #ffffff; ">
	<th>#</th>
	<th>Nazov</th>
	<th>Obrazok</th>
	<th>Adresa</th>
	<th>Posli ma</th>
	<th>Typ</th>
	<th>Stav</th>
	<th>Poradie</th>
	<th>Aplikacia id</th>
	<th><a href="#"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</a></th>
	</tr>

<?php
$poradie = 0;

if (!empty($tabsocial)) {

	foreach ($tabsocial as $id => $poz1) {
		foreach ($poz1 as $nazov => $poz2) {
			foreach ($poz2 as $img => $poz3) {
				foreach ($poz3 as $url => $poz4) {
					foreach ($poz4 as $target => $poz5) {
						foreach ($poz5 as $typ => $poz6) {
							foreach ($poz6 as $stav1 => $poz7) {
								foreach ($poz7 as $zorad => $poz8) {
									foreach ($poz8 as $app => $poz9) {
							$poradie += 1;
							?>
							<tr class="<?php echo $tabTr[$stav1]; ?>">
								<td><?php echo htmlspecialchars($poradie); ?></td>
								<td><?php echo htmlspecialchars($nazov); ?></td>
								<td><img src="<?php echo ADRESA . PRIECINOK . IMAGE_SOCIAL . htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($nazov); ?>" title="<?php echo htmlspecialchars($nazov); ?>" class="img" width="30px"></td>
								<td><?php echo htmlspecialchars($url); ?></td>
								<td><?php echo htmlspecialchars($target); ?></td>
								<td><?php echo htmlspecialchars($typ . '  ' . $typA[$typ]); ?></td>
								<td><?php echo htmlspecialchars($stav1 . $stavA[$stav1]); ?>&nbsp;<i class="<?php echo $stavB[$stav1]; ?>" style="color: <?php echo $color[$stav1]; ?>; "></i></td>
								<td><?php echo htmlspecialchars($zorad); ?>&nbsp; <a href="<?php echo htmlspecialchars('?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&order=' . $zorad . '&typ=' . $typ); ?>"><i class="glyphicon glyphicon-sort-by-order"></i></a></td>
								<td><?php echo htmlspecialchars($app); ?></td>
								<td>
									<?php
									if ($stav1 == '1') {
										# active
										?><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&deactive=' . $id);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
									}
									else {
										?><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&active=' . $id);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
									}

									?>
									<a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&edit=' . $id); ?>" title="Zmenit"><i class="glyphicon glyphicon-edit" style="color: <?php echo $color['3']; ?>; "></i></a> &nbsp; 
									<a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&delete=' . $id); ?>" title="delete"><i class="glyphicon glyphicon-trash" style="color: <?php echo $color['3']; ?>; "></i></a>
								</td>
							</tr>
							<?php
									}
								}
							}		
						}
					}
				}		
			}
		}
	}
}
else {
	echo '<tr><th><label class="text-warning">Ziadne data.</label></th></tr>';
}
?></table>

	</div>
</div><?php


/*       TYP 1 FAN PAGE          */  ?>
<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><?php $social->socialTitul('2'); ?></h3>
	</div>
	<div class="panel-body">
	<?php


	#TABULKA
	$tabsocial = $social->socialTab('2');

	$typA = array('1' => 'Fan Page',
				  '2' => 'Share',
				  '3' => 'Like');

	$stavA = array('1' => 'Aktivne',
					'2' => 'Zakazane');
	$stavB = array('1' => 'glyphicon glyphicon-ok-circle',
					'2' => 'glyphicon glyphicon-remove-circle');

	$tabTr = array('1' => 'success',
					'2' => 'danger');

	$color = array('1' => 'green',
					'2' => 'red',
					'3' => 'black');

	//$targetA = array('' => , ); //netreba


?><table class="table table-hover">
	<tr style="background: #ffffff; ">
	<th>#</th>
	<th>Nazov</th>
	<th>Obrazok</th>
	<th>Adresa</th>
	<th>Posli ma</th>
	<th>Typ</th>
	<th>Stav</th>
	<th>Poradie</th>
	<th>Aplikacia id</th>
	<th><a href="#"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</a></th>
	</tr>

<?php
$poradie = 0;

if (!empty($tabsocial)) {

	foreach ($tabsocial as $id => $poz1) {
		foreach ($poz1 as $nazov => $poz2) {
			foreach ($poz2 as $img => $poz3) {
				foreach ($poz3 as $url => $poz4) {
					foreach ($poz4 as $target => $poz5) {
						foreach ($poz5 as $typ => $poz6) {
							foreach ($poz6 as $stav1 => $poz7) {
								foreach ($poz7 as $zorad => $poz8) {
									foreach ($poz8 as $app => $poz9) {
							$poradie += 1;
							?>
							<tr class="<?php echo $tabTr[$stav1]; ?>">
								<td><?php echo htmlspecialchars($poradie); ?></td>
								<td><?php echo htmlspecialchars($nazov); ?></td>
								<td><img src="<?php echo ADRESA . PRIECINOK . IMAGE_SOCIAL . htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($nazov); ?>" title="<?php echo htmlspecialchars($nazov); ?>" class="img" width="30px"></td>
								<td><?php echo htmlspecialchars($url); ?></td>
								<td><?php echo htmlspecialchars($target); ?></td>
								<td><?php echo htmlspecialchars($typ . '  ' . $typA[$typ]); ?></td>
								<td><?php echo htmlspecialchars($stav1 . $stavA[$stav1]); ?>&nbsp;<i class="<?php echo $stavB[$stav1]; ?>" style="color: <?php echo $color[$stav1]; ?>; "></i></td>
								<td><?php echo htmlspecialchars($zorad); ?>&nbsp; <a href="<?php echo htmlspecialchars('?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&order=' . $zorad . '&typ=' . $typ); ?>"><i class="glyphicon glyphicon-sort-by-order"></i></a></td>
								<td><?php echo htmlspecialchars($app); ?></td>
								<td>
									<?php
									if ($stav1 == '1') {
										# active
										?><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&deactive=' . $id);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
									}
									else {
										?><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&active=' . $id);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
									}

									?>
									<a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&edit=' . $id); ?>" title="Zmenit"><i class="glyphicon glyphicon-edit" style="color: <?php echo $color['3']; ?>; "></i></a> &nbsp; 
									<a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&delete=' . $id); ?>" title="delete"><i class="glyphicon glyphicon-trash" style="color: <?php echo $color['3']; ?>; "></i></a>
								</td>
							</tr>
							<?php
									}
								}
							}		
						}
					}
				}		
			}
		}
	}
}
else {
	echo '<tr><th><label class="text-warning">Ziadne data.</label></th></tr>';
}
?></table>

	</div>
</div><?php


/*       TYP 1 FAN PAGE          */  ?>
<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><?php $social->socialTitul('3'); ?></h3>
	</div>
	<div class="panel-body">
	<?php


	#TABULKA
	$tabsocial = $social->socialTab('3');

	$typA = array('1' => 'Fan Page',
				  '2' => 'Share',
				  '3' => 'Like');

	$stavA = array('1' => 'Aktivne',
					'2' => 'Zakazane');
	$stavB = array('1' => 'glyphicon glyphicon-ok-circle',
					'2' => 'glyphicon glyphicon-remove-circle');

	$tabTr = array('1' => 'success',
					'2' => 'danger');

	$color = array('1' => 'green',
					'2' => 'red',
					'3' => 'black');

	//$targetA = array('' => , ); //netreba


?><table class="table table-hover">
	<tr style="background: #ffffff; ">
	<th>#</th>
	<th>Nazov</th>
	<th>Obrazok</th>
	<th>Adresa</th>
	<th>Posli ma</th>
	<th>Typ</th>
	<th>Stav</th>
	<th>Poradie</th>
	<th>Aplikacia id</th>
	<th><a href="#"><i class="glyphicon glyphicon-plus"></i>&nbsp;Pridaj</a></th>
	</tr>

<?php
$poradie = 0;

if (!empty($tabsocial)) {
	
	foreach ($tabsocial as $id => $poz1) {
		foreach ($poz1 as $nazov => $poz2) {
			foreach ($poz2 as $img => $poz3) {
				foreach ($poz3 as $url => $poz4) {
					foreach ($poz4 as $target => $poz5) {
						foreach ($poz5 as $typ => $poz6) {
							foreach ($poz6 as $stav1 => $poz7) {
								foreach ($poz7 as $zorad => $poz8) {
									foreach ($poz8 as $app => $poz9) {
							$poradie += 1;
							?>
							<tr class="<?php echo $tabTr[$stav1]; ?>">
								<td><?php echo htmlspecialchars($poradie); ?></td>
								<td><?php echo htmlspecialchars($nazov); ?></td>
								<td><img src="<?php echo ADRESA . PRIECINOK . IMAGE_SOCIAL . htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($nazov); ?>" title="<?php echo htmlspecialchars($nazov); ?>" class="img" width="30px"></td>
								<td><?php echo htmlspecialchars($url); ?></td>
								<td><?php echo htmlspecialchars($target); ?></td>
								<td><?php echo htmlspecialchars($typ . '  ' . $typA[$typ]); ?></td>
								<td><?php echo htmlspecialchars($stav1 . $stavA[$stav1]); ?>&nbsp;<i class="<?php echo $stavB[$stav1]; ?>" style="color: <?php echo $color[$stav1]; ?>; "></i></td>
								<td><?php echo htmlspecialchars($zorad); ?>&nbsp; <a href="<?php echo htmlspecialchars('?menu=' . $_GET['menu'] . '&action=' . $_GET['action'] . '&order=' . $zorad . '&typ=' . $typ); ?>"><i class="glyphicon glyphicon-sort-by-order"></i></a></td>
								<td><?php echo htmlspecialchars($app); ?></td>
								<td>
									<?php
									if ($stav1 == '1') {
										# active
										?><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&deactive=' . $id);  ?>" alt="Zablokovat" title="Zablokovat"><i class="glyphicon glyphicon-remove" style="color: red"></i></a>&nbsp;<?php
									}
									else {
										?><a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&active=' . $id);  ?>" alt="Aktivovat" title="Aktivovat"><i class="glyphicon glyphicon-ok" style="color: green" ></i></a>&nbsp;<?php
									}

									?>
									<a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&edit=' . $id); ?>" title="Zmenit"><i class="glyphicon glyphicon-edit" style="color: <?php echo $color['3']; ?>; "></i></a> &nbsp; 
									<a href="<?php echo $_SERVER['REQUEST_URI'] . htmlspecialchars('&delete=' . $id); ?>" title="delete"><i class="glyphicon glyphicon-trash" style="color: <?php echo $color['3']; ?>; "></i></a>
								</td>
							</tr>
							<?php
									}
								}
							}		
						}
					}
				}		
			}
		}
	}
}
else {
	echo '<tr><th><label class="text-warning">Ziadne data.</label></th></tr>';
}
?></table>

	</div>
</div><?php


} // end TAB -- PRAVA


if ($stav['function_write'] == '1') {
	##########################
	# NEW LANG ADD
	$social->socialAdd();
	##########################
}




?>