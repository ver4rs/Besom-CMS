				<li>
					<a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $row['text_url']); ?>">
						<img src="./clanok_files/50x50-thumbnail-1.jpg" alt="50x50" class="thumbnail">
						<?php

					if (file_exists(IMAGE_ARTICLES_MINI . htmlspecialchars($row['text_obrazok']))) {
						?><a id="image" href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $row['text_url']); ?>"><img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_MINI . htmlspecialchars($row['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($row['text_nazov']); ?>" title="<?php echo htmlspecialchars($row['text_nazov']); ?>" class="thumbnail"></a><?php
					}
					else {
						?><a id="image" href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $row['text_url']); ?>"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($row['text_nazov']); ?>" title="<?php echo htmlspecialchars($row['text_nazov']); ?>" class="thumbnail"></a><?php
					}

					?>
					</a>
					<div class="clearfix">
						<h6 class="title"><a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $row['text_url']); ?>"><?php echo htmlspecialchars($row['text_nazov']); ?></a></h6>

						<div class="meta">
							<span class="author"><?php echo htmlspecialchars($this->datum($row['text_datum'], '2')); ?></span>  
							<span class="noComments"><?php echo htmlspecialchars($row['text_comment']); ?> Comments</span>
						</div>
					</div>
				</li>
