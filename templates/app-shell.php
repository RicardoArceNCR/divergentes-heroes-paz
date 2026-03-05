<?php
/** @var string $root_id */
/** @var string $config_json */

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="hp-shell" data-theme="<?php echo esc_attr($theme ?? 'default'); ?>"
    data-layout="<?php echo esc_attr($layout ?? 'contained'); ?>" <?php echo !empty($shell_style_attr) ? ' style="' . esc_attr($shell_style_attr) . '"' : ''; ?>>
    <section class="hp-hero" aria-label="Héroes de la Paz">
        <div class="hp-container">
            <h1 class="hp-title">Los “Héroes de la Paz”</h1>
            <p class="hp-subtitle">Quiénes son en realidad los sandinistas que el régimen Ortega-Murillo glorifica</p>
        </div>
    </section>

    <div class="hp-track" data-hp-track>
        <div class="hp-track-inner" aria-hidden="true">
            <div class="hp-track-line"></div>
            <div class="hp-track-fill" data-hp-track-fill></div>
        </div>

        <section class="hp-intro">
            <div class="hp-container">
                <p class="hp-lede" data-hp-line-start>Esta línea de tiempo presenta casos y contexto, con perfiles
                    ampliados por persona.</p>
            </div>
        </section>

        <section class="hp-app">
            <div class="hp-container">
                <div id="<?php echo esc_attr($root_id); ?>" class="hp-root"
                    data-config='<?php echo esc_attr($config_json); ?>'>
                    <div class="hp-loading" role="status" aria-live="polite">Cargando…</div>
                </div>

                <div class="hp-fallback-wrap" data-hp-fallback>
                    <?php
                    $plugin_dir = plugin_dir_path(__FILE__);
                    $json_path = dirname($plugin_dir) . '/data/heroes.json';
                    $data = null;
                    if (file_exists($json_path)) {
                        $raw = file_get_contents($json_path);
                        $data = json_decode($raw, true);
                    }
                    include dirname(__FILE__) . '/seo-fallback.php';
                    ?>
                </div>
            </div>
        </section>
    </div>
</section>