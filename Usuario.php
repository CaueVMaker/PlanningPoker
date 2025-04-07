<?php
session_start();

// Aviso da quantidade de participantes
if (!isset($_SESSION['numeroParticipantes'])) {
    echo "<script>alert('Aguardando o Adminstrador.);</script>";
    exit();
}

// Senhas predefinidas
$senhasValidas = [
    "teste1",
    "trucoPede6",
    "Yugi09",
    "Pik@chu007",
    "senhaCHata"
];

// Simulação de login com verificação de senha válida
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["name"];
    $password = $_POST["password"];

    if (in_array($password, $senhasValidas)) {
        $_SESSION['numero_participantes'] = (int)$_POST["numero_participantes"];
        // exit("Redirecionando para teste.php");
        header("Location: Sessao.php");
        exit();
    } else {
        $erro = "Senha inválida. Solicite ao responsável pela sala.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Planning Poker</title>
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
        <h2>Planning Poker</h2>
    </header>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-container">
                    <h2>Login</h2>
                    <?php if(isset($erro)) echo "<p class='text-danger text-center'>$erro</p>"; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <!-- <div class="form-group">
                            <label for="numero_participantes">Número de participantes</label>
                            <input type="number" class="form-control" id="numero_participantes" name="numero_participantes" required>
                        </div> -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($_GET['admin'])): ?>
    <div class="container mt-3">
        <div class="alert alert-info">
            <h4>Senhas disponíveis (Apenas para o responsável):</h4>
            <ul>
                <?php foreach ($senhasValidas as $senha) echo "<li>$senha</li>"; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

<script>
    function VerificarSala () {
        fetch ('verificar_sala.php')
        .then(response => response.text())
        .then(status => {
            if (status == 'criada') {
                window.location.href = 'Sessao.php';
            }
        });
    }

    setInterval (VerificarSala, 3000); 
</script>

</body>
</html>
