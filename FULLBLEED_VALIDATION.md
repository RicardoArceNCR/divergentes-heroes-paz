# Guía de Ejecución - Fullbleed Correcto

## Estrategia Implementada

✅ **Archivo modificado:** `assets/css/page.css`
✅ **Método:** WordPress shell compatibility (sin tocar header/footer)
✅ **Resultado:** Wrapper fullbleed real + contenido editorial controlado

## Pasos Ejecutados

### 1. ✅ Wrapper Fullbleed Real
```css
.hp-wp-wrap--fullbleed {
  width: 100vw;
  max-width: 100vw;
  margin-left: calc(50% - 50vw);
  margin-right: calc(50% - 50vw);
  padding-left: 0;
  padding-right: 0;
}
```

### 2. ✅ Refuerzo con alignfull
```css
.hp-wp-wrap.hp-wp-wrap--fullbleed.alignfull {
  width: 100vw;
  max-width: 100vw;
  margin-left: calc(50% - 50vw);
  margin-right: calc(50% - 50vw);
}
```

### 3. ✅ Neutralizar Padding Heredado
```css
.wp-site-blocks .hp-wp-wrap--fullbleed {
  padding-left: 0 !important;
  padding-right: 0 !important;
}
```

### 4. ✅ Blindar contra is-layout-constrained
```css
.is-layout-constrained > .hp-wp-wrap--fullbleed,
.wp-block-post-content.is-layout-constrained > .hp-wp-wrap--fullbleed,
.entry-content > .hp-wp-wrap--fullbleed {
  max-width: none !important;
}
```

### 5. ✅ Control Editorial Interno
El `.hp-container` ya está configurado correctamente:
```css
.hp-shell .hp-container {
  width: min(100% - 32px, var(--hp-container-max));
  margin-inline: auto;
}
```

### 6. ✅ Protección Main Theme
```css
main .hp-wp-wrap--fullbleed {
  position: relative;
  left: 0;
  right: 0;
}
```

### 7. ✅ Blindar Secciones Internas
```css
.hp-shell,
.hp-hero,
.hp-intro,
.hp-track,
.hp-app {
  max-width: none;
}
```

## Validación Manual - Pasos Exactos

### 📋 Revisión A - Wrapper
**Inspeccionar:** `<div class="hp-wp-wrap hp-wp-wrap--fullbleed alignfull">`

**Debe mostrar:**
- `width: 100vw` (o viewport width)
- `max-width: 100vw`
- `margin-left: calc(50% - 50vw)` (valor negativo)
- `margin-right: calc(50% - 50vw)` (valor negativo)
- `padding-left: 0`
- `padding-right: 0`

### 📋 Revisión B - Hero
**Inspeccionar:** `<section class="hp-hero">`

**Debe mostrar:**
- Ancho completo del wrapper (no debe estar contenido)
- `max-width: none` (sin restricciones)

### 📋 Revisión C - Contenido Editorial
**Inspeccionar:** `<div class="hp-container hp-hero-container">`

**Debe mostrar:**
- Ancho controlado (ej: 1200px o viewport menos padding)
- `width: min(100% - 32px, var(--hp-container-max))`

## Comandos de Validación

### 1. Limpiar caché del navegador
```bash
# En Chrome DevTools:
# Right-click → Reload → Empty Cache and Hard Reload
# O: Cmd+Shift+R (Mac) / Ctrl+Shift+R (Windows)
```

### 2. Inspeccionar elementos
```bash
# Abrir DevTools: F12 o Cmd+Option+I
# Selector: .hp-wp-wrap--fullbleed
# Ver computed styles
```

### 3. Verificar no scroll horizontal
```bash
# Revisar que no aparezca scrollbar horizontal
# Probar responsive: mobile, tablet, desktop
```

## Si Hay Problemas

### Aire lateral persistente (1-2px)
Reemplazar los bloques principales con:
```css
.hp-wp-wrap--fullbleed,
.hp-wp-wrap.hp-wp-wrap--fullbleed.alignfull {
  width: auto;
  max-width: none;
  margin-left: calc(50% - 50vw);
  margin-right: calc(50% - 50vw);
}
```

### Sigue contenido contenido
Verificar que no haya CSS conflicting en:
- Theme styles
- Customizer additional CSS
- Otros plugins

## Estructura HTML Correcta

```html
<div class="hp-wp-wrap hp-wp-wrap--fullbleed alignfull">
  <section class="hp-shell">
    <section class="hp-hero">
      <div class="hp-container hp-hero-container">
        <!-- Contenido editorial -->
      </div>
    </section>
  </section>
</div>
```

## ✅ Qué NO Tocar

- `.wp-site-blocks` global
- `.has-global-padding` global  
- `.is-layout-constrained` global
- Header/footer del theme

## 🎯 Resultado Esperado

1. **Header/Footer:** Intactos del theme
2. **Wrapper:** Full viewport width
3. **Hero:** Full width dentro del wrapper
4. **Contenido:** Ancho editorial controlado por `.hp-container`
5. **Responsive:** Funciona en todos los breakpoints

## 🔍 Tests Adicionales

### Test 1: Visual
- Hero ocupa todo el ancho visible
- Texto dentro del container es legible y centrado

### Test 2: Responsive
- Mobile: Sin scroll horizontal
- Tablet: Layout se adapta correctamente
- Desktop: Fullbleed mantenido

### Test 3: WordPress
- No afecta otras páginas
- Header/footer intactos
- Editor Gutenberg funciona

---

**Estado:** ✅ Implementación completa lista para validación
