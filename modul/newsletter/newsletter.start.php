<?php

# START SOCIAL



$novinkovac = new novinkovac($this->db, $this->prefix, $this->lang);

$novinkovac->newsletter(); // zobrazi vsetko socialne siete +newsletter

//$novinkovac->sendEmail(); // POST



if ($novinkovac->post() == 'yes') {
	# EMAIL CLASS
	require("besom/mailer/class.phpmailer.php"); 

/*echo $novinkovac->odosielatel() . '</br>';
echo $novinkovac->nick() . '</br>';
echo $novinkovac->meno() . '</br>';
echo $novinkovac->email() . '</br>';
echo $novinkovac->titul() . '</br>';
echo $novinkovac->url() . '</br>';
echo $novinkovac->hash() . '</br>';
echo $novinkovac->stav() . '</br>';
echo $novinkovac->body() . '</br>';*/

	//require("PHPMailer-master/class.phpmailer.php"); // voláme súbor 
	$mail = new PHPMailer(); //instancia PHPMaileru 
	$mail->From = $novinkovac->odosielatel(); //moja adresa 
	$mail->FromName = $novinkovac->titul() . ' | ' . $novinkovac->nick() . ' - ' . $novinkovac->meno(); //moje meno // $nick alebo $meno
	$mail->AddAddress($novinkovac->email()); // komu 
	$mail->WordWrap = 50; // po 50 znaku slova rozdel slovo 
	$mail->IsHTML(true); //
	$mail->Charset = "utf-8"; 
	$mail->Subject = 'Aktivacia odoberania noviniek ' . $novinkovac->titul(); 
	$mail->Body = '<html> <head> </head> <body>'; 
	$mail->Body .= '<br><p style="display:block; width: 100%; height: 35px; color: red; background: #CDCDCD; border-bottom: 2px solid #232323; ">' . $novinkovac->body() . '</p><br>'; 
	$mail->Body .= '<b>Pekny den praje ' . $novinkovac->titul() . ' z adresy ' . $novinkovac->url() . '.</b> <br>'; 
	$mail->Body .= "Prave ste sa prihlasili na odber noviniek. ";
	
	if ($novinkovac->stav() == '2') {
		
		$mail->Body .= '<br>Este musite splnit jednu podmienku pre uspesne zasielanie noviniek. <br>Aktivujte si odber na tomto linku ' . '<a href="' . $novinkovac->url() . '?status=activeNews&hash=' . $novinkovac->hash() . '">Aktivovat</a>. <br>';
		$mail->Body .= 'Aktivaciou suhlasite s obchodnimy podmienkami a pravami pouzivania osobnych udajou. <br>'; 	
	}

	
	$mail->Body .= 'Dakujeme pekne za vasu priazen, budeme spokojny ak nas odporucite vasim znamim. <br>'; 
	$mail->Body .= 'Prajeme vam pekny zvysok dna. '; 
	$mail->Body .= '</body></html>'; 

								//$mail->AltBody = "Používate chabého klienta, takže nevyhrávate ani len link na poriadneho!"; 
	if(!$mail->Send()) { 
									//echo "Správa nebola zaslaná."; 
									//echo "Nastala chyba: " . $mail->ErrorInfo; 
									//exit;
		?><span style="color: red; ">Email nebol odoslany, v pripade problemou kontaktujte spravcu <?php echo $novinkovac->odosielatel(); ?></span><?php

	}
	else {
		if ($novinkovac->stav() == '2') { // treba aktivovat
			?><span style="color: green; ">Dakujeme. Nezabudnite si aktivovat odber, na vasom emaile. Ak nedostanete email kontaktujte spravcu.</span><?php
										
		}
		else {  // netreba aktivovat
			?><span style="color: green; ">Dakujeme. Ste prihlaseny k odberu.</span><?php
		}
	}


}


?>