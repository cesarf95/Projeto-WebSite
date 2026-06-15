<?php
session_start();

// 1. Verifica se o usuÃ¡rio estÃ¡ logado
if (!isset($_SESSION['nome'])) {
    header('Location: index.php?status=erro&msg=Acesso Negado');
    exit();
}

$nome_logado = $_SESSION['nome'];
$funcao = $_SESSION['funcao'] ?? '';

// 2. Padroniza a string para minÃºsculo para evitar erros de maiÃºsculas/minÃºsculas
$funcao_verificar = strtolower(trim($funcao));

// 3. Permite o acesso se for 'administrador', 'gerencia' ou 'admin'
if ($funcao_verificar !== 'administrador' && $funcao_verificar !== 'gerencia' && $funcao_verificar !== 'admin') {
    echo "<script>
        alert('Erro no sistema: Acesso Restrito para a funÃ§Ã£o: " . htmlspecialchars($funcao) . "');
        window.location.href='index.php';
        </script>";
    exit();
}

include 'conecta.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários - TechMetal</title>
    <link rel="icon" href="imagens/management.png" type="image/png">

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
            transition: color 0.2s;
        }

        .header a:hover {
            color: #e2e2e2;
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

    <div class="topo">
        <img src="Imagens/banner final 4.jpg" class="banner" alt="Banner TechMetal">
        <div class="header">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                <path fill-rule="evenodd"
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
            </svg>
            <span><b><?= htmlspecialchars($nome_logado) ?></b></span>
            <span style="opacity: 0.5;">|</span>
            <a href="sair.php">SAIR</a>
        </div>
    </div>

    <div class="w-full flex justify-center my-12">
        <nav class="relative mb-10 group w-full max-w-[900px] h-48 flex items-center justify-center">
            <a href="sgai.php" title="PÃ¡gina Inicial"
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
        <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalCadastrarUsuario">
            CADASTRAR NOVO USUARIO
        </button>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow border-2">
                    <div class="card-header bg-dark text-white text-center">
                        <b class="letter-spacing-1">USUARIOS DO SISTEMA</b>
                    </div>

                    <div class="card-body">
                        <?php
                        $sql = "SELECT id, nome, cpf, funcao, genero, login FROM usuarios ORDER BY nome ASC";
                        $dados = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

                        if (count($dados) > 0) {
                            echo "
                            <table class='table table-hover table-bordered align-middle'>
                                <thead class='table-dark'>
                                    <tr>
                                        <th>ID</th>
                                        <th>NOME</th>
                                        <th>CPF</th>
                                        <th>FUNÇÃO</th>
                                        <th>GENERO</th>
                                        <th>LOGIN</th>
                                        <th width='130' class='text-center'>AÇÕES</th>
                                    </tr>
                                </thead>
                                <tbody>
                            ";

                            foreach ($dados as $item) {
                                $id = $item['id'];
                                $nome_user = htmlspecialchars($item['nome']);
                                $cpf = htmlspecialchars($item['cpf']);
                                $user_funcao = htmlspecialchars($item['funcao']);
                                $genero = htmlspecialchars($item['genero']);
                                $login = htmlspecialchars($item['login']);

                                echo "
                                <tr>
                                    <td>{$id}</td>
                                    <td><strong>{$nome_user}</strong></td>
                                    <td>{$cpf}</td>
                                    <td>{$user_funcao}</td>
                                    <td>{$genero}</td>
                                    <td>{$login}</td>
                                    <td class='acoes text-center'>
                                        <a href='#' 
                                           data-bs-toggle='modal' 
                                           data-bs-target='#modalEditar'
                                           data-id='{$id}'
                                           data-nome='{$nome_user}'
                                           data-cpf='{$cpf}'
                                           data-funcao='{$user_funcao}'
                                           data-genero='{$genero}'
                                           data-login='{$login}'
                                           title='Editar'>
                                            ✍
                                        </a>
                                        <span style='color: #ccc; padding: 0 5px;'>|</span>
                                        <a href='excluir_usuario.php?id={$id}'
                                           onclick=\"return confirm('Deseja excluir este usuÃ¡rio?')\"
                                           title='Excluir'>
                                            ❌
                                        </a>
                                    </td>
                                </tr>
                                ";
                            }

                            echo "
                                </tbody>
                            </table>
                            ";
                        } else {
                            echo "
                            <div class='alert alert-danger text-center m-0'>
                                NENHUM USUARIO ENCONTRADO
                            </div>
                            ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCadastrarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">NOVO USUARIO</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="cadastro_usuario.php" method="POST">
                        <label class="form-label fw-bold">NOME COMPLETO</label>
                        <input type="text" name="nome" class="form-control mb-2" required>

                        <label class="form-label fw-bold">CPF</label>
                        <input type="text" name="cpf" class="form-control mb-2" required>

                        <label class="form-label fw-bold">FUNÇÃO</label>
                        <input type="text" name="funcao" class="form-control mb-2" required>

                        <label class="form-label fw-bold">GENERO</label>
                        <select name="genero" class="form-select mb-2">
                            <option value="Masculino">Masculino</option>
                            <option value="Feminino">Feminino</option>
                            <option value="Outro">Outro</option>
                        </select>

                        <label class="form-label fw-bold">LOGIN</label>
                        <input type="text" name="login" class="form-control mb-2" required>

                        <label class="form-label fw-bold">SENHA PRINCIPAL</label>
                        <input type="password" name="senha" class="form-control mb-3" required>

                        <input type="hidden" name="senha_secundaria" value="">

                        <button class="btn btn-success w-100 fw-bold">CADASTRAR USUARIO</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold text-dark">EDITAR USUARIO</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editar_usuario.php" method="POST">
                        <input type="hidden" name="editar" value="1">
                        <input type="hidden" name="id" id="edit_id">

                        <label class="form-label fw-bold">NOME</label>
                        <input type="text" name="nome" id="edit_nome" class="form-control mb-2" required>

                        <label class="form-label fw-bold">CPF</label>
                        <input type="text" name="cpf" id="edit_cpf" class="form-control mb-2" required>

                        <label class="form-label fw-bold">FUNÇÃO</label>
                        <input type="text" name="funcao" id="edit_funcao" class="form-control mb-2" required>

                        <label class="form-label fw-bold">GENERO</label>
                        <select name="genero" id="edit_genero" class="form-select mb-2">
                            <option value="Masculino">Masculino</option>
                            <option value="Feminino">Feminino</option>
                            <option value="Outro">Outro</option>
                        </select>

                        <label class="form-label fw-bold">LOGIN</label>
                        <input type="text" name="login" id="edit_login" class="form-control mb-2" required>

                        <label class="form-label fw-bold">NOVA SENHA PRINCIPAL (DEIXE EM BRANCO SE NÃO FOR ALTERAR)</label>
                        <input type="password" name="senha" class="form-control mb-2">

                        <label class="form-label fw-bold">NOVA SENHA SECUNDARIA (DEIXE EM BRANCO SE NÃO FOR ALTERAR)</label>
                        <input type="password" name="senha_secundaria" class="form-control mb-3">

                        <button class="btn btn-warning w-100 fw-bold text-dark">ATUALIZAR USUARIO</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var modalEditar = document.getElementById('modalEditar');
        modalEditar.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;

            document.getElementById('edit_id').value = button.getAttribute('data-id') || '';
            document.getElementById('edit_nome').value = button.getAttribute('data-nome') || '';
            document.getElementById('edit_cpf').value = button.getAttribute('data-cpf') || '';
            document.getElementById('edit_funcao').value = button.getAttribute('data-funcao') || '';
            document.getElementById('edit_genero').value = button.getAttribute('data-genero') || '';
            document.getElementById('edit_login').value = button.getAttribute('data-login') || '';
        });
    </script>

</body>

</html>
