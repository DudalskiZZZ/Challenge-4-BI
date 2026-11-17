<?php
require_once dirname(__DIR__).'/includes/bootstrap.php';require_admin();
$counts=['produtos'=>db()->query("SELECT COUNT(*) FROM produtos")->fetchColumn(),'pedidos'=>db()->query("SELECT COUNT(*) FROM pedidos")->fetchColumn(),'usuarios'=>db()->query("SELECT COUNT(*) FROM usuarios")->fetchColumn()];
$pageTitle='Painel administrativo';require dirname(__DIR__).'/includes/header.php';
?>
<h1 class="h2 mb-4">Painel administrativo</h1><div class="row g-3 mb-4"><?php foreach($counts as $label=>$count): ?><div class="col-md-4"><div class="card"><div class="card-body"><div class="text-secondary text-capitalize"><?= e($label) ?></div><div class="display-6 fw-bold"><?= (int)$count ?></div></div></div></div><?php endforeach; ?></div><div class="d-flex gap-2 flex-wrap"><a class="btn btn-dark" href="produtos.php">Gerenciar produtos</a><a class="btn btn-outline-dark" href="pedidos.php">Ver pedidos</a><a class="btn btn-outline-secondary" href="../index.php">Ver loja</a></div>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>