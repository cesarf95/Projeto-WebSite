<?php

// =========================================
// DADOS DA CONEXÃO COM O BANCO
// =========================================

// Endereço do servidor do banco
// localhost = seu próprio computador
$host = 'localhost';

// Nome do banco de dados
$db_name = 'sgai';

// Usuário do MySQL
// No XAMPP geralmente é root
$user = 'root';

// Senha do MySQL
// No XAMPP normalmente fica vazia
$pass = '';

// Charset = padrão de caracteres
// utf8mb4 suporta:
// acentos, emojis, caracteres especiais etc
$charset = 'utf8mb4';



// =========================================
// DSN (DATA SOURCE NAME)
// =========================================

// String usada pelo PDO para saber:
// - qual banco usar
// - qual servidor usar
// - qual charset usar
//
// Isso vira algo parecido com:
//
// mysql:host=localhost;dbname=aula;charset=utf8mb4
//
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";



// =========================================
// CONFIGURAÇÕES DO PDO
// =========================================

$options = [

    // Mostra erros reais do banco caso aconteçam
    // Facilita MUITO identificar problemas
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,



    // Faz o fetch retornar array associativo
    //
    // Exemplo:
    //
    // $usuario['nome']
    //
    // Em vez de:
    //
    // $usuario[0]
    //
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,



    // Desativa emulação de prepared statements
    // Segurança e compatibilidade melhores
    //
    // Boa prática profissional
    //
    PDO::ATTR_EMULATE_PREPARES => false,
];



// =========================================
// TENTATIVA DE CONEXÃO
// =========================================

try {

    // Cria conexão com banco usando PDO
    //
    // new PDO() = cria objeto de conexão
    //
    // Parecido com:
    //
    // SqlConnection no C#
    //
    $pdo = new PDO($dsn, $user, $pass, $options);



    // Teste opcional
    // Se descomentar mostra:
    // Conectado
    //
    //echo "Conectado";



    // =========================================
    // CASO DÊ ERRO NA CONEXÃO
    // =========================================

} catch (PDOException $e) {

    // Encerra execução do sistema
    //
    // exit() = para o PHP imediatamente
    //
    // getMessage() pega mensagem real do erro
    //
    // Exemplo:
    // senha errada
    // banco inexistente
    // mysql desligado
    //
    exit("Erro: " . $e->getMessage());
}
