<?php
    header('Content-Type: application/json; charset=utf-8');
    include_once(__DIR__ . '/../../../core/conexao.php');

    $retorno = [
        'status'    => '',
        'mensagem'  => '',
        'data'      => []
    ];

    //dados do banco
    $nome       = $_POST['nome'];
    $email      = $_POST['email'];
    $senha      = $_POST['senha'];
    $telefone   = $_POST['telefone'];
    $sexo       = $_POST['sexo'];
    $cargo      = $_POST['cargo'];

    try {
        $stmt = $conexao->prepare("INSERT INTO usuario (nome, email, telefone, senha, sexo, cargo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $email, $telefone, $senha, $sexo, $cargo);
        $stmt->execute();

        $retorno = [
            'status'    => 'ok',
            'mensagem'  => 'Registro inserido com sucesso!',
            'data'      => []
        ];

        $stmt->close();
    } catch (mysqli_sql_exception $erro) {
        $retorno = [
            'status'    => 'nok',
            'mensagem'  => '',
            'data'      => []
        ];

        if ((int) $erro->getCode() === 1062) {
            http_response_code(409);
            $retorno['mensagem'] = 'Este e-mail já está cadastrado. Use outro e-mail ou faça login.';
        } else {
            http_response_code(500);
            $retorno['mensagem'] = 'Falha ao inserir o registro.';
        }
    }

    $conexao->close();

    echo json_encode($retorno);