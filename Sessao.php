<?php
session_start();

// Verificar se o admin está logado e se o número de participantes está definido
if (!isset($_SESSION['numeroParticipantes'])) {
    header("Location: Usuario.php");
    exit();
}

// Pegar o número de participantes da sessão
$numeroDeParticipantes = $_SESSION['numeroParticipantes'];

// Simular lista de jogadores conectados
if (!isset($_SESSION['participantes_on'])) {
    $_SESSION['participantes_on'] = 0; 
}

// Contagem dos jogadores conectados
$_SESSION['participantes_on']++;

// Exibir aviso até todos os jogadores entrar
if ($_SESSION['participantes_on'] < $numeroDeParticipantes) {
    echo "<h2>Aguardando outros participantes... ({$_SESSION['participantes_on']}/$numeroDeParticipantes)</h2>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Planning Poker</title>
    <style>
        body { 
              font-family: Arial, sans-serif; 
              text-align: center;
              background-color: #000; 
        }

        h1 {
            color: rgb(0, 89, 255);
            text-shadow: 2px 2px 2px #ddd;
        }

        /* Estilo da mesa redonda */
        .mesa {
            position: relative;
            width: 400px;
            height: 400px;
            margin: 50px auto;
            border-radius: 50%;
            background-color:rgb(52, 150, 242);
            overflow: hidden;
            border: 2px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cartas {
            position: absolute;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .carta {
            width: 50px;
            height: 80px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.5s ease-in-out;
        }

        .carta img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .carta.virada img {
            transform: rotateY(180deg);
        }

        .resultado {
            margin-top: 20px;
            background: #f0f0f0;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #ddd;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            display: none;
        }

        .btn {
            margin-top: 10px;
        }

        .spectator {
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <h1>Planning Poker</h1>

    <!-- Visor para exibir os resultados -->
    <div class="resultado">
        <h2>Resultado</h2>
        <div class="resultado-informacao"></div>
    </div>

    <!-- A Mesa -->
    <div class="mesa">
        <div class="cartas">
            <script>
                // Cartas com o nome e horas associadas
                const cartas = [
                    { nome: 'cardA.png', pontos: 1, horas: 1 },
                    { nome: 'card2.jpg', pontos: 2, horas: 3 },
                    { nome: 'card3.jpg', pontos: 3, horas: 6 },
                    { nome: 'card5.jpg', pontos: 5, horas: 9 },
                    { nome: 'card8.jpg', pontos: 8, horas: 12 },
                ];

                let votos = []; // Array para armazenar os votos dos participantes
                let votosRealizados = 0;
                let totalVotos = <?php echo $numeroDeParticipantes; ?>;
                let modoEspectador = false;
                let votou = false; // Impedir mútiplos votos

                // Calcular a posição das cartas na mesa redonda
                const numeroDeCartas = cartas.length;
                const anguloEntreCartas = 360 / numeroDeCartas;

                // Gerar as cartas na tela
                cartas.forEach((carta, index) => {
                    const cartaElemento = document.createElement('div');
                    cartaElemento.classList.add('carta');
                    cartaElemento.onclick = () => votar(carta.pontos, cartaElemento, index);
                    cartaElemento.innerHTML = `<img src="Images/${carta.nome}" alt="Carta">`;
                    document.querySelector('.cartas').appendChild(cartaElemento);

                    // Posicionar as cartas em círculo
                    const angle = anguloEntreCartas * index;
                    cartaElemento.style.transform = `translate(-50%, -50%) rotate(${angle}deg) translateY(-150px)`;

                });

                // Função para registrar o voto
                function votar(pontos, cartaElemento, index) {
                    if (modoEspectador) {
                        alert("Você está no modo espectador, aguarde os outros participantes.");
                        return;
                    }

                    // Verificação de voto do participante
                    // if (votos.includes(pontos)) 
                       if (votou) {
                        alert("Você já votou.");
                        return;
                    }

                    // Registro de votação
                    votos.push(pontos);
                    votosRealizados++;
                    votou = true; 

                    // Mover carta para a mesa (centralizar)
                    cartaElemento.style.transform = `translate(-50%, -50%) rotate(${anguloEntreCartas * index}deg) translateY(0px)`;
                    cartaElemento.style.opacity = '0.5';

                    // Virar a carta
                    cartaElemento.classList.add('virada');
                    
                    // // Mudar para o modo espectador
                    // ativarModoEspectador(cartaElemento);

                    // Verifica se todos votaram
                    if (votosRealizados === totalVotos) {
                        calcularResultado();
                    }

                    // function ativarModoEspectador(cartaElemento) {
                    //     modoEspectador = true;
                    //     cartaElemento.classList.add('spectator');
                    // }
                }

                // Função para calcular o resultado
                function calcularResultado() {
                    // Contar a quantidade de votos para cada número
                    let contagem = {};
                    votos.forEach(voto => {
                        contagem[voto] = (contagem[voto] || 0) + 1;
                    });

                    // Encontrar o número mais repetido (moda)
                    let moda = Object.keys(contagem).reduce((a, b) => contagem[a] > contagem[b] ? a : b);

                    // Calcular a média dos votos
                    let soma = votos.reduce((acc, curr) => acc + curr, 0);
                    let media = soma / votos.length;

                    // Exibir o resultado
                    document.querySelector(".resultado").style.display = 'block';
                    document.querySelector(".resultado-informacao").innerHTML = `
                        <p><strong>Mais votado:</strong> Carta com ${moda} pontos</p>
                        <p><strong>Média dos Votos:</strong> ${media.toFixed(2)} pontos</p>
                        <p><strong>Tempo Estimado:</strong> ${calcularTempoEstimado(moda)}</p>
                    `;

                    // Desabilita as cartas após o resultado
                    document.querySelectorAll('.carta').forEach(carta => {
                        carta.style.pointerEvents = 'none';
                    });
                }

                // Função para calcular os pontos efetivos com base na carta escolhida
                function calcularTempoEstimado(moda) {
                    // Buscar as horas associadas ao valor de pontos
                    let cartaEscolhida = cartas.find(carta => carta.pontos == moda);
                    return cartaEscolhida.horas;
                }

                // Função para salvar os dados no LocalStorage
                function salvarResultado() {
                    const resultado = {
                        votos: votos,
                        resultado: document.querySelector(".resultado-informacao").innerHTML
                    };
                    localStorage.setItem("resultadoFinal", JSON.stringify(resultado));
                    alert("Resultado salvo");
                }

                // Função para excluir dados
                function excluirResultado() {
                    localStorage.removeItem("resultadoFinal");
                    alert("Resultado excluído");
                    document.querySelector(".resultado-informacao").innerHTML = "";
                }

                function resetarVotacao() {
                location.reload();
            }

            function limparSessaoESair() {
                fetch ('Reset.php', { method: 'POST'})
                .then (response => response.text())
                .then (data => {
                    alert (data);
                    window.location.href = "Usuario.php";
                });
            }
            </script>
        </div>
    </div>

    <div class="btn">
        <button class="btn btn-primary mt-3" onclick="resetarVotacao()">Voltar</button>
    </div>
    <div class="btn">
        <button class="btn btn-danger mt-3" onclick="limparSessaoESair()">Fim da Sessão</button>
    </div>

</body>
</html>



