<?php
session_start();
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $connexion->real_escape_string($_POST['email']);
    $motdepasse = $_POST['motdepasse'];

    $sql = "SELECT * FROM utilisateurs WHERE email='$email'";
    $resultat = $connexion->query($sql);

    if ($resultat->num_rows > 0) {
        $utilisateur = $resultat->fetch_assoc();
        if (password_verify($motdepasse, $utilisateur['motdepasse'])) {
            $_SESSION['email'] = $utilisateur['email'];
            header("Location: catalogue.php");
            exit();
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Email non trouvé.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles/style_login.css">
</head>
<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Connexion</h1>
    </header>
    <main>
        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="connecter.php" method="POST">
            <div class="input-group">
                <label for="email"><i class="fas fa-envelope"></i>Email :</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
            <div class="input-group">
                <label for="motdepasse"><i class="fas fa-lock"></i>Mot de passe :</label>
                <input type="password" id="motdepasse" name="motdepasse" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a>.</p>
    </main>
</body>
</html>