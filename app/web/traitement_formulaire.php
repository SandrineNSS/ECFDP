<?php 

require_once '../lib/bib_connect.php';
require_once '../lib/bib_general.php';
require_once '../lib/bib_sql.php';
require_once '../lib/bib_mail.php';

$messageSuccess = '';
$messageErreur = '';
$message = "";
$email = "";

//  Traitement formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //print_r($_POST); exit;
    $email = trim($_POST['email']);
    $message = $_POST['message'];
//Gestion messages erreur success 
    if ($email != strip_tags($email)){
        $messageErreur = 'Votre saisie d\'email est incorrecte';
    } 
    else
    if (!verifMail($email)) {
        $messageErreur = 'Adresse email non valide. Veuillez réessayer.';
    }
    else{

        /**réception des données formulaire de contact, insertion en DB, envoi email de confirm au client. Requêtes SQL préparées  meilleure sécurité + une fonctionnalité d'envoi d'email pr informer le client que sa demande a été reçue et traitée. */
                $data = [
                    ':email'=> $email, 
                    ':message' => $message,
                ];

                $sql = 'INSERT INTO client_demande (email, message) VALUES (:email, :message)';

                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($data)) {
                    $messageSuccess = "Votre message a été envoyé avec succès.";

        //Envoi Email de Confirm
        $mail = Mail::getMail();

        // Configuré avec expéditeur 
        $from = "sandrineECF@laposte.net";
        $mail->setFrom($from, $from);
        $mail->addAddress($email, $email);
        $mail->addReplyTo($from, $from);

        // Intégration Formulaire HTML
        $mail->isHTML(false);                                
        $mail->Subject = 'Confirmation de votre domaine de contact';
        $mail->Body    = "A propos du pack <b>in bold!</b>";
        $mail->AltBody = "A propos du pack ";

        if ($mail->send()) {
            $messageErreur = "Votre demande d'information a bien été prise en compte";
        } else {
            $messageErreur = "Erreur lors la prise en compte de votre message";
        }
        } else {
            $messageErreur = "Il y a eu un problème, veuillez recommencer.";
                }
    }
}
require_once '../lib/header.php';
?>
<body>
    <form method="POST" action="traitement_formulaire.php">
        <section class="border-top pb-5 mb-5 container">
            <h2 class="text-center display-4 my-md-5 my-4">Contact-us</h2>
    
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?=$email?>">
                    <p class="form-text m-0">We'll never share your email.</p>
                </div>
            
                <div class="form-floating mb-3">
                    <textarea class="form-control" placeholder="Your message" id="floatTxtArea" name="message" style="height: 100px"><?=$message?></textarea>
                    <label for="floatTxtArea">Your message</label>
                </div>
            
                    <button class="btn btn-primary">Submit</button>
        </section>
    </form>
    <?php if ($messageSuccess!='') {?>
    <div>
        <?=$messageSuccess?>
    </div>
    <?php }?>
    <?php if ($messageErreur!='') {?>
    <div>
        <?=$messageErreur?>
    </div>
    <?php }?>
    <?php require_once '../lib/footer.php';?>
</body>