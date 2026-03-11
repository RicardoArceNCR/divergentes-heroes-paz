# Guía Quirúrgica: Fix Fullbleed con Franja Beige Lateral

## Problema Identificado

El `.hp-wp-wrap--fullbleed` está usando `width: 100vw` + `calc(50% - 50vw)` que compite con las reglas de Gutenberg, causando desborde horizontal y una franja beige lateral.

## Diagnóstico

**Causa principal:** `width: 100vw` cuenta el scrollbar, mientras que el layout de Gutenberg ya maneja `alignfull` con sus propias reglas de padding.

**Conflicto:** Dos sistemas compitiendo:
- WordPress/Gutenberg: `alignfull` + `--wp--style--root--padding-*`
- Plugin: `100vw` + `calc(50% - 50vw)`

## Solución Quirúrgica

### Paso 1: Reemplazar Bloque Problemático

**Archivo:** `assets/css/page.css`

**Buscar y reemplazar completamente este bloque:**

```css
body.hp-has-fullbleed .hp-wp-wrap--fullbleed,
body.hp-has-fullbleed .hp-wp-wrap.hp-wp-wrap--fullbleed.alignfull {
  width: 100vw;
  max-width: 100vw;
  margin-left: calc(50% - 50vw) !important;
  margin-right: calc(50% - 50vw) !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  box-sizing: border-box;
}
```

**Por este bloque corregido:**

```css
body.hp-has-fullbleed .hp-wp-wrap--fullbleed,
body.hp-has-fullbleed .hp-wp-wrap.hp-wp-wrap--fullbleed.alignfull {
  width: auto;
  max-width: none !important;
  margin-left: calc(var(--wp--style--root--padding-left, 0px) * -1) !important;
  margin-right: calc(var(--wp--style--root--padding-right, 0px) * -1) !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  box-sizing: border-box;
}
```

### Paso 2: Reforzar Control de Overflow

**Agregar después de `body.hp-has-fullbleed { overflow-x: clip; }`:**

```css
body.hp-has-fullbleed .wp-site-blocks,
body.hp-has-fullbleed main,
body.hp-has-fullbleed .entry-content {
  overflow-x: clip;
}
```

### Paso 3: Verificar en DevTools

**Para confirmar el diagnóstico:**

1. **Desactivar temporalmente las reglas problemáticas:**
   - Desmarcar: `width: 100vw`
   - Desmarcar: `max-width: 100vw` 
   - Desmarcar: `margin-left: calc(50% - 50vw)`
   - Desmarcar: `margin-right: calc(50% - 50vw)`

2. **Verificar overflow horizontal:**
   ```javascript
   document.documentElement.scrollWidth > document.documentElement.clientWidth
   ```
   - Si devuelve `true`, hay overflow horizontal

3. **Comparar viewport vs client:**
   ```javascript
   console.log('innerWidth:', window.innerWidth);
   console.log('clientWidth:', document.documentElement.clientWidth);
   ```
   - Si `innerWidth` es mayor, el scrollbar está afectando el cálculo

### Paso 4: Si Persiste el Problema

**Opcional: Simplificar HTML**

**Archivo:** `templates/app-shell.php` (línea 29)

**Si el problema persiste, considerar:**

```php
<div class="hp-wp-wrap hp-wp-wrap--<?php echo dhp_esc_attr($layout); ?>">
```

**En lugar de:**

```php
<div class="hp-wp-wrap hp-wp-wrap--<?php echo dhp_esc_attr($layout); ?> alignfull">
```

**Por qué:** Evita doble competencia entre tu sistema `fullbleed` y el `alignfull` de Gutenberg.

## Código Final Recomendado

```css
/* Control de overflow principal */
body.hp-has-fullbleed {
  overflow-x: clip;
}

/* Refuerzo de overflow en contenedores WP */
body.hp-has-fullbleed .wp-site-blocks,
body.hp-has-fullbleed main,
body.hp-has-fullbleed .entry-content {
  overflow-x: clip;
}

/* Fullbleed corregido - sin 100vw */
body.hp-has-fullbleed .hp-wp-wrap--fullbleed,
body.hp-has-fullbleed .hp-wp-wrap.hp-wp-wrap--fullbleed.alignfull {
  width: auto;
  max-width: none !important;
  margin-left: calc(var(--wp--style--root--padding-left, 0px) * -1) !important;
  margin-right: calc(var(--wp--style--root--padding-right, 0px) * -1) !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  box-sizing: border-box;
}
```

## Por Qué Esta Solución Funciona

1. **Elimina dependencia de 100vw:** Evita contar el scrollbar
2. **Respeta padding global del theme:** Usa las variables CSS de Gutenberg
3. **Reduce conflicto de sistemas:** No compite con alignfull
4. **Previene overflow horizontal:** Control explícito con overflow-x: clip
5. **Más robusto en responsive:** Funciona mejor en diferentes viewports

## Testing Post-Cambio

1. **Recargar dura** (Ctrl/Cmd + Shift + R)
2. **Verificar desktop y mobile**
3. **Revisar que no haya franja lateral**
4. **Validar que el fullbleed funcione correctamente**
5. **Probar scroll horizontal**

## Si Necesitas Revertir

Si algo sale mal, el bloque original era:

```css
body.hp-has-fullbleed .hp-wp-wrap--fullbleed,
body.hp-has-fullbleed .hp-wp-wrap.hp-wp-wrap--fullbleed.alignfull {
  width: 100vw;
  max-width: 100vw;
  margin-left: calc(50% - 50vw) !important;
  margin-right: calc(50% - 50vw) !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  box-sizing: border-box;
}
```

## Resumen del Cambio Clave

**El cambio es uno solo:** Matar `100vw` y usar compensación por padding global de WordPress. Eso es lo que está rompiendo el responsive.
