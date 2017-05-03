<?php
if (isset($fullArticle)) {
	$article = $fullArticle;
}
?>
<div class="photo">
	<img src="<?php echo URL_ADRESA . GALLERY_CESTA_MINI . htmlspecialchars($article['photos_img']); ?>" alt="<?php echo htmlspecialchars($article['photos_nazov']); ?>" title="<?php echo htmlspecialchars($article['photos_nazov']); ?>" style="width: 100px;">
	<span><?php echo htmlspecialchars($article['photos_nazov']); ?></span>
	<span><?php echo htmlspecialchars($article['photos_popis']); ?></span>
</div>