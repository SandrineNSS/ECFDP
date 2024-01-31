<?php

require_once '../lib/bib_connect.php';
require_once '../lib/bib_sql.php';
require_once '../lib/header.php';
require_once '../lib/footer.php';

// Vérifie si l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    // Redirige vers la page de connexion si non-admin
    header('Location: login.php');
    exit;
}

if (isset($_POST['register_user'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        // Gére l'erreur si l'email n'est pas valide
        $erreur = "Adresse email non valide.";
        // Affiche un message d'erreur ou rediriger avec un paramètre d'erreur
    } else {
        // Hachage du mot de passe
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

        // Préparation de la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO personne (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)");
        if ($stmt->execute([':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':mot_de_passe' => $mot_de_passe])) {
            // Redirection si l'insertion a réussi
            header('Location: formulaire_contact.php?success');
            exit;
        } else {
            // Gérer l'erreur si l'insertion a échoué
            $erreur = "Problème lors de l'enregistrement de l'utilisateur.";
            // Afficher un message d'erreur ou rediriger avec un paramètre d'erreur
        }
    }
}

// Afficher ou gérer l'erreur si elle existe
if (isset($erreur)) {
    echo $erreur; 
}
?>
<!-- Formulaire d'inscription HTML voir celui de man -->
<form action="gestion_utilisateur.php" method="post">
    Nom: <input type="text" name="nom" required>
    Prénom: <input type="text" name="prenom" required>
    Email: <input type="email" name="email" required>
    Mot de passe: <input type="password" name="mot_de_passe" required>
    <input type="submit" name="register_user" value="Inscrire">
</form>
