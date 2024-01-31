<?php
$chaineAchercher = '../lib_ext';

if (strpos(get_include_path(), $chaineAchercher) === false)
    set_include_path(get_include_path().';'.$chaineAchercher);
    
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

class Mail{

    public static function getMail(){
        /** 
         * A voir dans le futur ce qu'on appelle SPF et DKIM
         * 
         */
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = 'smtp.laposte.net';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPDebug = 0; // 2 permet d'avoir le debuggage des   
        $mail->Username = 'sandrineECF@laposte.net';
        $mail->Password = 'sandrineECF1234&';
        $mail->Helo = 'DEV-PC';
        $mail->AuthType = 'LOGIN PLAIN';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Important evite les controles ANTI SPAM sfr et laposte
        $mail->XMailer = null;

        return $mail;
    }
}
?>