<?php
require_once __DIR__.'/includes/bootstrap.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $action=$_POST['acao']??'';
 $pid=filter_var($_POST['produto_id']??null,FILTER_VALIDATE_INT);
 if($action==='adicionar' && $pid){
  $qty=max(1,(int)($_POST['quantidade']??1));
  $s=db()->prepare("SELECT id,estoque FROM produtos WHERE id=? AND ativo=1");$s->execute([$pid]);$p=$s->fetch();
  if($p){$current=(int)($_SESSION['carrinho'][$pid]??0);$_SESSION['carrinho'][$pid]=min($current+$qty,(int)$p['estoque']);}
 } elseif($action==='atualizar'){
  foreach(($_POST['quantidades']??[]) as $id=>$qty){$id=(int)$id;$qty=(int)$qty;$s=db()->prepare("SELECT estoque FROM produtos WHERE id=? AND ativo=1");$s->execute([$id]);$p=$s->fetch();if(!$p||$qty<1)unset($_SESSION['carrinho'][$id]);else $_SESSION['carrinho'][$id]=min($qty,(int)$p['estoque']);}
 } elseif($action==='remover' && $pid){unset($_SESSION['carrinho'][$pid]);}
 header('Location: carrinho.php');exit;
}
$items=cart_items();$total=cart_total();$pageTitle='Carrinho';require __DIR__.'/includes/header.php';
?>
<h1 class="h2 mb-4">Seu carrinho</h1>
<?php if(!$items): ?><div class="alert alert-info">Seu carrinho está vazio. <a href="index.php">Voltar ao catálogo</a>.</div>
<?php else: ?><form method="post"><?= csrf_field() ?><input type="hidden" name="acao" value="atualizar"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Produto</th><th>Preço</th><th style="width:150px">Quantidade</th><th>Subtotal</th><th></th></tr></thead><tbody>
<?php foreach($items as $it): ?><tr><td><?= e($it['nome']) ?></td><td><?= money($it['preco']) ?></td><td><input class="form-control" type="number" min="0" max="<?= (int)$it['estoque'] ?>" name="quantidades[<?= (int)$it['id'] ?>]" value="<?= (int)$it['quantidade'] ?>"></td><td><?= money($it['subtotal']) ?></td><td><button class="btn btn-sm btn-outline-danger" formaction="carrinho.php" formmethod="post" name="acao" value="remover" onclick="this.form.insertAdjacentHTML('beforeend','<input type=hidden name=produto_id value=<?= (int)$it['id'] ?>>')">Remover</button></td></tr><?php endforeach; ?>
</tbody></table></div><div class="d-flex justify-content-between align-items-center flex-wrap gap-3"><button class="btn btn-outline-dark">Atualizar quantidades</button><div class="text-end"><div class="text-secondary">Total</div><div class="fs-3 fw-bold"><?= money($total) ?></div><a class="btn btn-dark mt-2" href="checkout.php">Continuar para checkout</a></div></div></form><?php endif; ?>
<?php require __DIR__.'/includes/footer.php'; ?>