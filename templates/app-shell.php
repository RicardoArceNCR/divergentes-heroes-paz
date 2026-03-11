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
$byline_label = dhp_array_get_string($intro, 'byline_label', 'Por Divergentes');

$paragraphs = array_values(array_filter($paragraphs, function ($item) {
    return is_string($item) && trim($item) !== '';
}));

$layout = dhp_array_get_string($config, 'layout', 'fullbleed');
$theme = dhp_array_get_string($config, 'theme', 'editorial');
?>

<div class="hp-wp-wrap hp-wp-wrap--<?php echo dhp_esc_attr($layout); ?> alignfull">
    <section class="hp-shell hp-shell--editorial"
             data-theme="<?php echo dhp_esc_attr($theme); ?>"
             data-layout="<?php echo dhp_esc_attr($layout); ?>">

        <!-- PORTADA -->
        <section class="hp-hero" aria-label="<?php echo dhp_esc_attr($title ?: 'Especial editorial'); ?>">
            <div class="hp-container hp-hero-container">
                <div class="hp-hero-copy hp-hero__copy">
                    <?php if ($title): ?>
                        <h1 class="hp-title hp-hero__title"><?php echo dhp_esc_html($title); ?></h1>
                    <?php endif; ?>

                    <?php if ($subtitle): ?>
                        <p class="hp-subtitle hp-hero__subtitle"><?php echo dhp_esc_html($subtitle); ?></p>
                    <?php endif; ?>

                    <?php if ($byline_label): ?>
                        <div class="hp-hero-byline-text hp-hero__byline">
                            <span><?php echo dhp_esc_html($byline_label); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- INTRO EDITORIAL -->
        <?php if (!empty($paragraphs) && is_array($paragraphs)): ?>
            <section class="hp-intro hp-intro--dark">
                <div class="hp-container hp-intro-container">
                    <img class="hp-intro-rail-art"
                         src="<?php echo esc_url(dhp_asset_image_url('hero-connector.png')); ?>"
                         alt=""
                         aria-hidden="true"
                         decoding="async"
                         loading="lazy" />

                    <div class="hp-intro-copy hp-intro__copy">
                        <?php
                        $first_paragraph = $paragraphs[0] ?? '';
                        $remaining_paragraphs = array_slice($paragraphs, 1);
                        ?>

                        <?php if ($first_paragraph): ?>
                            <p class="hp-lede hp-intro__lede" data-hp-line-start>
                                <?php echo dhp_esc_html($first_paragraph); ?>
                            </p>
                        <?php endif; ?>

                        <?php foreach ($remaining_paragraphs as $paragraph): ?>
                            <p class="hp-intro-paragraph hp-intro__paragraph">
                                <?php echo dhp_esc_html($paragraph); ?>
                            </p>
                        <?php endforeach; ?>

                        <?php if ($byline_label): ?>
                            <div class="hp-byline hp-intro__byline">
                                <img src="<?php echo esc_url(dhp_asset_image_url('por-divergentes.png')); ?>"
                                     alt="<?php echo dhp_esc_attr($byline_label); ?>"
                                     decoding="async"
                                     loading="lazy" />
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- TIMELINE -->
        <div class="hp-track" data-hp-track>
            <div class="hp-track-inner" aria-hidden="true">
                <div class="hp-track-line"></div>
                <div class="hp-track-fill" data-hp-track-fill></div>
            </div>

            <section class="hp-app hp-timeline">
                <div class="hp-container">
                    <div id="<?php echo esc_attr($root_id); ?>"
                         class="hp-root hp-timeline__root"
                         data-config="<?php echo esc_attr($config_json); ?>">
                        <div class="hp-loading" role="status" aria-live="polite">
                            Cargando perfiles...
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
