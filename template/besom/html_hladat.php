<div class="post">
	<?php
	
	if (file_exists(IMAGE_ARTICLES_MINI . htmlspecialchars($hladaj['text_obrazok']))) {									
		?><img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_MINI . htmlspecialchars($hladaj['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($hladaj['text_nazov']); ?>" title="<?php echo htmlspecialchars($hladaj['text_nazov']); ?>" style="width: 160px; height: 160px;" id="arimg" class="thumbnail"><?php
	}
	else {									
		?><img src="<?php echo URL_ADRESA . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($hladaj['text_nazov']); ?>" title="<?php echo htmlspecialchars($hladaj['text_nazov']); ?>" style="width: 160px; height: 160px;" id="arimg" class="thumbnail"><?php
	}
	?>
	
	<div class="info">
		<h3 class="title">
			<a href="<?php 	if ($hladaj['menu_typ'] == 'clanok') { 
									echo URL_ADRESA . htmlspecialchars('?article=' . $hladaj['text_url']);
								}
								else { 
									echo URL_ADRESA . htmlspecialchars('?menu=' . $hladaj['menu_url']);
								} ?>"><?php echo htmlspecialchars($hladaj['text_nazov']); ?></a>
		</h3>
		
		<div class="meta">
			<span class="date"><?php echo htmlspecialchars($this->datum($hladaj['text_datum'],'3')); ?></span> 
			<span class="category"><?php echo htmlspecialchars($this->lang['arcat']); ?> <a href="<?php echo URL_ADRESA . htmlspecialchars('?menu=' . $hladaj['menu_url']); ?>"><?php echo htmlspecialchars($hladaj['menu_nazov']); ?></a></span>  
			<span class="author"><?php echo htmlspecialchars($this->lang['arpridal']); ?> <a href="#"><?php echo htmlspecialchars($hladaj['user_meno']); ?></a></span></br>
			<span class="date" title="<?php echo htmlspecialchars(strtoupper($this->lang['arviews'])); ?>" alt="<?php echo htmlspecialchars(strtoupper($this->lang['arviews'])); ?>"><i class="icon-eye-open"></i> <?php echo htmlspecialchars($hladaj['text_visit']); ?></span> 
			<span class="date" title="<?php echo htmlspecialchars(strtoupper($this->lang['arcomment'])); ?>" alt="<?php echo htmlspecialchars(strtoupper($this->lang['arcomment'])); ?>"><i class="icon-comments"></i> <?php echo htmlspecialchars($hladaj['text_comment']); ?></span> 
		</div>
		
		<p><?php echo htmlspecialchars($hladaj['text_popis']); ?></p>
		
		<a href="<?php 	if ($hladaj['menu_typ'] == 'clanok') { 
									echo URL_ADRESA . htmlspecialchars('?article=' . $hladaj['text_url']);
								}
								else { 
									echo URL_ADRESA . htmlspecialchars('?menu=' . $hladaj['menu_url']);
								} ?>" class="button more"><?php echo htmlspecialchars(strtoupper($this->lang['arviac'])); ?></a>
	</div>
</div>
