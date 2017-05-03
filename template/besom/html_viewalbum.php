<?php
// $album;
// $photos;

?>
<a class="example-image-link" href="<?php echo URL_ADRESA . GALLERY_CESTA_ORIGINAL . htmlspecialchars($photos['photos_img']); ?>" data-lightbox="example-set" title="<?php echo htmlspecialchars($photos['photos_nazov'] . '  - ' . $photos['photos_popis']); ?>">
	<img class="example-image" src="<?php echo URL_ADRESA . GALLERY_CESTA_MINI . htmlspecialchars($photos['photos_img']); ?>" alt="<?php echo htmlspecialchars($photos['photos_nazov']); ?>" width="150" height="150"/>
</a>