<?php
require __DIR__.'/_init.php';
$pageTitle='Administration — Nalya';
$stats=[];
$stats['products']=(int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$stats['active']=(int)$pdo->query("SELECT COUNT(*) FROM products WHERE is_active=1")->fetchColumn();
$stats['orders']=(int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$stats['pending']=(int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status='En attente'")->fetchColumn();
$stats['revenue']=(float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status IN ('Confirmée','En préparation','Expédiée','Livrée')")->fetchColumn();
$best=$pdo->query("SELECT product_name,SUM(quantity) qty FROM order_items GROUP BY product_id,product_name ORDER BY qty DESC LIMIT 5")->fetchAll();
require __DIR__.'/../includes/header.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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

    <div class="admin-content"><div class="admin-heading">
        <div>
            <p class="eyebrow">VUE D’ENSEMBLE</p>
            <h1>Bonjour, <?= e(current_user()['name']) ?>.</h1>
        </div>
        <span class="status">Administration</span>
    </div>
    <div class="stats-grid">
        <article class="stat-card">
            <span>Produits</span>
            <strong><?= $stats['products'] ?></strong>
            <small><?= $stats['active'] ?> actifs</small>
        </article>
        <article class="stat-card">
            <span>Commandes</span>
            <strong><?= $stats['orders'] ?></strong>
            <small>Toutes périodes</small>
        </article>
            <article class="stat-card">
                <span>En attente</span>
                <strong><?= $stats['pending'] ?></strong>
                <small>À traiter</small>
            </article>
            <article class="stat-card">
                <span>Chiffre d’affaires</span>
                <strong><?= money($stats['revenue']) ?></strong>
                <small>Commandes confirmées à livrées</small>
            </article>
        </div>
        <section class="admin-panel">
            <div class="section-heading">
                <h2>Produits les plus vendus</h2>
                <a class="text-link" href="orders.php">Voir les commandes ↗</a>
            </div><?php if($best): ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité vendue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($best as $item): ?>
                                <tr>
                                    <td><?= e($item['product_name']) ?></td>
                                    <td><?= (int)$item['qty'] ?></td>
                                </tr><?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?><p>Les statistiques des ventes apparaîtront après les premières commandes.</p><?php endif; ?></section>
                    </div>
                </section>

