# Guía de Validación Final - Fullbleed WordPress FSE

## ✅ Versión Final Implementada

He reemplazado `assets/css/page.css` con la **versión final de 10 puntos quirúrgicos** diseñada para:

- ✅ Mantener header/footer intactos
- ✅ Neutralizar las 3 capas conflictivas de WordPress
- ✅ Forzar fullbleed real del especial
- ✅ Control editorial solo con `.hp-container`

## 🎯 Diagnóstico Correcto

El HTML no cambia (correcto), pero ahora nuestros overrides CSS deben ganarle a WordPress:

```
Capa 1: <main style="margin-top:var(--wp--preset--spacing--60)">
Capa 2: <div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60)">
Capa 3: <div class="entry-content has-global-padding is-layout-constrained">
```

## 🔍 Guía de Validación DevTools

### Preparación
1. **Hard Refresh:** `Cmd+Shift+R` (Mac) o `Ctrl+Shift+R` (Windows)
2. **Abrir DevTools:** `F12` o `Cmd+Option+I`
3. **Ir a pestaña "Computed"**

---

### 📋 Validación 1 - Main (Aire Superior)

**Inspeccionar:**
```html
<main class="wp-block-group has-global-padding is-layout-constrained" style="margin-top:var(--wp--preset--spacing--60)">
```

**Debe quedar computado como:**
- ✅ `margin-top: 0`

**Si sigue mostrando `var(--wp--preset--spacing--60)`:**
- Tu override no está ganando
- Revisa que page.css esté cargando
- Verifica especificidad CSS

---

### 📋 Validación 2 - Wrapper Interno (Padding Vertical)

**Inspeccionar:**
```html
<div class="wp-block-group alignfull has-global-padding is-layout-constrained" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
```

**Debe quedar computado como:**
- ✅ `padding-top: 0`
- ✅ `padding-bottom: 0`

**Si sigue con padding vertical:**
- El wrapper sigue empujando aire al especial
- Revisa el selector `body.page-id-102 main > .wp-block-group`

---

### 📋 Validación 3 - Entry Content (Padding Lateral)

**Inspeccionar:**
```html
<div class="entry-content alignfull wp-block-post-content has-global-padding is-layout-constrained">
```

**Debe quedar computado como:**
- ✅ `padding-left: 0`
- ✅ `padding-right: 0`
- ✅ `max-width: none`

**Si conserva padding lateral:**
- Ahí sigue el aire lateral del contenedor
- El especial no puede alcanzar fullbleed

---

### 📋 Validación 4 - Wrapper del Especial (Fullbleed Real)

**Inspeccionar:**
```html
<div class="hp-wp-wrap hp-wp-wrap--fullbleed alignfull">
```

**Debe quedar computado como:**
- ✅ `width: 100vw`
- ✅ `max-width: 100vw`
- ✅ `margin-left: calc(50% - 50vw)` (valor negativo)
- ✅ `margin-right: calc(50% - 50vw)` (valor negativo)
- ✅ `padding-left: 0`
- ✅ `padding-right: 0`

**Si no aparece aplicado:**
- El fullbleed no está realmente blindado
- El especial sigue contenido

---

### 📋 Validación 5 - Hero (Ancho Visual)

**Inspeccionar:**
```html
<section class="hp-hero">
```

**Debe medir:**
- ✅ Ancho del viewport (no 645px ni 1340px)

**Si sigue contenido:**
- WordPress sigue ganando la pelea de layout
- Revisa blindaje de `.hp-hero`

---

### 📋 Validación 6 - Container Editorial (Control Interno)

**Inspeccionar:**
```html
<div class="hp-container hp-hero-container">
```

**Debe quedar computado como:**
- ✅ `width: 100%`
- ✅ `max-width: 1200px`
- ✅ `margin-left: auto`
- ✅ `margin-right: auto`
- ✅ `padding-left: 24px`
- ✅ `padding-right: 24px`

**Esto es correcto:** Control editorial dentro del fullbleed

---

## 🚀 Resultados Esperados

### ✅ Si Todo Funciona
- ❌ **Sin franja blanca superior**
- ❌ **Sin aire lateral**
- ❌ **Sin sensación de contenedor**
- ✅ **Hero ocupa todo el ancho visible**
- ✅ **Texto centrado y legible**
- ✅ **Header/footer intactos**

### ❌ Si Hay Problemas
**Aire lateral persistente:**
- Revisa Validación 3 (entry-content)
- Verifica que no haya CSS conflicting

**Espacio superior:**
- Revisa Validación 1 (main)
- Verifica Validación 2 (wrapper interno)

**Scroll horizontal:**
- Revisa que ningún elemento exceda 100vw
- Verifica `box-sizing: border-box`

---

## ⚠️ Solución Alternativa

Si persiste aire lateral después de validar, reemplaza los bloques 5 y 6:

```css
/* 5) Fullbleed alternativo */
body.page-id-102 .hp-wp-wrap--fullbleed,
body.page-id-102 .hp-wp-wrap.hp-wp-wrap--fullbleed.alignfull {
  width: auto;
  max-width: none;
  margin-left: calc(50% - 50vw) !important;
  margin-right: calc(50% - 50vw) !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  box-sizing: border-box;
}
```

---

## 📋 Checklist Final

Antes de concluir, verifica:

- [ ] Hard refresh completado
- [ ] Main: `margin-top: 0` ✅
- [ ] Wrapper interno: `padding: 0` ✅
- [ ] Entry content: `padding: 0, max-width: none` ✅
- [ ] Wrapper especial: `100vw` con calc margins ✅
- [ ] Hero: ancho completo ✅
- [ ] Container: 1200px max ✅
- [ ] Sin scroll horizontal ✅
- [ ] Header/footer intactos ✅

---

## 🎯 Veredicto Final

**Estado actual:** ✅ **Solución final implementada**

**Próximo paso:** Validación DevTools de los 4 nodos críticos

Si las 4 validaciones principales pasan, el problema está resuelto. Si alguna falla, el issue está en ese punto específico.

---

**Importante:** No toques `.wp-site-blocks`, `.has-global-padding` o `.is-layout-constrained` a nivel global. La solución es quirúrgica y solo afecta `body.page-id-102`.

