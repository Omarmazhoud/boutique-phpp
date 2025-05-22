<?php
session_start();
include 'connexion.php';

$sql = "SELECT * FROM articles";
$resultat = $connexion->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Produits</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style_catalogue.css">
</head>
<body>
    <header>
        <?php include 'menu.php'; ?>
        <h1>Catalogue des Produits</h1>
    </header>
    <main>
        <div class="product-grid">
            <?php if ($resultat && $resultat->num_rows > 0): ?>
                <?php while ($produit = $resultat->fetch_assoc()): ?>
                    <div class="produit">
                        <h2><?php echo htmlspecialchars($produit['modele']); ?></h2>
                        <p><strong>Prix :</strong> <?php echo number_format($produit['prix']); ?> DT</p>
                        <?php if (!empty($produit['image'])): ?>
                            <img src="images/<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['modele']); ?>">
                        <?php endif; ?><br>
                        <button class="detail-btn" onclick="showDetails(<?php echo htmlspecialchars(json_encode($produit)); ?>)"><i class="fas fa-info-circle"></i>  Détail</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun produit disponible.</p>
            <?php endif; ?>
        </div>

        <div id="detailModal" class="modal">
            <div class="modal-content">
                <button class="close-btn" onclick="hideDetails()">&times;</button>
                <div class="modal-header">
                    <h2 id="modal-title"></h2>
                </div>
                <div class="modal-body">
                    <img id="modal-image" src="" alt="Product Image" class="modal-image" />
                    <div class="modal-info">
                        <p><strong>Prix :</strong> <span id="modal-price"></span></p>
                        <p><strong>Description :</strong> <span id="modal-description"></span></p>
                        <p><strong>Marque :</strong> <span id="modal-marque"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="add-to-cart-form" method="POST" action="panier.php">
                        <input type="hidden" name="product_id" id="product_id">
                        <input type="hidden" name="product_name" id="product_name">
                        <input type="hidden" name="product_price" id="product_price">
                        <button type="submit" name="action" value="add" class="add-to-cart-btn"><i class="fas fa-cart-plus"></i>  Ajouter au panier</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        function showDetails(produit) {
            document.getElementById('modal-title').textContent = produit.modele;
            document.getElementById('modal-price').textContent = produit.prix + " DT";
            document.getElementById('modal-description').textContent = produit.description;
            document.getElementById('modal-marque').textContent = produit.marque;
            document.getElementById('modal-image').src = "images/" + produit.image;

            document.getElementById('product_id').value = produit.id;
            document.getElementById('product_name').value = produit.modele;
            document.getElementById('product_price').value = produit.prix;

            document.getElementById('detailModal').style.display = "flex"; 
        }

        function hideDetails() {
            document.getElementById('detailModal').style.display = "none";
        }
    </script>
</body>
</html>
