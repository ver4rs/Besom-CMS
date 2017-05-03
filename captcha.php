<?php

//session_start();

if (isset($_GET['stav']) AND $_GET['stav'] == '1') {

			$vyber = 'qwer1tyuiIEUVFjHI5LUVLIUBSop35lkjhgfds7zxcvbnmQW8ERTGYUI6OPLKJHF87DSAZX5CVBN6M1230456789';
			$nahodneCislo = '';
			for ($i=1; $i <=5 ; $i++) {
			    $nahodneCislo .= substr($vyber, mt_rand(0, strlen($vyber) -1), 1);
			}

			$nahodneCislo = substr($nahodneCislo,0,5);//trim 5 digit

			$NewImage =imagecreatefromjpeg('images/captcha.jpg');//image create by existing image and as back ground

			$rand1 = mt_rand('1', '180');
			$rand2 = mt_rand('1', '180');
			$rand3 = mt_rand('1', '180');

			$LineColor = imagecolorallocate($NewImage,233,219,239);//line color
			$TextColor = imagecolorallocate($NewImage, $rand1, $rand2, $rand3);//text color-white

			imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image
			imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image

			$font = 'include/font/Arial.ttf';

			imagettftext($NewImage, 14, 0, 20, 23, $TextColor, $font, $nahodneCislo);

			//imagestring($NewImage, $font, 20, 10, $nahodneCislo, $TextColor);// Draw a random string horizontally


			$saltCaptcha = '35e4w5g464w6g46we4gwer4g6e4g';
			$nahodneCisloHash = md5(hash("SHA512", $nahodneCislo)) . md5(md5(hash("SHA512", $saltCaptcha)));

			//$_SESSION['naho'] = $nahodneCisloHash;// carry the data through session
			//$_SESSION['nahoda'] = $nahodneCislo;// carry the data through session
			setcookie('nahoda', $nahodneCisloHash, time()+500);

			header("Content-type: image/jpeg");// out out the image

			imagejpeg($NewImage);//Output image to browser
}
elseif (isset($_GET['stav']) AND $_GET['stav'] == '2') {



			$nahodneCislo1 = mt_rand('0', '9');
			$nahodneCislo2 = mt_rand('0', '9');

			$typ = array('1' => '+',
						 '2' => '-' );
			$ako = $typ[mt_rand('1', '2')];
			
			$nahodneCislo = $nahodneCislo1 . ' ' . $ako . ' ' . $nahodneCislo2 . ' =';


			$NewImage =imagecreatefromjpeg('images/captcha.jpg');//image create by existing image and as back ground

			$rand1 = mt_rand('1', '180');
			$rand2 = mt_rand('1', '180');
			$rand3 = mt_rand('1', '180');

			$LineColor = imagecolorallocate($NewImage,233,219,239);//line color
			$TextColor = imagecolorallocate($NewImage, $rand1, $rand2, $rand3);//text color-white

			imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image
			imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image

			$font = 'include/font/Arial.ttf';

			imagettftext($NewImage, 14, 0, 20, 23, $TextColor, $font, $nahodneCislo);

			//imagestring($NewImage, $font, 20, 10, $nahodneCislo, $TextColor);// Draw a random string horizontally

			if ($ako == '+') {
				$nahodneCisloHash = $nahodneCislo1 + $nahodneCislo2;
			}
			else { // -
				$nahodneCisloHash = $nahodneCislo1 - $nahodneCislo2;
			}

			$saltCaptcha = '35e4w5g464w6g46we4gwer4g6e4g';
			$nahodneCisloHash = md5(hash("SHA512", $nahodneCisloHash)) . md5(md5(hash("SHA512", $saltCaptcha)));

			//$_SESSION['naho'] = $nahodneCisloHash;// carry the data through session
			//$_SESSION['nahoda'] = $nahodneCislo;// carry the data through session
			setcookie('nahoda', $nahodneCisloHash, time()+500);

			header("Content-type: image/jpeg");// out out the image

			imagejpeg($NewImage);//Output image to browser
}
elseif (isset($_GET['stav']) AND $_GET['stav'] == '3') {



			$nahodneCislo1 = mt_rand('0', '9');
			$nahodneCislo2 = mt_rand('0', '9');

			$typ = array('1' => '+',
						 '2' => '-' );
			$ako = $typ[mt_rand('1', '2')];
			
			$nahodneCislo = $nahodneCislo1 . ' ' . $ako . ' ' . $nahodneCislo2 . ' =';


			$NewImage =imagecreatefromjpeg('images/captcha.jpg');//image create by existing image and as back ground

			$rand1 = mt_rand('1', '180');
			$rand2 = mt_rand('1', '180');
			$rand3 = mt_rand('1', '180');

			$LineColor = imagecolorallocate($NewImage,233,219,239);//line color

			$TextColor1 = imagecolorallocate($NewImage, mt_rand('1', '180'), mt_rand('1', '180'), mt_rand('1', '180'));//text color-white
			$TextColor2 = imagecolorallocate($NewImage, mt_rand('1', '180'), mt_rand('1', '180'), mt_rand('1', '180'));//text color-white
			$TextColor3 = imagecolorallocate($NewImage, mt_rand('1', '180'), mt_rand('1', '180'), mt_rand('1', '180'));//text color-white

			imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image
			imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image

			$font = 'include/font/Arial.ttf';

			imagettftext($NewImage, 14, 0, 20, 23, $TextColor1, $font, $nahodneCislo1);
			imagettftext($NewImage, 14, 0, 40, 25, $TextColor2, $font, $ako);
			imagettftext($NewImage, 14, 0, 60, 23, $TextColor3, $font, $nahodneCislo2);

			//imagestring($NewImage, $font, 20, 10, $nahodneCislo, $TextColor);// Draw a random string horizontally

			if ($ako == '+') {
				$nahodneCisloHash = $nahodneCislo1 + $nahodneCislo2;
			}
			else { // -
				$nahodneCisloHash = $nahodneCislo1 - $nahodneCislo2;
			}

			$saltCaptcha = '35e4w5g464w6g46we4gwer4g6e4g';
			$nahodneCisloHash = md5(hash("SHA512", $nahodneCisloHash)) . md5(md5(hash("SHA512", $saltCaptcha)));

			//$_SESSION['naho'] = $nahodneCisloHash;// carry the data through session
			//$_SESSION['nahoda'] = $nahodneCislo;// carry the data through session
			setcookie('nahoda', $nahodneCisloHash, time()+500);

			header("Content-type: image/jpeg");// out out the image

			imagejpeg($NewImage);//Output image to browser
}
else {

}
  			
?>			