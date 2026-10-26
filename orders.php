<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php'; require_login();
$pageTitle='Mes commandes — Nalya';
$stmt=$pdo->prepare("SELECT id,order_number,created_at,total,status FROM orders WHERE user_id=? ORDER BY created_at DESC"); $stmt->execute([current_user()['id']]); $orders=$stmt->fetchAll();
require __DIR__.'/includes/header.php';
?>
<section class="page-banner compact-banner"><p class="eyebrow">VOTRE HISTORIQUE</p><h1>Mes commandes</h1><p>Suivez vos commandes Nalya.</p></section>
<section class="section"><?php if(!$orders): ?><div class="empty-state"><h2>Aucune commande pour le moment</h2><a class="btn btn-dark" href="products.php">Découvrir la collection</a></div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Commande</th><th>Date</th><th>Total</th><th>Statut</th><th>Détail</th></tr></thead><tbody><?php foreach($orders as $order): ?><tr><td><?= e($order['order_number']) ?></td><td><?= e(date('d/m/Y',strtotime($order['created_at']))) ?></td><td><?= money($order['total']) ?></td><td><span class="status"><?= e($order['status']) ?></span></td><td><a href="order-details.php?id=<?= (int)$order['id'] ?>">Voir</a></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<?php require __DIR__.'/includes/footer.php'; ?>
