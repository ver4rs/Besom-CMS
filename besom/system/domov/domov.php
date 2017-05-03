<script type="text/javascript">
/*var auto_refresh = setInterval(
function ()
{
$('#load').load('#.php').fadeIn("slow");
}, 10000); // refresh every 10000 milliseconds
*/
</script>
<?php

# INDEX DOMOV



echo '<h1>' . $domov->nadpis() . '</h1>'; // nadpis, titul stranky



# TEST VALIDTE EAMI
?>
<form action="" method="post" accept-charset="utf-8">
	<input type="text" name="email" value="" placeholder="">
	<input type="datetime-local" name="" value="" placeholder="">	
	<input type="date" name="" value="" placeholder="">
	<input type="datetime" name="" value="" placeholder="">
	<input type="submit" name="submit" value="submit">
</form>
<?php




if (isset($_POST['submit']) AND isset($_POST['email'])) {
	
	$email = $_POST['email'];
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
  {
  $emailErr = "Invalid email format"; 
  echo $emailErr;
  }
  else {
  	echo 'ok';
  }
}


?>
<div class="row" id="load">
	<div class="col-2 col-sm-2 col-lg-3" style="background: #FFFFFF; border: 1px solid #CDCDCD; padding: 0px 10px 10px 10px; margin: 10px; ">
		<h3>Informacie</h3>
		<p><strong>Den:</strong> <?php echo date('d. m Y  H:i:s'); ?></p>
		<p><strong>IPadresa:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
		<p><strong>Zaciatok:</strong> <?php echo $domov->pocitadloMinDate(); ?></p>
	</div>
	
	<div class="col-3 col-sm-3 col-lg-3" style="background: #FFFFFF; border: 1px solid #CDCDCD; padding: 0px 10px 10px 10px; margin: 10px; ">
		<h3>Navstevnost</h3>
		
		<h4>Dnes</h4>
		<li><strong>Navstevy:</strong> <?php echo $domov->pocitadloDnes(1); ?></li>
		<li><strong>Zobrazenia:</strong> <?php echo $domov->pocitadloDnes(2); ?></li>
		
		<h4>Vcera</h4>
		<li><strong>Navstevy:</strong> <?php echo $domov->pocitadloVcera(1); ?></li>
		<li><strong>Zobrazenia:</strong> <?php echo $domov->pocitadloVcera(2);; ?></li>

		<h4>Tyzden</h4>
		<li><strong>Navstevy:</strong> <?php echo $domov->pocitadloTyzden(1); ?></li>
		<li><strong>Zobrazenia:</strong> <?php echo $domov->pocitadloTyzden(2);; ?></li>
		
		<h4>Celkom</h4>
		<li><strong>Navstevy:</strong> <?php echo $domov->pocitadloCelkovoPocet2(); ?></li>
		<li><strong>Zobrazenia:</strong> <?php echo $domov->pocitadloCelkovoPocet(); ?></li>
	</div>

	<div class="col-1 col-sm-1 col-lg-2" style="background: #FFFFFF; border: 1px solid #CDCDCD; padding: 0px 10px 10px 10px; margin: 10px; ">
		<h3>Online</h3>
		<p><strong>Pocet:</strong> <?php echo $domov->pocitadloOnlineStav(); ?></p>
	</div>

	            
</div>