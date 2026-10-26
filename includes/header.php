<?php
$pageTitle = $pageTitle ?? 'Nalya — Bijoux au quotidien';
$user = current_user();
$notice = get_flash();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="Nalya, une sélection de bijoux fantaisie élégants.">
  <link rel="stylesheet" href="/Nalya2/assets/css/style.css">
  <script defer src="/Nalya2/assets/js/app.js"></script>
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>
<body>
<?php if (!$user || $user['role'] !== 'admin'): ?>
<header class="site-header">
  <a class="brand" href="index.php" aria-label="Nalya accueil">Nalya<span>.</span></a>
  <button class="menu-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>

<nav class="main-nav">
    <a href="index.php">
        <i class="fa-solid fa-house"></i> Accueil
    </a>

    <a href="products.php">
        <i class="fa-solid fa-gem"></i> La collection
    </a>

    <a href="favorites.php">
        <i class="fa-regular fa-heart"></i> Favoris
        <span class="count" data-favorite-count>0</span>
    </a>

    <a href="cart.php">
        <i class="fa-solid fa-bag-shopping"></i> Panier
        <span class="count" data-cart-count>0</span>
    </a>

    <?php if ($user): ?>
        <a href="account.php">
            <i class="fa-regular fa-user"></i> Mon compte
        </a>

        <?php if ($user['role'] === 'admin'): ?>
            <a href="admin/index.php">
                <i class="fa-solid fa-gauge-high"></i> Administration
            </a>
        <?php endif; ?>

        <form class="nav-form" method="post" action="logout.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <button class="link-button" type="submit">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
            </button>
        </form>

    <?php else: ?>
        <a href="login.php">
            <i class="fa-solid fa-right-to-bracket"></i> Connexion
        </a>
    <?php endif; ?>
</nav>


</header>
<?php endif; ?>
<main>
<?php if ($notice): ?><div class="flash <?= e($notice['type']) ?>"><?= e($notice['message']) ?></div><?php endif; ?>
