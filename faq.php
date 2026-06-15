<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php?status=erro&msg=Acesso negado');
    exit;
}

$nome = $_SESSION['nome'];
$funcao = $_SESSION['funcao'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="imagens/web.png" type="image/png">
    <link rel="icon" href="imagens/landing-page.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manual e FAQ - SGAI</title>

    <style>
    body {
        background-color: #f8fafc;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* TOPO E HEADER PADRÃƒO */
    .banner {
        width: 100%;
        height: 400px;
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

    /* Estilo customizado para os cards de FAQ */
    .faq-card {
        border-left: 4px solid rgba(11, 43, 221, 0.88);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .faq-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* BotÃ£o Voltar Customizado */
    .btn-voltar {
        background-color: rgba(11, 43, 221, 0.88);
        transition: background-color 0.2s;
    }

    .btn-voltar:hover {
        background-color: #2563eb;
    }
    </style>
<link rel="stylesheet" href="css/animacoes.css">
</head>

<body>

    <div class="container-fluid p-0 topo">
        <img src="imagens/banner final 4.jpg" class="banner" alt="banner">
        <link href="" rel="stylesheet" />

        <div class="header">

            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                <path fill-rule="evenodd"
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
            </svg>
            <span><b><?= htmlspecialchars($nome) ?></b></span>
            <span style="opacity: 0.5;">|</span>
            <a href="sair.php" class="text-white font-bold no-underline hover:underline hover:text-red-200">SAIR</a>
        </div>
    </div>

    <div class="container max-w-4xl mx-auto my-12 px-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800">Central de Ajuda &amp; Manual</h1>
                <p class="text-gray-500 mt-1">Descubra como operar o SGAI (GestÃ£o de Ativos Industriais)</p>
            </div>
            <a href="sgai.php"
                class="btn-voltar text-white font-semibold px-5 py-2.5 rounded-lg shadow flex items-center gap-2 no-underline text-sm transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                </svg>
                Voltar ao Ini­cio
            </a>
        </div>

        <div class="space-y-6">

            <div class="faq-card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Como navegar usando o Menu Central?</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Na tela inicial, voce vera um botão azul central com um icone de "<strong>+</strong>".
                    Ao passar o mouse por cima dele (ou tocar na tela do celular), o menu ira se expandir dinamicamente,
                    revelando os icones de acesso rapido as tabelas do sistema como
                    <span class="font-semibold text-blue-600">Inicio</span>,
                    <span class="font-semibold text-blue-600">Setores</span>,
                    <span class="font-semibold text-blue-600">Ativos</span>,
                    <span class="font-semibold text-blue-600">Usuarios</span> e
                    <span class="font-semibold text-blue-600">Operadores</span>.
                </p>
            </div>

            <div class="faq-card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">O que fazer na Ã¡rea de "Ativos"?</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Na sessão de Ativos, voce gerencia as maquinas e os equipamentos industriais. é possi­vel visualizar a
                    listagem completa, cadastrar novos itens ou modificar os ja existentes através da opção de edição.
                    Cada ativo obrigatoriamente precisa estar vinculado a um <strong>Setor</strong> e possuir um número
                    de <strong>Patrimonio</strong> valido.
                </p>
            </div>

            <div class="faq-card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Por que não consigo ver os menus "Operadores" ou
                        "Usuarios"?</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    O SGAI utiliza regras rigidas baseadas na sua funcão profissional. Atualmente seu nivel não
                    reconhecido pelo sistema como
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-400"><?= htmlspecialchars($funcao) ?></span>.
                    As telas de gerenciamento de equipes e criação de novos acessos de login são restritas apenas para
                    quem possui privilegios de <strong>Administrador e Gerencia</strong>.
                </p>
            </div>

            <div class="faq-card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Boas praticas de segurança da conta</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Para proteger as informações industriais mapeadas no banco de dados, lembre-se sempre de clicar no
                    link <span class="font-semibold text-red-600">SAIR</span> localizado no topo direito da tela ao
                    terminar suas tarefas. Isso encerra formalmente a sua sessão PHP e impede que outras pessoas acessem
                    dados confidenciais a partir deste computador.
                </p>
            </div>

        </div>

        <div class="mt-12 text-center text-sm text-gray-400 border-t pt-4">
            SGAI - Sistema de GestÃ£o de Ativos Industriais Feito por CÃ©sar &copy; <?= date('Y') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
