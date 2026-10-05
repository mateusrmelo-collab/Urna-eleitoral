<?php

session_start();

$cargos = [
    "Deputado estadual",
    "Deputado federal",
    "1° Senador",
    "2° Senador",
    "Governador",
    "Presidente"
];

$limites = [
    5,
    4,
    3,
    3,
    2,
    2
];

$candidatos = [

    0 => [
        "13133" => [
            "nome" => "Thomas o carro",
            "partido" => "PT",
            "foto" => "assets/thomas.jpg"
        ],
        "50789" => [
            "nome" => "barney",
            "partido" => "PSOL",
            "foto" => "assets/barney.jpg"
        ],
        "50000" => [
            "nome" => "Seu madruga",
            "partido" => "Vila do chaves",
            "foto" => "assets/madruga.jpg"
        ]
    ],

    1 => [
        "7030" => [
            "nome" => "Manoel Gomes",
            "partido" => "Avante",
            "foto" => "assets/manoel.jpg"
        ],
        "2210" => [
            "nome" => "Sonic",
            "partido" => "Partido rapido",
            "foto" => "assets/sonic.jpg"
        ],
        "2121" => [
            "nome" => "Chaves",
            "partido" => "Partido presuntada",
            "foto" => "assets/chaves.jpg"
        ]
    ],

    2 => [
        "400" => [
            "nome" => "Cachorro frio",
            "partido" => "latidos",
            "foto" => "assets/cachorro.jpg"
        ],
        "155" => [
            "nome" => "Seu madruga",
            "partido" => "Vila do chaves",
            "foto" => "assets/madruga.jpg"
        ]
    ],

    3 => [
        "222" => [
            "nome" => "obama",
            "partido" => "PL",
            "foto" => "assets/obama.jpg"
        ],
        "155" => [
            "nome" => "Mário",
            "partido" => "MDB",
            "foto" => "assets/mario.jpg"
        ]
    ],

    4 => [
        "10" => [
            "nome" => "Cara comum",
            "partido" => "Humanos",
            "foto" => "assets/cara.jpg"
        ],
        "13" => [
            "nome" => "kratos",
            "partido" => "PT",
            "foto" => "assets/kratos.jpg"
        ],
        "45" => [
            "nome" => "Sart Bimpson",
            "partido" => "PSDB",
            "foto" => "assets/barto.jpg"
        ]
    ],

    5 => [
        "22" => [
            "nome" => "Jair Bolsonaro",
            "partido" => "PL",
            "foto" => "assets/bolsonaro.jpg"
        ],
        "13" => [
            "nome" => "Luiz Inácio Lula da Silva",
            "partido" => "PT",
            "foto" => "assets/lula.jpg"
        ],
        "15" => [
            "nome" => "Marx",
            "partido" => "PCDB",
            "foto" => "assets/marx.jpg"
        ]
    ]
];

if (!isset($_SESSION['votos'])) {
    $_SESSION['votos'] = [];
}

if (!isset($_SESSION['etapa'])) {
    $_SESSION['etapa'] = -1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    if ($acao === 'iniciar') {

        $_SESSION['votos'] = [];
        $_SESSION['etapa'] = 0;
    }

    if ($acao === 'votar') {

        $etapa = intval($_POST['etapa'] ?? -1);
        $numero = trim($_POST['numero'] ?? '');

        if (
            $etapa >= 0 &&
            $etapa < count($cargos)
        ) {

            $valido = false;

            if ($numero === 'BRANCO') {

                $valido = true;

            } elseif (
                ctype_digit($numero) &&
                strlen($numero) === $limites[$etapa] &&
                isset($candidatos[$etapa][$numero])
            ) {

                $valido = true;
            }

            if ($valido) {

                $_SESSION['votos'][$etapa] = $numero;
                $_SESSION['etapa'] = $etapa + 1;

                if ($_SESSION['etapa'] >= count($cargos)) {
                    $_SESSION['etapa'] = count($cargos);
                }
            }
        }
    }

    if ($acao === 'novo') {

        $_SESSION['votos'] = [];
        $_SESSION['etapa'] = -1;
    }
}

$etapa = $_SESSION['etapa'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Urna Eleitoral</title>

    <link
        rel="stylesheet"
        href="style.css">

</head>

<body>

<main class="main">

    <div class="telao">

        <?php if ($etapa === -1): ?>

            <div class="tela-inicial">

                <p>
                    Aperte qualquer botão para começar
                </p>

                <div class="just">

                    <img
                        src="assets/brasaosemf.png"
                        alt="Justiça Eleitoral">

                    <h2>
                        JUSTIÇA ELEITORAL
                    </h2>

                </div>

                <button
                    class="btn-info"
                    onclick="abrirInfo()">

                    INFORMAÇÕES

                </button>

            </div>

        <?php elseif ($etapa < count($cargos)): ?>

            <div class="tela-votacao">

                <div class="cabecalho-votacao">

                    <span>
                        ELEIÇÃO SIMULADA
                    </span>

                    <strong>
                        <?php echo strtoupper($cargos[$etapa]); ?>
                    </strong>

                </div>

                <div class="area-votacao">

                    <div class="lado-esquerdo">

                        <p class="instrucao-principal">
                            DIGITE O NÚMERO DO CANDIDATO
                        </p>

                        <div
                            class="numero-tela"
                            id="numeroTela">
                        </div>

                        <p class="mensagem">
                            <?php echo $limites[$etapa]; ?>
                            dígitos
                        </p>

                        <div
                            class="candidato-info"
                            id="candidatoInfo">

                            <p>
                                Digite o número do candidato.
                            </p>

                        </div>

                    </div>

                    <div class="foto-candidato">

                        <img
                            id="fotoCandidato"
                            src=""
                            alt="Foto do candidato">

                    </div>

                </div>

            </div>

        <?php else: ?>

            <div class="comprovante">

                <div class="comprovante-topo">

                    <img
                        src="assets/brasaosemf.png"
                        alt="Justiça Eleitoral">

                    <h1>
                        JUSTIÇA ELEITORAL
                    </h1>

                    <p>
                        COMPROVANTE DE VOTAÇÃO
                    </p>

                </div>

                <h2>
                    Votos registrados
                </h2>

                <?php foreach ($cargos as $indice => $cargo): ?>

                    <?php

                    $numeroVoto =
                        $_SESSION['votos'][$indice]
                        ?? 'BRANCO';

                    $candidatoVoto = null;

                    if (
                        $numeroVoto !== 'BRANCO' &&
                        isset($candidatos[$indice][$numeroVoto])
                    ) {

                        $candidatoVoto =
                            $candidatos[$indice][$numeroVoto];
                    }

                    ?>

                    <div class="voto-comprovante">

                        <div class="cargo-comprovante">

                            <strong>
                                <?php echo $cargo; ?>
                            </strong>

                            <span>
                                Número:
                                <?php echo htmlspecialchars($numeroVoto); ?>
                            </span>

                        </div>

                        <?php if ($candidatoVoto): ?>

                            <div class="candidato-comprovante">

                                <img
                                    src="<?php echo htmlspecialchars($candidatoVoto['foto']); ?>"
                                    alt="Foto">

                                <div>

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $candidatoVoto['nome']
                                        );
                                        ?>
                                    </strong>

                                    <span>
                                        <?php
                                        echo htmlspecialchars(
                                            $candidatoVoto['partido']
                                        );
                                        ?>
                                    </span>

                                </div>

                            </div>

                        <?php else: ?>

                            <strong>
                                VOTO BRANCO
                            </strong>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

                <div class="final">

                    <p>
                        VOTAÇÃO ENCERRADA
                    </p>

                    <small>
                        Seus votos foram computados.
                    </small>

                </div>


                <form method="POST">

                    <input
                        type="hidden"
                        name="acao"
                        value="novo">

                    <button
                        class="novo-voto"
                        type="submit">

                        NOVO VOTO

                    </button>

                </form>

            </div>

        <?php endif; ?>

    </div>

</main>

<div class="right">

    <div class="td">

        <div class="logo">

            <img
                src="assets/images.jpg"
                alt="Foto">

            <div class="text">

                <h2>JUSTIÇA</h2>
                <h2>ELEITORAL</h2>

            </div>

        </div>

        <div class="botoes">

            <div class="numeros">

                <button onclick="digitar(1)">1</button>
                <button onclick="digitar(2)">2</button>
                <button onclick="digitar(3)">3</button>

                <button onclick="digitar(4)">4</button>
                <button onclick="digitar(5)">5</button>
                <button onclick="digitar(6)">6</button>

                <button onclick="digitar(7)">7</button>
                <button onclick="digitar(8)">8</button>
                <button onclick="digitar(9)">9</button>

                <button
                    class="ultimo"
                    onclick="digitar(0)">

                    0

                </button>

            </div>

            <div class="decisoes">

                <button
                    class="branco"
                    onclick="votoBranco()">

                    BRANCO

                </button>

                <button
                    class="corrige"
                    onclick="corrigir()">

                    CORRIGE

                </button>

                <button
                    class="confirma"
                    onclick="confirmar()">

                    CONFIRMA

                </button>

            </div>

        </div>

    </div>

</div>

<div class="linhas">

    <div class="linha"></div>
    <div class="linha"></div>
    <div class="linha"></div>
    <div class="linha"></div>
    <div class="linha"></div>
    <div class="linha"></div>
    <div class="linha"></div>
    <div class="linha"></div>

</div>

<div
    class="overlay"
    id="overlay">

    <div class="popup">

        <button
            class="fechar"
            onclick="fecharInfo()">

            ×

        </button>

        <h2>
            Informações da Eleição
        </h2>

        <p>
            Bem-vindo à urna eletrônica.
        </p>

        <p>
            Nesta eleição você deverá votar nos seguintes cargos:
        </p>

        <ul>

            <li>
                Deputado Estadual
            </li>

            <li>
                Deputado Federal
            </li>

            <li>
                1° Senador
            </li>

            <li>
                2° Senador
            </li>

            <li>
                Governador
            </li>

            <li>
                Presidente
            </li>

        </ul>

        <p>
            Digite o número do candidato utilizando
            o teclado numérico e pressione
            <strong>CONFIRMA</strong>.
        </p>

        <button
            class="entendi"
            onclick="fecharInfo()">

            ENTENDI

        </button>

    </div>

</div>

<script>

let numero = "";

const etapaAtual = <?php echo $etapa; ?>;

const limites = <?php echo json_encode($limites); ?>;

const candidatos = <?php echo json_encode(
    $candidatos,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
); ?>;

function digitar(valor) {

    if (etapaAtual === -1) {

        iniciar();

        return;
    }

    if (etapaAtual >= limites.length) {
        return;
    }

    if (numero === "BRANCO") {
        return;
    }

    if (numero.length >= limites[etapaAtual]) {
        return;
    }

    numero += valor;

    atualizarTela();

    verificarCandidato();
}

function atualizarTela() {

    const tela =
        document.getElementById("numeroTela");

    if (tela) {
        tela.innerText = numero;
    }
}

function verificarCandidato() {

    const info =
        document.getElementById("candidatoInfo");

    const foto =
        document.getElementById("fotoCandidato");

    if (!info || !foto) {
        return;
    }

    if (numero.length < limites[etapaAtual]) {

        info.innerHTML = `
            <p>
                Digite os ${limites[etapaAtual]}
                números do candidato.
            </p>
        `;

        foto.style.display = "none";

        return;
    }

    const candidato =
        candidatos[etapaAtual][numero];

    if (candidato) {

        info.innerHTML = `

            <div class="candidato-encontrado">

                <span class="label-candidato">
                    CANDIDATO
                </span>

                <h2>
                    ${candidato.nome}
                </h2>

                <p>
                    PARTIDO:
                    <strong>
                        ${candidato.partido}
                    </strong>
                </p>

                <p>
                    NÚMERO:
                    <strong>
                        ${numero}
                    </strong>
                </p>

            </div>
        `;

        foto.src = candidato.foto;

        foto.style.display = "block";

    } else {

        info.innerHTML = `

            <div class="candidato-invalido">

                <strong>
                    NÚMERO NÃO ENCONTRADO
                </strong>

                <p>
                    Corrija o número ou escolha BRANCO.
                </p>

            </div>

        `;

        foto.src = "";

        foto.style.display = "none";
    }
}

function corrigir() {

    if (etapaAtual === -1) {

        abrirInfo();

        return;
    }

    numero = "";

    atualizarTela();

    const info =
        document.getElementById("candidatoInfo");

    const foto =
        document.getElementById("fotoCandidato");

    if (info) {

        info.innerHTML = `
            <p>
                Digite o número do candidato.
            </p>
        `;
    }

    if (foto) {

        foto.style.display = "none";
        foto.src = "";
    }
}

function votoBranco() {

    if (etapaAtual === -1) {

        iniciar();

        return;
    }

    if (etapaAtual >= limites.length) {
        return;
    }

    numero = "BRANCO";

    atualizarTela();

    const info =
        document.getElementById("candidatoInfo");

    const foto =
        document.getElementById("fotoCandidato");

    if (info) {

        info.innerHTML = `

            <div class="voto-branco">

                <strong>
                    VOTO BRANCO
                </strong>

                <p>
                    Pressione CONFIRMA para registrar.
                </p>

            </div>
        `;
    }

    if (foto) {
        foto.style.display = "none";
    }
}

function confirmar() {

    if (etapaAtual === -1) {

        iniciar();

        return;
    }

    if (etapaAtual >= limites.length) {
        return;
    }

    if (numero === "") {

        alert(
            "Digite o número do candidato."
        );

        return;
    }

    if (numero === "BRANCO") {

        enviarVoto();

        return;
    }

    if (
        numero.length === limites[etapaAtual] &&
        candidatos[etapaAtual][numero]
    ) {

        enviarVoto();

        return;
    }

    alert(
        "Número de candidato inválido."
    );
}

function enviarVoto() {

    const form =
        document.createElement("form");

    form.method = "POST";

    const acao =
        document.createElement("input");

    acao.type = "hidden";
    acao.name = "acao";
    acao.value = "votar";

    const etapa =
        document.createElement("input");

    etapa.type = "hidden";
    etapa.name = "etapa";
    etapa.value = etapaAtual;

    const campoNumero =
        document.createElement("input");

    campoNumero.type = "hidden";
    campoNumero.name = "numero";
    campoNumero.value = numero;

    form.appendChild(acao);
    form.appendChild(etapa);
    form.appendChild(campoNumero);

    document.body.appendChild(form);

    form.submit();
}

function iniciar() {

    const form =
        document.createElement("form");

    form.method = "POST";

    const acao =
        document.createElement("input");

    acao.type = "hidden";
    acao.name = "acao";
    acao.value = "iniciar";

    form.appendChild(acao);

    document.body.appendChild(form);

    form.submit();
}

function abrirInfo() {

    document
        .getElementById("overlay")
        .classList.add("aberto");
}

function fecharInfo() {

    document
        .getElementById("overlay")
        .classList.remove("aberto");
}

document
    .getElementById("overlay")
    .addEventListener(
        "click",
        function(event) {

            if (event.target === this) {
                fecharInfo();
            }

        }
    );

document.addEventListener(
    "keydown",
    function(event) {

        if (
            event.key >= "0" &&
            event.key <= "9"
        ) {

            digitar(
                Number(event.key)
            );
        }

        if (event.key === "Enter") {
            confirmar();
        }

        if (event.key === "Backspace") {
            corrigir();
        }

    }
);


</script>

</body>

</html>
