<div class="post">
	<?php
	
	if (file_exists(IMAGE_ARTICLES_MINI . htmlspecialchars($article['text_obrazok']))) {									
		?><img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_MINI . htmlspecialchars($article['text_obrazok']); ?>" alt="<?php echo htmlspecialchars($article['text_nazov']); ?>" title="<?php echo htmlspecialchars($article['text_nazov']); ?>" style="width: 160px; height: 160px;" id="arimg" class="thumbnail"><?php
	}
	else {									
		?><img src="<?php echo URL_ADRESA . htmlspecialchars('images/no-image.gif'); ?>" alt="<?php echo htmlspecialchars($article['text_nazov']); ?>" title="<?php echo htmlspecialchars($article['text_nazov']); ?>" style="width: 160px; height: 160px;" id="arimg" class="thumbnail"><?php
	}
	?>
	
	<div class="info">
		<h3 class="title">
			<a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $article['text_url']); ?>"><?php echo htmlspecialchars($article['text_nazov']); ?></a>
		</h3>
		
		<div class="meta">
			<span class="date"><?php echo htmlspecialchars($this->datum($article['text_datum'], '3')); ?></span> 
			<span class="category"><?php echo htmlspecialchars($this->lang['arcat']); ?> <a href="<?php echo URL_ADRESA . htmlspecialchars('?menu=' . $article['menu_url']); ?>"><?php echo htmlspecialchars($article['menu_nazov']); ?></a></span>  
			<span class="author"><?php echo htmlspecialchars($this->lang['arpridal']); ?> <a href="#"><?php echo htmlspecialchars($article['user_meno']); ?></a></span></br>
			<span class="date" title="<?php echo htmlspecialchars(strtoupper($this->lang['arviews'])); ?>" alt="<?php echo htmlspecialchars(strtoupper($this->lang['arviews'])); ?>"><i class="icon-eye-open"></i> <?php echo htmlspecialchars($article['text_visit']); ?></span> 
			<span class="date" title="<?php echo htmlspecialchars(strtoupper($this->lang['arcomment'])); ?>" alt="<?php echo htmlspecialchars(strtoupper($this->lang['arcomment'])); ?>"><i class="icon-comments"></i> <?php echo htmlspecialchars($article['text_comment']); ?></span> 
		</div>
		
		<p><?php echo htmlspecialchars($article['text_popis']); ?></p>
		
		<a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $article['text_url']); ?>" class="button more"><?php echo htmlspecialchars(strtoupper($this->lang['arviac'])); ?></a>
	</div>
</div>