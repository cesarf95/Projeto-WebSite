<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php?status=erro&msg=Acesso negado');
    exit;
}

include 'conecta.php';

$nome = $_SESSION['nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="imagens/logo_aba.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Cadastrar Setor</title>

    <style>
        body {
            background-color: #ddd7d7;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .banner {
            width: 100%;
            height: auto;
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
            gap: 8px;
        }

        .header a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }

        .menu-container {
            background-color: #ffffff;
            padding: 12px 20px;
        }

        .menu-links {
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .menu-item {
            color: #1a0beb;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }

        .container-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

<link rel="stylesheet" href="css/animacoes.css">
</head>

<body>

    <div class="container-fluid p-0 topo">

        <img src="imagens/banner final.jpg" class="banner" alt="banner">

        <div class="header">

            <span>
                <b><?= htmlspecialchars($nome) ?></b>
            </span>

            <span>|</span>

            <a href="sair.php">SAIR</a>

        </div>

    </div>

    <nav class="menu-container">

        <div class="container-fluid">

            <ul class="menu-links">

                <li>
                    <a href="index.php" class="menu-item">
                        Pagina Inicial
                    </a>
                </li>

                <li>
                    <a href="usuarios.php" class="menu-item">
                        &nbsp;| Cadastro de Usuarios
                    </a>
                </li>

                <li>
                    <a href="setores.php" class="menu-item">
                        &nbsp;| Cadastro de Setores da IndÃºstria
                    </a>
                </li>

                <li>
                    <a href="ativos.php" class="menu-item">
                        &nbsp;| Cadastro de Ativos
                    </a>
                </li>

                <li>
                    <a href="operadores.php" class="menu-item">
                        &nbsp;| Cadastro de Operadores
                    </a>
                </li>

            </ul>

        </div>

    </nav>

    <div class="container">

        <div class="container-form">

            <h2 class="mb-4">
                Novo Setor
            </h2>

            <form action="salvar_setor.php" method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Nome do Setor
                    </label>

                    <input type="text" name="nome" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        DescriÃ§Ã£o
                    </label>

                    <textarea name="descricao" class="form-control" rows="5"></textarea>

                </div>

                <button type="submit" class="btn btn-primary">
                    Cadastrar Setor
                </button>

                <a href="setores.php" class="btn btn-secondary">
                    Voltar
                </a>

            </form>

        </div>

    </div>


</body>

</html>
