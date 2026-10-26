<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php';
$pageTitle='Créer un compte — Nalya'; $errors=[];
if (is_post()) {
    verify_csrf();
    $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $phone=trim($_POST['phone']??''); $password=$_POST['password']??'';
    if (mb_strlen($name)<2) $errors[]='Veuillez saisir votre nom complet.';
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Veuillez saisir une adresse e-mail valide.';
    if (strlen($password)<8) $errors[]='Le mot de passe doit contenir au moins 8 caractères.';
    if (!$errors) {
        $stmt=$pdo->prepare("SELECT id FROM users WHERE email=?"); $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[]='Cette adresse e-mail est déjà utilisée.';
        else {
            $stmt=$pdo->prepare("INSERT INTO users(name,email,phone,password,role) VALUES(?,?,?,?, 'customer')");
            $stmt->execute([$name,$email,$phone,password_hash($password,PASSWORD_DEFAULT)]);
            flash('success','Votre compte a été créé. Vous pouvez vous connecter.'); redirect('login.php');
        }
    }
}
require __DIR__.'/includes/header.php';
?>
<section class="auth-wrap"><div class="auth-card"><p class="eyebrow">BIENVENUE CHEZ NALYA</p><h1>Créer un compte</h1><p>Quelques informations pour commencer.</p>
<?php foreach($errors as $error): ?><div class="form-error"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Nom complet<input name="name" required minlength="2" value="<?= e($_POST['name']??'') ?>"></label><label>Adresse e-mail<input type="email" name="email" required value="<?= e($_POST['email']??'') ?>"></label><label>Téléphone <span class="muted">(facultatif)</span><input name="phone" type="tel" value="<?= e($_POST['phone']??'') ?>"></label><label>Mot de passe<input type="password" name="password" minlength="8" required autocomplete="new-password"><small>8 caractères minimum.</small></label><button class="btn btn-dark btn-full">Créer mon compte</button></form><p class="auth-switch">Déjà inscrite ? <a href="login.php">Se connecter</a></p></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
