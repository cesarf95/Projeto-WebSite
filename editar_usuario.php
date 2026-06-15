<?php

// =========================================
// IMPORTA CONEXÃO COM O BANCO
// =========================================
include 'conecta.php';

// =========================================
// RECEBE DADOS DO FORMULÁRIO
// =========================================
// ID e dados do operador que será atualizado
$id           = $_POST['id'];
$nome         = $_POST['nome'];
$cpf          = $_POST['cpf'];
$funcao       = $_POST['funcao'];
$genero       = $_POST['genero'];
$login        = $_POST['login'];

try {
    // =========================================
    // COMANDO SQL UPDATE (Sintaxe Corrigida)
    // =========================================
    $sql = "UPDATE usuarios 
            SET nome = :nome,
                funcao = :funcao,
                genero = :genero,
                login =:login,
                cpf = :cpf
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    // Bind correto de todos os parâmetros com seus devidos placeholders
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':funcao', $funcao);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':cpf', $cpf);

    // =========================================
    // EXECUTA O UPDATE
    // =========================================
    $stmt->execute();

    // =========================================
    // REDIRECIONA PARA A LISTAGEM CORRETA
    // =========================================
    // Ajustado para voltar para 'usuarios.php' que é onde está sua tabela principal
    header('Location: usuarios.php?status=sucesso');
    exit;
} catch (PDOException $e) {
    echo "Erro ao atualizar dados: " . $e->getMessage();
    exit;
}
