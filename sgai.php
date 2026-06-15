<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php?status=erro&msg=Acesso negado');
    exit;
}

$nome = $_SESSION['nome'];
$funcao = $_SESSION['funcao'];

// 
// CAPTURA DOS FILTROS TEMPORAIS
// 
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

// 
// INTEGRAÇÃO COM O BANCO DE DADOS (sgai)
// 
$host = 'localhost';
$db   = 'sgai';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$totalAtivos = 0;
$totalMovimentacoes = 0;
$totalOperadores = 0;
$totalSetores = 0;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Preparação dos parâmetros com limite de horário comercial/completo do dia
    $params = [];
    if (!empty($data_inicio) && !empty($data_fim)) {
        $params[':data_inicio'] = $data_inicio . ' 00:00:00';
        $params[':data_fim'] = $data_fim . ' 23:59:59';
    }

    // 
    // 1. CONTAGEM DE ATIVOS (Filtrado por Data de Cadastro se houver filtro)
    // 
    try {
        if (!empty($data_inicio) && !empty($data_fim)) {
            $stmtAtivos = $pdo->prepare("SELECT COUNT(*) as total FROM ativos WHERE data_cadastro BETWEEN :data_inicio AND :data_fim");
            $stmtAtivos->execute($params);
        } else {
            $stmtAtivos = $pdo->query("SELECT COUNT(*) as total FROM ativos");
        }
        $totalAtivos = $stmtAtivos->fetch()['total'];
    } catch (PDOException $e) {
        $totalAtivos = 0;
    }

    // -------------------------------------------------------------------------
    // 2. CONTAGEM DE SETORES (Sempre traz o total real para não sumir da legenda)
    // -------------------------------------------------------------------------
    try {
        $stmtSetores = $pdo->query("SELECT COUNT(*) as total FROM setores");
        $totalSetores = $stmtSetores->fetch()['total'];
    } catch (PDOException $e) {
        $totalSetores = 0;
    }

    // -------------------------------------------------------------------------
    // 3. CONTAGEM DE MOVIMENTAÇÕES (Filtrado pelo período selecionado)
    // -------------------------------------------------------------------------
    try {
        if (!empty($data_inicio) && !empty($data_fim)) {
            $stmtMov = $pdo->prepare("SELECT COUNT(*) as total FROM movimentacoes WHERE data_movimentacao BETWEEN :data_inicio AND :data_fim");
            $stmtMov->execute($params);
        } else {
            $stmtMov = $pdo->query("SELECT COUNT(*) as total FROM movimentacoes");
        }
        $totalMovimentacoes = $stmtMov->fetch()['total'];
    } catch (PDOException $e) {
        // Fallback caso a coluna no banco de dados se chame apenas 'data'
        try {
            if (!empty($data_inicio) && !empty($data_fim)) {
                $stmtMov = $pdo->prepare("SELECT COUNT(*) as total FROM movimentacoes WHERE data BETWEEN :data_inicio AND :data_fim");
                $stmtMov->execute($params);
            } else {
                $stmtMov = $pdo->query("SELECT COUNT(*) as total FROM movimentacoes");
            }
            $totalMovimentacoes = $stmtMov->fetch()['total'];
        } catch (PDOException $ex) {
            $totalMovimentacoes = 0;
        }
    }

    // -------------------------------------------------------------------------
    // 4. CONTAGEM DE USUÁRIOS CADASTRADOS (Sempre traz o total real)
    // -------------------------------------------------------------------------
    try {
        $stmtUser = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $totalOperadores = $stmtUser->fetch()['total'];
    } catch (PDOException $e) {
        $totalOperadores = 0;
    }
} catch (\PDOException $e) {
    $totalAtivos = 0;
    $totalMovimentacoes = 0;
    $totalOperadores = 0;
    $totalSetores = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/animacoes.css">
    <link class="html-attribute-value" href="imagens/logo_aba.png" type="image/png">
    <link rel="icon" href="imagens/landing-page.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicionado suporte aos Bootstrap Icons para o ícone do chat -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>SGAI Gestão Industrial de Ativos Modernos</title>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Métrica', 'Quantidade'],
            ['Ativos Cadastrados', <?= (int)$totalAtivos ?>],
            ['Setores Totais', <?= (int)$totalSetores ?>],
            ['Movimentações Ativas', <?= (int)$totalMovimentacoes ?>],
            ['Usuários Cadastrados', <?= (int)$totalOperadores ?>]
        ]);

        var options = {
            title: 'Distribuição e Indicadores do Sistema SGAI',
            is3D: true,
            backgroundColor: 'transparent',
            chartArea: {
                width: '90%',
                height: '85%'
            },
            legend: {
                position: 'right',
                alignment: 'center'
            },
            colors: ['#3b82f6', '#dc2626', '#f59e0b', '#10b981']
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);

        window.addEventListener('resize', function() {
            chart.draw(data, options);
        });
    }
    </script>

    <style>
    body {
        background-color: #ffffff;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .banner {
        width: 100%;
        height: 400px;
        max-height: none;
        display: block;
    }

    .topo {
        position: relative;
    }

    .header {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: rgba(11, 43, 221, 0.88);
        padding: 8px 15px;
        border-radius: 10px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 50;
    }

    .header a {
        color: #ffffff;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.2s;
    }

    .header a:hover {
        color: #ffcccc;
        text-decoration: underline;
    }

    .faq-button {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.4);
        background-color: rgba(11, 43, 221, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        padding: 0;
    }

    .faq-button svg {
        height: 12px;
        fill: white;
    }

    .faq-button:hover {
        background-color: #2563eb;
    }

    .faq-button:hover svg {
        animation: jello-vertical 0.7s both;
    }

    @keyframes jello-vertical {
        0% {
            transform: scale3d(1, 1, 1);
        }

        30% {
            transform: scale3d(0.75, 1.25, 1);
        }

        40% {
            transform: scale3d(1.25, 0.75, 1);
        }

        50% {
            transform: scale3d(0.85, 1.15, 1);
        }

        65% {
            transform: scale3d(1.05, 0.95, 1);
        }

        75% {
            transform: scale3d(0.95, 1.05, 1);
        }

        100% {
            transform: scale3d(1, 1, 1);
        }
    }

    .tooltip-faq {
        position: absolute;
        top: -15px;
        opacity: 0;
        background-color: #1e3a8a;
        color: white;
        padding: 4px 8px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition-duration: 0.2s;
        pointer-events: none;
        letter-spacing: 0.5px;
        font-size: 11px;
        font-weight: normal;
        white-space: nowrap;
    }

    .tooltip-faq::before {
        position: absolute;
        content: "";
        width: 6px;
        height: 6px;
        background-color: #1e3a8a;
        transform: rotate(45deg);
        bottom: -3px;
        left: calc(50% - 3px);
        transition-duration: 0.3s;
    }

    .faq-button:hover .tooltip-faq {
        top: -35px;
        opacity: 1;
        transition-duration: 0.3s;
    }
    </style>
</head>

<body>

    <div class="container-fluid p-0 topo">
        <img src="imagens/banner final 4.jpg" class="banner" alt="banner">

        <div class="header">
            <button class="faq-button" onclick="window.location.href='faq.php';">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path
                        d="M80 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H144C73.3 32 16 89.3 16 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z">
                    </path>
                </svg>
                <span class="tooltip-faq">FAQ / Ajuda</span>
            </button>

            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                <path fill-rule="evenodd"
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
            </svg>

            <span><b><?= htmlspecialchars($nome) ?></b></span>
            <span style="opacity: 0.5;">|</span>
            <a href="sair.php">SAIR</a>
        </div>
    </div>

    <div class="w-full flex justify-center my-12">
        <nav class="relative mb-10 group w-full max-w-[1200px] h-40 flex items-center justify-center">
            <a href="sgai.php"
                class="relative w-16 h-16 bg-[#3b82f6] text-white rounded-full flex items-center justify-center shadow-xl transition-all duration-300 transform group-hover:scale-110 z-50 hover:bg-[#2563eb]"
                title="Início">
                <svg class="w-8 h-8 transition-transform duration-500 ease-in-out group-hover:rotate-45" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>

            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex justify-center items-center z-40 transition-all duration-500">
                <!-- Botão Central: Dashboard (abre o painel) -->
                <button type="button" onclick="toggleDashboard()"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:-translate-x-[480px] transition-all duration-500"
                    title="Dashboard">
                    <div
                        class="w-12 h-12 bg-blue-500 text-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-blue-600">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="14" width="3.5" height="7" rx="0.5" />
                            <rect x="8" y="11" width="3.5" height="10" rx="0.5" />
                            <rect x="13" y="8" width="3.5" height="13" rx="0.5" />
                            <rect x="18" y="4" width="3.5" height="17" rx="0.5" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Dashboard</span>
                </button>
                <a href="setores.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:-translate-x-[360px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[50ms]"
                    title="Setores">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L3 9v11a2 2 0 002 2h4v-7h6v7h4a2 2 0 002-2V9L12 2z"></path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Setores</span>
                </a>

                <a href="ativos.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:-translate-x-[240px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[100ms]"
                    title="Ativos">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Ativos</span>
                </a>

                <a href="movimentacoes.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:-translate-x-[120px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[150ms]"
                    title="Movimentações">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Movimentações</span>
                </a>

                <a href="operadores.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-[120px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[200ms]"
                    title="Operadores">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Operadores</span>
                </a>

                <?php if ($funcao == 'Administrador'): ?>
                <a href="usuarios.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-[240px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[250ms]"
                    title="Usuários">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Usuários</span>
                </a>
                <?php endif; ?>

                <a href="chat.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-[360px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[300ms]"
                    title="Chat">
                    <div
                        class="w-12 h-12 bg-emerald-500 text-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-emerald-600">
                        <i class="bi bi-chat-dots-fill text-white text-lg"></i>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Chat</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Container do Dashboard -->
    <div id="dashboard-container"
        class="container hidden transition-all duration-500 opacity-0 transform translate-y-4 mb-12">
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-3 h-6 bg-emerald-500 rounded-full inline-block"></span>
                    Painel Indicador de Ativos Industriais
                </h2>
                <button onclick="toggleDashboard()"
                    class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition-colors">
                    Fechar [X]
                </button>
            </div>

            <div
                class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-wrap justify-between items-center gap-4">
                <div class="flex flex-wrap gap-2">
                    <button type="button"
                        class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full hover:bg-blue-100 hover:text-blue-600 transition-all"
                        onclick="definirPeriodo('hoje')">Hoje</button>
                    <button type="button"
                        class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full hover:bg-blue-100 hover:text-blue-600 transition-all"
                        onclick="definirPeriodo('7dias')">Últimos 7 dias</button>
                    <button type="button"
                        class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full hover:bg-blue-100 hover:text-blue-600 transition-all"
                        onclick="definirPeriodo('30dias')">Últimos 30 dias</button>
                    <button type="button"
                        class="px-3 py-1 text-xs font-semibold bg-red-50 text-red-600 rounded-full hover:bg-red-100 transition-all"
                        onclick="limparFiltro()">Limpar Filtro</button>
                </div>

                <form action="sgai.php" method="GET" id="formFiltroData" class="flex flex-wrap items-center gap-3 m-0">
                    <input type="hidden" name="dashboard" value="aberto">
                    <div class="flex items-center gap-2">
                        <label for="data_inicio" class="text-xs font-bold text-gray-400 uppercase">De:</label>
                        <input type="date" id="data_inicio" name="data_inicio"
                            value="<?= htmlspecialchars($data_inicio) ?>"
                            class="form-control form-control-sm rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            style="width:145px; height:32px;">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="data_fim" class="text-xs font-bold text-gray-400 uppercase">Até:</label>
                        <input type="date" id="data_fim" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>"
                            class="form-control form-control-sm rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            style="width:145px; height:32px;">
                    </div>
                    <button type="submit"
                        class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1">
                        Filtrar
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-sm text-gray-400 font-medium uppercase">Ativos Cadastrados</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= number_format($totalAtivos, 0, ',', '.') ?></p>
                    <span class="text-xs text-emerald-500 font-semibold">No período selecionado</span>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-sm text-gray-400 font-medium uppercase">Setores Totais</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= number_format($totalSetores, 0, ',', '.') ?>
                    </p>
                    <span class="text-xs text-purple-500 font-semibold">Total Geral Cadastrado</span>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-sm text-gray-400 font-medium uppercase">Movimentações Ativas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        <?= number_format($totalMovimentacoes, 0, ',', '.') ?></p>
                    <span class="text-xs text-blue-500 font-semibold">No período selecionado</span>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-sm text-gray-400 font-medium uppercase">Usuários Cadastrados</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= number_format($totalOperadores, 0, ',', '.') ?>
                    </p>
                    <span class="text-xs text-gray-500 font-semibold">Total Geral no Banco</span>
                </div>
            </div>

            <div
                class="bg-white rounded-xl border border-gray-200 p-6 flex flex-col items-center justify-center min-h-[450px]">
                <div id="piechart_3d" class="w-full h-[400px]"></div>
            </div>
        </div>
    </div>

    <script>
    function definirPeriodo(tipo) {
        const hoje = new Date();
        let dataInicio = new Date();

        if (tipo === 'hoje') {
            dataInicio = hoje;
        } else if (tipo === '7dias') {
            dataInicio.setDate(hoje.getDate() - 7);
        } else if (tipo === '30dias') {
            dataInicio.setDate(hoje.getDate() - 30);
        }

        const formatarData = (d) => {
            const ano = d.getFullYear();
            const mes = String(d.getMonth() + 1).padStart(2, '0');
            const dia = String(d.getDate()).padStart(2, '0');
            return `${ano}-${mes}-${dia}`;
        };

        document.getElementById('data_inicio').value = formatarData(dataInicio);
        document.getElementById('data_fim').value = formatarData(hoje);
        document.getElementById('formFiltroData').submit();
    }

    function limparFiltro() {
        document.getElementById('data_inicio').value = '';
        document.getElementById('data_fim').value = '';
        document.getElementById('formFiltroData').submit();
    }

    function toggleDashboard() {
        const container = document.getElementById('dashboard-container');

        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('opacity-0', 'translate-y-4');
                container.classList.add('opacity-100', 'translate-y-0');
                if (typeof drawChart === 'function') {
                    drawChart();
                }
            }, 20);
        } else {
            container.classList.remove('opacity-100', 'translate-y-0');
            container.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => {
                container.classList.add('hidden');
            }, 500);
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('dashboard') === 'aberto') {
            const container = document.getElementById('dashboard-container');
            container.classList.remove('hidden', 'opacity-0', 'translate-y-4');
            container.classList.add('opacity-100', 'translate-y-0');
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>