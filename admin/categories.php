<?php
require __DIR__.'/_init.php';$errors=[];
if(is_post()){verify_csrf();$action=$_POST['action']??'';$id=(int)($_POST['id']??0);$name=trim($_POST['name']??'');if($action==='delete'){try{$stmt=$pdo->prepare("DELETE FROM categories WHERE id=?");$stmt->execute([$id]);flash('success','Catégorie supprimée.');}catch(PDOException $e){flash('error','Cette catégorie contient des produits. Réaffectez-les avant suppression.');}}else{if(mb_strlen($name)<2)$errors[]='Le nom doit contenir au moins 2 caractères.';else{try{if($action==='edit'){$stmt=$pdo->prepare("UPDATE categories SET name=? WHERE id=?");$stmt->execute([$name,$id]);}else{$stmt=$pdo->prepare("INSERT INTO categories(name) VALUES(?)");$stmt->execute([$name]);}flash('success','Catégorie enregistrée.');redirect('categories.php');}catch(PDOException $e){$errors[]='Cette catégorie existe déjà.';}}}}
$categories=$pdo->query("SELECT c.*,COUNT(p.id) product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name")->fetchAll();$pageTitle='Catégories — Nalya';require __DIR__.'/../includes/header.php';
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
            <div>
                <p class="eyebrow">ORGANISATION</p>
                <h1>Catégories</h1>
            </div>
        </div>
        <div class="admin-panel">
            <h2>Ajouter une catégorie</h2>
            <?php foreach($errors as $error): ?>
                <div class="form-error"><?= e($error) ?></div>
                <?php endforeach; ?>
                <form method="post" class="inline-form">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="action" value="add">
                    <input name="name" placeholder="Nom de la catégorie" required>
                    <button class="btn btn-dark">Ajouter</button>
                </form>
            </div>
            <div class="admin-panel">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Produits liés</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($categories as $c): ?>
                                <tr>
                                    <td><?= e($c['name']) ?></td>
                                    <td><?= (int)$c['product_count'] ?></td>
                                    <td class="actions">
                                        <form method="post" class="category-action-form">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">

                                            <input
                                                type="text"
                                                name="name"
                                                value="<?= e($c['name']) ?>"
                                                required
                                            >

                                            <button type="submit" class="table-action">
                                                Modifier
                                            </button>
                                        </form>

                                        <form
                                            method="post"
                                            class="category-action-form"
                                            onsubmit="return confirm('Supprimer cette catégorie ?')"
                                        >
                                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">

                                                <button
                                                    type="submit"
                                                    class="table-action danger"
                                                    <?= $c['product_count'] > 0 ? 'disabled title="Cette catégorie contient des produits"' : '' ?>
                                                >
                                                    Supprimer
                                                </button>
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

