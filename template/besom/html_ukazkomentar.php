<li class="comment">

	<?php

	if (isset($uzivatel_komentar['user_id']) AND $uzivatel_komentar['user_id'] != '0' AND file_exists(IMAGE_USER_MINI . htmlspecialchars($uzivatel_komentar['user_avatar'] . '.jpg'))) {

		?><img src="<?php echo URL_ADRESA . IMAGE_USER_MINI . htmlspecialchars($uzivatel_komentar['user_avatar'] . '.jpg'); ?>" alt="<?php echo htmlspecialchars($uzivatel_komentar['user_nick']); ?>" title="<?php echo htmlspecialchars($uzivatel_komentar['user_nick']); ?>" width="60" height="60" alt="60x60" class="thumbnail"><?php

	}
	else {

		?><img src="<?php echo URL_ADRESA . htmlspecialchars('images/user.gif'); ?>" alt="<?php echo htmlspecialchars($komentar['comment_name']); ?>" title="<?php echo htmlspecialchars($komentar['comment_name']); ?>" width="60" height="60" alt="60x60" class="thumbnail"><?php
		
	}
	?>

	<h5 class="nameAuthor"><?php if (isset($uzivatel_komentar['user_id']) AND $uzivatel_komentar['user_id'] != '0') { echo htmlspecialchars($uzivatel_komentar['user_nick']); } else { echo htmlspecialchars($komentar['comment_name']); } ?></h5>

	<div class="meta">
		<span class="date"><?php echo htmlspecialchars(parent::datum($komentar['comment_date'], '3')); ?></span> 
		<span class="time"><?php echo htmlspecialchars(parent::datum($komentar['comment_date'], '4')); ?></span>
	</div>
						
	<div class="contentComment"><?php echo htmlspecialchars($komentar['comment_text']); ?></div>
	
	<span class="noComment"><?php echo htmlspecialchars($pocet); ?></span>
	<div class="toolbar">
			<a href="#" class="button">Odpovedat</a>
	</div>

</li>