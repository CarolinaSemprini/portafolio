<?php
/**
 * ACF – Grupo de campos para la página "Sobre mí"
 * Compatible con ACF Free (sin repeater).
 */
if ( ! defined('ABSPATH') ) exit;

add_action('acf/init', function () {
  if ( ! function_exists('acf_add_local_field_group') ) return;

  acf_add_local_field_group(array(
    'key' => 'group_semprini_about',
    'title' => 'Sobre mí – Campos',
    'fields' => array(

      // =========================
      // DATOS PRINCIPALES
      // =========================
      array(
        'key' => 'tab_about_top',
        'label' => 'Datos principales',
        'type'  => 'tab',
        'placement' => 'top',
      ),
      array(
        'key' => 'field_about_foto',
        'label' => 'Foto / Imagen',
        'name' => 'about_foto',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'large',
      ),
      array(
        'key' => 'field_about_titulo',
        'label' => 'Título',
        'name' => 'about_titulo',
        'type' => 'text',
        'default_value' => 'Sobre mí',
      ),
      array(
        'key' => 'field_about_subtitulo',
        'label' => 'Subtítulo',
        'name' => 'about_subtitulo',
        'type' => 'text',
        'default_value' => 'Desarrollo Full-Stack & Ciencia de Datos',
      ),
      array(
        'key' => 'field_about_bio',
        'label' => 'Bio',
        'name' => 'about_bio',
        'type' => 'wysiwyg',
        'tabs' => 'all',
        'toolbar' => 'basic',
        'media_upload' => 0,
      ),
      array(
        'key' => 'field_about_skills',
        'label' => 'Habilidades (una por línea)',
        'name' => 'about_skills',
        'type' => 'textarea',
        'rows' => 6,
        'instructions' => "Escribe una habilidad por línea. Ej:\nReact\nNode.js\nPython\nSQL",
      ),

      // =========================
      // CV
      // =========================
      array(
        'key' => 'tab_about_cv',
        'label' => 'CV',
        'type'  => 'tab',
        'placement' => 'top',
      ),
      array(
        'key' => 'field_about_cv_pdf',
        'label' => 'Archivo CV (PDF)',
        'name' => 'about_cv_pdf',
        'type' => 'file',
        'return_format' => 'array',
        'library' => 'all',
        'mime_types' => 'pdf',
      ),

      // =========================
      // EXPERIENCIA (hasta 5 filas simples)
      // =========================
      array(
        'key' => 'tab_exp',
        'label' => 'Experiencia',
        'type'  => 'tab',
        'placement' => 'top',
      ),
      // Exp 1
      array('key'=>'field_exp1_anio','label'=>'Exp 1 – Año/Periodo','name'=>'exp1_anio','type'=>'text'),
      array('key'=>'field_exp1_rol','label'=>'Exp 1 – Rol','name'=>'exp1_rol','type'=>'text'),
      array('key'=>'field_exp1_empresa','label'=>'Exp 1 – Empresa','name'=>'exp1_empresa','type'=>'text'),
      array('key'=>'field_exp1_desc','label'=>'Exp 1 – Descripción','name'=>'exp1_desc','type'=>'textarea','rows'=>2),
      // Exp 2
      array('key'=>'field_exp2_anio','label'=>'Exp 2 – Año/Periodo','name'=>'exp2_anio','type'=>'text'),
      array('key'=>'field_exp2_rol','label'=>'Exp 2 – Rol','name'=>'exp2_rol','type'=>'text'),
      array('key'=>'field_exp2_empresa','label'=>'Exp 2 – Empresa','name'=>'exp2_empresa','type'=>'text'),
      array('key'=>'field_exp2_desc','label'=>'Exp 2 – Descripción','name'=>'exp2_desc','type'=>'textarea','rows'=>2),
      // Exp 3
      array('key'=>'field_exp3_anio','label'=>'Exp 3 – Año/Periodo','name'=>'exp3_anio','type'=>'text'),
      array('key'=>'field_exp3_rol','label'=>'Exp 3 – Rol','name'=>'exp3_rol','type'=>'text'),
      array('key'=>'field_exp3_empresa','label'=>'Exp 3 – Empresa','name'=>'exp3_empresa','type'=>'text'),
      array('key'=>'field_exp3_desc','label'=>'Exp 3 – Descripción','name'=>'exp3_desc','type'=>'textarea','rows'=>2),
      // Exp 4
      array('key'=>'field_exp4_anio','label'=>'Exp 4 – Año/Periodo','name'=>'exp4_anio','type'=>'text'),
      array('key'=>'field_exp4_rol','label'=>'Exp 4 – Rol','name'=>'exp4_rol','type'=>'text'),
      array('key'=>'field_exp4_empresa','label'=>'Exp 4 – Empresa','name'=>'exp4_empresa','type'=>'text'),
      array('key'=>'field_exp4_desc','label'=>'Exp 4 – Descripción','name'=>'exp4_desc','type'=>'textarea','rows'=>2),
      // Exp 5
      array('key'=>'field_exp5_anio','label'=>'Exp 5 – Año/Periodo','name'=>'exp5_anio','type'=>'text'),
      array('key'=>'field_exp5_rol','label'=>'Exp 5 – Rol','name'=>'exp5_rol','type'=>'text'),
      array('key'=>'field_exp5_empresa','label'=>'Exp 5 – Empresa','name'=>'exp5_empresa','type'=>'text'),
      array('key'=>'field_exp5_desc','label'=>'Exp 5 – Descripción','name'=>'exp5_desc','type'=>'textarea','rows'=>2),

      // =========================
      // TECNOLOGÍAS DESTACADAS  (AQUÍ DENTRO de "fields")
      // =========================
      array('key'=>'tab_feat','label'=>'Tecnologías destacadas','type'=>'tab','placement'=>'top'),
      array(
        'key'   => 'field_about_featured_stack',
        'label' => 'Botones destacados',
        'name'  => 'about_featured_stack',
        'type'  => 'textarea',
        'rows'  => 6,
        'instructions' => "Una por línea. Formato: Etiqueta | URL (opcional)\nEj:\nJavaScript | https://www.javascript.com\nPython\nReact | https://react.dev",
      ),
      array(
        'key'   => 'field_about_featured_title',
        'label' => 'Título de sección',
        'name'  => 'about_featured_title',
        'type'  => 'text',
        'default_value' => 'Tecnologías destacadas',
      ),
      array(
        'key'   => 'field_about_featured_desc',
        'label' => 'Descripción breve',
        'name'  => 'about_featured_desc',
        'type'  => 'text',
        'default_value' => 'Stack con el que diseño, construyo y escalo productos.',
      ),

    ), // <-- CIERRE CORRECTO del array "fields"

    'location' => array(
      array(
        array(
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'template-about.php',
        ),
      ),
    ),
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
  ));
});
