<?php
require 'conexao.php';

function get_html($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; VerificadorBot/1.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $html = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) return false;
    return $html;
}

function busca_palavras_chave($html, $palavras) {
    if (!$html || !$palavras) return false;
    $html_lower = mb_strtolower($html);
    $palavras_arr = array_map('trim', explode(',', mb_strtolower($palavras)));
    foreach ($palavras_arr as $p) {
        if ($p === '') continue;
        if (mb_strpos($html_lower, $p) !== false) {
            return true;
        }
    }
    return false;
}

function get_image_url($html, $selector = null, $base_url = null) {
    if (!$html) return null;
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);

    $imgs = $xpath->query("//img");

    foreach ($imgs as $img) {
        $src = $img->getAttribute('src');
        if (!$src) continue;
        if ($base_url && !preg_match('#^https?://#', $src)) {
            $src = rtrim($base_url, '/') . '/' . ltrim($src, '/');
        }
        if (strlen($src) > 10 && strpos(strtolower($src), 'logo') === false) {
            return $src;
        }
    }
    return null;
}

function validar_e_formatar_data($data, $hoje) {
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        $ano = intval($partes[2]);
        $mes = intval($partes[1]);
        $dia = intval($partes[0]);
        if (checkdate($mes, $dia, $ano) && $ano >= 2020 && $ano <= 2100) {
            $data_formatada = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
            if ($data_formatada >= $hoje) {
                return $data_formatada;
            }
        }
    }
    return false;
}

function extrai_data_por_fonte($fonte_nome, $html) {
    $hoje = date('Y-m-d');

    // Regex específicas para cada fonte
    $regexes = [
        'UFMG' => '/Data da prova:\s*(\d{2}\/\d{2}\/\d{4})/i',
        'PAS (UFLA)' => '/Data da prova:\s*(\d{2}\/\d{2}\/\d{4})/i',
        'UNICAMP' => '/Data do exame:\s*(\d{2}\/\d{2}\/\d{4})/i',
        'USP' => '/Data da prova:\s*(\d{2}\/\d{2}\/\d{4})/i',
        'UNIFAL' => '/Prova dia\s*(\d{2}\/\d{2}\/\d{4})/i',
        'UFJF' => '/(\d{2}\/\d{2}\/\d{4})/',  // Genérica
        'IFSULDEMINAS' => '/(\d{2}\/\d{2}\/\d{4})/' // Genérica
    ];

    // Tenta regex específica
    if (isset($regexes[$fonte_nome])) {
        if (preg_match($regexes[$fonte_nome], $html, $matches)) {
            $data = $matches[1];
            if ($data_formatada = validar_e_formatar_data($data, $hoje)) {
                return $data_formatada;
            }
        }
    }

    // Se não achou, tenta capturar qualquer data e validar
    if (preg_match_all('/\d{2}\/\d{2}\/\d{4}/', $html, $matches)) {
        foreach ($matches[0] as $data) {
            if ($data_formatada = validar_e_formatar_data($data, $hoje)) {
                return $data_formatada;
            }
        }
    }

    return null;
}

// Obtenção das fontes
$res = $conexao->query("SELECT * FROM fontes");
if (!$res) {
    die("Erro ao buscar fontes: " . $conexao->error);
}

while ($fonte = $res->fetch_assoc()) {
    echo "Verificando: {$fonte['nome']}... ";

    $html = get_html($fonte['url']);
    $status = 'erro';
    $img = null;
    $data_prova = null;

    if ($html === false) {
        echo "Erro ao baixar página.\n";
        continue;
    }

    $aberto = busca_palavras_chave($html, $fonte['palavras_chave']);
    $status = $aberto ? 'aberto' : 'fechado';
    $img = get_image_url($html, $fonte['selector_img'], $fonte['url']);
    $data_prova = extrai_data_por_fonte($fonte['nome'], $html);

    // Se a data não existir ou for inválida (null ou false), exibe N/A
    $data_exibir = ($data_prova && $data_prova !== false) ? $data_prova : 'N/A';

    echo "Status: $status | Data da prova: $data_exibir\n";
}

echo "Verificação finalizada.\n";
?>
