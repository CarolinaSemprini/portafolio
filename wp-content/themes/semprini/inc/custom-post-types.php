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
'menu_icon' => 'dashicons-portfolio',
'supports' => ['title', 'editor', 'thumbnail']
];
register_post_type('proyecto', $args);
}
add_action('init', 'semprini_register_cpt_proyecto');