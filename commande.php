<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['email'])) {
    header("Location: connecter.php");
    exit();
}

if (empty($_SESSION['panier'])) {
    header("Location: panier.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $connexion->real_escape_string($_POST['nom']);
    $prenom = $connexion->real_escape_string($_POST['prenom']);
    $telephone = $connexion->real_escape_string($_POST['telephone']);
    $email = $_SESSION['email'];
    $adresse = $connexion->real_escape_string($_POST['adresse']);

    $montant_total = 7;
    foreach ($_SESSION['panier'] as $id => $item) {
        $montant_total += $item['price'];

        $sql_supprimer_produit = "DELETE FROM articles WHERE id = $id";
        $connexion->query($sql_supprimer_produit);
    }

    $sql_commande = "INSERT INTO commandes (nom, prenom, telephone, email, adresse, montant_total) 
                     VALUES ('$nom', '$prenom', '$telephone', '$email', '$adresse', $montant_total)";
    if ($connexion->query($sql_commande) === TRUE) {
        unset($_SESSION['panier']);

        echo "<script>
            alert('Votre commande a été passée avec succès ! Montant total : " . number_format($montant_total, 3) . "DT.');
            window.location.href = 'catalogue.php';
        </script>";
        exit();
    } else {
        $message = "Erreur lors de l'enregistrement de la commande : " . $connexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer une commande</title>
    <link rel="stylesheet" href="styles/style_commande.css">
</head>
<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Passer une commande</h1>
    </header><br>
    <main>
        <?php if (!empty($message)): ?>
            <p style="color: red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="commande.php" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>  

            <label for="prenom">Prénom :</label> 
            <input type="text" id="prenom" name="prenom" required>  

            <label for="telephone">Téléphone :</label> 
            <input type="text" id="telephone" name="telephone" required>  

            <label for="adresse">Adresse de livraison :</label> 
            <textarea id="adresse" name="adresse" rows="4" required></textarea>  

            <p><strong>Montant total avec frais de livraison :</strong> 
                <?php
                $montant_total = 7;
                foreach ($_SESSION['panier'] as $item) {
                    $montant_total += $item['price'];
                }
                echo number_format($montant_total, 3);
                ?>DT
            </p>

            <button type="submit">Passer commande</button>
        </form>
    </main>
</body>
</html>
