<?php
// /admin/painel.php
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: login.php");
    exit;
}

// Conexão com o BD (note o '../api/')
include '../api/conexao.php';

// Busca de Cadastros Pendentes
try {
    $stmt = $pdo->query("
        SELECT id, nome, tipo_servico, telefone, plano_escolhido, data_cadastro 
        FROM prestadores 
        WHERE status = 'pendente' 
        ORDER BY data_cadastro ASC
    ");
    $pendentes = $stmt->fetchAll();
} catch (PDOException $e) {
    $erro_painel = "Erro ao buscar cadastros: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Catálogo Cáceres</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <header class="admin-header">
        <h1>Painel Administrativo</h1>
        <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span>
        <a href="logout.php" class="btn btn-logout">Sair</a>
    </header>

    <main class="container">
        <h2>Cadastros Pendentes de Aprovação</h2>
        
        <?php if (isset($erro_painel)): ?>
            <p class="error-message"><?php echo $erro_painel; ?></p>
        <?php endif; ?>

        <?php if (empty($pendentes) && !isset($erro_painel)): ?>
            <p>Nenhum cadastro pendente no momento.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Serviço</th>
                        <th>Plano</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-pendentes">
                    <?php foreach ($pendentes as $item): ?>
                        <tr id="item-<?php echo $item['id']; ?>">
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($item['tipo_servico']); ?></td>
                            <td><span class="tag-plano"><?php echo htmlspecialchars($item['plano_escolhido']); ?></span></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($item['data_cadastro'])); ?></td>
                            <td>
                                <button class="btn btn-approve" data-id="<?php echo $item['id']; ?>">Aprovar</button>
                                <button class="btn btn-remove" data-id="<?php echo $item['id']; ?>">Reprovar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabela = document.getElementById('tabela-pendentes');
        tabela.addEventListener('click', (event) => {
            const target = event.target;
            const id = target.dataset.id;
            if (!id) return;
            let actionFile = '';
            if (target.classList.contains('btn-approve')) {
                actionFile = 'aprovar.php';
            } else if (target.classList.contains('btn-remove')) {
                actionFile = 'remover.php';
            } else { return; }
            if (!confirm('Tem certeza?')) { return; }
            gerenciarCadastro(id, actionFile, target);
        });

        async function gerenciarCadastro(id, url, button) {
            button.disabled = true;
            button.textContent = 'Aguarde...';
            const formData = new FormData();
            formData.append('id', id);

            try {
                const response = await fetch(url, { method: 'POST', body: formData });
                const result = await response.json();
                if (result.status === 'success') {
                    document.getElementById(`item-${id}`).remove();
                } else {
                    alert('Erro: ' + result.message);
                    button.disabled = false;
                }
            } catch (error) {
                alert('Erro na requisição: ' + error.message);
                button.disabled = false;
            }
        }
    });
    </script>
</body>
</html>