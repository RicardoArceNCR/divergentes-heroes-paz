<?php
/** @var array $data */
/** @var array $config */
/** @var string $root_id */
/** @var string $config_json */

if (!defined('ABSPATH')) {
    exit;
}

$meta = dhp_array_get_array($data, 'meta');
$intro = dhp_array_get_array($data, 'intro');
$months = dhp_array_get_array($data, 'months');
$events = dhp_array_get_array($data, 'events');

$title = dhp_array_get_string($meta, 'title');
$subtitle = dhp_array_get_string($meta, 'subtitle');
$paragraphs = dhp_array_get_array($intro, 'paragraphs');
$byline_label = dhp_array_get_string($intro, 'byline_label', 'Equipo editorial');

$paragraphs = array_values(array_filter($paragraphs, function ($item) {
    return is_string($item) && trim($item) !== '';
}));

$layout = dhp_array_get_string($config, 'layout', 'fullbleed');
$theme = dhp_array_get_string($config, 'theme', 'editorial');
?>

<div class="dhp-special hp-wp-wrap hp-wp-wrap--<?php echo dhp_esc_attr($layout); ?>">
    <section class="hp-shell hp-shell--editorial" data-theme="<?php echo dhp_esc_attr($theme); ?>"
        data-layout="<?php echo dhp_esc_attr($layout); ?>">

        <!-- PORTADA -->
        <section class="hp-hero" aria-label="<?php echo dhp_esc_attr($title ?: 'Especial editorial'); ?>">
            <div class="hp-container hp-hero-container">
                <div class="hp-hero-copy hp-hero__copy">
                    <?php if ($title): ?>
                        <h1 class="hp-title hp-hero__title hp-hero-animate hp-hero-animate--title">
                            <span class="hp-hero-title-main hp-hero-title">Los "héroes de la paz"</span>
                            <span class="hp-hero-highlight"
                                aria-label="sin pasamontañas">
                                <span class="hp-hero-highlight__corner hp-hero-highlight__corner--tl"
                                    aria-hidden="true"></span>
                                <span class="hp-hero-highlight__corner hp-hero-highlight__corner--tr"
                                    aria-hidden="true"></span>
                                <span class="hp-hero-highlight__corner hp-hero-highlight__corner--bl"
                                    aria-hidden="true"></span>
                                <span class="hp-hero-highlight__corner hp-hero-highlight__corner--br"
                                    aria-hidden="true"></span>
                                <span class="hp-hero-highlight__lead">SIN</span>
                                <span class="hp-hero-highlight__text">PASAMONTAÑAS</span>
                            </span>
                        </h1>
                    <?php endif; ?>

                    <?php if ($subtitle): ?>
                        <p class="hp-subtitle hp-hero__subtitle hp-hero-animate hp-hero-animate--subtitle">
                            <?php echo dhp_esc_html($subtitle); ?></p>
                    <?php endif; ?>

                    <div class="hp-hero-intro"></div>

                    <div class="hp-signoff hp-hero-signoff hp-hero-animate hp-hero-animate--signoff">
                        <img class="hp-signoff__logo hp-hero-signoff__logo"
                            src="<?php echo esc_url(dhp_asset_image_url('por-divergentes.png')); ?>"
                            alt="Por Divergentes" decoding="async" loading="lazy" />

                        <div class="hp-share" aria-label="Compartir este especial">
                            <a class="hp-share__btn hp-share__btn--fb" href="#" target="_blank" rel="noopener noreferrer" aria-label="Compartir en Facebook">
                                <img
                                    src="<?php echo esc_url(dhp_asset_image_url('facebook.webp')); ?>"
                                    alt="Facebook"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>

                            <a class="hp-share__btn hp-share__btn--wa" href="#" target="_blank" rel="noopener noreferrer" aria-label="Compartir en WhatsApp">
                                <img
                                    src="<?php echo esc_url(dhp_asset_image_url('whatsapp.webp')); ?>"
                                    alt="WhatsApp"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>

                            <a class="hp-share__btn hp-share__btn--x" href="#" target="_blank" rel="noopener noreferrer" aria-label="Compartir en X">
                                <img
                                    src="<?php echo esc_url(dhp_asset_image_url('x.webp')); ?>"
                                    alt="X"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- INTRO EDITORIAL -->
        <section class="hp-intro hp-intro--light">
                <div class="hp-container hp-intro-container">
                    <picture class="hp-intro-rail-art" aria-hidden="true">
                        <source media="(max-width: 782px)"
                            srcset="<?php echo esc_url(dhp_asset_image_url('img-mobile.webp')); ?>">
                        <img src="<?php echo esc_url(dhp_asset_image_url('img-desktop.webp')); ?>" alt="" decoding="async"
                            loading="lazy">
                    </picture>

                    <div class="hp-intro-copy hp-intro__copy">

                        <div class="hp-lede hp-intro__lede-group" data-hp-line-start>
                            <p class="hp-intro__lede">
                                En Nicaragua existen nombres que, tras la crisis sociopolítica iniciada en abril de 2018,
                                fueron elevados por el régimen Ortega-Murillo a la categoría de “héroes de la paz”: civiles
                                armados y policías que perdieron la vida mientras participaban en acciones violentas
                                destinadas a reprimir las protestas y restablecer el control territorial del país. Sin
                                embargo, no todos ocupan el mismo lugar en la memoria de la dictadura sandinista. Mientras
                                algunos, como Francisco Aráuz Pineda —hijo de la reconocida sandinista Amada Pineda— o el
                                militante Bismarck Martínez han sido recordados con homenajes, placas y monumentos, otros
                                caídos apenas sobreviven en publicaciones de aniversario o han sido relegados al olvido.
                            </p>
                            <p class="hp-intro__lede">
                                Los policías fallecidos durante las protestas de 2018 también fueron incorporados por el
                                Gobierno a la categoría de “héroes de la paz y la seguridad”, pese a que murieron en un
                                contexto violento que organismos internacionales documentaron como de represión estatal en
                                contra de
                                las manifestaciones cívicas.
                            </p>
                            <p class="hp-intro__lede">
                                En su informe de 2018, la Comisión Interamericana de Derechos Humanos (CIDH) concluyó que la
                                violencia en Nicaragua que tuvo como principal agente represor a la Policía Nacional, estuvo
                                dirigida a disuadir la participación en las manifestaciones y sofocar el disenso político,
                                siguiendo un patrón caracterizado por el uso excesivo y arbitrario de la fuerza —incluida
                                fuerza letal—, la actuación de grupos parapoliciales y paramilitares con tolerancia estatal,
                                amenazas contra líderes sociales y la falta de diligencia en las investigaciones sobre
                                asesinatos y lesiones.
                            </p>
                            <p class="hp-intro__lede">
                                ¿Quiénes fueron estas personas, por qué el régimen las presenta como “héroes de la paz”,
                                pese a su participación en la represión y qué revela esa memoria selectiva del Frente
                                Sandinista sobre la forma en que se justifica la violencia ejercida en 2018?
                            </p>
                        </div>


                    </div>
                </div>
        </section>

        <!-- TIMELINE -->
        <div class="hp-track" data-hp-track>
            <div class="hp-track-inner" aria-hidden="true">
                <div class="hp-track-line"></div>
                <div class="hp-track-fill" data-hp-track-fill></div>
            </div>

            <section class="hp-app hp-timeline">
                <div class="hp-container">
                    <div id="<?php echo esc_attr($root_id); ?>" class="hp-root hp-timeline__root"
                        data-config="<?php echo esc_attr($config_json); ?>">
                        <div class="hp-loading" role="status" aria-live="polite">
                            Cargando contenido...
                        </div>
                    </div>

                    <div class="hp-fallback-wrap" data-hp-fallback>
                        <?php include DHP_PATH . 'templates/seo-fallback.php'; ?>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>