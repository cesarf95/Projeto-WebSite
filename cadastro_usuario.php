<?php

include 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome     = trim($_POST['nome'] ?? '');
    $cpf      = trim($_POST['cpf'] ?? '');
    $funcao   = trim($_POST['funcao'] ?? '');
    $genero   = trim($_POST['genero'] ?? '');
    $login    = trim($_POST['login'] ?? '');
    $senha    = trim($_POST['senha'] ?? '');

    // Se a senha secundária não vier do formulário, gera um PIN aleatório de 6 dígitos
    $senha_secundaria = trim($_POST['senha_secundaria'] ?? '');
    if (empty($senha_secundaria)) {
        $senha_secundaria = (string)rand(100000, 999999);
    }

    // VALIDAÇÃO CORRIGIDA: Tirado o erro do 'senha' com aspas e removido o empty da secundária
    if (empty($nome) || empty($cpf) || empty($login) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios!'); history.back();</script>";
        exit();
    }

    // APLICANDO O PADRÃO SENHA HASH (Bcrypt seguro) nas duas senhas
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $senha_secundaria_hash = password_hash($senha_secundaria, PASSWORD_DEFAULT);

    try {

        // VERIFICA SE O USUÁRIO JÁ EXISTE
        $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE cpf = :cpf OR login = :login";

        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->bindParam(':cpf', $cpf);
        $stmtCheck->bindParam(':login', $login);
        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() > 0) {

            echo "
            <script>
                alert('Usuário com este CPF ou Login já cadastrado no sistema!');
                history.back();
            </script>
            ";
            exit();
        } else {

            // CADASTRA NOVO USUÁRIO
            $sqlInsert = "INSERT INTO usuarios
            (
                nome,
                cpf,
                funcao,
                genero,
                login,
                senha,
                senha_secundaria
            )
            VALUES
            (
                :nome,
                :cpf,
                :funcao,
                :genero,
                :login,
                :senha,
                :senha_secundaria
            )";

            $stmtInsert = $pdo->prepare($sqlInsert);

            $stmtInsert->bindParam(':nome', $nome);
            $stmtInsert->bindParam(':cpf', $cpf);
            $stmtInsert->bindParam(':funcao', $funcao);
            $stmtInsert->bindParam(':genero', $genero);
            $stmtInsert->bindParam(':login', $login);
            $stmtInsert->bindParam(':senha', $senha_hash);
            $stmtInsert->bindParam(':senha_secundaria', $senha_secundaria_hash);

            if ($stmtInsert->execute()) {

                // Exibe o alerta com a senha secundária gerada para você anotar!
                echo "
                <script>
                    alert('Usuário cadastrado com sucesso!\\n\\n--> SENHA SECUNDÁRIA GERADA: " . $senha_secundaria . " <--\\nAnote e passe para o usuário.');
                    window.location.href = 'usuarios.php';
                </script>
                ";
                exit();
            } else {

                echo "
                <script>
                    alert('Erro ao cadastrar usuário!');
                    history.back();
                </script>
                ";
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
