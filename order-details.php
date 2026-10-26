<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php'; require_login();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); $stmt=$pdo->prepare("SELECT * FROM orders WHERE id=? AND user_id=?"); $stmt->execute([$id ?: 0,current_user()['id']]); $order=$stmt->fetch();
if(!$order){http_response_code(404);exit('Commande introuvable.');}
$stmt=$pdo->prepare("SELECT product_name,quantity,unit_price FROM order_items WHERE order_id=?");$stmt->execute([$order['id']]);$items=$stmt->fetchAll();
$pageTitle='Détail de commande — Nalya';require __DIR__.'/includes/header.php';
?>
<section class="page-banner compact-banner"><p class="eyebrow">COMMANDE</p><h1><?= e($order['order_number']) ?></h1><p><?= e($order['status']) ?> · <?= e(date('d/m/Y',strtotime($order['created_at']))) ?></p></section><section class="section"><div class="table-wrap"><table><thead><tr><th>Article</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr></thead><tbody><?php foreach($items as $item): ?><tr><td><?= e($item['product_name']) ?></td><td><?= (int)$item['quantity'] ?></td><td><?= money($item['unit_price']) ?></td><td><?= money($item['unit_price']*$item['quantity']) ?></td></tr><?php endforeach; ?></tbody></table></div><p class="order-total">Total : <strong><?= money($order['total']) ?></strong></p><p>Livraison : <?= e($order['address']) ?>, <?= e($order['city']) ?></p><a class="text-link" href="orders.php">← Toutes mes commandes</a></section>
<?php require __DIR__.'/includes/footer.php'; ?>
