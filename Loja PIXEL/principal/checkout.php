<?php
require_once __DIR__.'/includes/bootstrap.php'; require_login();
$items=cart_items();if(!$items){header('Location: carrinho.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$method=$_POST['pagamento']??'';
 if(!in_array($method,['PIX','Boleto','Cartão'],true))$error='Selecione uma forma de pagamento válida.';
 else{
  try{
   $pdo=db();$pdo->beginTransaction();$total=0;
   foreach($items as $it){$s=$pdo->prepare("SELECT preco,estoque,ativo FROM produtos WHERE id=? FOR UPDATE");$s->execute([$it['id']]);$fresh=$s->fetch();if(!$fresh||!$fresh['ativo']||(int)$fresh['estoque']<(int)$it['quantidade'])throw new RuntimeException('Estoque insuficiente para '.$it['nome'].'.');$total+=(float)$fresh['preco']*(int)$it['quantidade'];}
   $s=$pdo->prepare("INSERT INTO pedidos (usuario_id,total,pagamento,status) VALUES (?,?,?,'Confirmado')");$s->execute([$_SESSION['usuario']['id'],$total,$method]);$orderId=(int)$pdo->lastInsertId();
   foreach($items as $it){$s=$pdo->prepare("SELECT preco FROM produtos WHERE id=?");$s->execute([$it['id']]);$price=$s->fetchColumn();$pdo->prepare("INSERT INTO pedido_itens (pedido_id,produto_id,quantidade,preco_unitario) VALUES (?,?,?,?)")->execute([$orderId,$it['id'],$it['quantidade'],$price]);$pdo->prepare("UPDATE produtos SET estoque=estoque-? WHERE id=?")->execute([$it['quantidade'],$it['id']]);}
   $pdo->commit();$_SESSION['carrinho']=[];header('Location: pedido.php?id='.$orderId);exit;
  }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error=$e instanceof RuntimeException?$e->getMessage():'Não foi possível concluir o pedido.';}
 }
}
$pageTitle='Checkout';require __DIR__.'/includes/header.php';
?>
<h1 class="h2 mb-4">Finalizar compra</h1><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div class="row g-4"><div class="col-lg-7"><div class="card"><div class="card-body"><h2 class="h5">Forma de pagamento</h2><p class="text-secondary">Esta atividade utiliza pagamento simulado, sem cobrança real.</p><form method="post"><?= csrf_field() ?><div class="form-check"><input class="form-check-input" type="radio" name="pagamento" value="PIX" checked><label class="form-check-label">PIX</label></div><div class="form-check"><input class="form-check-input" type="radio" name="pagamento" value="Boleto"><label class="form-check-label">Boleto</label></div><div class="form-check"><input class="form-check-input" type="radio" name="pagamento" value="Cartão"><label class="form-check-label">Cartão</label></div><button class="btn btn-dark mt-4">Confirmar pedido</button></form></div></div></div>
<div class="col-lg-5"><div class="card"><div class="card-body"><h2 class="h5">Resumo</h2><?php foreach($items as $it): ?><div class="d-flex justify-content-between py-2"><span><?= e($it['nome']) ?> × <?= (int)$it['quantidade'] ?></span><strong><?= money($it['subtotal']) ?></strong></div><?php endforeach; ?><hr><div class="d-flex justify-content-between fs-5"><strong>Total</strong><strong><?= money(cart_total()) ?></strong></div></div></div></div></div>
<?php require __DIR__.'/includes/footer.php'; ?>