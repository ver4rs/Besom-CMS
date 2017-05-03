<?php

include_once('besom/mailer/class.phpmailer.php');

class kontakt {

	private $db, $prefix;
	public $lang1;

	public function __construct ($conn, $prefix, $lang) {
		$this->db = $conn;
		$this->prefix = $prefix;
		$this->lang = $lang;
	}

	protected static $mailer=NULL;

    protected static function getInstance() {

        if (self::$mailer === NULL) {
            
            self::$mailer = new PHPMailer();
        }
        return self::$mailer;
    }

	private function sendEmail($adminEmail, $adminMeno, $email, $body, $subject_add = ""){
        $mail=self::getInstance();

        $mail->From = $adminEmail; //moja adresa
        $mail->FromName = $adminMeno; //moje meno
        $mail->AddAddress($email); //Vas mail
        $mail->WordWrap = 50;                                 // po 50 znaku slova rozdel slovo
        $mail->IsHTML(true);
        $mail->Subject = $subject_add;
        $mail->Body    = htmlspecialchars_decode($body);
        $mail->AltBody = "Táto správa obsahuje HTML značky... Váš poštový klient ich asi nepodporuje";

        $mail->CharSet = "UTF-8";
        $mail->Send();
    }


	public function kontaktForm () {

		#POST
		if (isset($_POST['submit']) AND $_POST['submit'] == 'send' AND isset($_POST['formemail']) AND isset($_POST['formco']) AND isset($_POST['formpredmet']) AND isset($_POST['formtext'])) {

			$email = (isset($_POST['formemail'])) ? $_POST['formemail'] : '';
			$co = (isset($_POST['formco'])) ? $_POST['formco'] : '';
			$predmet = (isset($_POST['formpredmet'])) ? $_POST['formpredmet'] : '';
			$text = (isset($_POST['formtext'])) ? $_POST['formtext'] : '';

			if (filter_var($email, FILTER_VALIDATE_EMAIL) AND !empty($email) AND !empty($co) AND !empty($predmet) AND !empty($text)) {
				# OK
				
				# SEND EMAIL
				# EMAIL   -- stav    newToken
				$sys = $this->db->query('SELECT system_admin_email, system_title, system_url 
											FROM be_system' . $this->prefix . ' 
											ORDER BY system_id DESC ');
				if ($sys->num_rows != FALSE) {
					$system = $sys->fetch_assoc();

					$adminEmail = $system['system_admin_email'];
					$adminMeno = $system['system_title'];
					$adminUrl = $system['system_url'];
				}
				else {
					$adminEmail = 'besom@besom.sk';
					$adminMeno = 'Besom.sk CMS';
					$adminUrl = 'http://www.besom.sk';
				}

				$subject = 'Vec :' . $co . ' | ' . $predmet;
				$body = '<html><head></head><body>' . $text . '</body></html>';

				$this->sendEmail($adminEmail, $adminMeno, $email, $body, $subject);



				$_POST['ok'] = 'okkkker kfkr ek k';
			}
			else {
				#ERROR
				$_POST['error'] = 'Musia byt vsetky polia vyplnene. A emailmusi byt validny.';
			}
			
		}


		?>
		<div class="eleven">
		<h3>Kontaktujte ma</h3>
			<?php
			if (isset($_POST['error']) AND $_POST['error'] != '') {
				?><div class="alert-warning" style="width: 550px; text-align: center;"><?php echo htmlspecialchars($_POST['error']); ?></div><?php
			}
			elseif (isset($_POST['ok']) AND $_POST['ok'] != '') {
				?><div class="alert-success" style="width: 550px; text-align: center;"><?php echo htmlspecialchars($_POST['ok']); ?></div><?php
			}
			else {

			}
			?>
			<form method="post" action="">
				<p>
					<label>Email:</label>
					<input type="text" name="formemail" placeholder="jan@hrasko.sk" id="text">
				</p>
				<p>
					<label>Ohladne coho:</label>
					<input type="text" name="formco" placeholder="Co" id="text">
				</p>
				<p>
					<label>Predmet:</label>
					<input type="text" name="formpredmet" placeholder="Predmet" size="100" id="text">
				</p>
				<p>
					<label>Text:</label>
					<textarea name="formtext" placeholder="Text" style="width: 550px;"></textarea>
				</p>
				<p>
					<button name="submit" value="send" class="subnews">Odoslat</button>
				</p>
			</form>
		</div>
		<?php


	

	}





}

#$search = new search($this->db, $this->prefix, $this->lang);
#$search->searchzobraz() // zobrazy vyhladavanie serach na stranke








?>