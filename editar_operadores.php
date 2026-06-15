<?php

// =========================================
// IMPORTA CONEXÃO COM O BANCO
// =========================================
include 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // =========================================
    // RECEBE DADOS DO FORMULÁRIO
    // =========================================
    $id           = $_POST['id'] ?? '';
    $nome         = trim($_POST['nome'] ?? '');
    $funcao       = trim($_POST['funcao'] ?? '');
    $genero       = trim($_POST['genero'] ?? '');
    $data_entrada = trim($_POST['data_entrada'] ?? '');
    $cpf          = trim($_POST['cpf'] ?? '');
    // Agora recebemos a senha com segurança caso venha do formulário de edição


    if (empty($id)) {
        echo "<script>alert('ID inválido!'); window.location.href='operadores.php';</script>";
        exit();
    }

    try {
        // =========================================
        // VERIFICA SE A SENHA FOI PREENCHIDA
        // =========================================
        if (!empty($senha)) {
            // Se digitou uma nova senha, geramos o Hash e incluímos na query


            $sql = "UPDATE operadores 
                    SET nome = :nome,
                        funcao = :funcao,
                        genero = :genero,
                        data_entrada = :data_entrada,
                        cpf = :cpf,
                        senha = :senha
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            // Bind de todos os parâmetros, incluindo a nova senha criptografada
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':funcao', $funcao);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':data_entrada', $data_entrada);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':senha', $senha_hash);
        } else {
            // Se deixou a senha em branco, atualiza tudo NOMINALMENTE, menos a senha anterior
            // CORRIGIDO: Adicionado "operadores" após o UPDATE
            $sql = "UPDATE operadores 
                    SET nome = :nome,
                        cpf = :cpf,
                        funcao = :funcao,
                        genero = :genero,
                        data_entrada = :data_entrada
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            // Bind apenas dos parâmetros alterados (sem mexer na senha do banco)
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':funcao', $funcao);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':data_entrada', $data_entrada);
        }

        // =========================================
        // EXECUTA O UPDATE COM VALIDAÇÃO de SUCESSO
        // =========================================
        if ($stmt->execute()) {
            echo "<script>
                    alert('Operador atualizado com sucesso!');
                    window.location.href = 'operadores.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Erro ao atualizar operador no banco de dados!');
                    history.back();
                  </script>";
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro de Banco de Dados: " . $e->getMessage();
    }
}
