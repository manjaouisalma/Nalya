<?php
require __DIR__.'/_init.php';
$customers=$pdo->query("SELECT u.id,u.name,u.email,u.phone,u.created_at,COUNT(o.id) order_count,COALESCE(SUM(o.total),0) total_spent FROM users u LEFT JOIN orders o ON o.user_id=u.id WHERE u.role='customer' GROUP BY u.id ORDER BY u.created_at DESC")->fetchAll();$pageTitle='Clients — Nalya';require __DIR__.'/../includes/header.php';
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
    <div class="admin-content"><div class="admin-heading"><div><p class="eyebrow">CLIENTÈLE</p><h1>Clients</h1></div></div><div class="admin-panel"><div class="table-wrap"><table><thead><tr><th>Nom</th><th>E-mail</th><th>Téléphone</th><th>Commandes</th><th>Total commandé</th><th>Inscription</th></tr></thead><tbody><?php foreach($customers as $c): ?><tr><td><?= e($c['name']) ?></td><td><?= e($c['email']) ?></td><td><?= e($c['phone']?:'—') ?></td><td><?= (int)$c['order_count'] ?></td><td><?= money($c['total_spent']) ?></td><td><?= e(date('d/m/Y',strtotime($c['created_at']))) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div></section>

