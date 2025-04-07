<?php
session_start();

// Verificar se o admin está logado
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: Usuario.php"); // Redireciona para a página de login se não for admin
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
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Senhas - Planning Poker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <h2>Senhas Disponíveis</h2>
    </header>
    <div class="container mt-3">
        <div class="alert alert-info">
            <h4>Senhas disponíveis para os jogadores:</h4>
            <ul>
                <?php foreach ($senhasValidas as $senha) echo "<li>$senha</li>"; ?>
            </ul>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>
</body>
</html>
