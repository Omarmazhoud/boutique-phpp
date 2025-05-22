<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com') {
    echo "<p>Accès refusé. Seul l'administrateur peut ajouter des produits.</p>";
    echo '<a href="catalogue.php">Retour au catalogue</a>';
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marque = $connexion->real_escape_string($_POST['marque']);
    $modele = $connexion->real_escape_string($_POST['modele']);
    $description = $connexion->real_escape_string($_POST['description']);
    $prix = floatval($_POST['prix']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dossierImages = 'images/';
        $nomFichier = basename($_FILES['image']['name']);
        $cheminImage = $dossierImages . $nomFichier;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $cheminImage)) {
            $sql = "INSERT INTO articles (marque, modele, description, prix, image) 
                    VALUES ('$marque', '$modele', '$description', $prix, '$nomFichier')";
            if ($connexion->query($sql)) {
                $message = "Produit ajouté avec succès.";
            } else {
                $message = "Erreur : " . $connexion->error;
            }
        } else {
            $message = "Erreur lors du téléchargement de l'image.";
        }
    } else {
        $message = "Veuillez sélectionner une image.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit</title>
    <link rel="stylesheet" href="styles/style_ajou_prod.css">
</head>
<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Ajouter un Produit</h1>
    </header>
    <main>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="marque">Marque :</label>
            <input type="text" id="marque" name="marque" required>

            <label for="modele">Modèle :</label>
            <input type="text" id="modele" name="modele" required>

            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="prix">Prix :</label>
            <input type="number" id="prix" name="prix" required>

            <label for="image">Image :</label>
            <input type="file" id="image" name="image" required>

            <button type="submit">Ajouter</button>
        </form>
    </main>
</body>
</html>
