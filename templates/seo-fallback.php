<?php
/** @var array|null $data */

if (!defined('ABSPATH')) {
    exit;
}

$meta = is_array($data) && isset($data['meta']) ? $data['meta'] : null;
$months = is_array($data) && isset($data['months']) && is_array($data['months']) ? $data['months'] : [];
$events = is_array($data) && isset($data['events']) && is_array($data['events']) ? $data['events'] : [];

$months_by_id = [];
foreach ($months as $m) {
    if (!is_array($m) || empty($m['id'])) {
        continue;
    }
    $months_by_id[(string) $m['id']] = $m;
}
?>

<section class="hp-fallback" aria-label="Contenido">
    <?php if ($meta && !empty($meta['updated_at'])): ?>
        <p><small>Actualizado: <?php echo esc_html((string) $meta['updated_at']); ?></small></p>
    <?php endif; ?>

    <?php if (!empty($months)): ?>
        <h2>Meses</h2>
        <ol>
            <?php foreach ($months as $m): ?>
                <?php
                if (!is_array($m)) {
                    continue;
                }
                $label = isset($m['label']) ? (string) $m['label'] : '';
                $chapter = isset($m['chapter']) ? (string) $m['chapter'] : '';
                ?>
                <li>
                    <strong><?php echo esc_html($label); ?></strong>
                    <?php if ($chapter !== ''): ?>
                        <span> — <?php echo esc_html($chapter); ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <?php if (!empty($events)): ?>
        <h2>Eventos</h2>
        <ol>
            <?php foreach ($events as $e): ?>
                <?php
                if (!is_array($e)) {
                    continue;
                }
                $name = isset($e['name']) ? (string) $e['name'] : '';
                $display_date = isset($e['display_date']) ? (string) $e['display_date'] : '';
                $place = isset($e['place']) ? (string) $e['place'] : '';
                $context = isset($e['context']) ? (string) $e['context'] : '';
                $month_label = '';
                if (!empty($e['month_id']) && isset($months_by_id[(string) $e['month_id']]['label'])) {
                    $month_label = (string) $months_by_id[(string) $e['month_id']]['label'];
                }
                ?>
                <li>
                    <strong><?php echo esc_html($name); ?></strong>
                    <?php if ($display_date !== '' || $place !== ''): ?>
                        <div><small><?php echo esc_html(trim($display_date . ($place ? ' — ' . $place : ''))); ?></small></div>
                    <?php endif; ?>
                    <?php if ($month_label !== ''): ?>
                        <div><small><?php echo esc_html($month_label); ?></small></div>
                    <?php endif; ?>
                    <?php if ($context !== ''): ?>
                        <p><?php echo esc_html($context); ?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</section>
