<?php
require_once '../lib/bib_connect.php';
require_once '../lib/bib_sql.php';
require_once '../class/classUtilisateur.php';

$identifiant = "";
$mdp = "";
/**
 * Processus normalement lié à la validation suite à l'action de cliquer sur le bouton se connecter
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!isset($_POST['username']) || $_POST['username'] == ''){
        $mes = "La saisie de l'identifiant est obligatoire";
    }
    else
    if (!isset($_POST['password']) || $_POST['password'] == ''){
        $mes = "La saisie du mot de passe est obligatoire";
    }
    else {
        $identifiant = $_POST['username'];
        $mdp = $_POST['password'];
        $user = new Utilisateur();
        if ($user->verifieConnection($pdo,$identifiant,$mdp)){
            $_SESSION['clUser'] = serialize($user);
            header("Location:index.php");
            exit;
        }
        else 
            $mes = "Votre saisie identifiant / mot de passe est incorrecte";
    }
}
else{
    $mes = 'Connectez-vous pour accéder à votre compte.';
}
?> <?php require_once '../lib/header.php'?>;
<body>
	<div id="app">
		<form method="POST" class="signin h-screen overflow-y-auto px-4 py-4 md:flex md:items-center md:justify-center md:pb-32">
			<div
				class="signin-inner h-full flex flex-col justify-between md:block md:h-auto">
				<div class="signin-header">
					<h1 class="text-center">Hosting</h1>
					<div class="text-center">
						<h4 class="junipero mb-0">Veuillez vous indentifier!</h4>
						<p class="junipero text text-secondary"><?= $mes ?></p>
					</div>
					<div class="mt-12 p3-auth-form">
						<div>	
							<div class="junipero field text-input labeled empty !w-full">
								<input id="username" name="username" required="" type="text" placeholder="Identifiant" value="<?= $identifiant?>">
							</div>
						</div>
						<div class="mt-4">
							<div class="textfield-with-actions flex">
								<div class="junipero field text-input labeled empty">
									<input id="password" name="password" required="" type="password" placeholder="Mot de passe" value="<?= $mdp?>">
								</div>
							</div>
						</div>
						<div class="flex justify-end mt-8 md:justify-between">
							<div class="flex-none">
								<a
									href="oublie_mdp.php"><span>Mot de passe oublié ?</span></a>
							</div>
						</div>
					</div>
				</div>
				<div class="signin-button">
					<div class="flex flex-col justify-between items-center mt-12 md:flex-row">
						<div class="flex-none w-full order-first md:w-auto md:order-none">
							<button type="submit" onclick="this.form.submit()"
								class="junipero button disabled flex w-full justify-center items-center primary md:w-auto">
								<span>Se connecter</span>
							</button>
						</div>
						<div class="flex-none w-full order-first md:w-auto md:order-none">
							<a href="index.php" class="junipero button disabled flex w-full justify-center items-center primary md:w-auto">
								<span>Retour sur le site</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php
require_once '../lib/footer.php';
?>
<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="js/plugins/bootstrap.bundle.min.js"></script>
</body>
</html>
