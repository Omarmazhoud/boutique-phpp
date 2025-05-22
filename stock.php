<?php
session_start();
include 'connexion.php';

// Vérification si l'utilisateur est administrateur
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com') {
    echo "<p>Accès refusé. Seul l'administrateur peut accéder à cette page.</p>";
    echo '<a href="catalogue.php">Retour au catalogue</a>';
    exit();
}

$message = "";

// Suppression d'un article
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $sql_supprimer = "DELETE FROM articles WHERE id = $id";
    if ($connexion->query($sql_supprimer)) {
        $message = "L'article a été supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression de l'article : " . $connexion->error;
    }
}

// Modification d'un article
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
    $id = intval($_POST['id']);
    $modele = $connexion->real_escape_string($_POST['modele']);
    $marque = $connexion->real_escape_string($_POST['marque']);
    $description = $connexion->real_escape_string($_POST['description']);
    $prix = floatval($_POST['prix']);

    $sql_modifier = "UPDATE articles SET modele='$modele', marque='$marque', description='$description', prix=$prix WHERE id=$id";
    if ($connexion->query($sql_modifier)) {
        $message = "L'article a été modifié avec succès.";
    } else {
        $message = "Erreur lors de la modification : " . $connexion->error;
    }
}

// Récupération des articles
$sql_articles = "SELECT * FROM articles ORDER BY id DESC";
$resultat = $connexion->query($sql_articles);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>
    <link rel="stylesheet" href="styles/style_stock.css">
    <script>
        function openModal(id, modele, marque, description, prix) {
            document.getElementById('id').value = id;
            document.getElementById('modele').value = modele;
            document.getElementById('marque').value = marque;
            document.getElementById('description').value = description;
            document.getElementById('prix').value = prix;
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Gestion des Stocks</h1>
    </header>
    <main>
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($resultat && $resultat->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Modele</th>
                        <th>Marque</th>
                        <th>Description</th>
                        <th>Prix (DT)</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($article = $resultat->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td><?php echo htmlspecialchars($article['modele']); ?></td>
                            <td><?php echo htmlspecialchars($article['marque']); ?></td>
                            <td><?php echo htmlspecialchars($article['description']); ?></td>
                            <td><?php echo number_format($article['prix'], 3); ?>DT</td>
                            <td>
                                <img src="images/<?php echo htmlspecialchars($article['image']); ?>" alt="Image" style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <button onclick="openModal(<?php echo $article['id']; ?>, '<?php echo addslashes($article['modele']); ?>', '<?php echo addslashes($article['marque']); ?>', '<?php echo addslashes($article['description']); ?>', <?php echo $article['prix']; ?>)" class="btn btn-edit">Modifier</button>
                                <a href="stock.php?supprimer=<?php echo $article['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');" class="btn btn-delete">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun article disponible en stock.</p>
        <?php endif; ?>

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Modifier l'article</h2>
                <form method="post" action="stock.php">
                    <input type="hidden" id="id" name="id">
                    
                    <label for="modele">Modele :</label>
                    <input type="text" id="modele" name="modele" required>
                    
                    <label for="marque">Marque :</label>
                    <input type="text" id="marque" name="marque" required>

                    <label for="description">Description :</label>
                    <textarea id="description" name="description" rows="4" required></textarea>

                    <label for="prix">Prix (DT) :</label>
                    <input type="number" step="0.01" id="prix" name="prix" required>

                    <button type="submit" name="modifier" class="btn btn-edit">Enregistrer</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
