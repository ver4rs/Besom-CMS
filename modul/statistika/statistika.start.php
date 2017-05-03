<?php

# START

$statistika = new statistika($this->db, $this->prefix, $this->lang);
//$statistika->zobrazpristupyCelkovo() // zobrazy vyhladavanie serach na stranke
$statistika->zobrazStatistikuMala();


?>