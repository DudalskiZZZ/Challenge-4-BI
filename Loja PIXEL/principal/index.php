<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Loja Pixel | Games e acessórios';
$q = trim($_GET['q'] ?? '');
$categoryId = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: null;
$sql = "SELECT p.*, c.nome AS categoria FROM produtos p JOIN categorias c ON c.id=p.categoria_id WHERE p.ativo=1";
$params = [];
if ($q !== '') { $sql .= " AND (p.nome LIKE :q OR p.descricao LIKE :q)"; $params['q'] = "%$q%"; }
if ($categoryId) { $sql .= " AND p.categoria_id=:cat"; $params['cat'] = $categoryId; }
$sql .= " ORDER BY p.criado_em DESC";
$stmt = db()->prepare($sql); $stmt->execute($params); $products = $stmt->fetchAll();
$categories = db()->query("SELECT id,nome FROM categorias ORDER BY nome")->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<section class="hero rounded-4 p-4 p-md-5 mb-4">
  <div class="row align-items-center"><div class="col-lg-7">
    <span class="badge text-bg-warning mb-3">LOJA PIXEL</span>
    <h1 class="display-5 fw-bold">Seu próximo jogo começa aqui.</h1>
    <p class="lead">Games, acessórios e tecnologia para sua próxima aventura.</p>
    <a href="#catalogo" class="btn btn-light btn-lg">Explorar produtos</a>
  </div><div class="col-lg-5 text-center"><div class="hero-art">🎮</div></div></div>
</section>
<section id="catalogo">
 <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3"><div><h2 class="h3 mb-1">Catálogo</h2><p class="text-secondary mb-0">Produtos cadastrados na loja</p></div>
 <form class="d-flex gap-2" method="get">
  <input class="form-control" name="q" placeholder="Buscar produto..." value="<?= e($q) ?>">
  <select class="form-select" name="categoria"><option value="">Todas</option><?php foreach($categories as $c): ?><option value="<?= (int)$c['id'] ?>" <?= $categoryId===(int)$c['id']?'selected':'' ?>><?= e($c['nome']) ?></option><?php endforeach; ?></select>
  <button class="btn btn-dark">Filtrar</button>
 </form></div>
 <div class="row g-4">
 <?php if (!$products): ?><p>Nenhum produto encontrado.</p><?php endif; ?>
 <?php foreach($products as $p): ?><div class="col-6 col-md-4 col-xl-3"><article class="card product-card h-100">
  <img src="<?= e(image_url($p['imagem'])) ?>" class="card-img-top product-image" alt="<?= e($p['nome']) ?>" onerror="this.src='assets/img/placeholder.svg'">
  <div class="card-body d-flex flex-column"><span class="small text-secondary"><?= e($p['categoria']) ?></span><h3 class="h6 mt-1"><?= e($p['nome']) ?></h3><p class="small text-secondary flex-grow-1"><?= e(mb_strimwidth($p['descricao'],0,85,'…')) ?></p>
  <div class="fw-bold fs-5 mb-2"><?= money($p['preco']) ?></div><div class="small mb-3"><?= (int)$p['estoque']>0?'Em estoque':'Esgotado' ?></div>
  <a class="btn btn-outline-dark" href="produto.php?id=<?= (int)$p['id'] ?>">Ver produto</a></div></article></div><?php endforeach; ?>
 </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>