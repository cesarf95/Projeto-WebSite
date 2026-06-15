<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

include 'conecta.php';

$nome_logado = $_SESSION['nome'];
$funcao = $_SESSION['funcao'] ?? 'Operador';

$sql = "SELECT
            m.id,
            a.nome AS ativo,
            s.nome AS setor,
            o.nome AS operador,
            m.tipo_movimentacao,
            m.data_movimentacao,
            m.observacao,
            m.created_at
        FROM movimentacoes m
        INNER JOIN ativos a ON m.ativo_id = a.id
        INNER JOIN setores s ON m.setor_id = s.id
        LEFT JOIN operadores o ON m.operador_id = o.id
        ORDER BY m.data_movimentacao DESC, m.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$movimentacoes = $stmt->fetchAll();

$sqlAtivos = "SELECT id, nome FROM ativos ORDER BY nome ASC";
$ativos = $pdo->query($sqlAtivos)->fetchAll(PDO::FETCH_ASSOC);

$sqlSetores = "SELECT id, nome FROM setores ORDER BY nome ASC";
$setores = $pdo->query($sqlSetores)->fetchAll(PDO::FETCH_ASSOC);

$sqlOperadores = "SELECT id, nome FROM operadores ORDER BY nome ASC";
$operadores = $pdo->query($sqlOperadores)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Movimentações - TechMetal</title>
    <link rel="icon" href="imagens/movimentacao-de-carga.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    body {
        background-color: #ffffff;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .topo {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .banner {
        width: 100%;
        height: 400px;
        display: block;
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
        gap: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 50;
    }

    .header a {
        color: #ffffff;
        text-decoration: none;
        font-weight: bold;
    }

    .header a:hover {
        color: #ffcccc;
        text-decoration: underline;
    }

    .table {
        background: white;
        text-transform: uppercase;
    }

    .acoes a {
        text-decoration: none;
        font-size: 20px;
    }
    </style>
<link rel="stylesheet" href="css/animacoes.css">
</head>

<body>
    <div class="container-fluid p-0 topo">
        <img src="imagens/banner final 4.jpg" class="banner" alt="Banner">
        <div class="header">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                <path fill-rule="evenodd"
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
            </svg>
            <span><b><?= htmlspecialchars($nome_logado) ?></b></span>
            <span style="opacity:0.5;">|</span>
            <a href="sair.php">SAIR</a>
        </div>
    </div>

    <div class="w-full flex justify-center my-12">
        <nav class="relative mb-10 group w-full max-w-[900px] h-48 flex items-center justify-center">
            <a href="sgai.php" title="Pagina Inicial"
                class="relative w-16 h-16 bg-[#3b82f6] text-white rounded-full flex items-center justify-center shadow-xl transition-all duration-300 transform group-hover:scale-110 z-50 hover:bg-[#2563eb]">
                <svg class="w-8 h-8 transition-transform duration-500 ease-in-out group-hover:rotate-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 9.75L12 4.5l9 5.25V19.5a1.5 1.5 0 01-1.5 1.5H13.5V14.25a1.5 1.5 0 00-3 0V21H4.5A1.5 1.5 0 013 19.5V9.75z" />
                </svg>
            </a>

            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex justify-center items-center z-40 transition-all duration-500">
                <a href="setores.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:-translate-x-[220px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[50ms]">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Setores</span>
                </a>
                <a href="ativos.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:-translate-x-[120px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[100ms]">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Ativos</span>
                </a>
                <a href="movimentacoes.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-[120px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[150ms]">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6-6 6 6" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 15l6 6 6-6" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Movimentações</span>
                </a>
                <a href="operadores.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-[220px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[200ms]">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Operadores</span>
                </a>
                <?php if ($funcao == 'Administrador'): ?>
                <a href="usuarios.php"
                    class="absolute transform -translate-x-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-[320px] transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] delay-[250ms]">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-400 hover:text-[#3b82f6] transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-700 text-center mt-2 block opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-300">Usuarios</span>
                </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <div class="text-center my-4">
        <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalCadastrar">
            CADASTRAR MOVIMENTAÇÃO
        </button>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow border-2">
                    <div class="card-header bg-dark text-white text-center">
                        <b>MOVIMENTAÇÕES INDUSTRIAIS</b>
                    </div>
                    <div class="card-body">
                        <?php if (count($movimentacoes) > 0): ?>
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>ATIVO</th>
                                    <th>SETOR</th>
                                    <th>TIPO</th>
                                    <th>DATA</th>
                                    <th>OPERADOR</th>
                                    <th>OBSERVAÃ‡ÃƒO</th>
                                    <th>REGISTRO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimentacoes as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['id']) ?></td>
                                    <td><?= htmlspecialchars($item['ativo']) ?></td>
                                    <td><?= htmlspecialchars($item['setor']) ?></td>
                                    <td><?= htmlspecialchars($item['tipo_movimentacao']) ?></td>
                                    <td><?= htmlspecialchars($item['data_movimentacao']) ?></td>
                                    <td><?= htmlspecialchars($item['operador'] ?? 'N/A') ?></td>
                                    <td><?= nl2br(htmlspecialchars($item['observacao'])) ?></td>
                                    <td><?= htmlspecialchars($item['created_at']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="alert alert-warning text-center m-0">
                            Nenhuma movimentação registrada ainda.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCadastrar" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">REGISTRAR MOVIMENTAÇÃO</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="salvar_movimentacao.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ATIVO</label>
                                <select name="ativo_id" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($ativos as $ativo): ?>
                                    <option value="<?= $ativo['id'] ?>"><?= htmlspecialchars($ativo['nome']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">SETOR</label>
                                <select name="setor_id" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($setores as $setor): ?>
                                    <option value="<?= $setor['id'] ?>"><?= htmlspecialchars($setor['nome']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">TIPO DE MOVIMENTAÇÃO</label>
                                <select name="tipo_movimentacao" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option value="Alocação">Alocação</option>
                                    <option value="Manutenção preventiva">Manutenção preventiva</option>
                                    <option value="Manutenção corretiva">Manutenção corretiva</option>
                                    <option value="Retorno ao setor">Retorno ao setor</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">DATA</label>
                                <input type="date" name="data_movimentacao" class="form-control"
                                    value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">OPERADOR RESPONSAVEL</label>
                                <select name="operador_id" class="form-select">
                                    <option value="">Não informado</option>
                                    <?php foreach ($operadores as $operador): ?>
                                    <option value="<?= $operador['id'] ?>"><?= htmlspecialchars($operador['nome']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">OBSERVAÇÃO</label>
                                <input type="text" name="observacao" class="form-control"
                                    placeholder="Detalhes da movimentação">
                            </div>
                        </div>
                        <button class="btn btn-success w-100 fw-bold">REGISTRAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
