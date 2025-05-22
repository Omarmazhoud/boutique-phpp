<?php
session_start();

if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin123@gmail.com') {
    header("Location: acceuil.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

        switch ($action) {
            case 'add':
                if ($productId && isset($_POST['product_name']) && isset($_POST['product_price'])) {
                    $_SESSION['panier'][$productId] = [
                        'name' => $_POST['product_name'],
                        'price' => (float)$_POST['product_price']
                    ];
                }
                break;

            case 'remove':
                if (isset($_SESSION['panier'][$productId])) {
                    unset($_SESSION['panier'][$productId]);
                }
                break;

            case 'clear':
                unset($_SESSION['panier']);
                break;
        }
    }
    header("Location: panier.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon panier</title>
    <link rel="stylesheet" href="styles/style_panier.css">
</head>
<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Mon panier</h1>
    </header>
    <main>
        <?php if (empty($_SESSION['panier'])): ?>
            <p class="empty-cart">Votre panier est vide.</p>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['panier'] as $id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo number_format($item['price']); ?>DT</td>
                            <td>
                                <form method="POST" action="panier.php">
                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                    <button type="submit" name="action" value="remove" class="remove-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="total-price">
                <strong>Total général :
                    <?php
                    $total = 0;
                    foreach ($_SESSION['panier'] as $item) {
                        $total += $item['price'];
                    }
                    echo number_format($total);
                    ?>DT
                </strong>
            </p>
            <div class="command-buttons">
                <form method="POST" action="panier.php">
                    <button type="submit" name="action" value="clear" class="clear">Vider le panier</button>
                </form>
                <a href="commande.php">
                    <button type="button" class="order">Passer commande</button>
                </a>
            </div>
        <?php endif; ?>
        <br>
        <a href="catalogue.php" class="back-link">Retour au catalogue des produits</a>
    </main>
</body>
</html>
