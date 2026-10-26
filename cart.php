<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';
$pageTitle='Mon panier — Nalya';
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner compact-banner"><p class="eyebrow">VOTRE SÉLECTION</p><h1>Mon panier</h1><p>Vérifiez vos pièces avant de continuer.</p></section>
<section class="section cart-section"><div id="cart-page" data-products-endpoint="api/products.php"><div class="loading-state">Chargement du panier…</div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
