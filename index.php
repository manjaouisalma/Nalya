<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';
$pageTitle = 'Nalya — L’élégance au quotidien';
$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.is_active=1 ORDER BY p.created_at DESC LIMIT 4");
$products = $stmt->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="hero-copy">
    <p class="eyebrow">LA BEAUTÉ DES DÉTAILS</p>
    <h1>Un éclat subtil.<br><em>Votre signature.</em></h1>
    <p>Des bijoux fantaisie délicats, choisis pour accompagner votre style chaque jour.</p>
    <a class="btn btn-dark" href="products.php">Découvrir la collection <span>↗</span></a>
  </div>
  <div class="hero-art">
    <img src="assets/images/nalya-hero.jpg" alt="Bijoux Nalya" class="hero-image">
    
</div>
</section>
<section class="section">
  <div class="section-heading">
  <div><p class="eyebrow">TROUVEZ VOTRE STYLE</p><h2>Nos catégories</h2></div><a class="text-link" href="products.php">Tout découvrir ↗</a></div>
  <div class="category-grid">
    <a class="category-tile tile-ring" href="products.php?category=Bagues"><span>01 / Collection</span><strong>Bagues</strong></a>
    <a class="category-tile tile-necklace" href="products.php?category=Colliers"><span>02 / Collection</span><strong>Colliers</strong></a>
    <a class="category-tile tile-bracelet" href="products.php?category=Bracelets"><span>03 / Collection</span><strong>Bracelets</strong></a>
    <a class="category-tile tile-earring" href="products.php?category=Boucles%20d%27oreilles"><span>04 / Collection</span><strong>Boucles d’oreilles</strong></a>
  </div>
</section>
<section class="section section-soft">
  <div class="section-heading"><div><p class="eyebrow">SÉLECTION NALYA</p><h2>Les pièces du moment</h2></div><a class="text-link" href="products.php">Voir toute la collection ↗</a></div>
  <?php if ($products): ?><div class="product-grid">
    <?php foreach ($products as $product): ?>
      <article class="product-card">
        <button class="favorite-button" data-favorite="<?= (int)$product['id'] ?>" aria-label="Ajouter aux favoris">♡</button>
        <a class="product-photo" href="product-details.php?id=<?= (int)$product['id'] ?>"><img src="<?= e(image_url($product['image'])) ?>" alt="<?= e($product['name']) ?>"></a>
        <p class="product-category"><?= e($product['category_name'] ?? 'Collection Nalya') ?></p>
        <h3><a href="product-details.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h3>
        <div class="product-card-bottom"><strong><?= money($product['price']) ?></strong><button class="add-mini" data-add-cart="<?= (int)$product['id'] ?>" data-name="<?= e($product['name']) ?>" data-price="<?= e($product['price']) ?>" data-stock="<?= (int)$product['stock'] ?>" data-image="<?= e(image_url($product['image'])) ?>">＋</button></div>
      </article>
    <?php endforeach; ?>
  </div><?php else: ?><p class="empty-state">Les nouveautés arrivent bientôt. Ajoutez des produits depuis l’administration.</p><?php endif; ?>
</section>
<section class="story-section">
  <div class="story-shape">
    <img src="assets/images/image1.jpg" alt="Bijoux Nalya">
  </div>
  <div class="story-copy">
    <p class="eyebrow">L’UNIVERS NALYA</p>
    <h2>La simplicité a<br><em>son propre éclat.</em></h2>
    <p>Nalya célèbre les détails : une pièce discrète, un cadeau attentionné, un bijou qui devient votre favori. Une collection pensée pour se porter et se partager.</p>
    <a class="text-link" href="products.php">Explorer les bijoux ↗</a>
  </div>
</section>

<section class="benefits">
  <div><span>✧</span><h3>Choix délicat</h3><p>Des pièces faciles à associer.</p></div>
  <div><span>♡</span><h3>Paiement à la livraison</h3><p>Payez au moment de recevoir votre commande.</p></div>
  <div><span>◇</span><h3>Commande simple</h3><p>Un parcours clair, sans complication.</p></div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
