<?php
session_start();
require_once 'conexao.php'; // conexao mysqli

// Mensagem de erro ou sucesso
$msg = "";

// ==================== CADASTRO ====================
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $perfil = $_POST['perfil'];
    $turma = $_POST['turma'];

    // Verificar se o email já existe
    $check = $conexao->prepare("SELECT id_usuario FROM usuarios WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $msg = "Email já cadastrado!";
    } else {
        $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, perfil, turma) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $email, $senha, $perfil, $turma);
        
        if ($stmt->execute()) {
            $msg = "Cadastro realizado com sucesso!";
        } else {
            $msg = "Erro ao cadastrar!";
        }
    }
}

// ==================== LOGIN ====================
if (isset($_POST['login'])) {
    $email = $_POST['email_login'];
    $senha = $_POST['senha_login'];

    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario'] = $user['nome'];
        $_SESSION['perfil'] = $user['perfil'];
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Cadastro</title>
  <style>
    /* Reset básico */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #710000, #961515, #1f1919);
      background-size: 400% 400%;
      animation: bgAnim 10s ease infinite;
    }

    @keyframes bgAnim {
      0%, 100% {background-position: 0% 50%;}
      50% {background-position: 100% 50%;}
    }

    .container {
      width: 900px;
      height: 550px;
      border-radius: 20px;
      box-shadow: 0 0 30px rgba(0,0,0,0.5);
      display: flex;
      overflow: hidden;
      backdrop-filter: blur(20px);
      transition: 0.8s ease;
    }

    .left-panel, .right-panel {
      flex: 1;
      padding: 60px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      transition: 0.8s ease;
    }

    .left-panel {
      color: #fff;
    }

    .left-panel h1 {
      font-size: 46px;
      margin-bottom: 20px;
    }

    .left-panel p {
      font-size: 16px;
      opacity: 0.9;
      margin-bottom: 30px;
    }

    .right-panel {
      background: rgba(0,0,0,0.35);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    form {
      width: 100%;
      max-width: 320px;
      display: flex;
      flex-direction: column;
    }

    form h2 {
      margin-bottom: 20px;
      font-size: 28px;
      text-align: center;
    }

    input, select {
      background: #fff !important;
      color: #000 !important;
      padding: 12px 15px;
      margin-bottom: 15px;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      outline: none;
      transition: 0.3s;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }

    input::placeholder,
    select option {
      color: #555;
    }

    input:focus,
    select:focus {
      box-shadow: 0 0 6px rgba(255,255,255,0.6);
      transform: scale(1.02);
    }

    input:-webkit-autofill,
    select:-webkit-autofill {
      -webkit-box-shadow: 0 0 0px 1000px #fff inset !important;
      box-shadow: 0 0 0px 1000px #fff inset !important;
      -webkit-text-fill-color: #000 !important;
      transition: background-color 5000s ease-in-out 0s;
    }

    button.submit-btn {
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      background: linear-gradient(45deg, #780303, #740e0e);
      color: #fff;
      cursor: pointer;
      transition: 0.3s;
    }

    button.submit-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 0 12px rgba(255,0,0,0.6);
    }

    .switch-link {
      margin-top: 15px;
      text-align: center;
      cursor: pointer;
      color: #ddd;
      font-size: 14px;
      transition: 0.3s;
    }

    .switch-link:hover {
      color: #fff;
    }

    .container.signup-mode .left-panel {
      transform: translateX(100%);
    }

    .container.signup-mode .right-panel {
      transform: translateX(-100%);
    }
  </style>
</head>
<body>

<div class="container" id="container">
  <div class="left-panel">
    <h1 id="leftTitle">Bem-vindo!</h1>
    <p id="leftText">Acesse com sua conta para continuar sua jornada.</p>
  </div>

  <div class="right-panel">
    <!-- Login Form -->
 <form id="loginForm" method="POST" action="login.php">
  <input type="hidden" name="login" value="1"> 
  
  <h2>Entrar</h2>
  <input type="email" name="email_login" placeholder="Email" required>
  <input type="password" name="senha_login" placeholder="Senha" required>
  <button type="submit" class="submit-btn">Login</button>
  <div class="switch-link" id="toSignup">Ainda não tem conta? Cadastre-se</div>
</form>


    <!-- Cadastro Form -->
   <form id="signupForm" style="display: none;" method="POST" action="login.php">
  <input type="hidden" name="cadastrar" value="1"> 

  <h2>Cadastrar</h2>
  <input type="text" name="nome" placeholder="Nome completo" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="senha" placeholder="Senha" required>

  <select name="perfil" required>
    <option value="" disabled selected>Selecione a atuação</option>
    <option value="Aluno">Aluno</option>
    <option value="Professor">Professor</option>
    <option value="Coordenador">Coordenador</option>
  </select>

  <select name="turma" required>
    <option value="" disabled selected>Selecione a turma</option>
    <option value="Info A">Docente</option>
    <option value="Info A">Info A</option>
    <option value="Info B">Info B</option>
    <option value="Info C">Info C</option>
    <option value="Info D">Info D</option>
    <option value="Info E">Info E</option>
    <option value="Info F">Info F</option>
    <option value="Info G">Info G</option>
    <option value="Info H">Info H</option>
    <option value="Info I">Info I</option>
    <option value="Agro A">Agro A</option>
    <option value="Agro B">Agro B</option>
    <option value="Agro C">Agro C</option>
    <option value="Agro D">Agro D</option>
    <option value="Agro E">Agro E</option>
    <option value="Agro F">Agro F</option>
    <option value="Agro G">Agro G</option>
    <option value="Agro H">Agro H</option>
    <option value="Agro I">Agro I</option>
    <option value="Agro J">Agro J</option>
    <option value="Agro k">Agro K</option>
    <option value="Agro L">Agro L</option>
    <option value="Alim A">Alim A</option>
    <option value="Alim B">Alim B</option>
    <option value="Alim C">Alim C</option>

      </select>

      <button type="submit" class="submit-btn">Cadastrar</button>
      <div class="switch-link" id="toLogin">Já tem conta? Login</div>
    </form>
  </div>
</div>

<script>
  const container = document.getElementById('container');
  const toSignup = document.getElementById('toSignup');
  const toLogin = document.getElementById('toLogin');
  const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');
  const leftTitle = document.getElementById('leftTitle');
  const leftText = document.getElementById('leftText');

  toSignup.addEventListener('click', () => {
    container.classList.add('signup-mode');
    loginForm.style.display = 'none';
    signupForm.style.display = 'flex';
    leftTitle.innerText = "Crie sua conta!";
    leftText.innerText = "Preencha os dados e comece agora.";
  });

  toLogin.addEventListener('click', () => {
    container.classList.remove('signup-mode');
    signupForm.style.display = 'none';
    loginForm.style.display = 'flex';
    leftTitle.innerText = "Seja bem-vindo de volta!";
    leftText.innerText = "Acesse com sua conta para continuar sua jornada.";
  });
</script>

</body>
</html>
