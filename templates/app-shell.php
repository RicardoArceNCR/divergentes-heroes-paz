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
            <div class="hp-container hp-intro-container">
                <img class="hp-intro-rail-art"
                    src="<?php echo esc_url(plugins_url('images/hero-connector.png', dirname(__FILE__))); ?>" alt=""
                    aria-hidden="true" decoding="async" loading="lazy" />

                <div class="hp-intro-copy">
                    <p class="hp-lede" data-hp-line-start>
                        <strong>Tras la crisis sociopolítica de abril de 2018</strong> en Nicaragua, el régimen
                        Ortega-Murillo elevó a ciertos civiles armados y policías fallecidos durante las
                        protestas a la categoría de “héroes de la paz”. Entre ellos figuran militantes
                        sandinistas, trabajadores del Estado y miembros de la Policía Nacional que
                        participaron en operativos de represión contra manifestantes y en el
                        desmantelamiento de tranques.
                        <br><br>
                        El relato oficial justifica estas muertes como actos de lealtad y defensa de la paz,
                        mientras documentos de organismos internacionales como la CIDH y el GIEI evidencian
                        sus responsabilidades en la violencia letal, tortura y represión sistemática contra
                        manifestantes.
                    </p>

                    <div class="hp-byline">
                        <img src="<?php echo esc_url(plugins_url('images/por-divergentes.png', dirname(__FILE__))); ?>"
                            alt="Por Divergentes" decoding="async" loading="lazy" />
                    </div>
                </div>
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
                    $json_data = null;
                    $local_path = '';

                    // Try to resolve data_url to a local path
                    if (strpos($data_url, content_url()) !== false) {
                        $local_path = str_replace(content_url(), WP_CONTENT_DIR, $data_url);
                    }

                    if ($local_path && file_exists($local_path)) {
                        $json_data = json_decode(file_get_contents($local_path), true);
                    }

                    // Fallback to internal if still null
                    if (!$json_data) {
                        $internal_path = dirname(plugin_dir_path(__FILE__)) . '/data/heroes.json';
                        if (file_exists($internal_path)) {
                            $json_data = json_decode(file_get_contents($internal_path), true);
                        }
                    }

                    // For the seo-fallback.php inclusion, it expects $data variable
                    $data = $json_data;
                    include dirname(__FILE__) . '/seo-fallback.php';
                    ?>
                </div>
            </div>
        </section>
    </div>
</section>