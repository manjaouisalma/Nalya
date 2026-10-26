<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';
$pageTitle = 'La collection — Nalya';
$categories = $pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
$categoryName = trim($_GET['category'] ?? '');
$categoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
$search = trim($_GET['q'] ?? '');
$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.is_active=1";
$params = [];
if ($categoryId) { $sql .= " AND p.category_id=?"; $params[] = $categoryId; }
elseif ($categoryName !== '') { $sql .= " AND c.name=?"; $params[] = $categoryName; }
if ($search !== '') { $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
$sort = $_GET['sort'] ?? 'recent';
$sql .= match ($sort) { 'price_asc' => " ORDER BY p.price ASC", 'price_desc' => " ORDER BY p.price DESC", 'name' => " ORDER BY p.name ASC", default => " ORDER BY p.created_at DESC" };
$stmt = $pdo->prepare($sql); $stmt->execute($params); $products = $stmt->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner"><p class="eyebrow">LA COLLECTION NALYA</p><h1>Des bijoux qui vous ressemblent.</h1><p>Des détails subtils, pour tous les jours et toutes les occasions.</p></section>
<section class="section catalog-section">
  <form class="filters" method="get">
    <label>Rechercher<input type="search" name="q" value="<?= e($search) ?>" placeholder="Nom d’un bijou…"></label>
    <label>Catégorie<select name="category_id"><option value="">Toutes les catégories</option><?php foreach($categories as $cat): ?><option value="<?= (int)$cat['id'] ?>" <?= $categoryId===(int)$cat['id']?'selected':'' ?>><?= e($cat['name']) ?></option><?php endforeach; ?></select></label>
    <label>Trier par<select name="sort"><option value="recent" <?= $sort==='recent'?'selected':'' ?>>Nouveautés</option><option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Prix croissant</option><option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Prix décroissant</option></select></label>
    <button class="btn btn-dark" type="submit">Appliquer</button>
  </form>
  <p class="result-count"><?= count($products) ?> article(s)</p>
  <?php if ($products): ?><div class="product-grid">
    <?php foreach ($products as $product): ?><article class="product-card">
      <button class="favorite-button" data-favorite="<?= (int)$product['id'] ?>" aria-label="Ajouter aux favoris">♡</button>
      <a class="product-photo" href="product-details.php?id=<?= (int)$product['id'] ?>"><img src="<?= e(image_url($product['image'])) ?>" alt="<?= e($product['name']) ?>"></a>
      <p class="product-category"><?= e($product['category_name'] ?? 'Collection Nalya') ?></p><h3><a href="product-details.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h3>
      <div class="product-card-bottom"><strong><?= money($product['price']) ?></strong><button class="add-mini" data-add-cart="<?= (int)$product['id'] ?>" data-name="<?= e($product['name']) ?>" data-price="<?= e($product['price']) ?>" data-stock="<?= (int)$product['stock'] ?>" data-image="<?= e(image_url($product['image'])) ?>">＋</button></div>
    </article><?php endforeach; ?>
  </div><?php else: ?><div class="empty-state"><h2>Aucun bijou trouvé</h2><p>Essayez une autre recherche ou catégorie.</p><a class="btn btn-outline" href="products.php">Réinitialiser les filtres</a></div><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
