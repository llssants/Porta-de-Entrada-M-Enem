<?php
require 'conexao.php';
$stmt = $conexao->prepare("SELECT * FROM fontes ORDER BY ultima_verificacao DESC");
$stmt->execute();
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Todos os Vestibulares e Seriados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container my-5">
    <h1 class="mb-4">Todos os Vestibulares e Seriados</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($eventos as $e): ?>
      <div class="col">
        <div class="card h-100">
          <?php if ($e['img_url']): ?>
            <img src="<?= htmlspecialchars($e['img_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($e['nome']) ?>" style="height: 180px; object-fit: cover;">
          <?php else: ?>
            <div style="height:180px; background:#ccc; display:flex; justify-content:center; align-items:center;">Sem imagem</div>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($e['nome']) ?></h5>
            <p class="card-text">
              Status: <strong><?= htmlspecialchars($e['ultimo_status'] ?? 'não verificado') ?></strong><br>
              Última verificação: <?= $e['ultima_verificacao'] ?? '-' ?>
            </p>
            <a href="<?= htmlspecialchars($e['url']) ?>" target="_blank" class="btn btn-primary">Ir para a página</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
