<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com') {
    header("Location: catalogue.php?error=access_denied");
    exit();
}

// Suppression d'une commande
if (isset($_GET['supprimer'])) {
    $id_commande = intval($_GET['supprimer']);
    $sql_supprimer = "DELETE FROM commandes WHERE id = $id_commande";

    if ($connexion->query($sql_supprimer)) {
        $message = "La commande a été supprimée avec succès.";
    } else {
        $message = "Erreur lors de la suppression de la commande : " . $connexion->error;
    }
}

// Récupération des commandes
$sql_commandes = "SELECT * FROM commandes ORDER BY date_commande DESC";
$resultat = $connexion->query($sql_commandes);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <link rel="stylesheet" href="styles/style_liste_commande.css">
</head>

<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Liste des Commandes</h1>
    </header>

    <main>
        <?php if (isset($message)): ?>
            <p style="color: green;"><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($resultat && $resultat->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Montant Total (DT)</th>
                        <th>Date de Commande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($commande = $resultat->fetch_assoc()): ?>
                        <tr>
                            <td><?= $commande['id']; ?></td>
                            <td><?= htmlspecialchars($commande['nom']); ?></td>
                            <td><?= htmlspecialchars($commande['prenom']); ?></td>
                            <td><?= htmlspecialchars($commande['telephone']); ?></td>
                            <td><?= htmlspecialchars($commande['email']); ?></td>
                            <td><?= htmlspecialchars($commande['adresse']); ?></td>
                            <td><?= number_format($commande['montant_total'], 3); ?> DT</td>
                            <td><?= $commande['date_commande']; ?></td>
                            <td>
                                <!-- Bouton pour supprimer -->
                                <a href="liste_commandes.php?supprimer=<?= $commande['id']; ?>" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');" 
                                   class="btn btn-delete">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande enregistrée.</p>
        <?php endif; ?>
    </main>
</body>

</html>
