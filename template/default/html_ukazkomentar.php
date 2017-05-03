<div id="vypisujkom">

<?php

if (isset($uzivatel_komentar['user_id']) AND $uzivatel_komentar['user_id'] != '0' AND file_exists(IMAGE_USER_MINI . htmlspecialchars($uzivatel_komentar['user_avatar'] . '.jpg'))) {

	?><img src="<?php echo URL_ADRESA . IMAGE_USER_MINI . htmlspecialchars($uzivatel_komentar['user_avatar'] . '.jpg'); ?>" alt="<?php echo htmlspecialchars($uzivatel_komentar['user_nick']); ?>" title="<?php echo htmlspecialchars($uzivatel_komentar['user_nick']); ?>"><?php

}
else {

	?><img src="<?php echo URL_ADRESA . htmlspecialchars('images/user.gif'); ?>" alt="<?php echo htmlspecialchars($komentar['comment_name']); ?>" title="<?php echo htmlspecialchars($komentar['comment_name']); ?>"><?php
	
}


	?><div class="popkom">

		<h4><?php if (isset($uzivatel_komentar['user_id']) AND $uzivatel_komentar['user_id'] != '0') { echo htmlspecialchars($uzivatel_komentar['user_nick']); } else { echo htmlspecialchars($komentar['comment_name']); } ?><span><?php echo htmlspecialchars(parent::datum($komentar['comment_date'], '1')); ?></span></h4>
		<p><?php echo htmlspecialchars($komentar['comment_text']); ?></p>


	</div>


</div>