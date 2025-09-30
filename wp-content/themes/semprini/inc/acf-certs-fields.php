<?php
// ACF: Campos para cada "Certificado" (CPT)
if (!defined('ABSPATH')) exit;

add_action('acf/init', function(){
  if (!function_exists('acf_add_local_field_group')) return;

  acf_add_local_field_group([
    'key' => 'group_certificado_fields',
    'title' => 'Datos del certificado',
    'fields' => [
      [
        'key' => 'field_cert_emisor',
        'label' => 'Emisor / Institución',
        'name' => 'cert_emisor',
        'type' => 'text',
        'placeholder' => 'Coderhouse, IBM, Google, etc.',
      ],
      [
        'key' => 'field_cert_anio',
        'label' => 'Año',
        'name' => 'cert_anio',
        'type' => 'text',
        'placeholder' => '2023',
        'maxlength' => 4,
      ],
      [
        'key' => 'field_cert_url',
        'label' => 'URL de verificación (opcional)',
        'name' => 'cert_url',
        'type' => 'url',
        'placeholder' => 'https://...',
      ],
    ],
    'location' => [
      [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'certificado' ] ],
    ],
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'active' => true,
  ]);
});
