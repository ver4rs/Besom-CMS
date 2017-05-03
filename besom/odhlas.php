<?php

require 'config.php';

		$_SESSION['user_id'] = '';
		$_SESSION['user'] = '';
		$_SESSION['name'] = '';
		$_SESSION['avatar'] = '';
		$_SESSION['password'] = '';
		$_SESSION['email'] = '';

		# VYMAZANIE COOKIES SUBOROV ----> AUTOMATICKE PRIHLASENIE
		//$expire = time()-((3600*24)*10); // cokies ulozene na 10 dni
		$expire = time()-9999999999999; // c
		setcookie('beid', FALSE, $expire);
		setcookie('beuser', FALSE, $expire);
		setcookie('bename', FALSE, $expire);
		setcookie('beemail', FALSE, $expire);
		setcookie('beavatar', FALSE, $expire);
		setcookie('bepassword', FALSE, $expire);
		setcookie('bekey', FALSE, $expire);

		session_unset();
		session_destroy();

		header('Location: ' . URL_ADRESA);
		exit();
?>