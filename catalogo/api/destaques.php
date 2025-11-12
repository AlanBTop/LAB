<?php
// /api/destaques.php
include 'conexao.php';

try {
    $sql = "SELECT id, nome, tipo_servico, telefone, local_bairro, imagem_url 
            FROM prestadores 
            WHERE status = 'aprovado'
            ORDER BY data_cadastro DESC
            LIMIT 4";
            
    $stmt = $pdo->query($sql);
    $destaques = $stmt->fetchAll();

    echo json_encode($destaques);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar destaques: ' . $e->getMessage()]);
}