<?php
require __DIR__.'/_init.php';if(is_post()){verify_csrf();$id=(int)($_POST['id']??0);$stmt=$pdo->prepare("UPDATE contact_messages SET is_handled=1 WHERE id=?");$stmt->execute([$id]);flash('success','Message marqué comme traité.');redirect('messages.php');}$messages=$pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();$pageTitle='Messages — Nalya';
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
    <div class="admin-content"><div class="admin-heading"><div><p class="eyebrow">CONTACT</p><h1>Messages reçus</h1></div></div><div class="admin-panel"><?php if(!$messages): ?><p>Aucun message reçu.</p><?php endif; ?><?php foreach($messages as $m): ?><article class="message-card"><div class="section-heading"><h2><?= e($m['subject']?:'Sans sujet') ?></h2><span class="status"><?= $m['is_handled']?'Traité':'À traiter' ?></span></div><p><strong><?= e($m['name']) ?></strong> · <a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></p><p><?= nl2br(e($m['message'])) ?></p><?php if(!$m['is_handled']): ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button class="btn btn-outline">Marquer comme traité</button></form><?php endif; ?></article><?php endforeach; ?></div></div></section>

