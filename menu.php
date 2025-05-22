<link rel="stylesheet" href="styles/style_menu.css">
<nav class="navbar">
    <a href="catalogue.php" class="logo">Super Frippe</a>
    <ul class="navbar-list">
        <li><a href="catalogue.php">Catalogue des Produits</a></li>

        <?php if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com'): ?>
            <li><a href="panier.php">Panier</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin123@gmail.com'): ?>
            <li><a href="ajout_element.php" class="admin-link">Ajouter un Produit</a></li>
            <li><a href="liste_commandes.php" class="admin-link">Liste des Commandes</a></li>
            <li><a href="stock.php" class="admin-link">Stock</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['email'])): ?>
            <li><a href="compte.php">Compte</a></li>
            <li><a href="deconnexion.php">Se déconnecter</a></li>
        <?php else: ?>
            <li><a href="connecter.php">Se connecter</a></li>
            <li><a href="inscription.php">S'inscrire</a></li>
        <?php endif; ?>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>
