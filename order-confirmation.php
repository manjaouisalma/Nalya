<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php'; require_login();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); $stmt=$pdo->prepare("SELECT * FROM orders WHERE id=? AND user_id=?"); $stmt->execute([$id ?: 0,current_user()['id']]); $order=$stmt->fetch();
if(!$order || (int)($_SESSION['last_order_id']??0)!==(int)$order['id']) { http_response_code(404); exit('Commande introuvable.'); }
$itemsStmt=$pdo->prepare("SELECT product_name,quantity,unit_price FROM order_items WHERE order_id=?"); $itemsStmt->execute([$order['id']]); $items=$itemsStmt->fetchAll();
$pageTitle='Commande confirmée — Nalya'; require __DIR__.'/includes/header.php';
?>
<section class="section confirmation"><div class="confirmation-icon">✓</div><p class="eyebrow">MERCI POUR VOTRE COMMANDE</p><h1>Votre commande est enregistrée.</h1><p>Votre numéro de commande est <strong><?= e($order['order_number']) ?></strong>.</p><p>Statut : <span class="status"><?= e($order['status']) ?></span></p><div class="confirmation-summary"><h2>Récapitulatif</h2><?php foreach($items as $item): ?><div class="summary-row"><span><?= e($item['product_name']) ?> × <?= (int)$item['quantity'] ?></span><strong><?= money($item['unit_price']*$item['quantity']) ?></strong></div><?php endforeach; ?><div class="summary-row summary-total"><span>Total à régler à la livraison</span><strong><?= money($order['total']) ?></strong></div><p><?= e($order['address']) ?>, <?= e($order['city']) ?></p></div><a class="btn btn-dark" href="orders.php">Voir mes commandes</a></section>
<script>localStorage.removeItem('nalya_cart');document.querySelectorAll('[data-cart-count]').forEach(el=>el.textContent='0');</script>
<?php require __DIR__.'/includes/footer.php'; ?>
