<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/validation.php';

function defaults(): array
{
    static $defaults;
    return $defaults ??= json_decode(file_get_contents(__DIR__ . '/defaults.json'), true, 512, JSON_THROW_ON_ERROR);
}

function default_team(): array
{
    return [
        1 => ['id' => 1, 'foto' => 'assets/img/team/team/Eu.png', 'instagram' => 'https://www.instagram.com/_gvsales/', 'linkedin' => ''],
        2 => ['id' => 2, 'foto' => 'assets/img/team/team/Anel.png', 'instagram' => 'https://www.instagram.com/rafael44x/', 'linkedin' => ''],
        3 => ['id' => 3, 'foto' => 'assets/img/team/team/bea.png', 'instagram' => 'https://www.instagram.com/_biacacesi/', 'linkedin' => ''],
        4 => ['id' => 4, 'foto' => 'assets/img/team/team/Aura.png', 'instagram' => 'https://www.instagram.com/laurinhaadourado/', 'linkedin' => ''],
    ];
}

function default_portfolio(): array
{
    $result = [];
    foreach ([1 => 'site', 2 => 'sistema', 3 => 'landing', 4 => 'marca', 5 => 'site', 6 => 'sistema'] as $id => $category) {
        $result[$id] = ['id' => $id, 'categoria' => $category, 'imagem' => '', 'imagem_destaque' => '', 'ativo' => 1];
    }
    return $result;
}

function sections(): array
{
    return ['navegacao' => 'Navegação', 'hero' => 'Capa e diferenciais', 'servicos' => 'Serviços', 'sobre' => 'Sobre, missão, visão e valores', 'processo' => 'Processo', 'portfolio' => 'Portfólio', 'destaque' => 'Projeto em destaque', 'equipe' => 'Equipe', 'cta' => 'Chamada para ação', 'contato' => 'Contato e atendimento', 'rodape' => 'Rodapé', 'formulario' => 'Mensagens do formulário'];
}

function key_section(string $key): string
{
    if (in_array($key, ['contactWhatsapp', 'contactEmail', 'whatsappMessage', 'ctaWhatsBtn', 'ctaInfo1', 'ctaInfo2', 'ctaInfo3'], true) || str_starts_with($key, 'contact')) return 'contato';
    if ($key === 'skipLink' || str_starts_with($key, 'nav')) return 'navegacao';
    if (preg_match('/^(hero|trust)/', $key)) return 'hero';
    if (preg_match('/^(services|svc)/', $key)) return 'servicos';
    if (preg_match('/^(about|mission|vision|values|why)/', $key)) return 'sobre';
    if (preg_match('/^(process|step)/', $key)) return 'processo';
    if (preg_match('/^(portfolio|filter|pf)/', $key)) return 'portfolio';
    if (preg_match('/^(featured|feat)/', $key)) return 'destaque';
    if (str_starts_with($key, 'team')) return 'equipe';
    if (str_starts_with($key, 'cta')) return 'cta';
    if (str_starts_with($key, 'footer')) return 'rodape';
    return 'formulario';
}

function section_keys(string $section): array
{
    return array_values(array_filter(array_keys(defaults()['pt']), fn($key) => key_section($key) === $section));
}

function key_label(string $key): string
{
    $labels = ['contactWhatsapp' => 'WhatsApp com DDI e DDD (somente dígitos)', 'contactEmail' => 'E-mail de contato', 'whatsappMessage' => 'Mensagem pré-preenchida no WhatsApp', 'ctaInfo2' => 'Horário de atendimento', 'footerLocation' => 'Localização', 'featTechTags' => 'Tecnologias (uma por linha)', 'featResults' => 'Resultados', 'featQuote' => 'Depoimento', 'featuredTitle' => 'Título do destaque', 'featDesc' => 'Descrição do destaque'];
    if (isset($labels[$key])) return $labels[$key];
    if (preg_match('/^team([1-4])(Name|Role|Desc)$/', $key, $m)) return 'Membro ' . $m[1] . ' — ' . ['Name' => 'Nome', 'Role' => 'Cargo', 'Desc' => 'Descrição'][$m[2]];
    if (preg_match('/^pf([1-6])(Name2|Name|Cat|Desc)$/', $key, $m)) return 'Projeto ' . $m[1] . ' — ' . ['Name' => 'Nome na capa', 'Name2' => 'Título do cartão', 'Cat' => 'Categoria exibida', 'Desc' => 'Descrição'][$m[2]];
    $preview = defaults()['pt'][$key];
    return (function_exists('mb_strimwidth') ? mb_strimwidth($preview, 0, 90, '…', 'UTF-8') : substr($preview, 0, 90));
}

function validate_content(string $key, mixed $value): string
{
    if (!array_key_exists($key, defaults()['pt'])) throw new InvalidArgumentException('Campo desconhecido.');
    if ($key === 'contactWhatsapp') {
        $number = plain($value, 'WhatsApp', 15, true);
        if (!preg_match('/^[1-9][0-9]{7,14}$/D', $number)) throw new InvalidArgumentException('WhatsApp: use 8 a 15 dígitos, incluindo DDI e DDD.');
        return $number;
    }
    if ($key === 'contactEmail') return email_value($value);
    $max = preg_match('/(Title|Name|Role|Cat|Btn|Opt|Eyebrow|Label)$/', $key) ? 300 : 5000;
    if ($key === 'featTechTags') $max = 2000;
    return plain($value, key_label($key), $max);
}

function public_url(string $url): string
{
    try { return http_url($url); } catch (InvalidArgumentException) { return ''; }
}

function display_phone(string $value): string
{
    $digits = preg_replace('/[^0-9]/', '', $value);
    if (preg_match('/^55([0-9]{2})([0-9]{8,9})$/D', $digits, $parts) || preg_match('/^([0-9]{2})([0-9]{8,9})$/D', $digits, $parts)) {
        return '+55 ' . $parts[1] . ' ' . substr($parts[2], 0, -4) . '-' . substr($parts[2], -4);
    }
    return $value;
}

function image_path(string $path): string
{
    if ($path === '') return '';
    $originals = array_column(default_team(), 'foto');
    if (in_array($path, $originals, true) || preg_match('~^uploads/[a-f0-9]{32}\.(jpg|png|webp)$~D', $path)) return $path;
    return '';
}

function site_content(bool $fallback = true): array
{
    $content = ['dict' => defaults(), 'team' => default_team(), 'portfolio' => default_portfolio()];
    try {
        $pdo = db();
        // Uma leitura consistente evita misturar metadados e textos de uma edição concorrente.
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('SELECT chave, texto_pt, texto_en FROM site_conteudo');
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            if (!array_key_exists($row['chave'], $content['dict']['pt'])) continue;
            foreach (['pt', 'en'] as $lang) $content['dict'][$lang][$row['chave']] = validate_content($row['chave'], $row['texto_' . $lang]);
        }
        $stmt = $pdo->prepare('SELECT id, foto, instagram, linkedin FROM equipe ORDER BY id');
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            if (isset($content['team'][$row['id']])) $content['team'][$row['id']] = $row;
        }
        $stmt = $pdo->prepare('SELECT * FROM portfolio ORDER BY id');
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            if (isset($content['portfolio'][$row['id']]) && in_array($row['categoria'], ['site', 'sistema', 'landing', 'marca'], true)) $content['portfolio'][$row['id']] = $row + ['ativo' => 1];
        }
        $pdo->commit();
        return $content;
    } catch (Throwable $error) {
        if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
        error_log('Codora conteúdo: ' . $error->getMessage());
        if (!$fallback) throw $error;
        return ['dict' => defaults(), 'team' => default_team(), 'portfolio' => default_portfolio()];
    }
}

function seed_content(PDO $pdo): void
{
    $dict = defaults();
    $stmt = $pdo->prepare('INSERT INTO site_conteudo (chave, texto_pt, texto_en, secao) VALUES (?, ?, ?, ?)');
    foreach ($dict['pt'] as $key => $value) $stmt->execute([$key, $value, $dict['en'][$key], key_section($key)]);
    $stmt = $pdo->prepare('INSERT INTO equipe (id, foto, instagram, linkedin) VALUES (?, ?, ?, ?)');
    foreach (default_team() as $row) $stmt->execute(array_values($row));
    $stmt = $pdo->prepare('INSERT INTO portfolio (id, categoria, imagem, imagem_destaque, ativo) VALUES (?, ?, ?, ?, ?)');
    foreach (default_portfolio() as $row) $stmt->execute(array_values($row));
}
