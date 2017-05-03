<?php

class search {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	public function searchzobraz () {


		?>
			
		<div class="widget-bound newsletter-widget">
			<div class="heading-light"><h5><?php echo htmlspecialchars($this->lang['widnadpishladat']); ?></h5></div>
						
			<form method="get" action="">

				<input type="text" name="q" placeholder="<?php echo htmlspecialchars($this->lang['searchplaceholder']); ?>" value="<?php if (isset($_GET['q'])) { echo htmlspecialchars($_GET['q']); } ?>">
				<input type="submit" name="" value="<?php echo htmlspecialchars($this->lang['search']); ?>">
			</form>	
						
		</div>
		<?php


	}





}

#$search = new search($this->db, $this->prefix, $this->lang);
#$search->searchzobraz() // zobrazy vyhladavanie serach na stranke








?>