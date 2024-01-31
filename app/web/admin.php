<?php //  admin 
//phpinfo();
// Chargement bibliotheque globale
require_once '../lib/bib_connect.php';
require_once '../lib/bib_application.php';

// Phase de traitement
    // admin.php
if (isset($_POST['register'])) {
    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hasher le mot de passe

    // Inclure le fichier de connexion à la base de données

    // Préparer la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO personne (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)");
    if ($stmt->execute([':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':mot_de_passe' => $mot_de_passe])){
        // Vérifier si l'insertion a réussi et rediriger ou afficher un message
        if ($stmt->rowCount() > 0) {
            // Rediriger vers une page de confirmation ou afficher un succès
            // une mise à jour de valeur ou d'insertion a été réalisé
        } else {
            // Afficher un message d'erreur
            // aucune mise à jour ou insetion n'a été réalisé
        }
    }
    else{
        // La fonction SQL a planté
    }

}
?>
<!-- phase d'affichage -->
<form action="admin.php" method="post">
    Nom: <input type="text" name="nom" required>
    Prénom: <input type="text" name="prenom" required>
    Email: <input type="email" name="email" required>
    Mot de passe: <input type="password" name="mot_de_passe" required>
    <input type="submit" name="register" value="Inscrire">
</form>