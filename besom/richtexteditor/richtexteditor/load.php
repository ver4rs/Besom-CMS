<?php

if($_GET["type"]=="license")
{
	header("Content-Type: application/oct-stream"); 
	$_x215=dirname(__FILE__)."/phpeditor.lic";
	$_x57=filesize($_x215);
	/// $_x144=get_magic_quotes_runtime();
	///set_magic_quotes_runtime(0);
	$_x146=fopen($_x215,"rb");
	$_x130=fread($_x146,$_x57);
	fclose($_x146);
	/// set_magic_quotes_runtime($_x144);
	echo(bin2hex($_x130));
	exit(200);
}

?>