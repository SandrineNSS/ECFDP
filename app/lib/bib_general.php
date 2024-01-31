<?php 

class POST{    
    public static function get($data){
        if (isset($_POST[$data]))
            return $_POST[$data];
        else
            return null;
    }
}

class GET{
    public static function get($data){
        if (isset($_GET[$data]))
            return $_GET[$data];
        else
            return null;
    }
}

function verifMail ($mail){
    if ($mail == null || trim($mail)=='')
        return false;
    /**
     * On controle la saisie utilisateur de l'email qui de manière conventionnelle s'écrit <user>@<domaine>
     * on utilise alors un controle sur la base d'expression régulière : via la fonction php preg_match
     * 
     *
     * /^   signifie commence par 
     * [a-zA-Z0-9_]  tout caractère de 'a' à 'z' ou 'A' à 'Z' ou de 0 à 9 ou '_' 
     * + même regle que la précédemmant pour autant de caractère que saisie 
     * @ obligation d'avoir ce caractère
     * [a-zA-Z0-9\-]  tout caractère de 'a' à 'z' ou 'A' à 'Z' ou de 0 à 9 ou '-' 
     * + même regle que précédemment pr autant de caractère que saisie 
     * \. il faut un point
     * [a-zA-Z0-9\-\.]  tout caractère de 'a' à 'z' ou 'A' à 'Z' ou de 0 à 9 ou '-' ou '.' 
     * + même regle que la précédemment pour autant de caractère que saisie 
     * $ jusqu'à la fin
     * /i ignore case
     */
    if (preg_match ('/^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]/i', $mail ) ) {
        return false;
    }
    list ($nom, $domaine) = explode ('@', $mail);
    /**
     * Là ici on va tester le MX sur le domaine voir https://fr.wikipedia.org/wiki/Enregistrement_Mail_eXchanger
     */
    if (getmxrr ($domaine, $mxhosts))  {
        return true;
    } else {
        return false;
    }
} 