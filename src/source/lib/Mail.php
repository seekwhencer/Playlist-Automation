<?

	class Mail {
		
		public function sendMail($email, $subject, $text, $message=false){
			if($message==false){
				$message  = '<html><head><title>'.$subject.'</title></head><body>';
				$message .= $text;
				$message .= '</body><html>';
			}
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'To: '.$email.' <'.$email.'>' . "\r\n";
			$headers .= 'From: '.PAGE_NAME.' <'.MAIL.'>' . "\r\n";

		    return mail($email, $subject, $message, $headers);
		}
		
		public function buildMailBody(){
			global $_c;	
			
			$action = array(
								'html_content' 	=> '',
								'subject'		=> 'subject',
								'title'			=> 'title',
								
			);
			
			$content = $_c->partial('mail_body', $action);	
				
			return $content;
		}
		
		public function sendSMTP($recipient=false, $subject, $content){
			$smtp_server 	= MAIL_SMTP_SERVER;
			$port 			= MAIL_SMTP_SERVER_PORT;
			$mydomain 		= MAIL_SMTP_DOMAIN;
			$username 		= MAIL_SMTP_USERNAME; // MS Exchange servers will probably require a valid NT domain name as part of the username. E.g., "ntdomainuser"
			$password 		= MAIL_SMTP_PASSWORD;		
			$sender 		= MAIL;
			
            
			require_once('source/lib/smtp/class.phpmailer.php');
            include("source/lib/smtp/class.smtp.php");
			$mail = new PHPMailer(false); // true = throw exceptions
			
			
			$mail->IsSMTP(); // use SMTP
			           			
			try {
				$mail->Host       = MAIL_SMTP_SERVER;
				$mail->SMTPDebug  = false;
				$mail->SMTPAuth   = true;
				$mail->Port       = MAIL_SMTP_SERVER_PORT;
				$mail->Username   = MAIL_SMTP_USERNAME;
				$mail->Password   = MAIL_SMTP_PASSWORD;
                $mail->SMTPSecure = 'ssl';
				
				if(!is_array($recipient)){
					$mail->AddAddress($recipient, '');
				} else {
					foreach($recipient as $r)
						$mail->AddBCC($r, '');
				}
				
				$mail->SetFrom(MAIL, PAGE_NAME);
				$mail->Subject = $subject;
		
				$mail->MsgHTML(utf8_decode($content));
	
				//$mail->AddReplyTo(MAIL, 'First Last');
				//$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
				//$mail->AddAttachment('images/phpmailer.gif');      // attachment
				//$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
		
				$mail->Send();
			  	//echo "Message Sent OK</p>\n";
			} catch (phpmailerException $e) {
				echo $e->errorMessage(); //Pretty error messages from PHPMailer
			} catch (Exception $e) {
				echo $e->getMessage(); //Boring error messages from anything else!
			}
		}
	}
?>