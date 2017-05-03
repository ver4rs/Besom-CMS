<?php

if ($social_share['social_url'] == 'email') {
	# send email
	# data - prijimatel, odosielatel - meno, - predmet, text 
	?>
				<a href="#"  id="elem" target="<?php //echo htmlspecialchars($social_share['social_target']); ?>">
					<img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars($social_share['social_img']); ?>" alt="<?php echo htmlspecialchars($social_share['social_nazov']); ?>" title="<?php echo htmlspecialchars($social_share['social_nazov']); ?>" style="margin-right: 10px; width: 40px; height: 40px; ">
					<?php //echo fb_page_fan(htmlspecialchars($social_share['social_app'])); ?>
					<?php  /*<span><?php echo htmlspecialchars($social_share['social_nazov']); ?></span>  */ ?>
				</a>
<?php	
}
elseif ($social_share['social_url'] == 'print') {
	# tlacit    250 156
	# href=    onClick="window.print();return false"
	# href=javascript:window.print()
?>
				<a href="javascript:void()" onclick="window.print();return false" id="">
					<img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars($social_share['social_img']); ?>" alt="<?php echo htmlspecialchars($social_share['social_nazov']); ?>" title="<?php echo htmlspecialchars($social_share['social_nazov']); ?>" style="margin-right: 10px; width: 40px; height: 40px; ">
					<?php //echo fb_page_fan(htmlspecialchars($social_share['social_app'])); ?>
					<?php  /*<span><?php echo htmlspecialchars($social_share['social_nazov']); ?></span>  */ ?>
				</a>
<?php	
}
else {
?>
				<a href="<?php echo htmlspecialchars($social_share['social_url']) . $url; ?>" id="" target="<?php echo htmlspecialchars($social_share['social_target']); ?>">
					<img src="<?php echo URL_ADRESA . IMAGE_SOCIAL . htmlspecialchars($social_share['social_img']); ?>" alt="<?php echo htmlspecialchars($social_share['social_nazov']); ?>" title="<?php echo htmlspecialchars($social_share['social_nazov']); ?>" style="margin-right: 10px; width: 40px; height: 40px; ">
					<?php //echo fb_page_fan(htmlspecialchars($social_share['social_app'])); ?>
					<?php  /*<span><?php echo htmlspecialchars($social_share['social_nazov']); ?></span>  */ ?>
				</a>
<?php				
				}

?>