								<div class="article" id="clanok">
									<a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $article['text_url']); ?>">
										
										<?php
										if (file_exists(IMAGE_ARTICLES_MINI . htmlspecialchars($article['text_obrazok']))) {
											
											?><img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_MINI . htmlspecialchars($article['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($article['text_nazov']); ?>" title="<?php echo htmlspecialchars($article['text_nazov']); ?>" id="arimg"><?php
											
										}
										else {
											
											?><img src="<?php echo URL_ADRESA . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($article['text_nazov']); ?>" title="<?php echo htmlspecialchars($article['text_nazov']); ?>" id="arimg"><?php

										}
										?>

									</a>
									<div id="arte">
										<a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $article['text_url']); ?>"><h2><?php echo htmlspecialchars($article['text_nazov']); ?></h2></a>
										<span id="ardop"><img src="<?php echo URL_ADRESA . htmlspecialchars('images/user.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arpridal'] . ' ' . $article['user_meno']); ?>" alt="<?php echo htmlspecialchars($article['user_meno']); ?>"> <?php echo htmlspecialchars($article['user_meno']); ?> &nbsp; &nbsp; 
											<img src="<?php echo URL_ADRESA . htmlspecialchars('images/time.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['artime'] . ' ' . $this->datum($article['text_datum'],'1')); ?>" alt="<?php echo htmlspecialchars($this->datum($article['text_datum'],'1')); ?>"> <?php echo htmlspecialchars($this->datum($article['text_datum'], '1')); ?> &nbsp; &nbsp;  
											<img src="<?php echo URL_ADRESA . htmlspecialchars('images/category.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arcat'] . ' ' . $article['menu_nazov']); ?>" alt="<?php echo htmlspecialchars($article['menu_nazov']); ?>"> <?php echo htmlspecialchars($article['menu_nazov']); ?> &nbsp; &nbsp; 
											<img src="<?php echo URL_ADRESA . htmlspecialchars('images/views.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arviews'] . ' ' . $article['text_visit']); ?>" alt="<?php echo htmlspecialchars($article['text_visit']); ?>"> <?php echo htmlspecialchars($article['text_visit']); ?> &nbsp; &nbsp; 
											<img src="<?php echo URL_ADRESA . htmlspecialchars('images/comment.gif'); ?>" title="<?php echo htmlspecialchars($this->lang['arcomment'] . ' ' . $article['text_comment']); ?>" alt="<?php echo htmlspecialchars($article['text_comment']); ?>"> <?php echo htmlspecialchars($article['text_comment']); ?>
										</span>
										<p id="arpopis"><?php echo htmlspecialchars($article['text_popis']); ?></p>
										<a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $article['text_url']); ?>" id="arbutton"><?php echo htmlspecialchars($this->lang['arviac']); ?></a>
									</div>
								</div>

								<span id="aroddel">&nbsp;</span>