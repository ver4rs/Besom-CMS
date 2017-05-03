<!-- <div class="eleven">  -->
	<h1 class="title">
		<a href="<?php echo URL_ADRESA . htmlspecialchars('?article=' . $fullArticle['text_url']); ?>"><?php echo htmlspecialchars($fullArticle['text_nazov']); ?></a>
	</h1>
						
	<div class="meta">

		<span class="date"><?php echo htmlspecialchars($this->datum($fullArticle['text_datum'], '3')); ?></span> 
		<span class="time"><?php echo htmlspecialchars($this->datum($fullArticle['text_datum'], '4')); ?></span> 
		<span class="category"><?php echo htmlspecialchars($this->lang['arcat']); ?>  <a href="<?php echo URL_ADRESA . htmlspecialchars('?menu=' . $fullArticle['menu_url']); ?>"><?php echo htmlspecialchars($fullArticle['menu_nazov']); ?></a></span>  
		<span class="author"><?php echo htmlspecialchars($this->lang['arpridal']); ?> <a href="#"><?php echo htmlspecialchars($fullArticle['user_meno']); ?></a></span>  
		<span class="noComments" title="<?php echo htmlspecialchars($fullArticle['text_visit']); ?> <?php echo htmlspecialchars(strtoupper($this->lang['arviews'])); ?>"><i class="icon-eye-open"></i> <?php echo htmlspecialchars($fullArticle['text_visit']); ?></span>
		
		<span class="noComments" title="<?php echo htmlspecialchars($fullArticle['text_comment']); ?> <?php echo htmlspecialchars(strtoupper($this->lang['arcomment'])); ?>"><i class="icon-comments"></i> <?php echo htmlspecialchars($fullArticle['text_comment']); ?></span>

	</div>
	<div class="meta">
		<span class="date">
			<i class="icon-tags"  title="<?php echo htmlspecialchars(strtoupper($this->lang['artags'])); ?>"></i> <?php echo htmlspecialchars(strtoupper($this->lang['artags'])); ?> <?php echo htmlspecialchars($fullArticle['text_tags']); ?>
		</span>
	</div>

	<?php
	if (file_exists(IMAGE_ARTICLES_NORMAL . htmlspecialchars($fullArticle['text_obrazok']))) {
							
		?><img src="<?php echo URL_ADRESA . IMAGE_ARTICLES_NORMAL . htmlspecialchars($fullArticle['text_obrazok']); ?>" id="avatar" alt="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>" title="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>" class="thumbnail scale-with-grid"><?php

	}
	else {

		/*?><img src="<?php echo URL_ADRESA . htmlspecialchars('images/no-image.gif'); ?>" id="avatar" alt="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>" title="<?php echo htmlspecialchars($fullArticle['text_nazov']); ?>"><?php*/
							
	}
	?>
	
	<p><?php echo htmlspecialchars_decode($fullArticle['text_cely']); ?></p>


						<!--  <div id="postAuthor">
							<img src="#" alt="80x80" class="thumbnail">
							<div>
								<h5 class="nameAuthor">Kaka KAKAM</h5>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut porttitor lacus. In aliquam venenatis me tus a pulvinar. </p>
								
							</div>
						</div><!- - end postAuthor -->
<!-- </div> -->