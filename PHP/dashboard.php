<?php
session_start();

require 'conexao.php';
if (!isset($_SESSION['usuario']) || !isset($_SESSION['perfil'])) {
    header("Location: login.php"); // sua página de login
    exit;
}
$stmt = $conexao->prepare("SELECT * FROM fontes WHERE ultimo_status = 'aberto' ORDER BY ultima_verificacao DESC");

if (!$stmt) {
    die("Erro na preparação da query: " . $conexao->error);
}

$stmt->execute();

$result = $stmt->get_result();

$eventos = $result->fetch_all(MYSQLI_ASSOC);


$nome = $_SESSION['usuario'];
$perfil = $_SESSION['perfil'];

// Sistema de pontos
if (!isset($_SESSION['pontos'])) $_SESSION['pontos'] = 0;
if (!isset($_SESSION['ultimo_estudo'])) $_SESSION['ultimo_estudo'] = "";

if (isset($_POST['estudar'])) {
    $hoje = date("Y-m-d");
    if ($_SESSION['ultimo_estudo'] != $hoje) {
        $_SESSION['pontos'] += 10;
        $_SESSION['ultimo_estudo'] = $hoje;
        $msg = "🔥 Parabéns! Você estudou hoje e ganhou +10 pontos!";
    } else {
        $msg = "⚠️ Você já marcou estudo hoje! Volte amanhã 😉";
    }
}

if ($perfil === 'professor') {
    $linkPerfil = 'perfil_professor.php';
} elseif ($perfil === 'coordenador') {
    $linkPerfil = 'perfil_coordenador.php';
} else {
    $linkPerfil = '../HTML/perfil.html'; // padrão para aluno
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Game start</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>

    <header>
        <h2>Porta de entrada</h2>
        <nav>
            <a href="../HTML/metodos.html">Métodos de Estudo</a>
            <a href="#">Universidades</a>
            <a href="#">Estudos</a>
            <a href="<?php echo $linkPerfil; ?>" class="btn">Perfil</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <div class="container">
    <h1 class="mb-4">Vestibulares com Inscrições Abertas</h1>

<?php if (count($eventos) === 0): ?>
  <p>Nenhum vestibular com inscrições abertas no momento.</p>
<?php else: ?>
<div id="carrosselVestibulares" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php foreach ($eventos as $i => $e): ?>
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
        <?php if ($e['img_url']): ?>
          <img src="<?= htmlspecialchars($e['img_url']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($e['nome']) ?>" style="max-height: 400px; object-fit: cover;" />
        <?php else: ?>
          <div style="height: 400px; background: #ddd; display:flex; align-items:center; justify-content:center;">Sem imagem</div>
        <?php endif; ?>
        <div class="carousel-caption bg-dark bg-opacity-50 rounded p-3">
          <h5><?= htmlspecialchars($e['nome']) ?></h5>
          <?php if (!empty($e['data_prova'])): ?>
            <p>Data da Prova: <?= date('d/m/Y', strtotime($e['data_prova'])) ?></p>
          <?php endif; ?>
          <a href="<?= htmlspecialchars($e['url']) ?>" target="_blank" class="btn btn-primary">Saiba Mais</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carrosselVestibulares" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carrosselVestibulares" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Próximo</span>
  </button>
</div>
<?php endif; ?>


            <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?>! </h1>

            <p style="text-align:center;">
    Querido estudante, seja muito bem-vindo à nossa plataforma! <br><br>
    Esperamos que sua experiência aqui seja incrível e que você leve boas lembranças desse espaço. 
    Criamos este projeto como parte do nosso Projeto Integrador, com o objetivo de apoiar ainda mais os alunos da nossa instituição a 
    conquistarem seus sonhos. Se você é estudante do IF Sul de Minas, saiba que já estivemos no mesmo lugar que você. 
    Talvez não na mesma sala ou curso, mas com certeza compartilhamos desafios e experiências em comum. <br><br>

    Para facilitar sua jornada, aqui estão as regras do jogo: <br><br>

    <strong>1°</strong> Explore todas as páginas da plataforma — cada uma delas será essencial para você organizar sua rotina de estudos. <br>
    <strong>2°</strong> Na aba <em>Perfil</em>, você terá acesso à análise do seu desempenho: lá estarão as regras detalhadas e a 
    contabilização dos pontos. <br>
    <strong>3°</strong> Nunca se esqueça: este é um jogo, mas seus pontos não definem quem você é. Se em algum momento você se sentir mal, 
    acesse o bot na área de <em>Perfil</em> e converse conosco. Estamos aqui para ajudar! <br>
    <strong>4°</strong> Compartilhe experiências com seus colegas, seja pelo chat ou pelas postagens de conteúdos. <br>
    <strong>5°</strong> Aproveite ao máximo seus 3 anos de IF! Sim, serão desafiadores, mas também inesquecíveis e marcantes para a sua vida. <br><br>

    🚀 Estamos juntos nessa jornada. Agora é com você: estude, participe e divirta-se!
</p>



            <div class="pontos">
                <h3>Seus pontos: <?php echo $_SESSION['pontos']; ?> ⭐</h3>
                <form method="post">
                    <button class="btn" type="submit" name="estudar">Estudei hoje!</button>
                </form>
            </div>

            <?php if (!empty($msg)): ?>
                <p class="msg"><?php echo $msg; ?></p>
            <?php endif; ?>

        </div>

    <div class="container">
        <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?>! 👋</h1>

        <!-- <p>Seu perfil: <strong><?php echo ucfirst($perfil); ?></strong></p> -->


        <div class="pontos">
            <h3>Seus pontos: <?php echo $_SESSION['pontos']; ?> ⭐</h3>
            <form method="post">
                <button class="btn" type="submit" name="estudar">Estudei hoje!</button>
            </form>
        </div>

        <?php if (!empty($msg)): ?>
            <p class="msg"><?php echo $msg; ?></p>
        <?php endif; ?>

        <p style="text-align:center;">Estude todos os dias para acumular pontos e desbloquear conquistas! </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
