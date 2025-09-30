<?php
/*
Template Name: Servicios
*/
get_header();

// Helper para obtener campos de ACF de forma segura
function af($k, $d = '') {
    if (!function_exists('get_field')) return $d;
    $v = get_field($k);
    return ($v === null || $v === '') ? $d : $v;
}

// Obtenemos los datos de ACF para la cabecera
$titulo    = af('serv_titulo', get_the_title());
$subtitulo = af('serv_subtitulo', 'Una descripción de los servicios que ofrezco.');
$max_servicios = 6; // El número de grupos que creaste en ACF

?>

<main class="page-services">
    
    <section class="services-hero">
        <div class="about-wrap">
            <header class="services-head fade-in">
                <h1><?php echo esc_html($titulo); ?></h1>
                <p class="sub"><?php echo esc_html($subtitulo); ?></p>
            </header>
        </div>
    </section>

    <section class="services-grid-section">
        <div class="about-wrap">
            <div class="services-grid">

                <?php
                // Usamos un bucle 'for' para comprobar cada grupo de servicio
                for ($i = 1; $i <= $max_servicios; $i++) {
                    // Obtenemos el grupo completo. El nombre del campo es dinámico: 'servicio_1', 'servicio_2', etc.
                    $servicio_grupo = get_field('servicio_' . $i);

                    // La condición más importante: Solo mostramos la tarjeta si el grupo tiene datos
                    // Y, como mínimo, el campo de título dentro del grupo está relleno.
                    if ( !empty($servicio_grupo) && !empty($servicio_grupo['serv_item_titulo']) ) {
                        
                        // Extraemos los datos de cada servicio para mayor claridad
                        $icono     = esc_attr($servicio_grupo['serv_item_icono'] ?: 'fa-solid fa-star');
                        $titulo_s  = esc_html($servicio_grupo['serv_item_titulo']);
                        $desc_s    = esc_html($servicio_grupo['serv_item_desc']);
                        $btn_texto = esc_html($servicio_grupo['serv_item_btn_texto']);
                        $btn_url   = esc_url($servicio_grupo['serv_item_btn_url']);
                        ?>

                        <article class="service-card fade-in">
                            <?php if ($icono): ?>
                                <div class="service-icon">
                                    <i class="<?php echo $icono; ?>"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="service-title"><?php echo $titulo_s; ?></h3>
                            
                            <?php if ($desc_s): ?>
                                <p class="service-desc"><?php echo $desc_s; ?></p>
                            <?php endif; ?>
                            
                            <?php if ($btn_texto && $btn_url): ?>
                                <div class="service-cta">
                                    <a href="<?php echo $btn_url; ?>" class="btn-3d btn-3d--link btn-glow">
                                        <span class="glow-layer" aria-hidden="true"></span>
                                        <?php echo $btn_texto; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </article>

                        <?php
                    } // Fin del 'if' que comprueba si el grupo tiene datos
                } // Fin del bucle 'for'
                ?>

            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>