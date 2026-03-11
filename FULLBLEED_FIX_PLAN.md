# Plan de Acción - Solución Fullbleed Quirúrgica

## ✅ Diagnóstico Correcto

El problema no estaba en nuestro wrapper `.hp-wp-wrap--fullbleed`, sino en las **3 capas de WordPress** que lo rodean:

```
Capa 1: <main style="margin-top: var(--wp--preset--spacing--60)">
Capa 2: <div class="wp-block-group alignfull has-global-padding is-layout-constrained" 
        style="padding-top: var(--wp--preset--spacing--60); padding-bottom: var(--wp--preset--spacing--60)">
Capa 3: <div class="entry-content alignfull wp-block-post-content has-global-padding is-layout-constrained">
```

## 🎯 Estrategia Implementada

**Archivo modificado:** `assets/css/page.css`
**Método:** CSS quirúrgico con `body.page-id-102` (solo esta página)
**Objetivo:** Neutralizar las capas de WordPress sin romper header/footer

## ✅ Pasos Ejecutados

### 1. ✅ Neutralizar margin-top del main
```css
body.page-id-102 main.wp-block-group.has-global-padding.is-layout-constrained {
  margin-top: 0 !important;
}
```
**Resultado:** Elimina franja blanca superior

### 2. ✅ Quitar padding vertical del wrapper interno
```css
body.page-id-102 main > .wp-block-group.alignfull.has-global-padding.is-layout-constrained {
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}
```
**Resultado:** Elimina aire arriba y abajo del especial

### 3. ✅ Quitar padding lateral del entry-content
```css
body.page-id-102 .entry-content.alignfull.wp-block-post-content.has-global-padding.is-layout-constrained {
  padding-left: 0 !important;
  padding-right: 0 !important;
  max-width: none !important;
}
```
**Resultado:** Evita que WordPress empuje el especial hacia adentro

### 4. ✅ Reforzar fullbleed del wrapper del especial
```css
body.page-id-102 .hp-wp-wrap--fullbleed {
  width: 100vw;
  max-width: 100vw;
  margin-left: calc(50% - 50vw) !important;
  margin-right: calc(50% - 50vw) !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}
```
**Resultado:** Wrapper ocupa viewport completo

### 5. ✅ Impedir que wrappers padre recorten el especial
```css
body.page-id-102 .is-layout-constrained > .hp-wp-wrap--fullbleed,
body.page-id-102 .wp-block-post-content.is-layout-constrained > .hp-wp-wrap--fullbleed,
body.page-id-102 .entry-content > .hp-wp-wrap--fullbleed {
  max-width: none !important;
}
```
**Resultado:** Sin restricciones de ancho heredadas

### 6. ✅ Evitar márgenes verticales heredados
```css
body.page-id-102 .entry-content > .hp-wp-wrap--fullbleed {
  margin-top: 0 !important;
  margin-bottom: 0 !important;
}
```
**Resultado:** Sin spacing vertical accidental

### 7. ✅ Control editorial interno verificado
```css
.hp-shell .hp-container {
  width: min(100% - 32px, var(--hp-container-max));
  margin-inline: auto;
}
```
**Resultado:** Contenido editorial controlado dentro del fullbleed

## 🔍 Guía de Validación DevTools

### Preparación
1. **Hard Refresh:** `Cmd+Shift+R` (Mac) o `Ctrl+Shift+R` (Windows)
2. **Abrir DevTools:** `F12` o `Cmd+Option+I`

### Validación 1 - Main
**Inspeccionar:** `<main class="wp-block-group has-global-padding is-layout-constrained">`

**Debe mostrar:**
- ✅ `margin-top: 0` (sin `var(--wp--preset--spacing--60)`)

### Validación 2 - Wrapper Interno
**Inspeccionar:** `<div class="wp-block-group alignfull has-global-padding is-layout-constrained">`

**Debe mostrar:**
- ✅ `padding-top: 0`
- ✅ `padding-bottom: 0`

### Validación 3 - Entry Content
**Inspeccionar:** `<div class="entry-content alignfull wp-block-post-content has-global-padding is-layout-constrained">`

**Debe mostrar:**
- ✅ `padding-left: 0`
- ✅ `padding-right: 0`
- ✅ `max-width: none`

### Validación 4 - Wrapper del Especial
**Inspeccionar:** `<div class="hp-wp-wrap hp-wp-wrap--fullbleed alignfull">`

**Debe mostrar:**
- ✅ `width: 100vw`
- ✅ `max-width: 100vw`
- ✅ `margin-left: calc(50% - 50vw)` (valor negativo)
- ✅ `margin-right: calc(50% - 50vw)` (valor negativo)

### Validación 5 - Contenido Editorial
**Inspeccionar:** `<div class="hp-container hp-hero-container">`

**Debe mostrar:**
- ✅ `width: min(100% - 32px, var(--hp-container-max))`
- ✅ Ancho controlado (ej: ~1200px en desktop)

## 🎯 Resultado Esperado

### Visual
- ❌ **Sin franja blanca superior**
- ❌ **Sin aire lateral**
- ❌ **Sin sensación de contenedor**
- ✅ **Hero ocupa todo el ancho visible**
- ✅ **Texto dentro del container es legible y centrado**

### Estructural
```
Header (WordPress) - ✅ Intacto
├── Main (margin-top: 0) - ✅ Neutralizado
    ├── Wrapper WP (padding: 0) - ✅ Neutralizado
        ├── Entry Content (padding: 0, max-width: none) - ✅ Neutralizado
            ├── hp-wp-wrap--fullbleed (100vw) - ✅ Full viewport
                ├── hp-shell (max-width: none) - ✅ Sin restricciones
                    ├── hp-hero (max-width: none) - ✅ Full width
                        └── hp-container (1200px max) - ✅ Control editorial
Footer (WordPress) - ✅ Intacto
```

## 🚀 Tests Adicionales

### Test 1: Responsive
- **Mobile:** Sin scroll horizontal
- **Tablet:** Layout se adapta
- **Desktop:** Fullbleed mantenido

### Test 2: WordPress
- **Otras páginas:** No afectadas
- **Header/Footer:** Intactos
- **Editor Gutenberg:** Funciona

### Test 3: Navegación
- **Scroll:** Suave sin saltos
- **Deep linking:** Funciona
- **Interactive elements:** Operativos

## ⚠️ Si Sigue Habiendo Problemas

### Aire lateral persistente
Verificar que no haya CSS conflicting:
- Theme customizer
- Otros plugins
- Cache de navegador

### Scroll horizontal
Revisar:
- Que no haya elementos con `width > 100vw`
- Padding/margin incorrectos
- Box-sizing issues

## 📋 Checklist Final

- [ ] Hard refresh completado
- [ ] Main con `margin-top: 0`
- [ ] Wrapper interno con `padding: 0`
- [ ] Entry content sin padding lateral
- [ ] Wrapper especial con `100vw`
- [ ] Container editorial con ancho controlado
- [ ] Sin scroll horizontal
- [ ] Header/footer intactos
- [ ] Responsive funciona

---

**Estado:** ✅ **Solución quirúrgica implementada y lista para validación**
