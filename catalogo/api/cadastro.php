<?php
// /api/cadastro.php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    exit;
}
try {
    $nome = $_POST['nome'] ?? null;
    $tipo_servico = $_POST['servico'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $plano = $_POST['plano'] ?? 'inicial';
    $local_bairro = $_POST['local'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    if (empty($nome) || empty($tipo_servico) || empty($telefone)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Campos obrigatórios (nome, serviço, telefone) não podem estar vazios.']);
        exit;
    }

    $sql = "INSERT INTO prestadores 
                (nome, tipo_servico, telefone, local_bairro, descricao, plano_escolhido, status) 
            VALUES 
                (?, ?, ?, ?, ?, ?, 'pendente')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([ $nome, $tipo_servico, $telefone, $local_bairro, $descricao, $plano ]);

    http_response_code(201);
    echo json_encode(['status' => 'success', 'message' => 'Cadastro enviado com sucesso! Aguarde a aprovação.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar no banco de dados: ' . $e->getMessage()]);
}