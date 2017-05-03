<!-- <div class="wrap-main-content">
	<div class="container fullwidth">
		<div class="sixteen columns">  -->

			<?php if ($article['text_nazov_ano'] == '1') { //nadpis ?>
				<h1 class="title"><?php echo htmlspecialchars($article['text_nazov']); ?></h1>
			<?php } ?>	

			<?php if ($article['text_popis_ano'] == '1') { // popis ?>
				<p style="color: #CDCDCD;"><?php echo htmlspecialchars($article['text_popis']); ?></p>
			<?php } ?>

			<p style="text-align: justify; "><?php echo htmlspecialchars_decode($article['text_cely']); ?></p>

				
	<!-- </div>	


	
	</div>
</div>  -->