<?php
session_start();

if (!isset($_SESSION['funcao'])) {
    header('Location: index.php');
    exit;
}

echo htmlspecialchars($_SESSION['funcao'], ENT_QUOTES, 'UTF-8');
$funcao = $_SESSION['funcao'];
if ($funcao == 'Administrador') {
    echo "<a href='sgai.php' style='color: red; text-decoration: none; font-weight: bold'>PAGINA PRINCIPAL</a>"; //Remove sublinhado.
    echo "<b> | </b>";
    echo "<a href='usuarios.php' style='color: red; text-decoration: none; font-weight: bold'>USUARIOS</a>";
    echo "<b> | </b>";
    echo "<a href='operadores.php' style='color: red; text-decoration: none; font-weight: bold'>OPERADORES</a>";
    echo "<b> | </b>";
    echo "<a href='ativos.php' style='color: red; text-decoration: none; font-weight: bold'>ATIVOS</a>";
    echo "<b> | </b>";
    echo "<a href='movimentacoes.php' style='color: red; text-decoration: none; font-weight: bold'>MOVIMENTAÇÕES</a>";
    echo "<b> | </b>";
    echo "<a href='setores.php' style='color: red; text-decoration: none; font-weight: bold'>SETORES</a>";
} elseif ($funcao == 'Gerencia') {
    echo "<a href='sgai.php' style='color: black; text-decoration: none; font-weight: bold'>PAGINA PRINCIPAL</a>"; //Remove sublinhado.
    echo "<b> | </b>";
    echo "<a href='operadores.php' style='color: black; text-decoration: none; font-weight: bold'>OPERADORES</a>";
    echo "<b> | </b>";
    echo "<a href='setores.php' style='color: red; text-decoration: none; font-weight: bold'>SETORES</a>";
    echo "<b> | </b>";
    echo "<a href='movimentacoes.php' style='color: red; text-decoration: none; font-weight: bold'>MOVIMENTAÇÕES</a>";
    echo "<b> | </b>";
    echo "<a href='usuarios.php' style='color: red; text-decoration: none; font-weight: bold'>USUARIOS</a>";
    echo "<b> | </b>";
} else {
    echo "<a href='sgai.php' style='color: red; text-decoration: none; font-weight: bold'>PAGINA PRINCIPAL</a>"; //Remove sublinhado.
    echo "<b> | </b>";
    echo "<a href='setores.php' style='color: red; text-decoration: none; font-weight: bold'>SETORES</a>";
    echo "<b> | </b>";
    echo "<a href='movimentacoes.php' style='color: black; text-decoration: none; font-weight: bold'>MOVIMENTAÇÕES</a>";
    echo "<b> | </b>";
    echo "<a href='operadores.php' style='color: black; text-decoration: none; font-weight: bold'>OPERADORES</a>";
    echo "<b> | </b>";
}
