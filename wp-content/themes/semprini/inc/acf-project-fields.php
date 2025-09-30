<?php
// ACF: Campos para el CPT "proyecto"
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
  if (!function_exists('acf_add_local_field_group')) return;

  acf_add_local_field_group([
    'key' => 'group_semprini_project',
    'title' => 'Proyecto – Datos',
    'fields' => [

      ['key'=>'tab_proj_main','label'=>'Principal','type'=>'tab','placement'=>'top'],

      [
        'key' => 'field_proj_subtitle',
        'label' => 'Subtítulo / Rol',
        'name' => 'proj_subtitle',
        'type' => 'text',
        'placeholder' => 'Ej: Full-Stack • Data & GovTech',
      ],
      [
        'key' => 'field_proj_tech',
        'label' => 'Tecnologías (una por línea)',
        'name' => 'proj_tech',
        'type' => 'textarea',
        'rows' => 4,
        'instructions' => "Escribe una por línea (React\nNode.js\nPostgreSQL...)",
      ],
      [
        'key' => 'field_proj_featured',
        'label' => 'Destacado en Home',
        'name' => 'proj_featured',
        'type' => 'true_false',
        'ui'   => 1,
        'default_value' => 1,
      ],

      ['key'=>'tab_proj_links','label'=>'Enlaces','type'=>'tab','placement'=>'top'],

      [
        'key' => 'field_proj_live',
        'label' => 'URL Demo / Sitio',
        'name' => 'proj_live_url',
        'type' => 'url',
        'placeholder' => 'https://demo.tuapp.com',
      ],
      [
        'key' => 'field_proj_repo',
        'label' => 'Repositorio (GitHub, etc.)',
        'name' => 'proj_repo_url',
        'type' => 'url',
        'placeholder' => 'https://github.com/usuario/proyecto',
      ],
      [
        'key' => 'field_proj_case',
        'label' => 'Case study / Post',
        'name' => 'proj_case_url',
        'type' => 'url',
        'placeholder' => 'https://blog.tusitio.com/case-proyecto',
      ],

      ['key'=>'tab_proj_video','label'=>'Video','type'=>'tab','placement'=>'top'],

      [
        'key' => 'field_proj_video_embed',
        'label' => 'Video (YouTube / Vimeo – URL)',
        'name' => 'proj_video_oembed',
        'type' => 'oembed',
        'instructions' => 'Pega la URL del video. Si usas archivo MP4, deja este vacío.',
        'width' => 800,
        'height'=> 450,
      ],
      [
        'key' => 'field_proj_video_file',
        'label' => 'Video subido (MP4)',
        'name' => 'proj_video_file',
        'type' => 'file',
        'return_format' => 'array',
        'mime_types' => 'mp4',
        'instructions' => 'Sube un MP4 optimizado. Se mostrará si no hay oEmbed.',
      ],
      [
        'key' => 'field_proj_poster',
        'label' => 'Poster del video (opcional)',
        'name' => 'proj_video_poster',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'medium',
      ],
    ],
    'location' => [[['param'=>'post_type','operator'=>'==','value'=>'proyecto']]],
    'position' => 'normal',
    'style'    => 'default',
    'label_placement' => 'top',
    'active' => true,
  ]);
});
