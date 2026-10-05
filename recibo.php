<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

$cargos = [
    "Deputado estadual",
    "Deputado federal",
    "1° Senador",
    "2° Senador",
    "Governador",
    "Presidente"
];

$candidatos = [
    0 => [
        "13133" => ["nome" => "Thomas o carro", "partido" => "PT", "foto" => "assets/thomas.jpg"],
        "50789" => ["nome" => "barney", "partido" => "PSOL", "foto" => "assets/barney.jpg"],
        "50000" => ["nome" => "Seu madruga", "partido" => "Vila do chaves", "foto" => "assets/madruga.jpg"]
    ],
    1 => [
        "7030" => ["nome" => "Manoel Gomes", "partido" => "Avante", "foto" => "assets/manoel.jpg"],
        "2210" => ["nome" => "Sonic", "partido" => "Partido rapido", "foto" => "assets/sonic.jpg"],
        "2121" => ["nome" => "Chaves", "partido" => "Partido presuntada", "foto" => "assets/chaves.jpg"]
    ],
    2 => [
        "400" => ["nome" => "Cachorro frio", "partido" => "latidos", "foto" => "assets/cachorro.jpg"],
        "155" => ["nome" => "Seu madruga", "partido" => "Vila do chaves", "foto" => "assets/madruga.jpg"]
    ],
    3 => [
        "222" => ["nome" => "obama", "partido" => "PL", "foto" => "assets/obama.jpg"],
        "155" => ["nome" => "Mário", "partido" => "MDB", "foto" => "assets/mario.jpg"]
    ],
    4 => [
        "10" => ["nome" => "Cara comum", "partido" => "Humanos", "foto" => "assets/cara.jpg"],
        "13" => ["nome" => "kratos", "partido" => "PT", "foto" => "assets/kratos.jpg"],
        "45" => ["nome" => "Sart Bimpson", "partido" => "PSDB", "foto" => "assets/barto.jpg"]
    ],
    5 => [
        "22" => ["nome" => "Jair Bolsonaro", "partido" => "PL", "foto" => "assets/bolsonaro.jpg"],
        "13" => ["nome" => "Luiz Inácio Lula da Silva", "partido" => "PT", "foto" => "assets/lula.jpg"],
        "15" => ["nome" => "Marx", "partido" => "PCDB", "foto" => "assets/marx.jpg"]
    ]
];

$votos = $_SESSION['votos'] ?? [];
$temVotos = !empty($votos);

$dataEmissao = date('d/m/Y');
$horaEmissao = date('H:i:s');
$dataHoraCompleta = date('d/m/Y H:i:s');
$protocolo = date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8)) . '-' . rand(1000, 9999);
$zona = "001";
$secao = "0123";
$hashValidacao = strtoupper(substr(hash('sha256', json_encode($votos) . $protocolo), 0, 16));

if (isset($_POST['acao']) && $_POST['acao'] === 'novo') {
    $_SESSION['votos'] = [];
    $_SESSION['etapa'] = -1;
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Votação - Justiça Eleitoral</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="recibo.css">
</head>

<body>

    <div class="recibo-wrapper">

        <div class="recibo-papel-real" id="areaRecibo">

            <div class="recibo-header">
                <img src="assets/brasaosemf.png" alt="Brasão" onerror="this.style.display='none'">
                <h1>JUSTIÇA ELEITORAL</h1>
                <h2>RECIBO DE VOTAÇÃO</h2>
                <div class="subtitulo">Eleição Simulada - Comprovante Oficial</div>
            </div>

            <div class="info-emissao">
                <div>
                    <span>Data de Emissão</span>
                    <strong><?php echo $dataEmissao; ?></strong>
                </div>
                <div>
                    <span>Hora de Emissão</span>
                    <strong><?php echo $horaEmissao; ?></strong>
                </div>
                <div>
                    <span>Zona</span>
                    <strong><?php echo $zona; ?></strong>
                </div>
                <div>
                    <span>Seção</span>
                    <strong><?php echo $secao; ?></strong>
                </div>
                <div class="info-protocolo">
                    <span>Protocolo</span>
                    <strong><?php echo $protocolo; ?></strong>
                </div>
                <div class="info-protocolo">
                    <span>Data e Hora Completa</span>
                    <strong><?php echo $dataHoraCompleta; ?></strong>
                </div>
            </div>

            <div class="votos-lista">
                <h3>Votos Registrados</h3>

                <?php if ($temVotos): ?>
                    <?php foreach ($cargos as $i => $cargo): ?>
                        <?php
                        $num = $votos[$i] ?? 'BRANCO';
                        $cand = null;
                        if ($num !== 'BRANCO' && isset($candidatos[$i][$num])) {
                            $cand = $candidatos[$i][$num];
                        }
                        ?>
                        <div class="voto-item">
                            <div>
                                <div class="voto-cargo"><?php echo htmlspecialchars($cargo); ?></div>
                                <div class="voto-numero">Nº <?php echo htmlspecialchars($num); ?></div>
                            </div>

                            <?php if ($cand): ?>
                                <div class="voto-candidato">
                                    <div class="voto-candidato-info">
                                        <strong><?php echo htmlspecialchars($cand['nome']); ?></strong>
                                        <small><?php echo htmlspecialchars($cand['partido']); ?></small>
                                    </div>
                                    <img src="<?php echo htmlspecialchars($cand['foto']); ?>" alt="" onerror="this.style.display='none'">
                                </div>
                            <?php else: ?>
                                <div class="voto-branco-label">BRANCO</div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="aviso-sem-voto">
                        <p>Nenhum voto encontrado na sessão atual.</p>
                        <p>Volte e realize a votação.</p>
                    </div>
                <?php endif; ?>

            </div>

            <div class="recibo-footer">
                <div class="hash">HASH: <?php echo $hashValidacao; ?></div>
                <p style="margin-top:8px; font-weight:bold; color:#111;">VOTAÇÃO ENCERRADA COM SUCESSO</p>
            </div>

        </div>

        <div class="acoes-recibo">
            <button class="btn-print" onclick="imprimirRecibo()">IMPRIMIR RECIBO</button>
            <button class="btn-pdf" onclick="baixarPDF()">SALVAR EM PDF</button>
            <button class="btn-txt" onclick="baixarTXT()">BAIXAR TXT</button>
            <a class="btn-voltar" href="index.php">VOLTAR</a>
            <form method="POST" style="grid-column: 1 / -1; margin:0;">
                <input type="hidden" name="acao" value="novo">
                <button type="submit" class="btn-novo" style="width:100%;">NOVO VOTO</button>
            </form>
        </div>

    </div>

    <script>
        const cargos = <?php echo json_encode($cargos, JSON_UNESCAPED_UNICODE); ?>;
        const votosData = <?php echo json_encode($votos, JSON_UNESCAPED_UNICODE); ?>;
        const candidatosData = <?php echo json_encode($candidatos, JSON_UNESCAPED_UNICODE); ?>;
        const dataEmissao = "<?php echo $dataEmissao; ?>";
        const horaEmissao = "<?php echo $horaEmissao; ?>";
        const protocolo = "<?php echo $protocolo; ?>";
        const hashValidacao = "<?php echo $hashValidacao; ?>";

        function imprimirRecibo() {
            window.print();
        }

        function baixarPDF() {
            window.print();
        }

        function baixarTXT() {
            let conteudo = "";
            conteudo += "JUSTIÇA ELEITORAL - RECIBO DE VOTAÇÃO\n";
            conteudo += "=====================================\n\n";
            conteudo += "Eleição Simulada - Comprovante Oficial\n\n";
            conteudo += `Data de Emissão: ${dataEmissao}\n`;
            conteudo += `Hora de Emissão: ${horaEmissao}\n`;
            conteudo += `Protocolo: ${protocolo}\n`;
            conteudo += `Hash Validação: ${hashValidacao}\n\n`;
            conteudo += "VOTOS REGISTRADOS\n";
            conteudo += "-----------------\n";

            cargos.forEach((cargo, i) => {
                let num = votosData[i] || "BRANCO";
                let linha = `${cargo}: Nº ${num}`;
                if (num !== "BRANCO" && candidatosData[i] && candidatosData[i][num]) {
                    let cand = candidatosData[i][num];
                    linha += ` - ${cand.nome} (${cand.partido})`;
                } else if (num === "BRANCO") {
                    linha += " - VOTO BRANCO";
                }
                conteudo += linha + "\n";
            });

            const blob = new Blob([conteudo], {
                type: "text/plain;charset=utf-8"
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `recibo-votacao-${protocolo}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>

</body>

</html>