<?php
/** @var array $data */
/** @var array $config */
/** @var string $root_id */
/** @var string $config_json */

if (!defined('ABSPATH')) {
    exit;
}

$meta = $data['meta'] ?? [];
$intro = $data['intro'] ?? [];
$months = $data['months'] ?? [];
$events = $data['events'] ?? [];

$title = $meta['title'] ?? '';
$subtitle = $meta['subtitle'] ?? '';
$paragraphs = $intro['paragraphs'] ?? [];
$byline_label = $intro['byline_label'] ?? 'Por Divergentes';
$hero_image = $intro['hero_image'] ?? '';
$layout = $config['layout'] ?? 'fullbleed';
$theme = $config['theme'] ?? 'editorial';
?>

<div class="hp-wp-wrap hp-wp-wrap--<?php echo dhp_esc_attr($layout); ?>">
    <section class="hp-shell"
             data-theme="<?php echo dhp_esc_attr($theme); ?>"
             data-layout="<?php echo dhp_esc_attr($layout); ?>">

        <!-- PORTADA -->
        <section class="hp-hero" aria-label="Héroes de la Paz">
            <div class="hp-container hp-hero-container">
                <div class="hp-hero-copy">
                    <?php if ($title): ?>
                        <h1 class="hp-title"><?php echo dhp_esc_html($title); ?></h1>
                    <?php endif; ?>

                    <?php if ($subtitle): ?>
                        <p class="hp-subtitle"><?php echo dhp_esc_html($subtitle); ?></p>
                    <?php endif; ?>

                    <?php if ($byline_label): ?>
                        <div class="hp-hero-byline-text">
                            <span><?php echo dhp_esc_html($byline_label); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($hero_image): ?>
                    <div class="hp-hero-image">
                        <img src="<?php echo dhp_asset_image_url($hero_image); ?>"
                             alt="<?php echo dhp_esc_attr($title); ?>"
                             loading="eager"
                             decoding="async">
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- INTRO EDITORIAL -->
        <?php if (!empty($paragraphs)): ?>
            <section class="hp-intro">
                <div class="hp-container hp-intro-container">
                    <img class="hp-intro-rail-art"
                         src="<?php echo esc_url(plugins_url('assets/images/hero-connector.png', DHP_FILE)); ?>"
                         alt=""
                         aria-hidden="true"
                         decoding="async"
                         loading="lazy" />

                    <div class="hp-intro-copy">
                        <?php
                        $first_paragraph = $paragraphs[0] ?? '';
                        $remaining_paragraphs = array_slice($paragraphs, 1);
                        ?>

                        <?php if ($first_paragraph): ?>
                            <p class="hp-lede" data-hp-line-start>
                                <?php echo dhp_esc_html($first_paragraph); ?>
                            </p>
                        <?php endif; ?>

                        <?php foreach ($remaining_paragraphs as $paragraph): ?>
                            <p class="hp-intro-paragraph">
                                <?php echo dhp_esc_html($paragraph); ?>
                            </p>
                        <?php endforeach; ?>

                        <?php if ($byline_label): ?>
                            <div class="hp-byline">
                                <img src="<?php echo esc_url(plugins_url('assets/images/por-divergentes.png', DHP_FILE)); ?>"
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

            <section class="hp-app">
                <div class="hp-container">
                    <div id="<?php echo esc_attr($root_id); ?>"
                         class="hp-root"
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
