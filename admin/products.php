<?php
require __DIR__.'/_init.php';
if(is_post()){verify_csrf();
$id=(int)($_POST['id']??0);
$action=$_POST['action']??'';if($action==='toggle'){$stmt=$pdo->prepare("UPDATE products SET is_active=IF(is_active=1,0,1) WHERE id=?");$stmt->execute([$id]);flash('success','Statut du produit mis à jour.');}elseif($action==='delete'){$stmt=$pdo->prepare("DELETE FROM products WHERE id=?");try{$stmt->execute([$id]);flash('success','Produit supprimé.');}catch(PDOException $e){flash('error','Ce produit est lié à une commande et ne peut pas être supprimé. Désactivez-le plutôt.');}}redirect('products.php');}
$pageTitle='Produits — Administration';
$products=$pdo->query("SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.created_at DESC")->fetchAll();
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
    <div class="admin-content">
        <div class="admin-heading">

            <a class="btn btn-dark" href="product-form.php">＋ Ajouter un produit</a>
        </div>
        <div class="admin-panel">
            <div class="table-wrap">
                <table><thead><tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                    <tr>
                        <td>
                            <div class="table-product">
                                <img src="../<?= e($p['image'] ?: 'assets/images/product-placeholder.svg') ?>" alt="">
                                <span><?= e($p['name']) ?></span>
                            </div>
                        </td>
                        <td><?= e($p['category_name']??'—') ?></td>
                        <td><?= money($p['price']) ?></td>
                        <td><?= (int)$p['stock'] ?></td>
                        <td><?= $p['is_active']?'Actif':'Inactif' ?></td>
                        <td class="actions">
                            <a href="product-form.php?id=<?= (int)$p['id'] ?>">Modifier</a>
                            <form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <input type="hidden" name="action" value="toggle">
                            <button class="table-action" type="submit">
                                <?= $p['is_active']?'Désactiver':'Activer' ?>
                            </button>
                        </form>
                        <form method="post" onsubmit="return confirm('Supprimer ce produit ?')">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="table-action danger" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</section>

