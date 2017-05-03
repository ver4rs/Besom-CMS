					<div class="fullarticle">
						<h2><?php echo htmlspecialchars($fullArticle['text_nazov']); ?></h2>
						<?php
						if (file_exists(IMAGE_ARTICLES_NORMAL . htmlspecialchars($fullArticle['text_obrazok']))) {
							
							?><img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_NORMAL . htmlspecialchars($fullArticle['text_obrazok']); ?>" id="avatar" alt="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>" title="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>"><?php

						}
						else {

							/*?><img src="<?php echo URL_ADRESA . htmlspecialchars('images/no-image.gif'); ?>" id="avatar" alt="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>" title="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>"><?php
							*/
						}
						?>
						<p id="fullarpanel">
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/user.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arpridal'] . ' ' . $fullArticle['user_meno']); ?>" alt="<?php echo htmlspecialchars($fullArticle['user_meno']); ?>"> <?php echo htmlspecialchars($fullArticle['user_meno']); ?></span>
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/time.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['artime'] . ' ' . $this->datum($fullArticle['text_datum'],'1')); ?>" alt="<?php echo htmlspecialchars($this->datum($fullArticle['text_datum'],'1')); ?>"> <?php echo htmlspecialchars($this->datum($fullArticle['text_datum'], '1')); ?></span>  
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/category.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arcat'] . ' ' . $fullArticle['menu_nazov']); ?>" alt="<?php echo htmlspecialchars($fullArticle['menu_nazov']); ?>"> <?php echo htmlspecialchars($fullArticle['menu_nazov']); ?></span>
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/views.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arviews'] . ' ' . $fullArticle['text_visit']); ?>" alt="<?php echo htmlspecialchars($fullArticle['text_visit']); ?>"> <?php echo htmlspecialchars($fullArticle['text_visit']); ?></span>
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/comment.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arcomment'] . ' ' . $fullArticle['text_comment']); ?>" alt="<?php echo htmlspecialchars($fullArticle['text_comment']); ?>"> <?php echo htmlspecialchars($fullArticle['text_comment']); ?></span>			
						</p>
						<p id="fullartags"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/tags.gif'); ?>" alt="<?php echo htmlspecialchars($this->lang['artags']); ?>" title="<?php echo htmlspecialchars($this->lang['artags']); ?>"><span id="tagsp"><?php echo htmlspecialchars($this->lang['artags']); ?></span><?php echo htmlspecialchars($fullArticle['text_tags']); ?></p>
						<span>&nbsp;</span>
						<p id="fullartext"><?php echo htmlspecialchars_decode($fullArticle['text_cely']); ?></p>
					</div>