<?php
session_start();

// Definir credenciais do administrador
$adminUsername = "admin"; // Nome de usuário do administrador
$adminPassword = "admin123"; // Senha do administrador

// Senhas predefinidas (para os participantes)
$senhasValidas = [
    "teste1",
    "trucoPede6",
    "Yugi09",
    "Pik@chu007",
    "senhaCHata"
];

// Verificar se o formulário de login foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verificar se as credenciais são do administrador
    if ($username === $adminUsername && $password === $adminPassword) {
        $numeroDeParticipantes = (int)$_POST["numero_participantes"];

        // Definção do número de participantes
        if ($_SESSION["REQUEST_METHOD"] == "POST") {
            $numJogadores = (int)$_POST["numero_participantes"];

            if ($numJogadores > 0) {
                $_SESSION['numero_participantes'] = $numJogadores;
                $_SESSION['paticipantes_on'] = 0;
                echo "<script>alert('Número de participantes estabelecidos');</script>";
            } else {
                echo "<script>alert('Número inválido');</script>";
            }
        }

        // Validar o número de participantes
        if ($numeroDeParticipantes >= 1 && $numeroDeParticipantes <= 13) {
            $_SESSION['admin'] = true; // Definir que o admin está logado
            $_SESSION['numeroParticipantes'] = $numeroDeParticipantes;
            header("Location: ADsenha.php"); // Redireciona para a página de senhas
            exit();
        } else {
            $erro = "O número de participantes deve estar entre 1 e 13.";
        }
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['numeroParticipantes'] = $_POST['quantidade'];
    $_SESSION['senhaSala'] = $_POST['senha'];
    $_SESSION['admin_ativo'] = true;
    $_SESSION['sala_status'] = "criada";

    header("Location: teste.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin - Planning Poker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-size: cover;
        }

        header {
            position: absolute;
            top: 10px;
            font-size: 24px;
            font-weight: bold;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <header>
        <h2>Admin - Planning Poker</h2>
    </header>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-container">
                    <h2>Login Admin</h2>
                    <?php if(isset($erro)) echo "<p class='text-danger text-center'>$erro</p>"; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Nome de Usuário</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="numero_participantes">Número de participantes</label>
                            <input type="number" class="form-control" id="numero_participantes" name="numero_participantes" min="2" max="13" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                             </br>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
