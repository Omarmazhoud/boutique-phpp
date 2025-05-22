<?php
include 'connexion.php';

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $connexion->real_escape_string($_POST['nom']);
    $prenom = $connexion->real_escape_string($_POST['prenom']);
    $email = $connexion->real_escape_string($_POST['email']);
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);

    $sql_verifier_email = "SELECT * FROM utilisateurs WHERE email='$email'";
    $resultat = $connexion->query($sql_verifier_email);

    if ($resultat->num_rows > 0) {
        $message = "L'adresse email est déjà utilisée.";
    } else {
        $sql_inserer_utilisateur = "INSERT INTO utilisateurs (nom, prenom, email, motdepasse) 
                                    VALUES ('$nom', '$prenom', '$email', '$motdepasse')";
        if ($connexion->query($sql_inserer_utilisateur) === TRUE) {
            header("Location: connecter.php");
            exit();
        } else {
            $message = "Erreur lors de l'inscription : " . $connexion->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles/style_login.css">
</head>
<body>
<header>
        <?php include 'menu.php'; ?>
        <h1>Inscription</h1>
    </header>
    <main>
        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="inscription.php" method="POST">
            <div class="input-group">
                <label for="nom"><i class="fas fa-user"></i>Nom :</label>
                <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
            </div>

            <div class="input-group">
                <label for="prenom"><i class="fas fa-user"></i>Prénom :</label>
                <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
            </div>

            <div class="input-group">
                <label for="email"><i class="fas fa-envelope"></i>Email :</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
            </div>

            <div class="input-group">
                <label for="motdepasse"><i class="fas fa-lock"></i>Mot de passe :</label>
                <input type="password" id="motdepasse" name="motdepasse" placeholder="Choisissez un mot de passe" required>
            </div>

            <button type="submit">S'inscrire</button>
        </form>
        <p>Vous avez déjà un compte ? <a href="connecter.php">Connectez-vous</a>.</p>
    </main>
</body>
</html>
