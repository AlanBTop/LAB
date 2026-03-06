<?php
// /catalogo/perfil.php
include 'api/conexao.php'; 

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: servicos.html');
    exit;
}

try {
    // Busca todos os dados do prestador
    $sql = "SELECT * FROM prestadores WHERE id = ? AND status = 'aprovado'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $prestador = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prestador) {
        $error_message = "Prestador não encontrado ou aguardando aprovação.";
    }
    
    // Decodifica redes sociais (se houver)
    $redes = json_decode($prestador['redes_sociais'] ?? '[]', true);

} catch (PDOException $e) {
    $error_message = "Erro ao buscar dados: " . $e->getMessage();
    $prestador = null;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo $prestador ? htmlspecialchars($prestador['nome']) : 'Profissional'; ?> - Conecta Serviços</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        .profile-container { max-width: 1000px; margin: 40px auto; padding: 20px; background-color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px; }
        .profile-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .profile-info h1 { color: #0d2c4f; font-size: 2rem; }
        .profile-info p { margin: 5px 0 15px; }
        .detail-card { background-color: #f7f7f7; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .detail-card h3 { color: #f26419; margin-bottom: 15px; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .carousel-container { position: relative; max-height: 400px; overflow: hidden; margin-bottom: 30px; border-radius: 8px; }
        .carousel-track { display: flex; transition: transform 0.5s ease-in-out; }
        .carousel-track img { width: 100%; height: 400px; object-fit: cover; flex-shrink: 0; }
        .map-container { height: 400px; width: 100%; background-color: #ddd; border-radius: 8px; }
        .social-links a { display: inline-block; margin-right: 15px; font-size: 1.2rem; text-decoration: none; color: #0d2c4f; }
        .social-links a:hover { color: #f26419; }
    </style>
    
    <?php if ($prestador && $prestador['plano'] == 'profissional' && $prestador['localizacao']): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIINg9xdabL805t6zH6jB360k80GZt9zdW8=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20n65A367dcaX8eF6F/x5tE6sLzX+4w7b/8E6F5/s6Y=" crossorigin=""></script>
    <?php endif; ?>
</head>
<body>
    <header></header> <main>
        <?php if ($error_message ?? null): ?>
            <div class="profile-container" style="text-align: center; color: red;">
                <h2>Erro!</h2>
                <p><?php echo $error_message; ?></p>
            </div>
        <?php else: ?>
            <div class="profile-container">

                <?php if ($prestador['plano'] != 'inicial'): ?>
                    <?php 
                        $images_to_show = [];
                        for ($i = 1; $i <= (($prestador['plano'] == 'profissional') ? 6 : 2); $i++) {
                            $img_key = 'imagem' . $i;
                            if (!empty($prestador[$img_key])) {
                                $images_to_show[] = $prestador[$img_key];
                            }
                        }
                    ?>
                    <?php if (!empty($images_to_show)): ?>
                        <section class="carousel-container">
                            <div class="carousel-track" id="banner-track">
                                <?php foreach ($images_to_show as $img): ?>
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="Banner de Serviço">
                                <?php endforeach; ?>
                            </div>
                        </section>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const track = document.getElementById('banner-track');
                                const images = track.querySelectorAll('img');
                                if (images.length > 1) {
                                    let currentIndex = 0;
                                    setInterval(() => {
                                        currentIndex = (currentIndex + 1) % images.length;
                                        track.style.transform = `translateX(-${currentIndex * 100}%)`;
                                    }, 4000); // Troca a cada 4 segundos
                                } else if (images.length === 0) {
                                    // Se não tiver imagens, talvez ocultar o container
                                    document.querySelector('.carousel-container').style.display = 'none';
                                }
                            });
                        </script>
                    <?php endif; ?>
                <?php endif; ?>


                <div class="profile-header">
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($prestador['nome']); ?></h1>
                        <span class="plano-tag-<?php echo $prestador['plano']; ?>"><?php echo strtoupper($prestador['plano']); ?></span>
                        <p><?php echo htmlspecialchars($prestador['descricao_curta'] ?? 'Nenhuma descrição curta disponível.'); ?></p>
                        <p><strong>Serviço:</strong> <?php echo htmlspecialchars($prestador['tipo_servico']); ?></p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($prestador['local_bairro']); ?></p>
                    </div>
                    <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $prestador['telefone']); ?>" 
                       target="_blank" class="btn btn-whatsapp">
                        Chamar <?php echo htmlspecialchars($prestador['telefone']); ?>
                    </a>
                </div>

                <?php if ($prestador['plano'] != 'inicial'): ?>
                    <section class="details-grid">
                        <div class="detail-card">
                            <h3>Especialidades</h3>
                            <p><?php echo nl2br(htmlspecialchars($prestador['especialidades'])); ?></p>
                        </div>
                        <div class="detail-card">
                            <h3>Experiência</h3>
                            <p><?php echo htmlspecialchars($prestador['tempo_experiencia']); ?></p>
                        </div>
                        <div class="detail-card">
                            <h3>Horário</h3>
                            <p><?php echo htmlspecialchars($prestador['horario_atendimento']); ?></p>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($prestador['plano'] == 'profissional'): ?>
                    <section class="detail-card">
                        <h3>Descrição Completa dos Serviços</h3>
                        <p><?php echo nl2br(htmlspecialchars($prestador['descricao_completa'])); ?></p>
                    </section>

                    <section class="detail-card">
                        <h3>Localização</h3>
                        <p>Endereço: <?php echo htmlspecialchars($prestador['localizacao']); ?></p>
                        <div id="map-container" class="map-container"></div>
                        <p style="margin-top: 10px;">Encontre-nos no mapa interativo abaixo.</p>
                    </section>

                    <?php if (!empty($redes)): ?>
                    <section class="social-links detail-card">
                        <h3>Conecte-se nas Redes</h3>
                        <?php if (!empty($redes['facebook'])): ?>
                            <a href="<?php echo htmlspecialchars($redes['facebook']); ?>" target="_blank">Facebook</a>
                        <?php endif; ?>
                        <?php if (!empty($redes['instagram'])): ?>
                            <a href="<?php echo htmlspecialchars($redes['instagram']); ?>" target="_blank">Instagram</a>
                        <?php endif; ?>
                    </section>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer></footer> <?php if ($prestador && $prestador['plano'] == 'profissional' && $prestador['localizacao']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mapContainer = document.getElementById('map-container');
                const locationStr = "<?php echo htmlspecialchars($prestador['localizacao']); ?>";
                
                // Tenta analisar como coordenadas (Lat, Lon)
                const coords = locationStr.split(',').map(c => parseFloat(c.trim()));

                if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                    const lat = coords[0];
                    const lon = coords[1];
                    
                    // Inicializa o mapa
                    const map = L.map('map-container').setView([lat, lon], 15); // 15 é o nível de zoom

                    // Adiciona a camada de tiles do OpenStreetMap
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    // Adiciona um marcador no local
                    L.marker([lat, lon]).addTo(map)
                        .bindPopup("<b><?php echo htmlspecialchars($prestador['nome']); ?></b><br>Estamos aqui!")
                        .openPopup();
                } else {
                    // Se não for coordenada, mostra mensagem de erro no container
                    mapContainer.innerHTML = '<p style="text-align:center; padding: 20px;">Localização inválida. Por favor, insira coordenadas (Latitude, Longitude).</p>';
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
<main>
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <a href="servicos.html" class="btn btn-back" style="margin-bottom: 20px;">← Voltar à Lista de Serviços</a>
        </div>
    
    <?php if ($error_message ?? null): ?>
        <?php else: ?>
        <div class="profile-container">
            </div>
    <?php endif; ?>
</main>