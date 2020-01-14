
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer{

    private $user;
    private $smtp_config;

    public function __construct($user){
		$this->user = $user;
        $smtp_config = new Smtp_Config();
        $this->smtp_config = $smtp_config->where(["admin_id" => $user->admin_id])->one();
    }

    function send(){

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {

            $mail->ClearAllRecipients( );

            //Server settings
            // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $this->smtp_config->host;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->smtp_config->username;                 // SMTP username
            $mail->Password = $this->smtp_config->password;                           // SMTP password
            // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->SMTPSecure = $this->smtp_config->encryption;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->smtp_config->port;                                    // TCP port to connect to


            //Recipients
            $mail->setFrom($this->smtp_config->sender_email, 'Mailer');
            $mail->addAddress($this->user->email);     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            
            //Content

            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Verify your account';
            $mail->Body = 'Your verification code : ' . $this->user->verification_token;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );


            $mail->send();
            return true;
            // echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            return false;
        }
    }

}