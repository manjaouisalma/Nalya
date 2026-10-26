<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.is_active=1");
$stmt->execute([$id ?: 0]); $product = $stmt->fetch();
if (!$product) { http_response_code(404); $pageTitle='Produit introuvable'; require __DIR__.'/includes/header.php'; echo '<section class="empty-state"><h1>Produit introuvable</h1><a href="products.php">Retour à la collection</a></section>'; require __DIR__.'/includes/footer.php'; exit; }
$pageTitle = $product['name'].' — Nalya';
require __DIR__ . '/includes/header.php';
?>
<section class="section detail-layout">
  <div class="detail-image"><img src="<?= e(image_url($product['image'])) ?>" alt="<?= e($product['name']) ?>"></div>
  <div class="detail-copy"><p class="eyebrow"><?= e($product['category_name'] ?? 'COLLECTION NALYA') ?></p><h1><?= e($product['name']) ?></h1><p class="detail-price"><?= money($product['price']) ?></p><p><?= nl2br(e($product['description'])) ?></p>
    <p class="<?= $product['stock'] > 0 ? 'stock-ok' : 'stock-out' ?>"><?= $product['stock'] > 0 ? 'Disponible · '.$product['stock'].' en stock' : 'Actuellement indisponible' ?></p>
    <?php if ((int)$product['stock'] > 0): ?><label class="quantity-label">Quantité<input id="detail-quantity" type="number" min="1" max="<?= (int)$product['stock'] ?>" value="1"></label><?php endif; ?>
    <div class="detail-actions"><button class="btn btn-dark" <?= $product['stock'] < 1 ? 'disabled' : '' ?> data-add-cart="<?= (int)$product['id'] ?>" data-name="<?= e($product['name']) ?>" data-price="<?= e($product['price']) ?>" data-stock="<?= (int)$product['stock'] ?>" data-image="<?= e(image_url($product['image'])) ?>" data-use-quantity="detail-quantity">Ajouter au panier</button><button class="btn btn-outline" data-favorite="<?= (int)$product['id'] ?>">♡ Favoris</button></div>
    <p class="detail-note">Paiement à la livraison · Commande simple et sécurisée</p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
