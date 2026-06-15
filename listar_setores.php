<?php

session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

include 'conecta.php';

$nome_logado = $_SESSION['nome'];



/* =========================================
   BUSCA SETORES
========================================= */

$sql = "SELECT * FROM setores
        ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$setores = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Listagem de Setores</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ddd7d7;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* TOPO */

        .topo {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        /* BANNER */

        .banner {
            width: 100%;
            height: auto;
            display: block;
        }

        /* HEADER */

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
            z-index: 10;
        }

        .header a {
            color: #ddd1d1;
            text-decoration: none;
            font-weight: bold;
        }

        .header a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        /* MENU */

        .menu-container {
            background-color: #ffffff;
            padding: 12px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .menu-links {
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .menu-item {
            color: #1900ff;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }

        .menu-item:hover {
            opacity: 0.7;
            color: #cc0000;
        }

        /* CARD */

        .card-setores {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .titulo {
            font-weight: bold;
            margin-bottom: 25px;
        }

        table {
            background: white;
        }
    </style>

<link rel="stylesheet" href="css/animacoes.css">
</head>

<body>

    <!-- TOPO -->

    <div class="topo">

        <img src="imagens/banner final.jpg" class="banner" alt="Banner">

        <div class="header">

            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                class="bi bi-person-circle" viewBox="0 0 16 16">

                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />

                <path fill-rule="evenodd"
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
            </svg>

            <span>
                <b><?= htmlspecialchars($nome_logado) ?></b>
            </span>

            <span style="opacity:0.5;">|</span>

            <a href="sair.php">
                SAIR
            </a>

        </div>

    </div>

    <!-- MENU -->

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
                        &nbsp;| Cadastro de Setores da Industria
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

    <!-- CONTAINER -->

    <div class="container">

        <div class="card-setores">

            <h2 class="titulo">
                LISTAGEM DE SETORES
            </h2>

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>NOME</th>
                        <th>DESCRIÃ‡ÃƒO</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    if (count($setores) > 0) {

                        foreach ($setores as $setor) {

                    ?>

                            <tr>

                                <td>
                                    <?= $setor['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($setor['nome']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($setor['descricao']) ?>
                                </td>

                            </tr>

                        <?php

                        }
                    } else {

                        ?>

                        <tr>

                            <td colspan="3" class="text-center text-danger fw-bold">

                                NENHUM SETOR CADASTRADO

                            </td>

                        </tr>

                    <?php
                    }
                    ?>

                </tbody>

            </table>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
