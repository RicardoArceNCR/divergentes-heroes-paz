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
                // Support both old (display_date) and new (date) schemas
                $display_date = isset($e['display_date']) ? (string) $e['display_date'] : '';
                if ($display_date === '' && !empty($e['date'])) {
                    $display_date = (string) $e['date'];
                }
                // Support both old (place) and new (location) schemas
                $place = isset($e['place']) ? (string) $e['place'] : '';
                if ($place === '' && !empty($e['location'])) {
                    $place = (string) $e['location'];
                }
                // Support both old (context) and new (summary) schemas
                $context = isset($e['context']) ? (string) $e['context'] : '';
                if ($context === '' && !empty($e['summary'])) {
                    $context = (string) $e['summary'];
                }
                // Role field (new schema)
                $role = isset($e['role']) ? (string) $e['role'] : '';
                // month_id: support both month_id and monthId
                $month_id_val = '';
                if (!empty($e['month_id'])) {
                    $month_id_val = (string) $e['month_id'];
                } elseif (!empty($e['monthId'])) {
                    $month_id_val = (string) $e['monthId'];
                }
                $month_label = '';
                if ($month_id_val !== '' && isset($months_by_id[$month_id_val]['label'])) {
                    $month_label = (string) $months_by_id[$month_id_val]['label'];
                }
                ?>
                <li>
                    <strong><?php echo esc_html($name); ?></strong>
                    <?php if ($role !== ''): ?>
                        <div><em><?php echo esc_html($role); ?></em></div>
                    <?php endif; ?>
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