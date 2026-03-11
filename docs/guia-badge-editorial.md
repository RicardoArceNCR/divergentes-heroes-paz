# Guía de Implementación: Badge Editorial para Hero

## Objetivo

Transformar el título actual del hero en una composición editorial con un badge "SIN PASAMONTAÑAS" integrado visualmente.

## Estado Actual

**HTML actual (línea 39 en `templates/app-shell.php`):**
```php
<h1 class="hp-title hp-hero__title"><?php echo dhp_esc_html($title); ?></h1>
```

**CSS actual del título (en `assets/css/theme-editorial.css`):**
```css
.hp-hero__title,
.hp-hero .hp-title {
  margin: 0;
  max-width: 14ch;
  font-family: var(--hp-font-display);
  font-size: clamp(3rem, 8vw, 6.75rem);
  font-weight: var(--hp-weight-display);
  line-height: 0.9;
  letter-spacing: -0.02em;
  text-transform: uppercase;
  text-wrap: balance;
}
```

## Implementación Paso a Paso

### Paso 1: Modificar el HTML del Hero

**Archivo:** `templates/app-shell.php` (línea 39)

**Reemplazar:**
```php
<h1 class="hp-title hp-hero__title"><?php echo dhp_esc_html($title); ?></h1>
```

**Por:**
```php
<h1 class="hp-title hp-hero__title">
  <span class="hp-hero-title-main">Los "héroes de la paz"</span>
  <span class="hp-hero-highlight" aria-label="sin pasamontañas">
    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--tl" aria-hidden="true"></span>
    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--tr" aria-hidden="true"></span>
    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--bl" aria-hidden="true"></span>
    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--br" aria-hidden="true"></span>
    <span class="hp-hero-highlight__lead">SIN</span>
    <span class="hp-hero-highlight__text">PASAMONTAÑAS</span>
  </span>
</h1>
```

**Nota:** Para hacerlo dinámico, podrías usar:
```php
<h1 class="hp-title hp-hero__title">
  <?php 
  $title_parts = explode(' sin ', $title, 2);
  if (count($title_parts) === 2) {
    echo '<span class="hp-hero-title-main">' . dhp_esc_html($title_parts[0]) . '</span>';
    echo '<span class="hp-hero-highlight" aria-label="sin ' . dhp_esc_attr($title_parts[1]) . '">';
    // ... estructura del badge ...
    $badge_parts = explode(' ', $title_parts[1], 2);
    echo '<span class="hp-hero-highlight__lead">' . dhp_esc_html(ucfirst($badge_parts[0])) . '</span>';
    if (isset($badge_parts[1])) {
      echo '<span class="hp-hero-highlight__text">' . dhp_esc_html(strtoupper($badge_parts[1])) . '</span>';
    }
    echo '</span>';
  } else {
    echo dhp_esc_html($title);
  }
  ?>
</h1>
```

### Paso 2: Actualizar CSS del Título Principal

**Archivo:** `assets/css/theme-editorial.css` (alrededor de línea 94)

**Reemplazar el bloque actual:**
```css
.hp-hero__title,
.hp-hero .hp-title {
  margin: 0;
  max-width: 14ch;
  font-family: var(--hp-font-display);
  font-size: clamp(3rem, 8vw, 6.75rem);
  font-weight: var(--hp-weight-display);
  line-height: 0.9;
  letter-spacing: -0.02em;
  text-transform: uppercase;
  text-wrap: balance;
}
```

**Por:**
```css
.hp-hero__title,
.hp-hero .hp-title {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.28em;
  margin: 0;
  text-align: center;
  max-width: 12ch;
}

.hp-hero-title-main {
  display: block;
  max-width: 100%;
  text-wrap: balance;
  font-family: var(--hp-font-display);
  font-size: clamp(3rem, 8vw, 6.75rem);
  font-weight: var(--hp-weight-display);
  line-height: 0.9;
  letter-spacing: -0.02em;
  text-transform: uppercase;
}
```

### Paso 3: Agregar CSS del Badge Editorial

**Archivo:** `assets/css/app.css` (agregar después de la sección HERO LAYOUT, alrededor de línea 210)

```css
/* ==========================================================================
   HERO HIGHLIGHT BADGE
   ========================================================================== */

.hp-hero-highlight {
  position: relative;
  display: inline-flex;
  align-items: stretch;
  justify-content: center;
  flex-wrap: nowrap;
  width: fit-content;
  max-width: 100%;
  margin-top: 0.08em;
  border: 0.08em solid var(--hp-accent, #e10600);
  background: transparent;
  overflow: visible;

  font-family: var(--hp-font-ui, var(--font-family-ui));
  font-size: 0.24em;
  font-weight: 700;
  line-height: 1;
  letter-spacing: 0.14em;
  text-transform: uppercase;
}

.hp-hero-highlight__lead,
.hp-hero-highlight__text {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.42em 0.7em;
  white-space: nowrap;
}

.hp-hero-highlight__lead {
  background: var(--hp-accent, #e10600);
  color: #fff;
}

.hp-hero-highlight__text {
  background: rgba(0, 0, 0, 0.92);
  color: #fff;
}

.hp-hero-highlight__corner {
  position: absolute;
  width: 0.55em;
  height: 0.55em;
  pointer-events: none;
}

.hp-hero-highlight__corner--tl {
  top: -0.34em;
  left: -0.34em;
  border-top: 0.08em solid var(--hp-accent, #e10600);
  border-left: 0.08em solid var(--hp-accent, #e10600);
}

.hp-hero-highlight__corner--tr {
  top: -0.34em;
  right: -0.34em;
  border-top: 0.08em solid var(--hp-accent, #e10600);
  border-right: 0.08em solid var(--hp-accent, #e10600);
}

.hp-hero-highlight__corner--bl {
  bottom: -0.34em;
  left: -0.34em;
  border-bottom: 0.08em solid var(--hp-accent, #e10600);
  border-left: 0.08em solid var(--hp-accent, #e10600);
}

.hp-hero-highlight__corner--br {
  bottom: -0.34em;
  right: -0.34em;
  border-bottom: 0.08em solid var(--hp-accent, #e10600);
  border-right: 0.08em solid var(--hp-accent, #e10600);
}
```

### Paso 4: Agregar Responsive del Badge

**Archivo:** `assets/css/app.css` (agregar en la sección RESPONSIVE, alrededor de línea 889)

```css
/* Responsive adjustments for hero badge */
@media (max-width: 900px) {
  .hp-hero__title,
  .hp-hero .hp-title {
    gap: 0.22em;
    max-width: 11ch;
  }

  .hp-hero-highlight {
    font-size: 0.3em;
    letter-spacing: 0.1em;
  }

  .hp-hero-highlight__lead,
  .hp-hero-highlight__text {
    padding: 0.38em 0.56em;
  }
}

@media (max-width: 640px) {
  .hp-hero-highlight {
    font-size: 0.27em;
    max-width: 92%;
  }

  .hp-hero-highlight__text {
    white-space: normal;
  }
}
```

### Paso 5: Actualizar Responsive del Título Principal

**Archivo:** `assets/css/theme-editorial.css` (actualizar las media queries existentes)

**En la media query de 900px (alrededor de línea 148):**
```css
.hp-hero__title,
.hp-hero .hp-title {
  max-width: 11ch;
}

.hp-hero-title-main {
  font-size: clamp(2.4rem, 11vw, 4.6rem);
  line-height: 0.95;
}
```

**En la media query de 640px (alrededor de línea 170):**
```css
.hp-hero__title,
.hp-hero .hp-title {
  max-width: 10ch;
}

.hp-hero-title-main {
  font-size: clamp(2.2rem, 12vw, 3.7rem);
}
```

## Resultado Visual Esperado

```
LOS "HÉROES
DE LA PAZ"
   [ SIN ][ PASAMONTAÑAS ]
```

- **Bloque rojo** para "SIN"
- **Bloque negro** para "PASAMONTAÑAS" 
- **Borde rojo** general
- **Esquinas rojas** tipo marco editorial
- **Responsive** que escala con el título principal

## Ventajas de esta Implementación

1. **Semántica correcta**: El texto completo permanece dentro del h1
2. **Accesibilidad**: aria-label proporciona contexto completo
3. **SEO**: Título completo indexable
4. **Reusable**: Sistema de clases modular
5. **Maintenable**: Separación clara de responsabilidades
6. **Responsive**: Escala proporcionalmente con el título

## Consideraciones Técnicas

1. **Unidades relativas**: Todo usa `em` relativo al tamaño del título
2. **Tokens existentes**: Reutiliza variables del sistema actual
3. **Compatibilidad**: Mantiene clases existentes para backward compatibility
4. **Performance**: CSS optimizado sin selectores complejos

## Testing

1. **Desktop**: Verificar proporciones y esquinas
2. **Tablet**: Comprobar ajuste de espaciado
3. **Mobile**: Validar legibilidad y wrapping
4. **Accesibilidad**: Verificar lector de pantalla
5. **Cross-browser**: Testing en navegadores principales

## Alternativas

### Opción Simple (sin esquinas)
Si las esquinas decorativas no son críticas, puedes omitir los spans `.hp-hero-highlight__corner` y su CSS correspondiente.

### Opción Dinámica Avanzada
Para hacerlo completamente dinámico desde WordPress, considera:
1. Crear campos personalizados para el título y badge
2. Procesar el texto en PHP para separar componentes
3. Permitir configuración de colores y estilos desde admin

## Mantenimiento

- **CSS**: Los estilos del badge viven en `app.css` sección HERO HIGHLIGHT BADGE
- **HTML**: La estructura está en `templates/app-shell.php`
- **Tokens**: Usa tokens existentes del sistema editorial
- **Responsive**: Las media queries están en ambos archivos según corresponda
