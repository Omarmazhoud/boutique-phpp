<?php
session_start();

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: connecter.php");
    exit();
}

include 'connexion.php';

$email = $_SESSION['email'];
$sql = "SELECT * FROM utilisateurs WHERE email='$email'";
$resultat = $connexion->query($sql);

if ($resultat && $resultat->num_rows > 0) {
    $utilisateur = $resultat->fetch_assoc();
} else {
    echo "Erreur : impossible de récupérer les informations de l'utilisateur.";
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $prenom = $connexion->real_escape_string($_POST['prenom']);
    $nom = $connexion->real_escape_string($_POST['nom']);
    $nouveau_motdepasse = !empty($_POST['motdepasse']) ? password_hash($_POST['motdepasse'], PASSWORD_DEFAULT) : $utilisateur['motdepasse'];

    $sql_update = "UPDATE utilisateurs SET prenom='$prenom', nom='$nom', motdepasse='$nouveau_motdepasse' WHERE email='$email'";
    if ($connexion->query($sql_update) === TRUE) {
        $message = "Vos informations ont été mises à jour avec succès.";
        $_SESSION['prenom'] = $prenom;
    } else {
        $message = "Erreur lors de la mise à jour : " . $connexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="styles/style_compte.css">
</head>
<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Mon Compte</h1>
    </header><br>
    <main>
        <h2>Vos informations personnelles</h2>
        <form method="post" action="compte.php">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required>
            
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required>
            
            <label for="motdepasse">Nouveau mot de passe :</label>
            <input type="password" id="motdepasse" name="motdepasse">
            
            <button type="submit" name="update">Mettre à jour</button>
        </form>
    </main>
</body>
</html>
