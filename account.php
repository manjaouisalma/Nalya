<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php'; require_login();
$pageTitle='Mon compte — Nalya'; $user=current_user();
$stmt=$pdo->prepare("SELECT COUNT(*) AS total FROM orders WHERE user_id=?"); $stmt->execute([$user['id']]); $orderCount=(int)$stmt->fetch()['total'];
require __DIR__.'/includes/header.php';
?>
<section class="page-banner compact-banner"><p class="eyebrow">VOTRE ESPACE</p><h1>Bonjour, <?= e($user['name']) ?>.</h1><p>Retrouvez vos informations et vos commandes.</p></section>
<section class="section account-grid"><article class="info-card"><p class="eyebrow">MES INFORMATIONS</p><h2>Mon profil</h2><p><strong>Nom :</strong> <?= e($user['name']) ?></p><p><strong>E-mail :</strong> <?= e($user['email']) ?></p><a class="text-link" href="orders.php">Consulter mes commandes (<?= $orderCount ?>) ↗</a></article><article class="info-card"><p class="eyebrow">VOTRE SÉLECTION</p><h2>Vos favoris</h2><p>Retrouvez les bijoux que vous avez enregistrés sur cet appareil.</p><a class="btn btn-outline" href="favorites.php">Voir mes favoris</a></article></section>
<?php require __DIR__.'/includes/footer.php'; ?>
