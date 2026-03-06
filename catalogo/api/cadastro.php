<?php
// /catalogo/api/cadastro.php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    exit;
}

try {
    // 1. Coleta de dados e planos
    $plano = $_POST['plano'] ?? 'inicial';
    $nome = $_POST['nome'] ?? '';
    $tipo_servico = $_POST['servico'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $local_bairro = $_POST['local'] ?? '';
    $descricao_curta = $_POST['descricao_curta'] ?? null;
    
    // Dados de Planos Superiores
    $descricao_completa = $_POST['descricao_completa'] ?? null;
    $especialidades = $_POST['especialidades'] ?? null;
    $tempo_experiencia = $_POST['tempo_experiencia'] ?? null;
    $horario_atendimento = $_POST['horario_atendimento'] ?? null;
    $localizacao = $_POST['localizacao'] ?? null;

    // Redes Sociais
    $redes = [
        'whatsapp' => preg_replace('/[^0-9]/', '', $telefone),
        'facebook' => $_POST['facebook'] ?? null,
        'instagram' => $_POST['instagram'] ?? null,
    ];
    $redes_sociais_json = json_encode(array_filter($redes));

    // 2. Validação
    if (empty($nome) || empty($tipo_servico) || empty($telefone)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Campos obrigatórios (nome, serviço, telefone) não podem estar vazios.']);
        exit;
    }
    
    // 3. Lógica de Upload de Múltiplas Imagens
    $max_images = ($plano === 'profissional') ? 6 : (($plano === 'destaque') ? 2 : 0);
    $target_dir = "../uploads/";
    $image_paths = [];

    for ($i = 1; $i <= $max_images; $i++) {
        $input_name = 'imagem' . $i;
        $image_paths[$input_name] = null;

        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
            $file_extension = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid($plano . "_", true) . '_' . $i . '.' . $file_extension;
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $target_file)) {
                $image_paths[$input_name] = 'uploads/' . $new_file_name; 
            }
        }
    }

    // 4. Inserção no Banco de Dados
    $sql = "INSERT INTO prestadores 
                (plano, nome, tipo_servico, telefone, local_bairro, descricao_curta, descricao_completa, 
                 especialidades, tempo_experiencia, horario_atendimento, 
                 imagem1, imagem2, imagem3, imagem4, imagem5, imagem6, 
                 redes_sociais, localizacao, status) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendente')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([ 
        $plano, $nome, $tipo_servico, $telefone, $local_bairro, $descricao_curta, $descricao_completa, 
        $especialidades, $tempo_experiencia, $horario_atendimento,
        $image_paths['imagem1'] ?? null, $image_paths['imagem2'] ?? null, $image_paths['imagem3'] ?? null, 
        $image_paths['imagem4'] ?? null, $image_paths['imagem5'] ?? null, $image_paths['imagem6'] ?? null,
        $redes_sociais_json, $localizacao
    ]);

    http_response_code(201);
    echo json_encode(['status' => 'success', 'message' => 'Cadastro enviado com sucesso! Aguarde a aprovação.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar no banco de dados: ' . $e->getMessage()]);
}