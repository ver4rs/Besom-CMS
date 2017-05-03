							<div class="text">
								<?php 
								// nadpis
								if ($article['text_nazov_ano'] == '1') {

									?><h1 id="anadpis"><?php echo htmlspecialchars($article['text_nazov']); ?></h1><?php

								}
								 
								// popis
								if ($article['text_popis_ano'] == '1') { // nechame
										
									?><p id="apopis"><?php echo htmlspecialchars($article['text_popis']); ?></p><?php

								}
								?>
								<p id="atext"><?php echo htmlspecialchars_decode($article['text_cely']); ?></p>
							</div>