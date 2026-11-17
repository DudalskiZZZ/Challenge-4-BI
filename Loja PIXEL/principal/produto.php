<?php
require_once __DIR__ . '/includes/bootstrap.php';
$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
$stmt=db()->prepare("SELECT p.*,c.nome categoria FROM produtos p JOIN categorias c ON c.id=p.categoria_id WHERE p.id=? AND p.ativo=1");
$stmt->execute([$id]); $p=$stmt->fetch();
if(!$p){ http_response_code(404); exit('Produto não encontrado.'); }
$pageTitle=$p['nome']; require __DIR__.'/includes/header.php';
?>
<div class="row g-4 align-items-start"><div class="col-md-6"><img class="img-fluid rounded-4 product-detail" src="<?= e(image_url($p['imagem'])) ?>" onerror="this.src='assets/img/placeholder.svg'" alt="<?= e($p['nome']) ?>"></div>
<div class="col-md-6"><span class="text-secondary"><?= e($p['categoria']) ?></span><h1 class="mt-2"><?= e($p['nome']) ?></h1><p><?= nl2br(e($p['descricao'])) ?></p><div class="display-6 fw-bold my-3"><?= money($p['preco']) ?></div><p><?= (int)$p['estoque']>0 ? 'Disponível: '.(int)$p['estoque'].' unidade(s)' : 'Produto indisponível' ?></p>
<?php if((int)$p['estoque']>0): ?><form method="post" action="carrinho.php" class="d-flex gap-2 align-items-end"><?= csrf_field() ?><input type="hidden" name="acao" value="adicionar"><input type="hidden" name="produto_id" value="<?= (int)$p['id'] ?>"><div><label class="form-label">Quantidade</label><input class="form-control" type="number" name="quantidade" min="1" max="<?= (int)$p['estoque'] ?>" value="1" required></div><button class="btn btn-dark btn-lg">Adicionar ao carrinho</button></form><?php endif; ?>
</div></div>
<?php require __DIR__.'/includes/footer.php'; ?>