<?php
require __DIR__.'/_init.php';
$statuses=['En attente','Confirmée','En préparation','Expédiée','Livrée','Annulée'];$errors=[];
if(is_post()){
    verify_csrf();$id=(int)($_POST['id']??0);$status=$_POST['status']??'';
    if(!in_array($status,$statuses,true))$errors[]='Statut invalide.';
    else{$stmt=$pdo->prepare("SELECT status FROM orders WHERE id=?");$stmt->execute([$id]);$old=$stmt->fetch();if(!$old)$errors[]='Commande introuvable.';else{try{$pdo->beginTransaction();$pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status,$id]);$pdo->commit();flash('success','Statut de la commande mis à jour.');redirect('orders.php');}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$errors[]='Mise à jour impossible.';}}}}
$orders=$pdo->query("SELECT o.*,u.email FROM orders o LEFT JOIN users u ON u.id=o.user_id ORDER BY o.created_at DESC")->fetchAll()
;$pageTitle='Commandes — Nalya';
require __DIR__.'/../includes/header.php';
?>
<section class="admin-wrap">
    <aside class="admin-sidebar">
    <h2>Nalya<span>.</span></h2>
    <nav class="admin-nav">
        <a class="active" href="index.php">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Vue d’ensemble</span>
        </a>

        <a href="products.php">
            <i class="fa-solid fa-gem"></i>
            <span>Produits</span>
        </a>

        <a href="categories.php">
            <i class="fa-solid fa-layer-group"></i>
            <span>Catégories</span>
        </a>

        <a href="orders.php">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Commandes</span>
        </a>

        <a href="customers.php">
            <i class="fa-solid fa-users"></i>
            <span>Clients</span>
        </a>

        <a href="messages.php">
            <i class="fa-regular fa-envelope"></i>
            <span>Messages</span>
        </a>

    </nav>

    <div class="admin-sidebar-bottom">
        <form method="post" action="../logout.php">
            <input type="hidden" name="csrf_token"
                   value="<?= e(csrf_token()) ?>">

            <button type="submit" class="admin-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>
    <div class="admin-content"><div class="admin-heading"><div><p class="eyebrow">SUIVI DES VENTES</p><h1>Commandes</h1></div></div><?php foreach($errors as $error): ?><div class="form-error"><?= e($error) ?></div><?php endforeach; ?><div class="admin-panel"><div class="table-wrap"><table><thead><tr><th>N° commande</th><th>Client</th><th>Date</th><th>Total</th><th>Statut</th><th>Modifier</th></tr></thead><tbody><?php foreach($orders as $o): ?><tr><td><a href="order-view.php?id=<?= (int)$o['id'] ?>"><?= e($o['order_number']) ?></a></td><td><?= e($o['customer_name']) ?><br><small><?= e($o['email']) ?></small></td><td><?= e(date('d/m/Y',strtotime($o['created_at']))) ?></td><td><?= money($o['total']) ?></td><td><?= e($o['status']) ?></td><td><form method="post" class="status-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$o['id'] ?>"><select name="status"><?php foreach($statuses as $s): ?><option <?= $s===$o['status']?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="table-action">OK</button></form></td></tr><?php endforeach; ?></tbody></table></div></div></div></section>

