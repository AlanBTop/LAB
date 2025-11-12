<?php
// /api/listar_servicos.php
include 'conexao.php';

try {
    $sql = "SELECT id, nome, tipo_servico, telefone, local_bairro, descricao, imagem_url 
            FROM prestadores 
            WHERE status = 'aprovado'
            ORDER BY data_cadastro DESC";
            
    $stmt = $pdo->query($sql);
    $servicos = $stmt->fetchAll();

    echo json_encode($servicos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar serviços: ' . $e->getMessage()]);
}