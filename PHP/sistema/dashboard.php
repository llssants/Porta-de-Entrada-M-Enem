<?php
session_start();
require_once '../cadastro/conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../cadastro/login.php");
    exit;
}

$nome = $_SESSION['usuario'];
$linkPerfil = '../HTML/perfil.html';

// Inicializa variáveis de sessão para gamificação
if (!isset($_SESSION['pontos'])) $_SESSION['pontos'] = 0;
if (!isset($_SESSION['ultimo_estudo'])) $_SESSION['ultimo_estudo'] = "";
if (!isset($_SESSION['desafios'])) {
    $_SESSION['desafios'] = [
        'ler_30_minutos' => false,
        'responder_questao' => false,
        'assistir_video' => false,
    ];
}
if (!isset($_SESSION['badges'])) {
    $_SESSION['badges'] = [];
}

$msg = "";

// Função para adicionar pontos e controlar badges
function adicionarPontos($pts) {
    $_SESSION['pontos'] += $pts;

    // Adiciona badges conforme pontuação
    if ($_SESSION['pontos'] >= 100 && !in_array('badge_100', $_SESSION['badges'])) {
        $_SESSION['badges'][] = 'badge_100';
    }
    if ($_SESSION['pontos'] >= 300 && !in_array('badge_300', $_SESSION['badges'])) {
        $_SESSION['badges'][] = 'badge_300';
    }
    if ($_SESSION['pontos'] >= 500 && !in_array('badge_500', $_SESSION['badges'])) {
        $_SESSION['badges'][] = 'badge_500';
    }
}

// Ação botão Estudei hoje
if (isset($_POST['estudar'])) {
    $hoje = date("Y-m-d");
    if ($_SESSION['ultimo_estudo'] != $hoje) {
        adicionarPontos(10);
        $_SESSION['ultimo_estudo'] = $hoje;
        $msg = " Parabéns! Você estudou hoje e ganhou +10 pontos!";
    } else {
        $msg = " Você já marcou estudo hoje! Volte amanhã ";
    }
}

// Ação desafios diários
if (isset($_POST['desafio'])) {
    $desafio = $_POST['desafio'];
    if (array_key_exists($desafio, $_SESSION['desafios']) && $_SESSION['desafios'][$desafio] === false) {
        $_SESSION['desafios'][$desafio] = true;
        adicionarPontos(5);
        $msg = " Desafio '{$desafio}' completado! +5 pontos!";
    } else {
        $msg = " Você já completou este desafio hoje.";
    }
}


// Ranking fictício (estático para exemplo)
$ranking = [
    ['nome' => 'Ana', 'pontos' => 540],
    ['nome' => 'Carlos', 'pontos' => 490],
    ['nome' => $nome, 'pontos' => $_SESSION['pontos']],
    ['nome' => 'Mariana', 'pontos' => 350],
    ['nome' => 'Felipe', 'pontos' => 280],
];

// Mensagens motivacionais dinâmicas
$mensagens = [
    "A jornada de mil quilômetros começa com o primeiro passo.",
    "Estudar hoje é conquistar seu amanhã!",
    "Cada ponto é uma vitória. Continue assim!",
    "Você está construindo seu futuro. Não pare!",
    "Pequenos passos levam a grandes conquistas.",
];
$mensagemMotivacional = $mensagens[array_rand($mensagens)];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard | Porta de Entrada Gamificada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../CSS/dashboard.css">

</head>
<body>

<header>
    <h2 class="title">Porta de entrada</h2>
    <nav>
        <a href="../../HTML/metodos.html">Métodos de Estudo</a>
        <a href="../teste.html">Game Start</a>
        <a href="#">Simulados</a>

       <div class="menu-perfil">
  <span class="perfil-trigger">Perfil ▾</span>
  <ul class="perfil-opcoes">
    <li><a href="<?php echo $linkPerfil; ?>">Ver Perfil</a></li>
    <li><a href="logout.php">Sair</a></li>
  </ul>
</div>

    </nav>
</header>


<main class="container">

    <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?>! </h1>

    <section class="instrucoes" aria-label="Instruções para a plataforma">
        <h2>Como funciona o jogo?</h2>
        <p>
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

             Estamos juntos nessa jornada. Agora é com você: estude, participe e divirta-se!
        </p>
    </section>

    <div class="separador"></div>

    <section aria-label="Pontos e conquistas do usuário" class="pontos-container">
        <h2>Seus Pontos & Conquistas</h2>

        <div class="pontos text-center mb-4">
            <h3><?php echo $_SESSION['pontos']; ?> </h3>
            <form method="post">
                <button class="btn btn-success" type="submit" name="estudar">Estudei hoje!</button>
            </form>
            <?php if (!empty($msg)): ?>
                <p class="mt-3"><?php echo $msg; ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="barraProgresso" class="form-label"><strong>Meta mensal: 500 pontos</strong></label>
            <?php 
                $progress = min(100, ($_SESSION['pontos'] / 500) * 100);
            ?>
            <div class="progress" role="progressbar" aria-valuenow="<?= (int)$progress ?>" aria-valuemin="0" aria-valuemax="100">
                <div id="barraProgresso" class="progress-bar" style="width: <?= $progress ?>%;">
                    <?= (int)$progress ?>%
                </div>
            </div>
        </div>

        <div class="badges" aria-label="Badges conquistadas">
            <?php if (empty($_SESSION['badges'])): ?>
                <p style="color:#800000; font-style: italic; margin-top: 1rem;">Você ainda não conquistou badges. Continue estudando!</p>
            <?php else: ?>
                <?php foreach ($_SESSION['badges'] as $badge): ?>
                    <?php 
                        $nomeBadge = match($badge) {
                            'badge_100' => '100 pontos',
                            'badge_300' => '300 pontos',
                            'badge_500' => '500 pontos',
                            default => 'Badge',
                        };
                    ?>
                    <div class="badge-item" title="Badge por alcançar <?= $nomeBadge ?>">
                        <?= $nomeBadge ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <div class="separador"></div>

    <section aria-label="Desafios Diários" class="desafios">
        <h3>Desafios Diários</h3>
        <form method="post" aria-label="Formulário dos desafios diários">
            <?php 
            $desafios_texto = [
                'ler_30_minutos' => 'Ler 30 minutos',
                'responder_questao' => 'Responder uma questão',
                'assistir_video' => 'Assistir um vídeo educacional',
            ];
            foreach ($desafios_texto as $key => $texto): ?>
                <button 
                    type="submit" 
                    name="desafio" 
                    value="<?= $key ?>" 
                    <?= $_SESSION['desafios'][$key] ? 'disabled aria-disabled="true"' : '' ?>
                    aria-pressed="<?= $_SESSION['desafios'][$key] ? 'true' : 'false' ?>"
                >
                    <?= $texto ?> <?= $_SESSION['desafios'][$key] ? '✔️' : '' ?>
                </button>
            <?php endforeach; ?>
        </form>
    </section>

    <div class="separador"></div>

    

    <section class="ranking" aria-label="Ranking de usuários">
        <h2>Ranking de Usuários</h2>
        <table>
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Nome</th>
                    <th>Pontos</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ordenar ranking por pontos descendente
                usort($ranking, fn($a,$b) => $b['pontos'] <=> $a['pontos']);
                foreach ($ranking as $pos => $user): 
                ?>
                    <tr <?= $user['nome'] === $nome ? 'style="font-weight:bold; background:#ffe5e5;" aria-current="row"' : '' ?>>
                        <td><?= $pos+1 ?></td>
                        <td><?= htmlspecialchars($user['nome']) ?></td>
                        <td><?= $user['pontos'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <div class="mensagem-motivacional" aria-live="polite" aria-atomic="true">
         <?php echo $mensagemMotivacional; ?>
    </div>

</main>

<footer>
    &copy; <?php echo date("Y"); ?> IF Sul de Minas - Projeto Integrador
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
