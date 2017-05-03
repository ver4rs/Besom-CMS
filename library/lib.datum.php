<?php

# LIB DATUM

class datum {

	protected $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function datum ($datum, $typ) {

		if ($typ == '1') {
			# 15. jula 2013 21:35

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);

			$tlac = $datum_den . '. ' . $datum_mesiac . ' ' . $datum_rok = date('Y', strtotime($datum)) . ' ' . $cas_hodina = date('H', strtotime($datum)) . ':' . $cas_minuta = date('i', strtotime($datum));
			
			return $tlac;
		}

		if ($typ == '2') {
			# 15. jula 2013

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);

			$tlac = $datum_den . '. ' . $datum_mesiac . ' ' . $datum_rok = date('Y', strtotime($datum));
			
			return $tlac;
		}

		if ($typ == '3') {
			# jul 15, 2014

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac1 = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);
			$datum_mesiac = strtoupper($datum_mesiac1);

			$datum_rok = date('Y', strtotime($datum));

			//$tlac = $datum_den . '. ' . $datum_mesiac . ' ' . $datum_rok = date('Y', strtotime($datum));
			$tlac = $datum_mesiac . ' ' . $datum_den . ', ' . $datum_rok;
			
			return $tlac;
		}

		if ($typ == '4') {
			# 07:34

			$datum_den = date('d', strtotime($datum));
			//datum den
			if (substr($datum_den, 0, - 1) == '0') {
			    $datum_den = substr($datum_den, 1);
			}

			$datum_mesiac = date('m', strtotime($datum));

			$aj_cislo = array("01","02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$sk_nazov = array("Január", "Ferbuár", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December");

			$datum_mesiac1 = str_replace($aj_cislo, $sk_nazov, $datum_mesiac);
			$datum_mesiac = strtoupper($datum_mesiac1);

			$datum_rok = date('Y', strtotime($datum));

			$tlac = $cas_hodina = date('H', strtotime($datum)) . ':' . $cas_minuta = date('i', strtotime($datum));
			
			return $tlac;
		}

	}



	public function captcha ($typ = '1') {

		//session_start();

		if ($typ == '1') {

			$vyber = 'qwer1tyuiIEUVFjHI5LUVLIUBSop35lkjhgfds7zxcvbnmQW8ERTGYUI6OPLKJHF87DSAZX5CVBN6M1230456789';
			$nahodneCislo = '';
			for ($i=1; $i <=5 ; $i++) {
			    $nahodneCislo .= substr($vyber, mt_rand(0, strlen($vyber) -1), 1);
			}


			$nahodneCislo = substr($nahodneCislo,0,5);//trim 5 digit

			$NewImage =imagecreatefromjpeg('images/captcha.jpg');//image create by existing image and as back ground

			$LineColor = imagecolorallocate($NewImage,233,239,239);//line color
			$TextColor = imagecolorallocate($NewImage, 20, 20, 20);//text color-white

			imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image
			imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image

			imagestring($NewImage, 5, 20, 10, $nahodneCislo, $TextColor);// Draw a random string horizontally

			$saltCaptcha = '35e4w5g464w6g46we4gwer4g6e4g';
			$nahodneCisloHash = md5(hash("SHA512", $nahodneCislo)) . md5(md5(hash("SHA512", $saltCaptcha)));

			$_SESSION['nahoda'] = $nahodneCisloHash;// carry the data through session

			header("Content-type: image/jpeg");// out out the image

			imagejpeg($NewImage);//Output image to browser
		}
		elseif ($typ == '2') {
			# code...
		}
		else {
			# none
		}

	}	

}








?>