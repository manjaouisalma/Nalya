<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php';
$pageTitle='Connexion — Nalya'; $error='';
if (is_post()) {
    verify_csrf();
    $email = strtolower(trim($_POST['email'] ?? '')); $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1"); $stmt->execute([$email]); $user=$stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true); unset($user['password']); $_SESSION['user']=$user;
        flash('success','Ravi de vous retrouver, '. $user['name'].' !');
        redirect($user['role']==='admin'?'admin/index.php':'account.php');
    }
    $error='Adresse e-mail ou mot de passe incorrect.';
}
require __DIR__.'/includes/header.php';
?>
<section class="auth-wrap"><div class="auth-card"><p class="eyebrow">HEUREUSE DE VOUS REVOIR</p><h1>Connexion</h1><p>Retrouvez votre espace Nalya.</p>
<?php if($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Adresse e-mail<input type="email" name="email" required autocomplete="email" value="<?= e($_POST['email']??'') ?>"></label><label>Mot de passe<input type="password" name="password" required autocomplete="current-password"></label><button class="btn btn-dark btn-full">Se connecter</button></form><p class="auth-switch">Pas encore de compte ? <a href="register.php">Créer un compte</a></p></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
