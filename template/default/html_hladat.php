				<a href="<?php 	if ($hladaj['menu_typ'] == 'clanok') { 
									echo URL_ADRESA . htmlspecialchars('?article=' . $hladaj['text_url']);
								}
								else { 
									echo URL_ADRESA . htmlspecialchars('?menu=' . $hladaj['menu_url']);
								} ?>">
					<div class="hladat">
						<h2><?php echo htmlspecialchars($hladaj['text_nazov']); ?></h2>
						<p id="popis"><?php echo htmlspecialchars($hladaj['text_popis']); ?></p>
						<p id="count">
							<img src="<?php echo URL_ADRESA . htmlspecialchars('images/time.gif'); ?>" alt="<?php echo htmlspecialchars($this->lang['artime'] . ' ' . $this->datum($hladaj['text_datum'],'2')); ?>" title="<?php echo htmlspecialchars($this->lang['artime'] . ' ' . $this->datum($hladaj['text_datum'],'2')); ?>"><span><?php echo htmlspecialchars($this->datum($hladaj['text_datum'],'2')); ?></span>
							<img src="<?php echo URL_ADRESA . htmlspecialchars('images/views.gif'); ?>" alt="<?php echo htmlspecialchars($this->lang['arviews'] . ' ' . $hladaj['text_visit']); ?>" title="<?php echo htmlspecialchars($this->lang['arviews'] . ' ' . $hladaj['text_visit']); ?>"><span><?php echo htmlspecialchars($hladaj['text_visit']); ?></span>
							<img src="<?php echo URL_ADRESA . htmlspecialchars('images/comment.gif'); ?>" alt="<?php echo htmlspecialchars($this->lang['arcomment'] . ' ' . $hladaj['text_comment']); ?>" title="<?php echo htmlspecialchars($this->lang['arcomment'] . ' ' . $hladaj['text_comment']); ?>"><span><?php echo htmlspecialchars($hladaj['text_comment']); ?></span>
						</p>

					</div>
				</a>

				<span id="oddel">&nbsp;</span>
