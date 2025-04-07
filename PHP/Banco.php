<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$password = '::1'; // Altere conforme necessário
$database = 'planning_poker';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Erro ao conectar ao banco de dados']));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && isset($_POST['sessao'], $_POST['usuario'], $_POST['voto'])) {
    $sessao = $_POST['sessao'];
    $usuario = $_POST['usuario'];
    $voto = $_POST['voto'];

    $stmt = $conn->prepare('INSERT INTO votos (sessao, usuario, voto) VALUES (?, ?, ?)');
    $stmt->bind_param('ssi', $sessao, $usuario, $voto);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Voto registrado com sucesso']);
    } else {
        echo json_encode(['error' => 'Erro ao registrar voto']);
    }

    $stmt->close();
}

$conn->close();
?>
