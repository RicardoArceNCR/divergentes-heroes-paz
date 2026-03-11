# Guía Final: Corrección Quirúrgica Completa

## Problema Raíz Identificado

Aún tenemos **doble sistema activo**:
- **page.css:** Fullbleed con 100vw roto
- **HTML:** `alignfull` de WordPress + `fullbleed` del plugin
- **app.css:** Container con lógica nueva que desestabilizó timeline

## Solución Definitiva - 4 Pasos Quirúrgicos

### PASO 1: Corregir page.css - Fullbleed Estable

**Archivo:** `assets/css/page.css`

**Buscar y reemplazar este bloque problemático:**
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

**Reemplazar por versión estable:**
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

**Por qué funciona:**
- ❌ Elimina 100vw que cuenta scrollbar
- ✅ Usa padding global de WordPress
- 🎯 Sin competencia con alignfull

### PASO 2: Devolver .hp-container a Lógica Vieja

**Archivo:** `assets/css/app.css`

**Reemplazar bloque actual:**
```css
.hp-shell .hp-container {
  width: min(100% - 32px, var(--hp-container-max));
  margin-inline: auto;
}
```

**Por versión estable:**
```css
.hp-shell .hp-container {
  width: 100%;
  max-width: var(--hp-container-max);
  margin-left: auto;
  margin-right: auto;
  padding-left: 24px;
  padding-right: 24px;
}
```

**Reemplazar media query mobile:**
```css
@media (max-width: 767px) {
  .hp-shell .hp-container {
    width: min(100% - 24px, var(--hp-container-max));
  }

  .hp-shell .hp-reading {
    width: 100%;
  }
}
```

**Por versión estable:**
```css
@media (max-width: 767px) {
  .hp-shell .hp-container {
    width: 100%;
    padding-left: 16px;
    padding-right: 16px;
  }

  .hp-shell .hp-reading {
    width: 100%;
  }
}
```

**Por qué funciona:**
- ❌ Elimina `min()` complejo que causa doble contención
- ✅ Usa lógica simple: width + max-width + padding
- 🎯 Timeline móvil vuelve a estar estable

### PASO 3: Estabilizar Sticky Nav

**Archivo:** `assets/css/app.css`

**Agregar al final del archivo:**
```css
.hp-shell .hp-sticky-nav-inner > * {
  flex: 0 0 auto;
}
```

**Por qué funciona:**
- 🎯 Evita que pills hagan cosas raras en scroll horizontal
- 🔒 Comportamiento predecible en mobile

### PASO 4: Opcional - Eliminar Doble Fullbleed

**Archivo:** `templates/app-shell.php`

**Si después de los 3 pasos anteriores sigue raro:**

**Reemplazar:**
```php
<div class="hp-wp-wrap hp-wp-wrap--fullbleed alignfull">
```

**Por:**
```php
<div class="hp-wp-wrap hp-wp-wrap--fullbleed">
```

**Por qué funciona:**
- 🚫 Elimina competencia entre alignfull y fullbleed
- ✅ Un solo sistema controla el ancho

## Orden de Ejecución Recomendado

1. **PASO 1:** Corregir page.css (fullbleed)
2. **PASO 2:** Corregir app.css (container)
3. **PASO 3:** Agregar sticky nav fix
4. **PROBAR:** Recarga dura en mobile
5. **PASO 4:** Solo si persiste, quitar alignfull

## Resultado Esperado

### ✅ Timeline móvil estable:
- Cards entran limpias en viewport
- Sin contenido corrido
- Botón negro bien posicionado

### ✅ Hero badge funcional:
- Badge "SIN PASAMONTAÑAS" visible
- Responsive en todos los anchos

### ✅ Sin doble contención:
- Un solo sistema controla el ancho
- WordPress y plugin cooperan

### ✅ Arquitectura profesional:
- page.css: integración WordPress
- app.css: layout interno
- Responsabilidades claras

## Veredicto Final

Esta corrección elimina la **raíz del problema**:
- Doble sistema de fullbleed
- Container con lógica compleja
- Sticky nav sin cierre

Deja una base **profesional y estable** para futuros desarrollos.
