<?php
require __DIR__.'/config/database.php'; require __DIR__.'/includes/functions.php'; require_login();
$pageTitle='Finaliser ma commande — Nalya'; $errors=[]; $user=current_user();
if (is_post()) {
    verify_csrf();
    $name=trim($_POST['customer_name']??''); $phone=trim($_POST['phone']??''); $address=trim($_POST['address']??''); $city=trim($_POST['city']??''); $notes=trim($_POST['notes']??'');
    if (mb_strlen($name)<2) $errors[]='Veuillez saisir votre nom complet.';
    if (mb_strlen($phone)<6) $errors[]='Veuillez saisir un numéro de téléphone valide.';
    if (mb_strlen($address)<5) $errors[]='Veuillez préciser votre adresse.';
    if (mb_strlen($city)<2) $errors[]='Veuillez préciser votre ville.';
    $cartJson=json_decode($_POST['cart_json']??'[]',true);
    if (!is_array($cartJson) || !$cartJson) $errors[]='Votre panier est vide.';
    if (!$errors) {
        try {
            $pdo->beginTransaction(); $items=[]; $total=0;
            foreach ($cartJson as $entry) {
                $id=(int)($entry['id']??0); $qty=(int)($entry['quantity']??0);
                if ($id<1 || $qty<1) throw new RuntimeException('Un article du panier est invalide.');
                $stmt=$pdo->prepare("SELECT id,name,price,stock,is_active FROM products WHERE id=? FOR UPDATE"); $stmt->execute([$id]); $product=$stmt->fetch();
                if (!$product || !$product['is_active']) throw new RuntimeException('Un produit n’est plus disponible.');
                if ($qty>(int)$product['stock']) throw new RuntimeException('Stock insuffisant pour : '.$product['name']);
                $items[]=['id'=>$id,'name'=>$product['name'],'price'=>(float)$product['price'],'quantity'=>$qty];
                $total += (float)$product['price']*$qty;
            }
            $orderNumber='NLY-'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
            $stmt=$pdo->prepare("INSERT INTO orders(order_number,user_id,customer_name,phone,address,city,notes,total,status,payment_method) VALUES(?,?,?,?,?,?,?,?, 'En attente','Paiement à la livraison')");
            $stmt->execute([$orderNumber,$user['id'],$name,$phone,$address,$city,$notes,$total]); $orderId=(int)$pdo->lastInsertId();
            $insert=$pdo->prepare("INSERT INTO order_items(order_id,product_id,product_name,quantity,unit_price) VALUES(?,?,?,?,?)");
            $update=$pdo->prepare("UPDATE products SET stock=stock-? WHERE id=? AND stock>=?");
            foreach($items as $item) {
                $insert->execute([$orderId,$item['id'],$item['name'],$item['quantity'],$item['price']]);
                $update->execute([$item['quantity'],$item['id'],$item['quantity']]);
                if ($update->rowCount()!==1) throw new RuntimeException('Le stock a changé. Veuillez réessayer.');
            }
            $pdo->commit(); $_SESSION['last_order_id']=$orderId;
            header('Location: order-confirmation.php?id='.$orderId); exit;
        } catch(Throwable $e) {
            if($pdo->inTransaction()) $pdo->rollBack();
            $errors[]=$e instanceof RuntimeException ? $e->getMessage() : 'La commande n’a pas pu être enregistrée. Veuillez réessayer.';
        }
    }
}
require __DIR__.'/includes/header.php';
?>
<section class="page-banner compact-banner"><p class="eyebrow">DERNIÈRE ÉTAPE</p><h1>Finaliser ma commande</h1><p>Paiement à la livraison.</p></section>
<section class="section checkout-layout"><div class="checkout-form"><h2>Adresse de livraison</h2><?php foreach($errors as $error): ?><div class="form-error"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" class="form-stack" id="checkout-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="cart_json" id="cart-json">
<label>Nom et prénom<input name="customer_name" required value="<?= e($_POST['customer_name']??$user['name']) ?>"></label><label>Téléphone<input name="phone" type="tel" required value="<?= e($_POST['phone']??'') ?>"></label><label>Adresse complète<textarea name="address" rows="3" required><?= e($_POST['address']??'') ?></textarea></label><label>Ville<input name="city" required value="<?= e($_POST['city']??'') ?>"></label><label>Notes <span class="muted">(facultatif)</span><textarea name="notes" rows="2"><?= e($_POST['notes']??'') ?></textarea></label><button class="btn btn-dark btn-full">Confirmer la commande</button></form></div>
<aside class="cart-summary"><p class="eyebrow">PAIEMENT</p><h2>À la livraison</h2><p>Vous réglerez votre commande au moment de la réception. Le stock et les prix seront vérifiés côté serveur.</p><a class="text-link" href="cart.php">← Retour au panier</a></aside></section>
<script>
document.getElementById('checkout-form').addEventListener('submit', function(event) {
  let cart=[]; try { cart=JSON.parse(localStorage.getItem('nalya_cart')||'[]'); } catch(e) {}
  if (!cart.length) { event.preventDefault(); alert('Votre panier est vide.'); return; }
  document.getElementById('cart-json').value=JSON.stringify(cart.map(item=>({id:Number(item.id),quantity:Number(item.quantity)})));
});
</script>
<?php require __DIR__.'/includes/footer.php'; ?>
