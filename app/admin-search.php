<?php
declare(strict_types=1);
require_once __DIR__ . '/content.php';

function admin_search_index(array $content): array
{
    $index = [];
    $add = static function ($section, $key, $label, $lang, $text, $target) use (&$index): void {
        $index[] = ['section' => $section, 'sectionLabel' => sections()[$section], 'key' => $key, 'label' => $label, 'lang' => strtoupper($lang), 'text' => $text, 'target' => $target];
    };
    foreach (sections() as $section => $_) foreach (section_keys($section) as $key) foreach (['pt', 'en'] as $lang) {
        $add($section, $key, key_label($key), $lang, $content['dict'][$lang][$key], $key . '-' . $lang);
    }
    foreach ($content['team'] as $id => $member) foreach (['foto' => 'Foto', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn'] as $field => $label) {
        $add('equipe', 'team' . $id . '-' . $field, $content['dict']['pt']['team' . $id . 'Name'] . ' — ' . $label, 'PT/EN', $member[$field], 'team-media-' . $id . '-' . $field);
    }
    $filters = ['site' => 'Sites', 'sistema' => 'Sistemas', 'landing' => 'Landing Pages', 'marca' => 'Identidade de Marca'];
    foreach ($content['portfolio'] as $id => $project) foreach (['categoria' => 'Filtro de categoria', 'imagem' => 'Imagem'] as $field => $label) {
        $text = $field === 'categoria' ? $filters[$project[$field]] . ' (' . $project[$field] . ')' : $project[$field];
        $add('portfolio', 'pf' . $id . '-' . $field, $content['dict']['pt']['pf' . $id . 'Name'] . ' — ' . $label, 'PT/EN', $text, 'portfolio-media-' . $id . '-' . $field);
    }
    $add('destaque', 'imagem_destaque', 'Imagem do projeto em destaque — ' . $content['dict']['pt']['pf1Name'], 'PT/EN', $content['portfolio'][1]['imagem_destaque'], 'featured-image');
    return $index;
}
