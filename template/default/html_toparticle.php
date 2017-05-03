				<div id="topnews">
					<?php

					if (file_exists(IMAGE_ARTICLES_MINI . htmlspecialchars($row['text_obrazok']))) {
						?><a id="image" href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $row['text_url']); ?>"><img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_MINI . htmlspecialchars($row['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($row['text_nazov']); ?>" title="<?php echo htmlspecialchars($row['text_nazov']); ?>"></a><?php
					}
					else {
						?><a id="image" href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $row['text_url']); ?>"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($row['text_nazov']); ?>" title="<?php echo htmlspecialchars($row['text_nazov']); ?>"></a><?php
					}

					?>
					<a id="url" href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $row['text_url']); ?>" title="<?php echo htmlspecialchars($row['text_nazov']); ?>"><?php echo htmlspecialchars($row['text_nazov']); ?></a>
					
						<p id="fullarpanel">
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/time.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['artime'] . ' ' . $this->datum($row['text_datum'],'2')); ?>" alt="<?php echo htmlspecialchars($this->datum($row['text_datum'],'2')); ?>"> <?php echo htmlspecialchars($this->datum($row['text_datum'], '2')); ?></span>  
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/views.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arviews'] . ' ' . $row['text_visit']); ?>" alt="<?php echo htmlspecialchars($row['text_visit']); ?>"> <?php echo htmlspecialchars($row['text_visit']); ?></span>
							<span><img src="<?php echo URL_ADRESA . htmlspecialchars('images/comment.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arcomment'] . ' ' . $row['text_comment']); ?>" alt="<?php echo htmlspecialchars($row['text_comment']); ?>"> <?php echo htmlspecialchars($row['text_comment']); ?></span>			
						</p>
				</div>