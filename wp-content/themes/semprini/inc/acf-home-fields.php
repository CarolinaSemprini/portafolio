<?php
/**
 * ACF – Grupo de campos para la Página de Inicio
 * Crea todos los campos necesarios para editar la HOME desde WP-Admin.
 * Requiere ACF (free o Pro). No usa repeaters (compatibilidad con ACF Free).
 */

if ( ! defined('ABSPATH') ) exit;

add_action('acf/init', function () {
  if( ! function_exists('acf_add_local_field_group') ) return;

  acf_add_local_field_group(array(
    'key' => 'group_semprini_home',
    'title' => 'Home – Inicio',
    'fields' => array(

      // =========================
      // HERO
      // =========================
      array(
        'key' => 'field_hero_tab',
        'label' => 'Hero',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ),
      array(
        'key' => 'field_hero_titulo',
        'label' => 'Título principal',
        'name' => 'hero_titulo',
        'type' => 'text',
        'default_value' => 'Desarrolladora Full Stack & Especialista en Ciencia de Datos',
      ),
      array(
        'key' => 'field_hero_subtitulo',
        'label' => 'Subtítulo',
        'name' => 'hero_subtitulo',
        'type' => 'text',
        'default_value' => 'Fusiono tecnología, diseño y análisis para construir soluciones innovadoras y accesibles.',
      ),
      array(
        'key' => 'field_hero_ubicacion',
        'label' => 'Ubicación',
        'name' => 'ubicacion',
        'type' => 'text',
        'default_value' => 'Santa Cruz, Argentina',
      ),
      array(
        'key' => 'field_hero_logo',
        'label' => 'Logo (imagen)',
        'name' => 'hero_logo',
        'type' => 'image',
        'return_format' => 'array', // (aceptamos URL o array en el template)
        'preview_size' => 'medium',
        'library' => 'all',
      ),
      array(
        'key' => 'field_hero_video',
        'label' => 'Video de fondo (MP4)',
        'name' => 'hero_video_fondo',
        'type' => 'file',
        'return_format' => 'array', // (aceptamos URL o array en el template)
        'library' => 'all',
        'mime_types' => 'mp4',
      ),
      array(
        'key' => 'field_hero_imagen',
        'label' => 'Imagen de fondo (fallback)',
        'name' => 'hero_imagen_fondo',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'large',
        'library' => 'all',
      ),

      // =========================
      // SERVICES / HIGHLIGHTS
      // =========================
      array(
        'key' => 'field_services_tab',
        'label' => 'Servicios',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ),
      // Card 1
      array(
        'key' => 'field_svc1_title',
        'label' => 'Servicio 1 – Título',
        'name' => 'svc1_title',
        'type' => 'text',
        'default_value' => 'Aplicaciones Web',
      ),
      array(
        'key' => 'field_svc1_text',
        'label' => 'Servicio 1 – Descripción',
        'name' => 'svc1_text',
        'type' => 'textarea',
        'rows' => 2,
        'default_value' => 'Front-end moderno, performance y SEO técnico. Experiencias fluidas y accesibles.',
      ),
      array(
        'key' => 'field_svc1_link_text',
        'label' => 'Servicio 1 – Texto botón',
        'name' => 'svc1_link_text',
        'type' => 'text',
        'default_value' => 'Ver casos',
      ),
      array(
        'key' => 'field_svc1_link_url',
        'label' => 'Servicio 1 – URL botón',
        'name' => 'svc1_link_url',
        'type' => 'url',
        'default_value' => '/work',
      ),
      // Card 2
      array(
        'key' => 'field_svc2_title',
        'label' => 'Servicio 2 – Título',
        'name' => 'svc2_title',
        'type' => 'text',
        'default_value' => 'Datos & Automatización',
      ),
      array(
        'key' => 'field_svc2_text',
        'label' => 'Servicio 2 – Descripción',
        'name' => 'svc2_text',
        'type' => 'textarea',
        'rows' => 2,
        'default_value' => 'Dashboards, ETL, integraciones y procesos automatizados para tu negocio.',
      ),
      array(
        'key' => 'field_svc2_link_text',
        'label' => 'Servicio 2 – Texto botón',
        'name' => 'svc2_link_text',
        'type' => 'text',
        'default_value' => 'Proyectos',
      ),
      array(
        'key' => 'field_svc2_link_url',
        'label' => 'Servicio 2 – URL botón',
        'name' => 'svc2_link_url',
        'type' => 'url',
        'default_value' => '/work',
      ),
      // Card 3
      array(
        'key' => 'field_svc3_title',
        'label' => 'Servicio 3 – Título',
        'name' => 'svc3_title',
        'type' => 'text',
        'default_value' => 'GobTech',
      ),
      array(
        'key' => 'field_svc3_text',
        'label' => 'Servicio 3 – Descripción',
        'name' => 'svc3_text',
        'type' => 'textarea',
        'rows' => 2,
        'default_value' => 'Tecnología aplicada al sector público: eficiencia, transparencia y servicios digitales.',
      ),
      array(
        'key' => 'field_svc3_link_text',
        'label' => 'Servicio 3 – Texto botón',
        'name' => 'svc3_link_text',
        'type' => 'text',
        'default_value' => 'Hablemos',
      ),
      array(
        'key' => 'field_svc3_link_url',
        'label' => 'Servicio 3 – URL botón',
        'name' => 'svc3_link_url',
        'type' => 'url',
        'default_value' => '/contact',
      ),

      // =========================
      // STATS
      // =========================
      array(
        'key' => 'field_stats_tab',
        'label' => 'Stats',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ),
      array(
        'key' => 'field_stat_proyectos',
        'label' => 'N° Proyectos',
        'name' => 'stat_proyectos',
        'type' => 'text',
        'default_value' => '20',
      ),
      array(
        'key' => 'field_stat_anios',
        'label' => 'Años de experiencia',
        'name' => 'stat_anios',
        'type' => 'text',
        'default_value' => '5+',
      ),
      array(
        'key' => 'field_stat_stack',
        'label' => 'Tecnologías',
        'name' => 'stat_stack',
        'type' => 'text',
        'default_value' => '10+',
      ),

      // =========================
      // WORK TEASER
      // =========================
      array(
        'key' => 'field_work_tab',
        'label' => 'Trabajo Destacado',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ),
      array(
        'key' => 'field_work_heading',
        'label' => 'Título',
        'name' => 'work_heading',
        'type' => 'text',
        'default_value' => 'Trabajo Destacado',
      ),
      array(
        'key' => 'field_work_lead',
        'label' => 'Descripción corta',
        'name' => 'work_lead',
        'type' => 'text',
        'default_value' => 'Una selección de proyectos representativos.',
      ),
      array(
        'key' => 'field_work_btn_text',
        'label' => 'Texto botón',
        'name' => 'work_btn_text',
        'type' => 'text',
        'default_value' => 'Ir a Portafolio',
      ),
      array(
        'key' => 'field_work_btn_url',
        'label' => 'URL botón',
        'name' => 'work_btn_url',
        'type' => 'url',
        'default_value' => '/work',
      ),

      // =========================
      // CONTACT CTA
      // =========================
      array(
        'key' => 'field_cta_tab',
        'label' => 'Call To Action',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ),
      array(
        'key' => 'field_cta_text',
        'label' => 'Texto CTA',
        'name' => 'cta_text',
        'type' => 'text',
        'default_value' => '¿Listos para crear algo increíble?',
      ),
      array(
        'key' => 'field_cta_btn_text',
        'label' => 'Texto botón CTA',
        'name' => 'cta_button_text',
        'type' => 'text',
        'default_value' => 'Contactar',
      ),
      array(
        'key' => 'field_cta_btn_url',
        'label' => 'URL botón CTA',
        'name' => 'cta_button_url',
        'type' => 'url',
        'default_value' => '/contact',
      ),

            // =========================
      // SOCIAL LINKS
      // =========================
      array(
        'key' => 'field_social_tab',
        'label' => 'Redes Sociales',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ),
      array(
        'key' => 'field_social_linkedin',
        'label' => 'LinkedIn URL',
        'name' => 'social_linkedin',
        'type' => 'url',
        'default_value' => 'https://www.linkedin.com/in/semprinicarolina',
      ),
      array(
        'key' => 'field_social_github',
        'label' => 'GitHub URL',
        'name' => 'social_github',
        'type' => 'url',
        'default_value' => 'https://github.com/', // pon tu usuario si querés
      ),
      array(
        'key' => 'field_social_email',
        'label' => 'Email (solo dirección)',
        'name' => 'social_email',
        'type' => 'email',
        'default_value' => 'carolinasemprini@gmail.com',
      ),
      array(
        'key' => 'field_social_whatsapp',
        'label' => 'WhatsApp (solo números, con código país, sin +)',
        'name' => 'social_whatsapp',
        'type' => 'text',
        'instructions' => 'Ej: 5492966123456 (opcional)',
        'default_value' => '',
      ),


    ),
    'location' => array(
      // Aplica SOLO cuando la página usa el template "Página de Inicio"
      array(
        array(
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'template-home.php',
        ),
      ),
    ),
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
    'show_in_rest' => 0,
    
  ));
});