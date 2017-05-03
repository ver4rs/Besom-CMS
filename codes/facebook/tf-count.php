<?php
/*
  Name: Twitter Followers count
	Version : 1
	Author: Linesh Jose
	Url: http://lineshjose.com
	Email: lineshjose@gmail.com
	Donate:  http://bit.ly/donate-linesh
	github: https://github.com/lineshjose
	Copyright: Copyright (c) 2012 LineshJose.com
	
	Note: This script is free; you can redistribute it and/or modify  it under the terms of the GNU General Public License as published by 
		the Free Software Foundation; either version 2 of the License, or (at your option) any later version.This script is distributed in the hope 
		that it will be useful,    but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
		See the  GNU General Public License for more details.

-----------------------------------------------------------

	This php function returns your Twitter Followers count
	
	@param $user_name: your twiiter username
	
	Usage: <p>Followers: <strong><?php echo tf_count('lineshjose');?></strong></p>
	
*/
function tf_count($user_name='')
{
	if($user_name)	{
		$tuser_info = file_get_contents('http://api.twitter.com/1/users/show/'.$user_name.'.xml');
		$begin_tag = '<followers_count>'; 
		$end_tag = '</followers_count>';
		$first_part = explode($begin_tag,$tuser_info);
		$sec_part = explode($end_tag,$first_part[1] );
		$fcount = $sec_part[0];
		return  $fcount;
	}else{
		return '0';
	}
}


?>
Twitter: <strong><?php echo tf_count('ver4rs');?></strong>.