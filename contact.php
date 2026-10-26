<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php';
$pageTitle='Contact — Nalya'; $errors=[];
if(is_post()){
 verify_csrf(); $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $subject=trim($_POST['subject']??''); $message=trim($_POST['message']??'');
 if(mb_strlen($name)<2)$errors[]='Veuillez saisir votre nom.'; if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Adresse e-mail invalide.'; if(mb_strlen($message)<10)$errors[]='Votre message doit contenir au moins 10 caractères.';
 if(!$errors){$stmt=$pdo->prepare("INSERT INTO contact_messages(name,email,subject,message) VALUES(?,?,?,?)");$stmt->execute([$name,$email,$subject,$message]);flash('success','Votre message a bien été envoyé.');redirect('contact.php');}
}
require __DIR__.'/includes/header.php';
?>
<section class="page-banner compact-banner"><p class="eyebrow">RESTONS EN CONTACT</p><h1>Un mot à nous dire ?</h1><p>Une question sur la collection ou votre commande ? Écrivez-nous.</p></section><section class="section auth-wrap"><div class="auth-card"><h2>Envoyer un message</h2><?php foreach($errors as $error): ?><div class="form-error"><?= e($error) ?></div><?php endforeach; ?><form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Nom<input name="name" required value="<?= e($_POST['name']??'') ?>"></label><label>E-mail<input type="email" name="email" required value="<?= e($_POST['email']??'') ?>"></label><label>Sujet<input name="subject" value="<?= e($_POST['subject']??'') ?>"></label><label>Message<textarea name="message" rows="5" required><?= e($_POST['message']??'') ?></textarea></label><button class="btn btn-dark btn-full">Envoyer le message</button></form></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
