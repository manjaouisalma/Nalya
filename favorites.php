<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';
$pageTitle='Mes favoris — Nalya';
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner compact-banner"><p class="eyebrow">VOS PIÈCES PRÉFÉRÉES</p><h1>Mes favoris</h1><p>Gardez vos coups de cœur à portée de main.</p></section>
<section class="section"><div id="favorites-page" data-products-endpoint="api/products.php"><div class="loading-state">Chargement des favoris…</div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
