<?php

# CLASS SYSTEM

class captcha {

	private $db, $prefix, $conn;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}


	public function captcha ($typ = '1') {

		session_start();

		if ($typ == '1') {

			$vyber = 'qwer1tyuiIEUVFjHI5LUVLIUBSop35lkjhgfds7zxcvbnmQW8ERTGYUI6OPLKJHF87DSAZX5CVBN6M1230456789';
			$nahodneCislo = '';
			for ($i=1; $i <=5 ; $i++) {
			    $nahodneCislo .= substr($vyber, mt_rand(0, strlen($vyber) -1), 1);
			}


			$nahodneCislo = substr($nahodneCislo,0,5);//trim 5 digit

			$NewImage =imagecreatefromjpeg("../../obrazky/captcha.jpg");//image create by existing image and as back ground

			$LineColor = imagecolorallocate($NewImage,233,239,239);//line color
			$TextColor = imagecolorallocate($NewImage, 30, 30, 30);//text color-white

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