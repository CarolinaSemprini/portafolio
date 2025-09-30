<?php
/**
* CPT Proyectos (opcional para la sección Work/Portfolio)
*/
function semprini_register_cpt_proyecto() {
$labels = [
'name' => 'Proyectos',
'singular_name' => 'Proyecto'
];
$args = [
'labels' => $labels,
'public' => true,
'has_archive' => true,
'rewrite'     => ['slug' => 'portafolio'], // opcional: /portafolio/
'menu_icon' => 'dashicons-portfolio',
'supports' => ['title', 'editor', 'thumbnail']
];
register_post_type('proyecto', $args);
}
add_action('init', 'semprini_register_cpt_proyecto');

// Taxonomía para filtrar: "tecnologia"
register_taxonomy('tecnologia', ['proyecto'], [
  'labels' => ['name'=>'Tecnologías','singular_name'=>'Tecnología'],
  'hierarchical' => false,
  'show_admin_column' => true,
  'rewrite' => ['slug'=>'tech'],
  'public' => true,
]);

// ====== CPT Certificados ======
  register_post_type('certificado', [
    'labels' => [
      'name'               => 'Certificados',
      'singular_name'      => 'Certificado',
      'add_new'            => 'Añadir certificado',
      'add_new_item'       => 'Nuevo certificado',
      'edit_item'          => 'Editar certificado',
      'new_item'           => 'Nuevo certificado',
      'view_item'          => 'Ver certificado',
      'search_items'       => 'Buscar certificados',
      'not_found'          => 'No se encontraron certificados',
      'not_found_in_trash' => 'No hay certificados en la papelera',
      'all_items'          => 'Todos los certificados',
      'menu_name'          => 'Certificados',
    ],
    'public'        => true,
    'menu_icon'     => 'dashicons-awards',
    'has_archive'   => false,                  // usaremos una página con template
    'rewrite'       => ['slug' => 'certificados'],
    'supports'      => ['title','thumbnail','excerpt','page-attributes'], // thumbnail = foto del certificado
    'show_in_rest'  => false,
  ]);