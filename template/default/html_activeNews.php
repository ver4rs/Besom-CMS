<div class="status">
	<h3>Aktivacia odoberania noviniek</h3>

	<p>
		<?php
			if ($stav == 'active') {
				# true ok -- prave ste aktivovali
				?>Aktivacia odoberania noviniek bola potvrdena. Vasim emailom <?php echo $overenie['newsletter_email']; ?></br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			elseif ($stav == 'activerepeat') {
				# uz je aktivny / // co robis repeat???
				?>Prave sa pokusate o znovu potvrdenie aktivacie odoberania noviniek. Odoberanie novinek uz bolo aktivovane. Ak si myslite ze nedostavate nas odber noviniek alebo mate ine podozrenie, konatktujte spravcu stranky. </br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			elseif ($stav == 'adminactive') {
				# admin active --- admin aktivoval v systeme odber noviniek
				?>Ak ste mali problem s aktivaciou odberu noviniek, tak prave admin vam potvrdil aktivaciu. Ak ste si nezelali aktivovat odber a chcete stym nieco robit, kontaktujte spravcu stranky. </br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			elseif ($stav == 'newToken') {
				# new token --- novy odoslany
				?>Aktivacia odoberania noviniek bola potvrdena. Vasim emailom <?php echo $overenie['newsletter_email']; ?></br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			elseif ($stav == 'newEmail') {
				# new email - pridany v systeme
				?>Aktivacia odoberania noviniek bola potvrdena. Vasim emailom <?php echo $overenie['newsletter_email']; ?></br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			elseif ($stav == 'del') {
				# delete email --- vymazanie zo systemu adminom
				?>Prave vam bol zruseny odber noviniek. Ak mate nejake problemy alebo si myslite ze zruseny odber bol netolerantny, tak kontaktujte admina stranky. Mozete tiez skusit znovu odoberat odber noviniek. Vas email <?php echo $overenie['newsletter_email']; ?></br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			elseif ($stav == 'block') {
				# block -- ucet zablkokovany 
				?>Odoberanie noviniek nebolo potvrdene. V momentalnej dobe je odosielanie noviniek na vas email pozastavene. Zial teraz niesme schpny vam povedat preco je to takto. Ak stym mate problem alebo myslite ze vase pozastavenie bolo nespravne, skuste konatktovat spravcu stranky. </br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			elseif ($stav == 'error') {
				# error - nieje take chba
				?>Asi nas skusate. No dobre ste spokojny? Vuhoveli sme vasim poziadavkam. Ak ste sklamany, nezufajte, skusajte mozno sa podari. Sme len ludia. Ak mate nejaku staznost tak kontaktujte spravcu stranky. </br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}
			else {
				# error - nieje take chba
				?>Asi nas skusate. No dobre ste spokojny? Vuhoveli sme vasim poziadavkam. Ak ste sklamany, nezufajte, skusajte mozno sa podari. Sme len ludia. Ak mate nejaku staznost tak kontaktujte spravcu stranky. </br> Ist na hlavnu stranku <a href="<?php echo URL_ADRESA; ?>">Hlavna stranka</a><?php
			}

		?>
	</p>

</div>